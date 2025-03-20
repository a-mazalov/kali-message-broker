<?php

namespace Kali\Unit\Tests;

use Anik\Laravel\Amqp\Facades\Amqp;
use Kali\MessageBroker\Messages\Data\CourseEnrollment;
use Kali\MessageBroker\Messages\Data\Statements\MothersDayStatus;
use Kali\MessageBroker\Messages\Message;
use Orchestra\Testbench\TestCase;

class CourseEnrollmentTest extends TestCase
{
    public CourseEnrollment $testingData;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->freezeTime();

        $this->testingData = new CourseEnrollment(id: 1, username: 123321, courseName: 'Course name test', startDate: now()->startOfDay(), endDate: null);
    }

    public function test_instance()
    {
        $data = CourseEnrollment::from('{ "id": "1", "username": "123321", "courseName": "Course name test", "startDate": "' . now()->startOfDay()->toDateTimeString() . '", "endDate": null }');

        $this->assertInstanceOf(CourseEnrollment::class, $data);
    }

    public function test_make_message_with_kg_status()
    {
        $message = new Message(job: "CourseEnrollment", data: $this->testingData->toResource());

        $this->assertNotNull($message->getData());
    }

    public function test_create_test_data_from_json()
    {
        $testingData = CourseEnrollment::from('{ "id": "1", "username": "123321", "courseName": "Course name test", "startDate": "' . now()->startOfDay()->toDateTimeString() . '", "endDate": null }');

        $testingResource = $testingData->toResource();

        $this->assertArrayHasKey("id", $testingResource);
        $this->assertArrayHasKey("username", $testingResource);
        $this->assertArrayHasKey("courseName", $testingResource);
        $this->assertArrayHasKey("startDate", $testingResource);
        $this->assertArrayHasKey("endDate", $testingResource);
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
            "username" => "123321",
            "courseName" => "Course name test",
            "startDate" => now()->startOfDay()->format('d.m.Y'),
            "endDate" => null,
            "template" => "course-enrollment",
            "created_at" => now()->toDateTimeLocalString()
        ], $testingNotification);
    }

    public function test_send_message_testing()
    {
        $message = new Message(job: "CourseEnrollment", data: $this->testingData->toResource());

        Amqp::fake();

        Amqp::publish($message->toJson(), "testing");

        Amqp::assertPublished();
    }
}
