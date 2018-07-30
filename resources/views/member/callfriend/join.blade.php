@extends('inheritance')

@section('title')
    参加团购
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('sd_css/common.css')}}">
    <link rel="stylesheet" href="{{asset('sd_css/mui.min.css')}}">

    <style>
        .icon {
            width: 1em; height: 1em;
            vertical-align: -0.15em;
            fill: currentColor;
            overflow: hidden;
        }
        .pro-in img{
            width: 100%;
        }
        .pic-detail{
            overflow: hidden;
            width: 100%;
            height: 50px;
            text-align: center;

        }
        .pic-detail a{
            display: inline-block;
            padding: 4px 10px;
            border: 1px solid rgb(220,220,220);
            border-radius: 4px;
            margin-top: 10px;
        }
        .call-friend-join-progress{
            text-align: center;
            width: 100%;
            overflow: hidden;
        }
        .call-friend-join-progress ul{
            width: 80%;
            margin: 0 auto;
        }
        .call-friend-join-progress ul li:first-child{
            margin-top: 10px;
            margin-bottom: 10px;
        }
        .call-friend-join-progress ul li:nth-child(3){
            margin-top: 10px;
            margin-bottom: 10px;
        }
        .call-friend-join-progress ul li:last-child{
            margin-bottom: 10px;
        }

        .mui-progressbar{
            background: rgb(240,240,240);
        }
        .mui-progressbar span{
            background: #f45d54;
        }
        .to-buy{
            margin-top: 10px;
            overflow: hidden;
        }
        .to-buy p{
            margin-left: 10px;
            line-height: 40px;
        }
        .to-buy div{
            margin: 10px 10px 20px;
        }
        .to-buy div ul:nth-child(2){
            width: 48%;
            text-align: left;
        }
        .touxiang{
            width: 40px;
            height: 40px;
            border-radius: 30px;
            overflow: hidden;
        }
        .touxiang img{
            width: 40px;
            height: 40px;
        }
        .mybuy{
            background-color: #f53a3a;
            width: 85%;
            margin: 30px auto 50px;
            height: 36px;
            border-radius: 10px;
        }
        .mybuy a{
            color: white;
        }
    </style>
@endsection

@section('content')
<body >
<div class="mui-slider">
    <div class="mui-slider-group mui-slider-loop">
        <!--支持循环，需要重复图片节点-->
        <div class="mui-slider-item mui-slider-item-duplicate"><a href="#"><img src="http://anydo.wang/images/slide/slide4.png" alt=""></a></div>
        <div class="mui-slider-item"><a href="#"> <img src="http://anydo.wang/images/slide/slide1.png" alt=""></a></div>
        <div class="mui-slider-item"><a href="#"> <img src="http://anydo.wang/images/slide/slide2.png" alt=""></a></div>
        <div class="mui-slider-item"><a href="#"><img src="http://anydo.wang/images/slide/slide3.png" alt=""></a></div>
        <div class="mui-slider-item"><a href="#"> <img src="http://anydo.wang/images/slide/slide4.png" alt=""></a></div>
        <!--支持循环，需要重复图片节点-->
        <div class="mui-slider-item mui-slider-item-duplicate"><a href="#"><img src="http://anydo.wang/images/slide/slide1.png" alt=""></a></div>
    </div>
    <div class="mui-slider-indicator">
        <div class="mui-indicator mui-active"></div>
        <div class="mui-indicator"></div>
        <div class="mui-indicator"></div>
        <div class="mui-indicator"></div>
    </div>
</div>
<div class="pro-in b-white">
    <img src="{{asset('pic/call-friend-goodsDetail.png')}}" alt="">
</div>
<div class="pic-detail b-white">
    <a href="" class="font-14 color-80">查看图文详情</a>
</div>
<div class="call-friend-join-progress b-white">
    <ul>
        <li class="font-14 color-f53a3a">一起买更优惠</li>
        <li class="font-12 color-100">共5人参加购买(每人立省 <span class="color-f53a3a">39元</span>)，人满即发货。</li>
        <li id="demo1" class="mui-progressbar"><span></span> </li>
        <li class="font-12 color-100">一起买进度：2/5</li>
    </ul>
</div>
<div class="to-buy b-white">
    <p class="font-14 color-80">to-buy</p>
    <div class="flex-between">
        <ul>
            <li class="touxiang"><img src="{{asset('pic/me_avator_default.png')}}" alt=""></li>
        </ul>
        <ul class="font-12">
            <li class="color-80">用户名</li>
            <li style="color: #2fa8ec">参与一起购买¥580.00</li>
        </ul>
        <ul class="font-12">
            <li class="color-120">2017/04/04/04:04</li>
        </ul>
    </div>
    <div class="flex-between">
        <ul>
            <li class="touxiang"><img src="{{asset('pic/me_avator_default.png')}}" alt=""></li>
        </ul>
        <ul class="font-12">
            <li class="color-80">用户名</li>
            <li style="color: #2fa8ec">参与一起购买¥580.00</li>
        </ul>
        <ul class="font-12">
            <li class="color-120">2017/04/04/04:04</li>
        </ul>
    </div>
    <div class="flex-between">
        <ul>
            <li class="touxiang"><img src="{{asset('pic/me_avator_default.png')}}" alt=""></li>
        </ul>
        <ul class="font-12">
            <li class="color-80">用户名</li>
            <li style="color: #2fa8ec">参与一起购买¥580.00</li>
        </ul>
        <ul class="font-12">
            <li class="color-120">2017/04/04/04:04</li>
        </ul>
    </div>
    <div class="flex-between">
        <ul>
            <li class="touxiang"><img src="{{asset('pic/me_avator_default.png')}}" alt=""></li>
        </ul>
        <ul class="font-12">
            <li class="color-80">用户名</li>
            <li style="color: #2fa8ec">参与一起购买¥580.00</li>
        </ul>
        <ul class="font-12">
            <li class="color-120">2017/04/04/04:04</li>
        </ul>
    </div>
</div>
<div class="mybuy flex-center font-14">
    <a href="{{asset('/member/join_buy')}}">我也要买</a>
</div>
</body>
@endsection

@section('js')

    <script src="{{asset('sd_js/mui.min.js')}}"></script>
    <script src="{{asset('sd_js/jquery.min.js')}}"></script>
    <script src="{{asset('sd_js/font_wvum.js')}}"></script>

    <script>
        var gallery = mui('.mui-slider');
        gallery.slider({
            interval:5000//自动轮播周期，若为0则不自动播放，默认为0；
        });

        mui("#demo1").progressbar({progress:20}).show();
    </script>

@endsection



