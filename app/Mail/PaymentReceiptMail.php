<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;
use App\Models\Payment;

/**
 * Class PaymentReceiptMail
 *
 * This class is responsible for creating and sending payment receipt emails.
 * It extends the Mailable class and implements the ShouldQueue interface to
 * allow the email to be queued for sending.
 *
 * @property Order $order The order associated with the payment receipt.
 * @property Payment $payment The payment details associated with the order.
 * @property string $shippingAddress The shipping address for the order.
 * @property string $lang The language locale for the email.
 *
 * @method __construct(Order $order, Payment $payment) Initializes the mail with order and payment details.
 * @method build() Builds the email with the appropriate subject and view.
 * @method getSubject() Generates the email subject based on the language locale.
 */
class PaymentReceiptMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $order;
    public $payment;
    public $shippingAddress;
    public $lang;

    public function __construct(Order $order, Payment $payment)
    {
        $this->order = $order;
        $this->payment = $payment;
        $this->lang = app()->getLocale();
        $this->shippingAddress = $order->shippingAddress ?? $order->billingAddress;
    }

    public function build()
    {
        return $this->subject($this->getSubject())
            ->view("emails.{$this->lang}.orders.receipt")
            ->with([
                'order' => $this->order,
                'payment' => $this->payment,
                'shippingAddress' => $this->shippingAddress,
            ]);
    }

    private function getSubject()
    {
        return match ($this->lang) {
            'frca' => "Reçu de paiement pour la commande #{$this->order->id}",
            default => "Payment receipt for order #{$this->order->id}",
        };
    }
}
