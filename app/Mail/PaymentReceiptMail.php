<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;
use App\Models\Payment;

class PaymentReceiptMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $order;
    public $payment;
    public $shippingAddress;

    public function __construct(Order $order, Payment $payment)
    {
        $this->order = $order;
        $this->payment = $payment;

        $this->shippingAddress = $order->shippingAddress ?? $order->billingAddress;
    }

    public function build()
    {
        return $this->subject(__('emails.payment_receipt.subject', ['order_id' => $this->order->id]))
            ->view('emails.orders.receipt')
            ->with([
                'order' => $this->order,
                'payment' => $this->payment,
                'shippingAddress' => $this->shippingAddress,
            ]);
    }
}
