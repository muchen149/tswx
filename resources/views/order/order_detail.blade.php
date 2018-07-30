@extends('inheritance')

@section('title')
    订单详情
@endsection

@section('css')
    <link href="{{asset('css/main.css')}}" rel="stylesheet">
    <link href="{{asset('css/member.css')}}" rel="stylesheet">
    <link href="{{asset('css/header.css')}}" rel="stylesheet">
    <link href="{{asset('css/order/order_detail.css')}}" rel="stylesheet">

    <style>
        .line-footer {
            width: 100%;
            height: 40px;
            background-color: white;
            border-bottom: 1px solid rgb(220, 220, 220);
            display: flex;
            justify-content: space-between;
            align-items: center;

        }

        .line-footer p {
            height: 50px;
            line-height: 61px;
            color: rgb(100, 100, 100);
            font-size: 14px;
            margin-left: 3%;
        }

        body {
            background-color: rgb(240, 240, 240);
        }

        .submit-oder {
            width: 100%;
            height: 50px;
            display: flex; /* OLD - Android 4.4- */
            display: -webkit-box; /* OLD - iOS 6-, Safari 3.1-6 */
            display: -moz-box; /* OLD - Firefox 19- (buggy but mostly works) */
            display: -ms-flexbox; /* TWEENER - IE 10 */
            display: -webkit-flex; /* NEW - Chrome */
            display: flex; /* NEW, Spec - Opera 12.1, Firefox 20+ */ /* 09版 */
            -webkit-box-orient: horizontal; /* 12版 */
            -webkit-flex-direction: row;
            -moz-flex-direction: row;
            -ms-flex-direction: row;
            -o-flex-direction: row;
            flex-direction: row;;
            align-items: center;
            position: fixed;
            bottom: 0px;
        }

        .submit-oder-left {
            width: 60%;
            text-align: center;
            background-color: white;
            border-top: 1px solid rgb(220, 220, 220);
            display: flex;
            justify-content: flex-start;
            align-items: center;
        }

        .submit-oder-left p {
            height: 50px;
            line-height: 50px;
            color: rgb(100, 100, 100);
            font-size: 14px;
            margin-left: 5%;

        }

        .submit-oder-right {
            width: 40%;
            height: 50px;
            background-color: #ff5500;
            text-align: center;
        }

        .submit-oder-right a {
            color: white;
            height: 50px;
            line-height: 50px;
            display: block;
        }

        .datail-title {
            width: 100%;
            height: 40px;
            background-color: white;
            display: flex;
            justify-content: flex-start;
            align-items: center;
        }

        .datail-title img {
            width: 30px;
            height: 30px;
            margin-left: 3%;
        }

        .datail-title p {
            height: 40px;
            line-height: 40px;
            color: rgb(120, 120, 120);
            font-size: 14px;
            margin-left: 2%;
        }

        .ship_right {
            float: right;
            margin-left: 3px;
            margin-right: -7px;
            margin-top: 11px;
            display: block;
            background: url("img/sprites.png") no-repeat -180px -49px;
            background-size: 200px 200px;
            width: 16px;
            height: 19px;
        }

        .btn-bar {
            background: #eaedf1 none repeat scroll 0 0;
            border-top: 1px solid #e0e0e0;
            bottom: 0;
            font-size: 14px;
            height: 54px;
            max-width: 640px;
            padding: 0 10px 0 2px;
            position: fixed;
            width: 100%;
            z-index: 10;
        }

        .bottom-but {
            box-sizing: border-box;
            float: right;
            padding-left: 8px;
            width: 28%;
        }

        .bb-btn1-red {
            background: #f15353 none repeat scroll 0 0;
            border: 1px solid #f15353;
            border-radius: 3px;
            box-sizing: border-box;
            color: #fff;
            float: right;
            height: 34px;
            line-height: 34px;
            margin-left: 8px;
            text-align: center;
            width: 100%;
        }

        .bb-btn1-white {
            background: #fff none repeat scroll 0 0;
            border: 1px solid #bfbfbf;
            border-radius: 3px;
            box-sizing: border-box;
            color: #686868;
            float: right;
            height: 34px;
            line-height: 34px;
            margin-left: 8px;
            text-align: center;
            width: 100%;
        }

        .btn-bar .bb-btn1 {
            background: #fff none repeat scroll 0 0;
            border: 1px solid #bfbfbf;
            border-radius: 6px;
            color: #686868;
            font-size: 26px;
            height: 68px;
            line-height: 68px;
            text-align: center;
            transform: scale(0.5, 0.5);
            transform-origin: left top 0;
            width: 200%;
        }

        /*上传图片样式*/
        .xq-lb-new {
            position: fixed;
            bottom: -300px;
            /*bottom: -2px;*/
            left: 0;
            right: 0;
            max-height: 300px;
            background-color: #fff;
            z-index: 10; }

        .title-zeng-new, .input-dan-new, .title-edit-new {
            height: 50px;
            border-bottom: 1px solid #f0f0f0; }
        .title-zeng-new p,.input-dan-new p, .title-edit-new p {
            padding-top: 15px; }
        .title-zeng-new, #submitOrder .title-edit-new {
            position: relative; }

        .close-btn-new {
            position: absolute;
            right: 10px;
            top: 15px;
            cursor: pointer; }

        .addressContent-new {
            max-height: 260px;
            overflow: auto; }

    </style>
