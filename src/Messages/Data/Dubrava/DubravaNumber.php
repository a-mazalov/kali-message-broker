<?php

namespace Kali\MessageBroker\Messages\Data\Dubrava;

use Kali\MessageBroker\Messages\Data\Base;

class DubravaNumber extends Base
{
    protected string $template = "dubrava-number";

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