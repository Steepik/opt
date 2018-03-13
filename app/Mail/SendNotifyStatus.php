<?php

namespace App\Mail;

use App\Order;
use App\Special;
use App\Tire;
use App\Wheel;
use App\Truck;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendNotifyStatus extends Mailable
{
    use Queueable, SerializesModels;

    public $product;
    public $order;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {

        if($order->ptype == 1) { //tires
            $data = Tire::where('tcae', $order->tcae)->first();
        } elseif($order->ptype == 2) { //truck
            $data = Truck::where('tcae', $order->tcae)->first();
        } elseif($order->ptype == 3 ) { // special
            $data = Special::where('tcae', $order->tcae)->first();
        } elseif($order->ptype == 4) { //wheel
            $data = Wheel::where('tcae', $order->tcae)->first();
        }

        $this->order = $order;
        $this->product = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Проверка товара')->markdown('admin.emails.notify_status');
    }
}
