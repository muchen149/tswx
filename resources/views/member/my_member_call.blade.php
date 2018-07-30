@extends('inheritance')

{{--@section('title')
    召唤管家
@endsection--}}

@section('css')
    <title>{!!config('constant')['comTitle']['title']!!}</title>
    <link rel="stylesheet" href="{{asset('sd_css/common.css')}}">
    <link rel="stylesheet" href="{{asset('sd_css/mui.min.css')}}">
    {{--<link rel="stylesheet" href="{{asset('sd_css/tabbar.css')}}">--}}
    <link rel="stylesheet" href="{{asset('/sd_css/new_common.css')}}">
    <style>
        .call-header{
            width: 100%;
            padding-top: 10px;
            margin-bottom: 20px;
        }
        .call-header .steward-avator{
            margin-left: 7%;
            width:32% ;
            perspective: 800px;
        }
        .call-header .steward-avator img{
            width: 100px;
            margin-left: -120px;
        }
        .rotate{
            width: 100px;
            margin-left: -120px;
            animation: rotate 3s ease;
        }
        @keyframes rotate {
            100%{
                transform: rotateY(720deg)}
        }

        .call-header .steward-word{
            margin-left: 3%;
            margin-right: 3%;
            width: 55%;
        }

        .call-panel{
            border-top: 1px solid rgb(220,220,220);
            border-bottom: 1px solid rgb(220,220,220);
        }
        .call-panel ul{
            box-sizing: border-box;
            width: 100%;
            height: 100px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
            color: rgb(80,80,80);

        }
        .call-panel ul:nth-child(2),.call-panel ul:nth-child(3){
            box-sizing: border-box;
            border-top: 1px solid rgb(240,240,240);
        }
        .call-panel ul li{
            width: 25%;
            height: 100px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .call-panel ul li img{
            width: 100%;
        }

        .call-more{
            width: 100%;
            margin-top: 18px;
            margin-bottom: 100px;
            text-align: center;
        }
        .mui-slider-indicator{
            position: relative;
            top: -1px;
        }
        .b-left{
            border-left: 1px solid rgb(240,240,240);
        }
    </style>
@endsection

@section('content')
<body >


<div class="call-header flex-between">
    <div class="steward-avator">
        <img src="{{asset('sd_img/steward-avator1.png')}}" alt="">
    </div>
    <div class="steward-word font-12">
        <ul>
            <li>Hi,亲爱的用户！</li>
            <li>我是全能水丁管家，有什么能为您效劳的？</li>
        </ul>
    </div>
</div>


<div class="mui-slider">
    <div class="mui-slider-group">
        <div class="mui-slider-item">
            <div class="call-panel b-white">
                <ul>
                    <li>
                        <a href="/sd_shop/doSearch/1/10/19?"><img src="{{asset('/sd_img/zh01.jpg')}}" alt=""></a>
                        <span>精选食材</span>
                    </li>
                    <li class="b-left ">
                        <a href="/sd_shop/doSearch/1/10/26?"><img src="{{asset('/sd_img/zh04.jpg')}}" alt=""></a>
                        <span>舒适床品</span>
                    </li>
                    <li class="b-left">
                        <a href="/sd_shop/doSearch/1/10/159?"><img src="{{asset('/sd_img/zh07.jpg')}}" alt=""></a>
                        <span>宝宝用具</span>
                    </li>
                    <li class="b-left">
                        <a href="/sd_shop/doSearch/1/10/67?"><img src="{{asset('/sd_img/zh10.jpg')}}" alt=""></a>
                        <span>品质箱包</span>
                    </li>
                </ul>
                <ul>
                    <li>
                        <a href="/sd_shop/doSearch/1/10/20?"><img src="{{asset('/sd_img/zh02.jpg')}}" alt=""></a>
                        <span>茗茶冲饮</span>
                    </li>
                    <li class="b-left">
                        <a href="/sd_shop/doSearch/1/10/31?"><img src="{{asset('/sd_img/zh05.jpg')}}" alt=""></a>
                        <span>生活家电</span>
                    </li>
                    <li class="b-left">
                        <a href="/sd_shop/doSearch/1/10/76?"><img src="{{asset('/sd_img/zh08.jpg')}}" alt=""></a>
                        <span>宝宝寝居</span>
                    </li>
                    <li class="b-left">
                        <a href="/sd_shop/doSearch/1/10/154?"><img src="{{asset('/sd_img/zh11.jpg')}}" alt=""></a>
                        <span>出行必备</span>
                    </li>
                </ul>
                <ul>
                    <li>
                        <a href="/sd_shop/doSearch/1/10/21?"><img src="{{asset('/sd_img/zh03.jpg')}}" alt=""></a>
                        <span>休闲零食</span>
                    </li>
                    <li class="b-left">
                        <a href="/sd_shop/doSearch/1/10/27?"><img src="{{asset('/sd_img/zh06.jpg')}}" alt=""></a>
                        <span>品味家具</span>
                    </li>
                    <li class="b-left">
                        <a href="/sd_shop/doSearch/1/10/78?"><img src="{{asset('/sd_img/zh09.jpg')}}" alt=""></a>
                        <span>妈咪专区</span>
                    </li>
                    <li class="b-left">
                        <a href="/sd_shop/doSearch/1/10/64?"><img src="{{asset('/sd_img/zh12.jpg')}}" alt=""></a>
                        <span>酒店住宿</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
   {{-- <div class="mui-slider-indicator">
        <div class="mui-indicator mui-active"></div>
        <div class="mui-indicator"></div>
        <div class="mui-indicator"></div>
    </div>--}}
</div>

<div class="call-more font-12"><a href="/sd_shop/shop">以上都不是亲想要的？</a></div>
{{--<div class="tabbar">
    <a href="{{asset('shop/index')}}">
        <ul>
            <li><img src="{{asset('sd_img/tab_index_pre.png')}}" alt=""></li>
            <li >首页</li>
        </ul>
    </a>
    <a href="{{asset('member/call')}}">
        <ul>
            <li><img src="{{asset('sd_img/tab_call_pre.png')}}" alt=""></li>
            <li class="tabbar-set">召唤管家</li>
        </ul>
    </a>
    <a href="{{asset('/cart/index')}}">
        <ul>
            <li><img src="{{asset('sd_img/tab_car_pre.png')}}" alt=""></li>
            <li >购物车</li>
        </ul>
    </a>
    <a href="{{asset('/personal/index')}}">
        <ul>
            <li><img src="{{asset('sd_img/tab_user_set.png')}}" alt=""></li>
            <li >我的</li>
        </ul>
    </a>
</div>--}}
<div class="main-nav">
    <ul>
        <li class=" navBtn"><a href="{{asset('/shop/index')}}"><span class="icon"></span><span class="text">管家服务</span></a></li>
        <li class=" navBtn"><a href="{{asset('/sd_shop/shop')}}"><span class="icon"></span><span class="text">集市</span></a></li>
        <li class="calling active navBtn"><a href="{{asset('member/call')}}"><span class="icon"><i></i></span><span class="text">召唤管家</span></a></li>
        <li class=" navBtn"><a href="{{asset('/cart/index')}}"><span class="icon"></span><span class="text">购物车</span></a></li>
        <li class=" navBtn"><a href="{{asset('/personal/index')}}"><span class="icon"></span><span class="text">我的</span></a></li>
    </ul>
</div>

</body>
@endsection

@section('js')

    <script src="{{asset('sd_js/mui.min.js')}}"></script>
    <script src="{{asset('sd_js/jquery.min.js')}}"></script>
    <script type="text/javascript" charset="utf-8"> mui.init(); </script><!--mui框架初始化-->

    <script>

        $(function(){
            $('.steward-avator img').animate({
                marginLeft:'0px'
            },500);
            $('.steward-avator img').on('click',function(){
                $(this).toggleClass('rotate')
            })
        });

        var gallery = mui('.mui-slider');
        gallery.slider({
            interval:0//自动轮播周期，若为0则不自动播放，默认为0；
        });

        $('.navBtn').click(function () {
            $(this).addClass("active").siblings().removeClass("active");
        });
    </script>

@endsection



