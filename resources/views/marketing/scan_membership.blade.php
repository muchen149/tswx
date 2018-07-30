@extends('inheritance')
@section('title', '扫码获得会员')
@section('css')

    <style>
        body {
            background-color: white;
        }

        .container-bg {
            width: 100%;
        }

        .container-bg img {
            width: 100%;
        }

        .content {
            width: 80%;
            margin: 0 auto 0;
            padding: 20px 0;
            -border: 3px dotted #f55d54;
            text-align: center;
            color: #cd8c00;
        }

        .btn {
            width: 80%;
            height: 45px;
            margin: 0 auto 0;
            border-radius: 6px;
            background-color: #c5a896;
            color: #fffefd;
            font-weight: 600;

        }

        .luosuo {
            width: 80%;
            margin: 30px auto;
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
    <body>
    <div class="container-bg">
        <img src="{{ asset('sd_img/scan_get_member.jpg') }}">
    </div>

    <ul class="content">
        <li style="font-weight: 600;font-size: 20px">恭喜您获得</li>
        <li style="font-weight: 600;font-size: 20px">{{ $scanData['grade_name'] }} 服务{{ $scanData['exp_date'] }}{{ $scanData['exp_date_name'] }} </li>
    </ul>

    <li class="btn flex-center font-16">
        立即激活
    </li>

    <ul class="luosuo font-12 color-50">
        <div>权益介绍</div>
        <li>管家服务有效期为 {{ $scanData['exp_date'] }} {{ $scanData['exp_date_name'] }}（点击立即领取进入您的服务卡包）</li>
        <li>同一账户同一时间只能绑定并使用同一级管家服务</li>
    </ul>

    <form id="card_form" action="{{ asset('/membership/getCardToPackage') }}" method="post">
        <input type="hidden" name="mid" value="{{ $scanData['member_id'] }}">
        <input type="hidden" name="aid" value="{{ $scanData['activity_id'] }}">
        <input type="hidden" name="cid" value="{{ $scanData['card_id'] }}">
    </form>
    </body>
@endsection

@section('js')
    <script type="text/javascript">
        $(function () {
            $('.btn').on('click', function () {
                $('#card_form').submit();
            })
        })
    </script>
@endsection



