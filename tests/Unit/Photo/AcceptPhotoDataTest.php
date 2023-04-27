<?php

namespace Kali\Unit\Tests\Photo;

use Anik\Laravel\Amqp\Facades\Amqp;
use Kali\MessageBroker\Messages\Data\Photo\AcceptPhoto;
use Kali\MessageBroker\Messages\Message;
use Orchestra\Testbench\TestCase;

class AcceptPhotoDataTest extends TestCase
{
    public AcceptPhoto $testingData;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->testingData = new AcceptPhoto(username: "413664", message: "Ваше фото принято");
    }

    public function test_make_message_with_role() {
        $message = new Message(job: "AcceptPhoto", data: $this->testingData->toResource());

        $this->assertNotNull($message->getData());
    }

    public function test_create_role_data_from_json() {
        $testingData = AcceptPhoto::from('{ "username": "413664", "message": "Фото принято" }');

        $testingResource = $testingData->toResource();

        $this->assertArrayHasKey("username", $testingResource);
        $this->assertArrayHasKey("message", $testingResource);
    }
    
    public function test_convert_test_data_to_notification_data() {
        $testingNotification = $this->testingData->toNotificationData();

        $this->assertArrayHasKey("template", $testingNotification);
        $this->assertArrayHasKey("created_at", $testingNotification);
    }

    public function test_send_message_testing() {
        $message = new Message(job: "AcceptPhoto", data: $this->testingData->toResource());

        Amqp::fake();

        Amqp::publish($message->toJson(), "testing");

        Amqp::assertPublished();
    }
}
