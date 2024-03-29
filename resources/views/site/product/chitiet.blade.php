@extends('site.layout')
@section('content')
    <div class="container">
        <div id="content">
            <div class="row">
                <div class="col-sm-9">

                    <div class="row">
                        <div class="col-sm-4 col-xs-5">
                            <img src="source/image/product/{{$product->image}}" alt="">
                        </div>
                        <div class="col-sm-8 col-xs-7">
                            <div class="single-item-body">
                                <p class="single-item-title"><b>{{$product->name}}</b></p>
                                @if($product->promotion_price == 0)
                                    <p class="single-item-price">
                                        <span>{{number_format($product->unit_price)}} VNĐ</span>
                                    </p>
                                @else
                                    <p class="single-item-price">
                                        <span class="flash-del">{{number_format($product->unit_price)}} VNĐ</span>
                                        <span class="flash-sale">{{number_format($product->promotion_price)}} VNĐ</span>
                                    </p>
                                @endif
                            </div>
                            <div>
                                <ul class="list-inline row" style="max-width: 250px; margin-left: 10px;">
                                    <li class="col-6">
                                        <span>{{number_format($product->rating_point)}}</span>
                                        <i class="fa fa-star" style="color: yellow" aria-hidden="true"></i>
                                        {{--<svg class="rate-and-comment" src="source/image/thumbs-up.svg">--}}
                                        <span>({{$product->rating_quantity}})</span>
                                    </li>
                                    <li class="col-6">
                                        <i class="fa fa-comments-o" style="color: #0e76a8" aria-hidden="true"></i>
                                        <span>({{$product->comment_quantity}})</span>
                                    </li>
                                </ul>
                            </div>

                            <div class="clearfix"></div>
                            <div class="space20">&nbsp;</div>

                            <p>Số lượng:</p>
                            <div class="single-item-options">
                                <input type="number" min="1" value="1" class="so-luong">
                                <a class="add-to-cart" product_id="{{$product->id}}" href=""><i
                                            class="fa fa-shopping-cart"></i></a>
                                <div class="clearfix"></div>
                            </div>
                            @if($checkNotRated)
                            <div class="rate">
                                <h6>Đánh giá sản phẩm<span class="glyphicon glyphicon-pencil"></span></h6>
                                <form action="rating/{{$product->id}}" role="form" method="post">
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    <div class="rate">
                                        <input type="radio" id="star5" name="rate" value="5" />
                                        <label for="star5" title="text">5 stars</label>
                                        <input type="radio" id="star4" name="rate" value="4" />
                                        <label for="star4" title="text">4 stars</label>
                                        <input type="radio" id="star3" name="rate" value="3" />
                                        <label for="star3" title="text">3 stars</label>
                                        <input type="radio" id="star2" name="rate" value="2" />
                                        <label for="star2" title="text">2 stars</label>
                                        <input type="radio" id="star1" name="rate" value="1" />
                                        <label for="star1" title="text">1 star</label>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Gửi</button>
                                </form>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="space40">&nbsp;</div>
                    <div class="woocommerce-tabs">
                        <ul class="tabs">
                            <li><a href="#tab-description">Mô tả</a></li>
                        </ul>

                        <div class="panel" id="tab-description">
                            <p>{{$product->description}}</p>
                        </div>

                        @if(Auth::user())
                            <div class="well">
                                @if(session('message'))
                                    <div class="alert alert-success">
                                        {{session('message')}}
                                    </div>
                                @endif

                                <h4>Viết bình luận ...<span class="glyphicon glyphicon-pencil"></span></h4>
                                <form action="comment/{{$product->id}}" role="form" method="post">
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    <div class="form-group">
                                        <textarea name="content" class="form-control" rows="3"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Gửi</button>
                                </form>
                            </div>
                        @endif
                        <div class="comment">
                        @foreach($product->comments as $comment)
                            <!-- Comment -->
                                <div class="media">
                                    <div class="media-body">
                                        <h7 class="media-heading">{{$comment->user->full_name}}
                                            <small>{{$comment->created_at}}</small>
                                        </h7>
                                        <div><b>{{$comment->content}}</b></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="space50">&nbsp;</div>
                        <div class="beta-products-list">
                            <h4>Sản phẩm tương tự</h4>

                            <div class="row">
                                @foreach($sanphamtt as $row)
                                    <div class="col-sm-3 col-xs-4">
                                        <div class="single-item">
                                            @if($row->promotion_price != 0)
                                                <div class="ribbon-wrapper">
                                                    <div class="ribbon sale">Sale</div>
                                                </div>
                                            @endif
                                            <div class="single-item-header">
                                                <a href="{{route('chitiet',$row->id)}}">
                                                    <img src="source/image/product/{{$row->image}}" alt=""
                                                         height="200px">
                                                </a>
                                            </div>
                                            <div class="single-item-body">
                                                <p class="single-item-title">{{$row->name}}</p>
                                                @if($row->promotion_price == 0)
                                                    <p class="single-item-price">
                                                        <span>{{number_format($row->unit_price)}} VNĐ</span><br><br>
                                                    </p>
                                                @else
                                                    <p class="single-item-price">
                                                    <span class="flash-del">{{number_format($row->unit_price)}}
                                                        VNĐ</span><br>
                                                        <span class="flash-sale">{{number_format($row->promotion_price)}}
                                                            VNĐ</span>
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
                                            <div class="single-item-caption" style="margin-bottom: 20px">
                                                <a class="add-to-cart pull-left" product_id="{{$row->id}}"><i
                                                            class="fa fa-shopping-cart"></i></a>
                                                <a class="beta-btn primary" href="{{route('chitiet',$row->id)}}">Details
                                                    <i
                                                            class="fa fa-chevron-right"></i></a>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div> <!-- .beta-products-list -->
                    </div>
                </div>
                <div class="col-sm-3 aside">
                    <div class="widget">
                        <h3 class="widget-title">Các sản phẩm gợi ý</h3>
                        <div class="widget-body">
                            <div class="beta-sales beta-lists">
                                @foreach($suggests as $row)
                                    <div class="media beta-sales-item">
                                        <a class="pull-left" href="{{route('chitiet',['id' => $row->id])}}"><img
                                                    src="source/image/product/{{$row->image}}" alt=""></a>
                                        <div class="media-body">
                                            <p>{{$row->name}}</p>
                                            @if($row->promotion_price == 0)
                                                <span class="beta-sales-price">{{number_format($row->unit_price)}}
                                                    VNĐ</span>
                                            @else
                                                <span class="beta-sales-price">{{number_format($row->promotion_price)}}
                                                    VNĐ</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div> <!-- best sellers widget -->
                    <div class="widget">
                        <h3 class="widget-title">Sản phẩm bán chạy</h3>
                        <div class="widget-body">
                            <div class="beta-sales beta-lists">
                                @foreach($bestsell as $row)
                                    <div class="media beta-sales-item">
                                        <a class="pull-left" href="{{route('chitiet',['id' => $row->id])}}"><img
                                                    src="source/image/product/{{$row->image}}" alt=""></a>
                                        <div class="media-body">
                                            <p>{{$row->name}}</p>
                                            @if($row->promotion_price == 0)
                                                <span class="beta-sales-price">{{number_format($row->unit_price)}}
                                                    VNĐ</span>
                                            @else
                                                <span class="beta-sales-price">{{number_format($row->promotion_price)}}
                                                    VNĐ</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div> <!-- best sellers widget -->
                </div>
            </div> <!-- #content -->
        </div> <!-- .container -->
@endsection
