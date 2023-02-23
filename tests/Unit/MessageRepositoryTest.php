<?php

namespace Kali\Unit\Tests;

use Anik\Laravel\Amqp\Facades\Amqp;
use Kali\MessageBroker\DataObjects\MessageRelatedJob;
use Kali\MessageBroker\Messages\Data\Test;
use Kali\MessageBroker\Messages\Message;
use Kali\MessageBroker\Repositories\MessageJobRepository;
use Orchestra\Testbench\TestCase;

class MessageRepositoryTest extends TestCase
{
    public Test $testingData;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->testingData = new Test(email: "djoni@google.com", message: "Hello World!");
    }

    public function test_get_messages()
    {
        $messages = [
            [
                "name" => "TestingJob",
                "class" => null,
                "connection" => "redis",
                "queue" => "default",
                "delay" => 0,
            ],
        ];

        $repository = new MessageJobRepository($messages);

        $message = new Message(job: "TestingJob", data: $this->testingData->toResource());
        $relatedJob = $repository->findJobByMessage($message);

        $this->assertNotNull($relatedJob);
        $this->assertInstanceOf(MessageRelatedJob::class, $relatedJob);
    }

}