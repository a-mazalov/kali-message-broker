<?php

namespace Kali\MessageBroker\DataObjects;

class MessageRelatedJob extends DataTransferObject
{
	public function __construct(
		public string $name,
		public mixed $class,
		public string $connection,
		public string $queue,
		public int $delay
	) {}
}