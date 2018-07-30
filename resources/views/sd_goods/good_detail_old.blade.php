@extends('inheritance')

@section('title')
    商品详情
@endsection

@section('css')

    <link rel="stylesheet" href="{{asset('sd_css/mui.min.css')}}">
    <link rel="stylesheet" href="{{asset('sd_css/common.css')}}">
    <script src="{{asset('sd_js/font_wvum.js')}}"></script>

    {{--选择规格所需样式--}}
    <link href="{{asset('sd_css/goods/good.css')}}" rel="stylesheet">
    <link href="{{asset('sd_css/goods/product_detail.css')}}" rel="stylesheet">

    <style>
        .icon {
            width: 1em; height: 1em;
            vertical-align: -0.15em;
            fill: currentColor;
            overflow: hidden;
        }
        .p_in img{
            width: 100%;
        }
        .twxq{
            overflow: hidden;
            width: 100%;
            height: 50px;
            text-align: center;

        }
        .twxq a{
            display: inline-block;
            padding: 4px 10px;
            border: 1px solid rgb(220,220,220);
            border-radius: 4px;
            margin-top: 10px;
        }
        .er{
            height: 52px;
        }
        .erlist{
            margin-bottom: 10px;
        }
        .erlist img{
            width: 100%;
        }
        .er ul:first-child{
            margin-left: 10px;

        }
        .er ul:last-child{
            margin-right: 10px;
            background-color: #f53a3a;
            padding: 1px 4px 0px 5px;;
            color: white;
            font-size: 12px;
            border-radius: 4px;
        }
        .er ul:last-child a{
            color: white;
        }
        .ertitle{
            margin-top: 10px;
            height: 44px;
        }
        .ertitle ul{
            height: 44px;
            margin-left: 10px;
            margin-right: 10px;
        }
        .db{
            width: 100%;
            height: 48px;
            position: fixed;
            z-index: 999;
            bottom: 0;
            display: flex;
            align-items: center;
        }
        .db ul{
            height: 48px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .db ul a{
            color: white;
        }
        .db ul:last-child{
            width: 34%;
            background-color: #f55c54;
        }
        .db ul:nth-child(3){
            width: 26%;
            background-color: #ea9b00;
        }
        .db ul:nth-child(2){
            width: 20%;
            border-top: 1px solid rgb(220,220,220);
        }
        .db ul:nth-child(1){
            width: 20%;
            border-top: 1px solid rgb(220,220,220);
        }
        .tatm{
            text-align: center;
            margin-bottom: 100px;
            overflow: hidden;
        }
        .tatm h3{
            margin-top: 10px;
            margin-bottom: 20px;
        }
        .tatm .avator li{
            width: 48px;
            height: 48px;
            overflow: hidden;
            border-radius: 30px;
        }
        .tatm .avator li img{
            width: 48px;
            height: 48px;
        }
        .tatm .avator{
            margin-left: 10px;
        }
        .tatm div{
            margin-bottom: 20px;
        }
        .pr{
            position: fixed;
            bottom: 48px;
            width: 100%;
            height: 48px;
            background-color: white;
            display:flex;
            justify-content: space-between;
            align-items: center;
        }
        .pr ul{
            text-align: center;
        }
        .pr ul:first-child{
            width: 24%;
        }
        .pr ul:nth-child(2){
            width: 35%;
        }
        .pr ul:last-child{
            width: 24%;
        }



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

    </style>
@endsection
@section('content')
<body id="product" style="background-color: #ffffff;">
<div class="mui-slider">
    <div class="mui-slider-group mui-slider-loop">
        <!--支持循环，需要重复图片节点-->
        @foreach($spuImages as $img)
            <div class="mui-slider-item mui-slider-item-duplicate"><a href="#"> <img src="{{$img->image_url}}" alt=""></a></div>
        @endforeach
    </div>
    <div class="mui-slider-indicator">
        @foreach($spuImages as $img)
        <div class="mui-indicator mui-active"></div>
        @endforeach
    </div>
</div>
<input type="hidden" id="sid" value="{{ $goods->spu_id }}">
<div class="p_in b-white" style="height: 30px;overflow: hidden">
    <p style="margin-left: 20px;">{{$goods->spu_name}}</p>
    <p>{{$goods->ad_info}}</p>
</div>
<input type="hidden" name="is_virtual" id="is_virtual" value="{{$goods->is_virtual}}">
    <form id="tuwen" action="{{asset('/shop/goods/mobileContent')}}" method="post">
            <input type="hidden" name="mobilecontent" id="mobilecontent" value="{{$goods->mobile_content}}" />
            <input type="hidden" name="spu_id" id="spu_id" value="{{$goods->spu_id}}" />
            <input type="hidden" name="from_plat_code" id="from_plat_code" value="{{$goods->from_plat_code}}" />
                <div class="twxq b-white1" style="background-color: #ffffff;" >
                    <a href="javascript:void(0)" class="font-14 color-80">查看图文详情</a>
                </div>
    </form>
{{--<div class="ertitle b-white">
    <ul class="flex-between">
        <li class=" font-14 color-f53a3a">一起买更优惠</li>
        <li class="font-12 color-80">召唤朋友一起买可省 <span class="font-14 color-f53a3a">29元</span></li>
    </ul>
</div>
<div class="erlist b-white">
    <a href="call_friend_goodsDetail.html"><img src="http://anydo.wang/images/sp/sp1.jpg"/></a>
    <div class="er flex-between">
        <ul style="width: 75%;line-height: 20px;height: 40px;overflow: hidden;font-size: 12px;">
            <li class="font-12 color-50">{{$goods->spu_name}}</li>
            <li class="font-12 color-100">{{$goods->ad_info}}</li>
        </ul>
        <ul class="flex-center" style="font-size: 12px;"><a style="padding: 1px 4px;" href="{{asset('member/callFriend')}}">一起买</a></ul>
    </div>
</div>--}}
<div class="db b-white">
    <ul>
        <li>
            <svg class="icon font-22 color-160"  style="margin-top: 4px" aria-hidden="true"><use xlink:href="#icon-shoucang"></use> </svg>
        </li>
        <li class="font-12 color-100">
            @if ($goods->collect_status)
                已
            @endif
            关注
        </li>
    </ul>
    <ul>
        <a href="{{asset('cart/index')}}" id="sd_cart">
            <li>
                <svg class="icon font-22 color-160" style="margin-top: 4px" aria-hidden="true"><use xlink:href="#icon-gouwuchexuanzhong"></use> </svg>
            </li>
            <li class="font-12 color-100">购物车</li>
        </a>
    </ul>
    <ul class="flex-center font-14 color-white" id="gift" style="font-size: 15px;">
        <a href="javascript:void(0)" ><li>送礼</li></a>
    </ul>
    <ul class="flex-center font-14 color-white" id="directorder" style="font-size: 15px;">
        <a href="#popover{{$goods['spu_id']}}"><li>立即购买</li></a>
    </ul>
</div>
{{--<div class="tatm b-white">
    <h3 class="font-14 color-80">他们都在一起买</h3>
    <div class="flex-between">
        <ul class="flex-between avator">
            <li><img src="{{asset('pic/me_avator_default.png')}}" alt=""></li>
            <li><img src="{{asset('pic/me_avator_default.png')}}" alt=""></li>
            <li><img src="{{asset('pic/me_avator_default.png')}}" alt=""></li>
            <li><img src="{{asset('pic/me_avator_default.png')}}" alt=""></li>
            <li><img src="{{asset('pic/me_avator_default.png')}}" alt=""></li>
        </ul>
        <ul class="flex-between">
            <li class="font-12"><a href="{{asset('member/callFriendBuy')}}" class="color-80">去看看</a></li>
            <li><img src="{{asset('sd_img/next.png')}}" alt="" class="size-24"></li>
        </ul>
    </div>
</div>--}}
<div class="pr">
    <ul>
        <li class="font-14 color-f53a3a">价格{{$goods->spu_price}}</li>
        {{--<li class="font-12 color-100">快递15元</li>--}}
    </ul>
    <ul>
        {{--<li class="font-12 color-80">销量599笔</li>--}}
    </ul>

    <ul id="add_cart">
        <a href="#popover{{$goods['spu_id']}}">
        <li>
            <svg class="icon font-24 color-f53a3a" style="margin-top: 4px" aria-hidden="true"><use xlink:href="#icon-icon"></use> </svg>
        </li>
        <li class="font-12 color-80">加入购物车</li>
        </a>
        <input type="hidden" value="0" id="is_add">
    </ul>




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








<form action="{{asset('sd_cart/add')}}" method="post" name="add_form" id="add_form">
    <input type="hidden" name="spu_ids" id="spu_ids" value="">
    <input type="hidden" name="specs" id="specs">
    <input type="hidden" name="gift_nums" id="gift_nums" value="1">
</form>



</body>
@endsection

@section('js')
<script type="text/javascript" src="{{asset('sd_js/mui.min.js')}}"></script>
<script type="text/javascript" src="{{asset('sd_js/common.js')}}"></script>
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

<script>

    var gallery = mui('.mui-slider');
    gallery.slider({
        interval:5000//自动轮播周期，若为0则不自动播放，默认为0；
    });
    $(".b-white1").on('click',function(){
        $('#tuwen').submit();
    });


</script>

@endsection