<?php

namespace Kali\MessageBroker\Messages\Data\Statements;
use Kali\MessageBroker\Messages\Data\Base;

class HostessDayStatus extends Base
{
    protected string $template  = "hostessday-status";

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
        $params = self::prepareParamsFrom($data);

        return new self(
            id: $params->id,
            title: $params->title,
            body: $params->body,
            username: $params->username,
            status: $params->status,
        );
    }
}