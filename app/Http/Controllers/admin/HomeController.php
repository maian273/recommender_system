<?php
/**
 * Created by PhpStorm.
 * User: quangha
 * Date: 2/10/2018
 * Time: 10:33 PM
 */

namespace App\Http\Controllers\admin;

use App\Models\Contact;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(){
        $tran = DB::table('orders')->join('users','orders.user_id','users.id')
            ->selectRaw('users.full_name as name,orders.*')
            ->orderByRaw('orders.id desc')->take(10)->get();
        $total_tran = count($tran);
        $total_product = count(Product::all());
        $total_comment = count(Contact::all());
        $total_user = count(User::all());
        return view('admin.home.index',[
            'total_tran' => $total_tran,
            'total_product' => $total_product,
            'total_user' => $total_user,
            'total_comment' => $total_comment,
            'tran' => $tran
        ]);
    }

}