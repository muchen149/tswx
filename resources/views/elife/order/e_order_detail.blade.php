@extends('inheritance')

@section('title')
    订单详情
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('elife_css/mui.min.css')}}">
    <link rel="stylesheet" href="{{asset('elife_css/common.css')}}">
    <style>
        *{ margin:0; padding:0; list-style:none;}
        img{ border:0;}

        .lanrenzhijia {position: fixed;left: -100%;right:100%;top:0;bottom: 0;text-align: center;font-size: 0; z-index:9999; display:none;}
        .lanrenzhijia:after {content:"";display: inline-block;vertical-align: middle;height: 100%;width: 0;}
        .content{display: inline-block; *display: inline; *zoom:1;	vertical-align: middle;position: relative;right: -100%;}
        .content_mark{ width:100%; height:100%; position:fixed; left:0; top:0; z-index:555; background:#000; opacity:0.5;filter:alpha(opacity=50); display:none;}

        .head .nav{
            position: fixed;
            top: 0;
            padding: 0 4px;
            line-height: 40px;
            width:100%;
            z-index: 999999;
            font-family: "微软雅黑";
        }
        .head .nav .return{
            display: inline-block;
            width: 40%;
        }

        .icon{
            width: 1em;
            height: 1em;
            vertical-align: -0.15em;
            fill: currentColor;
            overflow: hidden;
        }
        .address{
            border-bottom: 1px solid rgb(230,230,230);
            box-shadow: 0 1px 1px rgba(80,80,80,.1);
        }
        .address div{
            width: 95%;
            margin: 10px auto;
        }
        .location-border{
            width: 100%;
            height: 4px;
            background-image: url("../../../sd_img/address-location-border.png");
            background-repeat: repeat-x;
            background-size: 30% 4px;
            margin-bottom: 10px;
        }
        .address .r{
            margin-left: 16px;
            width: 91%;
        }
        .address .r li:first-child{
            margin-top: 10px;
            margin-bottom: 8px;
        }
        .address .r .r-b{
            margin-bottom: 10px;
            line-height: 22px;
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
            margin-bottom: 20px;
            overflow: hidden;
        }
        .order-detail ul{
            width: 94%;
            margin: 10px auto 10px;
        }
        .showShipping {
            background-color: white;
            border: 1px solid rgb(50, 142, 200);
            border-radius: 6px;
            width: 90px;
            height: 32px;
            margin-right: 5%;
            margin-top: 9px;
            color: rgb(50, 142, 200);
            font-size: 13px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .nav_bar{
            background: #fff;
            padding: 10px 0px;
        }
    </style>
@endsection

@section('content')
    <body>
    <div class="head">
        <div class="nav b-white">
            <a class="return font-14 color-80" href="{{asset('elife/personal/index')}}"><img src="{{asset('sd_img/next.png')}}" alt="" class="size-26" style="transform:rotate(180deg);"></a>
            <a class="title font-18 color-80" href="#">订单详情</a>
        </div>
    </div>
    @if (!empty($order_address))
        <div class="address b-white" style="margin-top:50px;">
            <div class="flex-between">
                <ul class="l">
                    <li>
                        <svg class="icon font-26 color-100" aria-hidden="true"><use xlink:href="#icon-infenicon07"></use> </svg>
                    </li>
                </ul>
                <ul class="r">
                    <ul class="r-t flex-between font-14 color-50">
                        <li >收货人:<span>{{$order_address['recipient_name']}}</span></li>
                        <li>{{$order_address['mobile']}}</li>
                    </ul>
                    <li class="r-b font-12 color-80">收货地址：
                <span>
                    {{$order_address['address']}}
                </span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="location-border"></div>

    @endif


    <div class="order-list">
        <div class="order-list-title b-white flex-between">
            <li class="font-12 color-80">订单号：{{$order_info['plat_order_sn']}}</li>
            <li class="font-14 color-e42f46">
                @if($order_info['plat_order_state']==1)

                    @if($order_info['pay_mode_id'] == 7 )
                        电子汇款
                    @else
                        待付款
                    @endif

                @elseif($order_info['plat_order_state']==2)

                    @if($order_info['pay_mode_id'] == 5 )
                        货到付款
                    @elseif($order_info['pay_mode_id'] == 8)
                        账期支付
                    @else
                        已付款
                    @endif

                @elseif($order_info['plat_order_state']==3)

                    @if($order_info['pay_mode_id'] == 5 )
                        货到付款
                    @elseif($order_info['pay_mode_id'] == 8)
                        账期支付
                    @else
                        待收货
                    @endif


                @elseif($order_info['plat_order_state']==4)
                    已完成
                @elseif($order_info['plat_order_state']==9)
                    已完成
                @elseif($order_info['plat_order_state']==-1)
                    已取消
                @elseif($order_info['plat_order_state']==-2)
                    已退单
				@elseif($order_info['plat_order_state']==-3)
                    退单中
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
                <div class="order-list-content-r" style="width: 90%;">
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



        <div class="order-list-optionBar font-12 color-80 b-white">
            <ul class="flex-end">

                @if($order_info['plat_order_state']==1)
                    {{--若订单为电子支付，则显示该订单的银行支付凭证--}}
                    @if($order_info['pay_mode_id'] == 7)
                        <li class="flex-center" order_id="{{$order_info['plat_order_id']}}">上传支付凭证</li>

                    @else
                        <li class="flex-center pay" order_id="{{$order_info['plat_order_id']}}" id="go-to-pay">立即付款</li>

                    @endif
                    {{-- <li class="flex-center cancel-order" order_id="{{$order_info['plat_order_id']}}" >取消订单</li>--}}
                @elseif($order_info['plat_order_state']==2)

                    @if($order_info['pay_mode_id'] == 5 || $order_info['pay_mode_id'] == 8)
                        <li class="flex-center pay" order_id="{{$order_info['plat_order_id']}}" id="go-to-pay">立即付款</li>
                    @endif

                    {{--领取的微信礼品订单生成后不能在取消--}}
                    {{-- @if($order_info['is_get_gift'] == 0)
                         <li class="flex-center cancel-order" order_id="{{$order_info['plat_order_id']}}" is-share="{{$order_info['is_share_gifts']}}">取消订单</li>
                     @endif--}}
                    {{--<div class="bottom-but " style=" margin-right: 3%">--}}
                    {{--<a class="bb-btn1-white refund-order" order_id="{{$order_info['plat_order_id']}}"--}}
                    {{--style="font-size:14px;">退单</a>--}}
                    {{--</div>--}}
                @elseif($order_info['plat_order_state'] == 3)
                    @if($order_info['pay_mode_id'] == 5 || $order_info['pay_mode_id'] == 8)
                        <li class="flex-center pay" order_id="{{$order_info['plat_order_id']}}" id="go-to-pay">立即付款</li>

                    @else
                        <li class="flex-center showShipping" order="{{$order_info['plat_order_id']}}">查看物流</li>
						<li class="flex-center sure_order" order="{{$order_info['plat_order_id']}}">确认收货</li>


                    @endif

                @elseif($order_info['plat_order_state']==4 || $order_info['plat_order_state']== 9)

                    @if($order_info['plat_order_state']== 4 && $order_info['pay_mode_id'] == 8)
                        <li class="flex-center pay" order_id="{{$order_info['plat_order_id']}}" id="go-to-pay">立即付款</li>

                    @else
                        <li class="flex-center showShipping" order="{{$order_info['plat_order_id']}}">查看物流</li>

                    @endif

                @endif


            </ul>
        </div>
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
        @if(isset($order_info['payment']['org_pay_card']))
            <ul class="font-12 color-160 flex-between">
                <li>机构折扣</li>
                <li>-¥{{$order_info['payment']['org_pay_card']['pay_amount']}}</li>
            </ul>
        @endif
        {{--<ul class="font-12 color-160 flex-between">--}}
        {{--<li>优惠券</li>--}}
        {{--<li>-¥20.00</li>--}}
        {{--</ul>--}}
        <ul class="font-14 flex-between">
            <li class="color-80">实付款</li>
            <li class="color-e42f46">¥{{$order_info['pay_rmb_amount']}}</li>
        </ul>
    </div>

    @if($message!=null)
        <textarea style="width: 100%;height: 90px;font-size: 14px;" readonly="readonly">{{$message}}</textarea>
    @endif

    {{--<div class="order-detail b-white">--}}
    {{--<ul class="font-12 color-80">--}}
    {{--<li>订单编号：<span>201403312589</span></li>--}}
    {{--<li>创建时间：{{date( "Y-m-d H:i:s", $order_info['create_time'])}}</li>--}}
    {{--<li>支付时间：2017-03-31 20:14:02</li>--}}
    {{--</ul>--}}
    {{--</div>--}}
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
    <script type="text/javascript" src="{{asset('elife_js/mui.min.js')}}"></script>
    <script src="{{asset('elife_js/jquery.min.js')}}"></script>
    <script src="{{asset('elife_js/font_wvum.js')}}"></script>
    <script src="{{asset('elife_js/common.js')}}"></script>

    <script>
        $(function(){
            orderListContent();
        });
        function orderListContent(){
            $('.order-list').find('.order-list-content:gt(0)').css('border-top','2px solid white');
        }

        //去付款
        $('#go-to-pay').on('click', function () {
            var order_id = $(this).attr('order_id');
            window.location.href = '/elife/pay/gypay?plat_order_id='+order_id;
        });

        //取消订单
        $('.cancel-order').on('click',function () {

            var order_id = $(this).attr('order_id');
            var cancel = $(this);
            var is_share = $(this).attr('is-share');
            var r=confirm("确定取消该订单吗？");
            if (r==true){
                if(is_share == 1){
                    //用于微信礼品分享的订单
                    $.get('/gift/cancelShareGiftOrder/'+ order_id, function (data) {
                        if (data['code'] == 0) {
                            //刷新本页面
                            window.location.reload();//刷新当前页面.
                            return true;
                        } else {
                            message(data['message']);
                        }
                    });

                }else{
                    //普通，状态为未付款的订单取消
                    $.get('/order/cancel/'+ order_id,function (data) {
                        if (data['code'] == 0) {
                            message("取消成功！");
                            //刷新本页面
                            window.location.reload();//刷新当前页面.
                            return true;
                        } else {
                            message(data['message']);
                            return false;
                        }
                    });

                }

            }

        });

		 //确认订单
    $('.sure_order').on('click', function () {
        var sure_complete = $(this);
        var data = sure_complete.attr("order");
        $.get('/elife/order/transferConfirmReceipt/' + data, function (data) {
            if (data['code'] == 0) {
                sure_complete.parent().parent().parent().slideUp(300, function () {
                    sure_complete.parent().parent().parent().remove();
                    message('已完成！');
                });
            } else {
               message('系统繁忙，请稍后重试！');
            }

        })
    });
		
        //查看物流
        $(".showShipping").on('click', function () {
            var data = $(this).attr("order");
			window.location.href = '/elife/order/logistics/' + data;
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
@endsection

