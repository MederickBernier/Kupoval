<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderConfirmationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function build()
    {
        return $this->subject(__('emails.order.confirmation_subject', ['order_id' => $this->order->id]))
            ->view('emails.orders.confirmation')
            ->with([
                'greeting' => __('emails.order.confirmation_greeting', ['name' => $this->order->recipient_name]),
                'body' => __('emails.order.confirmation_body', ['order_id' => $this->order->id]),
                'buttonText' => __('emails.order.confirmation_button'),
                'buttonUrl' => route('orders.show', $this->order->id),
                'footer' => __('emails.order.confirmation_footer'),
            ]);
    }
}
