<?php

namespace Kali\Unit\Tests;

use Anik\Laravel\Amqp\Facades\Amqp;
use Kali\MessageBroker\Messages\Data\FixitJournal\FixitNewRequest;
use Kali\MessageBroker\Messages\Data\Tickets\TicketAnswer;
use Kali\MessageBroker\Messages\Message;
use Orchestra\Testbench\TestCase;

class FixitNewRequestTest extends TestCase
{
  public FixitNewRequest $testingData;

  /**
   * Setup the test environment.
   */
  protected function setUp(): void
  {
    parent::setUp();

    $this->testingData = new FixitNewRequest(username: "111111", requestId: "300", created_at: "2025-06-27 11:45:21");
  }

  public function test_make_message_with_salary()
  {
    $message = new Message(job: "FixitNewRequestJob", data: $this->testingData->toResource());

    $this->assertNotNull($message->getData());
  }

  public function test_create_test_data_from_json()
  {
    $testingData = FixitNewRequest::from('{ "username": "111111", "requestId": "300", "created_at":"2025-06-27 11:45:21" }');

    $testingResource = $testingData->toResource();

    $this->assertArrayHasKey("username", $testingResource);
    $this->assertArrayHasKey("requestId", $testingResource);
    $this->assertArrayHasKey("created_at", $testingResource);
  }

  public function test_convert_test_data_to_notification_data()
  {
    $testingNotification = $this->testingData->toNotificationData();

    $this->assertArrayHasKey("template", $testingNotification);
    $this->assertArrayHasKey("created_at", $testingNotification);
  }

  public function test_send_message_testing()
  {
    $message = new Message(job: "FixitNewRequestJob", data: $this->testingData->toResource());

    Amqp::fake();

    Amqp::publish($message->toJson(), "testing");

    Amqp::assertPublished();
  }
}
