<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DeliveryAssignmentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Order $order
    ) {
    }

    public function via(object $_notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toDatabase(object $_notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'message' => 'A new delivery has been assigned to you.',
            'status' => $this->order->order_status,
        ];
    }

    public function toMail(object $_notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(
                'New Delivery Assignment - ' . $this->order->order_number
            )
            ->greeting('New Delivery Assignment')
            ->line(
                'A new order has been assigned to you.'
            )
            ->line(
                'Order Number: ' . $this->order->order_number
            )
            ->line(
                'Status: ' . $this->order->order_status
            );
    }
}