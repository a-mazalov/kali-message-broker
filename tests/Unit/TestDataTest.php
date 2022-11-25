<?php

namespace Kali\Unit\Tests;

use Anik\Laravel\Amqp\Facades\Amqp;
use Kali\MessageBroker\Messages\Data\Test;
use Kali\MessageBroker\Messages\Message;
use Orchestra\Testbench\TestCase;

class TestDataTest extends TestCase
{
    public Test $testingData;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->testingData = new Test(email: "djoni@google.com", message: "Hello World!");
    }

    public function test_make_message_with_salary() {
        $message = new Message(job: "TestingJob", data: $this->testingData->toResource());

        $this->assertNotNull($message->getData());
    }

    public function test_create_test_data_from_json() {
        $testingData = Test::from('{ "email": "djoni@google.com", "message": "Hello World!" }');

        $testingResource = $testingData->toResource();

        $this->assertArrayHasKey("email", $testingResource);
        $this->assertArrayHasKey("message", $testingResource);
    }
    
    public function test_convert_test_data_to_notification_data() {
        $testingNotification = $this->testingData->toNotificationData();

        $this->assertArrayHasKey("template", $testingNotification);
        $this->assertArrayHasKey("created_at", $testingNotification);
    }

    public function test_send_message_testing() {
        $message = new Message(job: "TestingJob", data: $this->testingData->toResource());

        Amqp::fake();

        Amqp::publish($message->toJson(), "testing");

        Amqp::assertPublished();
    }
}
