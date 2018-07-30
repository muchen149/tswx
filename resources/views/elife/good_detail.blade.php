@extends('inheritance')

@section('title')
    商品详情
@endsection

@section('css')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>商品详情</title>
    <link rel="stylesheet" href="/elife_css/swiper.min.css">
    <link rel="stylesheet" href="/elife_css/new_common.css">
    <link rel="stylesheet" href="/elife_css/e_shop.css">
    {{--选择规格所需样式--}}
    <link href="{{asset('elife_css/goods/good.css')}}" rel="stylesheet">
    <link href="{{asset('elife_css/goods/product_detail.css')}}" rel="stylesheet">

    <style>

        *{ margin:0; padding:0; list-style:none;}
        img{ border:0;}
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
        /*.select-goods-property p{
            margin-left: 3%;
            margin-top: 5px;
            margin-bottom: 8px;
        }*/
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
        /*头部导航*/
        .nav_tab {
            width: 100%;
            height: 44px;
            display: flex;
            justify-content: space-around;
            border-bottom: 1px solid #8c8c8c;
            position: fixed;
            z-index: 10;
            background-color: #ffffff;
        }
        .nav_tab li {
            box-sizing: border-box;
            width: 25%;
            padding: 10px 0;
            text-align: center;
        }
        .tab {
            border-bottom: 1px solid #e90006;

        }
        /*商品详情*/
        .detail-baseInfo .goods ul{
            padding: 5px 10px;
        }
        .goods .share {
            color: #999;
            text-align: center;
            box-sizing: border-box;
        }
        .goods .share .btn-focus {
            height: 45px;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
            display: block;
            padding-top: 5px;
            line-height: 1.4em;
            font-size: 12px;
        }
        .goods .share .icon{
            display: block;
            width: 20px;
            height: 20px;
            margin: auto;
            background: url(../sd_img/css-spd.png) 0 0 no-repeat;
            -webkit-background-size: 400px 400px;
            background-size: 400px 400px;
            background-position: 0px -47px;
        }

        .goods .share .btn-focus .icon {
            background-position: -80px 0;
        }
        /*.detail-baseInfo .goods .share .focus-out {
            background-position: 0px -3px;
        }*/
        .detail-baseInfo .price span{
            color: #e71516;
            font-weight: 600;
        }
        .detail-baseInfo .det {
            padding: 3px 10px;
            width: 100%;
            font-size: 12px;
            color: #525252;
            font-family: "微软雅黑";
        }
        .detail-baseInfo .ming {
            padding: 0 10px;
            font-weight: 600;
            font-weight: 600;
            font-family: "微软雅黑";
        }
        .detail-baseInfo .tong{
            height: 35px;
            background-color: #f7f7f7;
            margin-top: 2px;
            width: 95%;
            margin: 0 auto;
            border-bottom: 1px solid #f1f1f1;
        }
        .detail-baseInfo .tong li{
            margin-left: 10px;
            font-family: "微软雅黑";
        }
        .detail-baseInfo ul li .pos {
            margin-right: 10px;
            color:#ff0b01;
        }

        /*图文详情*/
        .shop-detail div{
            text-align: -webkit-center;
            height: 30px;
            background: #eee;
            margin-bottom: 10px;
            padding: 3px;
            font-size: 15px;
            font-weight: 600;
        }
        .detail-baseInfo .shop-detail img{
            width:100%;
            padding: 0 5px;
        }
        .detail-baseInfo .shop-note {
            margin-bottom: 50px;
        }
        /*底部样式*/
        .bottom-menu .btn-addCart {
            height: 45px;
            line-height: 45px;
            background: #ea9b00;
            display: block;
            color: #fff;
        }
        .bottom-menu li:nth-child(1) {
            flex: 1;
            display: block;
            margin: auto;
            background: url(../sd_img/css-spd.png) 0 0 no-repeat;
            -webkit-background-size: 400px 400px;
            background-size: 400px 400px;
        }
        .bottom-menu .btn-index {
            height: 45px;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
            display: block;
            padding-top: 5px;
            line-height: 1.4em;
            font-size: 12px;
        }
        .bottom-menu .btn-index .icon {
            background-position: 0 0;
        }
        .bottom-menu li:nth-child(2) {
            flex: 0.7;
        }
        .bottom-menu li:nth-child(3) {
            flex: 1;
        }
        /*购物车*/
        .cart_num {
            top: 3px;
            left: 35%;
        }
        .cart_big_num {
            top: 3px;
            left: 35%;
        }
        h6 {
            color: #8f8f94;
            margin: 10px 10px 10px 10px;
        }
        .service {
            margin: 5px 0px;
        }
		.skuClass {
			color:LightSlateGray;
			font-size:15px;
		}
    </style>

