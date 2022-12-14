<?php

namespace Kali\Unit\Tests;

use Anik\Laravel\Amqp\Facades\Amqp;
use Kali\MessageBroker\Messages\Data\Tickets\TicketNew;
use Kali\MessageBroker\Messages\Message;
use Orchestra\Testbench\TestCase;

class TicketNewDataTest extends TestCase
{
    public TicketNew $testingData;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->testingData = new TicketNew(users: ["413664", "412661"], ticketId: "100");
    }

    public function test_make_message_with_ticket_new() {
        $message = new Message(job: "TicketNewJob", data: $this->testingData->toResource());

        $this->assertNotNull($message->getData());
    }

    public function test_create_ticket_new_data_from_json() {
        $testingData = TicketNew::from('{ "users": ["413664", "412661"], "ticketId": "200" }');

        $testingResource = $testingData->toResource();

        $this->assertArrayHasKey("users", $testingResource);
        $this->assertArrayHasKey("ticketId", $testingResource);
    }
    
    public function test_convert_test_data_to_notification_data() {
        $testingNotification = $this->testingData->toNotificationData();

        $this->assertArrayHasKey("template", $testingNotification);
        $this->assertArrayHasKey("created_at", $testingNotification);
    }

    public function test_send_message_testing() {
        $message = new Message(job: "TicketNewJob", data: $this->testingData->toResource());

        Amqp::fake();

        Amqp::publish($message->toJson(), "testing");

        Amqp::assertPublished();
    }
}
