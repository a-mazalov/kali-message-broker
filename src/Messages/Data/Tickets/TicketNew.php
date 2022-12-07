<?php

namespace Kali\MessageBroker\Messages\Data\Tickets;

use Kali\MessageBroker\Messages\Data\Base;

/**
 * Сообщение об новом обращении
 * 
 * @property array $users - список username которым необходимо отправить уведомление
 */
class TicketNew extends Base
{
    protected string $template = "ticket-new";

	public function __construct(
        public array $users,
        public string $ticketId
    ) {}

    public function toResource(): array {
        return [
            "users" => $this->users,
            "ticketId" => $this->ticketId,
        ];
    }

    public static function from(string|array $data) {
        $params = self::prepareParamsFrom($data);

        return new self(
            users: $params->users,
            ticketId: $params->ticketId,
        );
    }
}