<?php

namespace Kali\MessageBroker\Messages\Data;
use DateTime;
use Illuminate\Support\Carbon;

class Test extends Base
{
    protected string $template = "testing";

	public function __construct(
        public string $email,
        public string $message,
    ) {}

    public function toResource(): array {
        return [
            "email" => $this->email,
            "message" => $this->message,
        ];
    }

    public static function from(string|array $data) {
        $params = self::prepareParamsFrom($data);

        return new self(
            email: $params->email,
            message: $params->message,
        );
    }
}