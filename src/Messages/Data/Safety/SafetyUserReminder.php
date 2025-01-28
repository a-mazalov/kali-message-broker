<?php

namespace Kali\MessageBroker\Messages\Data\Safety;

use Illuminate\Support\Carbon;
use Kali\MessageBroker\Messages\Data\Base;

class SafetyUserReminder extends Base
{
    protected string $template = "safety-user-reminder";

	public function __construct(
        public string $username,
        public string $safetyName,
        public Carbon $deadlineAt
    ) {}

    public function toResource(): array {
        return [
            "username" => $this->username,
            "safetyName" => $this->safetyName,
            "deadlineAt" => $this->deadlineAt->toDateString()
        ];
    }

    public static function from(string|array $data) {
        $params = self::prepareParamsFrom($data);

        return new self(
            username: $params->username,
            safetyName: $params->safetyName,
            deadlineAt: Carbon::parse($params->deadlineAt)
        );
    }
}