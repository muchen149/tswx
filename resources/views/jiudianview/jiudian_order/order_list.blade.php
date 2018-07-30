@extends('inheritance')

@section('title')
    订单列表
@endsection

@section('css')
    <link href="{{asset('css/reset.css')}}" rel="stylesheet">
    <link href="{{asset('css/main.css')}}" rel="stylesheet">
    <link href="{{asset('css/member.css')}}" rel="stylesheet">

    <link href="{{asset('css/after-sales.css')}}" rel="stylesheet">

    <style>
        .order-shop-pd {
            border-bottom: 2px solid rgb(255, 255, 255);
            background-color: rgb(240, 240, 240);
        }

        .order-total {
            width: 95%;
            margin: 0 auto;
            height: 40px;
            background-color: white;
            border-bottom: 1px solid rgb(220, 220, 220);
        }
        .order-total_wrap{
            background-color: white;
        }

        .order-total p {
            height: 40px;
            line-height: 40px;
            font-size: 13px;
            color: #000;
            text-align: left;
            margin-left: 3%;
        }

        .order-status {
            width: 100%;
            height: 50px;
            background-color: white;
            border-bottom: 1px solid rgb(220, 220, 220);
            display: flex;
            justify-content: flex-end;
        }

        .order-status .cancel-order,.refund-order, .order-status .del-order,.order-status .tuikuan_order,.order-status .redpack {
            background-color: white;
            border: 1px solid rgb(200, 200, 200);
            border-radius: 6px;
            width: 90px;
            height: 32px;
            margin-right: 5%;
            margin-top: 9px;
            color: rgb(100, 100, 100);
            font-size: 13px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .order-status .show_shipping {
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

        .order-status .sure_order {
            background-color: white;
            border: 1px solid #f15353;
            border-radius: 6px;
            width: 90px;
            height: 32px;
            margin-right: 5%;
            margin-top: 9px;
            color: #f15353;
            font-size: 13px;
            display: flex;
            justify-content: center;
            align-items: center;

        }

        .order-status .pay-order {
            background-color: white;
            border: 1px solid #f15353;
            border-radius: 6px;
            width: 90px;
            height: 32px;
            margin-right: 5%;
            margin-top: 9px;
            color: #f15353;
            font-size: 13px;
            display: flex;
            justify-content: center;
            align-items: center;

        }
        .show_shipping {
            width: 88px;
            height: 32px;
            border: 1px solid rgb(220,220,220);
            border-radius: 6px;
            margin-left: 3%;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        /*等待确认*/
        .wait-server {
            background-color: white;
            border: 1px solid #f15353;
            border-radius: 6px;
            width: 90px;
            height: 32px;
            margin-right: 5%;
            margin-top: 9px;
            color: #f15353;
            font-size: 13px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
@endsection


@section('content')
<body>
    <div class="nav-order">
        <div class="nav-title" style="font-family: 微软雅黑;">
            <div class="nav-content border-right @if(empty($state)) current @endif" state="0"><a href="/jd/order/index">全部</a></div>
            <div class="nav-content border-right @if($state == '1') current @endif" state="1"><a href="/jd/order/index/1">待付款</a></div>
            <div class="nav-content border-right @if($state == '2') current @endif" state="2"><a href="/jd/order/index/2">待确认</a></div>
            <div class="nav-content border-right @if($state == '3') current @endif" state="3"><a href="/jd/order/index/3">待入住</a></div>
            <div class="nav-content border-right @if($state == '9') current @endif" state="9"><a href="/jd/order/index/9">已完成</a></div>
            {{--<div class="nav-content border-right @if($state == '4') current @endif" state="4"><a >待评价</a></div>--}}

        </div>
    </div>
    <div style="width: 100%;height: 10px;border-bottom:0px;border-top: 0px;background-color: #f3f4f6">
    </div>


    <div class="order-list-wp" >
        @if($orders_info== null )
            <div style="margin:0 auto;height:200px;width:200px;margin-top:40px;text-align: center">
                <img style="width:80%" src="/img/empty_quan.png">
                <p style="color:#323232;font-size:14px;margin-top:14px;">暂无相应订单！</p>
            </div>
        @else
            <ul>
                @foreach($orders_info as $order_info)
                    <li>
                        <div class="order-ltlt">
                            <div class="order-lcnt-left">
                                <p style="margin-left:0;height: 40px;line-height: 40px;">订单号： {{$order_info['plat_order_sn']}}</p>
                            </div>
                            <div class="order-lcnt-right">
                                <p>
                                    @if($order_info['plat_order_state']==1)
                                        @if($order_info['pay_mode_id'] == 7 )
                                            <span>电子汇款</span>
                                        @else
                                            <span style="color: #f15353;">待付款</span>
                                        @endif

                                    @elseif($order_info['plat_order_state']==2)
                                        {{--若订单支付方式为货到付款或账期支付则显示相应的支付方式--}}
                                        @if($order_info['pay_mode_id'] == 5 )
                                            <span>货到付款</span>
                                        @elseif($order_info['pay_mode_id'] == 8)
                                            <span>账期支付</span>
                                        @else
                                            <span style="color: #f15353;">已付款</span>
                                        @endif

                                    @elseif($order_info['plat_order_state']==3)

                                        @if($order_info['pay_mode_id'] == 5 )
                                            <span>货到付款</span>
                                        @elseif($order_info['pay_mode_id'] == 8)
                                            <span>账期支付</span>
                                        @else
                                            <span style="color: #f15353;">待确认</span>
                                        @endif

                                    @elseif($order_info['plat_order_state']==4)
                                        <span style="color: #f15353;">待评价</span>
                                        {{--<span>已完成</span>--}}
                                        {{--<img src="/img/order_succ.png" style="width:75px;position: absolute;top:17px;right: 2px;"/>--}}
                                    @elseif($order_info['plat_order_state']==5)
                                        <span style="color: #f15353;">售后处理中</span>
                                    @elseif($order_info['plat_order_state']==9)
                                        <span style="color: #f15353;">已完成</span>
                                        <img src="/img/order_succ.png" style="width:75px;margin-top:-5px;position: absolute;right: 13px;"/>
                                    @elseif($order_info['plat_order_state']==-1)
                                        <span style="color: #f15353;">已取消</span>
                                    @elseif($order_info['plat_order_state']==-2)
                                        <span style="color: #f15353;">已退单</span>
                                    @elseif($order_info['plat_order_state']==-5)
                                        <span style="color: #f15353;">已退货</span>
                                    @elseif($order_info['plat_order_state']==-9)
                                        <span style="color: #f15353;">已删除</span>
                                    @endif


                                </p>
                            </div>
                        </div>

                        <div>
                            <li>
                                <span>{{$order_info['enterdate']}}入住-{{$order_info['leavedate']}}离店</span>
                                </li>
                        </div>


                        <div class="order-lcnt">

                            {{--商品信息--}}
                            @foreach($order_info['skus'] as $goods)
                                <div class="order-shop-pd">
                                    <a href="{{asset('jd/order/info/'.$order_info['plat_order_id'])}}" class="order-ldetail clearfix ">
                                               <span class="order-pdpic">
                                                            <img src="{{asset($goods['sku_image'])}}">
                                               </span>
                                        <div class="order-pdinfor" style="display: inline-block;margin-left: 3%;width: 60%;">
                                            <p>{{$goods['sku_name']}}</p>
                                            <div style="margin-top: 10%;">
                                                规格： @foreach($goods['sku_spec'] as $spec)<span>{{$spec}}&nbsp;</span>@endforeach
                                            </div>
                                            {{--<p style="color: #f15353">
                                                ¥&nbsp;{{$goods['settlement_price']}}
                                                &nbsp;&times;&nbsp;{{$goods['number']}}
                                            </p>--}}
                                        </div>
                                        <div style="color: #f15353;display: inline-block;float: right;margin-right:6%;">
                                            <p>
                                                ¥&nbsp;{{$goods['settlement_price']}}

                                            </p>
                                            <div style="margin-top: 50%;margin-left:50%;">
                                                &times;&nbsp;{{$goods['number']}}
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                        <div class="order-total_wrap">
                            <div class="order-total">
                                {{--<p>应付：¥&nbsp;{{$order_info['payable_amount']}} <span class="order" style="font-size: 12px;">&nbsp;(含运费&nbsp;¥&nbsp;{{$order_info['fare_amount']}})</span></p>--}}
                                <p>总计：<span style="color: #f15353;">¥&nbsp;{{$order_info['payable_amount']}}</span> <span class="order" style="font-size: 12px;">&nbsp;(含运费&nbsp;¥&nbsp;{{$order_info['fare_amount']}})</span></p>
                            </div>
                        </div>

                        {{--<div class="order-total_wrap">
                            <div class="order-total">
                                <p>已付：¥&nbsp;{{$order_info['yifu_total']}}</p>
                            </div>
                        </div>
                        @if($order_info['plat_order_state']==1)
                            <div class="order-total_wrap">
                                <div class="order-total">
                                    <p>待付：<span style="color: #f15353">¥&nbsp;{{$order_info['pay_rmb_amount']}}</span></p>
                                </div>
                            </div>
                        @endif--}}

                        {{--若订单为账期支付，若订单还未完成，这显示订单支付剩余时间，--}}
                        @if($order_info['pay_mode_id'] == 8 && $order_info['plat_order_state'] >=1 && $order_info['plat_order_state'] < 9)
                           @if($order_info['end_time_day'] > 0)
                                <div class="order-total_wrap">
                                    <div class="order-total" id="show-time">
                                        <p>订单支付剩余时间：&nbsp;<span style="color: #f15353">{{$order_info['end_time_day']}}</span>&nbsp;天</p>
                                    </div>
                                </div>
                           @else
                                <div class="order-total_wrap">
                                    <div class="order-total" id="time-out">
                                        <p style="color:#f15353">您的账期支付时间已到，请及时支付订单！</p>
                                    </div>
                                </div>
                           @endif

                        @endif


                        <div class="order-status">
                            @if($order_info['plat_order_state']==1)
                                {{--若订单是电子付款，则需用户上传该订单的银行支付凭证--}}
                                @if($order_info['pay_mode_id'] == 7)
                                    {{--<div class="pay-order" style="width: 105px;">上传支付凭证</div>--}}
                                    <input type="hidden" value="{{$order_info['plat_order_id']}}"/>
                                    <div class="cancel-order">取消订单</div>
                                @else
                                    <div class="pay-order go-to-pay">去付款</div>
                                    <input type="hidden" value="{{$order_info['plat_order_id']}}"/>
                                    <div class="cancel-order">取消订单</div>
                                @endif

                                {{--<span class="refund-order" order="{{$order_info['plat_order_id']}}">退单</span>--}}

                            @elseif($order_info['plat_order_state']==2)
                                @if($order_info['pay_mode_id'] == 5 || $order_info['pay_mode_id'] == 8)
                                    <div class="pay-order go-to-pay">去付款</div>
                                    <input type="hidden" value="{{$order_info['plat_order_id']}}"/>
                                    {{--<div class="cancel-order">取消订单</div>--}}
                                @endif
                                    {{--<div class="wait-server">等待确认</div>--}}
                                {{--<span  class="refund-order" order="{{$order_info['plat_order_id']}}">退单</span>--}}
                            @elseif($order_info['plat_order_state']==3)

                                @if($order_info['pay_mode_id'] == 5 || $order_info['pay_mode_id'] == 8)
                                    <div class="pay-order go-to-pay">去付款</div>
                                    <input type="hidden" value="{{$order_info['plat_order_id']}}"/>
                                @else
                                    <span class="sure_order" order="{{$order_info['plat_order_id']}}">确认入住</span>
                                @endif

                            @elseif($order_info['plat_order_state']==4)

                                @if($order_info['pay_mode_id'] == 8)
                                    <div class="pay-order go-to-pay">去付款</div>
                                    <input type="hidden" value="{{$order_info['plat_order_id']}}"/>

                                @else
                                    <span class="del-order" order="{{$order_info['plat_order_id']}}">删除订单</span>
                                @endif


                            @elseif($order_info['plat_order_state']==9)
                                <span class="del-order" order="{{$order_info['plat_order_id']}}">删除订单</span>
                            @endif

                            @if($order_info['plat_order_state'] >= 3)
                                {{--<span class="show_shipping" order="{{$order_info['plat_order_id']}}">查看物流</span>--}}
                            @endif
                        </div>

                        <div style="background-color:#f3f4f6;border-bottom:1px;border-top: 1px;height: 10px;"></div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <div id="pop-confirm" class="pop pop-ctm-01" style="display: none; position: fixed; top: 30%;left: 15%;">
        <div class="pop-msg">
            <div id="confirm-content" class="pop-msg-cnt" style="padding-top:18px;min-height: 50px;font-size: 15px;">
            </div>
            <div class="pop-msg-btm">
                <a id="confirm-ok-btn" class="btn-h4 btn-c3" style="width: 96px;border: 1px solid #fe6666;"
                   href="javascript:void(0);">确定</a>
                <a id="confirm-close-btn" class="btn-h4 btn-c3"
                   style="width: 96px;border: 1px solid #cfcfcf;color: #737373;background: #fff"
                   href="javascript:void(0);">取消</a>
            </div>
        </div>
    </div>
    <div class="__MASK_SID_DIV" style="display:none;width:100%;position: absolute; z-index: 999; left: 0px; top: 0px;">
        <div id="set_height" style="width:100%; height: 1024px; opacity: 0.7; background: black none repeat scroll 0% 0%; position: static; margin: 0px;"></div>
    </div>
</body>
@endsection

@section('js')

    <script type="text/javascript" src="{{asset('js/zepto.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/common-top.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/template.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/common.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/simple-plugin.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/order/order_list.js')}}"></script>

@endsection
