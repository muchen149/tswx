@extends('inheritance')
@section('水丁管家商城')
    商城
@endsection
@section('css')
    <meta charset="UTF-8">
    <title>大美管家-更贴心的家政服务</title>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link rel="stylesheet" href="{{asset('/sd_css/swiper.min.css')}}">
    <link rel="stylesheet" href="{{asset('/sd_css/new_common.css')}}">
    <link rel="stylesheet" href="{{asset('/sd_css/shop.css')}}">
    <link rel="stylesheet" href="{{asset('/sd_css/new_index.css')}}">
    <script type="text/javascript" src="{{asset('/sd_js/jquery-1.11.2.min.js')}}"></script>
    <style>
        a:link, a:visited, a:hover, a:active{
            text-decoration:none;
        }
    </style>
@endsection
@section('content')
    <body>
    <div class="container container-ys" style="padding-bottom: 15px;">
        <div class="swiper-container shop-banner">
            <div class="swiper-wrapper">
                {{--<div class="swiper-slide"><a href=""><img src="/ys_img/ys01.jpg" width="100%" alt=""></a></div>--}}
                {{--<div class="swiper-slide"><a href=""><img src="/ys_img/ys02.jpg" width="100%" alt=""></a></div>--}}
                @foreach($advertList['advert_a0301'] as $val)
                    <div class="swiper-slide"><a href="{{$val->out_url}}"><img src="{{$val->images}}" width="100%" alt=""></a></div>
                @endforeach
            </div>
            <!-- Add Pagination -->
            <div class="swiper-pagination"></div>
        </div>
        {{--月嫂服务修给后--}}
        <div class="ys-qEnter">
            <ul>
                <li><a href="/ys/goods/spuDetail/1201284"><i class="icon"></i><i class="t">月嫂服务</i></a></li>
                <li><a href="/ys/goods/spuDetail/1201282"><i class="icon"></i><i class="t">催乳师</i></a></li>
                <li><a href="/ys/goods/spuDetail/1201283"><i class="icon"></i><i class="t">育儿嫂</i></a></li>
                <li><a href="/ys/goods/spuDetail/1201285"><i class="icon"></i><i class="t">产后康复</i></a></li>
            </ul>
        </div>
        <img src="../ys_img/_01_04.png" alt="">
        <div class="title">
            <h4 style="font-family: 'Microsoft YaHei';font-weight: 400;"><img src="../ys_img/icon_14.png" width="30%">人气推荐<img src="../ys_img/icon_17.png" width="30%"></h4>
        </div>
        <div class="details">
            <ul>
                <li><a href="/ys/goods/spuDetail/1201284"><img src="../ys_img/_01_05.png" width="100%" alt></a></li>
                <li>
                    <a href="/ys/goods/spuDetail/1201282">
                        <img src="../ys_img/_01_06.png" width="100%" alt>
                    </a>
                    <a href="/ys/goods/spuDetail/1201283">
                        <img src="../ys_img/_01_07.png" width="100%" alt>
                    </a>
                    <a href="/ys/goods/spuDetail/1201285">
                        <img src="../ys_img/_01_08.png" width="100%" alt>
                    </a>
                </li>
            </ul>
        </div>
        {{--月嫂服务修给前--}}
        {{--<div  class="serve">
            <div  class="sever_icon">
                <img class="sever_img" src="/ys_img/ysfw.png">
            </div>
            <ul  class="sever_intro">
                <li class="sever_bt">月嫂服务</li>
                <li class="sever_js">为产妇和新生儿提供24小时全方位护理</li>
            </ul>
            --}}{{--<div class="sever_order"><a href="/ys/goods/spuList/1/10/172">预约>></a></div>--}}{{--
            <div class="sever_order"><a href="/ys/goods/spuDetail/1201284">预约>></a></div>
            <div style="clear:both;"></div>
        </div>
        <div  class="serve1">
            <div  class="sever_icon">
                <img class="sever_img" src="/ys_img/yes.png">
            </div>
            <ul  class="sever_intro1">
                <li class="sever_bt">育儿嫂</li>
                <li class="sever_js">婴儿喂养、起居照顾、早期教育、行为培养、婴儿健康及家务</li>
            </ul>
            --}}{{--<div class="sever_order"><a href="/ys/goods/spuList/1/10/173">预约>></a></div>--}}{{--
            <div class="sever_order"><a href="/ys/goods/spuDetail/1201283">预约>></a></div>
            <div style="clear:both;"></div>
        </div>
        <div  class="serve1">
            <div  class="sever_icon">
                <img class="sever_img" src="/ys_img/trs.png">
            </div>
            <ul  class="sever_intro1">
                <li class="sever_bt">通乳师</li>
                <li class="sever_js">解决产妇奶少、涨奶、乳房胀痛、有大小硬块、宝宝吃不出奶等各种乳房问题。</li>
            </ul>
            --}}{{--<div class="sever_order"><a href="/ys/goods/spuList/1/10/174">预约>></a></div>--}}{{--
            <div class="sever_order"><a href="/ys/goods/spuDetail/1201282">预约>></a></div>
            <div style="clear:both;"></div>
        </div>
        <div  class="serve1">
            <div  class="sever_icon">
                <img class="sever_img" src="/ys_img/chkf.png">
            </div>
            <ul  class="sever_intro1">
                <li class="sever_bt">产后康复</li>
                <li class="sever_js">产后形体恢复，产后子宫恢复和产后心理恢复及产后减脂塑形等。</li>
            </ul>
            --}}{{--<div class="sever_order"><a href="/ys/goods/spuList/1/10/175">预约>></a></div>--}}{{--
            <div class="sever_order"><a href="/ys/goods/spuDetail/1201285">预约>></a></div>
            <div style="clear:both;"></div>
        </div>--}}



        <div class="ysmain-nav">
            <ul style="background:#F2F2F2">
                <li class="active navBtn"><a href="{{asset('/ys')}}"><span class="icon"></span><span class="text">首页</span></a></li>
                <li class="navBtn"><a href="{{asset('/ys/order/index')}}"><span class="icon"></span><span class="text">服务单</span></a></li>
                {{--<li class="navBtn"><a href="{{asset('/cart/index')}}"><span class="icon"></span><span class="text">购物车</span></a></li>--}}
                <li class="navBtn"><a href="{{asset('/ys/index')}}"><span class="icon"></span><span class="text">我的</span></a></li>
            </ul>
        </div>
        {{--<div class="main-nav">
            <ul>
                <li class="active navBtn"><a href="{{asset('/shop/index')}}"><span class="icon"></span><span class="text">管家服务</span></a></li>
                <li class=" navBtn"><a href="{{asset('/sd_shop/shop')}}"><span class="icon"></span><span class="text">集市</span></a></li>
                <li class="calling navBtn"><a href="{{asset('member/call')}}"><span class="icon"><i></i></span><span class="text">召唤管家</span></a></li>
                <li class=" navBtn"><a href="{{asset('/cart/index')}}"><span class="icon"></span><span class="text">购物车</span></a></li>
                <li class=" navBtn"><a href="{{asset('/personal/index')}}"><span class="icon"></span><span class="text">我的</span></a></li>
            </ul>
        </div>--}}
    </div>
    </body>
@endsection
@section('js')
    <script type="text/javascript" src="{{asset('/sd_js/swiper.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('/sd_js/shop.js')}}"></script>
      {{-- $('.navBtn').click(function () {
    $(this).addClass("active").siblings().removeClass("active");
    });--}}
@endsection