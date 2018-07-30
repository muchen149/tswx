@extends('inheritance')

@section('title')
    我的会员
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('sd_css/mui.min.css')}}">
    <link rel="stylesheet" href="{{asset('sd_css/common.css')}}">
    <style>
        p{
            margin: 0;
        }
        /*改写轮播图的尺寸*/
        .item img{
            width: 100%;
        }


        /*头部样式*/
        .head-wrap{
            position: relative;
            width: 100%;
            background-color: white;
            overflow: hidden;
        }

        .steward-welcome{
            width: 90%;
            height: 145px;
            margin: 30px auto 10px;
        }
        .steward-welcome-l img{
            width: 100px;
        }
        .steward-welcome-r li:first-child{
            margin-bottom: 8px;
        }
        .bar1{
            position: fixed;
            z-index: 9999;
            right: 0;
            top: 20px;
            width: 106px;
            height: 32px;
            background-color: rgba(245,58,58,0.8);
            border-radius: 16px 0 0px 16px;
            display: flex;
            align-items: center;
            color: white;
            font-size: 12px;
        }
        .bar2{
            display: none;
        }
        .bar1 li:first-child{
            margin-left: 10px;
            width: 27px;
            height: 27px;
            border: 1px solid white;
            border-radius: 20px;
            overflow: hidden;
        }
        .bar1 li:last-child{
            margin-left: 8px;
        }
        .bar1 img{
            width: 27px;
        }


        /*我的特权*/
        .my-privilege{
            width: 100%;
            margin: 10px 0 10px;
            background-color: white;
        }
        .my-privilege p{
            margin-left: 2.5%;
            font-size: 14px;
            line-height: 40px;
        }
        .privilege-list{
            width: 95%;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .privilege-list a{
            display: flex;
            justify-content: center;
            align-items: center;
            width: 30%;
        }
        .privilege-list a li{
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            margin:6px 0 6px;
            font-size: 12px;
            color: rgb(80,80,80);
        }
        .privilege-list img{
            width: 46px;
        }
        .privilege-list span{
            margin-top: 6px;
        }
        /*更多特权*/
        .more-privilege{
            width: 100%;
            margin-top: 10px;
        }
        .privilege-wrap{
            background-color: white;
            margin-bottom: 10px;
        }
        .more-privilege p{
            padding-left: 2.5%;
            font-size: 14px;
            line-height: 44px;
            background-color: white;
        }
        .m-privilege-list{
            width: 95%;
            height: 120px;
            margin: 0 auto 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
        }
        .m-privilege-list .image{
            width: 80px;
            height: 80px;
            border-radius: 50px;
            overflow: hidden;
        }
        .m-privilege-list .image img{
            width: 80px;
            height: 80px;
        }
        .m-privilege-list .content{
            width: 48%;
        }
        .m-privilege-list .content li:first-child{
            color: rgb(50,50,50);
            font-size: 14px;

        }
        .m-privilege-list .content li:last-child{
            width: 100px;
            margin-top: 6px;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 1px 0;
            color: white;
            font-size: 12px;
            background-color: #f53a3a;
            border-radius: 20px;


        }
        .m-privilege-list .option1{
            width: 24%;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 4px 6px;
            color: rgb(80,80,80);
            border: 1px solid #f53a3a;
            border-radius: 4px;
        }
        .m-privilege-list .option2{
            width: 22%;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 4px 6px;
            color: rgb(150,150,150);
            border: 1px solid rgb(150,150,150);
            border-radius: 4px;
        }
    </style>

@endsection

@section('content')
<body >
<div class="head-wrap">
    <div class="steward-welcome flex-between">
        <ul class="steward-welcome-l">
            <li><img src="{{asset('sd_img/steward-avator2.png')}}" alt=""></li>
        </ul>
        <ul class="steward-welcome-r font-14 ">
            <li class="color-50">你好，亲爱的C级会员！</li>
            <li class="color-80">您的会员即将过期，请您及时续费。</li>
        </ul>
        <a href="{{asset('/member/my_member_detail')}}">
            <ul class="bar1" id="bar">
                <li><img src="{{asset('sd_img/me_avator_default.png')}}" alt=""></li>
                <li>C级会员</li>
            </ul>
        </a>
    </div>
</div>
<div class="my-privilege">
    <p>我的特权</p>
    <ul class="privilege-list font-12">
        <a href="{{asset('/member/privilege_detail')}}"> <li><img src="{{asset('sd_img/mymember_privilege1.png')}}" alt=""><span>尊享优惠</span></li></a>
        <a href="{{asset('/member/privilege_detail')}}"><li><img src="{{asset('sd_img/mymember_privilege2.png')}}" alt=""><span>尊享商品</span></li></a>
        <a href="{{asset('/member/privilege_detail')}}"><li><img src="{{asset('sd_img/mymember_privilege3.png')}}" alt=""><span>尊享活动</span></li></a>
    </ul>
    <ul class="privilege-list font-12">
        <a href="{{asset('/member/privilege_detail')}}"><li><img src="{{asset('sd_img/mymember_privilege4.png')}}" alt=""><span>尊享推荐</span></li></a>
        <a href="{{asset('/member/privilege_detail')}}"><li><img src="{{asset('sd_img/mymember_privilege5.png')}}" alt=""><span>尊享兑换</span></li></a>
        <a href="{{asset('/member/privilege_detail')}}"><li><img src="{{asset('sd_img/mymember_privilege5.png')}}" alt=""><span>专属管家</span></li></a>
    </ul>
</div>
<div>
    <div class="carousel slide" id="mycarousel"   data-pase="hover"  data-ride="carousel" data-interval="3000">
        <ol class="carousel-indicators" style="bottom: -6px;width: auto;left: 115%">
            <li data-target="#mycarousel" data-slide-to="0" class="active"></li>
            <li data-target="#mycarousel" data-slide-to="1"></li>
            <li data-target="#mycarousel" data-slide-to="2"></li>
        </ol>
        <div class="carousel-inner">
            <div class="item active">
                <img src="{{asset('pic/mymenber_carousel1.png')}}" alt="">
            </div>

            <div class="item">
                <img src="{{asset('pic/mymenber_carousel2.png')}}" alt="">
            </div>
            <div class="item">
                <img src="{{asset('pic/mymenber_carousel3.png')}}" alt="">
            </div>
        </div>
    </div>
</div>

<div class="more-privilege">
    <p>更多特权</p>
    <div class="privilege-wrap">
        <ul class="m-privilege-list">
            <li class="image"><img src="{{asset('pic/more_privilege2.png')}}" alt=""></li>
            <div class="content">
                <li>免费领取滴滴快车1-10元红包，数量有限</li>
                <li>金牌会员专享</li>
            </div>
            <div class="option1">立即领取</div>
        </ul>
    </div>
    <div class="privilege-wrap">
        <ul class="m-privilege-list">
            <li class="image"><img src="{{asset('pic/more_privilege1.png')}}" alt=""></li>
            <div class="content">
                <li>免费领取滴滴快车1-10元红包，数量有限</li>
                <li>金牌会员专享</li>
            </div>
            <div class="option2">暂不可领</div>
        </ul>
    </div>
    <div class="privilege-wrap">
        <ul class="m-privilege-list">
            <li class="image"><img src="{{asset('pic/more_privilege3.png')}}" alt=""></li>
            <div class="content">
                <li>免费领取滴滴快车1-10元红包，数量有限</li>
                <li>金牌会员专享</li>
            </div>
            <div class="option1">立即领取</div>
        </ul>
    </div>
    <div class="privilege-wrap">
        <ul class="m-privilege-list">
            <li class="image"><img src="{{asset('pic/more_privilege1.png')}}" alt=""></li>
            <div class="content">
                <li>免费领取滴滴快车1-10元红包，数量有限</li>
                <li>金牌会员专享</li>
            </div>
            <div class="option1">立即领取</div>
        </ul>
    </div>
</div>
</body>
@endsection

@section('js')

    <script src="{{asset('sd_js/jgestures.min.js')}}"></script>
    <script src="{{asset('sd_js/jquery.min.js')}}"></script>
    <script>

        //    给轮播添加触屏支持
        $(document).ready(function(){
            $('#mycarousel').on('swiperight swiperightup swiperightdown',function(){
                $("#mycarousel").carousel('prev');
            });
            $('#mycarousel').on('swipeleft swipeleftup swipeleftdown',function(){
                $("#mycarousel").carousel('next');
            })
        });

        //    隐藏bar
        $(window).on('scroll',function(){
            var obj=$(document).scrollTop();
            console.log(obj);
            if (obj>=185){
                $('#bar').attr('class','bar2');
            }
            else {
                $('#bar').attr('class','bar1');
            }
        });

        //    动态控制第一个与最后一个list的样式
        $('.privilege-wrap').last().css('margin-bottom','40px');
        $('.privilege-wrap').first().css('border-top','1px solid rgb(220,220,220)');

    </script>

@endsection



