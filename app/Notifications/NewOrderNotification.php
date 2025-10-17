<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewOrderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $order;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Nouvelle commande #' . $this->order->id)
                    ->greeting('Bonjour ' . $notifiable->name . ' !')
                    ->line('Une nouvelle commande a été passée par ' . $this->order->customer_name . '.')
                    ->line('Commande #' . $this->order->id . ' - Total: ' . $this->order->formatted_total)
                    ->action('Voir la commande', url('/dashboard/ecommerce/orders/' . $this->order->id))
                    ->line('Merci d\'utiliser notre plateforme !');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'customer_name' => $this->order->customer_name,
            'customer_email' => $this->order->customer_email,
            'total' => $this->order->total,
            'formatted_total' => $this->order->formatted_total,
            'items_count' => $this->order->total_items,
            'order_url' => '/dashboard/ecommerce/orders/' . $this->order->id,
            'message' => 'Nouvelle commande #' . $this->order->id . ' de ' . $this->order->customer_name . ' (' . $this->order->formatted_total . ')',
        ];
    }
}

