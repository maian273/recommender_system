<div id="header">
    <div class="header-top">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <div class="contactinfo">
                        <ul class="nav nav-pills">
                            <li><a href="#"><i class="fa fa-phone"></i> +2 95 01 88 821</a></li>
                            <li><a href="#"><i class="fa fa-envelope"></i> info@domain.com</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- <div class="pull-right auto-width-right">
                <ul class="top-details menu-beta l-inline">
                    @if(Auth::check())
                    <li><a href="{{route('thaydoitk')}}" id="user"><i class="fa fa-user"></i>{{(Auth::user())->full_name}}</a></li>
                        <li><a href="{{route('dangxuat')}}">Đăng xuất</a></li>
                    @else
                        <li><a href="{{route('dangky')}}">Đăng ký</a></li>
                        <li><a href="{{route('dangnhap')}}">Đăng nhập</a></li>
                    @endif
                </ul>
            </div> -->
            <div class="clearfix"></div>
        </div> <!-- .container -->
    </div> <!-- .header-top -->

    <div class="header-middle"><!--header-middle-->
        <div class="container">
            <div class="row">
                <div class="col-sm-4">
                    <div class="logo pull-left">
                        <a href="{{route('trangchu')}}"><img src="images/home/logo.png" alt="" /></a>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="shop-menu pull-right">
                        <ul class="nav navbar-nav">
                            @if(Auth::check())
                            <li><a href="{{route('thaydoitk')}}"><i class="fa fa-user"></i>{{(Auth::user())->full_name}}</a></li>
                            <li><a href="{{route('lichsu')}}"><i class="fa fa-star"></i>Lịch sử</a></li>
                            <li><a href="{{route('dangxuat')}}"><i class="fa fa-crosshairs"></i>Đăng xuất</a></li>
                            @else
                            <li><a href="{{route('dangnhap')}}"><i class="fa fa-lock"></i>Đăng nhập</a></li>
                            <li><a href="{{route('dangky')}}"><i class="fa fa-user"></i>Đăng ký</a></li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div><!--/header-middle-->

    <div class="header-body">
        <div class="container beta-relative">
            <div class="pull-right beta-components space-left ov">
                <div class="space10">&nbsp;</div>
                <div class="beta-comp">
                    <form role="search" method="get" id="searchform" action="{{route('search')}}">
                        <input type="text" value="" class="search-input" name="s" id="s" autocomplete="off" placeholder="Nhập từ khóa..." />
                        <button class="fa fa-search" type="submit" id="searchsubmit"></button>
                    </form>
                </div>

                <div class="beta-comp">
                    <div class="cart" id="div-cart">
                        <div class="beta-select"><i class="fa fa-shopping-cart"></i><span id="count">Giỏ hàng (Trống)</span><i class="fa fa-chevron-down"></i></div>
                    </div> <!-- .cart -->
                </div>
            </div>
            <div class="clearfix"></div>
        </div> <!-- .container -->
    </div> <!-- .header-body -->
    
    <div class="header-bottom" style="background-color: #f7a93f;">
        <div class="container">
            <a class="visible-xs beta-menu-toggle pull-right" href="#"><span class='beta-menu-toggle-text white'>Menu</span></a>
            <div class="visible-xs clearfix"></div>
            <nav class="main-menu">
                <ul class="nav nav-pills">
                    <li><a href="{{route('trangchu')}}">Trang chủ</a></li>
                    <li>
                        <a>Sản phẩm <span class="badge"><?=count($loaisp) ?></span></a>
                        <ul class="sub-menu">
                            @foreach($loaisp as $row)
                                <li><a href="{{route('loaisp',['id' => $row->id])}}">{{$row->name}}</a></li>
                            @endforeach
                        </ul>
                    </li>
                    <li><a href="{{route('gioithieu')}}">Giới thiệu</a></li>
                    <li><a href="{{route('lienhe')}}">Liên hệ</a></li>
                </ul>
                <div class="clearfix"></div>
            </nav>
        </div> <!-- .container -->
    </div> <!-- .header-bottom -->
    <div id="thonbao" style="display: none">
        @if(session('thongbao'))
            <p class="nd">{{ session('thongbao') }}</p>
        @endif
    </div>

</div> <!-- #header -->
