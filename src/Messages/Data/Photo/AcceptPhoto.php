<?php

namespace Kali\MessageBroker\Messages\Data\Photo;

use Kali\MessageBroker\Messages\Data\Base;

/**
 * Уведомление об пройденой верификации фото
 * 
 * @property string $username - табельный пользователя
 * @property string $message - сообщение
 */
class AcceptPhoto extends Base
{
    protected string $template = "photo-accept";

    public function __construct(
        public string $username,
        public string $message,
    ) {
    }

    public function toResource(): array
    {
        return [
            "username" => $this->username,
            "message" => $this->message,
        ];
    }

    public static function from(string|array $data)
    {
        $params = self::prepareParamsFrom($data);

        return new self(
            username: $params->username,
            message: $params->message,
        );
    }
}
