<?php

namespace Kali\MessageBroker\Messages\Data\Safety;

use Illuminate\Support\Carbon;
use Kali\MessageBroker\Messages\Data\Base;

class SafetyManagerReminder extends Base
{
    protected string $template = "safety-manager-reminder";

	public function __construct(
        public string $username,
        public array $users,
        public Carbon $deadlineAt
    ) {}

    public function toResource(): array {
        return [
            "username" => $this->username,
            "users" => $this->users,
            "deadlineAt" => $this->deadlineAt->toDateString()
        ];
    }

    public static function from(string|array $data) {
        $params = self::prepareParamsFrom($data);

        return new self(
            username: $params->username,
            users: $params->users,
            deadlineAt: Carbon::parse($params->deadlineAt)
        );
    }
}