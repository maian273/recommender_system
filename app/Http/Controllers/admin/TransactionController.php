<?php

namespace App\Http\Controllers\admin;

use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    //
    public function view(){
        $tran = DB::table('orders')->join('users','orders.user_id','users.id')
                ->selectRaw('users.full_name as name,orders.*')->orderBy('orders.id', 'DESC');
        $total = count($tran->get());
        $tran = $tran->paginate(5);
        return view('admin.transaction.view',[
            'tran' => $tran,
            'total' => $total
        ]);
    }
    //
    public function chitiet($id){
        $order = DB::table('order_detail')->join('products','products.id','order_detail.id_product')
            ->where('order_detail.id_order',$id)
            ->selectRaw('products.id as id_product,products.*,order_detail.id as id_order,order_detail.*')
            ->get();
        return response()->json($order);
    }
    ////chỉnh sửa status
    public function changeStatus($id){
        $order = DB::table('order_detail')->join('products','products.id','order_detail.id_product')
            ->where('order_detail.id',$id)
            ->join('orders', 'orders.id','=', 'order_detail.id_order')
            ->selectRaw('products.id as id_product,products.*,order_detail.id as id_order,order_detail.*, orders.status as status')
            ->first();
        return view('admin.transaction.edit', [
            'order' => $order
        ]);
    }

    public function postChangeStatus($id, Request $request) {
        $order = Order::find($id);
        $status = $request->status;
        $order->status = $status;
        $order->save();
        return redirect('admin/order/view')->with('thongbao', 'Cập nhật trạng thái thành công');
    }
    //xử lý thêm thông tin sản phẩm
    public function postAdd(Request $request){
        $tran = new Order();
        $tran->name = $request->name;
        $tran->unit_price =floatval(str_replace(',','',$request->price));
        $tran->promotion_price = floatval(str_replace(',','',$request->discount));
        $tran->new = $request->new;
        $tran->description = $request->mota;
        $tran->id_type = $request->cat;
        $file = $request->file('image');
        $duoi = $file->getClientOriginalExtension();
        if ($duoi != 'jpg' && $duoi != 'png') {
            return redirect('admin/order/add')->with('thongbao', 'Bạn phải chọn file ảnh')->withInput();
        }

        do {
            $name = str_random(4).$file->getClientOriginalName();
        }while ((file_exists("source/image/Order/.$name")) );

        $tran->image = $name;
        $file->move('source/image/Order/', $name);
        $tran->save();
        return redirect('admin/Order/add')->with('thongbao', 'Thêm sản phẩm thành công');
    }
    //hiển thị form sửa thông tin sản phẩm
    public function edit($id){
        $tran = Order::find($id);
        if(!$tran) return view('admin.Order.error');
        return view('admin.Order.edit',compact('Order'));
    }
    //xử lý sửa thông tin sản phẩm
    public function postEdit($id,Request $request){
        $tran = Order::find($id);
        $tran->name = $request->name;
        $tran->unit_price =floatval(str_replace(',','',$request->price));
        $tran->promotion_price = floatval(str_replace(',','',$request->discount));
        $tran->new = $request->new;
        $tran->description = $request->mota;
        $tran->id_type = $request->cat;
        if($request->hasFile('image')){
            $file = $request->file('image');
            $duoi = $file->getClientOriginalExtension();
            if ($duoi != 'jpg' && $duoi != 'png') {
                return redirect('admin/Order/edit/'.$id)->with('loi', 'Bạn phải chọn file ảnh')->withInput();
            }
            $tencu = $file->getClientOriginalName();
            do {
                $name = str_random(4).$file->getClientOriginalName();
            }while ((file_exists("source/image/Order/.$name")) );

            $tran->image = $name;
            $file->move('source/image/Order/',$name);
            if(file_exists("source/image/Order/".$tencu))
                unlink('source/image/Order/'.$tencu);
        }
        $tran->save();
        return redirect('admin/Order/edit/'.$id)->with('thongbao', 'Chỉnh sửa sản phẩm thành công');
    }
    //xóa một giao dịch
    public function delete($id){
        $tran = Order::find($id);
        if(!$tran) return view('admin.product.error');
        OrderDetail::where('id_order',$id)->delete();
        $tran->delete();
        return redirect('admin/transaction/view')->with('thongbao','Xóa giao dịch thành công');
    }
    //xóa nhiều giao dịch
    public function deleteMultiple(Request $request){
        foreach ($request->allVals as $row){
            $tran = Order::find($row);
            if(!$tran) return response()->json('fail');
            OrderDetail::where('id_order',$row)->delete();
            $tran->delete();
        }
        return response()->json('ok');
    }
    //tìm kiếm transaction
    public function search(Request $request){
        $tran = DB::table('orders')->join('users','orders.user_id','users.id')
            ->where('date_order','>=',date('Y-m-d',strtotime($request->date_from)))
            ->where('date_order','<=',date('Y-m-d',strtotime($request->date_to)))
            ->selectRaw('users.full_name as name,orders.*');
        //var_dump($tran->get());
        $total = count($tran->get());
        $tran = $tran->paginate(5);
        return view('admin.transaction.view',[
            'tran' => $tran,
            'total' => $total
        ]);
    }
}
