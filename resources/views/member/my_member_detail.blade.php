@extends('inheritance')

{{--@section('title')
    会员详情
@endsection--}}

@section('css')
{{--    <link rel="stylesheet" href="{{asset('sd_css/bootstrap.min.css')}}">--}}
<title>{!!config('constant')['comTitle']['title']!!}</title>
    <link rel="stylesheet" href="{{asset('sd_css/common.css')}}">
    <style>
        .m-option a{
            color: rgb(80,80,80);
        }
        .header{
            width: 100%;
            height: 160px;
            background: url("../../sd_img/member-bg2.jpg") center no-repeat;
            background-size: 100% 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .header ul{
            margin-top: 28px;
            margin-bottom: 8px;
        }
        .header ul li:first-child{
            width: 80px;
            height: 80px;
            border-radius: 50px;
            overflow: hidden;
            border: 2px solid white;
        }

        .m-option ul{
            width: 95%;
            margin: 10px auto 0;
            height: 50px;
            background-color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .m-option a{
            color: rgb(80,80,80);
        }
        #list img{
            transition: all 0.8s;
        }
        .rotate{
            transform:rotate(90deg);
        }
        .buy-list{
            width: 100%;
            margin-top: 6px;
            background-color: rgb(250,250,250);
            margin-bottom: 50px;
        }
        .buy-list ul{
            width: 95%;
            height: 45px;
            margin: 0 auto;
            background-color: rgb(250,250,250);
        }
        .shadow{
            box-shadow: 0 2px 2px rgba(80,80,80,0.4);
        }
        .a{
            transition: transform 0.5s ease;
        }
    </style>

@endsection

@section('content')
<body >
<div class="header color-white font-14">
    <ul>
        <li> @if(!empty(Auth::user()->avatar))
                <img id="avatar"
                     src="{{Auth::user()->avatar}}"
                     style="width: 80px;"/>
            @else
                <img src='/img/default_user_portrait.gif' style="width: 80px;"/>
            @endif
        </li>
    </ul>
    <li>bac_xyz</li>
</div>
<div class="m-option">
    <div class="b-white">
        <ul>
            <li>会员有效期至</li>
            <li>2048-01-01</li>
        </ul>
    </div>
    <div class="b-white">
        <a href="{{asset('/member/pay_privilege')}}">
            <ul>
                <li>续费</li>
                <li><img src="{{asset('sd_img/next.png')}}" alt="" class="size-30"></li>
            </ul>
        </a>

    </div>
    <div class="b-white">
        <a href="{{asset('/member/buy_privilege')}}">
            <ul>
                <li>提升级别</li>
                <li><img src="{{asset('sd_img/next.png')}}" alt="" class="size-30"></li>
            </ul>
        </a>
    </div>

    <div id="list" style="background-color: white;z-index: 9999">
        <ul>
            <li>购买记录</li>
            <li ><img width="30px" src="{{asset('sd_img/next.png')}}" alt=""></li>
        </ul>
    </div>
    <div class="buy-list" style="display: none">
        <ul class="flex-between font-12 color-80 border-b">
            <li>A级会员</li>
            <li>1个月</li>
            <li>2017-06-08</li>
        </ul>
        <ul class="flex-between font-12 color-80 border-b">
            <li>A级会员</li>
            <li>1个月</li>
            <li>2017-06-08</li>
        </ul>
        <ul class="flex-between font-12 color-80 border-b">
            <li>C级会员</li>
            <li>1年</li>
            <li>2017-06-08</li>
        </ul>
        <ul class="flex-between font-12 color-80 border-b">
            <li>C级会员</li>
            <li>1年</li>
            <li>2017-06-08</li>
        </ul>
        <ul class="flex-between font-12 color-80 border-b">
            <li>A级会员</li>
            <li>1个月</li>
            <li>2017-06-08</li>
        </ul>
        <ul class="flex-between font-12 color-80 border-b">
            <li>C级会员</li>
            <li>1年</li>
            <li>2017-06-08</li>
        </ul>
        <ul class="flex-between font-12 color-80 border-b">
            <li>C级会员</li>
            <li>1年</li>
            <li>2017-06-08</li>
        </ul>
    </div>
</div>
</body>
@endsection

@section('js')

    <script src="{{asset('sd_js/jgestures.min.js')}}"></script>
    <script src="{{asset('sd_js/jquery.min.js')}}"></script>

    <script>
        $('#list').on('click',function(){
            $(this).toggleClass('shadow');
            $(this).find('img').toggleClass('rotate');
            $('.buy-list').slideToggle(800);
        })
    </script>

@endsection



