<?php

namespace Kali\Unit\Tests;

use Anik\Laravel\Amqp\Facades\Amqp;
use Kali\MessageBroker\Messages\Data\Kindergarten\KindergartenDelete;
use Kali\MessageBroker\Messages\Message;
use Orchestra\Testbench\TestCase;

class KindergatenDeleteDataTest extends TestCase
{
    public KindergartenDelete $testingData;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->testingData = new KindergartenDelete(id: 1, title: "Kindergarten Test Delete", body: "body test", username: "111111");
    }

    public function test_make_message_with_kg_delete() {
        $message = new Message(job: "KindergartenDelete", data: $this->testingData->toResource());

        $this->assertNotNull($message->getData());
    }

    public function test_create_test_data_from_json() {
        $testingData = KindergartenDelete::from('{ "id": "1", "title": "Kindergarten Test json", "body": "body test", "username": "111111" }');

        $testingResource = $testingData->toResource();

        $this->assertArrayHasKey("id", $testingResource);
        $this->assertArrayHasKey("title", $testingResource);
        $this->assertArrayHasKey("body", $testingResource);
        $this->assertArrayHasKey("username", $testingResource);
    }
    
    public function test_convert_test_data_to_notification_data() {
        $testingNotification = $this->testingData->toNotificationData();

        $this->assertArrayHasKey("template", $testingNotification);
        $this->assertArrayHasKey("created_at", $testingNotification);
    }

    public function test_send_message_testing() {
        $message = new Message(job: "KindergartenDelete", data: $this->testingData->toResource());

        Amqp::fake();

        Amqp::publish($message->toJson(), "testing");

        Amqp::assertPublished();
    }
}
