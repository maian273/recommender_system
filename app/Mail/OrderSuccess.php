<?php

namespace App\Mail;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderSuccess extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $order;


    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $order_detail = DB::table('order_detail')
            ->join('products','products.id', '=', 'order_detail.id_product')
            ->select('order_detail.id','order_detail.id_order','order_detail.quantity','order_detail.unit_price','products.id','products.name','products.image')
            ->where('id_order', $this->order->id)->get();
        $user = User::where('id',$this->order->user_id)->first();
        return $this->view('emails.orders.shipped')
            ->with([
                'user' => $user,
                'order' => $this->order,
                'order_detail' =>$order_detail
            ]);
    }
}
