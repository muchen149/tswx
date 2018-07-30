@extends('inheritance')
@section('title')
    水丁收银台
@endsection
@section('css')
    <link href="{{asset('sd_css/common.css')}}" rel="stylesheet">
    <style>
        .pay-privilege{
            width: 100%;
        }
        .pay-privilege ul{
            width: 95%;
            height: 50px;
            margin: 0px auto;
        }
        .pay-privilege div:first-child{
            margin-bottom: 10px;
        }
        .pay-privilege .set_paystyle{
            width: 65%;
            margin: 0 auto;
        }
        .pay-privilege .set_paystyle li{
            margin-bottom: 10px;
        }
        .pay-privilege .set_paystyle img{
            width: 42px;
            margin-bottom: 4px;
        }
        .unselect{
            color: rgba(80,80,80,0.5);
        }


        /*sb start*/
        .steward-welcome{
            position: relative;
            width: 85%;
            height: 145px;
            margin: 20px auto 10px;
        }
        .steward-welcome-l img{
            width: 100px;
        }
        .steward-welcome-r{
            width: 63%;
            text-align: left;
        }
        .steward-welcome-r li:first-child{
            margin-bottom: 8px;
        }
        /*sb end*/


        /*服务协议*/
        .arguement{
            margin-top: 20px;
        }
        .arguement img{
            width: 18px;
            margin-right: 5px;
        }
        .arguement a{
            color: dodgerblue;
        }
        .arguement a:active{
            color: dodgerblue;
        }
        .arguement a:visited{
            color: dodgerblue;
        }

        /*支付按钮*/
        .pay-privilege-btn{
            width: 85%;
            height: 44px;
            margin: 18px auto 30px;
            background-color: #f53a3a;
            border-radius: 8px;
        }
    </style>
@endsection
@section('content')
    <div class=" steward steward-welcome flex-between">
        <ul class="steward-welcome-l">
            <li><img src="{{asset('sd_img/steward-avator1.png')}}" alt=""></li>
        </ul>
        <ul class="steward-welcome-r font-14 ">
            <li class="color-50">亲爱的用户,我是!</li>
            <li class="color-80">感谢您的购买，精彩即将开始。</li>
        </ul>

    </div>
    <div class="pay-privilege">
        <div class="list font-14 b-white ">
            <ul class="flex-between">
                <li class="color-80">管家类型</li>
                <li class="color-80 font-ber">{{$activity->grade_name}}</li>
            </ul>
        </div>

        <div class="list font-14 b-white" >
            <ul class="flex-between border-b">
                <li class="color-80">支付金额</li>
                <li class="color-80 font-ber">￥{{$activity->price}}</li>
            </ul>
        </div>

    </div>
    <div class="arguement flex-center font-12 color-80">
        <img class="agree" src="{{asset('sd_img/agree.png')}}" alt="">
        同意<a href="{{asset('document/'.$document->doc_id)}}">《{{$document->doc_title}}》</a>
    </div>
    <div class="pay-privilege-btn flex-center color-white font-14">立即支付</div>
    <script type="text/javascript" src="{{asset('js/common-top.js')}}"></script>
    <script type="text/javascript">

        var wxJson = '';
        $(document).ready(function () {
            $('.set_paystyle li').click(function(e){
                var target= e.currentTarget;
                $(target).find('img').toggle();
                $(target).find('span').toggleClass();
            });

            $('.arguement img').click(function(e){
                if($(this).hasClass('disagree')){
                    $(this).removeClass('disagree').attr('src', '{{asset('sd_img/agree.png')}}');
                }else{
                    $(this).addClass('disagree').attr('src', '{{asset('sd_img/unagree.png')}}');
                }
            });
            function onBridgeReady() {
                WeixinJSBridge.invoke(
                        'getBrandWCPayRequest', wxJson, function (res) {
                            WeixinJSBridge.log(res.err_msg);
                            if (res.err_msg == "get_brand_wcpay_request:ok") {
                                window.location.href = '{{asset('personal/index')}}';
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

            $(".pay-privilege-btn").on("click", function () {
                if($('.arguement img').hasClass('disagree')){
                    return false;
                }
                $.post('{{asset('wx/pay/payMemberShip')}}', {
                    'activity_id': '{{$activity->activity_id}}'
                }, function (res) {
                    if (res.code == 0) {
                        wxJson = res.data.wx_json;
                        Wechat_Pay();
                    } else {
                        message(res.message, 5000);
                        return false;
                    }
                });
            });
        });

    </script>
    </body>
@endsection

