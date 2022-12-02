<?php

namespace Kali\MessageBroker\Messages\Data\Tickets;

use Kali\MessageBroker\Messages\Data\Base;

class TicketNew extends Base
{
    protected string $template = "ticket-new";

	public function __construct(
        public string $ticketId
    ) {}

    public function toResource(): array {
        return [
            "ticketId" => $this->ticketId,
        ];
    }

    public static function from(string|array $data) {
        $params = self::prepareParamsFrom($data);

        return new self(
            ticketId: $params->ticketId,
        );
    }
}