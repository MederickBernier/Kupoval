<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;

class OrderConfirmationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $order;
    public $user;
    public $shippingAddress;
    public $billingAddress;
    public $fullName;

    public function __construct(Order $order)
    {
        $this->order = $order;
        $this->user = $order->user;

        // If no shipping address, use the billing address from the user's profile
        $this->shippingAddress = $order->shippingAddress ?? $order->user->profile->billingAddress;
        $this->billingAddress = $order->user->profile->billingAddress;

        // Construct full name with available fields (title, first_name, last_name)
        $profile = $order->user->profile;
        $this->fullName = trim(implode(' ', array_filter([
            $profile->title ?? '',
            $profile->first_name ?? '',
            $profile->last_name ?? ''
        ])));
    }

    public function build()
    {
        return $this->subject(__('emails/order_confirmation.subject', ['order_id' => $this->order->id]))
            ->view('emails.orders.confirmation')
            ->with([
                'order' => $this->order,
                'user' => $this->user,
                'fullName' => $this->fullName,
                'shippingAddress' => $this->shippingAddress,
                'billingAddress' => $this->billingAddress,
            ]);
    }
}