@endsection



@section('content')
    <body>
    <div id="header"></div>
    <input id="plat_order_id" type="hidden" value="{{$order_info['plat_order_id']}}"/>
    <div class="order-detail-wp">
        <div class="order-detail" id="order_detail_wp">
            @if($order_info['plat_order_state']==9 || $order_info['plat_order_state']==4)
                <div style="position: absolute;top:46px;right:7px;z-index:9999">
                    <img src="/img/order_succ.png" style="width:75px;margin-top:17px">
                </div>
            @endif
            <div class="line-zhuangtai"><!--订单状态-->
                <div class="left" >
                    <p>订单号: {{$order_info['plat_order_sn']}}</p>
                </div>
                <div class="right">
                    {{--<p class="" style="text-align: right;">--}}

                    @if($order_info['plat_order_state']==1)

                        @if($order_info['pay_mode_id'] == 7 )
                            <span class="ship_right" style="width: 61px;">电子汇款</span>
                        @else
                            <span class="ship_right" style="width: 61px;">待付款</span>
                        @endif

                    @elseif($order_info['plat_order_state']==2)

                        @if($order_info['pay_mode_id'] == 5 )
                            <span class="ship_right" style="width: 61px;">货到付款</span>
                        @elseif($order_info['pay_mode_id'] == 8)
                            <span class="ship_right" style="width: 61px;">账期支付</span>
                        @else
                            <span class="ship_right" style="width: 61px;">已付款</span>
                        @endif

                    @elseif($order_info['plat_order_state']==3)

                        @if($order_info['pay_mode_id'] == 5 )
                            <span class="ship_right" style="width: 61px;">货到付款</span>
                        @elseif($order_info['pay_mode_id'] == 8)
                            <span class="ship_right" style="width: 61px;">账期支付</span>
                        @else
                            <span class="ship_right" style="width: 61px;">待收货</span>
                        @endif


                    @elseif($order_info['plat_order_state']==4)
                        <span class="ship_right" style="width: 61px;">已完成</span>
                    @elseif($order_info['plat_order_state']==9)
                        <span class="ship_right" style="width: 61px;">已完成</span>
                    @elseif($order_info['plat_order_state']==-1)
                        <span class="ship_right" style="width: 61px;">已取消</span>
                    @elseif($order_info['plat_order_state']==-2)
                        <span class="ship_right" style="width: 61px;">已退单</span>
                    @elseif($order_info['plat_order_state']==-5)
                        <span class="ship_right" style="width: 61px;">已退货</span>
                    @elseif($order_info['plat_order_state']==-9)
                        <span class="ship_right" style="width: 61px;">已删除</span>
                    @endif

                    {{--@if ($order_info['state'] == 4 || ($order_info['state'] == 6 && !empty($order_info['tracking_num']) && !empty($order_info['tracking_name']))) { ?>--}}
                    {{--<span class="ship_right"></span>--}}
                    {{--@endif--}}
                    {{--</p>--}}
                </div>
            </div>


            <div class="fenge" style="border-bottom:none;border-bottom: 1px solid rgb(240,240,240);border-top: 1px solid rgb(240,240,240); "></div><!--分割线-->
            @if (!empty($order_address))
                <div class="step2 bdr-new">
                    <div class="m step2-in ">
                        <div class="mt">
                            <div class="s2-name"><i></i>{{$order_address['recipient_name']}}</div>
                            <div class="s2-phone">
                                <i></i>{{$order_address['mobile']}}</div>
                        </div>
                        <div class="mc step2-in-con" style="margin-left: 19px;margin-top:10px;">{{$order_address['address']}}</div>
                    </div>
                    <b class="s2-borderT"></b>
                    <b class="s2-borderB"></b>
                </div>
                <div class="fenge" style="border-top:none;border-bottom: 1px solid rgb(240,240,240);border-top: 1px solid rgb(240,240,240);"></div><!--分割线-->
                @endif

                {{--<div class="datail-title">--}}
                {{--@if ($order_info['from_paltform'] == 'jd')--}}
                {{--<img src="img/title_$order_info['from_paltform']-1.png" alt="">--}}
                {{--<p>京东商城</p>--}}
                {{--@elseif ($order_info['from_paltform'] == 'sf')--}}
                {{--<img src="img/title_$order_info['from_paltform']-1.png" alt="">--}}
                {{--<p>顺丰优选</p>--}}
                {{--@else--}}
                {{--<img src="../$order_info['logo']" alt="" style="width:30px; line-height: 30px">--}}
                {{--<p>易远达公司</p>--}}
                {{--@endif--}}
                {{--</div>--}}



                        <!--商品信息-->
                @foreach($order_info['skus'] as $goods)

                    <div class="line-shangpin">
                        <div class="left">
                            <img style="width:90%" src="{{asset($goods['sku_image'])}}" />
                        </div>
                        <div class="right">
                            <p style="margin: 0 0 0px;">{{$goods['sku_name']}}</p>
                            <p style="height:8px;color: #8e8e8e;font-size: 12px;">
                                规格： @foreach($goods['sku_spec'] as $spec)<span>{{$spec}}&nbsp;</span>@endforeach
                            </p>
                            <p>
                                ¥{{$goods['settlement_price']}} &nbsp;&times;&nbsp;{{$goods['number']}}
                            </p>
                        </div>
                    </div>
                @endforeach

                <div class="line-wrap">
                    <div class="line-total" style="padding-top: 13px;height: 41px;">
                        <p>商品总金额</p>
                        <p>￥ {{$order_info['goods_amount_totals']}}</p>
                    </div>
                </div>
                <div class="fenge" style=";border-bottom: 1px solid rgb(240,240,240);border-top: 1px solid rgb(240,240,240);"></div><!--分割线-->
                @if($message!=null)
                    <div class="line-footer" style="height: 80px;">
                        <div style="width: 100%;margin-left: 3%">买家留言:<br><span style="margin-left: 7%;font-size:12px;">{{$message}}</span></div>
                        {{--<div style="width: 100px;"></div>--}}
                    </div>
                @endif
                <div class="line-footer">
                    <p>运费金额：</p>
                    <span style="color: #ff5500;margin-right: 3%">¥ {{$order_info['fare_amount']}}</span>
                </div>

                @if(isset($order_info['payment']['pay_vrb']))
                    <div class="line-footer">
                        <p>使用{{$plat_vrb_name}}：</p>
                        <span style="color: #ff5500;margin-right: 3%">{{$order_info['payment']['pay_vrb']['pay_amount']}}
                            (抵现：¥{{$order_info['payment']['pay_vrb']['pay_amount_to_rmb']}})
                            </span>
                    </div>
                @endif

                @if(isset($order_info['payment']['pay_wallet']))
                    <div class="line-footer">
                        <p>使用零钱：</p>
                        <span style="color: #ff5500;margin-right: 3%">¥ {{$order_info['payment']['pay_wallet']['pay_amount']}}</span>
                    </div>
                @endif

                @if(isset($order_info['payment']['pay_card_balance']))
                    <div class="line-footer">
                        <p>使用卡余额：</p>
                        <span style="color: #ff5500;margin-right: 3%">¥ {{$order_info['payment']['pay_card_balance']['pay_amount']}}</span>
                    </div>
                @endif
                @if($order_info['plat_order_state']==1)
                    <div class="line-footer">
                        <p>待付总金额：</p>
                        <span style="color: #ff5500;margin-right: 3%">¥ {{$order_info['pay_rmb_amount']}}</span>
                    </div>
                @endif

                <div class="fenge" style="border-top:none;border-bottom: 1px solid rgb(240,240,240);border-top: 1px solid rgb(240,240,240);"></div><!--分割线-->


                {{--@if($order_info['plat_order_state']==1)--}}
                {{--<div class="" style="background-color:white;border-bottom: 1px solid rgb(220, 220, 220);height: 41px;margin-bottom:120px;">--}}
                {{--<span style="width: 100%;font-size: 12px;padding-top: 20px;margin-left:10px;line-height:40px;">创建时间：{{date( "Y-m-d H:i:s", $order_info['create_time'])}}</span><br>--}}
                {{--</div>--}}
                {{--@elseif($order_info['plat_order_state']==2)--}}
                {{--<div class="" style="background-color:white;border-bottom: 1px solid rgb(220, 220, 220);height: 62px;margin-bottom:120px;padding-top:4px;">--}}
                {{--<span style="width: 100%;font-size: 12px;margin-left:10px;">创建时间：{{date( "Y-m-d H:i:s", $order_info['create_time'])}}</span><br>--}}
                {{--@if($order_info['pay_rmb_sn'])--}}
                {{--<span style="width: 100%;font-size: 12px;margin-left:10px;">支付交易号：{{$order_info['pay_rmb_sn']}}</span><br>--}}
                {{--@endif--}}
                {{--<span style="width: 100%;font-size: 12px;margin-left:10px;">付款时间：{{date( "Y-m-d H:i:s",$order_info['pay_rmb_time'])}}</span><br>--}}
                {{--</div>--}}
                {{--@elseif($order_info['plat_order_state']==3)--}}
                {{--<div class="" style="background-color:white;border-bottom: 1px solid rgb(220, 220, 220);height: 82px;margin-bottom:120px;padding-top:4px;">--}}
                {{--<span style="width: 100%;font-size: 12px;margin-left:10px;">创建时间：{{date( "Y-m-d H:i:s", $order_info['create_time'])}}</span><br>--}}
                {{--@if($order_info['pay_rmb_sn'])--}}
                {{--<span style="width: 100%;font-size: 12px;margin-left:10px;">支付交易号：{{$order_info['pay_rmb_sn']}}</span><br>--}}
                {{--@endif--}}
                {{--<span style="width: 100%;font-size: 12px;margin-left:10px;">付款时间：{{date( "Y-m-d H:i:s",$order_info['pay_rmb_time'])}}</span><br>--}}
                {{--@if($order_info['transport_time'])--}}
                {{--<span style="width: 100%;font-size: 12px;margin-left:10px;">发货时间：{{date( "Y-m-d H:i:s",$order_info['transport_time'])}}</span><br>--}}
                {{--@endif--}}
                {{--</div>--}}
                {{--@elseif($order_info['plat_order_state']==4 || $order_info['plat_order_state']==9)--}}
                {{--<div class="" style="background-color:white;border-bottom: 1px solid rgb(220, 220, 220);height: 97px;margin-bottom:120px;padding-top:4px;">--}}
                {{--<span style="width: 100%;font-size: 12px;margin-left:10px;">创建时间：{{date( "Y-m-d H:i:s", $order_info['create_time'])}}</span><br>--}}
                {{--@if($order_info['pay_rmb_sn'])--}}
                {{--<span style="width: 100%;font-size: 12px;margin-left:10px;">支付交易号：{{$order_info['pay_rmb_sn']}}</span><br>--}}
                {{--@endif--}}
                {{--@if($order_info['pay_rmb_time'])--}}
                {{--<span style="width: 100%;font-size: 12px;margin-left:10px;">付款时间：{{date( "Y-m-d H:i:s",$order_info['pay_rmb_time'])}}</span><br>--}}
                {{--@endif--}}
                {{--@if($order_info['transport_time'])--}}
                {{--<span style="width: 100%;font-size: 12px;margin-left:10px;">发货时间：{{date( "Y-m-d H:i:s",$order_info['transport_time'])}}</span><br>--}}
                {{--@endif--}}
                {{--@if($order_info['arrival_time'])--}}
                {{--<span style="width: 100%;font-size: 12px;margin-left:10px;">成交时间：{{date( "Y-m-d H:i:s",$order_info['arrival_time'])}}</span><br>--}}
                {{--@endif--}}
                {{--</div>--}}
                {{--@endif--}}


                {{--若订单为账期支付，若订单还未完成，这显示订单支付剩余时间，--}}
                @if($order_info['pay_mode_id'] == 8 && $order_info['plat_order_state'] >=1 && $order_info['plat_order_state'] < 9)
                    @if($order_info['end_time_day'] > 0)
                        <div class="line-footer">
                            <p>订单支付剩余时间：&nbsp;</p>
                            <span style="margin-right: 3%">&nbsp;<span style="color: #ff5500;margin-right: 10px;">{{$order_info['end_time_day']}}</span>天</span>
                        </div>
                    @else
                        <div class="line-footer">
                            <p style="color: #ff5500;">您的账期支付时间已到，请及时支付订单！</p>
                        </div>
                    @endif

                @endif

                <div class="submit-oder" style="border-top:1px solid rgb(220,220,220);background-color: rgba(255,255,255,.8);;display: flex;justify-content: flex-end;">

                    @if($order_info['plat_order_state']==1)
                        {{--若订单为电子支付，则显示该订单的银行支付凭证--}}
                        @if($order_info['pay_mode_id'] == 7)
                            <div class="bottom-but " style="margin-right: 2%;">
                                <a class="bb-btn1-red show_shipping pingzheng" order_id="{{$order_info['plat_order_id']}}"
                                   style="font-size:14px; width: 97px;">上传支付凭证</a>
                            </div>
                        @else
                            <div class="bottom-but " style="margin-right: 2%;">
                                <a class="bb-btn1-red show_shipping" id="go-to-pay" order_id="{{$order_info['plat_order_id']}}"
                                   style="font-size:14px;">去付款</a>
                            </div>
                        @endif

                        {{--<div class="bottom-but " style=" margin-right: 3%">
                            <a class="bb-btn1-white cancel-order" order_id="{{$order_info['plat_order_id']}}"
                               style="font-size:14px;">取消订单</a>
                        </div>--}}
                    @elseif($order_info['plat_order_state']==2)

                        @if($order_info['pay_mode_id'] == 5 || $order_info['pay_mode_id'] == 8)
                            <div class="bottom-but " style="margin-right: 2%;">
                                <a class="bb-btn1-red show_shipping" id="go-to-pay" order_id="{{$order_info['plat_order_id']}}"
                                   style="font-size:14px;">去付款</a>
                            </div>

                        @endif


                        {{--<div class="bottom-but " style=" margin-right: 3%">--}}
                        {{--<a class="bb-btn1-white refund-order" order_id="{{$order_info['plat_order_id']}}"--}}
                        {{--style="font-size:14px;">退单</a>--}}
                        {{--</div>--}}
                    @elseif($order_info['plat_order_state'] == 3)
                        @if($order_info['pay_mode_id'] == 5 || $order_info['pay_mode_id'] == 8)
                            <div class="bottom-but " style="margin-right: 2%;">
                                <a class="bb-btn1-red show_shipping" id="go-to-pay" order_id="{{$order_info['plat_order_id']}}"
                                   style="font-size:14px;">去付款</a>
                            </div>
                        @else
                            <div class="bottom-but " style="margin-right: 2%; ">
                                <a class="bb-btn1-white show_shipping"  href="{{url('/order/logistics/'.$order_info['plat_order_id'])}}"
                                   style="font-size:14px;color: rgb(50, 142, 200)">查看物流</a>
                            </div>

                            <div class="bottom-but " style="margin-right: 3%">
                                <a class="bb-btn1-red sure_order" order_id="{{$order_info['plat_order_id']}}" style="font-size:14px;">确认收货</a>
                            </div>
                        @endif

                    @elseif($order_info['plat_order_state']==4 || $order_info['plat_order_state']== 9)

                        @if($order_info['plat_order_state']== 4 && $order_info['pay_mode_id'] == 8)
                            <div class="bottom-but " style="margin-right: 2%;">
                                <a class="bb-btn1-red show_shipping" id="go-to-pay" order_id="{{$order_info['plat_order_id']}}"
                                   style="font-size:14px;">去付款</a>
                            </div>
                        @else
                            <div class="bottom-but " style="">
                                <a class="bb-btn1-white show_shipping"  href="{{url('/order/logistics/'.$order_info['plat_order_id'])}}"
                                   style="font-size:14px;margin-right: 18%;color: rgb(50, 142, 200)">查看物流</a>
                            </div>
                        @endif

                    @endif

                </div>



                {{--@if (!empty($order_info['finished_time']) && $order_info['state'] == 6)--}}
                {{--<div class="line-footer" style="margin-bottom:60px;">--}}
                {{--<p>完成时间：</p>--}}
                {{--<span style="margin-right: 3%">--}}
                {{--2017-8-9--}}
                {{--</span>--}}
                {{--</div>--}}
                {{--@endif--}}

                {{--@if ($order_info['state'] == 1)--}}
                {{--<div class="submit-oder">--}}
                {{--<div class="submit-oder-left">--}}
                {{--<p>应付金额：</p>--}}
                {{--<span id="total_price"--}}
                {{--amount_total="524">￥965</span>--}}
                {{--</div>--}}
                {{--<div class="submit-oder-right">--}}
                {{--<a href="../index.php?c=weixin&a=wechat_pay_order&order_id=123456">立即支付</a>--}}
                {{--</div>--}}
                {{--</div>--}}
                {{--@endif--}}


                {{--@if (0 < $order_info['state'] && $order_info['state'] < 4 && empty($order_info['package_id']))--}}

                {{--@if ($order_info['state'] == 2 || $order_info['state'] == 3)--}}

                {{--<div class="submit-oder"--}}
                {{--style="border-top:1px solid rgb(220,220,220); background-color: rgba(255,255,255,.8);display: flex;justify-content: flex-end;">--}}
                {{--@if (!$no_cancel)--}}
                {{--<div class="bottom-but" style="">--}}
                {{--<a class="bb-btn1-white cancel-order" order_id="5412"--}}
                {{--style="font-size:14px;">取消订单</a>--}}
                {{--</div>--}}
                {{--@endif--}}
                {{--<div class="bottom-but" style="margin-right: 2%">--}}
                {{--<a href="wx_order_qrcode.php?orderid=12541" class="bb-btn1-red"--}}
                {{--style="font-size:14px;">我要自提</a>--}}
                {{--</div>--}}
                {{--</div>--}}
                {{--@endif--}}


                {{--@elseif ($order_info['state'] >= 6)--}}
                {{--<div class="submit-oder"--}}
                {{--style="border-top:1px solid rgb(220,220,220);background-color: rgba(255,255,255,.8);;display: flex;justify-content: flex-end;">--}}
                {{--@if (!empty($order_info['tracking_num']) && !empty($order_info['tracking_name']))--}}
                {{--<div class="bottom-but " style="">--}}
                {{--<a class="bb-btn1-white show_shipping" order_id="1221"--}}
                {{--style="font-size:14px;">查看物流</a>--}}
                {{--</div>--}}
                {{--@endif--}}
                {{--<div class="bottom-but " style="margin-right: 2%">--}}
                {{--<a class="bb-btn1-white del-order" order_id="11111" style="font-size:14px;">删除订单</a>--}}
                {{--</div>--}}
                {{--</div>--}}
                {{--@elseif ($order_info['state'] == 4)--}}
                {{--<div class="submit-oder"--}}
                {{--style="border-top:1px solid rgb(220,220,220); background-color: rgba(255,255,255,.8);display: flex;justify-content: flex-end;">--}}
                {{--@if(!empty($order_info['tracking_num']) && !empty($order_info['tracking_name']))--}}
                {{--<div class="bottom-but " style="">--}}
                {{--<a class="bb-btn1-white show_shipping" order_id="123456"--}}
                {{--style="font-size:14px;">查看物流</a>--}}
                {{--</div>--}}
                {{--@endif--}}
                {{--<div class="bottom-but " style="margin-right: 2%">--}}
                {{--<a class="bb-btn1-red sure_order" order_id="123456" style="font-size:14px;">确认收货</a>--}}
                {{--</div>--}}
                {{--</div>--}}
                {{--@endif--}}
        </div>
    </div>


    <div class="xq-lb-new" id="pingzheng-show" style="background-color: rgb(240,240,240); width: 100%;">
        <div class="title-zeng-new" style="background-color: white">
            <p class="text-center">上传支付凭证</p>
            <div class="close-btn-new " id="ka_yu_e_close">
                <img src="{{asset('img/m-close.svg')}}" height="20" width="20"/>
            </div>
        </div>



        <div style="background-color: rgb(240,240,240); display: flex;justify-content: flex-start;align-items: center" id="img-wrapper">
            @if($order_info['pay_cert'])
                <div class="append" data-img="{{$order_info['pay_cert']}}">
                    <img src="{{$order_info['pay_cert']}}" alt="" width="60px">
                </div>

            @endif

            <div class="upload-btn-box" @if($order_info['pay_cert']) hidden @else show @endif>
                <input id="pageUpload" name="uploadPic" order_id="{{$order_info['plat_order_id']}}" class="upload-btn" type="file" value="defewqfewafewf">
            </div>

            <script type="text/javascript" src="{{asset('js/ajaxfileupload.js')}}"></script>

        </div>
        {{--<div style="position:relative;bottom: -30px;;background-color: white;height: 46px;">--}}
        {{--<div style="--}}
        {{--position: absolute;--}}
        {{--top: 4px;--}}
        {{--left: 7%;--}}
        {{--width: 86%;--}}
        {{--height: 38px;--}}
        {{--display: flex;--}}
        {{--justify-content: center;--}}
        {{--align-items: center;--}}
        {{--border-radius: 8px;--}}
        {{--color: white;--}}
        {{--background-color: #f15353;--}}
        {{--">--}}
        {{--提交--}}
        {{--</div>--}}

        {{--</div>--}}



        <div>
            <p style="padding-top: 21px;"></p>
        </div>
    </div>

    <!--*********************卡余额  start!*********************-->
    <!------------------------阴影效果  START!---------------------------->
    <div class="pingzheng-xiaoguo">
    </div>
    <!--======================阴影效果  END!============================-->

    </body>
@endsection

@section('js')
    <script type="text/javascript" src="{{asset('js/zepto.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/common-top.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/template.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/common.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/simple-plugin.js')}}"></script>

    <script>
        var uploadImgUrl = '{{asset('order/uploadImg')}}';
        var delImgUrl = '{{asset('order/delImg')}}';
        var scriptUrl = '{{asset('js/order/order_cert.js')}}';

        $(document).ready(function () {
            //去付款
            $('#go-to-pay').on('click', function () {
                var order_id = $(this).attr('order_id');
                window.location.href = '/wx/pay/wxPay?plat_order_id=' + order_id;
            });

            //取消订单
            $('.cancel-order').on('click',function () {
                var order_id = $(this).attr('order_id');
                var cancel = $(this);
                if(confirm("确定取消订单吗？")){
                    $.get('/order/cancel/'+ order_id,function (data) {
                        if (data['code'] == 0) {
                            message('取消成功！');
                            location.href ='/order/info/'+order_id;
                        } else {
                            message('取消订单失败，请稍后重试！');
                        }
                    })
                }

            });

            //点击上传凭证
            $('.pingzheng').on('click', function () {

                $('#pingzheng-show').css('bottom', 0).css('transition', 'all 0.5s');
                $('.pingzheng-xiaoguo').addClass('yinying');
            });


            //点击关闭按钮
            $('#ka_yu_e_close').on('click', function () {

                $('#pingzheng-show').css('bottom', '-300px').css('transition', 'all 0.5s');
                $('.pingzheng-xiaoguo').removeClass('yinying');
            });

        });

    </script>
    <script type="text/javascript" src="{{asset('js/order/order_cert.js')}}"></script>
@endsection
