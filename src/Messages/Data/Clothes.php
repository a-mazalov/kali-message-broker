<?php

namespace Kali\MessageBroker\Messages\Data;

class Clothes extends Base
{
    protected string $template = "clothes";

	public function __construct(
        public string $username,
        public array $clothes,
        public DateTime $dateChange
    ) {}

    public function toResource(): array {
        return [
            "username" => $this->username,
            "clothes" => $this->clothes,
            "dateChange" => $this->dateChange->toDateString()
        ];
    }
}