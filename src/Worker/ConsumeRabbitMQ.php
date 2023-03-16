<?php

namespace Apps\ConsumerQueue\Worker;

use Illuminate\Support\Facades\Log;
use Kali\MessageBroker\Messages\Message;
use Kali\MessageBroker\Repositories\MessageJobRepository;
use VladimirYuldashev\LaravelQueueRabbitMQ\Queue\Jobs\RabbitMQJob;

use Exception;

class ConsumeRabbitMQ extends RabbitMQJob
{
    /**
     * Обработчик внешних входящий сообщений RabbitMQ
     * 
     * Переопределенный метод fire из Illuminate\Queue\Jobs\Job.php
     * @return void
     */
    public function fire()
    {
        $messagesRepository = new MessageJobRepository();
        
        try {
            $params = $this->payload();

            $message = new Message(...$params);
            $relatedJob = $messagesRepository->findJobByMessage($message);

            $this->logConsumeInfo($message, $relatedJob);

            ($relatedJob->class)::dispatch($message->getData())
                ->onConnection($relatedJob->connection)
                ->onQueue($relatedJob->queue)
                ->delay($relatedJob->delay);

        } catch (Exception $e) {
            /**
             * Данный воркер не умеет адекватно работать с $this->fail(), по этому не выдает Failed статус
             * Так как пытается поместить проваленное задание в failed_table, даже если установить failed_driver = null,
             * то выдаст Processing, Failed, Processed.
             * 
             * Перехватываем ошибку создания связанного задания и отправляем логи самостоятельно
             */

            Log::error($e);
        } finally {
            $this->delete();
        }
    }

    /**
     * Логгирование обработки внешней очереди
     * TODO: В будущем убрать, когда протестируем систему, или настроить на отдельный индекс
     * @param mixed $message
     * @param mixed $relatedJob
     * @return void
     */
    public function logConsumeInfo($message, $relatedJob)
    {
        Log::info("Consume External Queue RabbitMQ: " . $message->getJob(), [
            "payload" => $message,
            "relatedJob" => $relatedJob->toJson()
        ]);
    }
}