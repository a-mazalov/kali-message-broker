<?php

namespace Kali\MessageBroker\Messages\Data\Roles;

use Kali\MessageBroker\Messages\Data\Base;

/**
 * Выдача роли динамически
 * 
 * @property array $users - список username которым необходимо выдать роль
 * @property string $role - роль
 */
class GrantRole extends Base
{
    protected string $template = "role-grant";

    public function __construct(
        public array $users,
        public string $role,
        public string $client
    ) {
    }

    public function toResource(): array
    {
        return [
            "users" => $this->users,
            "role" => $this->role,
            "client" => $this->client
        ];
    }

    public static function from(string|array $data)
    {
        $params = self::prepareParamsFrom($data);

        return new self(
            users: $params->users,
            role: $params->role,
            client: $params->client,
        );
    }
}
