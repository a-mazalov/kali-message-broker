<?php

namespace Kali\MessageBroker\Messages\Data;

use Kali\MessageBroker\Messages\Data\Base;

class BaseStatus extends Base
{
    public function __construct(
        public int $id,
        public string $title,
        public string $body,
        public string $username,
        public string $status,
    ) {
    }

    public function toResource(): array
    {
        return [
            "id" => $this->id,
            "title" => $this->title,
            "body" => $this->body,
            "username" => $this->username,
            "status" => $this->status,
        ];
    }

    public static function from(string|array $data)
    {
        $params = static::prepareParamsFrom($data);

        return new static(
            id: $params->id,
            title: $params->title,
            body: $params->body,
            username: $params->username,
            status: $params->status,
        );
    }
}