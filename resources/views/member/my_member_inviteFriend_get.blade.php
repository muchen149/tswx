@extends('inheritance')

@section('title')
    去领奖
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('sd_css/common.css')}}">
    <style>
        body{
            background: #f65950;
        }
        .inviteFriend-get{
            width: 100%;
        }
        .inviteFriend-get img{
            width: 100%;
        }
        .inviteFriend-avator{
            width: 50px;
            height: 50px;
            margin: 12px auto 0;
            border-radius: 30px;
            border: 2px solid white;
            overflow: hidden;
        }
        .inviteFriend-avator img{
            width: 50px;
            height: 50px;
        }
        .inviteFriend-text{
            width: 100%;
            text-align: center;
        }
        .inviteFriend-get-btn{
            width: 160px;
            height: 36px;
            margin: 20px auto 0;
            border-radius: 6px;
            background: #ffe466;
        }
        .inviteFriend-instruction{
            width: 95%;
            margin: 10px auto 0;
            line-height: 24px;
        }
    </style>
@endsection

@section('content')
<body >

    <div class="inviteFriend-get">
        <img src="{{asset('sd_img/invitedFriend-get-bg.jpg')}}" alt="">
    </div>
    <ul class="inviteFriend-avator">
        <li><img src="{{$member->avatar}}" alt=""></li>
    </ul>
    <ul class="inviteFriend-text">
        <li class="font-14 color-white">{{$member->nick_name}} 给你送了一月TA的专享管家服务</li>
    </ul>
    <ul class="inviteFriend-get-btn flex-center">
        <li class="font-14"><a href="{{asset('personal/index?member_id='.$member_id)}}">立即领取</a></li>
    </ul>

    <ul class="inviteFriend-instruction font-12 color-white">
        <li>活动规则:</li>
        <li>1、仅限水丁管家从未注册过的新用户；</li>
        <li>2、所获奖品请在个人中心中查看；</li>
        <li>3、最终解释权归水丁管家所有，如有疑问请及时联系平台：010-56266744</li>
    </ul>


</body>
@endsection


@section('js')

@endsection



