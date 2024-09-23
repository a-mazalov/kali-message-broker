<?php

namespace Kali\Unit\Tests;

use Anik\Laravel\Amqp\Facades\Amqp;
use Kali\MessageBroker\Messages\Data\Dubrava\DubravaRoundPeriod;
use Kali\MessageBroker\Messages\Message;
use Orchestra\Testbench\TestCase;

class DubravaRoundPeriodDataTest extends TestCase
{
    public DubravaRoundPeriod $testingData;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->testingData = new DubravaRoundPeriod(id: 1, title: "Dubrava Test round", body: "Db round", type: "now");
    }

    public function test_make_message_with_dubrava_round() {
        $message = new Message(job: "DubravaRoundPeriod", data: $this->testingData->toResource());

        $this->assertNotNull($message->getData());
    }

    public function test_create_test_data_from_json() {
        $testingData = DubravaRoundPeriod::from('{ "id": "1", "title": "Dubrava Test json", "body": "body", "type": "now" }');

        $testingResource = $testingData->toResource();

        $this->assertArrayHasKey("id", $testingResource);
        $this->assertArrayHasKey("title", $testingResource);
        $this->assertArrayHasKey("body", $testingResource);
        $this->assertArrayHasKey("type", $testingResource);
    }
    
    public function test_convert_test_data_to_notification_data() {
        $testingNotification = $this->testingData->toNotificationData();

        $this->assertArrayHasKey("template", $testingNotification);
        $this->assertArrayHasKey("created_at", $testingNotification);
    }

    public function test_send_message_testing() {
        $message = new Message(job: "DubravaRoundPeriod", data: $this->testingData->toResource());

        Amqp::fake();

        Amqp::publish($message->toJson(), "testing");

        Amqp::assertPublished();
    }
}
