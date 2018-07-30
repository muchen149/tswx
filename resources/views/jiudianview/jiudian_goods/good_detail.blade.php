@extends('inheritance')

@section('title')
    商品详情
@endsection

@section('css')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title></title>
    <link rel="stylesheet" href="/sd_css/swiper.min.css">
    <link rel="stylesheet" href="/sd_css/new_common.css">
    <link rel="stylesheet" href="/sd_css/shop.css">
    {{--选择规格所需样式--}}
    <link href="{{asset('sd_css/goods/good.css')}}" rel="stylesheet">
    <link href="{{asset('sd_css/goods/product_detail.css')}}" rel="stylesheet">

    <link href="{{asset('jiudian_css/date.css')}}" rel="stylesheet" />

    <style>
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

<body id="product">
<div class="container">
    <div class="swiper-container detail-banner">
        <div class="swiper-wrapper">
            @foreach($spuImages as $img)
                <div class="swiper-slide"><a href=""><img src="{{$img->image_url}}" width="100%" alt=""></a></div>
            @endforeach
        </div>
        <!-- Add Pagination -->
        <div class="swiper-pagination"></div>
    </div>

    <div class="detail-baseInfo">
        <p style="font-family: 微软雅黑;font-size: 18px;font-weight: 400">{{$goods->spu_name}}</p>
        {{-- <p class="tc">为什么选择这款大米，这款大米和其它大米有什么不同呢</p>
         <p>1、为什么选择这款大米</p>--}}
        {!! $goods->ad_info !!}
        <form id="tuwen" action="{{asset('/shop/goods/mobileContent')}}" method="post">
            <input type="hidden" name="mobilecontent" id="mobilecontent" value="{{$goods->mobile_content}}" />
            <input type="hidden" name="spu_id" id="spu_id" value="{{$goods->spu_id}}" />
            <input type="hidden" name="from_plat_code" id="from_plat_code" value="{{$goods->from_plat_code}}" />
            <div id="twxq"  >
                <a href="javascript:void(0)" class="btn-more">查看图文详情</a>
            </div>
        </form>
    </div>
    <div style="padding: 0 5px;">
        <!-- 入住时间选择 -->
        <div class="select-time">
            <span class="time entertime"></span>
            <input type="text" class="input-enter none" id="enterdatet">
            <span>入住</span>
            <span class="c-back">一</span>
            <span class="time leavetime"></span>
            <input type="text" class="input-leave none" id="leavedatet">
            <span>离店</span>
            <span class="night" id="night" value="1" >共1晚</span>
        </div>

    </div>
    <input type="hidden" id="sid" value="{{ $goods->spu_id }}">
    <div id="sku_list">
        {{--<div class="media-body">
            <h6 class="media-heading" id="choose-name" style="font-size:12px;color: rgb(80,80,80);margin-top: 14px;">{{$goods->spu_name}}</h6>
            <p class="jiaqian" style="color: #f13030"><span>¥&nbsp;</span><span id="choose-price">{{$goods->spu_price}}</span>
                --}}{{--<span style="color: #999;font-weight: normal;font-size: 13px;">&nbsp;&nbsp;¥&nbsp;<s>{{$goods->spu_market_price}}</s></span>--}}{{--
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
        </div>--}}
        {{--@if($skus)--}}
            {{--@foreach($skus as $sku)--}}

                {{--<div class="media">--}}

                    {{--<a class="media-left" href="#">--}}
                        {{--<div style="text-align: center;">--}}
                            {{--<input type="checkbox" name="sku2buy" value="{{$sku->sku_id}}" text="{{$sku->sku_name}}" /> <img src="{{$sku->img}}" alt="加载中..." style="height:80px;width: 80px;">--}}
                        {{--</div>--}}

                    {{--</a>--}}
                    {{--<div class="media-body">--}}
                        {{--<h6 class="media-heading" id="choose-name" style="font-size:12px;color: rgb(80,80,80);margin-top: 14px;">{{$sku->sku_name}}</h6>--}}
                        {{--<p class="jiaqian" style="color: #f13030"><span>¥&nbsp;</span><span id="choose-price"></span>--}}
                            {{--<span style="color: #999;font-weight: normal;font-size: 13px;">&nbsp;&nbsp;¥&nbsp;<s>{{$goods->spu_market_price}}</s></span>--}}
                        {{--</p>--}}
                       {{-- @if($goods->spu_points>=0)--}}
                            {{--<p style="margin-top:5px;margin-left:-11px;margin-bottom: 5px;" class="aa" id="p_id">--}}
                                {{--<span  class="small-price" style="font-size:13px;color: rgb(120,120,120);padding-left: 6px;">【可支付{{$plat_vrb_caption}}：<span style="color: #f23030;" id="usable_points">{{$goods->spu_points}}</span>】</span>--}}
                            {{--</p>--}}
                        {{--@else--}}
                            {{--<p style="margin-top:5px;margin-left:-11px;margin-bottom: 5px;" class="bb" id="p_id" hidden>--}}
                                {{--<span  class="small-price" style="font-size:13px;color: rgb(120,120,120);padding-left: 6px;">【可支付{{$plat_vrb_caption}}：<span style="color: #f23030;" id="usable_points">0</span>】</span>--}}
                            {{--</p>--}}
                        {{--@endif--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--@endforeach--}}
        {{--@endif--}}

        {{--<div class="clearfix"></div>--}}

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
    <div class="detail-price">
        <ul>
            <li style="width: 80%;">
                {{--<p class="price"><span>¥ {{$goods->spu_price}}</span>--}}
                {{--<del>¥ 236</del>--}}
                </p>{{--@if($isHighest!=1)
                    <a href="/member/buy"> <p class="sm" style="width: 100%">升级享低折扣<span style="color: #000;">¥ {{$goods->supper_spu_price}}</span> </p></a>
                @endif--}}
                {{--<p class="sm">快递费：{{$goods->spu_price}}</p>--}}
            </li>
           {{-- <li>--}}{{--<p class="sm xl">快递费：15</p>--}}{{--
            </li>--}}
            {{--<li>
                <div  id="add_cart" class="btn-addCart">
                    <a href="#popover{{$goods['spu_id']}}">
                    <i class="icon"></i>
                    <p class="sm">加入购物车</p>
                    <input type="hidden" value="0" id="is_add">
                    </a>
                </div>

            </li>--}}
        </ul>
    </div>
    <div class="bottom-menu">
        <ul>
            {{--<li>
                <a href="{{asset('cart/index')}}" id="sd_cart">
                    <span class="btn-cart">
                    <i class="icon"></i> 购物车 </span>
                </a>
            </li>--}}
            {{--<li id="directorder_ys">
                <a href="javascript:buyClick();">
                    <span class="btn-buy" style="background:#c9151e">立即预约</span>
                </a>
            </li>--}}

            <li>
                <a href="{{asset('/member/inviteFriend')}}" id="sd_cart">
                    <span class="btn-focus" id="focusOn" >
                    <i id="attentionFocus" class="icon focus-out"></i>
                        {{-- @if ($goods->collect_status)
                             已
                         @endif
                         关注--}}
                        分享有奖</span>
                </a>
            </li>
            <li>
                <a href="{{asset('cart/index')}}" id="sd_cart">
                    <span class="btn-cart">
                    <i class="icon"></i> 购物车 </span>
                </a>
            </li>
            {{--<li id="gift">
                <span class="btn-sl"> 送礼 </span>
            </li>--}}
            <li id="directorder">
                    <span class="btn-buy">立即购买</span>
                {{--<button class="buy-now red-color f18">立即购买</button>--}}
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

