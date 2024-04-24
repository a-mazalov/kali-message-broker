<?php

namespace Kali\Unit\Tests;

use Kali\MessageBroker\Messages\Data\Post;
use Orchestra\Testbench\TestCase;

class PostDataTest extends TestCase
{
    public Post $testingData;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->testingData = new Post(id: 100, title: "Post title text test", body: "Post body text test");
    }

    public function test_create_test_data_from_json() {
        $testingData = Post::from('{ "id": "100", "title": "Post title text test", "body": "Post body text test" }');

        $testingResource = $testingData->toResource();

        $this->assertArrayHasKey("id", $testingResource);
        $this->assertArrayHasKey("title", $testingResource);
        $this->assertArrayHasKey("body", $testingResource);

    }
    
    public function test_convert_test_data_to_notification_data() {
        $testingNotification = $this->testingData->toNotificationData();

        $this->assertArrayHasKey("template", $testingNotification);
        $this->assertArrayHasKey("created_at", $testingNotification);
    }
}
