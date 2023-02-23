<?php

namespace Kali\Unit\Tests\Roles;

use Anik\Laravel\Amqp\Facades\Amqp;
use Kali\MessageBroker\Messages\Data\Roles\RevokeRole;
use Kali\MessageBroker\Messages\Message;
use Orchestra\Testbench\TestCase;

class RevokeRoleDataTest extends TestCase
{
    public RevokeRole $testingData;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->testingData = new RevokeRole(users: ["413664", "412661"], role: "HR_GOD", client: "hr_internal");
    }

    public function test_make_message_with_role() {
        $message = new Message(job: "RevokeRoleJob", data: $this->testingData->toResource());

        $this->assertNotNull($message->getData());
    }

    public function test_create_role_data_from_json() {
        $testingData = RevokeRole::from('{ "users": ["413664", "412661"], "role": "HR_GOD", "client": "hr_internal"}');

        $testingResource = $testingData->toResource();

        $this->assertArrayHasKey("users", $testingResource);
        $this->assertArrayHasKey("role", $testingResource);
         $this->assertArrayHasKey("client", $testingResource);
    }
    
    public function test_convert_test_data_to_notification_data() {
        $testingNotification = $this->testingData->toNotificationData();

        $this->assertArrayHasKey("template", $testingNotification);
        $this->assertArrayHasKey("created_at", $testingNotification);
    }

    public function test_send_message_testing() {
        $message = new Message(job: "RevokeRoleJob", data: $this->testingData->toResource());

        Amqp::fake();

        Amqp::publish($message->toJson(), "testing");

        Amqp::assertPublished();
    }
}
