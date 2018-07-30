@extends('inheritance')

@section('title')
    商品分类
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('elife_css/mui.min.css')}}">
    <link rel="stylesheet" href="{{asset('/elife_css/new_common.css')}}">
    <link rel="stylesheet" href="{{asset('/elife_css/e_shop.css')}}">
    <link rel="stylesheet" href="{{asset('/elife_css/swiper.min.css')}}">
    <style>

        .s-listAll{
            padding: 5px 10px;
        }
        /*阿里图标样式初始化 -start*/
        .icon{
            width: 1em;
            height: 1em;
            vertical-align: -0.15em;
            fill: currentColor;
            overflow: hidden;
        }
        /*阿里图标样式初始化 -end*/
        .head{
            width: 100%;
        }
        /*改写mui顶部标签切换组件的选中样式*/
        .swiper-wrapper .swiper-slide{
            overflow:hidden;display: inline-block;
        }
        .swiper-wrapper .active{
            border-bottom: 2px solid #e42f46 !important;
        }
        .mui-control-item.mui-active{
            color: #000!important;
            border-bottom: 2px solid #fff !important;
        }
        /*改写mui卡片视图content图片样式*/
        .mui-card-content img{
            width: 100%;
        }
        .mui-card-footer-r{
            border-left: 1px solid rgb(200,200,200);
            text-align: center;
            padding-left: 8px;
            margin-left: 10px;
            width: 70px;
        }
        .mui-card-footer:before{
            position: absolute;
            top: 0;
            right: 0;
            left: 0;
            height: 0px;
            content: '';
            -webkit-transform: scaleY(.5);
            transform: scaleY(.5);
            background-color: rgb(200, 199, 204);
        }
        .select{
            color: #f53a3a;
        }

        .popover{
            bottom: 0px!important;
            background-color: rgb(240,240,240);
        }
        .property-sure{
            width: 100%;
            height: 46px;
            background-color: #f53a3a;
        }
        .property-sure a{
            color: white;
        }
        .property-close{
            position: absolute;
            right: 16px;
            top: 12px;
        }

        .property-unselect{
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 3px 10px;
            margin-right: 15px;
            margin-bottom: 10px;
            border-radius: 10px;
            font-size: 12px;
            color: rgb(80,80,80);
            background-color: #e4e4e4;
        }
        .property-select{
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 3px 10px;
            margin-right: 15px;
            margin-bottom: 10px;
            border-radius: 10px;
            font-size: 12px;
            color: rgb(192,192,192) !important;
            background-color: #f53a3a;
        }
        .select-goods-property{
            width: 100%;
        }
        .select-goods-property p{
            margin-left: 3%;
            margin-top: 5px;
            margin-bottom: 8px;
        }
        .goods-head{
            display: flex;
            margin-left: 3%;
            height: 70px;
        }
        .goods-head ul:first-child{
            background-color: white;
            padding: 5px;
            border: 1px solid rgb(220,220,220);
            border-radius: 6px;
            margin-top: -30px;
        }
        .goods-head ul:first-child img{
            width: 90px;

        }
        .goods-head ul:last-child{
            margin-left: 5%;
            margin-top: 14px;

        }
        .goods-property{
            display: flex;
            margin-left: 3%;
            justify-content: flex-start;
            flex-wrap: wrap;
            align-items: center;
        }

        /*商品选择数量*/
        .gift-amount{
            height: 50px;
            margin-bottom: 10px;
        }
        .gift-amount ul:first-child{
            margin-left: 3%;
        }
        .gift-amount ul:last-child{
            margin-right: 3%;
            width: 117px;
        }
        .gift-amount ul:last-child input{
            box-sizing: border-box;
            text-align: center;
            width: 60px;
            height: 30px;
            border-top: 1px solid rgb(220,220,220);
            border-bottom: 1px solid rgb(220,220,220);
            border-left: none;
            border-right: none;
            outline: none;
        }
        .min{
            box-sizing: border-box;
            height: 30px;
            width: 30px;
            border: 1px solid rgb(220,220,220);
            border-radius: 4px 0 0 4px;

        }
        .add{
            box-sizing: border-box;
            height: 30px;
            width: 30px;
            border: 1px solid rgb(220,220,220);
            border-radius: 0 4px 4px 0;

        }

        .head .nav{
            position: fixed;
            top: 0;
            padding: 0 4px;
            line-height: 38px;
            width:100%;
            z-index: 999999;
            font-family: "微软雅黑";
            text-align: center;;
        }
        .head .nav .return{
            display: inline-block;
            width: 35%;
        }
        .column_img img{
            width:100%;
            min-height:100%; text-align:center;
        }
    </style>
@endsection

