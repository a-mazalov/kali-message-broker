<?php

namespace Kali\Unit\Tests\Safety;

use Anik\Laravel\Amqp\Facades\Amqp;
use Kali\MessageBroker\Messages\Data\Safety\SafetyManagerReminder;
use Kali\MessageBroker\Messages\Message;
use Orchestra\Testbench\TestCase;

class SafetyManagerDataTest extends TestCase
{
    public SafetyManagerReminder $testingData;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->freezeTime();

        $this->testingData = new SafetyManagerReminder(username: '000111', users: ['222222','033000'], deadlineAt: now());
    }

    public function test_make_message() {
        $message = new Message(job: "SafetyManagerReminder", data: $this->testingData->toResource());

        $this->assertNotNull($message->getData());
    }

    public function test_create_test_data_from_json() {
        $testingData = SafetyManagerReminder::from('{ "username": "1111111", "users": ["444444", "555555"], "deadlineAt": "2025-01-01" }');

        $testingResource = $testingData->toNotificationData();

        $this->assertEquals([
            "username" => "1111111",
            "users" => ["444444", "555555"],
            "deadlineAt" => "2025-01-01",
            "template" => "safety-manager-reminder",
            "created_at" => now()->toDateTimeLocalString()
        ], $testingResource);
    }

    public function test_create_test_data_from_json_flat() {
        $testingData = SafetyManagerReminder::from('{ "username": "1111111", "users": ["444444", "555555"], "deadlineAt": "2025-01-01" }');

        $testingResource = $testingData->toNotificationData(true);

        $this->assertEquals([
            "username" => "1111111",
            "users" => '["444444","555555"]',
            "deadlineAt" => "2025-01-01",
            "template" => "safety-manager-reminder",
            "created_at" => now()->toDateTimeLocalString()
        ], $testingResource);
    }
    

    public function test_send_message_testing() {
        $message = new Message(job: "SafetyManagerReminder", data: $this->testingData->toResource());

        Amqp::fake();

        Amqp::publish($message->toJson(), "testing");

        Amqp::assertPublished($message->toJson());
    }
}
