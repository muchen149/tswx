@extends('inheritance')
@section('首页')
    首页
@endsection
@section('css')
    <meta charset="UTF-8">
    <title>{!!config('constant')['eLife']['title']!!}</title>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link rel="stylesheet" href="{{asset('/elife_css/swiper.min.css')}}">
    <link rel="stylesheet" href="{{asset('/elife_css/new_common.css')}}">
    <link rel="stylesheet" href="{{asset('/elife_css/shop.css')}}">
    <style>
        a:link, a:visited, a:hover, a:active{
            text-decoration:none;
        }
        .head{
            width: 100%;
        }
        /*.head img{
            width: 100%;
        }*/
        .column {
            background-color: #fff;
            padding: 1px 2px;
            line-height:15px;
        }
        .column .name{
            width: 25%;
            float: left;
            padding:  2px;
            box-sizing: border-box;
            text-align: center;
        }
        /*.cloumn_list {
            font-size: inherit;
            font-family: "微软雅黑";
            font-weight: 600;
            line-height: 50px;
            padding: 0px 10px;
        }
        .cloumn_list p:last-child{
            color:#fff;
        }*/

        .list-wrap {
            height: 50px;
            font-size: 14px;
            font-family: "微软雅黑";
            background-color: white;
        }
        .lists{
            background-color: #f56a89;
        }
        .list-wrap .column_name{
            font-size: 14px;
        }
        .list-wrap li:first-child{
            width:100%;
            text-align: center;
        }
        .list-wrap li:nth-child(2){
            width: 20%;
            text-align: end;
        }
		
		 /*头部导航*/
        .head .nav_tab{
            position: fixed;
            top: 0;
            padding: 0 10px;
            line-height: 38px;
            width:100%;
            z-index: 999999;
            font-family: "微软雅黑";
        }
        .head .nav_tab .return{
            display: inline-block;
            width: 34%;
        }
        .column_img{
            display:inline-block;
            margin:5px auto;
            width: 20px;
            height: 20px;
            /*border-radius:100px;*/
        }
        .column_img img{
            width:100%;
            min-height:100%; text-align:center;
        }
    </style>
    <script type="text/javascript" src="{{asset('/sd_js/jquery-1.11.2.min.js')}}"></script>
