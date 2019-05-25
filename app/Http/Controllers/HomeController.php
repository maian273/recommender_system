<?php

namespace App\Http\Controllers;

use App\Contact;
use App\OrderDetail;
use App\Product;
use App\Silde;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    //
    public function index(){
        $slide = Silde::all();
        $min = '';
        $max = '';
        if (isset($_GET['min']) || isset($_GET['max'])) {
            if (!empty($_GET['min']) && empty($_GET['max'])) {
                $product = Product::where([['created_at', '<>', 0], ['unit_price', '>=', $_GET['min']]])->paginate(12);
                $count_product = Product::where([['created_at', '<>', 0], ['unit_price', '>=', $_GET['min']]])->get();
                $new_product = Product::where([['new', 1], ['unit_price', '>=', $_GET['min']]])->paginate(12);
                $count_new_product = Product::where([['new', 1], ['unit_price', '>=', $_GET['min']]])->get();
                $sanphamkhuyenmai = Product::where([['promotion_price', '<>', 0], ['unit_price', '>=', $_GET['min']]])->paginate(12);
                $count_sanphamkhuyenmai = Product::where([['promotion_price', '<>', 0], ['unit_price', '>=', $_GET['min']]])->get();
                $min = $_GET['min'];
            } else if (!empty($_GET['max']) && empty($_GET['min'])) {
                $product = Product::where([['created_at', '<>', 0], ['unit_price', '<=', $_GET['max']]])->paginate(12);
                $count_product = Product::where([['created_at', '<>', 0], ['unit_price', '<=', $_GET['max']]])->get();
                $new_product = Product::where([['new', 1], ['unit_price', '<=', $_GET['max']]])->paginate(12);
                $count_new_product = Product::where([['new', 1], ['unit_price', '<=', $_GET['max']]])->get();
                $sanphamkhuyenmai = Product::where([['promotion_price', '<>', 0], ['unit_price', '<=', $_GET['max']]])->paginate(12);
                $count_sanphamkhuyenmai = Product::where([['promotion_price', '<>', 0], ['unit_price', '<=', $_GET['max']]])->get();
                $max = $_GET['max'];
            } else {
                $product = Product::where([['created_at', '<>', 0], ['unit_price', '>=', $_GET['min']], ['unit_price', '<=', $_GET['max']]])->paginate(12);
                $count_product = Product::where([['created_at', '<>', 0], ['unit_price', '>=', $_GET['min']], ['unit_price', '<=', $_GET['max']]])->get();
                $new_product = Product::where([['new', 1], ['unit_price', '>=', $_GET['min']], ['unit_price', '<=', $_GET['max']]])->paginate(12);
                $count_new_product = Product::where([['new', 1], ['unit_price', '>=', $_GET['min']], ['unit_price', '<=', $_GET['max']]])->get();
                $sanphamkhuyenmai = Product::where([['promotion_price', '<>', 0], ['unit_price', '>=', $_GET['min']], ['unit_price', '<=', $_GET['max']]])->paginate(12);
                $count_sanphamkhuyenmai = Product::where([['promotion_price', '<>', 0], ['unit_price', '>=', $_GET['min']], ['unit_price', '<=', $_GET['max']]])->get();
                $min = $_GET['min'];
                $max = $_GET['max'];
            }
        } else {
            $product = Product::where('created_at', '<>', 0);
            $new_product = $product->where('new', 1);
            $count_new_product = count(($new_product)->get());
            $new_product = $new_product->paginate(12);

            $saleProduct = $product->where('promotion_price', '<>', 0);
            $count_saleProduct = count($saleProduct->get());
            $saleProduct = $saleProduct->paginate(12);
            $product = $product->paginate(12);
        }
        return view('site.home.index',[
            'slide' => $slide,
            'product' => $product,
            'new_product' => $new_product,
            'count_new_product' => $count_new_product,
            'sanphamkhuyenmai' => $saleProduct,
            'count_sanphamkhuyenmai' => $count_saleProduct,
            'min' =>$min,
            'max' =>$max
        ]);
    }
    //
    public function getLoaiSP($id){
        $product_theoloaisp = Product::where('id_type',$id)->where('new',1)->get();
        $sanphamkhuyenmai = Product::where('id_type',$id)->where('promotion_price','<>',0)->get();
        return view('site.catalog.index',[
            'product_theoloaisp' => $product_theoloaisp,
            'sanphamkhuyenmai' => $sanphamkhuyenmai
        ]);
    }
    //
    public function getSanPham($id){
        $product = Product::find($id);
        $userId = Auth::user()->id;
        $new_product = Product::where('new',0)->take(4)->get();
        $bestsell = DB::table('order_detail')
            ->join('products','products.id', '=', 'order_detail.id_product')
            ->select('products.id','products.name','products.unit_price','products.promotion_price','products.image',
                DB::raw('count(products.id)'))
            ->groupBy('products.id','products.name','products.unit_price','products.promotion_price','products.image')
            ->orderByRaw('count(products.id) DESC')
            ->take(4)->get();
        $sanphamtt = Product::where('id_type',$product->id_type)->where('id','<>',$product->id)->get();
        return view('site.product.chitiet',[
            'product' => $product,
            'sanphamtt' => $sanphamtt,
            'new_product' => $new_product,
            'bestsell' => $bestsell,
        ]);
    }
    //
    public function getLienHe(){
        return view('site.home.lienhe');
    }
    //
    public function postLienHe(Request $request){
        $contact = new Contact();
        $contact->id_user = Auth::user()->id;
        $contact->subject = $request->subject;
        $contact->message = $request->message;
        $contact->date = date("Y-m-d");
        $contact->save();
        return redirect('lienhe')->with('thongbao','Cảm ơn bạn đã phản hồi');
    }
    public function getGioiThieu(){
        return view('site.home.gioithieu');
    }//
    public function search(Request $request){
        $product = Product::where('name','like',htmlspecialchars($request->q).'%')->get();
        return response()->json($product);
    }
    //
    public function timkiem(Request $request){
        $key = $request->s;
        if(is_numeric($key)&&$key!=0){
            $product = Product::Where('unit_price',$key)
                ->orWhere('promotion_price',$key)
                ->paginate(12);
            $count = count(Product::Where('unit_price',$key)
                ->orWhere('promotion_price',$key)->get());
        }else{
            $product = Product::where('name','like',$key.'%')
                ->paginate(12);
            $count = count(Product::where('name','like',$key.'%')->get());
        }

        return view('site.home.search',[
            'result' => $product,
            'count' => $count,
            'key' => $key
        ]);
    }
}
