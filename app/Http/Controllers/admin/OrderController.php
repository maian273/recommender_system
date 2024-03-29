<?php

namespace App\Http\Controllers\admin;

use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    //
    public function view(){
        // $orderDetails = OrderDetail::all()->toArray();
        // $orders = array();
        // foreach ($orderDetails as $key => $orderDetail) {
        //     $orders[$key]['id_product'] = $orderDetail['id_product'];
        // }
        $orders = DB::table('order_detail')->join('products','products.id','order_detail.id_product')
            ->join('orders','orders.id','order_detail.id_order')
            ->selectRaw('products.id as id_product,products.*,order_detail.id as id_order,order_detail.*, orders.status as status');
        // //var_dump($order->get());
        $total = count($orders->get());
        $orders = $orders->orderBy('order_detail.created_at', 'DESC')->paginate(10);
        return view('admin.order.view',[
            'orders' => $orders,
            'total' => $total
        ]);
    }
    //xóa một đơn hàng
    public function delete($id){
        $order = OrderDetail::find($id);
        if(!$order) return view('admin.product.error');
        $tran = Order::find($order->id_order);
        if($tran->total - $order->unit_price*$order->quantity ==0 ){
            $tran->delete();
        }
        else{
            $tran->total = $tran->total - $order->unit_price*$order->quantity;
            $tran->save();
        }
        $order->delete();
        return redirect('admin/order/view')->with('thongbao','Xóa đơn hàng thành công');
    }
    //xóa nhiều đơn hàng
    public function deleteMultiple(Request $request){
        foreach ($request->allVals as $row){
            $order = OrderDetail::find($row);
            if(!$order) return response()->json('fail');
            $tran = Order::find($order->id_order);
            if($tran->total - $order->unit_price*$order->quantity ==0 ){
                $tran->delete();
            }
            else{
                $tran->total = $tran->total - $order->unit_price*$order->quantity;
                $tran->save();
            }
            $order->delete();
        }



        return response()->json('ok');
//        $order = OrderDetail::find($id);
//        if(!$order) return view('admin.product.error');
//        $tran = Order::find($order->id_order);
//        if($tran->total - $order->unit_price*$order->quantity ==0 ){
//            $tran->delete();
//        }
//        else{
//            $tran->total = $tran->total - $order->unit_price*$order->quantity;
//            $tran->save();
//        }
//        $order->delete();
//        return redirect('admin/order/view')->with('thongbao','Xóa đơn hàng thành công');
    }
    //tìm kiếm order
    public function search(Request $request){
        $orders = DB::table('order_detail')->join('products','products.id','order_detail.id_product')
            ->join('orders','orders.id','order_detail.id_order')
            ->where('order_detail.created_at','>=',date('Y-m-d',strtotime($request->date_from)))
            ->where('order_detail.created_at','<=',date('Y-m-d',strtotime($request->date_to)))
            ->selectRaw('products.id as id_product,products.*,order_detail.id as id_order,order_detail.*');
        //var_dump($order->get());
        $total = count($orders->get());
        $orders = $orders->orderBy('order_detail.id_order')->paginate(10);
        return view('admin.order.view',[
            'orders' => $orders,
            'total' => $total
        ]);
    }
}
