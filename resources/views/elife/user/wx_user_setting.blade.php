@extends('inheritance')

@section('title')
    账号设置
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('elife_css/mui.min.css')}}">
    <link rel="stylesheet" href="{{asset('elife_css/common.css')}}">
    <script src="{{asset('sd_js/font_wvum.js')}}"></script>

    <style>
        .head .nav{
            position: fixed;
            top: 0;
            padding: 0 4px;
            line-height: 40px;
            width:100%;
            z-index: 999999;
            font-family: "微软雅黑";
        }
        .head .nav .return{
            display: inline-block;
            width: 40%;
        }

        .me-user-information-item-wrap {
            width: 100%;
            background-color: white;
        }

        .me-user-information-item {
            position: relative;
            width: 96%;
            margin-left: auto;
            margin-right: auto;
            height: 50px;

        }

        .me-user-information-item .itemLeft .itemText {
            margin-left: 24px;
        }

    </style>
@endsection

@section('content')
    <body>
    <div class="head">
        <div class="nav b-white">
            <a class="return font-14 color-80" href="{{asset('elife/personal/index')}}"><img src="{{asset('sd_img/next.png')}}" alt="" class="size-26" style="transform:rotate(180deg);"></a>
            <a class="title font-18 color-80" href="#">个人设置</a>
        </div>
    </div>
    <div class="me-user-information-item-wrap borderTop borderBottom" style="margin-top:50px;">
        <a href="{{asset('/elife/personal/userInfo')}}">
            <div class="me-user-information-item flex-between b-white marginTop ">
                <ul class="flex-between itemLeft">
                    <li>
                        <svg class="icon font-26" style="color: #fa7564" aria-hidden="true">
                            <use xlink:href="#icon-iconfontwode"></use>
                        </svg>
                    </li>
                    <li class="font-14 color-80 itemText">个人信息</li>
                </ul>
                <ul class="itemRight">
                    <li>
                        <svg class="icon font-26 color-100" aria-hidden="true">
                            <use xlink:href="#icon-icon07"></use>
                        </svg>
                    </li>

                </ul>
            </div>
        </a>
    </div>

    <div class="me-user-information-item-wrap borderTop">
        <a href="{{asset('/elife/personal/addressList')}}">
            <div class="me-user-information-item flex-between b-white marginTop ">
                <ul class="flex-between itemLeft">
                    <li>
                        <svg class="icon font-26 color-f53a3a" style="color: #f0a700" aria-hidden="true">
                            <use xlink:href="#icon-site"></use>
                        </svg>
                    </li>
                    <li class="font-14 color-80 itemText">我的收货地址</li>
                </ul>
                <ul class="itemRight">

                    <li>
                        <svg class="icon font-26 color-100" aria-hidden="true">
                            <use xlink:href="#icon-icon07"></use>
                        </svg>
                    </li>

                </ul>
            </div>
        </a>
    </div>

    <div class="me-user-information-item-wrap borderTop">
        <a href="{{asset('/elife/personal/explain')}}">
            <div class="me-user-information-item flex-between b-white marginTop ">
                <ul class="flex-between itemLeft">
                    <li>
                        <svg class="icon font-26 color-f53a3a" style="color: #f0a700" aria-hidden="true">
                            <use xlink:href="#icon-bangzhu1"></use>
                        </svg>
                    </li>
                    <li class="font-14 color-80 itemText">帮助中心</li>
                </ul>
                <ul class="itemRight">
                    <li>
                        <svg class="icon font-26 color-100" aria-hidden="true">
                            <use xlink:href="#icon-icon07"></use>
                        </svg>
                    </li>

                </ul>
            </div>
        </a>
    </div>
    </body>
@endsection

@section('js')


@endsection

