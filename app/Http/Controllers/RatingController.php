<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Product;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use predictionio\EventClient;
use Carbon\Carbon;

class RatingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $orderDetailID)
    {
        $rating = new Rating();
        $rating->user_id = Auth::id();
        $rating->point = (float)$request->get('rate');
        $rating->product_id = $orderDetailID;
        $product = Product::query()->find($orderDetailID);
        if ($rating->save()) {
            $this->sendEventServerRate($rating);
            $quatity = $product->rating_quantity;
            $point = $product->rating_point;
            $product->rating_quantity += 1;
            $update_point = ($quatity*$point + $rating->point)/$product->rating_quantity;
            $product->rating_point = $update_point;
            $product->save();
        }
        return redirect($orderDetailID . '/chitiet')->with('messageRating', 'Success');
    }

    public function sendEventServerRate($rating) {
        $accessKey = env('ACCESS_KEY');
        $client = new EventClient($accessKey, 'http://localhost:7070');
        $client->createEvent(array(
            'event' => 'rate',
            'entityType' => 'user',
            'entityId' => $rating->user_id,
            'targetEntityType' => 'item',
            'targetEntityId' => $rating->product_id,
            'properties' => array('rating'=> $rating->point),
            'eventTime' => Carbon::now()->toIso8601String()
        ));
    }

}
