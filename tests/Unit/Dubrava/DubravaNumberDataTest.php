<?php

namespace Kali\Unit\Tests;

use Anik\Laravel\Amqp\Facades\Amqp;
use Kali\MessageBroker\Messages\Data\Dubrava\DubravaNumber;
use Kali\MessageBroker\Messages\Message;
use Orchestra\Testbench\TestCase;

class DubravaNumberDataTest extends TestCase
{
    public DubravaNumber $testingData;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->testingData = new DubravaNumber(id: 1, title: "Dubrava Test Number", body: "Db number", username: "111111");
    }

    public function test_make_message_with_dubrava_number() {
        $message = new Message(job: "DubravaUpdateNumber", data: $this->testingData->toResource());

        $this->assertNotNull($message->getData());
    }

    public function test_create_test_data_from_json() {
        $testingData = DubravaNumber::from('{ "id": "1", "title": "Dubrava Test json", "body": "body", "username": "111111" }');

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
        $message = new Message(job: "DubravaUpdateNumber", data: $this->testingData->toResource());

        Amqp::fake();

        Amqp::publish($message->toJson(), "testing");

        Amqp::assertPublished();
    }
}
