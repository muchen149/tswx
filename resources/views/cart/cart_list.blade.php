@extends('inheritance')

{{--@section('title')
    购物车
@endsection--}}

@section('css')
    <title>{!!config('constant')['comTitle']['title']!!}</title>
    <link rel="stylesheet" href="{{asset('sd_css/mui.min.css')}}">
    <link rel="stylesheet" href="{{asset('sd_css/common.css')}}">
    <link rel="stylesheet" href="{{asset('sd_css/tabbar.css')}}">

    <style>
        /*阿里图标默认样式*/
        .icon{
            width: 1em;
            height: 1em;
            vertical-align: -0.15em;
            fill: currentColor;
            overflow: hidden;
        }

        /*标题栏*/
        .title{
            position: fixed;
            width: 100%;
            height: 44px;
            top: 0;
            margin-bottom: 10px;
            border-bottom: 1px solid rgb(230,230,230);
            text-align: center;
        }
        .title ul{
            width: 90%;
        }
        .title #shopCar-count{
            color: #f53a3a;
        }
        .title .car-status1{
            color: deepskyblue;
        }
        .title .car-status2{
            color: deepskyblue;
        }


        /*商品列表*/
        .goods-list{
            height: 120px;
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .l{
            margin-left: 3%;
            margin-right: 3%;
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

        .m{
            margin-right: 3%;
        }
        .m img{
            width: 100px;
            margin-top: 4px;
        }
        .r{
            width: 60%;
            margin-right: 3%;
        }
        .r .goods-title{
            height: 42px;
            overflow: hidden;
        }
        .r .p-c{
            margin-top: 8px;
        }
        .r .prize{
            color: #f53a3a;
        }




        /*加号与减号*/
        .car-editor{
            width: 45%;
            margin-left: 4%;
        }
        .car-editor ul:last-child{
            margin-top: 6px;
        }
        .car-editor ul span{
            width: 30%;
        }
        .car-editor ul input{
            box-sizing: border-box;
            text-align: center;
            width: 40%;
            height: 40px;
            outline: none;
            border: none;
            margin: 0;
            padding: 0;
        }
        .min ,.add{
            height: 40px;
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
        .actionBar .goPay{
            width: 25%;
            height: 51px;
            background-color: #f53a3a;
        }
        .actionBar .goPay a{
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
        /*底部操作条 end*/



        /*底部弹出的商品属性*/
        #popover{
            bottom: 54px!important;
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
            background-color: white;
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
            color: white;
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


    </style>
@endsection



@section('content')
<body>
@if(empty($skus))
    <div style="margin:0 auto;height:200px;width:200px;margin-top:40px;text-align: center">
        <img style="width:80%" src="/sd_img/empty_quan.png">
        <p style="color:#323232;font-size:14px;margin-top:14px;">亲，您的购物车空空如也！</p>
    </div>
@else
    <div class="title b-white font-16 color-50 flex-center">
        <ul class="flex-between">
            <li class="font-14 color-80" ><span id="jianshu">合计：<span id="shopCar-count"></span>&nbsp;件</span></li>
            <li id="openPopover">购物车</li>
            <li class="car-status1 font-14 color-80">编辑</li>
            <li class="car-status2 font-14 color-80" style="display: none">完成</li>
        </ul>
    </div>

    @foreach($skus as $sku)
        <div class="goods-list b-white">
            <ul class="l" >
                <li class="turn-status2" cart_id="{{$sku['cart_id']}}">
                </li>
            </ul>
            <ul class="m">
                <a href="{{url('/sd_shop/goods/spuDetail/'.$sku['spu_id'])}}">
                    <li ><img src="{{asset($sku['main_img'])}}" alt=""></li>
                </a>
            </ul>
            <ul class="r">
                <a href="{{url('/sd_shop/goods/spuDetail/'.$sku['spu_id'])}}">
                    <li class="goods-title font-12 color-50">{{$sku['sku_name']}}</li>
                </a>

                <li class="property font-12 color-120">
                    @if( !empty($sku['sku_spec']) )
                        规格:
                        @foreach($sku['sku_spec'] as $guige)
                            &nbsp;{{$guige}}
                        @endforeach

                    @endif
                </li>
                <ul class="p-c flex-between font-14">
                    <li class="prize ">
                        ¥&nbsp;<span class="g_price">{{$sku['price']}}</span>
                    </li>
                    <li class="count color-80">
                        X&nbsp;<span class="buy_num">{{$sku['number']}}</span>
                    </li>
                </ul>
            </ul>
            <ul class="car-editor" style="display: none">
                <ul class="add-min flex-between">
                    <span class='min flex-start color-160 font-22'><svg class='icon' aria-hidden='true'><use xlink:href='#icon-minus'></use></svg></span>
                    <input type="text" value="{{$sku['number']}}" class="font-16 font-ber color-80 quantity" cart_id="{{$sku['cart_id']}}">
                    <span class='add  flex-end color-160 font-22'><svg class='icon' aria-hidden='true'><use xlink:href='#icon-icon1460189703731'></use></svg></span>
                </ul>
                <ul class='flex-between'>
                    <li class='font-12 color-120'>
                        @if( !empty($sku['sku_spec']) )
                            规格:
                            @foreach($sku['sku_spec'] as $guige)
                                &nbsp;{{$guige}}
                            @endforeach

                        @endif</li>
                    {{--<li>--}}
                        {{--<a href="#popover">--}}
                            {{--<svg class='icon font-18 color-160' aria-hidden='true' ><use xlink:href='#icon-zhankai'></use></svg>--}}
                        {{--</a>--}}
                    {{--</li>--}}
                </ul>
            </ul>
        </div>

    @endforeach

    <div class="actionBar flex-between">
        <ul class="flex-between" style="margin-left: 3%">
            <li class="turn-status-all-2" id="turn" >
            </li>
            <li class=" font-14 color-50" style="margin-left: 6px;margin-top: 4px">全选</li>
        </ul>
        <ul class='heji'>
            <li class="font-12 color-50">合计</li>
            <li class="font-14 color-50" style="color: red">¥&nbsp;<span class="total_price">0</span></li>
        </ul>
        <ul class="goPay color-white flex-center font-14">
            <li del="0">去结算</li>
        </ul>

    </div>
@endif


<form action="{{asset('/order/showPay')}}" method="post" id="jsform">
    <input type="hidden" name="cartIds" id="cartIds">
    <input type="hidden" name="sku_source_type" value="1">
</form>


{{--<div class="actionBar flex-between">--}}
    {{--<ul class="flex-between" style="margin-left: 3%">--}}
        {{--<li class="turn-status-all-2" id="turn" >--}}
        {{--</li>--}}
        {{--<li class=" font-14 color-50" style="margin-left: 6px;margin-top: 4px">全选</li>--}}
    {{--</ul>--}}
    {{--<ul class='heji'>--}}
        {{--<li class="font-12 color-50">合计</li>--}}
        {{--<li class="font-14 color-50">¥18659.00</li>--}}
    {{--</ul>--}}
    {{--<ul class="goPay color-white flex-center font-14">--}}
        {{--<li>去结算</li>--}}
    {{--</ul>--}}

{{--</div>--}}
{{--<div class="tabbar">--}}
    {{--<a href="index.html">--}}
        {{--<ul>--}}
            {{--<li><img src="img/tab_index_pre.png" alt=""></li>--}}
            {{--<li >首页</li>--}}
        {{--</ul>--}}
    {{--</a>--}}
    {{--<a href="call.html">--}}
        {{--<ul>--}}
            {{--<li><img src="img/tab_call_pre.png" alt=""></li>--}}
            {{--<li >召唤管家</li>--}}
        {{--</ul>--}}
    {{--</a>--}}
    {{--<a href="shopCar.html">--}}
        {{--<ul>--}}
            {{--<li><img src="img/tab_car_set.png" alt=""></li>--}}
            {{--<li class="tabbar-set">购物车</li>--}}
        {{--</ul>--}}
    {{--</a>--}}
    {{--<a href="me.html">--}}
        {{--<ul>--}}
            {{--<li><img src="img/tab_user_pre.png" alt=""></li>--}}
            {{--<li >我的</li>--}}
        {{--</ul>--}}
    {{--</a>--}}
{{--</div>--}}
{{--<div id="popover" class="mui-popover mui-popover-bottom mui-popover-action ">--}}
    {{--<div class="select-goods-property">--}}
        {{--<div class="goods-head">--}}
            {{--<ul>--}}
                {{--<li><img src="img/car_example2.jpg" alt=""></li>--}}
            {{--</ul>--}}
            {{--<ul class="font-14">--}}
                {{--<li style="color: #f53a3a">¥39.99</li>--}}
                {{--<li class="font-14 color-80">库存130</li>--}}
                {{--<li class="font-14 color-80">已选：<span>170cm黄</span></li>--}}
            {{--</ul>--}}
        {{--</div>--}}
        {{--<li class="property-close">--}}
            {{--<a href="#popover"><!--通过加入锚点链接，锚点为popover容器的id,以切换popover-->--}}
                {{--<svg class='icon font-26 color-80' aria-hidden='true' ><use xlink:href='#icon-iconfonterror2'></use></svg>--}}
            {{--</a>--}}
        {{--</li>--}}

        {{--<p class="font-14 color-50">规格1</p>--}}
        {{--<ul class="goods-property" id="property-1">--}}
            {{--<li class="property-unselect">纯白色</li>--}}
            {{--<li class="property-unselect">米黄色</li>--}}
            {{--<li class="property-unselect">粉色</li>--}}
            {{--<li class="property-unselect">中国红</li>--}}
            {{--<li class="property-unselect">灰色</li>--}}
            {{--<li class="property-unselect">黑色</li>--}}
            {{--<li class="property-unselect">绿色</li>--}}
        {{--</ul>--}}
        {{--<p class="font-14 color-50">规格2</p>--}}
        {{--<ul class="goods-property" id="property-2">--}}
            {{--<li class="property-unselect">150cm</li>--}}
            {{--<li class="property-unselect">155cm</li>--}}
            {{--<li class="property-unselect">160cm</li>--}}
            {{--<li class="property-unselect">165cm</li>--}}
            {{--<li class="property-unselect">168cm</li>--}}
            {{--<li class="property-unselect">170cm</li>--}}
            {{--<li class="property-unselect">175cm</li>--}}
        {{--</ul>--}}
    {{--</div>--}}

    {{--<!-- 收起按钮 -->--}}
    {{--<li class="property-sure flex-center color-white font-14">--}}
        {{--<a href="#popover"><b>确定</b></a><!--通过加入锚点链接，锚点为popover容器的id,以切换popover-->--}}
    {{--</li>--}}
{{--</div>--}}
</body>

@endsection

@section('js')
    <script src="{{asset('sd_js/font_wvum.js')}}"></script>
    <script src="{{asset('sd_js/common.js')}}"></script>
    <script src="{{asset('sd_js/cart/cart_list.js')}}"></script>


@endsection
