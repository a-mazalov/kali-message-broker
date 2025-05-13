<?php

namespace Kali\Unit\Tests\Safety;

use Anik\Laravel\Amqp\Facades\Amqp;
use Kali\MessageBroker\Messages\Data\Safety\SafetyUserReminder;
use Kali\MessageBroker\Messages\Message;
use Orchestra\Testbench\TestCase;

class SafetyUserDataTest extends TestCase
{
    public SafetyUserReminder $testingData;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->freezeTime();

        $this->testingData = new SafetyUserReminder(username: '000111', safetyName: 'Производство', deadlineAt: now());
    }

    public function test_make_message() {
        $message = new Message(job: "SafetyUserReminder", data: $this->testingData->toResource());

        $this->assertNotNull($message->getData());
    }

    public function test_create_test_data_from_json() {
        $testingData = SafetyUserReminder::from('{ "username": "1111111", "safetyName": "Безопасность труда", "deadlineAt": "2025-01-01" }');

        $testingResource = $testingData->toNotificationData();

        $this->assertEquals([
            "username" => "1111111",
            "safetyName" => "Безопасность труда",
            "deadlineAt" => "2025-01-01",
            "template" => "safety-user-reminder",
            "created_at" => now()->toDateTimeLocalString()
        ], $testingResource);
    }
    

    public function test_send_message_testing() {
        $message = new Message(job: "SafetyUserReminder", data: $this->testingData->toResource());

        Amqp::fake();

        Amqp::publish($message->toJson(), "testing");

        Amqp::assertPublished($message->toJson());
    }
}
