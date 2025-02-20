<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;

class OrderStatusUpdateMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $status;

    public function __construct(Order $order){
        $this->order = $order;
        $this->status = ucfirst($order->status);
    }

    public function build(){
        return $this->subject("Your Order #{$this->order->id} is now {$this->status}")
            ->view('email.order-status-update')
            ->with(['order' => $this->order, 'status' => $this->status]);
    }
}
