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

    public function test_send_message() 
    {
        $message = new Message(job: "MyJobTest", data: [ "username" => "Djoni" ]);

        Amqp::fake();

        Amqp::publish($message->toJson(), "testing");

        Amqp::assertPublished();
    }
}