{{--            <div class="a-choose">
                --}}{{--@if(!empty($spuSpec))--}}{{--
                @foreach($spuSpec as $guige)
                    --}}{{-- {{dd($guige)}}--}}{{--
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
                    --}}{{-- <hr>--}}{{--
                @endforeach
                --}}{{--@endif--}}{{--
            </div>--}}
            <div class="clearfix"></div>
            <div class="col-xs-12 cart-num"  style="display: none;" >
                <div class="col-xs-3">
                    <span>购买数量</span>
                </div>
                <div class="col-xs-9">
                    <div class="text-right change-number">
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

        <div class="col-xs-12 buy-btn">
            <form action="{{asset('/jd/order/showPay')}}" method="post" name="buyForm" id="buyForm">
                <input type="hidden" name="spuId" id="spuId">
                <input type="hidden" name="number" id="number">
                <input type="hidden" name="src" id="src">
                <input type="hidden" name="guiges" id="guiges">
                <input type="hidden" name="nights" id="nights">
                <input type="hidden" name="enterdate" id="enterdate">
                <input type="hidden" name="leavedate" id="leavedate">
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
    <script type="text/javascript" src="/sd_js/jquery-1.11.2.min.js"></script>
    <script type="text/javascript" src="/jiudian_js/date.js"></script>
    <script type="text/javascript" src="/sd_js/swiper.min.js"></script>
    <script type="text/javascript" src="/sd_js/detail.js"></script>
    <script type="text/javascript" src="{{asset('/jiudian_js/jd_product.js')}}"></script>
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

        $("#twxq").on('click',function(){
            $('#tuwen').submit();
        });

