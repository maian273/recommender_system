<?php

namespace App\Http\Controllers;

use App\Models\User;
use Cart;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

class UserController extends Controller
{
    //
    public function getDangKy(){
        return view('site.user.dangky');
    }
    //
    public function postRegister(Request $request){
        $user = new User();
        $user->full_name = $request->fullname;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->password = bcrypt($request->password);
        $user->save();
        return redirect('dang-ky')->with('thongbao','Đăng ký tài khoản thành công');
    }
    //
    public function getLogin(Request $request){
        if($request->url()!=url()->previous())
        Session::put('url',url()->previous());
        return view('site.user.dangnhap');
    }
    //
    public function postLogin(Request $request){
        if(Auth::attempt(['email' =>$request->email,'password' => $request->password])){
            //return redirect()->intended('index')->with('thongbao','Đăng nhập thành công');
            $url = Session::get('url');
            //Session::forget('url');
            if(strpos($url,'dang-ky')!==false)
                return redirect('index')->with('thongbao','Đăng nhập thành công');
            return redirect($url)->with('thongbao','Đăng nhập thành công');
        }
        return redirect('dang-nhap')->with('thongbao','Đăng nhập không thành công');
    }
    //
    public function checkEmail(Request $request){
        if(count(User::where('email',$request->email)->get())>0){
            return json_encode(FALSE);
        }
        return json_encode(TRUE);
    }
    public function getLogout(Request $request){
        Auth::logout();
        Cart::destroy();
        return redirect(url()->previous())->with('thongbao','Đăng xuất thành công');
    }
    //
    public function getChangeAcc(){
        return view('site.user.thaydoitaikhoan',[
            'user' => Auth::user()
        ]);
    }
    //
    public function postChangeAcc(Request $request){
        $user = Auth::user();
        $user->full_name = $request->fullname;
        $user->phone = $request->phone;
        $user->address = $request->address;
        if($request->password!='')
        $user->password = bcrypt($request->password);
        $user->save();
        return redirect('thay-doi-tai-khoan')->with('thongbao','Thay đổi tài khoản thành công');
    }

    public function getHistory() {
        $user = Auth::user();
        $orders = $order_detail = DB::table('order_detail')
            ->join('products','products.id', '=', 'order_detail.id_product')
            ->join('orders', 'orders.id', '=', 'order_detail.id_order')
            ->select('order_detail.id','order_detail.id_order','order_detail.quantity','order_detail.id_product','order_detail.unit_price','products.id','products.name','products.image','order_detail.created_at')
            ->where('user_id', $user->id)->orderBy('id_order', 'desc')->get();
//        dd($orders);
        return view('site.user.lichsu', [
           'orders' => $orders,
            'user' => $user
        ]);
    }
}