@endsection
@section('content')
    <body>
        <div class="head">
            <div class="nav_tab b-white">
                <a class="return font-14 color-80" onclick="hybrid_app.back();"><img src="{{asset('sd_img/next.png')}}" alt="" class="size-26" style="transform:rotate(180deg);"></a>
                @if(!empty($enter_id) && $enter_id == 1)
                    <a class="title font-18" style="color:#000000;" href="#">网易严选专区</a>
                @elseif($enter_id == 2 || $enter_id == 3)
                    <a class="title font-18" style="color:#000000;" href="#">考拉海购精选</a>
                @endif
                {{--<a class="font-14 color-80" onclick="hybrid_app.share();" style="float: right"><img src="{{asset('elife_img/fenxiang.png')}}" alt="" class="size-26"></a>--}}
            </div>
        </div>
		@if(empty($advertList[0]->images))
		    <a href="#"><img src="{{asset('sd_img/noGoods.jpg')}}" alt="" style="margin-top:40px;width:100%"></a>
		@else
			<a href="{{$advertList[0]->out_url}}"><img src="{{$advertList[0]->images}}" alt="" style="margin-top:40px;width:100%"></a>
		@endif
        @if(!empty($enter_id) && $enter_id == 1)
            <div><img src="{{asset('/elife_img/yanxuan.jpg')}}" alt="" width="100%"></div>
        @elseif($enter_id == 2 || $enter_id == 3)
            <div><img src="{{asset('/elife_img/kaola.jpg')}}" alt="" width="100%"></div>
        @endif
        <div class="swiper-container sh-swiper base nav" id="fixPara"  style="background-color: white;height:58px;">
        @if(!empty($enter_id) && $enter_id == 1)
            <div class="swiper-wrapper" style="background-color: #8b9363">
        @elseif($enter_id == 2 || $enter_id == 3)
            <div class="swiper-wrapper" style="background-color: #f56a89">
        @endif
                @if($label_goods_list)
                    @foreach($label_goods_list as $label_list)
                        <div class="swiper-slide" style="overflow:hidden;display: inline-block;height: 60px">
                            <a href="{{asset('elife/classGoods/'.$label_list['elife_column_id'])}}" style="color:#000">
                                @if($label_list['column_url'])
                                    <div class="column_img" style="margin: 7px 30px -5px 30px;">
                                        <img src="{{asset($label_list['column_url'])}}" width="100%" alt="">
                                    </div>
                                @endif
                                <div style="vertical-align: middle;display: table-cell;width:10%">
                                    <div style="text-decoration:none;text-align: center;">
                                        <nobr style="font-family:微软雅黑;font-size: 10px;color: white">{{$label_list['elife_column_name']}}</nobr>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
        {{--<div><a href="{{asset('/elife/coupon/couponActive')}}"><img src="{{asset('/elife_img/coupon.png')}}" alt="" width="100%"></a></div> //优惠券图片--}}
        <div class="container" style="" id="content" >
            <div class="s-listAll" style="padding: 0px 10px">
                @if(!empty($label_goods_list))
                    @foreach($label_goods_list as $key=>$label_list)

                        @if(!empty($enter_id) && $enter_id == 1)
                            <div class="shop" style="background-color:#f0f0f0; margin:0px -10px;">
                        @elseif($enter_id == 2 || $enter_id == 3)
                            <div class="shop" style="background-color:#f56a89; margin:0px -10px;">
                        @endif
                         @if($label_list['sort'] < 9)
                             @if(!empty($enter_id) && $enter_id == 1)
                                 <lu class="flex-between color-80 list-wrap">
                             @elseif($enter_id == 2 || $enter_id == 3)
                                 <lu class="flex-between color-80 list-wrap lists">
                             @endif
                             @if(!empty($enter_id) && $enter_id == 1)
                                 <li class="column_name" style="color: #000000; letter-spacing:3px;">
                             @elseif($enter_id == 2 || $enter_id == 3)
                                 <li class="column_name" style="color: white; letter-spacing:3px;">
                             @endif
                                 <b>{{$label_list['elife_column_name']}}</b>
                                 </li>
                                 </lu>
                         @endif
                            <ul class="clearFix">
                                @foreach($label_list['g_list'] as $k => $good_info)
                                    <li class="goods">
                                        <a href="{{url('/elife/goods/spuDetail/'.$good_info['spu_id'])}}" style="background-color: white"><img src="{{$good_info['main_image']}}" width="100%" height="120px" alt="" style="background-color: #fafafa">
                                            <p style="white-space: nowrap;overflow: hidden;text-overflow: ellipsis;text-align: start;font-size: 10px;margin: 0px 0px -5px 5px">{{$good_info['spu_name']}}</p>
                                            @if(!empty($good_info['ad_info']))
                                                <div class="shop_info">
                                                    {!! $good_info['ad_info'] !!}
                                                </div>
                                            @endif
                                            <p style="color:red;height: 18px;margin: 1px 0px 0px 5px;font-size: 10px">￥<b>{{$good_info['spu_price']}}</b>
                                                @if($grade!=10)
                                                    <del class="font-12" style="color:#999999;height: 20px;font-size: 8px;">¥ {{$good_info['spu_market_price']}}</del>
                                                @endif
                                            </p>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                          </lu>
                         </div>
                    @endforeach
                    <div style="color: #ccc;text-align: -webkit-center;">————— 我是有底线的 —————</div>
                @else
                    <div><a href="javascript:void(0)"><img src="{{asset('sd_img/noGoods.jpg')}}" width="100%" alt=""></a></div>
                @endif
            </div>
            <div class="main-nav">
                <ul>
                    <li class="active navBtn"><a href="{{asset('/elife/eLifeIndex')}}"><span class="icon"></span><span class="text" style="color: #000000">首页</span></a></li>
                    <li class=" navBtn"><a href="{{asset('/elife/cart/index')}}">@if($goods_num_in_cart < 100)<span class="cart_num">{{$goods_num_in_cart}}</span>@else<span class="cart_big_num">99+</span>@endif<span class="icon"></span><span class="text" style="color: #000000">购物车</span></a></li>
                    <li class=" navBtn"><a href="{{asset('/elife/personal/index')}}"><span class="icon"></span><span class="text" style="color: #000000">我的</span></a></li>
                </ul>
            </div>
        </div>
    </body>
@endsection
@section('js')
    <script type="text/javascript" src="{{asset('/elife_js/swiper.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('/elife_js/index.js')}}"></script>
    <script type="text/javascript" src="{{asset('/elife_js/hybrid_app.js')}}"></script>
    <script type="text/javascript">
window.onload=
        function(){
            var oDiv = document.getElementById("fixPara"),
                    H = 0,
                    Y = oDiv
            while (Y) {
                H += Y.offsetTop;
                Y = Y.offsetParent;
            }
            window.onscroll = function()
            {
                var s = document.body.scrollTop || document.documentElement.scrollTop
                if(s>H) {
                    oDiv.style = "position:fixed;top:39px;background-color:white;"
                } else {
                    oDiv.style = ""
                }
            }
        }
    </script>
@endsection