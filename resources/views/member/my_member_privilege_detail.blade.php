@extends('inheritance')

@section('title')
    特权详情
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('sd_css/common.css')}}">
    <link rel="stylesheet" href="{{asset('sd_css/mui.min.css')}}">

    <style>
        .mui-scroll-wrapper{
            height: 44px!important;
            box-shadow: 0 1px 1px rgba(50,50,50,0.4);
        }
        .mui-scroll{
            background-color: white;
            height: 44px!important;
        }
        .mui-scroll a{
            box-sizing: border-box;
            height: 40px;
            margin-top: 4px;
            color: #f53a3a!important;
            text-decoration: none;
            font-size: 12px;
        }
        .mui-scroll .mui-active{
            border-bottom: 2px solid #f53a3a!important;
        }
        .privilege-head{
            width: 100%;
        }
        .privilege-head img{
            width: 100%;
        }
        .f1-title,.f2-title{
            width: 100%;
            height: 44px;
            padding-left: 10px;
            border-bottom: 1px solid rgb(220,220,220);
            font-size: 14px;
            color: rgb(80,80,80);
            line-height: 44px;
        }
        .f1-content{
            width: 100%;
            height: 90px;
            padding-left: 20px;
            padding-right: 20px;
        }
        .f2{
            padding-bottom: 20px;
        }
        .f2-title-1{
            padding-left: 10px;
            margin-top: 12px;
        }
        .f2-content{
            padding-left: 10px;
            margin-top: 10px;
            line-height: 24px;
        }
    </style>
@endsection

@section('content')
<body >

<div class="privilege-head">
    <img src="{{asset('sd_img/privilege_detail.png')}}" alt="">
</div>
<div class="mui-scroll-wrapper mui-slider-indicator mui-segmented-control mui-segmented-control-inverted">
    <div class="mui-scroll">
        <a class="mui-control-item mui-active" href="#tab1">
            尊享优惠
        </a>
        <a class="mui-control-item" href="#tab2">
            尊享商品
        </a>
        <a class="mui-control-item" href="#tab3">
            尊享活动
        </a>
        <a class="mui-control-item" href="#tab4">
            尊享推荐
        </a>

        <a class="mui-control-item" href="#tab5">
            专属礼品
        </a>
        <a class="mui-control-item" href="#tab6">
            专属管家
        </a>
    </div>
</div>
<div class="tab marginTop mui-control-content mui-active" id="tab1">
    <ul class="f1 b-white">
        <li class="f1-title">尊享优惠</li>
        <li class="font-12 color-80 f1-content flex-center">
            水丁网小水管家服务所有实物商品尊享95折优惠，部分特殊商品除外
        </li>
    </ul>
    <ul class="f2 b-white marginTop">
        <li class="f2-title">权益介绍</li>
        <li class="font-14 color-80 f2-title-1">1、权益说明</li>
        <li class="font-12 color-100 f2-content">水丁网小水管家服务所有实物商品尊享95折优惠，部分特殊商品除外</li>
        <li class="font-14 color-80 f2-title-1">2、使用说明</li>
        <li class="font-12 color-100 f2-content">购买小水管家服务后，商品价格直接对应优惠后价格，直接下单购买即可。</li>
    </ul>
</div>
<div class="tab marginTop mui-control-content" id="tab2">
    <ul class="f1 b-white">
        <li class="f1-title">尊享商品</li>
        <li class="font-12 color-80 f1-content flex-center">
            专属好物，优先购，更实惠专属定制产品

        </li>
    </ul>
    <ul class="f2 b-white marginTop">
        <li class="f2-title">权益介绍</li>
        <li class="font-14 color-80 f2-title-1">1、权益说明</li>
        <li class="font-12 color-100 f2-content">专属定制产品，数量有优先购买更实惠</li>
        <li class="font-14 color-80 f2-title-1">2、使用说明</li>
        <li class="font-12 color-100 f2-content">权益商品、国际品牌、特殊定制产品，限量版商品等。</li>
    </ul>
