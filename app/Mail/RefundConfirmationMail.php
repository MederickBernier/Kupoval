<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RefundConfirmationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $order;
    public $refundAmount;
    public $refundDate;
    public $lang;

    public function __construct(Order $order, $refundAmount, $refundDate){
        $this->order = $order;
        $this->refundAmount = $refundAmount;
        $this->refundDate = $refundDate;
        $this->lang = app()->getLocale(); // Detects the app locale dynamically
    }

    public function build(){
        return $this->subject($this->getSubject())
        ->view("emails.{$this->lang}.orders.refund")
        ->with([
            'order' => $this->order,
            'refundAmount' => $this->refundAmount,
            'refundDate' => $this->refundDate,
        ]);
    }

    private function getSubject(){
        return match($this->lang){
            'frca' => "Confirmation de remboursement pour la commande #{$this->order->id}",
            default => "Refund confirmation for order #{$this->order->id}",
        };
    }
}
