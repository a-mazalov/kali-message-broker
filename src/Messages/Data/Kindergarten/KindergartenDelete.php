<?php

namespace Kali\MessageBroker\Messages\Data\Kindergarten;

use Kali\MessageBroker\Messages\Data\Base;

class KindergartenDelete extends Base
{
    protected string $template = "kindergarten-delete";

	public function __construct(
        public int $id,
        public string $title,
        public string $body,
        public string $username,
    ) {}

    public function toResource(): array {
        return [
            "id" => $this->id,
            "title" => $this->title,
            "body" => $this->body,
            "username" => $this->username,
        ];
    }

    public static function from(string|array $data) {
        $params = self::prepareParamsFrom($data);

        return new self(
            id: $params->id,
            title: $params->title,
            body: $params->body,
            username: $params->username,
        );
    }
}