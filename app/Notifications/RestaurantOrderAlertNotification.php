<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RestaurantOrderAlertNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Order $order
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'status' => $this->order->order_status,
            'total_amount' => $this->order->total_amount,
            'message' => 'New order received.',
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(
                'New Order - ' . $this->order->order_number
            )
            ->greeting('New Order Received!')
            ->line(
                'A new order has been placed for your restaurant.'
            )
            ->line(
                'Order Number: ' . $this->order->order_number
            )
            ->line(
                'Total Amount: ₹' . $this->order->total_amount
            )
            ->line(
                'Status: ' . $this->order->order_status
            );
    }
}