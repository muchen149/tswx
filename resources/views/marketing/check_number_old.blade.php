@extends('inheritance')

@section('title')
    卡券绑定
@endsection

@section('css')
    <style>
        * {
            margin: 0;

        }

        body {
            background-color: rgb(240, 240, 240);
        }

        .header {
            position: absolute;
            top: 0px;
            z-index: 9999;
            width: 100%;
            height: 140px;
            background-color: #f44336;
            text-align: center;
            overflow: hidden;
            box-shadow: 0 2px 3px rgb(180, 180, 180);
        }

        .header p:first-child {
            color: white;
            margin-top: 50px;
            font-size: 19px;

        }

        .header p:last-child {
            color: white;
            margin-top: 10px;
            font-size: 19px;

        }

        .down {
            display: none;
            width: 95%;
            height: 290px;
            margin: 140px auto 0;
            border-radius: 0 0 8px 8px;
            background-color: white;
            text-align: center;
            overflow: hidden;
            box-shadow: 0px 2px 4px rgb(180, 180, 180);
        }

        .round {
            display: flex;
            width: 291px;
            margin: 18px auto 0;
            justify-content: space-between;
            align-items: center;

        }

        .round input {
            height: 30px;
            border: 1px solid rgb(200, 200, 200);
            border-radius: 4px;
            outline: none;
            text-align: center;
        }

        .round img {
            width: 84px;
        }

        .footer {
            width: 100%;
            text-align: center;
            margin-top: 40px;
        }

        .footer img {
            width: 170px;
        }

        .tips {
            width: 291px;
            height: 18px;
            margin: 5px auto 0;
            text-align: left;
            font-size: 12px;
            color: #ff3232;
        }

        .submit {
            width: 291px;
            margin: 18px auto 0;
        }

        .submit img {
            width: 100%;
        }

        .success {
            margin-top: 100px;
        }

        .success h4 {
            color: #ff3232;
            font-family: sans-serif;
        }

        .success p {
            margin-top: 5px;
            font: 12px sans-serif;
            color: rgb(120, 120, 120);

        }

        .alieditContainer {
            position: relative;
            display: flex;
            width: 291px;
            margin: 18px auto 0;
            justify-content: space-between;
            align-items: center;
        }

        .alieditContainer .i-text {
            position: absolute;
            color: #fff;
            opacity: 0.2;
            width: 291px;
            height: 33px;
            font-size: 12px;
            left: 0;
            -webkit-user-select: initial; /*取消禁用选择页面元素*/
            z-index: 9;
            padding: 0;
            border: 0;
            outline: none;
            letter-spacing: 22px;
            text-indent: 14px;
        }

        .alieditContainer .sixDigitPassword {
            width: 310px;
            height: 34px;
            cursor: text;
            background: #fff;
            outline: none;
            /*padding: 8px 0;*/
            border: 1px solid rgb(180, 180, 180);
            border-radius: 5px;
        }

        .alieditContainer .sixDigitPassword i {
            width: 28.8px;
            height: 32px;
            float: left;
            display: block;
            padding: 4px 0;
            border-left: 1px solid rgb(180, 180, 180);
        }

        .alieditContainer .sixDigitPassword i:first-child {
            border-left: 0;
        }

        .alieditContainer .sixDigitPassword b {
            display: block;
            /*margin: -6px auto 4px auto;*/
            width: 29px;
            height: 24px;
            line-height: 24px;
            font-style: normal;
            overflow: hidden;
        }
    </style>
@endsection

@section('content')
    <body>
    <div class="header">
        <p>请输入您收到的10位数字短信卡密</p>
        <p>以激活卡片</p>
    </div>
    <div class="down">
        <div class="alieditContainer" id="cardPassword_container">
            <input maxlength="10" autofocus id="cardPassword_rsainput" name="cardPassword_rsainput"
                   class="ui-input i-text" type="text"/>
            <div class="sixDigitPassword" tabindex="0">
                <i><b></b></i>
                <i><b></b></i>
                <i><b></b></i>
                <i><b></b></i>
                <i><b></b></i>
                <i><b></b></i>
                <i><b></b></i>
                <i><b></b></i>
                <i><b></b></i>
                <i><b></b></i>
            </div>
        </div>
        <div class="tips"><span id="password_error"></span></div>


        <div class="round">
            <input type="text" placeholder="输入右侧验证码" maxlength="4" id="captcha">
            <input type="hidden" id="identity" value="{{$captcha['identity']}}">
            <img id="refresh" src="{{$captcha['image']}}" alt="">
        </div>
        <div class="tips"><span id="captcha_error"></span></div>
        <div class="submit">
            <img src="{{asset('img/submit_state2.png')}}" alt="">
        </div>
    </div>
    <div id="pop-loading" style="z-index: 1001;display: none; position: fixed; top: 40%;left: 42.5%;">
        <img style="height:38px;width:38px;margin-top:60%;margin-left:48%" src="{{asset('img/loading.gif')}}">
    </div>
    <div class="__MASK_SID_DIV" style="display:none;width:100%;position: absolute; z-index: 999; left: 0px; top: 0px;">
        <div id="set_height"
             style="width:100%; height: 1024px; opacity: 0.7; background: black none repeat scroll 0% 0%; position: static; margin: 0px;"></div>
    </div>
    </body>
