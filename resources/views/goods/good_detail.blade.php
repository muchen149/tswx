@extends('inheritance')

@section('title')
商品详情
@endsection

@section('css')
<link href="{{asset('css/reset.css')}}" rel="stylesheet">
<link href="{{asset('css/font-awesome.min.css')}}" rel="stylesheet">
<link href="{{asset('css/main.css')}}" rel="stylesheet">
<link href="{{asset('css/child.css')}}" rel="stylesheet">
<link href="{{asset('css/header.css')}}" rel="stylesheet">
<link href="{{asset('css/goods/product_detail.css')}}" rel="stylesheet">

<link href="{{asset('css/goods/good.css')}}" rel="stylesheet">

{{--<link href="{{asset('css/goods/lrtk.css')}}" rel="stylesheet">--}}
<style>
    * {
        margin: 0;
        padding: 0;
    }

    .header-slider {
        margin: 0 auto;
        max-width: 640px;
        min-width: 320px;
        overflow: hidden;
    }

    .tuijian-title {
        width: 100%;
        height: 40px;
        background-color: white;
    }

    .tuijian-title p {
        margin-left: 3%;
        height: 40px;
        line-height: 40px;
        font-size: 13px;
    }

    .tuijian-content {
        width: 100%;
        display: flex;
        justify-content: space-around;
        align-content: center;
        background-color: white;
    }

    .tuijian-content ul {
        width: 80px;
        list-style: none;
        text-align: center;
    }

    .tuijian-content ul li img {
        width: 80px;
        height: 80px;
    }


    .tuijian-content ul .price {
        color: red;
        font-weight: bold;
        font-size: 12px;
    }

    a:link, a:visited, a:hover, a:active{
        text-decoration:none;
    }

    .goods_num{
        position: absolute;
        z-index: 99999;
        height: 13px;
        line-height: 15px;
        font-family: arial;
        padding: 0px 3px 0px 3px;
        background: #e4393c;
        -webkit-border-radius: 15px;
        -moz-border-radius: 15px;
        border-radius: 15px;
        color: #fff;
        font-size: 12px;
        top:8%;
        left:57%;
    }


</style>
@endsection



@section('content')
<body id="product">
<div id="header"></div>

<div class="header-slider" id="product_detail_wp"
    {{--@if(empty($_GET['if']) || $_GET['if'] != 'false')--}}
        {{--style="margin-bottom:54px;"--}}
    {{--@endif --}}
