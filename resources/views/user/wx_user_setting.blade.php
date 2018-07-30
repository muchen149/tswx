@extends('inheritance')

@section('title')
    账号设置
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('sd_css/mui.min.css')}}">
    <link rel="stylesheet" href="{{asset('sd_css/common.css')}}">
    <script src="{{asset('sd_js/font_wvum.js')}}"></script>

    <style>

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
    <div class="me-user-information-item-wrap borderTop borderBottom">
        <a href="{{asset('/personal/userInfo')}}">
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

    <div class="me-user-information-item-wrap borderTop borderBottom">
        <a href="{{asset('member/center')}}">
            <div class="me-user-information-item flex-between b-white marginTop ">
                <ul class="flex-between itemLeft">
                    <li>
                        <svg class="icon font-26" style="color: #2495ff" aria-hidden="true">
                            <use xlink:href="#icon-lipeiguanjia"></use>
                        </svg>
                    </li>
                    <li class="font-14 color-80 itemText">我的管家</li>
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
        <a href="{{asset('personal/address/addressList')}}">
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
    <div class="me-user-information-item-wrap">
        <a href="">
            <div class="me-user-information-item flex-between b-white borderTop">
                <ul class="flex-between itemLeft">
                    <li>
                        <svg class="icon font-26" style="color: #02c780" aria-hidden="true">
                            <use xlink:href="#icon-1"></use>
                        </svg>
                    </li>
                    <li class="font-14 color-80 itemText">账户与安全</li>
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
    <div class="me-user-information-item-wrap borderBottom">

        <a href="{{asset('personal/about')}}">
            <div class="me-user-information-item flex-between b-white borderTop">
                <ul class="flex-between itemLeft">
                    <li>
                        <svg class="icon font-26" style="color: #09cace" aria-hidden="true">
                            <use xlink:href="#icon-guanyu1"></use>
                        </svg>
                    </li>
                    <li class="font-14 color-80 itemText">关于水丁管家</li>
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
    <div class="me-user-information-item-wrap borderTop borderBottom marginTop">
        <a href="me_setting_help.html">
            <div class="me-user-information-item flex-between b-white">
                <ul class="flex-between itemLeft">
                    <li>
                        <svg class="icon font-26" style="color: #ff8c60" aria-hidden="true">
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

