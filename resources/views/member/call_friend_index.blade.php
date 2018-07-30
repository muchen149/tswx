@extends('inheritance')

@section('title')
    呼朋唤友
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('sd_css/common.css')}}">
    <link rel="stylesheet" href="{{asset('sd_css/mui.min.css')}}">

    <style>
        .call-friend-search {
            background-color: white;
            width: 100%;
            height: 50px;
            display: flex;
            align-items: center;
        }

        .call-friend-search .mui-search {
            width: 80%;
            margin-top: 15px;
            margin-left: 10px;
        }

        .call-friend-search .search-btn {
            display: inline-block;
            text-align: center;
            width: 16%;
            font-size: 14px;
            color: rgb(80, 80, 80);
        }

        .wy {
            overflow: hidden;
            text-align: center;
            padding-top: 10px;
            padding-bottom: 10px;
        }

        .mui-segmented-control-primary {
            margin-top: 10px;
        }

        .mui-control-item.mui-active {
            color: #f53a3a !important;
            border-bottom: 2px solid #f53a3a !important;
        }

        .mui-control-content img {
            width: 100%;
        }

        .er {
            height: 52px;
        }

        .erlist {
            margin-bottom: 10px;
        }

        .er ul:first-child {
            margin-left: 10px;

        }

        .er ul:last-child {
            margin-right: 10px;
            background-color: #f53a3a;
            padding: 4px 6px 1px 6px;
            color: white;
            font-size: 12px;
            border-radius: 4px;
        }

        .er ul:last-child a {
            color: white;
        }
    </style>
@endsection

@section('content')
    <body>
    <div class="mui-slider">
        <div class="mui-slider-group mui-slider-loop">

            <!--支持循环，需要重复图片节点-->
            <div class="mui-slider-item mui-slider-item-duplicate">
                <a href="#"><img src="http://anydo.wang/images/sp/sp4.jpg" alt=""></a>
            </div>

            <div class="mui-slider-item">
                <a href="#"><img src="http://anydo.wang/images/sp/sp1.jpg" alt=""></a>
            </div>

            <div class="mui-slider-item"><a href="#"> <img src="http://anydo.wang/images/sp/sp2.jpg" alt=""></a></div>
            <div class="mui-slider-item"><a href="#"> <img src="http://anydo.wang/images/sp/sp3.jpg" alt=""></a></div>
            <div class="mui-slider-item"><a href="#"> <img src="http://anydo.wang/images/sp/sp4.jpg" alt=""></a></div>
            <!--支持循环，需要重复图片节点-->
            <div class="mui-slider-item mui-slider-item-duplicate"><a href="#"><img
                            src="http://anydo.wang/images/sp/sp1.jpg" alt=""></a></div>
        </div>
        <div class="mui-slider-indicator">
            <div class="mui-indicator mui-active"></div>
            <div class="mui-indicator"></div>
            <div class="mui-indicator"></div>
            <div class="mui-indicator"></div>
        </div>
    </div>
    <div class="call-friend-search">
        <div class="mui-input-row mui-search ">
            <input type="search" class="mui-input-clear" placeholder="">
        </div>
        <span class="search-btn">搜索</span>
    </div>
    <div class="wy b-white">
        <h3 class="font-14 color-50">有朋友，不孤单</h3>
        <p>闲来无事，约上三五好友一起买，一起吃，一起玩</p>
    </div>
    <div class="mui-segmented-control mui-segmented-control-inverted mui-segmented-control-primary"
         style="padding: 0 6px;background-color: white">
        <a class="mui-control-item  font-12 mui-active" href="#item1">一起买</a>
        <a class="mui-control-item font-12 color-80" href="#item2">一起吃</a>
        <a class="mui-control-item font-12 color-80" href="#item3">一起玩</a>
    </div>
    <div id="item1" class="mui-control-content mui-active">
        <div class="erlist b-white">
            <a href="call_friend_goodsDetail.html"><img src="http://anydo.wang/images/slide/slide1.png"/></a>
            <div class="er flex-between b-white">
                <ul>
                    <li class="font-14 color-50">商品色热不热水不热是本人热不热热不热</li>
                    <li class="font-12 color-100">比如风湿病人封神榜发电设备</li>
                </ul>
                <ul class="flex-center"><a href="{{asset('member/callFriendBuy')}}">一起买</a></ul>
            </div>
        </div>
        <div class="erlist b-white">
            <a href="call_friend_goodsDetail.html"><img src="http://anydo.wang/images/slide/slide2.png"/></a>
            <div class="er flex-between b-white">
                <ul>
                    <li class="font-14 color-50">商品色热不热水不热是本人热不热热不热</li>
                    <li class="font-12 color-100">比如风湿病人封神榜发电设备</li>
                </ul>
                <ul class="flex-center"><a href="{{asset('member/callFriendBuy')}}">一起买</a></ul>
            </div>
        </div>

    </div>
    <div id="item2" class="mui-control-content">
        <div class="erlist b-white">
            <a href="call_friend_goodsDetail.html"><img src="http://anydo.wang/images/slide/slide3.png"/></a>
            <div class="er flex-between b-white">
                <ul>
                    <li class="font-14 color-50">商品色热不热水不热是本人热不热热不热</li>
                    <li class="font-12 color-100">比如风湿病人封神榜发电设备</li>
                </ul>
                <ul class="flex-center"><a href="{{asset('member/callFriendBuy')}}" class="color-white">一起买</a></ul>
            </div>
        </div>
        <div class="erlist b-white">
            <a href="call_friend_goodsDetail.html"><img src="http://anydo.wang/images/slide/slide4.png"/></a>
            <div class="er flex-between b-white">
                <ul>
                    <li class="font-14 color-50">商品色热不热水不热是本人热不热热不热</li>
                    <li class="font-12 color-100">比如风湿病人封神榜发电设备</li>
                </ul>
                <ul class="flex-center"><a href="{{asset('member/callFriendBuy')}}">一起买</a></ul>
            </div>
        </div>

    </div>
    <div id="item3" class="mui-control-content">
        <div class="erlist b-white">
            <a href="call_friend_goodsDetail.html"><img src="http://anydo.wang/images/slide/slide1.png"/></a>
            <div class="er flex-between">
                <ul>
                    <li class="font-14 color-50">商品色热不热水不热是本人热不热热不热</li>
                    <li class="font-12 color-100">比如风湿病人封神榜发电设备</li>
                </ul>
                <ul class="flex-center"><a href="{{asset('member/callFriendBuy')}}">一起买</a></ul>
            </div>
        </div>
        <div class="erlist b-white">
            <a href="call_friend_goodsDetail.html"><img src="http://anydo.wang/images/slide/slide2.png"/></a>
            <div class="er flex-between">
                <ul>
                    <li class="font-14 color-50">商品色热不热水不热是本人热不热热不热</li>
                    <li class="font-12 color-100">比如风湿病人封神榜发电设备</li>
                </ul>
                <ul class="flex-center"><a href="{{asset('member/callFriendBuy')}}">一起买</a></ul>
            </div>
        </div>
    </div>
    </body>
@endsection

@section('js')

    <script src="{{asset('sd_js/mui.min.js')}}"></script>
    <script src="{{asset('sd_js/jquery.min.js')}}"></script>
    <script>
        var gallery = mui('.mui-slider');
        gallery.slider({
            interval: 5000//自动轮播周期，若为0则不自动播放，默认为0；
        });
    </script>

@endsection