>

    {{--@if("商品不空")--}}
      <div class="header-slider-con" style="transition: all 500ms ease 0s; transform: translate3d(0px, 0px, 0px);">


       <input type="hidden" id="sid" value="{{ $goods->spu_id }}">

        <li class="header-slider-item" style=" ">
           <div id="mainLayout" class="page-content">

              <div class="scroll-imgs">
                 <div class="slide" id="mySwipe" style="cursor: pointer;">
                    <ul data-slide-ul="firstUl" class="swipe-wrap" style="">
                        @if(!$spuImages->isEmpty())
                            @foreach($spuImages as $img)
                                <li class="swipe-item J_ping" data-ul-child="child" style="transition: all 0ms ease 0s; height: 320px; width: 320px; transform: translate3d(0px, 0px, 0px);">
                                    <img class="J_ping" src="{{$img->image_url}}">
                                </li>
                            @endforeach
                        @else
                            <li class="swipe-item J_ping" data-ul-child="child" style="transition: all 0ms ease 0s; height: 320px; width: 320px; transform: translate3d(0px, 0px, 0px);">
                                <img class="J_ping" src="{{$goods->main_image}}">
                            </li>
                        @endif
                    </ul>
                    <div id="tittup" class="tittup">
                        <div class="arrow-box">
                            <em class="arrow"></em>
                            <em class="arrow2"></em>
                        </div>
                        <span class="inner">
                               滑动查看详情
                        </span>
                    </div>

                    <div class="page-nub">
                        <em class="fz18" id="slide-nub">1</em>
                        <em class="nub-bg">/</em>
                        @if(!$spuImages->isEmpty())
                            <em class="fz12" id="slide-sum">{{count($spuImages)}}</em>
                        @else
                            <em class="fz12" id="slide-sum">{{1}}</em>
                        @endif
                    </div>
                 </div>
              </div>


               {{--old--}}
               {{--<div id="iSlider-effect-wrapper">--}}
                   {{--<div id="animation-effect" class="iSlider-effect" style="border: 0"></div>--}}
               {{--</div>--}}

              <div class="goods-part bdr-tb">
                 <div class="basic-info bdr-b borb">
                    <div class="prod-title">
                        <input type="hidden" name="is_virtual" id="is_virtual" value="{{$goods->is_virtual}}">
                        <a class="detailInfoClick J_ping">
                           <span class="title-text">
                               {{$goods->spu_name}}
                           </span>
                        </a>
                    </div>
                    <div class="prod-price">
                       <span class="yang-pic-price">
                              <span class="big-price" style="font-size: 18px;">¥&nbsp;{{$goods->spu_price}}</span>&nbsp;&nbsp;
                              <span  class="small-price" style="color: #999;font-weight: normal;font-size: 14px;">¥&nbsp;<s>{{$goods->spu_market_price}}</s></span>
                       </span>
                    </div>

                     @if($goods->spu_points)
                         <div style="color: #999;font-weight: normal;display: block;margin-left:-5px;margin-top:32px;">
                             <span  class="small-price" style="font-size: 14px;">【可支付{{$plat_vrb_caption}}：<span style="color: #f23030;">{{$goods->spu_points}}</span>】</span>
                             <div style="clear:both"></div>
                         </div>
                     @endif
                    <div class="depreciate-arrival-inform-box">
                        {{--<div id="depreciateInformPr" class="depreciate-arrival-inform  J_ping">￥1000</div>--}}
                    </div>
                    <div style="clear:both"></div>
                    <div class="prod-seckill">
                    </div>
                 </div>
              </div>

               {{--@if(!empty('from_paltform'))--}}
                   {{--<div class="goods-part bdr-tb mar-t">--}}
                       {{--<div class="provide-srv" style="margin-top:5px">--}}
                           {{--<img src="" style="width:92px;float: left;">--}}
                           {{--<p class="provider base-txt" style="margin-bottom:5px;height:30px;line-height: 32px;margin-left:100px;">此商品由yiyuanda(商品来源)提供</p>--}}
                       {{--</div>--}}
                   {{--</div>--}}
               {{--@endif--}}

               {{--@if(empty($_GET['if']) || $_GET['if'] != 'false')--}}
                   {{--<div class="goods-part bdr-tb mar-t" style="overflow: hidden">--}}
                       {{--<div id="prodSpecArea" class="prod-spec">--}}
                           {{--<div id="natureID" style="float: left;margin-top: 6px" report-eventlevel="5" report-pageparam="549762" report-eventid="MProductdetail_SpecificationsPackUp" class="spec-desc J_ping arrow-fold">--}}
                               {{--<span class="part-note-msg">已选</span>--}}
                               {{--<div class="base-txt" id="specDetailInfo">--}}
                                   {{--&nbsp;&nbsp;<span id="amount">1件</span>--}}
                               {{--</div>--}}
                           {{--</div>--}}

                           {{--<div id="natureCotainer" class="nature-container" style="float: right;margin-bottom: 14px;margin-right:90px">--}}
                               {{--<div class="pro-count">--}}
                                   {{--<span class="part-note-msg" style="margin-top: 1px">数量</span>--}}
                                   {{--<div class="quantity-wrapper">--}}
                                       {{--<input type="hidden">--}}
                                       {{--<a id="quantityDecrease" href="javascript:void(0)" class="quantity-decrease">--}}
                                           {{--<em id="minus">-</em>--}}
                                       {{--</a>--}}
                                       {{--<input type="tel" class="quantity" id="buynum" value="1" size="4"/>--}}
                                       {{--<a id="quantityPlus" href="javascript:void(0)" class="quantity-increase">--}}
                                           {{--<em id="plus">+</em>--}}
                                       {{--</a>--}}
                                   {{--</div>--}}
                               {{--</div>--}}
                           {{--</div>--}}
                       {{--</div>--}}
                   {{--</div>--}}
               {{--@endif--}}
           </div>
            <form id="tuwen" action="{{asset('/shop/goods/mobileContent')}}" method="post">
                 <div class="goods-part bdr-tb mar-t mobile_content" style="margin-bottom: 100px;">
                     <input type="hidden" name="mobilecontent" id="mobilecontent" value="{{$goods->mobile_content}}" />
                     <div class="more-detail detailInfoClick J_ping">
                         <div id="productInfo">
                             <span class="part-note-msg" style="height:44px;line-height: 44px;">图文详情</span>
                             <em class="icon-arrow icon-arrow-right"></em>
                         </div>
                     </div>
                 </div>
            </form>
{{--为你推荐 start--}}
           {{--<div id="showRecommendList" class="guess-ulike bdr-t">--}}
           {{--</div>--}}
            {{--<div class="tuijian-title">--}}
                {{--<p>为你推荐</p>--}}
            {{--</div>--}}
            {{--<div class="tuijian-content" style="height:235px;">--}}

                {{--<ul>--}}
                    {{--<li class="pic">--}}
                            {{--<img src="//m.360buyimg.com/n6/jfs/t3124/162/3805700536/299747/16ff9593/57fa267eN9e3bf7a5.jpg" style="animation: 400ms ease 0s normal none 1 running fade;">--}}
                    {{--</li>--}}
                    {{--<li class="title guess-item-content" style="height:70px;">--}}
                        {{--<span> 产品名称产品名称产品名称产品名称产品名称产品名称 </span>--}}
                    {{--</li>--}}
                    {{--<li class="price">￥120</li>--}}
                {{--</ul>--}}

                {{--<ul>--}}
                    {{--<li class="pic">--}}
                            {{--<img src="//m.360buyimg.com/n6/jfs/t3124/162/3805700536/299747/16ff9593/57fa267eN9e3bf7a5.jpg" style="animation: 400ms ease 0s normal none 1 running fade;">--}}
                    {{--</li>--}}
                    {{--<li class="title guess-item-content" style="height:70px;">--}}
                        {{--<span> 产品名称产品名称产品名称产品名称产品 </span>--}}
                    {{--</li>--}}
                    {{--<li class="price">￥120</li>--}}
                {{--</ul>--}}

                {{--<ul>--}}
                    {{--<li class="pic">--}}
                            {{--<img src="//m.360buyimg.com/n6/jfs/t3124/162/3805700536/299747/16ff9593/57fa267eN9e3bf7a5.jpg" style="animation: 400ms ease 0s normal none 1 running fade;">--}}
                    {{--</li>--}}
                    {{--<li class="title guess-item-content" style="height:70px;">--}}
                        {{--<span> 产品名称产品名称产品名称产品名称产品 </span>--}}
                    {{--</li>--}}
                    {{--<li class="price">￥120</li>--}}
                {{--</ul>--}}

                {{--<ul>--}}
                    {{--<li class="pic">--}}
                            {{--<img src="//m.360buyimg.com/n6/jfs/t3124/162/3805700536/299747/16ff9593/57fa267eN9e3bf7a5.jpg" style="animation: 400ms ease 0s normal none 1 running fade;">--}}
                    {{--</li>--}}
                    {{--<li class="title guess-item-content" style="height:70px;">--}}
                        {{--<span> 产品名称产品名称产品名称产品名称产品 </span>--}}
                    {{--</li>--}}
                    {{--<li class="price">￥120</li>--}}
                {{--</ul>--}}
            {{--</div>--}}
            {{--为你推荐 end--}}
        </li>
      </div>
    {{--@endif--}}
