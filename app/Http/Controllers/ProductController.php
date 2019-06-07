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

class ProductController extends Controller
{
    //
    public function getProductType($id){
        $product_theoloaisp = Product::where('id_type',$id)->where('new',1)->get();
        $sanphamkhuyenmai = Product::where('id_type',$id)->where('promotion_price','<>',0)->get();
        return view('site.catalog.index',[
            'product_theoloaisp' => $product_theoloaisp,
            'sanphamkhuyenmai' => $sanphamkhuyenmai
        ]);
    }

    public function getDetailProduct($id){
        $product = Product::find($id);
        $checkNotRated = true;
        if (Auth::check()) {
           $userId = Auth::user()->id;
           $this->sendEventServerView($userId, $id);
           if ($product->ratings){
                $rated = $product->ratings->where('user_id', '=', $userId)->toArray();
                if (!empty($rated)){
                    $checkNotRated = false;
                }
           }
           //
            $suggests = $product->getSuggestProducts($userId, 6);
        }else{
            $suggests = Product::where('new',0)->take(4)->get();
        }
        $bestsell = $this->getBestSeller(4);
        $sanphamtt = Product::where('id_type',$product->id_type)->where('id','<>',$product->id)->take(12)->get();
        return view('site.product.chitiet',[
            'product' => $product,
            'sanphamtt' => $sanphamtt,
            'suggests' => $suggests,
            'bestsell' => $bestsell,
            'checkNotRated'=> $checkNotRated
        ]);
    }
    //
    public function getBestSeller(){
        $bestsell = DB::table('order_detail')
            ->join('products','products.id', '=', 'order_detail.id_product')
            ->select('products.id','products.name','products.unit_price','products.promotion_price','products.image',
                DB::raw('count(products.id)'))
            ->groupBy('products.id','products.name','products.unit_price','products.promotion_price','products.image')
            ->orderByRaw('count(products.id) DESC')
            ->take(4)->get();
        return $bestsell;    
    }

    public function sendEventServerView($userId, $productId) {
           $accessKey = env('ACCESS_KEY');
           $client = new EventClient($accessKey, 'http://localhost:7070'); //Event Server 
           $client->createEvent([
                'event' => 'view',
                'entityType' => 'user',
                'entityId' => $userId,
                'targetEntityType' => 'item',
                'targetEntityId' => $productId,
                'eventTime' => Carbon::now()->toIso8601String()
            ]);
    }
}
