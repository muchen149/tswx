@extends('inheritance')

@section('title')
    管家中心
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('sd_css/common.css')}}">
    <link rel="stylesheet" href="{{asset('sd_css/mui.min.css')}}">
    <style>
        div{
            width: 80%;
            margin: 100px auto 0;
        }
        div li{
            width: 120px;
            height: 46px;
            border: 1px solid rgb(220,220,220);
        }

    </style>
@endsection
@section('content')
<body>
<div class="flex-between">
    <a href="{{asset('member/my_member')}}"><li class="font-14 color-50 b-white flex-center">true</li></a>
    <a href="{{asset('member/my_member_buy_privilege')}}"><li class="font-14 color-50 b-white flex-center">false</li></a>
</div>
</body>
@endsection