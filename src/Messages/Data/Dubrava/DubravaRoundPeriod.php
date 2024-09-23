<?php

namespace Kali\MessageBroker\Messages\Data\Dubrava;

use Kali\MessageBroker\Messages\Data\Base;

class DubravaRoundPeriod extends Base
{
    protected string $template = "dubrava-round-period";

	public function __construct(
        public int $id,
        public string $title,
        public string $body,
        public string $type,
    ) {}

    public function toResource(): array {
        return [
            "id" => $this->id,
            "title" => $this->title,
            "body" => $this->body,
            "type" => $this->type,
        ];
    }

    public static function from(string|array $data) {
        $params = self::prepareParamsFrom($data);

        return new self(
            id: $params->id,
            title: $params->title,
            body: $params->body,
            type: $params->type,
        );
    }
}