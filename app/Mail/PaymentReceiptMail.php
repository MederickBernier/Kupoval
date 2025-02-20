<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentReceiptMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function build()
    {
        return $this->subject(__('emails.order.receipt_subject', ['order_id' => $this->order->id]))
            ->view('emails.orders.receipt')
            ->with([
                'greeting' => __('emails.order.receipt_greeting', ['name' => $this->order->recipient_name]),
                'body' => __('emails.order.receipt_body'),
                'buttonText' => __('emails.order.receipt_button'),
                'buttonUrl' => route('orders.show', $this->order->id),
                'footer' => __('emails.order.receipt_footer'),
            ]);
    }
}
