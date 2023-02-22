<?php

namespace Kali\Unit\Tests;

use Anik\Laravel\Amqp\Facades\Amqp;
use Kali\MessageBroker\Messages\Data\Role;
use Kali\MessageBroker\Messages\Message;
use Orchestra\Testbench\TestCase;

class RoleDataTest extends TestCase
{
    public Role $testingData;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->testingData = new Role(users: ["413664", "412661"], role: "HR_GOD", client: "hr_internaal");
    }

    public function test_make_message_with_role() {
        $message = new Message(job: "GiveRoleJob", data: $this->testingData->toResource());

        $this->assertNotNull($message->getData());
    }

    public function test_create_role_data_from_json() {
        $testingData = Role::from('{ "users": ["413664", "412661"], "role": "HR_GOD", "client": "hr_internal"}');

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
        $message = new Message(job: "GiveRoleJob", data: $this->testingData->toResource());

        Amqp::fake();

        Amqp::publish($message->toJson(), "testing");

        Amqp::assertPublished();
    }
}
