@extends('inheritance')

@section('title')
    绑定手机号
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('sd_css/mui.min.css')}}">
    <link rel="stylesheet" href="{{asset('sd_css/common.css')}}">


    <style>
        .icon {
            width: 1em;
            height: 1em;
            vertical-align: -0.15em;
            fill: currentColor;
            overflow: hidden;
        }

        .head {
            width: 90%;
            margin: 30px auto 0;

        }

        .head li:first-child {
            width: 72px;
            height: 72px;
            overflow: hidden;
        }

        .head li:first-child img {
            width: 72px;
            height: 72px;
        }

        .head li:nth-child(2) {
            font-size: 64px;
            color: rgb(150, 150, 150);
        }

        .head li:nth-child(3) {
            font-size: 72px;
            color: rgb(245, 58, 58);
        }

        body {
            background-color: white;
        }

        .bind {
            width: 100%;
            margin-top: 40px;

        }

        .mui-input-row {
            width: 85%;
            height: 50px;
            margin: 20px auto 0;
            border-bottom: 1px solid rgb(220, 220, 220);
        }

        .mui-btn {
            background-color: #e42f46;
            border: 0;
            width: 85%;
            height: 46px;
            padding-top: 12px;
            margin: 40px auto 0;
            border-radius: 8px;
        }

        /*.yanzheng {*/
            /*width: 50%;*/
            /*border: 0 !important;*/
        /*}*/
        .head_nav .nav1{
            position: fixed;
            top: 0;
            padding: 0 4px;
            line-height: 40px;
            width:100%;
            z-index: 999999;
            font-family: "微软雅黑";
            background: #ffffff;
        }
        .head_nav .nav1 .chu{
            display: inline-block;
            width: 40%;
        }

        .btn {
            width: 120px;
            height: 30px;
            margin-top: 5px;
            color: deepskyblue;
            border: 0;
        }
    </style>
@endsection

@section('content')
    <body>
    <div class="head_nav">
        <div class="nav1 b-white">
            <a class="chu font-14 color-80" id="redirect_button"><img src="{{asset('sd_img/next.png')}}" alt="" class="size-26" style="transform:rotate(180deg);"></a>
            <a class="title font-14 color-80">绑定手机号</a>
        </div>
    </div>
    <ul class="head flex-around" style="margin-top:80px">
        <li class="avator">
            @if(!empty(Auth::user()->avatar))
                <img id="avatar"
                     src="{{Auth::user()->avatar}}"
                     style="border-radius: 60px;"/>
            @else
                <img style='border-radius: 60px;position: relative;z-index:0;height:80px;width:80px;margin-top:5%;'
                     src="{{asset('/sd_img/default_user_portrait.gif')}}">
            @endif
        </li>
        <li>
            <svg class="icon" aria-hidden="true">
                <use xlink:href="#icon-jiaohuan"></use>
            </svg>
        </li>
        <li>
            <svg class="icon" aria-hidden="true">
                <use xlink:href="#icon-shouji"></use>
            </svg>
        </li>
    </ul>


    <div class="bind">
        <input type="hidden" id="member_id" value="{{Auth::user()->member_id}}">

        <div class="mui-input-row">
            <input type="tel" id="mobile_num" class="mui-input-clear font-14" placeholder="请输入手机号码" style="border: 0px">
        </div>

        <div class="mui-input-row" style="display: flex;justify-content: space-between;">
            <input type="tel" class="yanzheng font-14" placeholder="请输入验证码" id="vcode" style="border: 0px">
            <button class="btn font-12 color-80" id="btn">获取验证码</button>
        </div>
    </div>
    <button type="button"
            data-loading-icon="mui-spinner mui-spinner-custom"
            data-loading-text="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;正在绑定"
            data-loading-icon-position="right"
            class="mui-btn mui-btn-blue mui-btn-block flex-center" id="bind_btn">
			<span style="margin-left:135px;margin-right:135px;">
        @if(Auth::user()->mobile_bind == 1)
            更改手机号
        @else
			立即绑定
		@endif
		</span>
    </button>
    </body>
@endsection

@section('js')
    <script src="{{asset('sd_js/jquery.min.js')}}"></script>
    <script src="{{asset('sd_js/mui.min.js')}}"></script>
    <script src="{{asset('sd_js/common.js')}}"></script>
    <script src="{{asset('sd_js/font_wvum.js')}}"></script>

    <script type="text/javascript">

        mui(document.body).on('tap', '.mui-btn', function (e) {
            mui(this).button('loading');
            setTimeout(function () {
                mui(this).button('reset');
            }.bind(this), 10000);
        });

        $(function () {
            var reg = /^(0|86|17951)?(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/;

            $("#btn").on('click', function () {
                if ($('#mobile_num').val() == '' || !reg.test($('#mobile_num').val())) {
                    message('请输入正确的手机号！');
                    return false;
                }

                send_code();
            });

			//跳转账号信息页面
			$("#redirect_button").on('click', function () {
				window.location.href = "/personal/userInfo";
			});
			
            $("#bind_btn").on('click', function () {

                if ($('#mobile_num').val() == '' || !reg.test($('#mobile_num').val())) {
                    message('请输入正确的手机号！');
                    return false;
                }

                if ($("#vcode").val() == '') {
                    message('请输入验证码！');
                    return false;
                }

                var member_id = $("#member_id").val();
                var mobile_num = $('#mobile_num').val();
                var vcode = $("#vcode").val();

                $.post('/personal/userMobileBindUpdate',
                        {
                            member_id: member_id,
                            mobile: mobile_num,
                            vcode: vcode
                        },
                        function (data) {
                            if (data.code == 0) {
                                message(data.message);
                                window.location.href = "/personal/userInfo";
                                return true;
                            } else {
                                message(data.message);
                                return false;
                            }
                        }
                        );
            });


            function send_code() {
                var count = 120;
                var countdown = setInterval(CountDown, 1000);

                function CountDown() {
                    $("#btn").attr("disabled", true);
                    $("#btn").text("重新获取" + count + " 秒");
                    if (count == 0) {
                        $("#btn").text("获取验证码").removeAttr("disabled");
                        clearInterval(countdown);
                    }
                    count--;
                }

                $.post('/personal/getYZM', {login_phonenum: $('#mobile_num').val()}, function (data) {
                    if (data.code != 0) {
                        message(data.message);
                        return false;
                    }
                });
            }
        });

    </script>
@endsection

