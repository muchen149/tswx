@extends('inheritance')

@section('title')
    退单状态
@endsection
@section('css')
    <link rel="stylesheet" href="{{asset('elife_css/mui.min.css')}}">
    <link rel="stylesheet" href="{{asset('elife_css/common.css')}}">
    <link rel="stylesheet" href="{{asset('elife_css/order/refund_order_state.css')}}">
    <style>
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
            width: 10%;
        }
    </style>
@endsection

@section('content')
    <body>
    {{--<ul class="head flex-around">
        <li class="avator">
            @if(!empty(Auth::user()->avatar))
                <img id="avatar"
                     src="{{Auth::user()->avatar}}"
                     style="border-radius: 60px;"/>
            @else
                <img style='border-radius: 60px;position: relative;z-index:0;height:80px;width:80px;margin-top:5%;'
                     src="{{asset('/sd_img/default_user_portrait.gif')}}">
            @endif
        </li>
        <li>
            <svg class="icon" aria-hidden="true">
                <use xlink:href="#icon-jiaohuan"></use>
            </svg>
        </li>
        <li>
            <svg class="icon" aria-hidden="true">
                <use xlink:href="#icon-shouji"></use>
            </svg>
        </li>
    </ul>--}}
    {{--商品信息--}}
    <div class="head">
        <div class="nav b-white">
            <a class="return font-14 color-80" href="{{asset('elife/personal/index')}}"><img src="{{asset('sd_img/next.png')}}" alt="" class="size-26" style="transform:rotate(180deg);"></a>
            <a class="title font-14 color-80" href="#">退款金额将在1-3个工作日内返回到原账户</a>
        </div>
    </div>
    <div id="order_id" style="margin-top:50px">
        @foreach($service_order as $order)
                <div class="goods-list b-white flex-between">
                    <li class="supplier_name"><img src="{{asset('/sd_img/supplier_nav.png')}}" alt="" width="100%">{{$order->supplier_name}}</li>
                    @if($order->supplier_state == 0)
                        <li class="supplier_state">申请驳回</li>
                    @elseif($order->supplier_state == 1)
                        <li class="supplier_state">审核通过</li>
                    @elseif($order->supplier_state == 3 || $order->supplier_state == 2)
                        <li class="supplier_state">审核中</li>
                    @endif
                </div>
                @foreach($order->sku_id as $sku_info)
                <a href="{{asset('elife/order/e_info/'.$order->plat_order_id)}}">
                    <div class="order-list-content flex-between">
                        <ul class="order-list-content-l">
                            <li><img src="{{asset($sku_info->sku_image)}}" alt=""></li>
                        </ul>
                        <div class="order-list-content-r">
                            <ul>
                                <li class="r-title font-12 color-80">{{$sku_info->sku_name}}</li>
                                <li class="r-property font-12 color-160"></li></ul>
                            <ul class="font-14 color-80 flex-between">
                                <li>¥&nbsp;{{$sku_info->settlement_price}}</li>
                                <li>&nbsp;&times;&nbsp;{{$sku_info->number}}</li>
                            </ul>
                        </div>
                    </div>
				</a>
                @endforeach
                @if($order->supplier_state == 0 || $order->supplier_state == 1)
                <div class="supplier_reason">处理结果:{{$order->close_message}}</div>
                @endif
                <div class="separate"></div>
        @endforeach
    </div>

    </body>
@endsection

@section('js')
    <script src="{{asset('elife_css/jquery.min.js')}}"></script>
    <script src="{{asset('elife_css/common.js')}}"></script>
    <script src="{{asset('elife_css/font_wvum.js')}}"></script>
@endsection