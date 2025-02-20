<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RefundConfirmationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function build()
    {
        return $this->subject(__('emails.refund.refund_subject', ['order_id' => $this->order->id]))
            ->view('emails.orders.refund')
            ->with([
                'greeting' => __('emails.refund.refund_greeting', ['name' => $this->order->recipient_name]),
                'body' => __('emails.refund.refund_body', ['order_id' => $this->order->id]),
                'buttonText' => __('emails.refund.refund_button'),
                'buttonUrl' => route('orders.show', $this->order->id),
                'footer' => __('emails.refund.refund_footer'),
            ]);
    }
}
