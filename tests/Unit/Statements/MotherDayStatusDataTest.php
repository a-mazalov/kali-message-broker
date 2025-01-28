<?php

namespace Kali\Unit\Tests;

use Anik\Laravel\Amqp\Facades\Amqp;
use Kali\MessageBroker\Messages\Data\Statements\MotherDayStatus;
use Kali\MessageBroker\Messages\Message;
use Orchestra\Testbench\TestCase;

class MotherDayStatusDataTest extends TestCase
{
    public MotherDayStatus $testingData;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->freezeTime();

        $this->testingData = new MotherDayStatus(id: 1, title: "MotherDay Test Update Status", body: "body test", username: "111111", status: "new");
    }

    public function test_make_message_with_kg_status()
    {
        $message = new Message(job: "MotherDayUpdateStatus", data: $this->testingData->toResource());

        $this->assertNotNull($message->getData());
    }

    public function test_create_test_data_from_json()
    {
        $testingData = MotherDayStatus::from('{ "id": "1", "title": "MotherDay Test json", "body": "body test", "username": "111111", "status": "new" }');

        $testingResource = $testingData->toResource();

        $this->assertArrayHasKey("id", $testingResource);
        $this->assertArrayHasKey("title", $testingResource);
        $this->assertArrayHasKey("body", $testingResource);
        $this->assertArrayHasKey("username", $testingResource);
        $this->assertArrayHasKey("status", $testingResource);
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
            "id" => 1,
            "title" => "MotherDay Test Update Status",
            "body" => "body test",
            "username" => "111111",
            "status" => "new",
            "template" => "mother-day-status",
            "created_at" => now()->toDateTimeLocalString()
        ], $testingNotification);
    }

    public function test_send_message_testing()
    {
        $message = new Message(job: "MotherDayUpdateStatus", data: $this->testingData->toResource());

        Amqp::fake();

        Amqp::publish($message->toJson(), "testing");

        Amqp::assertPublished();
    }
}
