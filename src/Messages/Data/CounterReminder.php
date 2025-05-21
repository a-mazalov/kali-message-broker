<?php

namespace Kali\MessageBroker\Messages\Data;

use Illuminate\Support\Carbon;
use Kali\MessageBroker\Messages\Data\Base;

class CounterReminder extends Base
{
    protected string $template = "counter-reminder";

    public function __construct(
        public string $username,
        public Carbon $date,
        public int $norm,
        public string $address
    ) {
    }

    public function toResource(): array
    {
        return [
            "username" => $this->username,
            "date" => $this->date->toDateString(),
            "norm" => $this->norm,
            "address" => $this->address
        ];
    }

    public static function from(string|array $data)
    {
        $params = self::prepareParamsFrom($data);

        return new self(
            username: $params->username,
            date: Carbon::parse($params->date),
            norm: $params->norm,
            address: $params->address
        );
    }
}