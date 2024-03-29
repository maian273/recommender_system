@extends('site.layout')
@section('content')
   <script lang="javascript">var _vc_data = {id : 6766352, secret : '25fbf5b19054cb4de7c6761442b65156'};(function() {var ga = document.createElement('script');ga.type = 'text/javascript';ga.async=true; ga.defer=true;ga.src = '//live.vnpgroup.net/client/tracking.js?id=6766352';var s = document.getElementsByTagName('script');s[0].parentNode.insertBefore(ga, s[0]);})();</script>                      
    <div class="fullwidthbanner-container">
        <div class="fullwidthbanner">
            <div class="bannercontainer" >
                <div class="banner" >
                    <ul>
                        <!-- THE FIRST SLIDE -->
                        @foreach($slide as $row)
                            <li data-transition="boxfade" data-slotamount="20" class="active-revslide" style="width: 100%;
                             height: 100%; overflow: hidden; z-index: 18; visibility: hidden; opacity: 0;">
                            <div class="slotholder" style="width:100%;height:100%;" data-duration="undefined" data-zoomstart="undefined"
                                 data-zoomend="undefined" data-rotationstart="undefined" data-rotationend="undefined"
                                 data-ease="undefined" data-bgpositionend="undefined" data-bgposition="undefined"
                                 data-kenburns="undefined" data-easeme="undefined" data-bgfit="undefined"
                                 data-bgfitend="undefined" data-owidth="undefined" data-oheight="undefined">
                                <div class="tp-bgimg defaultimg" data-lazyload="undefined" data-bgfit="cover"
                                     data-bgposition="center center" data-bgrepeat="no-repeat"
                                     data-lazydone="undefined" src="source/image/slide/{{$row->image}}"
                                     data-src="source/image/slide/{{$row->image}}" style="background-color: rgba(0, 0, 0, 0);
                                      background-repeat: no-repeat; background-image: url('source/image/slide/{{$row->image}}');
                                      background-size: cover;
                                      background-position: center center; width: 100%; height: 100%; opacity: 1; visibility: inherit;">
                                </div>
                            </div>

                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="tp-bannertimer"></div>
        </div>
    </div>
    <!--slider-->
    </div>
    <div class="container">
        <div id="content" class="space-top-none">
            <div class="main-content">
                <div class="space60">&nbsp;</div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="beta-products-list">
                            <form action="" method="get">
                                <div>
                                    <h6>Lọc Theo Giá: </h6>
                                    <div class="filter-cost">
                                        <label for="min">Từ: </label>
                                        <input type="number" class="bg-gray" placeholder="Min: VNĐ" name="min" value="<?php if (!empty($min)) { echo $min;} ?>">
                                    </div>
                                    <div class="filter-cost">
                                        <label for="max">Đến: </label>
                                        <input type="number" class="bg-gray" placeholder="Max: VNĐ" name="max" value="<?php if (!empty($max)) { echo $max;} ?>">
                                    </div>
                                    <button type="submit" class="btn fa fa-play" style="margin-bottom: 3.5px"></button>
                                </div>
                            </form>
                            <h4>Sản phẩm mới</h4>
                            <div class="beta-products-details">
                                <p class="pull-left">Có {{$count_new_product}} sản phẩm</p>
                                <div class="clearfix"></div>
                            </div>

                            <div class="row">
                                <?php $i = 0;?>
                                @foreach($new_product as $row)
                                    <?php $i++?>
                                    <div class="col-sm-2 col-xs-4">
                                        <div class="single-item">
                                            @if($row->promotion_price != 0)
                                                <div class="ribbon-wrapper"><div class="ribbon sale">Sale</div></div>
                                            @endif
                                            <div class="single-item-header">
                                                <a href="{{route('chitiet',$row->id)}}">
                                                    <img src="source/image/product/{{$row->image}}" alt="" height="200px">
                                                </a>
                                            </div>
                                            <div class="single-item-body">
                                                <p class="single-item-title">{{$row->name}}</p>
                                                @if($row->promotion_price == 0)
                                                    <p class="single-item-price singer-item-phone">
                                                        <span>{{number_format($row->unit_price)}} VNĐ</span><br><br>
                                                    </p>
                                                @else
                                                    <p class="single-item-price">
                                                        <span class="flash-del">{{number_format($row->unit_price)}} VNĐ</span><br>
                                                        <span class="flash-sale">{{number_format($row->promotion_price)}} VNĐ</span>
                                                    </p>
                                                @endif
                                            </div>
                                            <div>
                                                <ul class="list-inline row">
                                                    <li class="col-6">
                                                        <span>{{number_format($row->rating_point)}}</span>
                                                        <i class="fa fa-star" style="color: yellow" aria-hidden="true"></i>
                                                        {{--<svg class="rate-and-comment" src="source/image/thumbs-up.svg">--}}
                                                        <span>({{$row->rating_quantity}})</span>
                                                    </li>
                                                    <li class="col-6">
                                                        <i class="fa fa-comments-o" style="color: #0e76a8" aria-hidden="true"></i>
                                                        <span>({{$row->comment_quantity}})</span>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="single-item-caption">
                                                <a class="add-to-cart pull-left"  product_id="{{$row->id}}" ><i class="fa fa-shopping-cart"></i></a>
                                                <a class="beta-btn primary" href="{{route('chitiet',['id' => $row->id ])}}">Details <i class="fa fa-chevron-right"></i></a>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                    </div>
                                        @if($i%6==0)
                                            <div class="space50">&nbsp;</div>
                                        @endif
                                @endforeach
                            </div>
                            <div class="row">{{$new_product->appends(request()->input())->links()}}</div>
                        </div> <!-- .beta-products-list -->

                        <div class="space50">&nbsp;</div>

                        <div class="beta-products-list">
                            <h4>Sản phẩm khuyến mại</h4>
                            <div class="beta-products-details">
                                <p class="pull-left">Có {{$count_sanphamkhuyenmai}} sản phẩm</p>
                                <div class="clearfix"></div>
                            </div>
                            <?php $i = 0;?>
                            <div class="row">
                            @foreach($sanphamkhuyenmai as $row)
                                <?php $i++?>

                                <div class="col-sm-2 col-xs-4">
                                    <div class="single-item">
                                        <div class="ribbon-wrapper"><div class="ribbon sale">Sale</div></div>

                                        <div class="single-item-header">
                                            <a href="{{route('chitiet',['id' => $row->id ])}}"><img src="source/image/product/{{$row->image}}"
                                            </a>
                                        </div>
                                        <div class="single-item-body">
                                            <p class="single-item-title">{{$row->name}}</p>
                                            <p class="single-item-price">
                                                <span class="flash-del">{{number_format($row->unit_price)}} VNĐ</span><br>
                                                <span class="flash-sale">{{number_format($row->promotion_price)}} VNĐ</span>
                                            </p>
                                        </div>
                                        <div>
                                            <ul class="list-inline row">
                                                <li class="col-6">
                                                    <span>{{number_format($row->rating_point)}}</span>
                                                    <i class="fa fa-star" style="color: yellow" aria-hidden="true"></i>
                                                    {{--<svg class="rate-and-comment" src="source/image/thumbs-up.svg">--}}
                                                    <span>({{$row->rating_quantity}})</span>
                                                </li>
                                                <li class="col-6">
                                                    <i class="fa fa-comments-o" style="color: #0e76a8" aria-hidden="true"></i>
                                                    <span>({{$row->comment_quantity}})</span>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="single-item-caption">
                                            <a class="add-to-cart pull-left"  product_id="{{$row->id}}" ><i class="fa fa-shopping-cart"></i></a>
                                            <a class="beta-btn primary" href="{{route('chitiet',['id' => $row->id ])}}">Details <i class="fa fa-chevron-right"></i></a>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>
                                </div>
                                @if($i%6==0)
                                    <div class="space40">&nbsp;</div>
                                @endif
                            @endforeach
                            </div>
                            <div class="row">{{$sanphamkhuyenmai->appends(request()->input())->links()}}</div>
                        </div> <!-- .beta-products-list -->

                        <div class="space50">&nbsp;</div>

                        <div class="beta-products-list">
                            <h4>Các sản phẩm gợi ý</h4>
                            <div class="beta-products-details">
                                <!-- {{--<p class="pull-left">Có {{count($count_product)}} sản phẩm</p>--}} -->
                                <div class="clearfix"></div>
                            </div>

                            <div class="row">
                                <?php $i = 0;?>
                                @foreach($suggestProducts as $row)
                                    <?php $i++?>
                                    <div class="col-sm-2 col-xs-4">
                                        <div class="single-item">
                                            @if($row->promotion_price != 0)
                                                <div class="ribbon-wrapper"><div class="ribbon sale">Sale</div></div>
                                            @endif
                                            <div class="single-item-header">
                                                <a href="{{route('chitiet',$row->id)}}">
                                                    <img src="source/image/product/{{$row->image}}" alt="" height="200px">
                                                </a>
                                            </div>
                                            <div class="single-item-body">
                                                <p class="single-item-title">{{$row->name}}</p>
                                                @if($row->promotion_price == 0)
                                                    <p class="single-item-price singer-item-phone">
                                                        <span>{{number_format($row->unit_price)}} VNĐ</span><br><br>
                                                    </p>
                                                @else
                                                    <p class="single-item-price">
                                                        <span class="flash-del">{{number_format($row->unit_price)}} VNĐ</span><br>
                                                        <span class="flash-sale">{{number_format($row->promotion_price)}} VNĐ</span>
                                                    </p>
                                                @endif
                                            </div>
                                            <div>
                                                <ul class="list-inline row">
                                                    <li class="col-6">
                                                        <span>{{number_format($row->rating_point)}}</span>
                                                        <i class="fa fa-star" style="color: yellow" aria-hidden="true"></i>
                                                        {{--<svg class="rate-and-comment" src="source/image/thumbs-up.svg">--}}
                                                        <span>({{$row->rating_quantity}})</span>
                                                    </li>
                                                    <li class="col-6">
                                                        <i class="fa fa-comments-o" style="color: #0e76a8" aria-hidden="true"></i>
                                                        <span>({{$row->comment_quantity}})</span>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="single-item-caption">
                                                <a class="add-to-cart pull-left"  product_id="{{$row->id}}" ><i class="fa fa-shopping-cart"></i></a>
                                                <a class="beta-btn primary" href="{{route('chitiet',['id' => $row->id ])}}">Details <i class="fa fa-chevron-right"></i></a>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                    </div>
                                    @if($i%6==0)
                                        <div class="space50">&nbsp;</div>
                                    @endif
                                @endforeach
                            </div>
                        </div> <!-- .beta-products-list -->

                </div> <!-- end section with sidebar and main content -->
            </div> <!-- .main-content -->
        </div> <!-- #content -->
    </div> <!-- .container -->
@endsection

<style>
    .filter-cost {
        display: inline-block;
        padding: 5px 10px;
    }
    .filter-cost label {
        width: 30px;
        margin-right: 10px;
    }
    .filter-cost input {

    }
</style>