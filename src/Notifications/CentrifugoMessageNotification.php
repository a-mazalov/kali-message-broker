<?php

namespace Kali\MessageBroker\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Kali\MessageBroker\Channels\CentrifugoChannel;

class CentrifugoMessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public array $data;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [CentrifugoChannel::class];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toCentrifugo($notifiable)
    {
        return $this->data;
    }
}
