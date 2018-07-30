@extends('inheritance')

@section('title')
    绑定仓点管理员
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="/css/reset.css">
    <link rel="stylesheet" href="/css/main.css"/>
    <link rel="stylesheet" type="text/css" href="/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="/css/member.css">
    <link rel="stylesheet" type="text/css" href="/css/login.css">
    <style>
        a:link, a:visited, a:hover, a:active{
            text-decoration:none;
        }

    </style>
@endsection

@section('content')
    <body>
    <div class="login-form" style="display:block;margin:0 auto;padding: 0 0.15rem 0.2rem;">

            <span>
                <input type="text" placeholder="仓点管理员用户名" class="input-45" name="username" id="username"/>
            </span>
             <span>
                <input type="password" placeholder="密码" class="input-45" name="password" id="password"/>
            </span>
        <div id="error-tips" class="error-tips mt10"></div>
        <a href="javascript:void(0);" class="l-btn-login mt20" id="loginbtn" style="border-radius: 3px;">
            绑定
        </a>
    </div>
    </body>
@endsection

@section('js')
    <script type="text/javascript" src="/js/zepto.min.js"></script>
    <script type="text/javascript" src="/js/common.js"></script>
    <script type="text/javascript" src="/js/common-top.js"></script>
    <script type="text/javascript" src="/js/simple-plugin.js"></script>
    <script type="text/javascript">
        $('#loginbtn').on("click", function(){//绑定
            $username = $("#username").val();
            $password = $("#password").val();

            if($username == ''){
                message("用户名必须填写！");
                return false;
            }else if($password == ''){
                message("密码必填！");
                return false;
            }else{
                $.post( '/member/bindCdAdminSubmit',
                        {
                            username: $username,
                            password: $password
                        },
                        function(data){
                            if (data.code) {
                                window.location.href="/personal/index";
                                return true;
                            } else {
                                message(data.message);
                                return false;
                            }
                        });


            }

        });
    </script>
@endsection

