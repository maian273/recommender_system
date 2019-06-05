<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use predictionio\EngineClient;
use Illuminate\Support\Facades\DB;
/**
 * App\Product
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $id_type
 * @property string|null $description
 * @property float|null $unit_price
 * @property float|null $promotion_price
 * @property string|null $image
 * @property string|null $unit
 * @property int|null $new
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereIdType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereNew($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product wherePromotionPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereUnitPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Product extends Model
{
    //
    protected $table = 'products';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments(){
        return $this->hasMany(Comment::class);
    }
    public function ratings(){
        return $this->hasMany(Rating::class);
    }

    public function getBestSeller($num){
        $bestsell = DB::table('order_detail')
            ->join('products','products.id', '=', 'order_detail.id_product')
            ->select('products.*',
                DB::raw('count(products.id)'))
            ->groupBy('products.id','products.name','products.unit_price','products.promotion_price','products.image')
            ->orderByRaw('count(products.id) DESC')
            ->take($num)->get();
        return $bestsell;    
    }
    public function getSuggestProducts($userId, $num) {
        $engineClient = new EngineClient('http://localhost:8000');
            $suggestItems = $engineClient->sendQuery(array('user' => $userId, 'num' => $num)); // Submit user
            $itemArr = [];
            if (!empty($suggestItems)) {
                $items = $suggestItems['itemScores'];
                if (!empty($items)) {
                    foreach ($items as $item) {
                        $itemArr[] = $item['item'];
                    }
                }
            }
            $suggests = Product::whereIn('id', $itemArr)->get();
            return $suggests;
    }
}
