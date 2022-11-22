<?php

namespace Kali\MessageBroker\Interfaces;

interface MessageDataInterface
{
	/**
	 * Необходимо определить возвращаемые атрибуты
	 * 
	 * @return array
	 */
	public function toResource(): array;

	/**
	 * Метод преобразует в структуру вида key => value, без вложенностей.
	 * 	Преобразование value массивов в json строку
	 * 
     * @api \Kreait\Firebase\Messaging\MessageData
     * @return array
     */
	public function toMessageData(): array;
}