@extends('inheritance')

@section('title')
    支付
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('sd_css/common.css')}}">
    <link rel="stylesheet" href="{{asset('sd_css/mui.min.css')}}">
    <link rel="stylesheet" href="{{asset('sd_css/mui.min.css')}}">

    <style>
        .icon {
            width: 1em; height: 1em;
            vertical-align: -0.15em;
            fill: currentColor;
            overflow: hidden;
        }
        .call-friend-youhui{
            overflow: hidden;
        }
        .call-friend-youhui p{
            margin-left: 10px;
            line-height: 40px;
        }
        .call-friend-youhui ul{
            text-align: center;
            font-size: 12px;
            line-height: 16px;
            color: rgb(100,100,100);
            margin-bottom: 10px;
        }
        .call-friend-youhui ul img{
            width: 40px;
            height: 40px;

        }
        .call-friend-amount{
            margin-top: 10px;
            overflow: hidden;
        }
        .call-friend-amount p{
            margin-left: 10px;
            line-height: 40px;
        }
        .call-friend-amount ul{
            display: flex;
            justify-content: flex-start;
            flex-wrap: wrap;
        }
        .call-friend-amount li{
            padding: 4px 6px;
            border: 1px solid rgb(220,220,220);
            border-radius: 6px;
            margin-left: 10px;
            margin-bottom: 10px;
            font-size: 12px;
            color: rgb(80,80,80);
        }

        .address div{
            width: 95%;
            margin: 10px auto 0;
        }
        .address .r{
            margin-left: 16px;
        }
        .address .r li:first-child{
            margin-top: 10px;
            margin-bottom: 8px;
        }
        .address .r .r-b{
            margin-bottom: 10px;
            line-height: 22px;
        }
        .call-friend-sp ul:first-child{
            margin-left: 10px;
        }
        .call-friend-sp ul:first-child img{
            width: 80px;
        }
        .call-friend-sp{
            margin-top: 10px;
        }
        .call-friend-sp ul:last-child{
            margin-left: 10px;
            margin-right: 10px;
        }
        .call-friend-property{
            margin-top: 10px;
        }
        .call-friend-property p{
            font-size: 14px;
            color: rgb(50,50,50);
            line-height: 40px;
        }
        .call-friend-property ul{
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-start;
            align-items: flex-start;
        }
        .call-friend-property ul li{
            margin-right: 10px;
            margin-bottom: 10px;
            border: 1px solid rgb(220,220,220);
            padding: 4px 6px;
            border-radius: 6px;
            font-size: 12px;
            color: rgb(100,100,100);
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .call-friend-property p,.call-friend-property ul{
            margin-left: 10px;
        }
        .call-friend-property ul{
            margin-right: 10px;
        }

        .buy-num{
            margin-top: 10px;
            height: 50px;
        }
        .buy-num ul{
            height: 50px;
            margin-left: 10px;
            margin-right: 10px;
        }
        .buy-num ul li:last-child{
            width: 100px;
        }
        .buy-num ul li:last-child input{
            box-sizing: border-box;
            text-align: center;
            width: 40px;
            height: 30px;
            border-top: 1px solid rgb(220,220,220);
            border-bottom: 1px solid rgb(220,220,220);
            border-left: none;
            border-right: none;
            outline: none;
            margin-bottom: 0;
            border-radius: 0;
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
        .call-friend-payStyle{
            margin-top: 10px;
            height: 44px;
        }
        .call-friend-payStyle ul{
            height: 44px;
            margin-left: 10px;
            margin-right: 10px;
        }

        .call-friend-payStyle-more{
            height: 44px;
            margin-bottom: 80px;
        }
        .call-friend-count{
            position: fixed;
            z-index: 999;
            bottom: 0;
            width: 100%;
            height: 50px;
        }
        .call-friend-count ul{
            height: 50px;
        }
        .call-friend-count li:first-child{
            box-sizing: content-box;
            width: 70%;
            height: 50px;
            border-top: 1px solid rgb(220,220,220);
            line-height: 50px;
            padding-left: 10px;
        }
        .call-friend-count a{
            display: inline-block;
            background-color: #f53a3a;
            width: 30%;
            height: 50px;
            text-align: center;
            line-height: 50px;
            color: white;

        }
    </style>
@endsection

@section('content')
<body >
<div class="call-friend-youhui b-white">
    <p class="font-14 color-50">一起买更优惠</p>
    <div class="flex-around">
        <ul>
            <li><img src="{{asset('sd_img/call-friend-step1.jpg')}}" alt=""></li>
            <li>选择</li>
            <li>一起买人数</li>
        </ul>
        <ul>
            <li><img src="{{asset('sd_img/call-friend-step2.jpg')}}" alt=""></li>
            <li>支付</li>
            <li>开始一起买</li>
        </ul>
        <ul>
            <li><img src="{{asset('sd_img/call-friend-step3.jpg')}}" alt=""></li>
            <li>有效时间内</li>
            <li>邀请好友一起买</li>
        </ul>
        <ul>
            <li><img src="{{asset('sd_img/call-friend-step4.jpg')}}" alt=""></li>
            <li>人数满发货</li>
            <li>超期未成退款</li>
        </ul>
    </div>
</div>
<div class="call-friend-amount b-white">
    <p class="font-14 color-50">选择购买人数</p>
    <ul>
        <li>3人立省29元</li>
        <li>5人立省39元</li>
        <li>7人立省49元</li>
        <li>9人立省59元</li>
    </ul>
</div>
<div class="address b-white">
    <div class="flex-between">
        <ul class="l">
            <li>
                <svg class="icon font-26 color-100" aria-hidden="true"><use xlink:href="#icon-infenicon07"></use> </svg>
            </li>
        </ul>
        <ul class="r">
            <ul class="r-t flex-between font-14 color-50">
                <li >收货人:<span>易远达</span></li>
                <li>12345678901</li>
            </ul>
            <li class="r-b font-12 color-80">收货地址：
                <span>
                    北京市北京市西城区中南海中海路12号饿得我确定sdsacasxs
            海路12号饿得我确定sdsacasx
                </span>
            </li>
        </ul>
    </div>
</div>
<div class="call-friend-sp flex-between">
    <ul>
        <li><img src="http://anydo.wang/images/default.jpg" alt=""></li>
    </ul>
    <ul>
        <li class="font-14 color-50">商品的标题商品的标题商品的标题商品的标题占两行</li>
        <li class="font-12 color-f53a3a">¥<span>99.00</span></li>
        <li class="font-12 color-120">已选：<span>白色</span>&nbsp;<span>加厚</span></li>
    </ul>
</div>
<div class="call-friend-property b-white">
    <p>第一组属性</p>
    <ul>
        <li>属性1</li>
        <li>属性233333</li>
        <li>属性333</li>
        <li>属性4</li>
        <li>属性53333</li>
    </ul>
    <p>第二组属性</p>
    <ul>
        <li>属性111111111</li>
        <li>属性33</li>
        <li>属性332223</li>
        <li>属性4</li>
        <li>属性53333</li>
    </ul>
</div>
<div class="buy-num b-white">
    <ul class="flex-between">
        <li class="font-14 color-50">buy-num</li>
        <li class="font-12 color-f53a3a">最大购买数量<span>6</span>件</li>
        <li class="color-180 flex-between">
            <span class="min flex-center color-160 font-22"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-minus"></use></svg></span>
            <input type="text" value="1" id="amount" class="color-50">
            <span class="add flex-center color-160 font-22"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-icon1460189703731"></use></svg></span>
        </li>
    </ul>
</div>
<div class="call-friend-payStyle b-white">
    <ul class="flex-between">
        <li>
            <span class="" style="font-size: 86px"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-weixinzhifu"></use></svg></span>
        </li>
        <li>
            <span class="font-22 color-f53a3a"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-gou1"></use></svg></span>
        </li>
    </ul>
</div>
<div class="call-friend-payStyle-more b-white flex-center">
    <ul class="flex-between">
        <li class="font-12 color-100">更多支付方式</li>
        <li>
            <span class="font-22"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-zhankai"></use></svg></span>
        </li>
    </ul>
</div>
<div class="call-friend-count">
    <ul class="flex-between">
        <li class="font-14 color-80 b-white">合计：<span class="color-f53a3a">¥199.00</span></li>
        <a href="{{asset('/member/group_to_pay')}}" class="font-12 color-white">立即支付</a>
    </ul>
</div>
</body>
@endsection

@section('js')

    <script src="{{asset('sd_js/mui.min.js')}}"></script>
    <script src="{{asset('sd_js/jquery.min.js')}}"></script>
    <script src="{{asset('sd_js/font_wvum.js')}}"></script>

    <script>
        //单击商品数量+
        $(".add").click(function(){
            var $goodsCount= parseInt($('#amount').val());
            $('#amount').val($goodsCount+1);
        });

        $(".min").click(function(){
            var $goodsCount= parseInt($('#amount').val());
            if ($goodsCount>=2){
                $('#amount').val($goodsCount-1);
            }

        });
    </script>

@endsection



