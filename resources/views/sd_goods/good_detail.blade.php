@extends('inheritance')

@section('title')
    商品详情
@endsection

@section('css')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>{!!config('constant')['comTitle']['title']!!}{{--详情--}}</title>
    <link rel="stylesheet" href="/sd_css/swiper.min.css">
    <link rel="stylesheet" href="/sd_css/new_common.css">
    <link rel="stylesheet" href="/sd_css/shop.css">
    {{--选择规格所需样式--}}
    <link href="{{asset('sd_css/goods/good.css')}}" rel="stylesheet">
    <link href="{{asset('sd_css/goods/product_detail.css')}}" rel="stylesheet">

    <style>

        *{ margin:0; padding:0; list-style:none;}
        img{ border:0;}

        .lanrenzhijia {position: fixed;left: -100%;right:100%;top:0;bottom: 0;text-align: center;font-size: 0; z-index:9999; display:none;}
        .lanrenzhijia:after {content:"";display: inline-block;vertical-align: middle;height: 100%;width: 0;}
        .content{display: inline-block; *display: inline; *zoom:1;	vertical-align: middle;position: relative;right: -100%;}
        .content_mark{ width:100%; height:100%; position:fixed; left:0; top:0; z-index:555; background:#000; opacity:0.5;filter:alpha(opacity=50); display:none;}

        /*底部弹出的商品属性*/
        /*#popover{*/
        /*bottom: 0px!important;*/
        /*background-color: rgb(240,240,240);*/
        /*}*/
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
        .nav {
            width: 100%;
            height: 44px;
            display: flex;
            justify-content: space-around;
            border-bottom: 1px solid #8c8c8c;
            position: fixed;
            z-index: 10;
            background-color: #ffffff;
        }
        .nav li {
            box-sizing: border-box;
            width: 20%;
            padding: 10px 0;
            text-align: center;
        }
        .tab {
            border-bottom: 1px solid #e90006;

        }
        /*商品详情*/
        .detail-baseInfo .goods ul{
            padding: 0 10px;
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
            font-size: 20px;
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
        /*相似推荐*/
        .detail-baseInfo .shop-goodRmd {
            padding: 0 10px 15px;
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
        /*底部样式*/
        .bottom-menu .btn-addCart {
            height: 45px;
            line-height: 45px;
            background: #ea9b00;
            display: block;
            color: #fff;
        }
        .bottom-menu li:nth-child(1) {
            flex: 0.7;
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
            flex: 0.8;
        }
        /*购物车*/
        .cart_num {
            top: 3px;
            left: 85px;
        }
        .cart_big_num {
            top: 3px;
            left: 85px;
        }

        /*点击关注*/
        .follow {position: fixed;left: -100%;right:100%;top:0;bottom: 0;text-align: center;font-size: 0; z-index:9999; display:none;}
        .follow:after {content:"";display: inline-block;vertical-align: middle;height: 100%;width: 0;}
        .content{display: inline-block; *display: inline; *zoom:1;	vertical-align: middle;position: relative;right: -100%;}
        .content_nat{ width:100%; height:100%; position:fixed;left:0; top:0; z-index:555;background:url(/sd_img/subscribe_background.jpg);background-repeat : no-repeat;background-size: 100%,100%;  display:none;}
    </style>

@endsection
@section('content')

<body id="product">
@if($subscribe==0)
    <div style="position: fixed;z-index: 11;top:0">
        <a href="javascript:showSubscribe();"><img src="{{asset('sd_img/t_banner.jpg')}}" alt="" width="100%"></a>
    </div>
@endif
<div class="font-14 nav">
    <li class="tab" data-to="shop">商品</li>
    <li data-to="detail">详情</li>
    <li data-to="like">推荐</li>
</div>
<div class="container">
    <div class="swiper-container detail-banner" style="margin-top:44px;" id="shop">
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
                    <p class="price"><span>¥ {{$goods->spu_price}}</span>
                        @if($grade!=10)
                            <del class="font-12">¥ {{$goods->spu_market_price}}</del>
                        @endif
                    </p>
                    <li class="share">
                        <a href="{{asset('/member/inviteFriend')}}" id="sd_cart">
                        <span class="btn-focus" id="focusOn" >
                            <i id="attentionFocus" class="icon focus-out"></i>
                            分享有奖
                        </span>
                        </a>
                    </li>
                </ul>
                <div class="ming">{{$goods->spu_name}}</div>
                <div class="det">
                    {!! $goods->ad_info !!}
                </div>
            </div>
            <ul class="flex-between  color-80 border-b1 tong">
                <li class="font-12" style="">@if($grade==10)开通{{$member_class_arr[20]['grade_name']}}服务立省@elseif($grade==20)开通{{$member_class_arr[30]['grade_name']}}服务立省@elseif($grade==30){{$member_class_arr[30]['grade_name']}}服务已为您节省@endif{{$goods->sup_discount}}元</li>
                @if($grade < 30)
                    <li><a href="{{asset('member/buy')}}" class="flex-between color-80"><span class="font-12" style="padding-right: 10px;">立即开通 <span class="font-14">></span></span>{{--<img src="{{asset('sd_img/next.png')}}" alt="" class="size-28">--}}</a></li>
                @else
                    <li><a href="{{asset('member/buy/30')}}" class="flex-between color-80"><span class="font-12" style="padding-right: 10px;">立即续费 <span class="font-14">></span></span></a></li>
                @endif
            </ul>

            <ul class="flex-between  color-80 border-b1 tong">
                @if(empty($goods->from_plat_name))
                    <li class="font-12">直营</li>
                @else
                    <li class="font-12">供应商 : {{$goods->from_plat_name}}</li>
                @endif
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
            <div class="shop-goodRmd" id="like" style=" border:none;">
                <h4 class="font-12" style="line-height: 2em;">相似推荐</h4>
                <ul>
                    @if(!$rmdSpuList)
                        <li class="font-12">暂无推荐内容...</li>
                    @else
                        @foreach($rmdSpuList as $good)
                            <li style="width: 33%;"><a href="{{url('/shop/goods/spuDetail/'.$good->spu_id)}}">
                                    <span class="img"><img src="{{$good->main_image}}" width="100%" alt=""><i class="tag">推荐</i></span>
                                    <h4 class="rmdGood">{{$good->spu_name}}</h4><p class="price" style="font-size: 15px;">¥&nbsp;{{$good->spu_price}}</p></a>
                            </li>
                        @endforeach
                    @endif
                </ul>
            </div>
            <div class="shop-detail" id="detail">
                <div class="font-12" style="line-height: 2em;">图文详情</div>
                {!! $goods->mobile_content  !!}
            </div>
        {{--<form id="tuwen" action="{{asset('/shop/goods/mobileContent')}}" method="post">
            <input type="hidden" name="mobilecontent" id="mobilecontent" value="{{$goods->mobile_content}}" />
            <input type="hidden" name="spu_id" id="spu_id" value="{{$goods->spu_id}}" />
            <input type="hidden" name="from_plat_code" id="from_plat_code" value="{{$goods->from_plat_code}}" />
            <div id="twxq">
                <a href="javascript:void(0)" class="btn-more">查看图文详情</a>
            </div>
        </form>--}}
    </div>
{{--    <div class="detail-recommend">
        <h3><span class="right"><i>召唤朋友一起买可</i><i>省29元</i></span><span class="t">一起买更优惠</span></h3>
        <a href="">
            <span class="img"><img src="/sd_img/img09.jpg" width="100%" alt=""><i class="price">129元起</i></span>
            <a href="{{asset('member/callFriend')}}"><span class="btn">一起买</span></a>
            <h4>社稷尚品有机大米礼盒 5kg竹编礼盒</h4>
            <p>五年有机土体 传统种植方法</p>

        </a>

    </div>
    <div class="detail-other">
        <h3><span>TA都在一起买</span></h3>
        <p class="img">
            <img src="/sd_img/img07.jpg" width="30" height="30" alt="">
            <img src="/sd_img/img08.jpg" width="30" height="30" alt="">
            <img src="/sd_img/img07.jpg" width="30" height="30" alt="">
            <img src="/sd_img/img08.jpg" width="30" height="30" alt="">
            <img src="/sd_img/img07.jpg" width="30" height="30" alt="">
            <img src="/sd_img/img08.jpg" width="30" height="30" alt="">
            <span class="num">20</span>
            <a href="{{asset('member/callFriendBuy')}}">去看看<i class="icon-next"></i></a>
        </p>
    </div>--}}

    {{-- --------------------------------旧版本------------------------------ --}}

    {{--<div class="detail-price">
        <ul>
            <li style="width: 80%;">
                <p class="price"><span>¥ {{$goods->spu_price}}</span>
                    @if($grade!=10)
                        <del>¥ {{$goods->spu_market_price}}</del>
                    @endif
                </p>
                @if($isHighest!=1)
                    <a href="/member/buy"> <p class="sm" style="width: 100%">升级享低折扣<span style="color: #000;">¥ {{$goods->supper_spu_price}}</span> </p></a>
                @endif
                @if($grade==10)
                    <a href="/member/buy"> <p class="sm" style="width: 100%;color: #000;">升级会员优惠<span style="color: #000;">¥ {{$goods->sup_discount}}</span> </p></a>
                @else
                    <p class="sm" style="width: 100%;color: #000;">当前会员优惠<span style="color: #000;">¥ {{$goods->sup_discount}}</span> </p>
                @endif
                <p class="sm">快递费：{{$goods->spu_price}}</p>
            </li>
            <li><p class="sm xl">快递费：15</p>
            </li>
            <li>
                <div  id="add_cart" class="btn-addCart">
                    <a href="#popover{{$goods['spu_id']}}">
                    <i class="icon"></i>
                    <p class="sm">加入购物车</p>
                    <input type="hidden" value="0" id="is_add">
                    </a>
                </div>

            </li>
        </ul>
    </div>--}}
    <div class="bottom-menu">
        <ul>
            {{--<li>
                <a href="{{asset('/member/inviteFriend')}}" id="sd_cart">
                    <span class="btn-focus" id="focusOn" >
                    <i id="attentionFocus" class="icon focus-out"></i>
                        @if ($goods->collect_status)
                            已
                        @endif
                        关注
                    分享有奖</span>
                </a>
            </li>--}}
            <li>
                <a href="{{asset('shop/index')}}" id="sd_cart">
                    <span class="btn-index">
                        <i class="icon"></i> 首页 </span>
                </a>
            </li>
            <li>
                <a href="{{asset('cart/index')}}" id="sd_cart">
                    <span class="btn-cart">
                        @if($goods_num_in_cart < 100)
                            <span class="cart_num">{{$goods_num_in_cart}}</span>
                        @else
                            <span class="cart_big_num">99+</span>
                        @endif
                        <i class="icon"></i> 购物车 </span>
                </a>
            </li>
            <li id="gift">
                <span class="btn-sl"> 送礼 </span>
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
                    <p class="jiaqian" style="color: #f13030"><span>¥&nbsp;</span><span id="choose-price">{{$goods->spu_price}}</span>
                        {{--<span style="color: #999;font-weight: normal;font-size: 13px;">&nbsp;&nbsp;¥&nbsp;<s>{{$goods->spu_market_price}}</s></span>--}}
                    </p>
                    @if($goods->spu_points>=0)
                        <p style="margin-top:5px;margin-left:-11px;margin-bottom: 5px;" class="aa" id="p_id">
                            <span  class="small-price" style="font-size:13px;color: rgb(120,120,120);padding-left: 6px;">【可支付{{$plat_vrb_caption}}：<span style="color: #f23030;" id="usable_points">{{$goods->spu_points}}</span>】</span>
                        </p>
                    @else
                        <p style="margin-top:5px;margin-left:-11px;margin-bottom: 5px;" class="bb" id="p_id" hidden>
                            <span  class="small-price" style="font-size:13px;color: rgb(120,120,120);padding-left: 6px;">【可支付{{$plat_vrb_caption}}：<span style="color: #f23030;" id="usable_points">0</span>】</span>
                        </p>
                    @endif
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
            <form action="{{asset('/order/showPay')}}" method="post" name="buyForm" id="buyForm">
                <input type="hidden" name="spuId" id="spuId">
                <input type="hidden" name="number" id="number">
                <input type="hidden" name="src" id="src">
                <input type="hidden" name="guiges" id="guiges">
                <button class="buy-now red-color f18">立即购买</button>
            </form>

        </div>

        <div class="col-xs-12 share-gift">
            <form action="{{asset('/wx/pay/orderConfirm')}}" method="post" name="send-gift" id="send-gift">
                <input type="hidden" name="spu_id" id="spu_id_gifts">
                <input type="hidden" name="spec" id="spec">
                <input type="hidden" name="gift_num" id="gift_num" value="1">

                <button class="share-now yellow-color f18">送礼</button>
            </form>

        </div>
    </div>
    <!--======================点击页脚按钮出现规格层  END!============================-->

    <!------------------------选择规格时body的阴影部分  START!---------------------------->
    <div class="xiaoguo">

    </div>
    <!--======================选择规格时body的阴影部分  END!============================-->
</div>

<div class="lanrenzhijia" id="pop-ad">
    <div class="content">
        <img width="270px;" height="320px" src="{{asset('/sd_img/notice_gift.png')}}" usemap="#Map" />
        <map name="Map">
            <area shape="rect" coords="19,275,129,318" href="javascript:hideNotice();">
            <area shape="rect" coords="139,273,258,317" href="javascript:showGiftChoose();">
        </map>
    </div>
</div>
<div class="content_mark"></div>


<form action="{{asset('sd_cart/add')}}" method="post" name="add_form" id="add_form">
    <input type="hidden" name="spu_ids" id="spu_ids" value="">
    <input type="hidden" name="specs" id="specs">
    <input type="hidden" name="gift_nums" id="gift_nums" value="1">
</form>
{{--点击关注页面--}}
<div class="follow" id="pop-ad">
    <div class="content">
        <img width="270px;" height="332px" src="{{asset('/sd_img/subscribe_share.jpg')}}" usemap="#Mapp" />
        <map name="Mapp">
            <area shape="rect" coords="234,5,265,31" href="javascript:hideSubscribe();">
        </map>
    </div>
</div>
<div class="content_nat"></div>
</body>

@endsection

@section('js')
    <script type="text/javascript" src="/sd_js/jquery-1.11.2.min.js"></script>
    <script type="text/javascript" src="/sd_js/swiper.min.js"></script>
    <script type="text/javascript" src="/sd_js/detail.js"></script>
    <script type="text/javascript" src="{{asset('sd_js/goods/product.js')}}"></script>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script type="text/javascript">
        var stoken = '{{$stoken}}';
        var appId = '{{$signPackage['appId']}}';
        var timestamp = '{{$signPackage['timestamp']}}';
        var nonceStr = '{{$signPackage['nonceStr']}}';
        var signature = '{{$signPackage['signature']}}';
        var link = '{{$share_link}}';
        var title = '{{$goods->spu_name}}';
        var imgUrl = '{{$goods->main_image}}';
        var desc = '{{$goods->ad_info}}';
        desc = desc.replace(/&lt;.*?&gt;/ig,"");
        //alert(desc);
        //var imgUrl = 'http://img.shuitine.com/spu/2017-08-01/9/95/c7add6b4aaae1e23cf18319c8e64213e.jpg';//{{asset('sd_img/logo_shuitine.png')}}';
        //alert(imgUrl);
        wx.config({
            debug: false,
            appId: appId,
            timestamp: timestamp,
            nonceStr: nonceStr,
            signature: signature,
            jsApiList: [
                // 所有要调用的 API 都要加到这个列表中
                'onMenuShareTimeline',
                'onMenuShareAppMessage'
            ]
        });
        wx.ready(function () {
            // 在这里调用 API
            wx.onMenuShareTimeline({
                title: title, // 分享标题
                imgUrl: imgUrl, // 分享图标
                link:link,
                success: function (msg) {
                    // 用户确认分享后执行的回调函数
                },
                cancel: function (msg) {
                    // 用户取消分享后执行的回调函数
                },
                fail: function () {
                    // 用户取消分享后执行的回调函数
                    alert("分享失败，请稍后再试");
                }
            });


            wx.onMenuShareAppMessage({
                title: title, // 分享标题
                desc: desc,//'分享送好礼', // 分享描述
                imgUrl: imgUrl, // 分享图标
                link:link,
                type: '', // 分享类型,music、video或link，不填默认为link
                dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
                success: function (msg) {
                    // 用户确认分享后执行的回调函数
                },
                cancel: function (msg) {
                    // 用户取消分享后执行的回调函数
                },
                fail: function () {
                    // 用户取消分享后执行的回调函数
                    alert("分享失败，请稍后再试");
                }
            });
        });

        /*$("#twxq").on('click',function(){
            $('#tuwen').submit();
        });*/
        /*锚点*/
        $('.nav li').on('click',function(){
            $(this).addClass('tab').siblings().removeClass('tab');
        });
        if('{{$subscribe}}'==1) {
            $('.nav').on('click', 'li', function (e) {
                var target = e.target;
                var id = $(target).data("to");
                console.log(id);
                $('html,body').animate({scrollTop: $('#' + id).offset().top - 44}, 500);
            });
        }
        /*点击关注js*/
        function showSubscribe() {
            $('.follow').show(0);
            $('.content_nat').show(0);
        }

        function hideSubscribe(){
            $('.follow').hide(0);
            $('.content_nat').hide(0);
        }

        if('{{$subscribe}}'==0){
            $('.head-wrap').css('marginTop',37);
            $('.nav').css('top',35);
            $('.detail-banner').css('marginTop',79);
            $('.nav').on('click','li',function(e){
                var target = e.target;
                var id = $(target).data("to");
                console.log(id);
                $('html,body').animate({scrollTop:$('#'+id).offset().top - 79}, 500);
            });
        }
    </script>
@endsection