</div>
<div class="tab marginTop mui-control-content" id="tab3">
    <ul class="f1 b-white">
        <li class="f1-title">尊享活动</li>
        <li class="font-12 color-80 f1-content flex-center">
            专属好物，优先购，更实惠专属定制产品

        </li>
    </ul>
    <ul class="f2 b-white marginTop">
        <li class="f2-title">权益介绍</li>
        <li class="font-14 color-80 f2-title-1">1、权益说明</li>
        <li class="font-12 color-100 f2-content">专属活动特别邀请，每年尊享3次。</li>
        <li class="font-14 color-80 f2-title-1">2、使用说明</li>
        <li class="font-12 color-100 f2-content">线下主题活动、线上讨论活动以及户外出游活动等，具体活动上线后会通过站内消息像您发送邀请，您也可以在个人中心活动中尽行查看。</li>
    </ul>
</div>
<div class="tab marginTop mui-control-content" id="tab4">
    <ul class="f1 b-white">
        <li class="f1-title">尊享活动</li>
        <li class="font-12 color-80 f1-content flex-center">
            尊享商品可推荐，尊享活动课推荐

        </li>
    </ul>
    <ul class="f2 b-white marginTop">
        <li class="f2-title">权益介绍</li>
        <li class="font-14 color-80 f2-title-1">1、权益说明</li>
        <li class="font-12 color-100 f2-content">推荐与朋友一起分享好物</li>
        <li class="font-14 color-80 f2-title-1">2、使用说明</li>
        <li class="font-12 color-100 f2-content">
            可以把商品链接发送给朋友或者是分享到朋友圈等，通过链接进来即可购买或参加活动
        </li>
    </ul>
</div>
<div class="tab marginTop mui-control-content" id="tab5">
    <ul class="f1 b-white">
        <li class="f1-title">专属礼品</li>
        <li class="font-12 color-80 f1-content flex-center">
            跨界专享特权，打车劵、餐饮优惠券等

        </li>
    </ul>
    <ul class="f2 b-white marginTop">
        <li class="f2-title">权益介绍</li>
        <li class="font-14 color-80 f2-title-1">1、权益说明</li>
        <li class="font-12 color-100 f2-content">可不定期领取跨界优惠券等（如酒店优惠券、餐饮优惠券等）</li>
        <li class="font-14 color-80 f2-title-1">2、使用说明</li>
        <li class="font-12 color-100 f2-content">
            购买管家服务后可直接在管家中心进行领取所需的卡劵，领取后可直接在线上消费或者线下消费使用。
        </li>
    </ul>
</div>
<div class="tab marginTop mui-control-content" id="tab6">
    <ul class="f1 b-white">
        <li class="f1-title">专属管家</li>
        <li class="font-12 color-80 f1-content flex-center">
            专享管家24小时服务。

        </li>
    </ul>
    <ul class="f2 b-white marginTop">
        <li class="f2-title">权益介绍</li>
        <li class="font-14 color-80 f2-title-1">1、权益说明</li>
        <li class="font-12 color-100 f2-content">24小时专属管家服务</li>
        <li class="font-14 color-80 f2-title-1">2、使用说明</li>
        <li class="font-12 color-100 f2-content">
            为您解决您在购物中产生的商品咨询、及商品质量退换货等所有问题。
        </li>
    </ul>
</div>
</body>
@endsection

@section('js')

    <script src="{{asset('sd_js/jquery.min.js')}}"></script>
    <script src="{{asset('sd_js/mui.min.js')}}"></script>

    <script type="text/javascript">
        mui.init()
    </script>

    <script>
        //    导航栏配置项
        mui('.mui-scroll-wrapper').scroll({
            scrollY: false,
            scrollX: true,
            startX: 0,
            startY: 0,
            indicators: false,
            deceleration:0.0006,
            bounce: true

        });

        //点击移动导航条
        $('.mui-scroll a:eq(1)').on('click',function(){
            $('.mui-scroll').css({
                'transform':'translateX(-73px)',
                '-moz-transform':'translateX(-73px)',
                '-webkit-transform':'translateX(-73px)',
                '-o-transform':'translateX(-73px)',
                'transition':'all 1s cubic-bezier(0.165, 0.84, 0.44, 1)',
                '-moz-transition':'all 1s cubic-bezier(0.165, 0.84, 0.44, 1)',
                '-webkit-transition':'all 1s cubic-bezier(0.165, 0.84, 0.44, 1)',
                '-0-transition':'all 1s cubic-bezier(0.165, 0.84, 0.44, 1)'
            })
        });

    </script>


@endsection



