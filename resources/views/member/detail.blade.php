@extends('inheritance')

@section('title', '会员详情')

@section('css')
    <style type="text/css">
        .m-option a {
            color: rgb(80, 80, 80);
        }

        .header {
            width: 100%;
            height: 160px;
            background: url("../../sd_img/member-bg2.jpg") center no-repeat;
            background-size: 100% 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .header ul {
            margin-top: 28px;
            margin-bottom: 8px;
        }

        .header ul li:first-child {
            width: 80px;
            height: 80px;
            border-radius: 50px;
            overflow: hidden;
            border: 2px solid white;
        }

        #avatar {
            width: 75px;
        }

        .m-option ul {
            width: 95%;
            margin: 10px auto 0;
            height: 50px;
            background-color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
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
            margin-bottom: 50px;
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
            box-shadow: 0 2px 2px rgba(80, 80, 80, 0.4);
        }

        .a {
            transition: transform 0.5s ease;
        }
    </style>

@endsection

@section('content')
    <body>
    <div class="header color-white font-14">
        <ul>
            <li>
                @if(!empty($member['avatar']))
                    <img id="avatar" src="{{ $member['avatar'] }}"/>
                @else
                    <img id="avatar" src='{{ asset('img/default_user_portrait.gif') }}'/>
                @endif
            </li>
        </ul>
        <li>{{ $member['nick_name'] or $member['member_name'] }}</li>
    </div>
    <div class="m-option">
        <div class="b-white">
            @if($member['grade'] == 10)
                <ul>
                    <li>会员等级</li>
                    <li>普通会员</li>
                </ul>
            @else
                <ul>
                    <li>会员有效期</li>
                    <li>{{ date('Y-m-d H:i:s', $member['grade_expire_time']) }}</li>
                </ul>
            @endif
        </div>
        <div class="b-white">
            <a href="{{ asset('member/buy/' . $member['grade']) }}">
                <ul>
                    <li>续费</li>
                    <li><img src="{{asset('sd_img/next.png')}}" class="size-30"></li>
                </ul>
            </a>

        </div>
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
            @foreach($member['membership_record'] as $record)
                <ul class="font-12 color-80 border-b">
                    <li class="grade-column">{{ $record->class_name }}</li>
                    <li class="time-column">{{ $record->exp_date }}{{ $record->exp_date_name }}</li>
                    <li class="price-column"> ¥ {{ $record->price }}</li>
                    <li class="date-column">{{ date('Y-m-d H:i:s', $record->updated_at) }}</li>
                </ul>
            @endforeach
        </div>
    </div>
    </body>
@endsection

@section('js')

    <script src="{{asset('sd_js/jgestures.min.js')}}"></script>
    <script src="{{asset('sd_js/jquery.min.js')}}"></script>

    <script>
        $('#list').on('click', function () {
            $(this).toggleClass('shadow');
            $(this).find('img').toggleClass('rotate');
            $('.buy-list').slideToggle(800);
        })
    </script>

@endsection



