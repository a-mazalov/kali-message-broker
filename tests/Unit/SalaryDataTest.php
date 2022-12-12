<?php

namespace Kali\Unit\Tests;

use Anik\Laravel\Amqp\Facades\Amqp;
use DateTime;
use Illuminate\Support\Carbon;
use Kali\MessageBroker\Messages\Data\Salary;
use Kali\MessageBroker\Messages\Message;
use Orchestra\Testbench\TestCase;

class SalaryDataTest extends TestCase
{
    public Salary $salary;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->salary = new Salary(salaryDate: now()->format("Y-m-d"));
    }

    public function test_make_message_with_salary() {
        $message = new Message(job: "Salary", data: $this->salary->toResource());

        $this->assertNotNull($message->getData());
    }

    public function test_create_salary_from_json() {
        $salary = Salary::from('{ "salaryDate": "2022-01-01" }');

        $salaryResource = $salary->toResource();

        $dateFormatTest = Carbon::createFromFormat("M Y", $salary->salaryMonthYear);

        $this->assertArrayHasKey("salaryDate", $salaryResource);
        $this->assertArrayHasKey("salaryMonthYear", $salaryResource);
        $this->assertTrue($dateFormatTest->isoFormat("MMMM Y") === $salary->salaryMonthYear);
    }
    
    public function test_convert_salary_to_notification_data() {
        $salaryNotification = $this->salary->toNotificationData();

        $this->assertArrayHasKey("template", $salaryNotification);
        $this->assertArrayHasKey("created_at", $salaryNotification);
    }

    public function test_send_message_salary() {
        $message = new Message(job: "SalaryComplete", data: $this->salary->toResource());

        Amqp::fake();

        Amqp::publish($message->toJson(), "testing");

        Amqp::assertPublished();
    }
}
