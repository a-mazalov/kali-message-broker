<?php

namespace Kali\MessageBroker\Messages;

use Kali\MessageBroker\Messages\Data\Base;
use Kali\MessageBroker\DataObjects\DataTransferObject;

class Message extends DataTransferObject
{
    public function __construct(
        public string $job,
        public array $data,
    ) {}

    /**
     * Получить имя задачи
     * 
     * @return string
     */
	public function getJob(): string
	{
		return $this->job;
	}

	/**
     * Задать имя задачи
     * 
	 * @param string $job 
	 * @return Message
	 */
	public function setJob(string $job): self
	{
		$this->job = $job;
		return $this;
	}

	/**
     * Получить данные
     * 
	 * @return array
	 */
	public function getData(): array
	{
		return $this->data;
	}

	/**
     * Задать данные
     * 
	 * @param array $data 
	 * @return Message
	 */
	public function setData(array $data): self
	{
		$this->data = $data;
		return $this;
	}
}