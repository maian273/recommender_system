<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Mail\OrderSuccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use predictionio\EventClient;
use Cart;
use Carbon\Carbon;

class OrderController extends Controller
{
    //
    public function ship(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);

        // Ship order...

        $user = $request->user();
        if ($user) {
            Mail::to($user->email)->send(new OrderSuccess($order));
        }
    }

    public function getOrder(){
        $user = Auth::user();
        $cart = Cart::content();
        $total = Cart::subtotal();
        $image = [];
        foreach ($cart as $row){
            $image[$row->id] = Product::find($row->id)->image;
        }
        return view('site.order.order',compact(['user','cart','image','total']));
    }
    public function postOrder(Request $request){
        $tran = new Order();
        $tran->user_id = Auth::id();
        $tran->date_order = date('Y-m-d');
        $tran->status = 0;
        $tran->total = floatval(str_replace(',','',Cart::subtotal()));
        $tran->payment = $request->payment_method;
        $tran->note = $request->note;
        $tran->save();
        foreach (Cart::content() as $row){
            $order = new OrderDetail();
            $order->id_order = $tran->id;
            $order->id_product = $row->id;
            $order->quantity = $row->qty;
            $order->unit_price = $row->price;
            if ($order->save()){
                $this->sendEventServerBuy($tran->user_id, $row->id);
            }
        }
        Cart::destroy();
        $this->ship($request, $tran->id);
        return redirect('')->with('thongbao','Cảm ơn bạn đã đặt hàng sản phẩm của chúng tôi! Hãy tiếp tục mua sắm');
    }

    public function sendEventServerBuy($userId, $productId) {
           $accessKey = env('ACCESS_KEY');
           $client = new EventClient($accessKey, 'http://localhost:7070'); //Event Server 
           $client->createEvent([
                'event' => 'buy',
                'entityType' => 'user',
                'entityId' => $userId,
                'targetEntityType' => 'item',
                'targetEntityId' => $productId,
                'eventTime' => Carbon::now()->toIso8601String()
            ]);
    }
}
