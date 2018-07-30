@extends('inheritance')

@section('title', '水丁收银台')

@section('css')
    <link href="{{asset('sd_css/common.css')}}" rel="stylesheet">

    <style type="text/css">
        .pay-privilege {
            width: 100%;
        }

        .pay-privilege ul {
            width: 95%;
            height: 50px;
            margin: 0px auto;
        }

        .pay-privilege div:first-child {
            margin-bottom: 2px;
        }

        .pay-privilege div:last-child {
            margin-top: 2px;
        }

        .pay-privilege .set_paystyle {
            width: 65%;
            margin: 0 auto;
        }

        .pay-privilege .set_paystyle li {
            margin-bottom: 10px;
        }

        .pay-privilege .set_paystyle img {
            width: 42px;
            margin-bottom: 4px;
        }

        .unselect {
            color: rgba(80, 80, 80, 0.5);
        }

        .steward-welcome {
            position: relative;
            width: 100%;
            height: 180px;
            margin: 0px auto 20px;
        }

        .steward-welcome-l img {
            width: 100px;
            margin-left: 5%;
            margin-top: -30%;
        }

        .steward-welcome-r {
            width: 63%;
            text-align: left;
        }

        .steward-welcome-r li:first-child {
            margin-bottom: 8px;
        }

        /*服务协议*/
        .arguement {
            margin-top: 20px;
        }

        .arguement img {
            width: 18px;
            margin-right: 5px;
        }

        .arguement a {
            color: dodgerblue;
        }

        .arguement a:active {
            color: dodgerblue;
        }

        .arguement a:visited {
            color: dodgerblue;
        }

        /*支付按钮*/
        .pay-privilege-btn {
            width: 85%;
            height: 44px;
            margin: 18px auto 30px;
            background-color: #f53a3a;
            border-radius: 8px;
        }
    </style>
@endsection

@section('content')
    <body>
    {{--<div class=" steward steward-welcome flex-between" style="background-image : url(/sd_img/back.jpg);background-repeat : no-repeat;position: relative;background-size:100% 100%;">
        <ul class="steward-welcome-l">
            @if($activity->grade == 20)
                <li><img src="{{asset('sd_img/xiaoshui_01.png')}}" alt=""></li>
            @elseif($activity->grade == 30)
                <li><img src="{{asset('sd_img/xiaoding_01.png')}}" alt=""></li>
            @elseif($activity->grade == 40)
                <li><img src="{{asset('sd_img/laoding_01.png')}}" alt=""></li>
            @endif
        </ul>

       <ul class="steward-welcome-r font-14 " style="color: white">
            <li class="color-50" style="color: #ede6e6;width: 90%;text-align: center;font-weight: 600">谢谢你主人 !</li>
            <li class="color-80" style="color: #ede6e6;width: 90%;">“{{$activity->grade_name}}”一定会用心为您服务，让您的生活越来越精致，有什么问题可以随时召唤我~~</li>
        </ul>
    </div>--}}
    <div class="b-white" style="text-align: -webkit-center;margin-top: 10px;">
        <img src="{{$activity->activity_images}}" alt="" width="90%">
    </div>
    <div class="pay-privilege">
        <div class="list font-14 b-white ">
            <ul class="flex-between">
                <li class="color-80 font-ber">管家类型</li>
                <li class="color-80 font-ber">{{$activity->grade_name}}</li>
            </ul>
        </div>

        <div class="list font-14 b-white">
            <ul class="flex-between border-b">
                <li class="color-80 font-ber">支付金额</li>
                <li class="color-80 font-ber">￥{{$activity->price}}</li>
            </ul>
        </div>

        <div class="list font-14 b-white">
            <ul class="flex-between">
                <li class="color-80 font-ber">服务时间</li>
                <li class="color-80 font-ber">{{$activity->exp_date}}{{$activity->exp_date_name}}</li>
            </ul>
        </div>
    </div>
    <div class="font-12" style="margin-left: 10px;">
        *开通后您的{{$activity->grade_name}}服务将于{{ $end_time }}到期
    </div>

    <div class="arguement flex-center font-12 color-80">
        <img class="agree" src="{{asset('sd_img/agree.png')}}" alt=""> 同意
        <a href="{{asset('document/' . $document->doc_id)}}">《{{$document->doc_title}}》</a>
    </div>

    <div class="pay-privilege-btn flex-center color-white font-14">立即支付</div>
    </body>
@endsection

@section('js')
    <script type="text/javascript" src="{{asset('js/common-top.js')}}"></script>
    <script type="text/javascript">
        var wxJson = '';
        $(document).ready(function () {
            $('.set_paystyle li').click(function (e) {
                var target = e.currentTarget;
                $(target).find('img').toggle();
                $(target).find('span').toggleClass();
            });

            $('.arguement img').click(function (e) {
                if ($(this).hasClass('disagree')) {
                    $(this).removeClass('disagree').attr('src', '{{asset('sd_img/agree.png')}}');
                } else {
                    $(this).addClass('disagree').attr('src', '{{asset('sd_img/unagree.png')}}');
                }
            });

            $(".pay-privilege-btn").on("click", wx_pay_event);

            function wx_pay_event() {
                $(".pay-privilege-btn").unbind('click');
                if ($('.arguement img').hasClass('disagree')) {
                    message('请需要先同意<br>《' + '{{ $document->doc_title }}' + '》', 4000);
                    $(".pay-privilege-btn").bind('click', wx_pay_event);
                    return false;
                }

                $.post('{{asset('membership/getPayJosnForMemberShip')}}', {
                    'activity_id': '{{ $activity->activity_id }}'
                }, function (res) {
                    if (res.code == 0) {
                        wxJson = res.data.wx_json;
                        Wechat_Pay();
                    } else {
                        message(res.message, 2000);
                    }
                });
            }

            function onBridgeReady() {
                WeixinJSBridge.invoke(
                        'getBrandWCPayRequest', wxJson, function (res) {
                            if (res.err_msg == "get_brand_wcpay_request:ok") {
                                window.location.href = '{{asset('member/center')}}';
                            }
                        }
                );
            }

            function Wechat_Pay() {
                if (typeof WeixinJSBridge == "undefined") {
                    if (document.addEventListener) {
                        document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
                    } else if (document.attachEvent) {
                        document.attachEvent('WeixinJSBridgeReady', onBridgeReady);
                        document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
                    }
                } else {
                    onBridgeReady();
                }
            }
        });
    </script>
@endsection