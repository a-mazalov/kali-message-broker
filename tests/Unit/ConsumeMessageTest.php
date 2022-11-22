<?php

namespace Kali\Unit\Tests;

use Anik\Laravel\Amqp\Facades\Amqp;
use Kali\MessageBroker\Messages\Data\Clothes;
use Kali\MessageBroker\Messages\Message;
use Orchestra\Testbench\TestCase;

class ConsumeMessageTest extends TestCase
{

    public function test_consume_message_correct()
    {
        $message = (new Message(job: "TestMessage", data: []));
        $messageArray = $message->toArray();

        $this->assertArrayHasKey("job", $messageArray);
        $this->assertArrayHasKey("data", $messageArray);
    }

    public function test_make_message() {
        $payload = new Clothes(username: "djoni", clothes: [["name" => "boots"]]);

        $message = new Message(job: "MyJobTest", data: $payload->toMessageData(true));
        
        $this->assertTrue(true);
    }

    public function test_send_message() {
        $payload = new Clothes(username: "djoni", clothes: [["name" => "boots"]]);

        $message = new Message(job: "MyJobTest", data: $payload->toMessageData(true));

        Amqp::fake();

        Amqp::publish($message->toJson(), "testing");

        Amqp::assertPublished();
    }
}
