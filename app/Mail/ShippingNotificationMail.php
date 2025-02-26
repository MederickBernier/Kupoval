<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ShippingNotificationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $order;
    public $trackingUrl;
    public $carrier;
    public $trackingNumber;
    public $estimatedDelivery;
    public $lang;

    public function __construct(Order $order, $trackingUrl, $carrier, $trackingNumber, $estimatedDelivery)
    {
        $this->order = $order;
        $this->trackingUrl = $trackingUrl;
        $this->carrier = $carrier;
        $this->trackingNumber = $trackingNumber;
        $this->estimatedDelivery = $estimatedDelivery;
        $this->lang = app()->getLocale(); // Detects the app locale dynamically
    }

    public function build()
    {
        return $this->subject($this->getSubject())
            ->view("emails.{$this->lang}.orders.shipping_notification")
            ->with([
                'order' => $this->order,
                'trackingUrl' => $this->trackingUrl,
                'carrier' => $this->carrier,
                'trackingNumber' => $this->trackingNumber,
                'estimatedDelivery' => $this->estimatedDelivery,
            ]);
    }

    private function getSubject()
    {
        return match ($this->lang) {
            'frca' => "Votre commande #{$this->order->id} a été expédiée!",
            default => "Your Order #{$this->order->id} Has Been Shipped!",
        };
    }
}
