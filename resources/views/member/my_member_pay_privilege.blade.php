@extends('inheritance')

@section('title')
    购买会员
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('sd_css/common.css')}}">

    <style>
        .pay-privilege{
            width: 100%;
        }
        .pay-privilege ul{
            width: 95%;
            height: 50px;
            margin: 0px auto;
        }
        .pay-privilege div:first-child{
            margin-bottom: 10px;
        }
        .pay-privilege .set_paystyle{
            width: 65%;
            margin: 0 auto;
        }
        .pay-privilege .set_paystyle li{
            margin-bottom: 10px;
        }
        .pay-privilege .set_paystyle img{
            width: 42px;
            margin-bottom: 4px;
        }
        .unselect{
            color: rgba(80,80,80,0.5);
        }


        /*sb start*/
        .steward-welcome{
            position: relative;
            width: 85%;
            height: 145px;
            margin: 20px auto 10px;
        }
        .steward-welcome-l img{
            width: 100px;
        }
        .steward-welcome-r{
            width: 63%;
            text-align: left;
        }
        .steward-welcome-r li:first-child{
            margin-bottom: 8px;
        }
        /*sb end*/


        /*服务协议*/
        .arguement{
            margin-top: 20px;
        }
        .arguement img{
            width: 18px;
            margin-right: 5px;
        }
        .arguement a{
            color: dodgerblue;
        }
        .arguement a:active{
            color: dodgerblue;
        }
        .arguement a:visited{
            color: dodgerblue;
        }

        /*支付按钮*/
        .pay-privilege-btn{
            width: 85%;
            height: 44px;
            margin: 18px auto 30px;
            background-color: #f53a3a;
            border-radius: 8px;
        }
    </style>

@endsection

@section('content')
<body >

<div class=" steward steward-welcome flex-between">
    <ul class="steward-welcome-l">
        <li>@if(!empty(Auth::user()->avatar))
                <img id="avatar"
                     src="{{Auth::user()->avatar}}"
                     style="width: 80px;"/>
            @else
                <img src='{{asset('img/default_user_portrait.gif')}}' style="width: 80px;"/>
            @endif</li>
    </ul>
    <ul class="steward-welcome-r font-14 ">
        <li class="color-50">亲爱的用户,我是sb!</li>
        <li class="color-80">感谢您的购买，精彩即将开始。</li>
    </ul>

</div>
<div class="pay-privilege">
    <div class="list font-14 b-white ">
        <ul class="flex-between">
            <li class="color-80">管家类型</li>
            <li class="color-80 font-ber"> Undefined</li>
        </ul>
    </div>

    <div class="list font-14 b-white" >
        <ul class="flex-between border-b">
            <li class="color-80">支付金额</li>
            <li class="color-80 font-ber">0.00</li>
        </ul>
    </div>

    <div class="list font-14 b-white">
        <ul class="flex-between ">
            <li class="font-14 color-80">支付方式</li>
        </ul>
    </div>

    <div class="b-white">
        <div class="set_paystyle flex-between font-12 color-80">
            <li class="flex-column-between"><img src="{{asset('sd_img/pay_style_yue1.png')}}" alt="" ><img src="{{asset('sd_img/pay_style_yue2.png')}}" alt="" style="display: none" ><span class="unselect">余额支付</span></li>
            <li class="flex-column-between"><img src="{{asset('sd_img/pay_style_wx1.png')}}" alt=""><img src="{{asset('sd_img/pay_style_wx2.png')}}" alt="" style="display: none"><span class="unselect">微信支付支付</span></li>
        </div>
    </div>
</div>
<div class="arguement flex-center font-12 color-80">
    <img src="{{asset('sd_img/agree.png')}}" alt="">
    <img src="{{asset('sd_img/unagree.png')}}" alt="" style="display: none">
    同意<a href="pay_arguement.html">《水丁管家会员服务协议》</a>
</div>
<div class="pay-privilege-btn flex-center color-white font-14">立即支付</div>

</body>
@endsection

@section('js')

    <script src="{{asset('sd_js/jquery.min.js')}}"></script>
    <script>

        $('.set_paystyle li').click(function(e){
            var target= e.currentTarget;
            $(target).find('img').toggle();
            $(target).find('span').toggleClass();
        });

        $('.arguement').click(function(e){
            $(this).find('img').toggle();
        });
    </script>

@endsection



