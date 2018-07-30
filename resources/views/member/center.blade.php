@extends('inheritance')

{{--@section('title', '我的会员')--}}

@section('css')
    {{--<title>{!!config('constant')['comTitle']['title']!!}</title>--}}
    <title>专享权益</title>
    <link rel="stylesheet" href="{{asset('sd_css/mui.min.css')}}">
    <link rel="stylesheet" href="{{asset('sd_css/common.css')}}">
    <link type="text/css" rel="stylesheet" href="{{asset('sd_css/swiper.min.css')}}"/>
    <style>
        p {
            margin: 0;
        }

        .item img {
            width: 100%;
        }

        .head-wrap {
            position: relative;
            width: 100%;
            background-color: white;
            overflow: hidden;
        }

        .steward-welcome {
            width: 90%;
            height: 145px;
            margin: 30px auto 10px;
        }

        .steward-welcome-l img {
            width: 100px;
        }

        .steward-welcome-r li:first-child {
            margin-bottom: 8px;
        }

        .bar1 {
            position: fixed;
            z-index: 9999;
            right: 0;
            top: 20px;
            width: 106px;
            height: 32px;
            background-color: rgba(245, 58, 58, 0.8);
            border-radius: 16px 0 0px 16px;
            display: flex;
            align-items: center;
            color: white;
            font-size: 12px;
        }

        .bar2 {
            display: none;
        }

        .bar1 li:first-child {
            margin-left: 10px;
            width: 27px;
            height: 27px;
            border: 1px solid white;
            border-radius: 20px;
            overflow: hidden;
        }

        .bar1 li:last-child {
            margin-left: 8px;
        }

        .bar1 img {
            width: 27px;
        }

        .my-privilege {
            width: 100%;
            margin: 10px 0 10px;
            background-color: white;
        }

        .my-privilege p {
            margin-left: 2.5%;
            font-size: 14px;
            line-height: 40px;
        }

        .privilege-list {
            width: 95%;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .privilege-list a {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 30%;
        }

        .privilege-list a li {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            margin: 6px 0 6px;
            font-size: 12px;
            color: rgb(80, 80, 80);
        }

        .privilege-list img {
            width: 46px;
        }

        .privilege-list span {
            margin-top: 6px;
        }

        .more-privilege {
            width: 100%;
            -margin-top: 10px;
        }

        .privilege-wrap {
            background-color: white;
            margin:10px auto;
        }

        .more-privilege p {
            padding-left: 2.5%;
            font-size: 14px;
            line-height: 44px;
            background-color: white;
        }

        .m-privilege-list {
            width: 95%;
            height: 80px;
            margin: 0 auto 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
        }

        .m-privilege-list .image {
            width: 80px;
            height: 80px;
            border-radius: 50px;
            overflow: hidden;
        }

        .m-privilege-list .image img {
            width: 80px;
            height: 80px;
        }

        .m-privilege-list .content {
            width: 48%;
        }

        .m-privilege-list .content li:first-child {
            color: rgb(50, 50, 50);
            font-size: 14px;

        }

        .m-privilege-list .content li:last-child {
            width: 75px;
            margin-top: 6px;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 1px 0;
            -color: white;
            font-size: 12px;
            -background-color: #f53a3a;
            border-radius: 20px;
            border: 1px solid #d4ad6a;

        }

        .m-privilege-list .option1 {
            cursor: pointer;
            width: 24%;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 4px 6px;
            color: rgb(255, 255, 255);
            border: 1px solid #f53a3a;
            border-radius: 4px;
            background: #f63a3b;
        }

        .m-privilege-list .option2 {
            cursor: pointer;
            width: 22%;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 4px 6px;
            color: rgb(150, 150, 150);
            border: 1px solid rgb(150, 150, 150);
            border-radius: 4px;
        }

        .grade-name {
            font-size: 14px;
            font-weight: bold;
            color: orange;
        }

        .grade-expire-time {
            font-size: 14px;
            font-weight: bold;
            color: red;
        }
        /*头部样式*/
        .head-wrap {
            width: 100%;
            background: #fff;
            background-size: 100%;
            height: 70px;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .head {
            position: relative;
            width: 90%;
            height: 70px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .head .avator {
            width: 60px;
            height: 60px;
            border: 2px solid white;
            border-radius: 50px;
            overflow: hidden;
            box-shadow: 0px 0px 1px rgba(120,120,120,.4);
        }
        .head .equal {
            width: 78%;
            text-align: left;
        }
        .head .equal li:first-child {
            font-size: 1.5rem;
            font-family: "微软雅黑";
        }

        /*中间滑动样式*/
        .swiper-container {
            margin-top: 10px;
            width: 100%;
            height: 100%;
        }
        .swiper-slide {
            text-align: center;
            font-size: 18px;
            -background: #fff;

            /* Center slide text vertically */
            display: -webkit-box;
            display: -ms-flexbox;
            display: -webkit-flex;
            display: flex;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            -webkit-justify-content: center;
            justify-content: center;
            -webkit-box-align: center;
            -ms-flex-align: center;
            -webkit-align-items: center;
            align-items: center;
        }

        /*底部选项*/
        .m-option a {
            color: rgb(80, 80, 80);
        }
        .m-option ul {
            width: 95%;
            margin: 5px auto 0;
            height: 35px;
            background-color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
        }

        .m-option a {
            color: rgb(80, 80, 80);
        }

        #list img {
            transition: all 0.8s;
        }

        .rotate {
            transform: rotate(90deg);
        }

        .buy-list {
            width: 100%;
            background-color: rgb(250, 250, 250);
        }

        .buy-list ul {
            width: 95%;
            height: 45px;
            margin: 0 auto;
            background-color: rgb(250, 250, 250);
        }

        .grade-column {
            display: block;
            width: 20%;
            text-align: center;
        }

        .time-column {
            display: block;
            width: 15%;
            text-align: center;
        }

        .price-column {
            display: block;
            width: 20%;
            text-align: center;
        }

        .date-column {
            display: block;
            width: 35%;
            text-align: center;
        }

        .shadow {
            box-shadow: 0 0 0 rgba(80, 80, 80, 0.4);
        }

        .a {
            transition: transform 0.5s ease;
        }
    </style>
@endsection

@section('content')
    <body>
    <div class="font-16 head-wrap">
        {{--<img src="{{asset('sd_img/mymember_privilege1.png')}}" alt="">--}}
        <ul class="head">
            <li class="avator"><img src="{{ $member_obj->avatar }}" alt="" width="100%"></li>
            <div class="equal">
                @if($member_info_arr['is_show_expire_notice'])
                    <li class="color-80">您的VIP会员将在<br/>
                        <span class="grade-expire-time">{{ $member_info_arr['diff_now_grade_expire_time'] }}</span>
                        过期
                    </li>
                @else
                    <li>您已开通@if($member_obj->grade == 20)<strong>体验</strong>@elseif($member_obj->grade == 30)<strong>小水</strong>@elseif($member_obj->grade == 40)<strong>老丁</strong>@endif服务<br/>可享受以下权益</li>
                @endif
            </div>
        </ul>
    </div>
    <div class="swiper-container">
        <div class="swiper-wrapper">
            @if($member_obj->grade == 20)
                <div class="swiper-slide">
                    <img src="{{asset('sd_img/discount_0301.png')}}" alt="" width="70%">
                </div>
                <div class="swiper-slide">
                    <img src="{{asset('sd_img/discount_1.png')}}" alt="" width="70%">
                </div>
                <div class="swiper-slide">
                    <img src="{{asset('sd_img/discount_2.png')}}" alt="" width="70%">
                </div>
                <div class="swiper-slide">
                    <img src="{{asset('sd_img/discount_3.png')}}" alt="" width="70%">
                </div>
            @elseif($member_obj->grade == 30)
                <div class="swiper-slide">
                    <img src="{{asset('sd_img/discount_0201.png')}}" alt="" width="70%">
                </div>
                <div class="swiper-slide">
                    <img src="{{asset('sd_img/discount_1.png')}}" alt="" width="70%">
                </div>
                <div class="swiper-slide">
                    <img src="{{asset('sd_img/discount_2.png')}}" alt="" width="70%">
                </div>
                <div class="swiper-slide">
                    <img src="{{asset('sd_img/discount_3.png')}}" alt="" width="70%">
                </div>
            @elseif($member_obj->grade == 40)
                <div class="swiper-slide">
                    <img src="{{asset('sd_img/discount_0101.png')}}" alt="" width="70%">
                </div>
                <div class="swiper-slide">
                    <img src="{{asset('sd_img/discount_1.png')}}" alt="" width="70%">
                </div>
                <div class="swiper-slide">
                    <img src="{{asset('sd_img/discount_2.png')}}" alt="" width="70%">
                </div>
                <div class="swiper-slide">
                    <img src="{{asset('sd_img/discount_3.png')}}" alt="" width="70%">
                </div>
            @endif
        </div>
        <!-- Add Pagination -->
        <div class="swiper-pagination"></div>
    </div>
    <div class="m-option">
        <div class="b-white">
            @if($member_obj['grade'] == 10)
                <ul>
                    <li>会员等级</li>
                    <li>普通会员</li>
                </ul>
            @else
                <ul>
                    <li>会员有效期</li>
                    <li>{{ date('Y-m-d', $member_obj['grade_expire_time']) }}</li>
                </ul>
            @endif
        </div>
        @if($member_obj['grade'] > 20)
        <div class="b-white">
            <a href="{{ asset('member/buy/' . $member_obj['grade']) }}">
                <ul>
                    <li>续费</li>
                    <li><img src="{{asset('sd_img/next.png')}}" class="size-30"></li>
                </ul>
            </a>

        </div>
        @endif
        <div class="b-white">
            <a href="{{ asset('member/buy') }}">
                <ul>
                    <li>提升级别</li>
                    <li><img src="{{asset('sd_img/next.png')}}" class="size-30"></li>
                </ul>
            </a>
        </div>

        <div id="list" style="background-color: white;z-index: 9999">
            <ul>
                <li>购买记录</li>
                <li><img width="30px" src="{{asset('sd_img/next.png')}}"></li>
            </ul>
        </div>

        <div class="buy-list" style="display: none">
            <ul class="font-12 color-80 border-b">
                <li class="grade-column">级别</li>
                <li class="time-column">时长</li>
                <li class="price-column">价格</li>
                <li class="date-column">开通时间</li>
            </ul>
            @foreach($member as $record)
                <ul class="font-12 color-80 border-b">
                    <li class="grade-column">{{ $record->class_name }}</li>
                    <li class="time-column">{{ $record->exp_date }}{{ $record->exp_date_name }}</li>
                    <li class="price-column"> ¥ {{ $record->price }}</li>
                    <li class="date-column">{{ date('Y-m-d', $record->created_at) }}</li>
                </ul>
            @endforeach
        </div>

        <div class="more-privilege b-white">
            <ul id="g-list">
                <li>更多特权</li>
                <li><img width="30px" src="{{asset('sd_img/next.png')}}"></li>
            </ul>
        </div>
    </div>
    <div class="more-privilege buy-goods" style="display: none;">
        @foreach($member_info_arr['goods_list'] as $goods)
            <div class="privilege-wrap">
                <ul class="m-privilege-list">
                    <form action="{{ asset('/order/showPay') }}" method="post">
                        <input type="hidden" name="goods" value="{{ $goods->sku_id }}">
                    </form>

                    <li class="image"><img src="{{ $goods->main_image }}"></li>

                    <div class="content">
                        <li>{{ $goods->sku_name }}</li>
                        <li>{{ $goods->grade_name }}</li>
                    </div>

                    @if($goods->grade == $member_info_arr['grade_code'])
                        @if($goods->is_virtual == 1)
                            <div class="option1 getImmediately">立即领取</div>
                        @else
                            <div class="option1 buyImmediately">立即购买</div>
                        @endif
                    @else
                        @if($goods->is_virtual == 1)
                            <div class="option2 unableGet">暂不可领</div>
                        @else
                            <div class="option2 unableBuy">暂不可买</div>
                        @endif
                    @endif
                </ul>
            </div>
        @endforeach
    </div>
    {{-- -------------------------------旧版本--------------------------------- --}}
    {{--<div class="head-wrap">
        <div class="steward-welcome flex-between">

            @if($member_info_arr['is_show_expire_notice'])
                <ul class="steward-welcome-r font-14">
                    <li class="color-50">亲爱的
                        <span class="grade-name">{{ $member_info_arr['grade_name'] }}</span>
                        会员
                    </li>

                    <li class="color-80">您的VIP会员将在
                        <span class="grade-expire-time">{{ $member_info_arr['diff_now_grade_expire_time'] }}</span>
                        过期
                    </li>
                </ul>
            @else
                <ul class="steward-welcome-r font-14">
                   --}}{{-- <li class="color-50">亲爱的
                        <span class="grade-name">{{ $member_info_arr['grade_name'] }}</span>
                        会员
                    </li>--}}{{--

                    <li style="padding-left: 10px;" class="color-80">您来了老板！！<br/>小水管家已经期盼您很久了，有什么需要小水帮忙的，可以随时召唤我！</li>
                </ul>
            @endif

            --}}{{--<a href="{{asset('member/detail')}}">
                <ul class="bar1" id="bar">
                    <li><img src="{{ $member_info_arr['avatar'] }}" alt=""></li>
                    <li>{{ $member_info_arr['grade_name'] }}</li>
                </ul>
            </a>--}}{{--
        </div>
    </div>--}}

    {{--<div class="my-privilege">
        <p>我的特权</p>
        <ul class="privilege-list font-12">
            <a href="{{asset('/member/privilege_detail')}}">
                <li>
                    <img src="{{asset('sd_img/mymember_privilege1.png')}}" alt=""><span>尊享优惠</span>
                </li>
            </a>

            <a href="{{asset('/member/privilege_detail')}}">
                <li><img src="{{asset('sd_img/mymember_privilege2.png')}}" alt=""><span>尊享商品</span></li>
            </a>

            <a href="{{asset('/member/privilege_detail')}}">
                <li><img src="{{asset('sd_img/mymember_privilege3.png')}}" alt=""><span>尊享活动</span></li>
            </a>
        </ul>

        <ul class="privilege-list font-12">
            <a href="{{asset('/member/privilege_detail')}}">
                <li><img src="{{asset('sd_img/mymember_privilege4.png')}}" alt=""><span>尊享推荐</span></li>
            </a>

            <a href="{{asset('/member/privilege_detail')}}">
                <li><img src="{{asset('sd_img/mymember_privilege5.png')}}" alt=""><span>尊享兑换</span></li>
            </a>

            <a href="{{asset('/member/privilege_detail')}}">
                <li><img src="{{asset('sd_img/mymember_privilege6.png')}}" alt=""><span>专属管家</span></li>
            </a>
        </ul>
    </div>--}}

    {{--<div>
        <div class="carousel slide" id="mycarousel" data-pase="hover" data-ride="carousel" data-interval="3000">
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
    </div>--}}

    {{--<div>
        <div class="carousel slide" id="mycarousel" data-pase="hover" data-ride="carousel" data-interval="3000">
            <ol class="carousel-indicators" style="bottom: -6px;width: auto;left: 115%">
                @foreach($member_info_arr['advert'] as $advert)
                    @if ($loop->first)
                        <li data-target="#mycarousel" data-slide-to="0" class="active"></li>
                    @else
                        <li data-target="#mycarousel" data-slide-to="{{ $loop->index }}"></li>
                    @endif
                @endforeach
            </ol>

            <div class="carousel-inner">
                @foreach($member_info_arr['advert'] as $advert)
                    @if ($loop->first)
                        <div class="item active">
                            <a href="{{ $advert->out_url }}"><img src="@showImg($advert->images)"></a>
                        </div>
                    @else
                        <div class="item">
                            <a href="{{ $advert->out_url }}"><img src="@showImg($advert->images)"></a>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>--}}
    {{--<div class="more-privilege">
        <p>更多特权</p>

        @foreach($member_info_arr['goods_list'] as $goods)
            <div class="privilege-wrap">
                <ul class="m-privilege-list">
                    <form action="{{ asset('/order/showPay') }}" method="post">
                        <input type="hidden" name="goods" value="{{ $goods->sku_id }}">
                    </form>

                    <li class="image"><img src="{{ $goods->main_image }}"></li>

                    <div class="content">
                        <li>{{ $goods->sku_name }}</li>
                        <li>{{ $goods->grade_name }}</li>
                    </div>

                    @if($goods->grade == $member_info_arr['grade_code'])
                        @if($goods->is_virtual == 1)
                            <div class="option1 getImmediately">立即领取</div>
                        @else
                            <div class="option1 buyImmediately">立即购买</div>
                        @endif
                    @else
                        @if($goods->is_virtual == 1)
                            <div class="option2 unableGet">暂不可领</div>
                        @else
                            <div class="option2 unableBuy">暂不可买</div>
                        @endif
                    @endif
                </ul>
            </div>
        @endforeach
    </div>--}}

    {{-- -------------------------------旧版本结束--------------------------------- --}}
    </body>
@endsection

@section('js')
    {{--<script src="{{asset('sd_js/jgestures.min.js')}}"></script>--}}
    <script type="text/javascript" src="{{asset('/sd_js/swiper.min.js')}}"></script>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script type="text/javascript">
        //分享相关数据——start
        var appId = '{{$signPackage['appId']}}';
        var timestamp = '{{$signPackage['timestamp']}}';
        var nonceStr = '{{$signPackage['nonceStr']}}';
        var signature = '{{$signPackage['signature']}}';
        var link = window.location;
        var title = '管家甄选优质商品及服务，享低价，定制生活，尽情享受。';
        var imgUrl = '{{asset('sd_img/share_Index.png')}}';
        var desc = '更多优质商品及服务可畅享85折。优惠价格，管家专享。';

        wx.config({
            debug: false,
            appId: appId,
            timestamp: timestamp,
            nonceStr: nonceStr,
            signature: signature,
            jsApiList: [
                // 所有要调用的 API 都要加到这个列表中
                'onMenuShareTimeline',
                'onMenuShareAppMessage'
            ]
        });
        wx.ready(function () {
            // 在这里调用 API
            wx.onMenuShareTimeline({
                title: title, // 分享标题
                imgUrl: imgUrl, // 分享图标
                link:link,
                success: function (msg) {
                    // 用户确认分享后执行的回调函数
                },
                cancel: function (msg) {
                    // 用户取消分享后执行的回调函数
                },
                fail: function () {
                    // 用户取消分享后执行的回调函数
                    alert("分享失败，请稍后再试");
                }
            });


            wx.onMenuShareAppMessage({
                title: title, // 分享标题
                desc: desc,//'分享送好礼', // 分享描述
                imgUrl: imgUrl, // 分享图标
                link:link,
                type: '', // 分享类型,music、video或link，不填默认为link
                dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
                success: function (msg) {
                    // 用户确认分享后执行的回调函数
                },
                cancel: function (msg) {
                    // 用户取消分享后执行的回调函数
                },
                fail: function () {
                    // 用户取消分享后执行的回调函数
                    alert("分享失败，请稍后再试");
                }
            });
        });

        //分享相关数据——end


        // 给轮播添加触屏支持
        $(document).ready(function () {
            $('#mycarousel').on('swiperight swiperightup swiperightdown', function () {
                $("#mycarousel").carousel('prev');
            }).on('swipeleft swipeleftup swipeleftdown', function () {
                $("#mycarousel").carousel('next');
            })
        });

        // 隐藏bar
        $(window).on('scroll', function () {
            var obj = $(document).scrollTop();
            if (obj >= 185) {
                $('#bar').attr('class', 'bar2');
            }
            else {
                $('#bar').attr('class', 'bar1');
            }
        });

        // 动态控制第一个与最后一个list的样式
        /*$('.privilege-wrap').last().css('margin-bottom', '40px').first().css('border-top', '1px solid rgb(220,220,220)');*/
        $('.getImmediately').on('click', getImmediately);
        $('.buyImmediately').on('click', buyImmediately);
        $('.unableGet').on('click', unableGet);
        $('.unableBuy').on('click', unableBuy);

        function getImmediately() {
            // ...
        }

        function buyImmediately() {
            $(this).parent().children().submit();
        }

        function unableBuy() {
            message('会员等级不足, 无法选购', 1000);
        }

        function unableGet() {
            message('会员等级不足, 无法免费领取', 1000);
        }

        /*中间滑动*/
        var swiper = new Swiper('.swiper-container', {

            pagination: '.swiper-pagination',
            paginationClickable: true,
            spaceBetween:-100
        });

        /*购买记录*/
        $('#list').on('click', function () {
            $(this).toggleClass('shadow');
            $(this).find('img').toggleClass('rotate');
            $('.buy-list').slideToggle(800);
        });
        /*更多特权*/
        $('#g-list').on('click', function () {
            var t = $(window).scrollTop();
            $('body,html').animate({'scrollTop':t+150},700);
            $('#g-list li:first').css({'width':'100%','display': 'fixed','justify-content':'space-between','align-items': 'center'});
            $(this).toggleClass('shadow');
            $(this).find('img').toggleClass('rotate');
            $('.buy-goods').slideToggle(800);

        });
    </script>
@endsection



