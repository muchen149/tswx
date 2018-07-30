@extends('inheritance')

@section('title')
    我的会员
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('sd_css/mui.min.css')}}">
    <link rel="stylesheet" href="{{asset('sd_css/common.css')}}">
    <style>
        body{
            background-color: white;
        }
        .container-bg{
            width: 100%;
        }
        .container-bg img{
            width: 100%;
        }
        .content{
            width: 100%;
            margin: 0px auto 0;
        }
        .content img {
            width:100%;
        }
        .btn{
            width: 80%;
            height: 45px;
            margin: 15px auto 0;
            border-radius: 6px;
            background-color: #c5a896;
            color: #fffefd;
        }
        .btn a{
            color: white;
        }
        .luosuo{
            width: 80%;
            margin: 30px auto ;
            text-align: left;
            line-height: 24px;
        }
        .luosuo div {
            font-size: 14px;
            font-weight: 600;
            font-family: "微软雅黑";
            margin-left: -14px;
        }
        .luosuo li {
            list-style-type: disc;
            color: #919191;
        }
    </style>

@endsection

@section('content')
<body >
    <div class="container-bg">
        <img src="{{asset('sd_img/scan_get_member.jpg')}}" alt="">
    </div>
    <ul class="content">
        {{--<li>恭喜</li>
        <li>获得1个月小丁管家服务</li>--}}
        <img src="{{asset('sd_img/scan_get_time.jpg')}}" alt="">
    </ul>
    <a href="">
        <li class="btn flex-center font-14">
            立即领取
        </li>
    </a>
    <ul class="luosuo font-12 color-50">
        <div>权益介绍</div>
        <li>权益有效期：管家服务有效期为30日(点击领取进入您的会员卡包)</li>
        <li>参与要求：同一账户同一时间只能绑定并使用同一级会员管家服务</li>
    </ul>
</body>
@endsection

@section('js')

    <script src="{{asset('sd_js/jquery.min.js')}}"></script>

@endsection



