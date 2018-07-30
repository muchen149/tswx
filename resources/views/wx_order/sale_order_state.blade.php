@extends('inheritance')

@section('title')
    退单状态
@endsection
@section('css')
    <link rel="stylesheet" href="{{asset('sd_css/mui.min.css')}}">
    <link rel="stylesheet" href="{{asset('sd_css/common.css')}}">


    <style>

        body {
            background-color: #fafafa;
        }
        #order_id .title{
            text-align: -webkit-center;
            padding: 15px;
            color: #999;
            font-size: 13px;
            background:#fafafa;
        }
        #order_id .goods-list{
            padding: 10px 0px 5px 5px;
            border-bottom: 1px solid #fafafa;
        }
        #order_id .supplier_name img{
            width: 33px;
            padding: 0px 5px 4px 10px;
            font-size: 15px;
            font-family: 微软雅黑;
        }
        #order_id .supplier_state{
            color: #c80000;
            margin-right: 10px;
            font-size: 15px;
        }
        #order_id .supplier_reason{
            padding-left:12px;
            font-size: 13px;
            background: #fff;
            border-top: 1px solid #fafafa;
            line-height: 30px;
        }
        #order_id .separate{
            margin-bottom: 10px;
        }
        /*商品信息*/
        .order-list-content {
            width: 100%;
            height: 110px;
            background-color: #fff;
        }
        .flex-between {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .order-list-content-l {
            margin-left: 3%;
        }
        .order-list-content li:nth-of-type(1) img {
            width: 80px;
        }
        .order-list-content-r {
            margin-left: 4%;
            margin-right: 3%;
            width: 80%;
        }
        .order-discount ul {
            width: 94%;
            height: 24px;
            margin: 0 auto;
        }



        .turn-status2{
            width: 22px;
            height: 22px;
            background: url("../../sd_img/unselecte.png") no-repeat;
            background-size: 100%;
        }
        .turn-status1{
            width: 22px;
            height: 22px;
            background: url("../../sd_img/selected.png") no-repeat;
            background-size: 100%;
        }
        /*底部操作条 start*/
        .actionBar{
            position: fixed;
            bottom: 0px;
            box-sizing: border-box;
            width:100%;
            height: 50px;
            border-top: 1px solid rgb(220,220,220);
            background-color: rgba(255,255,255,0.8);
        }
        .actionBar .heji{
            text-align: center;
        }
        .actionBar .goApply{
            width: 25%;
            height: 51px;
            background-color: #f53a3a;
        }
        .actionBar .goApply a{
            color: white;
        }
        .actionBar .delete{
            width: 25%;
            height: 51px;
            background-color: #f53a3a;
        }

        .turn-status-all-2{
            width: 22px;
            height: 22px;
            background: url("../../sd_img/unselecte.png") no-repeat;
            background-size: 100%;
        }
        .turn-status-all-1{
            width: 22px;
            height: 22px;
            background: url("../../sd_img/selected.png") no-repeat;
            background-size: 100%;
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
    <div id="order_id">
        <div class="title">退款金额将在1-3个工作日内返回到原账户</div>
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
                <a href="{{asset('order/info/')}}">
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
    <script src="{{asset('sd_js/jquery.min.js')}}"></script>
    <script src="{{asset('sd_js/common.js')}}"></script>
    <script src="{{asset('sd_js/font_wvum.js')}}"></script>
@endsection