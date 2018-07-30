@extends('inheritance')

@section('title')
    {{$scanData['activity_name']}}
@endsection

@section('css')
    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('css/after-sales.css')}}" rel="stylesheet">
    <style type="text/css">
        * {
            margin: 0;
            padding: 0;
            border: 0;
        }

        .modal-backdrop.in {
            filter: alpha(opacity=80);
            opacity: .7
        }

        a:hover {
            text-decoration: none
        }

        li {
            list-style: none;
            margin: 0;
        }

        body {
            background: #c32d38 url("{{asset('img/lottery/zp_bg.png')}}") no-repeat top;
            background-size: 100% auto;
        }

        .title {
            width: 80%;
            margin: 60px auto 0;
        }

        .title img {
            width: 100%;
        }

        /*转盘样式开始*/
        .line {
            width: 90%;
            margin: 0px auto 10px;
            text-align: center;
        }

        .line1 {
            margin-top: 50px;
        }

        .Container ul {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .Container li {
            width: 32%;
            height: 93px;
            background: url("{{asset('img/lottery/zp_box_bg.png')}}") no-repeat center;
            background-size: 100% 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
        }

        .Container .active {
            background: rgba(252, 227, 0, 0.4);
            border-radius: 8px;
        }

        .Container .start {
            background: #b60714;
            border-radius: 8px;
            background-size: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .Container img {
            width: 100%;
        }

        .Container p {
            color: black;
            font-size: 8px;
            font-weight: 800;
            margin-bottom: 8px;
            line-height: 14px;
        }

        /*活动说明样式*/
        .Explain {
            color: white;
            width: 90%;
            margin: 40px auto 0;
        }

        .Explain h5 {
            margin-bottom: 10px;
        }

        .Explain li {
            font-size: 13px;
            margin-bottom: 10px;
            line-height: 20px;
        }

        .intro {
            text-align: center;
            background-color: #fff;
            border-radius: 8px;
            font-size: 15px;
            height: 200px;
            color: #793707;
            width: 90%;
            font-weight: 400;
            font-family: '微软雅黑', Arial, Helvetica, sans-serif;
            line-height: 24px;
        }

        .btn-ok {
            width: 100%;
            height: 41px;
            color: #33a0e5;
            border-bottom-left-radius: 8px;
            border-bottom-right-radius: 8px;
            display: block;
            line-height: 41px;
            font-size: 16px;
        }

        .intro-lingqu {
            text-align: center;
            background-color: #fff;
            border-radius: 8px;
            font-size: 15px;
            height: 360px;
            color: #793707;
            width: 90%;
            font-weight: 400;
            font-family: '微软雅黑', Arial, Helvetica, sans-serif;
            line-height: 24px;
        }

        .btn-lingqu {
            width: 100%;
            height: 41px;
            color: #fff;
            background-color: #c32d38;
            border-bottom-left-radius: 8px;
            border-bottom-right-radius: 8px;
            display: block;
            line-height: 41px;
            font-size: 16px;
        }

        .btn-lingqu:hover, .btn-lingqu:link, .btn-lingqu:visited {
            color: #fff
        }

        .second_p {
            margin-bottom: 0;
            word-spacing: 8px;
            letter-spacing: 1px;
            padding: 10px 21px;
            text-align: left;
            color: rgb(80, 80, 80);
            font-size: 15px;
            height: 115px;
            border-bottom: 1px solid rgb(220, 220, 220)
        }
    </style>
@endsection

@section('content')
    <body>
    <div class="title"><img src="{{asset('img/lottery/zp_title.png')}}" alt=""></div>
    <div class="Container" id="lottery">
        @if($scanData['lottery_infonum'] == 8)
            <div class="line line1">
                <ul>
                    <li class="lottery-unit lottery-unit-8" unit="8">
                        @if($scanData['lottery_info'][0]['exchange_type'] == 0)
                            <img src="{{asset('img/lottery/zp_result_no.png')}}" alt="">
                            <p>谢谢参与</p>
                        @else
                            <img src="{{asset('img/lottery/zp_result_p.png')}}" alt="">
                            <p>{{$scanData['lottery_info'][0]['prize']}}{{$scanData['plat_vrb_name']}}</p>
                        @endif
                    </li>
                    <li class="lottery-unit lottery-unit-1" level="1" unit="1">
                        @if($scanData['lottery_info'][1]['is_virtual'] == 0)
                            <img style="width:52.5%"
                                 src="{{$scanData['lottery_info'][1]['prizeProduct']['prize_image']}}" alt="">
                        @else
                            <img src="{{$scanData['lottery_info'][1]['prizeProduct']['prize_image']}}" alt="">
                        @endif
                        <p>{{$scanData['lottery_info'][1]['prizeProduct']['sku_name']}}</p>
                    </li>
                    <li class="lottery-unit lottery-unit-2" level="2" unit="2">
                        @if($scanData['lottery_info'][2]['is_virtual'] == 0)
                            <img style="width:52.5%"
                                 src="{{$scanData['lottery_info'][2]['prizeProduct']['prize_image']}}" alt="">
                        @else
                            <img src="{{$scanData['lottery_info'][2]['prizeProduct']['prize_image']}}" alt="">
                        @endif
                        <p>{{$scanData['lottery_info'][2]['prizeProduct']['sku_name']}}</p>
                    </li>
                </ul>
            </div>
            <div class="line line2">
                <ul>
                    <li class="lottery-unit lottery-unit-7" level="7" unit="7">
                        @if($scanData['lottery_info'][7]['is_virtual'] == 0)
                            <img style="width:52.5%"
                                 src="{{$scanData['lottery_info'][7]['prizeProduct']['prize_image']}}" alt="">
                        @else
                            <img src="{{$scanData['lottery_info'][7]['prizeProduct']['prize_image']}}" alt="">
                        @endif
                        <p>{{$scanData['lottery_info'][7]['prizeProduct']['sku_name']}}</p>
                    </li>
                    <li class="start"><img src="{{asset('img/lottery/zp_btn.png')}}" alt=""></li>
                    <li class="lottery-unit lottery-unit-3" level="3" unit="3">
                        @if($scanData['lottery_info'][3]['is_virtual'] == 0)
                            <img style="width:52.5%"
                                 src="{{$scanData['lottery_info'][3]['prizeProduct']['prize_image']}}" alt="">
                        @else
                            <img src="{{$scanData['lottery_info'][3]['prizeProduct']['prize_image']}}" alt="">
                        @endif
                        <p>{{$scanData['lottery_info'][3]['prizeProduct']['sku_name']}}</p>
                    </li>
                </ul>
            </div>
            <div class="line line3">
                <ul>
                    <li class="lottery-unit lottery-unit-6" level="6" unit="6">
                        @if($scanData['lottery_info'][6]['is_virtual'] == 0)
                            <img style="width:52.5%"
                                 src="{{$scanData['lottery_info'][6]['prizeProduct']['prize_image']}}" alt="">
                        @else
                            <img src="{{$scanData['lottery_info'][6]['prizeProduct']['prize_image']}}" alt="">
                        @endif
                        <p>{{$scanData['lottery_info'][6]['prizeProduct']['sku_name']}}</p>
                    </li>
                    <li class="lottery-unit lottery-unit-5" level="5" unit="5">
                        @if($scanData['lottery_info'][5]['is_virtual'] == 0)
                            <img style="width:52.5%"
                                 src="{{$scanData['lottery_info'][5]['prizeProduct']['prize_image']}}" alt="">
                        @else
                            <img src="{{$scanData['lottery_info'][5]['prizeProduct']['prize_image']}}" alt="">
                        @endif
                        <p>{{$scanData['lottery_info'][5]['prizeProduct']['sku_name']}}</p>
                    </li>
                    <li class="lottery-unit lottery-unit-4" level="4" unit="4">
                        @if($scanData['lottery_info'][4]['is_virtual'] == 0)
                            <img style="width:52.5%"
                                 src="{{$scanData['lottery_info'][4]['prizeProduct']['prize_image']}}" alt="">
                        @else
                            <img src="{{$scanData['lottery_info'][4]['prizeProduct']['prize_image']}}" alt="">
                        @endif
                        <p>{{$scanData['lottery_info'][4]['prizeProduct']['sku_name']}}</p>
                    </li>
                </ul>
            </div>
        @endif
        @if($scanData['lottery_infonum'] == 7)
            <div class="line line1">
                <ul>
                    <li class="lottery-unit lottery-unit-8" unit="8">
                        @if($scanData['lottery_info'][0]['exchange_type'] == 0)
                            <img src="{{asset('img/lottery/zp_result_no.png')}}" alt="">
                            <p>谢谢参与</p>
                        @else
                            <img src="{{asset('img/lottery/zp_result_p.png')}}" alt="">
                            <p>{{$scanData['lottery_info'][0]['prize']}}{{$scanData['plat_vrb_name']}}</p>
                        @endif
                    </li>
                    <li class="lottery-unit lottery-unit-1" level="1" unit="1">
                        @if($scanData['lottery_info'][1]['is_virtual'] == 0)
                            <img style="width:52.5%"
                                 src="{{$scanData['lottery_info'][1]['prizeProduct']['prize_image']}}" alt="">
                        @else
                            <img src="{{$scanData['lottery_info'][1]['prizeProduct']['prize_image']}}" alt="">
                        @endif
                        <p>{{$scanData['lottery_info'][1]['prizeProduct']['sku_name']}}</p>
                    </li>
                    <li class="lottery-unit lottery-unit-2" level="2" unit="2">
                        @if($scanData['lottery_info'][2]['is_virtual'] == 0)
                            <img style="width:52.5%"
                                 src="{{$scanData['lottery_info'][2]['prizeProduct']['prize_image']}}" alt="">
                        @else
                            <img src="{{$scanData['lottery_info'][2]['prizeProduct']['prize_image']}}" alt="">
                        @endif
                        <p>{{$scanData['lottery_info'][2]['prizeProduct']['sku_name']}}</p>
                    </li>
                </ul>
            </div>

            <div class="line line2">
                <ul>
                    <li class="lottery-unit lottery-unit-7" level="6" unit="7">
                        @if($scanData['lottery_info'][6]['is_virtual'] == 0)
                            <img style="width:52.5%"
                                 src="{{$scanData['lottery_info'][6]['prizeProduct']['prize_image']}}" alt="">
                        @else
                            <img src="{{$scanData['lottery_info'][6]['prizeProduct']['prize_image']}}" alt="">
                        @endif
                        <p>{{$scanData['lottery_info'][6]['prizeProduct']['sku_name']}}</p>
                    </li>
                    <li class="start"><img src="{{asset('img/lottery/zp_btn.png')}}" alt=""></li>
                    <li class="lottery-unit lottery-unit-3" level="3" unit="3">
                        @if($scanData['lottery_info'][3]['is_virtual'] == 0)
                            <img style="width:52.5%"
                                 src="{{$scanData['lottery_info'][3]['prizeProduct']['prize_image']}}" alt="">
                        @else
                            <img src="{{$scanData['lottery_info'][3]['prizeProduct']['prize_image']}}" alt="">
                        @endif
                        <p>{{$scanData['lottery_info'][3]['prizeProduct']['sku_name']}}</p>
                    </li>
                </ul>
            </div>

            <div class="line line3">
                <ul>
                    <li class="lottery-unit lottery-unit-6" unit="6">
                        <img src="{{asset('img/lottery/zp_result_no.png')}}" alt="">
                        <p>谢谢参与</p>
                    </li>
                    <li class="lottery-unit lottery-unit-5" level="5" unit="5">
                        @if($scanData['lottery_info'][5]['is_virtual'] == 0)
                            <img style="width:52.5%"
                                 src="{{$scanData['lottery_info'][5]['prizeProduct']['prize_image']}}" alt="">
                        @else
                            <img src="{{$scanData['lottery_info'][5]['prizeProduct']['prize_image']}}" alt="">
                        @endif
                        <p>{{$scanData['lottery_info'][5]['prizeProduct']['sku_name']}}</p>
                    </li>
                    <li class="lottery-unit lottery-unit-4" level="4" unit="4">
                        @if($scanData['lottery_info'][4]['is_virtual'] == 0)
                            <img style="width:52.5%"
                                 src="{{$scanData['lottery_info'][4]['prizeProduct']['prize_image']}}" alt="">
                        @else
                            <img src="{{$scanData['lottery_info'][4]['prizeProduct']['prize_image']}}" alt="">
                        @endif
                        <p>{{$scanData['lottery_info'][4]['prizeProduct']['sku_name']}}</p>
                    </li>
                </ul>
            </div>
        @endif
        @if($scanData['lottery_infonum'] == 6)
            <div class="line line1">
                <ul>
                    <li class="lottery-unit lottery-unit-8" unit="8">
                        @if($scanData['lottery_info'][0]['exchange_type'] == 0)
                            <img src="{{asset('img/lottery/zp_result_no.png')}}" alt="">
                            <p>谢谢参与</p>
                        @else
                            <img src="{{asset('img/lottery/zp_result_p.png')}}" alt="">
                            <p>{{$scanData['lottery_info'][0]['prize']}}{{$scanData['plat_vrb_name']}}</p>
                        @endif
                    </li>
                    <li class="lottery-unit lottery-unit-1" level="1" unit="1">
                        @if($scanData['lottery_info'][1]['is_virtual'] == 0)
                            <img style="width:52.5%"
                                 src="{{$scanData['lottery_info'][1]['prizeProduct']['prize_image']}}" alt="">
                        @else
                            <img src="{{$scanData['lottery_info'][1]['prizeProduct']['prize_image']}}" alt="">
                        @endif
                        <p>{{$scanData['lottery_info'][1]['prizeProduct']['sku_name']}}</p>
                    </li>
                    <li class="lottery-unit lottery-unit-2" level="2" unit="2">
                        @if($scanData['lottery_info'][2]['is_virtual'] == 0)
                            <img style="width:52.5%"
                                 src="{{$scanData['lottery_info'][2]['prizeProduct']['prize_image']}}" alt="">
                        @else
                            <img src="{{$scanData['lottery_info'][2]['prizeProduct']['prize_image']}}" alt="">
                        @endif
                        <p>{{$scanData['lottery_info'][2]['prizeProduct']['sku_name']}}</p>
                    </li>
                </ul>
            </div>
            <div class="line line2">
                <ul>
                    <li class="lottery-unit lottery-unit-7" level="5" unit="7">
                        @if($scanData['lottery_info'][5]['is_virtual'] == 0)
                            <img style="width:52.5%"
                                 src="{{$scanData['lottery_info'][5]['prizeProduct']['prize_image']}}" alt="">
                        @else
                            <img src="{{$scanData['lottery_info'][5]['prizeProduct']['prize_image']}}" alt="">
                        @endif
                        <p>{{$scanData['lottery_info'][5]['prizeProduct']['sku_name']}}</p>
                    </li>
                    <li class="start"><img src="{{asset('img/lottery/zp_btn.png')}}" alt=""></li>
                    <li class="lottery-unit lottery-unit-3" level="3" unit="3">
                        @if($scanData['lottery_info'][3]['is_virtual'] == 0)
                            <img style="width:52.5%"
                                 src="{{$scanData['lottery_info'][3]['prizeProduct']['prize_image']}}" alt="">
                        @else
                            <img src="{{$scanData['lottery_info'][3]['prizeProduct']['prize_image']}}" alt="">
                        @endif
                        <p>{{$scanData['lottery_info'][3]['prizeProduct']['sku_name']}}</p>
                    </li>
                </ul>
            </div>
            <div class="line line3">
                <ul>
                    <li class="lottery-unit lottery-unit-6" unit="6">
                        <img src="{{asset('img/lottery/zp_result_no.png')}}" alt="">
                        <p>谢谢参与</p>
                    </li>
                    <li class="lottery-unit lottery-unit-5" level="4" unit="5">
                        @if($scanData['lottery_info'][4]['is_virtual'] == 0)
                            <img style="width:52.5%"
                                 src="{{$scanData['lottery_info'][4]['prizeProduct']['prize_image']}}" alt="">
                        @else
                            <img src="{{$scanData['lottery_info'][4]['prizeProduct']['prize_image']}}" alt="">
                        @endif
                        <p>{{$scanData['lottery_info'][4]['prizeProduct']['sku_name']}}</p>
                    </li>
                    <li class="lottery-unit lottery-unit-4" unit="4">
                        <img src="{{asset('img/lottery/zp_result_no.png')}}" alt="">
                        <p>谢谢参与</p>
                    </li>
                </ul>
            </div>
        @endif
        @if($scanData['lottery_infonum'] == 5)
            <div class="line line1">
                <ul>
                    <li class="lottery-unit lottery-unit-8" unit="8">
                        @if($scanData['lottery_info'][0]['exchange_type'] == 0)
                            <img src="{{asset('img/lottery/zp_result_no.png')}}" alt="">
                            <p>谢谢参与</p>
                        @else
                            <img src="{{asset('img/lottery/zp_result_p.png')}}" alt="">
                            <p>{{$scanData['lottery_info'][0]['prize']}}{{$scanData['plat_vrb_name']}}</p>
                        @endif
                    </li>
                    <li class="lottery-unit lottery-unit-1" level="1" unit="1">
                        @if($scanData['lottery_info'][1]['is_virtual'] == 0)
                            <img style="width:52.5%"
                                 src="{{$scanData['lottery_info'][1]['prizeProduct']['prize_image']}}" alt="">
                        @else
                            <img src="{{$scanData['lottery_info'][1]['prizeProduct']['prize_image']}}" alt="">
                        @endif
                        <p>{{$scanData['lottery_info'][1]['prizeProduct']['sku_name']}}</p>
                    </li>
                    <li class="lottery-unit lottery-unit-2" unit="2">
                        <img src="{{asset('img/lottery/zp_result_no.png')}}" alt="">
                        <p>谢谢参与</p>
                    </li>
                </ul>
            </div>

            <div class="line line2">
                <ul>
                    <li class="lottery-unit lottery-unit-7" level="4" unit="7">
                        @if($scanData['lottery_info'][4]['is_virtual'] == 0)
                            <img style="width:52.5%"
                                 src="{{$scanData['lottery_info'][4]['prizeProduct']['prize_image']}}" alt="">
                        @else
                            <img src="{{$scanData['lottery_info'][4]['prizeProduct']['prize_image']}}" alt="">
                        @endif
                        <p>{{$scanData['lottery_info'][4]['prizeProduct']['sku_name']}}</p>
                    </li>
                    <li class="start"><img src="{{asset('img/lottery/zp_btn.png')}}" alt=""></li>
                    <li class="lottery-unit lottery-unit-3" level="2" unit="3">
                        @if($scanData['lottery_info'][2]['is_virtual'] == 0)
                            <img style="width:52.5%"
                                 src="{{$scanData['lottery_info'][2]['prizeProduct']['prize_image']}}" alt="">
                        @else
                            <img src="{{$scanData['lottery_info'][2]['prizeProduct']['prize_image']}}" alt="">
                        @endif
                        <p>{{$scanData['lottery_info'][2]['prizeProduct']['sku_name']}}</p>
                    </li>
                </ul>
            </div>

            <div class="line line3">
                <ul>
                    <li class="lottery-unit lottery-unit-6" unit="6">
                        <img src="{{asset('img/lottery/zp_result_no.png')}}" alt="">
                        <p>谢谢参与</p>
                    </li>
                    <li class="lottery-unit lottery-unit-5" level="3" unit="5">
                        @if($scanData['lottery_info'][3]['is_virtual'] == 0)
                            <img style="width:52.5%"
                                 src="{{$scanData['lottery_info'][3]['prizeProduct']['prize_image']}}" alt="">
                        @else
                            <img src="{{$scanData['lottery_info'][3]['prizeProduct']['prize_image']}}" alt="">
                        @endif
                        <p>{{$scanData['lottery_info'][3]['prizeProduct']['sku_name']}}</p>
                    </li>
                    <li class="lottery-unit lottery-unit-4" unit="4">
                        <img src="{{asset('img/lottery/zp_result_no.png')}}" alt="">
                        <p>谢谢参与</p>
                    </li>
                </ul>
            </div>
        @endif
    </div>
    <div class="Explain">
        <H5>活动说明</H5>
        <ul>
            {{$scanData['introduction']}}
        </ul>
    </div>

    <div class="modal fade" id="exampleModal4" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel4"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="background-color:inherit;border:none;box-shadow: none">
                <img style="height:38px;width:38px;margin-top:60%;margin-left:48%" src="{{asset('img/loading.gif')}}">
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal3" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content"
                 style="margin-top:30%;margin-left:9%;text-align:center;background-color:inherit;border:none;box-shadow: none">
                <div class="intro-lingqu">
                    <p class="first-p"
                       style="margin-bottom:10px;padding-top:12px;color:#c32d38;font-size:16px;height:42px;word-spacing:8px; letter-spacing: 1px;">
                        谢谢参与!</p>
                    <img class="proImg" style="width:85%" src="{{asset('img/lottery/lottery_no.png')}}"/>
                    <p class="third_p"
                       style="margin-bottom:0;padding-top:12px;color:#505050;font-size:15px;height:45px;word-spacing:8px; letter-spacing: 1px;">
                        您离大奖只有一步之遥</p>
                    <a class="btn-lingqu" data-dismiss="modal" aria-hidden="true">进入商城</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content"
                 style="margin-top:32%;margin-left:9%;text-align:center;background-color:inherit;border:none;box-shadow: none">
                <div class="intro-lingqu" style="height:335px">
                    <p class="first-p"
                       style="margin-bottom:10px;padding-top:12px;color:#c32d38;font-size:17px;height:42px;word-spacing:8px; letter-spacing: 1px;">
                        糟糕，{{$scanData['plat_vrb_name']}}不够!</p>
                    <img class="proImg" style="width:70%" src="{{asset('img/lottery/lottery_points_no.png')}}"/>
                    <p class="third_p"
                       style="margin-bottom:0;padding-top:12px;color:#505050;font-size:16px;height:50px;word-spacing:8px; letter-spacing: 1px;">
                        努力扫码，挣够{{$scanData['plat_vrb_name']}}再来</p>
                    <a class="btn-lingqu" data-dismiss="modal" aria-hidden="true">确定</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content"
                 style="margin-top:30%;margin-left:9%;text-align:center;background-color:inherit;border:none;box-shadow: none">
                <div class="intro-lingqu">
                    <p class="first-p"
                       style="margin-bottom:10px;padding-top:12px;color:#c32d38;font-size:16px;height:42px;word-spacing:8px; letter-spacing: 1px;"></p>
                    <img class="proImg" src=""/>
                    <p class="second-p" style="position: absolute;width:50px;"></p>
                    <p class="third_p"
                       style="margin-bottom:0;padding-top:12px;color:#505050;font-size:16px;height:45px;word-spacing:8px; letter-spacing: 1px;"></p>
                    <a class="btn-lingqu" data-dismiss="modal" aria-hidden="true">立即领取</a>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="exampleModal6" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel6"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content"
                 style="margin-top:38%;margin-left:9%;text-align:center;background-color:inherit;border:none;box-shadow: none">
                <div class="intro-lingqu" style="height:270px;">
                    <img class="proImg" style="width:65%;margin-top:22px;" src="{{$scanData['company_qrcode']}}"/>
                    <p class="third_p"
                       style="margin-bottom:0;padding-top:12px;color:#505050;font-size:16px;height:45px;word-spacing:8px; letter-spacing: 1px;">
                        长按关注公众号即可领取红包</p>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="exampleModal5" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel5"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content"
                 style="margin-top:51%;margin-left:9%;text-align:center;background-color:inherit;border:none;box-shadow: none">
                <div class="intro">
                    <p style="margin-bottom:0;padding-top:12px;color:rgb(50,50,50);font-weight:600;font-size:16px;height:42px;">
                        恭喜，成功领取红包！</p>
                    <p class="second_p"></p>
                    <a class="btn-ok" data-dismiss="modal" aria-hidden="true">确定</a>
                </div>
            </div>
        </div>
    </div>
    <div id="pop-warn" class="pop pop-ctm-01" style="display: none; position: fixed; top: 30%;left: 15%;">
        <div class="pop-msg">
            <div id="warn-content" class="pop-msg-cnt" style="padding-top:30px;min-height: 50px"></div>
            <div id="warn-close-btn" class="pop-msg-btm"><a class="btn-h4 btn-c3" href="javascript:void(0);">确定</a>
            </div>
        </div>
    </div>
    <div id="pop-confirm" class="pop pop-ctm-01" style="display: none; position: fixed; top: 30%;left: 15%;">
        <div class="pop-msg">
            <div id="confirm-content" class="pop-msg-cnt" style="padding-top:18px;min-height: 50px;font-size: 15px;">
            </div>
            <div class="pop-msg-btm">
                <a id="confirm-ok-btn" class="btn-h4 btn-c3" style="width: 96px;border: 1px solid #fe6666;"
                   href="javascript:void(0);">确定</a>
                <a id="confirm-close-btn" class="btn-h4 btn-c3"
                   style="width: 96px;border: 1px solid #cfcfcf;color: #737373;background: #fff"
                   href="javascript:void(0);">取消</a>
            </div>
        </div>
    </div>
    <div class="__MASK_SID_DIV" style="display:none;width:100%;position: absolute; z-index: 999; left: 0px; top: 0px;">
        <div id="set_height"
             style="width:100%; height: 1024px; opacity: 0.7; background: black none repeat scroll 0% 0%; position: static; margin: 0px;"></div>
    </div>
    </body>
@endsection

@section('js')
    <script type="text/javascript" src="{{asset('js/bootstrap.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/common.js')}}"></script>
    <script type="text/javascript">
        var yesb_available = '{{$scanData['yesb_available']}}';
        var cost_vrcoin = '{{$scanData['cost_vrcoin']}}';
        var subscribe = '{{$scanData['subscribe']}}';
        var company_id = '{{$scanData['company_id']}}';
        var card_id = '{{$scanData['card_id']}}';
        var activity_id = '{{$scanData['activity_id']}}';
        var lottery_infonum = '{{$scanData['lottery_infonum']}}';
        var activity_type = '{{$scanData['activity_type']}}';
        var plat_vrb_name = '{{$scanData['plat_vrb_name']}}'; //虚拟币

        var json_param = '';
        var prize_level = "";

        var lottery = {
            index: 0,	//当前转动到哪个位置，起点位置
            count: 0,	//总共有多少个位置
            timer: 0,	//setTimeout的ID，用clearTimeout清除
            speed: 80,	//初始转动速度
            times: 0,	//转动次数
            cycle: rd(32, 48),	//转动基本次数：即至少需要转动多少次再进入抽奖环节
            prize: 0,	//中奖位置
            init: function (id) {
                if ($("#" + id).find(".lottery-unit").length > 0) {
                    lottery1 = $("#" + id);
                    units = lottery1.find(".lottery-unit");
                    this.obj = lottery1;
                    this.count = units.length;
                    lottery1.find(".lottery-unit-" + this.index).addClass("active");
                }

            },
            roll: function () {
                var index = this.index;
                var count = this.count;
                var lottery = this.obj;
                $(lottery).find(".lottery-unit-" + index).removeClass("active");
                index += 1;
                if (index > count) {
                    index = 1;
                }
                $(lottery).find(".lottery-unit-" + index).addClass("active");
                this.index = index;
                return false;
            },
            stop: function (index) {
                var lottery = this.obj;
                $(lottery).find(".lottery-unit").removeClass("active");
                clearTimeout(this.timer);
                this.prize = index;
                this.times = index;
                click = false;
                return false;
            }
        };

        function roll() {
            lottery.times += 1;
            lottery.roll();
            if (lottery.times >= lottery.cycle + 6 && lottery.prize == lottery.index && prize_level != '') {
                clearTimeout(lottery.timer);
                lottery.prize = 0;
                lottery.times = 0;
                click = false;
                if (json_param.exchange_type == 2) {
                    $('#exampleModal2').modal({
                        show: true,
                        backdrop: 'static',
                        keyboard: false
                    }).find(".first-p").html("恭喜，获得" + json_param.prize + plat_vrb_name +"!");
                    $('#exampleModal2').find("img").attr("src", json_param.prize_image).css("width", "85%");
                    $('#exampleModal2').find(".third_p").html(json_param.sku_name);
                    $('#exampleModal2').find(".second-p").html(json_param.prize).css({
                        color: "#c32d38",
                        top: "40%",
                        left: "38%",
                        "font-size": "22px",
                        "font-weight": "400"
                    });
                    $('#exampleModal2').on('hidden.bs.modal', function () {
                        if (subscribe == 1) {
                            $(".intro").css("height", "165px").find(".second_p").css("height", "82px");
                            $('#exampleModal5').find(".second_p").html("您获得的" + json_param.prize + plat_vrb_name +"已存入您的账户,可在<span style='color:rgb(50,50,50)'>“个人中心”</span>查看。");
                            $('#exampleModal5').modal({
                                show: true,
                                backdrop: 'static',
                                keyboard: false
                            });
                            $('#exampleModal5').on('hidden.bs.modal', function () {
                                window.location.href = '{{asset('personal/awardsRecord')}}';//跳转至中奖记录列表
                            });
                        } else {
                            //window.location.href = "weixin/wx_points_succeed.php?company_name=" + companyname + "&integral=" + json_param.integral;
                        }
                    });
                } else if (json_param.exchange_type == 1) {
                    $('#exampleModal2').modal({
                        show: true,
                        backdrop: 'static',
                        keyboard: false
                    }).find(".first-p").html("恭喜，获得" + json_param.prize_level_str + "!");
                    $('#exampleModal2').find(".third_p").html(json_param.sku_name);
                    switch (json_param.is_virtual) {
                        case 0: //实物奖品
                            $('#exampleModal2').find("img").attr("src", json_param.prize_image).css({
                                "width": "178px",
                                "height": "236px"
                            });
                            $('#exampleModal2').on('hidden.bs.modal', function () {
                                //完善领奖信息
                                window.location.href = '{{asset('marketing/lottery/perfectAwardInfo')}}' + '/' + json_param.awardsrecord_id;
                            });
                            break;
                        case 1: //虚拟奖品
                            $('#exampleModal2').find("img").attr("src", json_param.prize_image).css("width", "85%");
                            $('#exampleModal2').find(".second-p").html(json_param.prize).css({
                                color: "#fff",
                                top: "56%",
                                left: "32%",
                                "font-size": "50px",
                                "font-weight": "400",
                                "text-align": "right"
                            });
                            $(".btn-lingqu").on("click", function () {
                                $(this).unbind("click");
                                if (subscribe == '1') {
                                    $.ajax({
                                        type: "post",
                                        url: '{{asset('marketing/lottery/receiveRedpack')}}',
                                        data: {awardsrecord_id: json_param.awardsrecord_id},
                                        dataType: "json",
                                        beforeSend: function () {
                                            $('#exampleModal4').modal({
                                                show: true,
                                                backdrop: 'static',
                                                keyboard: false
                                            });
                                        },
                                        success: function (result) {
                                            if (result.code == 0) {
                                                $(".intro").css("height", "185px").find(".second_p").css("height", "100px");
                                                $('#exampleModal5').find(".second_p").html("您获得的" + result.data.prize + "元现金红包已发送至您的微信,可在<span style='color:rgb(50,50,50)'>“公众号”</span>领取。");
                                                $('#exampleModal5').modal({
                                                    show: true,
                                                    backdrop: 'static',
                                                    keyboard: false
                                                });
                                                $('#exampleModal5').on('hidden.bs.modal', function () {
                                                    location.href = '{{asset('personal/index')}}'; //个人中心
                                                });
                                            } else {
                                                message(result.message);
                                                return false;
                                            }
                                        },
                                        error: function (XMLHttpRequest, textStatus, errorThrown) {
                                            alert("error: 红包领取失败，可在中奖记录重新领取！");
                                            return false;
                                        }
                                    });
                                } else {
                                    $('#exampleModal2').modal("hide");
                                    $('#exampleModal6').modal({
                                        show: true,
                                        backdrop: 'static',
                                        keyboard: false
                                    });
                                }
                            });
                            break;
                    }
                } else {
                    yesb_available -= cost_vrcoin;
                    $('#exampleModal3').modal({
                        show: true,
                        backdrop: 'static',
                        keyboard: false
                    });
                    if (activity_type == 2) {
                        $('#exampleModal3').find(".btn-lingqu").html("确定");
                        $("#lottery .start").click(function () {
                            json_param = '';
                            prize_level = '';
                            lottery.prize = 0;
                            click = false;
                            lo_a();
                        });
                    } else {
                        $('#exampleModal3').on('hidden.bs.modal', function () {
                            if (subscribe == '1') {
                                location.href = '{{asset('/')}}';
                            } else {
                                $('#exampleModal6').modal({
                                    show: true,
                                    backdrop: 'static',
                                    keyboard: false
                                });
                            }
                        });
                    }
                }
            } else {
                if (lottery.times < lottery.cycle) {
                    lottery.speed -= 10;
                } else if (lottery.times >= lottery.cycle && prize_level != '') {
                    lottery.prize = prize_level;
                    if (lottery.times >= lottery.cycle + 6 && ((lottery.prize == 0 && lottery.index == 7) || lottery.prize == lottery.index + 1 || prize_level != '')) {
                        lottery.speed += 110;
                    } else {
                        lottery.speed += 20;
                    }
                } else {
                    if (lottery.times >= lottery.cycle + 6 && ((lottery.prize == 0 && lottery.index == 7) || lottery.prize == lottery.index + 1 || prize_level != '')) {
                        lottery.speed += 110;
                    } else {
                        lottery.speed += 20;
                    }
                }
                if (lottery.speed < 80) {
                    lottery.speed = 80;
                }
                //console.log(lottery.times + '^^^^^^' + lottery.speed + '^^^^^^^' + lottery.prize + '^^^^^^^' + prize_level + '^^^^^^^' + lottery.index);
                lottery.timer = setTimeout(roll, lottery.speed);
            }
            return false;
        }

        var click = false;

        window.onload = function () {
            $(".btn-lingqu").on("click", function () {
                $(this).css("color", "#fff");
            });
            lottery.init('lottery');
            $("#lottery .start").click(function () {
                lo_a();
            });
        };

        function lo_a() {
            if (click) {
                return false;
            } else {
                lottery.speed = 100;
                lotteryClick();
                click = true;
                return false;
            }
        }
        function lotteryClick() {
            $("#lottery .start").unbind('click').css("cursor", "default");
            var objParameter = new Object;
            objParameter = {
                activity_id: activity_id,
                card_id: card_id
            };
            if (activity_type == 2) {
                if (parseInt(yesb_available) < parseInt(cost_vrcoin)) {
                    $('#exampleModal1').modal({
                        show: true,
                        backdrop: 'static',
                        keyboard: false
                    });
                    $("#lottery .start").click(function () {
                        click = false;
                        lo_a();
                    });
                    return false;
                } else {
                    var con = confirm('花费' + cost_vrcoin + plat_vrb_name +'玩一次吗？');
                    if (!con) {
                        $("#lottery .start").click(function () {
                            click = false;
                            lo_a();
                        });
                        return false;
                    }
                }
            }
            roll();
            $.ajax({
                type: 'POST',
                url: '{{asset('marketing/lottery/handleLottery')}}',
                dataType: 'json',
                data: objParameter,
                cache: false,
                async: true,
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    lottery.stop(0);
                    message("出错了");
                    return false;
                },
                success: function (res) {
                    json_param = res.data;
                    if (res.code != 0) {
                        lottery.stop(0);
                        message(res.message);
                        return false;
                    } else {
                        var level = res.data.prize_level;
                        if (level == 0) {
                            prize_level = "8";
                        } else {
                            prize_level = $("li[level='" + level + "']").attr("unit");
                        }
                    }
                }
            });
        }
        function rd(n, m) {
            var c = m - n + 1;
            return Math.floor(Math.random() * c + n);
        }
    </script>
@endsection



