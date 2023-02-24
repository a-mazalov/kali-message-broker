<?php

namespace Kali\MessageBroker\Channels;

use denis660\Centrifugo\Centrifugo;
use Illuminate\Notifications\Notification;
use Exception;

class CentrifugoChannel
{
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return bool|null
     */
    public function send($notifiable, Notification $notification, Centrifugo $centrifugo)
    {
        if (!$channel = $notifiable->routeNotificationFor('centrifugo', $notification)) {
            return;
        }

        if (!is_string($channel)) {
            throw new Exception("Non string routing key not allowed");
        }

        $data = $this->buildPayload($channel, $notification);

        return $centrifugo->publish(config('broadcasting.connections.centrifugo.channel_prefix') . ':' . $channel, $data);
    }

    /**
     * Build an array payload for the DatabaseNotification Model.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return array
     */
    protected function buildPayload($notifiable, Notification $notification): array
    {
        if (method_exists($notification, "toCentrifugo")) {
            return $notification->toCentrifugo($notifiable);
        }

        throw new \RuntimeException('Notification is missing toCentrifugo method.');
    }

}