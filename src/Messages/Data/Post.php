<?php

namespace Kali\MessageBroker\Messages\Data;

class Post extends Base
{
    protected string $template = "post";

    public function __construct(
        public int|string $id,
        public string $title,
        public string $body
    ) {
    }

    public function toResource(): array
    {
        return [
            "id" => $this->id,
            "title" => $this->title,
            "body" => $this->body,
        ];
    }

    public static function from(string|array $data)
    {
        $params = self::prepareParamsFrom($data);

        return new self(
            id: $params->title,
            title: $params->title,
            body: $params->body,
        );
    }
}