@extends('inheritance')

@section('title')
    重置密码
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="/css/reset.css">
    <link rel="stylesheet" href="/css/main.css"/>
    <link rel="stylesheet" type="text/css" href="/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="/css/member.css">
    <link rel="stylesheet" type="text/css" href="/css/header.css">
    <link rel="stylesheet" type="text/css" href="/css/login.css">

@endsection

@section('content')
    <body style="background-color: #f3f5f7">
    <div id="header"></div>
    <section class="page">
        <div class="wrap regPage">
            <div class="input-box">
                <div class="input-container">
                    <input type="tel" id="username" class="acc-input telphone txt-input J_ping" placeholder="请输入手机号" report-eventid="MLoginRegister_PhoneInput">
                    <i class="icon icon-clear"></i>
                </div>
                <button data-mesCode="true" class="mesg-code mesg-disable J_ping" report-eventid="MLoginRegister_ReceiveMsgCheck" data-eventid="MLoginRegister_ReReceiveMsgCheck">获取短信验证码</button>
            </div>
            <div class="input-container">
                <input id="vcode" class="acc-input txt-input J_ping" type="text" placeholder="请输入短信验证码" autocomplete="off" report-eventid="MLoginRegister_MsgInput" >
                <i class="icon icon-clear"></i>
            </div>
            <div class="input-container">
                <input id="userpwd" type="password" class="acc-input password txt-input J_ping" placeholder="请设置6-20位新的密码" autocomplete="off" report-eventid="MLoginRegister_PasswordInput" >
                <i style="right: 0.1rem;" class="icon icon-clear"></i>
            </div>

            <div class="notice">&nbsp;</div>
            <div class="error-tips mt10"></div>
            <a href="javascript:;" id="regBtn" style="height: 45px;line-height: 30px;" class="btn J_ping" report-eventid="MLoginRegister_Finish">确 定</a>

        </div>
    </section>

</body>
@endsection

@section('js')
    <script type="text/javascript" src="/js/zepto.min.js"></script>
    <script type="text/javascript" src="/js/common.js"></script>
    <script type="text/javascript" src="/js/common-top.js"></script>
    <script type="text/javascript" src="/js/simple-plugin.js"></script>
    <script type="text/javascript" src="/js/member/forget_password.js"></script>

    <script>
        $(function(){
            $('input.J_ping').on('focus',function(){
                $(this).next('.icon-clear').css('display','inline').mouseover(function(){ $(this).prev().val(''); $(this).prev().focus();});
            });
            $('input.J_ping').on('mouseout',function(){
                $(this).next('.icon-clear').css('display','none');
            });
            $('input.J_ping').on('blur',function(){
                if($(this).val() == ''){
                    $(this).next('.icon-clear').css('display','none');$(this).next('.icon-clear').css('display','none');
                }
            });
            $('#username').on('keyup',function(){
                if($(this).val().match(reg)!= null){
                    $('button[data-eventid="MLoginRegister_ReReceiveMsgCheck"]').removeClass('mesg-disable').removeAttr('disabled').on('click',show_dialog);
                }else{
                    $('button[data-eventid="MLoginRegister_ReReceiveMsgCheck"]').addClass('mesg-disable').attr('disabled','true');
                }
            });
            $('input.J_ping').on('keyup',function(){
                var res = true;
                $('input.J_ping').each(function(){
                    if($(this).val() == null || ($(this).val() == '')){
                        res = false;
                    }
                });
                if(res){
                    $('a[report-eventid="MLoginRegister_Finish"]').addClass('btn-active');
                }else{
                    $('a[report-eventid="MLoginRegister_Finish"]').removeClass('btn-active');
                }
            });
            $('a[report-eventid="MLoginRegister_Finish"]').on("click",function(){
                if($(this).hasClass('btn-active')){
                    register();
                }
            });
        });
    </script>

@endsection

