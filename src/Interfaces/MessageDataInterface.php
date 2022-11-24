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
	 * На основе метода toResource возвращает json строку
	 * 
	 * @return string
	 */
	public function toJson(): string;

	/**
	 * Метод преобразует в структуру вида key => value, без вложенностей.
	 * 	Преобразование value массивов в json строку
	 * 
     * @api \Kreait\Firebase\Messaging\MessageData
     * @return array
     */
	public function toNotificationData(): array;
}