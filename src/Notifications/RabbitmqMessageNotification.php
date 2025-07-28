<?php

namespace Kali\MessageBroker\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Kali\MessageBroker\Channels\RabbitmqChannel;
use Kali\MessageBroker\Messages\Message;

class RabbitmqMessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public string $job;
    public array $data;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(string $job, array $data)
    {
        $this->job = $job;
        $this->data = $data;
    }

    /**
     * Determine the time at which the job should timeout.
     * 
     * @return \DateTime
     */
    public function retryUntil(): \DateTime
	{
		return now()->addMinutes(30);
	}

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [RabbitmqChannel::class];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return Message
     */
    public function toRabbitmq($notifiable)
    {
        return new Message(job: $this->job, data: $this->data);
    }
}