/*        function buyClick(){
            $('.form-gui-ge').css('bottom', '-600px');
            $('.xiaoguo').removeClass('yinying');
            $arr = [];

            $('.a-style').each(function (index, value) {
                index = $(this).next().val();
                var v = index + 'CONNECTOR' + $(value).text();
                $arr.push(v);
            });

            $('#spuId').val($('#sid').val());
            $('#number').val($('.text_box').val());
            $('#guiges').val($arr.join('SEPARATOR'));
            $('#src').val($('.img-border').attr('src'));

            $('#buyForm').submit();
        }*/

        function showSkus(){
            var spu_id = $('#sid').val();
            $arr = [];
            $('.a-style').each(function (index, value) {
                index = $(this).next().val();
                $str = index;
                $arr.push($str);
            });
            //console.log($arr);
            var data = {
                "stoken" : stoken,
                "spu_id" : spu_id,
                "ids" : $arr,
                "enterdate":$('#enterdatet').val(),
                "leavedate":$('#leavedatet').val(),
            };
            $.post('/jd/getSkus',data,function (data) {
                //debugger
                var skus=data.skus;
                var content='';
                $.each(skus, function (index, item) {

                    content+=
                    '<div class="media">'+

                        '<a class="media-left" href="#">'+
                            '<div><img src="'+item.img+'" alt="加载中..." style="height:80px;width: 80px;"></div><div style="text-align:center;"><input type="checkbox"  name="sku2buy" value="'+item.sku_id+'"  text="'+item.sku_name+'"  /></div></a>'+
                            '<div class="media-body">'+
                            '<h6 class="media-heading" id="choose-name" style="font-size:12px;color: rgb(80,80,80);margin-top: 14px;">'+item.sku_name+'</h6>'+
                            '<p class="jiaqian" style="color: #f13030"><span>¥&nbsp;'+item.price+'</span><span id="choose-price"></span>'+
                             '{{--<span style="color: #999;font-weight: normal;font-size: 13px;">&nbsp;&nbsp;¥&nbsp;<s>{{$goods->spu_market_price}}</s></span>--}}'+
                            '</p></div></div>';

                });
                $('#sku_list').html(content);


                /*if(data['type'] == 1){
                 $('#choose-name').text(data['sku']['sku_name']);
                 $('#choose-price').text(data['sku']['price']);
                 $('#choose-img').attr('src',data['sku']['img']);
                 //若有购买下限，这显示的为购买下限，默认为1
                 $('#buy_num').val(data['sku']['minimum_limit']);
                 $('#buy_num').attr('min_limit',data['sku']['minimum_limit']);
                 if(data['sku']['sku_points']>=0){
                 $('#usable_points').text(data['sku']['sku_points']);
                 $('#p_id').show();
                 }else{
                 $('#p_id').hide();
                 }
                 if(!data['sku']['is_can_buy']){
                 $('.join-btn').unbind('click').find('.join-now').addClass('disabled').removeClass('red-color');
                 $('.buy-btn').unbind('click').find('.buy-now').addClass('disabled').removeClass('red-color');
                 $('.share-gift').unbind('click').find('.share-now').addClass('disabled').removeClass('yellow-color');

                 }else{
                 $('.join-btn').bind('click', joinClick).find('.join-now').removeClass('disabled').addClass('red-color');
                 $('.buy-btn').bind('click', buyClick).find('.buy-now').removeClass('disabled').addClass('red-color');
                 $('.share-gift').bind('click', buyClick).find('.share-now').removeClass('disabled').addClass('yellow-color');

                 }
                 }*/
            });
        }

        $('.select-time').hotelDate();
        showSkus();
    </script>
@endsection