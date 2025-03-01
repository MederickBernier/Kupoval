<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Class ShippingNotificationMail
 *
 * This class is responsible for sending shipping notification emails to customers.
 * It extends the Mailable class and implements the ShouldQueue interface to allow
 * the email to be queued for sending.
 *
 * @property Order $order The order associated with the shipping notification.
 * @property string $trackingUrl The URL for tracking the shipment.
 * @property string $carrier The carrier handling the shipment.
 * @property string $trackingNumber The tracking number for the shipment.
 * @property string $estimatedDelivery The estimated delivery date of the shipment.
 * @property string $lang The language/locale for the email content.
 *
 * @method __construct(Order $order, string $trackingUrl, string $carrier, string $trackingNumber, string $estimatedDelivery)
 * Constructor to initialize the ShippingNotificationMail object with order details and tracking information.
 *
 * @method build()
 * Builds the email message with the appropriate subject and view based on the locale.
 *
 * @method getSubject()
 * Generates the email subject based on the locale.
 */
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
        $this->lang = app()->getLocale();
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
