<?php

namespace Kali\MessageBroker\Repositories;

use Kali\MessageBroker\DataObjects\MessageRelatedJob;
use Kali\MessageBroker\Exceptions\MessageJobNotFound;
use Kali\MessageBroker\Messages\Message;
use Illuminate\Support\Collection;
use Illuminate\Support\ItemNotFoundException;

class MessageJobRepository
{
	protected Collection $messages;

	public function __construct(?array $messages = [])
	{
		// Получить доступные сообщения и задания из заданого массива или конфигурации
		$messageArray = $messages ?? config("messages.incoming", []);
		$this->messages = $this->getAvailableMessages($messageArray);
	}

	/**
	 * Возвращает харкодный массив доступных сообщений ожидаемых из внешней очереди
	 * Ключи - алиас, название команды которая передается сторонним приложение по протоколу amqp/stomp в очередь rabbitmq
	 * 
	 * @return Collection
	 */
	public function getAvailableMessages(array $message): Collection
	{
		return collect($message);
	}

	/**
	 * Найти задание по имени
	 * 
	 * @param Message $message
	 * @throws MessageJobNotFound 
	 * @return MessageRelatedJob
	 */
	public function findJobByMessage(Message $message)
	{
		try {
			$job = $this->messages->firstOrFail("name", $message->getJob());

			return new MessageRelatedJob(...$job);

		} catch (ItemNotFoundException $exception) {
			throw new MessageJobNotFound("Message not have available Jobs: " . $message->getJob());
		}
	}
}