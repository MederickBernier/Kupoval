<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Class RefundConfirmationMail
 *
 * This class is responsible for sending a refund confirmation email.
 * It extends the Mailable class and implements the ShouldQueue interface
 * to allow the email to be queued.
 *
 * @property Order $order The order for which the refund is being confirmed.
 * @property float $refundAmount The amount of the refund.
 * @property string $refundDate The date of the refund.
 * @property string $lang The language/locale of the email.
 */
class RefundConfirmationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $order;
    public $refundAmount;
    public $refundDate;
    public $lang;

    public function __construct(Order $order, $refundAmount, $refundDate)
    {
        $this->order = $order;
        $this->refundAmount = $refundAmount;
        $this->refundDate = $refundDate;
        $this->lang = app()->getLocale();
    }

    public function build()
    {
        return $this->subject($this->getSubject())
            ->view("emails.{$this->lang}.orders.refund")
            ->with([
                'order' => $this->order,
                'refundAmount' => $this->refundAmount,
                'refundDate' => $this->refundDate,
            ]);
    }

    private function getSubject()
    {
        return match ($this->lang) {
            'frca' => "Confirmation de remboursement pour la commande #{$this->order->id}",
            default => "Refund confirmation for order #{$this->order->id}",
        };
    }
}