@section('content')
    <body>
    <div class="head">
        <div class="nav b-white">
            <a class="return font-14 color-80" style="position:absolute;left:-50px;top:0px;"href="javascript:history.go(-1)"><img src="{{asset('sd_img/next.png')}}" alt="" class="size-26" style="transform:rotate(180deg);"></a>
            <a class="font-18" style="color: #000000" href="#">{{$column->elife_column_name}}</a>
        </div>
        <div style="margin-top:40px;">
            <a href="#"><img src="{{$column->image_url}}" alt="" style="width:100%;height: 180px"></a>
        </div>
    </div>

    <div class="mui-segmented-control mui-segmented-control-inverted mui-segmented-control-primary swiper-container sh-swiperlk base" style="padding: 0 6px;background-color: #fff;  border-bottom:solid 1px #d4d2d2;">
        <div class="swiper-wrapper">
            @if($label_goods_list)
                @foreach($label_goods_list as $key => $label_list)
                    <div class="swiper-slide" id="class" code="{{$label_list['elife_goods_label_id']}}">
                        <a class="mui-control-item  font-14">{{$label_list['elife_goods_label_name']}}</a>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
    <div class="container" style="background:#fff;">
        <div class="s-listAll" id="product_list">
            @if(!empty($label_goods_list[0]))
            <ul class="clearFix">
                @if($label_goods_list[0]['goods_list'] != '')
                    @foreach($label_goods_list[0]['goods_list'] as $good_info)
                        <li>
                            <a href="{{url('/elife/goods/spuDetail/'.$good_info['spu_id'])}}"><img src="{{$good_info['main_image']}}" width="100%" alt="">
                                <h6 style="white-space: nowrap;overflow: hidden;text-overflow: ellipsis;text-align: start;">{{$good_info['spu_name']}}</h6>
                                <p style="color:red">￥&nbsp;{{$good_info['spu_plat_price']}}
                                    <del class="font-12" style="color:#ccc;">&nbsp;¥ {{$good_info['spu_market_price']}}</del>
                                </p>
                            </a>
                        </li>
                    @endforeach
                @endif
            </ul>
            <div class="scroll-load-wait" style="font-size: 12px; line-height: 2em; color: rgb(153, 153, 153); text-align: center; background: rgb(255, 255, 255); padding: 10px 0px;">正在加载数据...</div>
            @endif
            {{--@foreach($label_goods_list as $key => $label_list)
                <div id="item{{$label_list['elife_goods_label_id']}}" class="mui-control-content @if($key == 0) mui-active @endif ">
                    <ul class="clearFix">
                    @foreach($label_list['g_list'] as $k => $good_info)
                            <li>
                                <a href="{{url('/elife/goods/spuDetail/'.$good_info['spu_id'])}}"><img src="{{$good_info['main_image']}}" width="100%" alt="">
                                    <h6 style="white-space: nowrap;overflow: hidden;text-overflow: ellipsis;text-align: start;">{{$good_info['spu_name']}}</h6>
                                    <p style="color:red">￥&nbsp;{{$good_info['spu_price']}}
                                        @if($grade!=10)
                                            <del class="font-12" style="color:#ccc;">&nbsp;¥ {{$good_info['spu_market_price']}}</del>
                                        @endif
                                    </p>
                                </a>
                            </li>
                    @endforeach
                    </ul>
                </div>
            @endforeach--}}
        </div>
    </div>
    <div class="main-nav">
        <ul>
            <li class="active navBtn"><a href="{{asset('/elife/eLifeIndex')}}"><span class="icon"></span><span class="text">首页</span></a></li>
            <li class=" navBtn"><a href="{{asset('/elife/cart/index')}}">@if($goods_num_in_cart < 100)<span class="cart_num">{{$goods_num_in_cart}}</span>@else<span class="cart_big_num">99+</span>@endif<span class="icon"></span><span class="text">购物车</span></a></li>
            <li class=" navBtn"><a href="{{asset('/elife/personal/index')}}"><span class="icon"></span><span class="text">我的</span></a></li>
        </ul>
    </div>
    <form action="{{asset('/wx/pay/orderConfirm')}}" method="post" id="orderConfirm">
        <input type="hidden" name="spu_id" id="spu_id">
        <input type="hidden" name="spec" id="spec">
        <input type="hidden" name="gift_num" id="gift_num" value="1">
    </form>

</body>

@endsection

@section('js')
    {{--<script type="text/javascript" src="{{asset('elife_js/mui.min.js')}}"></script>--}}
    <script type="text/javascript" src="{{asset('elife_js/common-top.js')}}"></script>
    <script type="text/javascript" src="{{asset('elife_js/jquery.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('elife_js/font_wvum.js')}}"></script>
    <script type="text/javascript" src="{{asset('/elife_js/swiper.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('/elife_js/index.js')}}"></script>
    <script type="text/javascript" src="{{asset('/elife_js/shop.js')}}"></script>
    <script type="text/javascript" src="{{asset('/elife_js/scrollLoadData.js')}}"></script>
@endsection

