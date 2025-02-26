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
    public $lang;

    public function __construct(Order $order, Payment $payment){
        $this->order = $order;
        $this->payment = $payment;
        $this->lang = app()->getLocale(); // Get the app locale for language detection

        // Use shipping address if provided, otherwise fallback to billing
        $this->shippingAddress = $order->shippingAddress ?? $order->billingAddress;
    }

    public function build(){
        return $this->subject($this->getSubject())
        ->view("emails.{$this->lang}.orders.receipt")
        ->with([
            'order' => $this->order,
            'payment' => $this->payment,
            'shippingAddress' => $this->shippingAddress,
        ]);
    }

    private function getSubject(){
        return match($this->lang){
            'frca' => "Reçu de paiement pour la commande #{$this->order->id}",
            default => "Payment receipt for order #{$this->order->id}",
        };
    }
}