@endsection

@section('js')
    <script type="text/javascript" src="{{asset('js/common.js')}}"></script>
    <script type="text/javascript">
        var ckpassword = false;
        var ckcaptcha = false;
        $(function () {
            $('.down').slideToggle(2000);

            $('#refresh').on('click', function () {
                $.post('{{asset('marketing/dispatch/captcha')}}', {}, function (res) {
                    $("#refresh").attr('src', res.data.image);
                    $("#identity").val(res.data.identity);
                    ckcaptcha = false;
                    $("#captcha").val('');
                    $('#captcha_error').text('');
                });
            });

            //操作成功提示
            $('.submit').on('click', function () {
                if (!ckpassword || !ckcaptcha) {
                    return false;
                }
                loading();
                $.post('{{asset('marketing/dispatch/submit')}}', {"numberCode": $("#cardPassword_rsainput").val()}, function (res) {
                    loadSucc();
                    if (res.code == 0) {
                        var url = '{{asset('personal/index')}}';
                        switch (parseInt(res.data.type)) {
                            case 3:
                                url = '{{asset('personal/wallet/giftCoupons/choseGiftCouponGoods')}}' + '/' + res.data.giftcoupon_id;
                                break;
                        }
                        var obj = $('<div  class="success"><h4>恭喜，绑定成功!</h4><p>即将跳转...</p></div>');
                        $('.down').empty().append(obj);
                        setTimeout(turn_to(url), 5000);
                    } else  if(res.code == 50000){
                        url = '{{asset('personal/userLoginView')}}';
                        var obj = $('<div  class="success"><h4>'+res.message+'</h4><p>即将跳转登陆...</p></div>');
                        $('.down').empty().append(obj);
                        setTimeout(turn_to(url), 5000);
                    } else {
                        $('#password_error').text(res.message);
                    }
                });
            });
        });

        $(window).ready(function () {
            var index = 0;

            $(".i-text").keyup(function () {
                var inp_v = $(this).val();
                var inp_l = inp_v.length;
                var v = inp_v.substring(inp_l - 1, inp_l);
                if (index == inp_l + 1) {
                    v = '';
                    $(".sixDigitPassword").find("i").eq(index - 1).find("b").text(v);
                } else {
                    $(".sixDigitPassword").find("i").eq(index).find("b").text(v);
                }
                if (inp_l == 0) {
                    $(".sixDigitPassword").find("b").text('');
                }
                index = inp_l;
            });

            $(".i-text").blur(function () {
                ck_password();
            });

            $("#captcha").keyup(function () {
                if ($(this).val().length == 4) {
                    ck_captcha();
                    if (ckcaptcha) {
                        ckcaptcha = false;
                        $.post('{{asset('marketing/dispatch/checkCaptcha')}}', {
                            "captcha": $("#captcha").val(),
                            "identity": $("#identity").val()
                        }, function (data) {
                            if (data.code == 0) {
                                ckcaptcha = true;
                                $('#captcha_error').text('');
                            } else {
                                ckcaptcha = false;
                                $('#captcha_error').text('验证码错误');
                            }
                        });
                    }
                }
            });
        });

        function ck_password() {
            var pattern = /^\d{10}$/;
            if ($('.i-text').val() == '') {
                $('#password_error').text('卡密不能为空');
                ckpassword = false;
            } else if (!pattern.test($('.i-text').val())) {
                $('#password_error').text('请输入正确的10位卡密');
                ckpassword = false;
            } else {
                $('#password_error').text('');
                ckpassword = true;
            }
        }

        function ck_captcha() {
            var pattern = /^\d{4}$/;
            if ($('#captcha').val() == '') {
                $('#captcha_error').text('验证码不能为空');
                ckcaptcha = false;
            } else if (!pattern.test($('#captcha').val())) {
                $('#captcha_error').text('请输入正确的4位验证码');
                ckcaptcha = false;
            } else {
                $('#captcha_error').text('');
                ckcaptcha = true;
            }
        }

        function turn_to(url) {
            location.href = url;
        }

    </script>
@endsection



