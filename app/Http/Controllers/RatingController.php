<?php

namespace App\Http\Controllers;

use App\Rating;
use App\Product;
use App\OrderDetail;
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
        $accessKey = 'I-Jma9h9LaDSC0FxHBblpUnoKmJgzwye-OOcU3WKxRLlYyoSuqlme7iSpp-1pv48';
        $client = new EventClient($accessKey, 'http://localhost:7070');
        $rating->user_id = Auth::id();
        $rating->point = $request->get('rate');
        $rating->product_id = $orderDetailID;
        $product = Product::query()->find($orderDetailID);
        $client->createEvent(array(
            'event' => 'rate',
            'entityType' => 'user',
            'entityId' => $rating->user_id,
            'targetEntityType' => 'item',
            'targetEntityId' => $rating->product_id,
            'properties' => array('rating'=> $rating->point),
            'eventTime' => Carbon::now()->toIso8601String()
        ));
        if ($rating->save()) {
            $quatity = $product->rating_quantity;
            $point = $product->rating_point;
            $product->rating_quantity += 1;
            $update_point = ($quatity*$point + $rating->point)/$product->rating_quantity;
            $product->rating_point = $update_point;
            $product->save();
        }
        return redirect($orderDetailID . '/chitiet')->with('message', 'Success');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
