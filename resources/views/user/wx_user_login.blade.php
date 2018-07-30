@extends('inheritance')

@section('title')
    登录
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="/css/reset.css">
    <link rel="stylesheet" href="/css/main.css"/>
    <link rel="stylesheet" type="text/css" href="/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="/css/member.css">
    <link rel="stylesheet" type="text/css" href="/css/header.css">
    <link rel="stylesheet" type="text/css" href="/css/login.css">
    <style>
        a:link, a:visited, a:hover, a:active{
            text-decoration:none;
        }

    </style>
@endsection

@section('content')
    <body>
    <div id="header"></div>
    <div class="login-form" style="display:block;margin:0 auto;padding: 0 0.15rem 0.2rem;">

            <span>
                <input type="text" placeholder="用户名/手机号" class="input-45" name="username" id="username"/>
            </span>
             <span>
                <input type="password" placeholder="请输入密码" class="input-45" name="password" id="password"/>
            </span>
            <div id="error-tips" class="error-tips mt10"></div>
            <a href="javascript:void(0);" class="l-btn-login mt20" id="loginbtn" style="border-radius: 3px;">
                登录
            </a>

            {{--<label for="remberme" class="remberme J_ping" report-eventid="MLoginRegister_AutoLogin">--}}
                {{--<input type="checkbox" id="remberme" checked>--}}
                {{--<span style="margin:0;" class="icon icon-rember"></span>--}}
                {{--7天内免登录--}}
            {{--</label>--}}
            <div class="quick-nav clearfix">
                <a href="javascript:;" class="J_ping findpwd" report-eventid="MLoginRegister_FindPassword"><i class="icon icon-clock"></i>忘记密码</a>
                <a href="javascript:;" class="J_ping quickReg" report-eventid="MLoginRegister_PhoneRegister"><i class="icon icon-reg"></i>快速注册</a>
            </div>
            <div class="quick-login">
                <h4>其他登录方式</h4>
                <a href="javascript:;" class="J_ping quick-wx" report-eventid="MLoginRegister_WxLogin"><i class="icon icon-wx"></i><br>微信</a>
            </div>
    </div>
</body>
@endsection

@section('js')
    <script type="text/javascript" src="/js/zepto.min.js"></script>
    <script type="text/javascript" src="/js/common.js"></script>
    <script type="text/javascript" src="/js/common-top.js"></script>
    <script type="text/javascript" src="/js/simple-plugin.js"></script>
    <script type="text/javascript" src="/js/member/login.js"></script>

    <script type="text/javascript">
        $('a.J_ping').click(function (){
            var id = $(this).attr('report-eventid').split('_')[1];
            var remberme = Number($('#remberme').attr('checked'));
            var url = '';
            var parameters = '<?php echo isset($_SERVER['QUERY_STRING'])?$_SERVER['QUERY_STRING']:"?location=user"?>';
            var oauth2_url = "/oauth2.php?"+parameters;
            switch (id){
                case 'PhoneRegister':
                    url = '/personal/userRegister';
                    break;
                case 'FindPassword':
                    url = '/personal/userPasswordView';
                    break;
                case 'WxLogin':
                    url = '/oauth';
                    break;
                default :
                    url = "/oauth";
                    break;
            }
            window.location.href=url;
        });
    </script>
@endsection

