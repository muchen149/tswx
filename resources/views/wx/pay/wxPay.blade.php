@extends('inheritance')
@section('css')
    <link type="text/css" href="{{asset('css/order/order_index.css')}}" rel="stylesheet">
    <link href="{{asset('css/footer.css')}}" rel="stylesheet">
    <link href="{{asset('css/header.css')}}" rel="stylesheet">

@endsection
@section('title')
    水丁收银台
@endsection
@section('content')
    <body id="wxPay" class="col-xs-12" style="background-color: #f0f0f0">
    <div id="header"></div>

    <div class="hang col-xs-12" style="background-color: white">
        <div class="col-xs-6">
            订单金额：
        </div>
        <div class="col-xs-6 jiaqian text-right">
            ¥&nbsp;{{$plat_order->payable_amount}}
        </div>
    </div>
    @if(isset($arr_payment_info['pay_vrb']))
        <div class="hang col-xs-12" style="background-color: white">
            <div class="col-xs-6">
                {{$plat_vrb_caption}}抵：
            </div>
            <div class="col-xs-6 jiaqian text-right">
                ¥&nbsp;{{$arr_payment_info['pay_vrb']['pay_amount_to_rmb']}}
            </div>
        </div>
    @endif
    @if(isset($arr_payment_info['pay_wallet']))

        <div class="hang col-xs-12" style="background-color: white">
            <div class="col-xs-6">
                零钱支付：
            </div>
            <div class="col-xs-6 jiaqian text-right">
                ¥&nbsp;{{$arr_payment_info['pay_wallet']['pay_amount']}}
            </div>
       </div>
    @endif
    @if(isset($arr_payment_info['pay_card_balance']))

        <div class="hang col-xs-12" style="background-color: white">
            <div class="col-xs-6">
                卡余额支付：
            </div>
            <div class="col-xs-6 jiaqian text-right">
                ¥&nbsp;{{$arr_payment_info['pay_card_balance']['pay_amount']}}
            </div>
        </div>
    @endif

    <div class="hang col-xs-12" style="background-color: white">
            <div class="col-xs-6">
                实付金额：
            </div>
            <div class="col-xs-6 jiaqian text-right">
                ¥&nbsp;{{$plat_order->pay_rmb_amount}}
            </div>
    </div>
    <!------------------------分割线  START!---------------------------->
    <div class="col-xs-12 fengexian" style="rgb(240,240,240);"></div>
    <!--======================分割线  END!============================-->

    <div class="col-xs-12" style="background-color: white">
        <p class="hang f16 fz">支付方式</p>

        {{--<div class="hang zhifubao col-xs-12 mode-of-payment" p_mode='Alipay' style="background-color: white" is-chose="0">--}}
            {{--<div class="col-xs-6 zhifubao-icon">--}}
                {{--<img src="{{asset('img/m-zhifubao.svg')}}" height="28" width="28"/>--}}
                {{--<span class="f14 fz">支付宝</span>--}}
            {{--</div>--}}
            {{--<div class="col-xs-6 text-right">--}}
                {{--<img src="{{asset('img/defau.png')}}" height="24" class="child_img"/>--}}

            {{--</div>--}}
        {{--</div>--}}

        <div class="hang weixin col-xs-12 mode-of-payment" p_mode = 'WechatPay' style="background-color: white" is-chose="1">
            <div class="col-xs-6 weixin-icon">
                <img src="{{asset('img/m-weixin.svg')}}" height="28" width="28"/>
                <span class="f14 fz">微信支付</span>
            </div>
            <div class="col-xs-6 text-right">
                <img src="{{asset('img/selected.png')}}" height="24" class="child_img"/>
                {{--<span class="glyphicon glyphicon-chevron-right right-btn fzq"></span>--}}
            </div>
        </div>
    </div>

    <div id="to_pay" style="width: 80%;margin: 275px auto 0;border-radius: 10px;height: 42px;background-color: #e83828;color: white;font-size: 14px;display:flex;justify-content: center;align-items: center;">立即支付</div>

    <script type="text/javascript" src="{{asset('js/common-top.js')}}"></script>
    <script type="text/javascript">

        $(document).ready(function () {
            function onBridgeReady() {
                WeixinJSBridge.invoke(
                        'getBrandWCPayRequest',{!! $wx_json !!}, function (res) {
                            WeixinJSBridge.log(res.err_msg);
                            if (res.err_msg == "get_brand_wcpay_request:ok") {
                                //订单支付成功后，不能跳到详情，跳到订单列表中，为了刷新订单列表，因为从未付款标签中进行支付的订单，
                                //全部标签中的订单还是未付款，所以应跳到订单列表刷新订单列表
                                window.location.href ='/order/index';
                                {{--window.location.href = '{{asset('order/info/'.$plat_order->plat_order_id)}}';--}}
                            } else if (res.err_msg == "get_brand_wcpay_request:cancel") {
                                window.location.href = "{{asset('order/index')}}";
                            } else {
                                window.location.href = "{{asset('order/index')}}";
                            }
                        }
                );
            }

//            $('.weixin').on('click', function () {
//                if (typeof WeixinJSBridge == "undefined") {
//                    if (document.addEventListener) {
//                        document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
//                    } else if (document.attachEvent) {
//                        document.attachEvent('WeixinJSBridgeReady', onBridgeReady);
//                        document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
//                    }
//                } else {
//                    onBridgeReady();
//                }
//            });

//            $('.zhifubao').on('click', function () {
//                alert('支付宝还没写');
//            });


            function Wechat_Pay(){
                if (typeof WeixinJSBridge == "undefined") {
                    if (document.addEventListener) {
                        document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
                    } else if (document.attachEvent) {
                        document.attachEvent('WeixinJSBridgeReady', onBridgeReady);
                        document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
                    }
                } else {
                    onBridgeReady();
                }

            }

            //点击某个支付方式时，进行判断是否是选择,微信支付是默认的支付方式
            $('.mode-of-payment').on('click',function(){
                var isChose = $(this).attr('is-chose');
                //若为1，说明要取消该支付方式
                if(isChose == 1){
                    $(this).attr('is-chose',0);
                    $(this).find(".child_img").attr('src','/img/defau.png');

                }else{
                    //取消原来选中的支付方式
                    $('.mode-of-payment').each(function(){
                        var c = $(this).attr('is-chose');
                        if(c == 1){
                            $(this).attr('is-chose',0);
                            $(this).find(".child_img").attr('src','/img/defau.png');
                        }

                    });
                    //设置选中的支付方式
                    $(this).attr('is-chose',1);
                    $(this).find(".child_img").attr('src','/img/selected.png');
                }

            });


            $("#to_pay").on("click", function () {
                //找到支付方式
                var len = $(".mode-of-payment[is-chose=1]").length;
                if(len == 1){
                    var mode = $(".mode-of-payment[is-chose=1]").attr('p_mode');

                    switch (mode){
//                        case 'Alipay':
//                            alert('还没有支付宝支付！');
//                            break;
                        case "WechatPay":
                            Wechat_Pay();
                            break;
                    }

                }else{
                    alert("请选择一种支付方式");
                    return false;
                }

            });


        });






    </script>
    </body>
@endsection

