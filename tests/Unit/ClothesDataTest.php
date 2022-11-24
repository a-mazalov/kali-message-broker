<?php

namespace Kali\Unit\Tests;

use Anik\Laravel\Amqp\Facades\Amqp;
use Kali\MessageBroker\Messages\Data\Clothes;
use Kali\MessageBroker\Messages\Message;
use Orchestra\Testbench\TestCase;

class ClothesDataTest extends TestCase
{
    public Clothes $clothes;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->clothes = new Clothes(username: "djoni", clothes: [["name" => "boots"]], dateChange: now());
    }


    public function test_make_message_with_clothes() {
        $message = new Message(job: "MyJobTest", data: $this->clothes->toResource());

        $this->assertNotNull($message->getData());
    }

    public function test_create_clothes_from_json() {
        $clothes = Clothes::from($this->clothes->toJson());

        $clothesResource = $clothes->toResource();

        $this->assertArrayHasKey("username", $clothesResource);
        $this->assertArrayHasKey("clothes", $clothesResource);
        $this->assertArrayHasKey("dateChange", $clothesResource);
        $this->assertInstanceOf(\DateTime::class, $clothes->dateChange);
    }
    
    public function test_convert_clothes_to_notification_data() {
        $clothesNotification = $this->clothes->toNotificationData();

        $this->assertArrayHasKey("template", $clothesNotification);
        $this->assertArrayHasKey("created_at", $clothesNotification);
    }

    public function test_convert_clothes_to_notification_data_flat() {
        $clothesNotificationFlat = $this->clothes->toNotificationData(true);

        $this->assertJson($clothesNotificationFlat['clothes']);
    }

    public function test_send_message_clothes() {
        $message = new Message(job: "MyJobTest", data: $this->clothes->toResource());

        Amqp::fake();

        Amqp::publish($message->toJson(), "testing");

        Amqp::assertPublished();
    }
}
