<?php

namespace Kali\Unit\Tests;

use Anik\Laravel\Amqp\Facades\Amqp;
use Kali\MessageBroker\Messages\Data\CounterReminder;
use Kali\MessageBroker\Messages\Message;
use Orchestra\Testbench\TestCase;

class CounterReminderTest extends TestCase
{
    public CounterReminder $testingData;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->freezeTime();

        $this->testingData = new CounterReminder(username: 123321, date: now()->startOfDay(), norm: 2, address:"ул. Пушкина 12 д. 33");
    }

    public function test_instance()
    {
        $data = CounterReminder::from('{ "username": "123321", "date": "' . now()->startOfDay()->toDateTimeString() . '", "norm": 2, "address": "ул. Пушкина 12 д. 33"}');

        $this->assertInstanceOf(CounterReminder::class, $data);
    }

    public function test_make_message_with_kg_status()
    {
        $message = new Message(job: "CounterReminder", data: $this->testingData->toResource());

        $this->assertNotNull($message->getData());
    }

    public function test_create_test_data_from_json()
    {
        $testingData = CounterReminder::from('{ "username": "123321", "date": "' . now()->startOfDay()->toDateTimeString() . '", "norm": 2, "address": "ул. Пушкина 12 д. 33"}');

        $testingResource = $testingData->toResource();

        $this->assertArrayHasKey("username", $testingResource);
        $this->assertArrayHasKey("date", $testingResource);
        $this->assertArrayHasKey("norm", $testingResource);
        $this->assertArrayHasKey("address", $testingResource);
    }

    public function test_convert_test_data_to_notification_data()
    {
        $testingNotification = $this->testingData->toNotificationData();

        $this->assertArrayHasKey("template", $testingNotification);
        $this->assertArrayHasKey("created_at", $testingNotification);
    }

    public function test_convert_test_data_to_notification_data_flat()
    {
        $testingNotification = $this->testingData->toNotificationData(true);

        $this->assertEquals([
            "username" => "123321",
            "date" => now()->toDateTimeLocalString(),
            "norm" => 2,
            "address" => "ул. Пушкина 12 д. 33",
            "template" => "counter-reminder",
            "created_at" => now()->toDateTimeLocalString()
        ], $testingNotification);
    }

    public function test_send_message_testing()
    {
        $message = new Message(job: "CounterReminder", data: $this->testingData->toResource());

        Amqp::fake();

        Amqp::publish($message->toJson(), "testing");

        Amqp::assertPublished();
    }
}
