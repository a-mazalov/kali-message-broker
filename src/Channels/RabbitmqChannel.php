<?php

namespace Kali\MessageBroker\Channels;

use Anik\Amqp\Exchanges\Exchange;
use Anik\Laravel\Amqp\Facades\Amqp;
use Illuminate\Notifications\Notification;
use Kali\MessageBroker\Messages\Message;
use Exception;

class RabbitmqChannel
{
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return bool|null
     */
    public function send($notifiable, Notification $notification)
    {
        /**
         * Получение наименование очереди из роута 
         * 
         * Если очереди не указан, пропустить отправку.
         * Пропуск необходим для использования уведомлений через Notification::route по определенным каналам
         */
        if (!$routing_key = $notifiable->routeNotificationFor('rabbitmq', $notification)) {
            return;
        }

        if (!is_string($routing_key)) {
            throw new Exception("Non string routing key not allowed");
        }

        $message = $this->buildPayload($routing_key, $notification);

        $exchange = new Exchange(
            config("amqp.connections.rabbitmq.exchange.name"), 
            config("amqp.connections.rabbitmq.exchange.type")
        );

        return Amqp::publish($message->toJson(), $routing_key, $exchange);
    }

    /**
     * Build an array payload for the DatabaseNotification Model.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return Message
     */
    protected function buildPayload($notifiable, Notification $notification): Message
    {
        if (method_exists($notification, "toRabbitmq")) {
            return $notification->toRabbitmq($notifiable);
        }

        throw new \RuntimeException('Notification is missing toRabbitmq method.');
    }

}