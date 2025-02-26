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
    public $lang;

    public function __construct(Order $order)
    {
        $this->order = $order;
        $this->user = $order->user;
        $this->lang = app()->getLocale(); // Get the application locale

        // Use shipping address if provided, otherwise fallback to billing
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
