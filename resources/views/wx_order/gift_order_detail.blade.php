@extends('inheritance')

@section('title')
    微信分享礼品订单详情
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('sd_css/mui.min.css')}}">
    <link rel="stylesheet" href="{{asset('sd_css/common.css')}}">
    <style>
        *{ margin:0; padding:0; list-style:none;}
        img{ border:0;}

        .lanrenzhijia {position: fixed;left: -100%;right:100%;top:0;bottom: 0;text-align: center;font-size: 0; z-index:9999; display:none;}
        .lanrenzhijia:after {content:"";display: inline-block;vertical-align: middle;height: 100%;width: 0;}
        .content{display: inline-block; *display: inline; *zoom:1;	vertical-align: middle;position: relative;right: -100%;}
        .content_mark{ width:100%; height:100%; position:fixed; left:0; top:0; z-index:555; background:#000; opacity:0.5;filter:alpha(opacity=50); display:none;}

        .icon{
            width: 1em;
            height: 1em;
            vertical-align: -0.15em;
            fill: currentColor;
            overflow: hidden;
        }

        .order-list{
            width: 100%;
        }

        .order-list .order-list-title{
            width: 100%;
            height: 40px;
        }
        .order-list .order-list-title li:first-child{
            margin-left: 3%;
        }
        .order-list .order-list-title li:last-child{
            margin-right: 3%;
        }

        .order-list-content{
            width: 100%;
            height: 110px;
            background-color: rgb(250,250,250);
        }
        .order-list-content-l{
            margin-left: 3%;
        }

        .order-list-content-r{
            margin-left: 4%;
            margin-right: 3%;
        }
        .order-list-content li:nth-of-type(1) img{
            width: 80px;
        }
        .order-list-optionBar{
            width: 100%;
        }
        .order-list-optionBar ul{
            height: 50px;
            margin-right: 3%;
        }
        .order-list-optionBar li{
            width: 88px;
            height: 32px;
            border: 1px solid rgb(220,220,220);
            border-radius: 6px;
            margin-left: 3%;
        }
        .order-list-optionBar .pay{
            border: 1px solid #f53a3a;
        }

        .order-discount{
            width: 100%;
            background-color: rgb(250,250,250);
        }
        .order-discount ul{
            width: 94%;
            height: 24px;
            margin: 0 auto;
        }
        .order-discount ul:last-child{
            height: 30px;
        }
        .order-detail{
            margin-top: 10px;
            margin-bottom: 10px;
            overflow: hidden;
        }
        .order-detail ul{
            width: 94%;
            margin: 10px auto 10px;
        }
        .zhengyu{
            width: 100%;
            overflow: hidden;
        }
        .zhengyu ul{
            width: 95%;
            margin: 0 auto;
        }
        .zhengyu ul li:first-child{
            line-height: 44px;
        }
        .zhengyu ul .content{
            margin-bottom: 10px;
        }
        .lipinstate{
            width: 100%;
            box-shadow: 0 1px 1px rgba(50,50,50,0.4);
        }
        .lipinstate ul{
            width: 95%;
            margin: 10px auto 10px;
            text-align: center;
        }
        .lingquzt{
            width: 98%;
            margin: 10px auto;
            border-radius: 6px;
            overflow: hidden;
        }
        .lingquzt div{
            width: 95%;
            margin: 0 auto;
        }
        .lingquzt .zhuangtat{
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
            margin-bottom: 10px;
        }
        .lingquzt .zhuangtat ul:first-child{
            width: 63%;
        }
        .lingquzt .zhuangtat li:first-child{
            width: 40px;
            height: 40px;
            overflow: hidden;
            border-radius: 30px;
        }
        .lingquzt .zhuangtat li:first-child img{
            width: 40px;
            height: 40px;
        }
        .lingquzt .ganxieyu{
            min-height: 6px;
        }
        .lingquzt .time{
            text-align: right;
            margin: 0px 0;
        }


    </style>
@endsection

@section('content')
<body>
<div class="order-list">


    <div class="order-list-title b-white flex-between">
        <li class="font-12 color-80">订单号：{{$order_info['plat_order_sn']}}</li>
        <li class="font-14 color-f53a3a">
            @if($order_info['plat_order_state']==1)
                    待付款
            @elseif($order_info['plat_order_state']==2)
                    已付款
            @elseif($order_info['plat_order_state']==3)
                    待收货
            @elseif($order_info['plat_order_state']==4)
                已完成
            @elseif($order_info['plat_order_state']==9)
                已完成
            @elseif($order_info['plat_order_state']==-1)
                已取消
            @elseif($order_info['plat_order_state']==-2)
                已退单
            @elseif($order_info['plat_order_state']==-5)
                已退货
            @elseif($order_info['plat_order_state']==-9)
                已删除
            @endif

        </li>
    </div>



    <!--商品信息-->
    @foreach($order_info['skus'] as $goods)

        <div class="order-list-content flex-between">
            <ul class="order-list-content-l">
                <li><img src="{{asset($goods['sku_image'])}}" alt=""></li>
            </ul>
            <div class="order-list-content-r">
                <ul>
                    <li class="r-title font-12 color-80">{{$goods['sku_name']}}</li>
                    <li class="r-property font-12 color-160">规格： @foreach($goods['sku_spec'] as $spec)<span>{{$spec}}&nbsp;</span>@endforeach</li></ul>
                <ul class="font-14 color-80 flex-between">
                    <li>¥{{$goods['settlement_price']}}</li>
                    <li>&nbsp;&times;&nbsp;{{$goods['number']}}</li>
                </ul>
            </div>
        </div>
    @endforeach

    {{--@if($order_info['plat_order_state']==1 || $order_info['plat_order_state']==2)
        <div class="order-list-optionBar font-12 color-80 b-white">
            <ul class="flex-end">
                <li class="flex-center" id="cancel-order" order_id="{{$order_info['plat_order_id']}}">取消订单</li>
            </ul>
        </div>
    @endif--}}

</div>

<div class="order-discount">
    <ul class="font-12 color-160 flex-between">
        <li>商品总价</li>
        <li>¥{{$order_info['goods_amount_totals']}}</li>
    </ul>
    <ul class="font-12 color-160 flex-between">
        <li>运费</li>
        <li>¥{{$order_info['fare_amount']}}</li>
    </ul>
    @if(isset($order_info['payment']['pay_wallet']))
        <ul class="font-12 color-160 flex-between">
            <li>使用零钱</li>
            <li>-¥{{$order_info['payment']['pay_wallet']['pay_amount']}}</li>
        </ul>
    @endif
    @if(isset($order_info['payment']['pay_card_balance']))
        <ul class="font-12 color-160 flex-between">
            <li>使用卡余额</li>
            <li>-¥{{$order_info['payment']['pay_card_balance']['pay_amount']}}</li>
        </ul>
    @endif
    @if(isset($order_info['payment']['pay_vrb']))
        <ul class="font-12 color-160 flex-between">
            <li>使用{{$plat_vrb_name}}</li>
            <li>-¥{{$order_info['payment']['pay_vrb']['pay_amount']}}
                (抵现：¥{{$order_info['payment']['pay_vrb']['pay_amount_to_rmb']}})</li>
        </ul>
    @endif

    <ul class="font-14 flex-between">
        <li class="color-80">实付款</li>
        <li class="color-f53a3a">¥&nbsp;{{$order_info['pay_rmb_amount']}}</li>
    </ul>
</div>

<div class="zhengyu b-white" style="margin-top: 8px">
    <ul>
        {{--<li class="font-14 color-50" style="line-height:20px;padding-top:6px;">礼品分享标题：</li>
        <textarea style="width: 100%;padding:0px;margin-bottom:5px;margin-top:4px;;;height: 25px;font-size: 14px;font-family: 楷体,楷体_GB2312;" readonly="readonly">{{$share_info->gifts_title}}</textarea>

        --}}{{--<li class="title font-12 color-f53a3a">{{$share_info->gifts_title}}</li>--}}{{--
        <li class="font-14 color-50" style="line-height:20px;">给商家留言：</li>--}}
        <textarea style="width: 100%;padding:0px;margin-bottom:5px;margin-top:4px;font-size: 14px;font-family: 楷体,楷体_GB2312;" readonly="readonly">{{$share_info->gifts_message}}</textarea>

        {{--<li class="content font-12 color-80">--}}
           {{--{{$share_info->gifts_message}}--}}
        {{--</li>--}}
    </ul>
</div>
<div class="lipinstate marginTop flex-between b-white">
    <ul class="font-14">
        <li >礼品数量</li>
        <li class="font-b color-f53a3a">{{$share_info->gifts_num}}</li>
    </ul>
    <ul>
        <li>待领取</li>
        <li class="font-b color-f53a3a">{{$share_info->gifts_num - $share_info->current_num}}</li>
    </ul>
    <ul>
        <li>已领取</li>
        <li class="font-b color-f53a3a">{{$share_info->current_num}}</li>
    </ul>

</div>

@if($get_info)

    @foreach($get_info as $getInfo)
        <div class="lingquzt b-white">
            <div class="zhuangtat" >
                <ul style="display: flex;margin-left: 4px;">
                    <li>
                        <img src="{{$getInfo->avatar}}" alt="">
                    </li>
                    <li  style="text-align: left;margin-top: 11px;margin-left: 10px;font-size: 14px;color: black;">{{$getInfo->nick_name}}</li>
                </ul>
                <ul>
                    <li class="font-14 color-f53a3a" style="line-height: 50px;margin-bottom: 6px">已领取</li>
                </ul>
            </div>

            <div class="ganxieyu">
                <li class="font-12 color-80" style="font-family: 楷体,楷体_GB2312;">
                    @if($getInfo->thanks_content)
                        {{$getInfo->thanks_content}}
                    @else
                        谢谢您的礼品！
                    @endif
                </li>
            </div>
            <div class="time">
                <li class="font-12 color-100">{{date('Y-m-d H:i:s', $getInfo->create_time)}}</li>
            </div>
        </div>

    @endforeach

@endif
<div class="lanrenzhijia" id="pop-ad">
    <div class="content">
        <img width="270px;" height="290px" src="{{asset('/sd_img/subscribe_pic.png')}}" usemap="#Map" />
        <map name="Map">
            <area shape="rect" coords="234,5,265,31" href="javascript:hideSubscribe();">
        </map>
    </div>
</div>
<div class="content_mark"></div>

</body>

@endsection

@section('js')
    <script src="{{asset('sd_js/font_wvum.js')}}"></script>
    <script type="text/javascript" src="{{asset('sd_js/common.js')}}"></script>

    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script type="text/javascript">
        var appId = '{{$signPackage['appId']}}';
        var timestamp = '{{$signPackage['timestamp']}}';
        var nonceStr = '{{$signPackage['nonceStr']}}';
        var signature = '{{$signPackage['signature']}}';

        var link = '{{$share_link}}';
        var title = '{{$share_info->gifts_title}}'==''?'微信分享礼品':'{{$share_info->gifts_title}}';
        var imgUrl = '{{$goods['sku_image']}}';

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
                desc: '时间在变，但是我们的情谊永不变，小小礼物，希望你能喜欢！', // 分享描述
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


        //取消订单
        $('#cancel-order').on('click',function () {

            var order_id = $(this).attr('order_id');
            if (confirm("确定取消该订单吗？")){
               //用于微信礼品分享的订单
               $.get('/gift/cancelShareGiftOrder/'+ order_id, function (data) {
                   if (data['code'] == 0) {
                      //刷新本页面
                      message('取消成功！');
                      window.location.reload();//刷新当前页面.
                      return true;
                   } else {
                      message(data['message']);
                      return false;
                   }
               });
            }

        });

        function showSubscribe() {
            $('.lanrenzhijia').show(0);
            $('.content_mark').show(0);
        }

        function hideSubscribe(){
            $('.lanrenzhijia').hide(0);
            $('.content_mark').hide(0);
        }

    </script>
    @if($subscribe==0)
        <script>
            showSubscribe();
        </script>
    @endif
@endsection

