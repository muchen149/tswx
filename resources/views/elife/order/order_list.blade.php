@extends('inheritance')

@section('title')
    订单列表
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

        .mui-segmented-control-primary{
            position: fixed;
            top: 0;
            padding: 0 4px;
            z-index: 999999;
        }
        /*改写mui顶部标签切换组件的选中样式*/
        .mui-control-item.mui-active{
            color: #e42f46!important;
            border-bottom: 2px solid #e42f46!important;
        }
        .mui-control-content{
            margin-top: 50px;
        }
        .order-list{
            width: 100%;
            margin-bottom: 10px;
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
            width: 80%;
        }
        .order-list-content li:nth-of-type(1) img{
            width: 80px;
        }
        .order-list-count{
            width: 100%;
            border-bottom: 1px solid rgb(230,230,230);
        }
        .order-list-count ul{
            height: 40px;
            margin-right: 3%;
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
            color: #000000;
        }
        .order-list-optionBar .pay{
            border: 1px solid #e42f46;
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

    </style>
@endsection

@section('content')
    <body>
    @if($state!=0)
    <div class="mui-segmented-control mui-segmented-control-inverted mui-segmented-control-primary b-white">
        <a class="mui-control-item  font-12 " href="{{asset('/elife/personal/index')}}"><img src="{{asset('sd_img/next.png')}}" alt="" style="transform:rotate(180deg);width:22px;margin-left:-50px;"></a>
        <a class="mui-control-item font-14 color-80 @if($state == '1') mui-active @endif" href="{{asset('/elife/order/index/1')}}">待付款</a>
        <a class="mui-control-item font-14 color-80 @if($state == '2') mui-active @endif" href="{{asset('/elife/order/index/2')}}">待发货</a>
        <a class="mui-control-item font-14 color-80 @if($state == '3') mui-active @endif" href="{{asset('/elife/order/index/3')}}">待收货</a>
        <a class="mui-control-item font-14 color-80 @if($state == '9') mui-active @endif" href="{{asset('/elife/order/index/9')}}">已完成</a>
    </div>
    @else
    <div class="head">
        <div class="nav b-white">
            <a class="return font-14 color-80" href="{{asset('elife/personal/index')}}"><img src="{{asset('sd_img/next.png')}}" alt="" class="size-26" style="transform:rotate(180deg);"></a>
            <a class="title font-18 color-80" href="#">订单详情</a>
        </div>
    </div>
    @endif
    <div  class="mui-control-content mui-active">


        @if($orders_info== null )
            <div style="margin:0 auto;height:200px;width:200px;margin-top:40px;text-align: center">
                <img style="width:80%" src="/sd_img/dingdan.jpg">
                <p style="color:#bcbcbc;font-size:14px;margin-top:14px;">暂无相应订单！</p>
            </div>
        @else
            @foreach($orders_info as $order_info)
                <div class="order-list">
                    <div class="order-list-title b-white flex-between">
                        <li class="font-12 color-80">订单号： {{$order_info['plat_order_sn']}}</li>
                        <li class="font-14 color-e42f46">
                            @if($order_info['plat_order_state']==1)
                                @if($order_info['pay_mode_id'] == 7 )
                                    <span>电子汇款</span>
                                @else
                                    <span>待付款</span>
                                @endif

                            @elseif($order_info['plat_order_state']==2)
                                {{--若订单支付方式为货到付款或账期支付则显示相应的支付方式--}}
                                @if($order_info['pay_mode_id'] == 5 )
                                    <span>货到付款</span>
                                @elseif($order_info['pay_mode_id'] == 8)
                                    <span>账期支付</span>
                                @else
                                    <span>已付款</span>
                                @endif

                            @elseif($order_info['plat_order_state']==3)

                                @if($order_info['pay_mode_id'] == 5 )
                                    <span>货到付款</span>
                                @elseif($order_info['pay_mode_id'] == 8)
                                    <span>账期支付</span>
                                @else
                                    <span>待收货</span>
                                @endif

                            @elseif($order_info['plat_order_state']==4)
                                <span>待评价</span>
                                {{--<span>已完成</span>--}}
                                {{--<img src="/img/order_succ.png" style="width:75px;position: absolute;top:17px;right: 2px;"/>--}}
                            @elseif($order_info['plat_order_state']==5)
                                <span>售后处理中</span>
                            @elseif($order_info['plat_order_state']==9)
                                <span>已完成</span>
                                {{--<img src="/sd_img/order-success.png" style="width:75px;margin-top:0px;position: absolute;right: 40px;"/>--}}
                            @elseif($order_info['plat_order_state']==-1)
                                <span>已取消</span>
                            @elseif($order_info['plat_order_state']==-2)
                                <span>已退款</span>
							@elseif($order_info['plat_order_state']==-3)
                                <span>退单中</span>
                            @elseif($order_info['plat_order_state']==-5)
                                <span>已退货</span>
                            @elseif($order_info['plat_order_state']==-9)
                                <span>已删除</span>
                            @endif

                            {{--若是微信送礼订单，则标记是微信送礼生成的订单--}}
                            @if($order_info['is_share_gifts'])
                                <img src="/sd_img/wx-share-gift.png" style="width:75px;margin-top:0px;position: absolute;right: 40px;"/>
                            @endif
                            {{--若是得的微信礼品的订单，则标记得到微信礼品的图标--}}
                            @if($order_info['is_get_gift'])
                                <img src="/sd_img/get-share-gift.png" style="width:75px;margin-top:0px;position: absolute;right: 40px;"/>
                            @endif
                        </li>
                    </div>

                    {{--商品信息--}}
                    @foreach($order_info['skus'] as $goods)
                        <a href="{{asset('elife/order/e_info/'.$order_info['plat_order_sn'])}}">
                            <div class="order-list-content flex-between">
                                <ul class="order-list-content-l">
                                    <li><img src="{{asset($goods['sku_image'])}}" alt=""></li>
                                </ul>
                                <div class="order-list-content-r">
                                    <ul>
                                        <li class="r-title font-12 color-80">{{$goods['sku_name']}}</li>
                                        <li class="r-property font-12 color-160">规格： @foreach($goods['sku_spec'] as $spec)<span>{{$spec}}&nbsp;</span>@endforeach</li></ul>
                                    <ul class="font-14 color-80 flex-between">
                                        <li>¥&nbsp;{{$goods['settlement_price']}}</li>
                                        <li>&nbsp;&times;&nbsp;{{$goods['number']}}</li>
                                    </ul>
                                </div>
                            </div>
                        </a>
                    @endforeach

                    <div class="order-list-count b-white">
                        <ul class="font-12 color-80 flex-end">
                            <li>
                                {{--共<span class="color-f53a3a">0</span>件商品&nbsp;&nbsp;&nbsp;&nbsp;--}}
                                实付款：¥<span class="color-e42f46">{{$order_info['pay_rmb_amount']}}</span></li>
                        </ul>
                    </div>

                    <div class="order-list-optionBar font-12 color-80 b-white">
                        <ul class="flex-end">
                            {{--<li class="buyagain flex-center" value="{{$order_info['skus'][0]['spu_id']}}">购买</li>--}}
                            @if($order_info['plat_order_state']==1)
                                {{--若订单是电子付款，则需用户上传该订单的银行支付凭证--}}
                                @if($order_info['pay_mode_id'] == 7)
                                    {{--<div class="pay-order" style="width: 105px;">上传支付凭证</div>--}}
                                    <input type="hidden" value="{{$order_info['plat_order_id']}}"/>
                                    <li class="flex-center cancel-order" is-share="{{$order_info['is_share_gifts']}}">取消订单</li>
                                @else
                                    <li class="flex-center pay go-to-pay">立即付款</li>
                                    <input type="hidden" value="{{$order_info['plat_order_id']}}"/>
                                    <li class="flex-center cancel-order" is-share="{{$order_info['is_share_gifts']}}">取消订单</li>
                                @endif

                                {{--<span class="refund-order" order="{{$order_info['plat_order_id']}}">退单</span>--}}

                            @elseif($order_info['plat_order_state']==2)
                                @if($order_info['pay_mode_id'] == 5 || $order_info['pay_mode_id'] == 8)
                                    <li class="flex-center pay go-to-pay">立即付款</li>
                                    <input type="hidden" value="{{$order_info['plat_order_id']}}"/>
                                    {{--<div class="cancel-order">取消订单</div>--}}
                                @endif

                                {{--领取的微信礼品订单生成后不能在取消--}}
                                @if($order_info['is_get_gift'] == 0)
                                    <input type="hidden" value="{{$order_info['plat_order_id']}}"/>
                                    {{-- <li class="flex-center cancel-order" is-share="{{$order_info['is_share_gifts']}}">取消订单</li>--}}
                                    <a href="/elife/order/applyRefund/{{$order_info['plat_order_id']}}"><li class="refund-order1 flex-center"  order="{{$order_info['plat_order_id']}}">申请退款</li></a>
                                @endif

                                {{--<span  class="refund-order" order="{{$order_info['plat_order_id']}}">退单</span>--}}
                            @elseif($order_info['plat_order_state']==3)

                                @if($order_info['pay_mode_id'] == 5 || $order_info['pay_mode_id'] == 8)
                                    <li class="flex-center pay go-to-pay">立即付款</li>
                                    <input type="hidden" value="{{$order_info['plat_order_id']}}"/>
                                @else
                                    <li class="flex-center sure_order" order="{{$order_info['plat_order_id']}}">确认收货</li>

                                @endif

                            @elseif($order_info['plat_order_state']==4)

                                @if($order_info['pay_mode_id'] == 8)
                                    <li class="flex-center pay go-to-pay ">立即付款</li>
                                    <input type="hidden" value="{{$order_info['plat_order_id']}}"/>

                                @else
                                    <!--<span style="margin-right: 35%;">赠送(积分):<span style="color:red">{{$order_info['getIntegral']}}</span></span>!-->
                                    <span class="del-order" order="{{$order_info['plat_order_id']}}">删除订单</span>
                                @endif
                            @elseif($order_info['plat_order_state']==-2)
                                <a href="/elife/order/saleOrderState/{{$order_info['plat_order_id']}}"><li class="flex-center">查看详情</li></a>
                            @elseif($order_info['plat_order_state']==9)
                                <li class="flex-center">删除订单</li>
                            @endif

                            @if($order_info['plat_order_state'] >= 3)
                                <li class="show_shipping" order="{{$order_info['plat_order_id']}}" >查看物流</li>
                            @endif
                        </ul>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
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
    <script src="{{asset('elife_js/jquery.min.js')}}"></script>
    <script src="{{asset('elife_js/font_wvum.js')}}"></script>
    <script type="text/javascript" src="{{asset('elife_js/order/order_list.js')}}"></script>
    <script type="text/javascript" src="{{asset('elife_js/common.js')}}"></script>
    <script type="text/javascript">
        function showSubscribe() {
            $('.lanrenzhijia').show(0);
            $('.content_mark').show(0);
        }

        function hideSubscribe(){
            $('.lanrenzhijia').hide(0);
            $('.content_mark').hide(0);
        }


@endsection