@endsection
@section('content')
<body id="product">
    <div class="font-14 nav_tab">
        <li style="text-align: start;width:10%;"><a href="javascript:history.go(-1)" style="padding: 0px 5px;"><img src="{{asset('sd_img/next.png')}}" alt="" style="transform:rotate(180deg);width:23px"></a></li>
        <li class="tab font-14" data-to="shop">商品</li>
        <li data-to="detail" class="font-14">详情</li>
        <li data-to="like" class="font-14">购物须知</li>
    </div>
    <div class="container">
        <div class="swiper-container detail-banner" id="shop">
            <div class="swiper-wrapper">
                @foreach($spuImages as $img)
                    <div class="swiper-slide"><a href=""><img src="{{$img->image_url}}" width="100%" alt=""></a></div>
                @endforeach
            </div>
            <!-- Add Pagination -->
            <div class="swiper-pagination"></div>
        </div>
        <input type="hidden" id="sid" value="{{ $goods->spu_id }}">
        <div class="detail-baseInfo">
            <div class="goods">
                <ul class="flex-between  color-80">
                    <p class="price font-16"><span>¥ {{$goods->spu_price}}</span>
                        @if($grade!=10)
                            <del class="font-12">¥ {{$goods->spu_market_price}}</del>
                        @endif
                    </p>
                </ul>
                <div class="ming">{{$goods->spu_name}}</div>
                <div class="det" style="margin-top: 5px">
                    {!! $goods->ad_info !!}
                </div>
                <div class="service">
                    <ul class="flex-between  color-80  service" style=" border-top: 1px solid #dddddd">
                        <li><span class="font-12">运费</span></li>
                        @if(!empty($tpl))
                            @if($tpl->isFreeDelivery == 1)
                                <li><span class="font-12 pos">满{{$tpl->limitNum}}元包邮</span></li>
                            @elseif($tpl->isFreeDelivery == 2)
                                <li><span class="font-12 pos">满{{$tpl->limitNum}}件包邮</span></li>
                            @elseif($tpl->isFreeDelivery == 0)
                                <li><span class="font-12 pos">运费{{$tpl->limitNum}}元</span></li>
                            @endif
                        @else
                            <li><span class="font-12 pos">免运费</span></li>
                        @endif
                    </ul>
                </div>
            </div>
            <div class="shop-detail" id="detail">
                <div class="font-12" style="line-height: 2em;">图文详情</div>
                {!! $goods->mobile_content  !!}
            </div>
            <div class="shop-detail" id="like">
                <div class="font-12" style="line-height: 2em;">购物说明</div>
                    @if($shopNote)
                        {!!$shopNote->doc_content!!}
                @endif
            </div>
        </div>
        <div class="bottom-menu">
            <ul>
                <li>
                    <a href="{{asset('elife/eLifeIndex')}}" id="sd_cart">
                    <span class="btn-index">
                        <i class="icon"></i> 首页 </span>
                    </a>
                </li>
                <li>
                    <a href="{{asset('elife/cart/index')}}" id="sd_cart">
                    <span class="btn-cart">
                        @if($goods_num_in_cart < 100)
                            <span class="cart_num">{{$goods_num_in_cart}}</span>
                        @else
                            <span class="cart_big_num">99+</span>
                        @endif
                        <i class="icon"></i> 购物车 </span>
                    </a>
                </li>
                <li id="add_cart" class="btn-addCart">
                    <a href="#popover{{$goods['spu_id']}}">
                        <span class="sm" style="color:#ffffff"> 加入购物车 </span>
                        <input type="hidden" value="0" id="is_add">
                    </a>
                </li>
                <li id="directorder">
                    <a href="#popover{{$goods['spu_id']}}">
                        <span class="btn-buy" style="color:#ffffff;">立即购买</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    {{--选择规格--}}
    <!------------------------点击页脚按钮出现规格层  START!---------------------------->
    <div  class="detail">
        <div class="form-gui-ge">
            <div class="choose-gui-ge col-xs-12">
                <div class="text-right close-choose">
                    <img src="{{asset('img/m-close.svg')}}" height="20" width="20"/>
                </div>
                <div class="media">
                    <a class="media-left" href="#">
                        <div>
                            <img src="{{$goods->main_image}}" alt="加载中..." id="choose-img">
                        </div>

                    </a>
                    <div class="media-body">
                        <h6 class="media-heading" id="choose-name" style="font-size:12px;color: rgb(80,80,80);margin-top: 14px;">{{$goods->spu_name}}</h6>
                        <p class="jiaqian" style="color: #f13030">
							<span>¥&nbsp;</span>
							<span id="choose-price">{{$goods->spu_price}}</span>
							<strong class="skuClass">
							<span>&nbsp;库存:</span>
							<span id="choose-num">{{$goods->spu_storage_num}}</span>
							<span>件</span>
                            {{--<span style="color: #999;font-weight: normal;font-size: 13px;">&nbsp;&nbsp;¥&nbsp;<s>{{$goods->spu_market_price}}</s></span>--}}
							</strong>
                        </p>
                    </div>
                </div>

                <div class="a-choose">
                    {{--@if(!empty($spuSpec))--}}
                    @foreach($spuSpec as $guige)
                        {{-- {{dd($guige)}}--}}
                        <input type="hidden" value="{{count($spuSpec)}}" class="spuLength"/>
                        @if($guige['data_type']== 'C')
                            <p class="guigeming">{{$guige['spec_name']}}</p>
                            <div class="spu-value">
                                @foreach($guige['spec_value'] as $index=>$item)
                                    <div style="font-size: 12px;">
                                        <a class="choose-a" style="margin: 10px 5px 10px 0px;">{{$item}}</a>
                                        <input type="hidden" value="{{$index}}" class="sku_id"/>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="guigeming">{{$guige['spec_name'] }}</p>
                            <div class="col-xs-12 spu-value">
                                @foreach($guige['spec_value'] as $item)
                                    <div class="col-xs-3">
                                        <img class="dingzhiyangshi" src="{{asset($item)}}" alt="{{$item}}" >
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        {{-- <hr>--}}
                    @endforeach
                    {{--@endif--}}
                </div>
                <div class="clearfix"></div>
                <div class="col-xs-12 cart-num">
                    <div class="col-xs-3">
                        <span>购买数量</span>
                    </div>
                    <div class="col-xs-9">
                        <div class="text-right change-number" >
                            <input class="min" name="" type="button" style="
                        width: 26px;
                        height: 26px;
                        padding: 0;
                        " value="-"/>
                            <input class="text_box text-center"  id="buy_num" name="" type="text" min_limit="1" value="1" style="
                        width: 61px;
                        height: 26px;
                        padding: 0;
                        "/>
                            <input class="add" name="" type="button" style="
                        width: 26px;
                        height: 26px;
                        padding: 0;
                        " value="+"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>

            <div class="col-xs-12 join-btn">
                <button class="join-now red-color f18" >加入购物车</button>
            </div>
            <div class="col-xs-12 buy-btn">
                <form action="{{asset('/elife/order/showPay')}}" method="post" name="buyForm" id="buyForm">
                    <input type="hidden" name="spuId" id="spuId">
                    <input type="hidden" name="number" id="number">
                    <input type="hidden" name="src" id="src">
                    <input type="hidden" name="guiges" id="guiges">
                    {{--<input type="hidden" name="importType" value="{{$goods['importType']}}">--}}
                    <button class="buy-now red-color f18">立即购买</button>
                </form>

            </div>

        </div>
        <!--======================点击页脚按钮出现规格层  END!============================-->

        <!------------------------选择规格时body的阴影部分  START!---------------------------->
        <div class="xiaoguo">

        </div>
        <!--======================选择规格时body的阴影部分  END!============================-->
    </div>
</body>

@endsection

@section('js')
    <script type="text/javascript" src="/elife_js/jquery-1.11.2.min.js"></script>
    <script type="text/javascript" src="/elife_js/swiper.min.js"></script>
    <script type="text/javascript" src="/elife_js/detail.js"></script>
    <script type="text/javascript" src="{{asset('elife_js/goods/product.js')}}"></script>
    <script type="text/javascript">
        /*锚点*/
        $('.nav_tab li').on('click',function(){
            $(this).addClass('tab').siblings().removeClass('tab');
        });

        $('.nav_tab').on('click', 'li', function (e) {
            var target = e.target;
            var id = $(target).data("to");
            console.log(id);
            $('html,body').animate({scrollTop: $('#' + id).offset().top - 44}, 500);
        });

    </script>
    {{--<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>--}}
@endsection