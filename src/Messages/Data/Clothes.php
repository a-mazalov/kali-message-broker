<?php

namespace Kali\MessageBroker\Messages\Data;
use DateTime;
use Illuminate\Support\Carbon;

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

    public static function from(string $data) {
        $params = (object) json_decode($data);

        return new self(
            username: $params->username,
            clothes: $params->clothes,
            dateChange: Carbon::parse($params->dateChange)
        );
    }
}