<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ShippingNotificationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $order;
    public $trackingUrl;

    public function __construct($order, $trackingUrl)
    {
        $this->order = $order;
        $this->trackingUrl = $trackingUrl;
    }

    public function build()
    {
        return $this->subject(__('emails.shipping.shipping_subject', ['order_id' => $this->order->id]))
            ->view('emails.orders.shipping')
            ->with([
                'greeting' => __('emails.shipping.shipping_greeting', ['name' => $this->order->recipient_name]),
                'body' => __('emails.shipping.shipping_body', ['order_id' => $this->order->id]),
                'buttonText' => __('emails.shipping.shipping_button'),
                'buttonUrl' => $this->trackingUrl,
                'footer' => __('emails.shipping.shipping_footer'),
            ]);
    }
}
