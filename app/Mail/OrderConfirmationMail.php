<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;

/**
 * Class OrderConfirmationMail
 *
 * This class is responsible for creating and sending order confirmation emails.
 * It extends the Mailable class and implements the ShouldQueue interface to allow
 * the email to be queued for sending.
 *
 * @property Order $order The order instance.
 * @property User $user The user who placed the order.
 * @property string $shippingAddress The shipping address for the order.
 * @property string $billingAddress The billing address for the order.
 * @property string $fullName The full name of the user.
 * @property string $lang The language/locale of the application.
 *
 * @method __construct(Order $order) Initializes the mail with the given order.
 * @method build() Builds the email with the appropriate subject and view.
 * @method getSubject() Generates the email subject based on the application locale.
 */
class OrderConfirmationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $order;
    public $user;
    public $shippingAddress;
    public $billingAddress;
    public $fullName;
    public $lang;

    public function __construct(Order $order)
    {
        $this->order = $order;
        $this->user = $order->user;
        $this->lang = app()->getLocale();
        $this->shippingAddress = $order->shippingAddress ?? $order->user->profile->billingAddress;
        $this->billingAddress = $order->user->profile->billingAddress;

        $profile = $order->user->profile;
        $this->fullName = trim(implode(' ', array_filter([
            $profile->title ?? '',
            $profile->first_name ?? '',
            $profile->last_name ?? ''
        ])));
    }

    public function build()
    {
        return $this->subject($this->getSubject())
            ->view("emails.{$this->lang}.orders.confirmation")
            ->with([
                'order' => $this->order,
                'user' => $this->user,
                'fullName' => $this->fullName,
                'shippingAddress' => $this->shippingAddress,
                'billingAddress' => $this->billingAddress,
            ]);
    }

    private function getSubject()
    {
        return match ($this->lang) {
            'frca' => "Confirmation de commande # {$this->order->id}",
            default => "Order Confirmation # {$this->order->id}"
        };
    }
}