</div>

{{--@if(empty($_GET['if']) || $_GET['if'] != 'false')--}}
    <div class="cart-concern-btm-fixed five-column four-column" id="cart1" style="display: table;">
        <div class="concern-cart footer-xq">
            <a id="payAttention" class="love-heart-icn J_ping">
                <div id="focusOn" class="focus-container">
                    <div class="focus-icon">
                        <i id="attentionFocus"
                           class="bottom-focus-icon
                           @if (!$collect_status)
                                   focus-out
                           @else
                                   focus-on
                           @endif

                           "></i>
                        <i class="focus-scale"></i>
                    </div>
                    <span class="focus-info">
                        @if ($goods->collect_status)
                            已
                        @endif
                        收藏
                    </span>
                </div>
            </a>
            <a id="toCart" href="{{url('/cart/index')}}" class="cart-car-icn" style="position: relative">
                {{--<em id="shoppingCart" class="btm-act-icn">--}}
                    {{--<i id="carNum" class="order-numbers"></i>--}}
                {{--</em>--}}

                <em class="goods_num" @if(empty($goods_num_in_cart)) hidden @else show @endif>{{$goods_num_in_cart}}</em>
                <img src="{{asset('img/m-cart.svg')}}" style="    display: block;height: 21px;margin: 10px auto 4px;position: relative;"/>

                <span class="focus-info cart-font">购物车</span>
                <div id="cart_dt" class="num"></div>
            </a>


        </div>
        <div class="action-list">
            <a id="add_cart" class="yellow-color"
               style="transform-origin: 0px 0px 0px; opacity: 1; transform: scale(1, 1);"> 加入购物车 </a>
            <a id="directorder" class="red-color "
               style="transform-origin: 0px 0px 0px; opacity: 1; transform: scale(1, 1);">立即购买</a>
        </div>
    </div>
{{--@endif--}}

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
                    <h6 class="media-heading" id="choose-name" style="font-size:15px;color: rgb(80,80,80);margin-top: 14px;">{{$goods->spu_name}}</h6>
                    <p class="jiaqian" style="color: #f13030"><span>¥&nbsp;</span><span id="choose-price">{{$goods->spu_price}}</span>
                        {{--<span style="color: #999;font-weight: normal;font-size: 13px;">&nbsp;&nbsp;¥&nbsp;<s>{{$goods->spu_market_price}}</s></span>--}}
                    </p>
                    @if($goods->spu_points)
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
            <button class="join-now yellow-color f18">加入购物车</button>
        </div>
        <div class="col-xs-12 buy-btn">
            <button class="buy-now red-color f18">立即购买</button>
        </div>
    </div>
    <!--======================点击页脚按钮出现规格层  END!============================-->

    <!------------------------选择规格时body的阴影部分  START!---------------------------->
    <div class="xiaoguo">

    </div>
    <!--======================选择规格时body的阴影部分  END!============================-->
</div>

<form action="{{asset('/order/showPay')}}" method="post" id="buyForm">
    <input type="hidden" name="spuId" id="spuId">
    <input type="hidden" name="number" id="number">
    <input type="hidden" name="src" id="src">
    <input type="hidden" name="guiges" id="guiges">
</form>


</body>

<script>

</script>


@endsection

@section('js')
<script type="text/javascript" src="{{asset('js/common-top.js')}}"></script>
<script type="text/javascript" src="{{asset('js/common.js')}}"></script>

<script type="text/javascript" src="{{asset('js/goods/product.js')}}"></script>

{{--图片轮播--}}
<script type="text/javascript" src="{{asset('js/swipe.js')}}"></script>
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
            desc: '分享送好礼', // 分享描述
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
</script>

@endsection
