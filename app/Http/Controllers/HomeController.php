<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Slide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use predictionio\EventClient;
use predictionio\EngineClient;
use Carbon\Carbon;

class HomeController extends Controller
{
    //
    public function index(){
        $slide = Slide::all();
        $min = '';
        $max = '';
        if (isset($_GET['min']) || isset($_GET['max'])) {
            if (!empty($_GET['min']) && empty($_GET['max'])) {
                $query = ['unit_price', '>=', $_GET['min']];
                $min = $_GET['min'];
            } else if (!empty($_GET['max']) && empty($_GET['min'])) {
                $query = ['unit_price', '<=', $_GET['max']];
                $max = $_GET['max'];
            } else {
                $query = [['unit_price', '>=', $_GET['min']], ['unit_price', '<=', $_GET['max']]];
                $min = $_GET['min'];
                $max = $_GET['max'];
            }
        }
        if (!empty($query)){
            $new_product = Product::where('new', 1)->where($query);
            $saleProduct = Product::where('promotion_price', '<>', 0)->where($query);
            $product = Product::where($query)->paginate(12);
        } else {
            $new_product = Product::where('new', 1);
            $saleProduct = Product::where('promotion_price', '<>', 0);
            $product = Product::where('created_at', '<>', 0)->paginate(12);
        }
        $count_new_product = count(($new_product)->get());
        $new_product = $new_product->paginate(12);
        $count_saleProduct = count($saleProduct->get());
        $saleProduct = $saleProduct->paginate(12);
        $suggestProducts = new Product(); 
        if (Auth::check()) {
           $userId = Auth::user()->id;
            $suggestProducts = $suggestProducts->getSuggestProducts($userId, 6);
        }else{
            $suggestProducts = $suggestProducts->getBestSeller(6);
        }
        return view('site.home.index',[
            'slide' => $slide,
            'product' => $product,
            'suggestProducts' => $suggestProducts,
            'new_product' => $new_product,
            'count_new_product' => $count_new_product,
            'sanphamkhuyenmai' => $saleProduct,
            'count_sanphamkhuyenmai' => $count_saleProduct,
            'min' =>$min,
            'max' =>$max
        ]);
    }
    //
    public function getContact(){
        return view('site.home.lienhe');
    }
    //
    public function postContact(Request $request){
        $contact = new Contact();
        $contact->id_user = Auth::user()->id;
        $contact->subject = $request->subject;
        $contact->message = $request->message;
        $contact->date = date("Y-m-d");
        $contact->save();
        return redirect('lienhe')->with('thongbao','Cảm ơn bạn đã phản hồi');
    }
    public function getAbout(){
        return view('site.home.gioithieu');
    }//

    public function search(Request $request){
        $product = Product::where('name','like',htmlspecialchars($request->q).'%')->get();
        return response()->json($product);
    }
    //
    public function keySearch(Request $request){
        $key = $request->s;
        if(is_numeric($key)&&$key!=0){
            $product = Product::Where('unit_price',$key)
                ->orWhere('promotion_price',$key);
            $count = count($product->get());
            $product = $product->paginate(12);
        }else{
            $product = Product::where('name','like','%'.$key.'%');
            $count = count($product->get());
            $product = $product->paginate(12);
        }

        return view('site.home.search',[
            'result' => $product,
            'count' => $count,
            'key' => $key
        ]);
    }
}
