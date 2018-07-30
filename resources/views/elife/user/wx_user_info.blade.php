@extends('inheritance')

@section('title')
    账号信息
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('sd_css/mui.min.css')}}">
    <link rel="stylesheet" href="{{asset('sd_css/common.css')}}">
    <script src="{{asset('sd_js/font_wvum.js')}}"></script>
    <style>
        body{
            position: relative;
        }

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

        .user-information-item{
            position: relative;
            width: 100%;
            height: 50px;
        }
        .user-information-item div{
            position: relative;
            width: 95%;
            height: 50px;
            margin-left:auto;
            margin-right: auto;
        }
        .user-information-item div ul img{
            width: 50px;
        }

    </style>

@endsection

@section('content')
    <body>
    <div class="head">
        <div class="nav b-white">
            <a class="return font-14 color-80" href="{{asset('/elife/personal/accountSet')}}"><img src="{{asset('sd_img/next.png')}}" alt="" class="size-26" style="transform:rotate(180deg);"></a>
            <a class="title font-14 color-80" href="#">账号信息</a>
        </div>
    </div>
    <div class="user-information-item flex-center b-white marginTop borderTop borderBottom" style="height: 80px;margin-top:50px;">
        <div class="flex-between">
            <ul class="user-information-item-left">
                <li class="font-14 color-80">头像</li>
            </ul>
            <ul  class="user-information-item-right">
                @if(!empty(Auth::user()->avatar))
                    <img id="avatar"
                         src="{{Auth::user()->avatar}}"
                         style="border-radius: 60px;"/>
                @else
                    <img style='border-radius: 60px;position: relative;z-index:0;height:80px;width:80px;margin-top:5%;'
                         src='/img/default_user_portrait.gif'>
                @endif
            </ul>
        </div>
    </div>

    <div class="user-information-item flex-center b-white marginTop borderTop ">
        <div class="flex-between borderBottom">
            <ul class="user-information-item-left">
                <li class="font-14 color-80">昵称</li>
            </ul>
            <ul class="user-information-item-right flex-between">
                <li class="font-12 color-100">{{Auth::user()->nick_name}}</li>

            </ul>
        </div>
    </div>

    <div class="user-information-item flex-center b-white">
        <div class="flex-between  borderBottom">
            <ul class="user-information-item-left">
                <li class="font-14 color-80">性别</li>
            </ul>
            <ul class="user-information-item-right flex-between">
                <li class="font-12 color-100">
                    @if(Auth::user()->sex == 1)
                        男
                    @elseif(Auth::user()->sex == 2)
                        女
                    @else
					    未知
					@endif
                </li>
            </ul>
        </div>
    </div>


        <div class="user-information-item flex-center b-white borderBottom" id="bind_mobile">
            <div class="flex-between  ">
                <input type="hidden" name="mobile" value="{{empty(Auth::user()->mobile) ? '' : Auth::user()->mobile}}">

                <ul class="user-information-item-left">
                    <li class="font-14 color-80">手机号码</li>
                </ul>
                <ul class="user-information-item-right flex-between">
                    <li class="font-12 color-100">
                        @if(Auth::user()->mobile)
                            {{Auth::user()->mobile}}
                        @else
                            绑定手机号
                        @endif
                    </li>
                    <li>
                        <svg class="icon font-24 color-100" aria-hidden="true" ><use xlink:href="#icon-icon07"></use></svg>
                    </li>

                </ul>
            </div>
        </div>





    </body>
@endsection

@section('js')
    <script type="text/javascript" src="/js/common-top.js"></script>
    <script>
        $(function () {
            var m = $("input[name=mobile]").val();
            $("#bind_mobile").on("click", function () {
//                alert(m);
//                if (m) {
//                    //不空说明已经绑定，跳到页面显示已绑定信息
//                    window.location.href = "/personal/userBindedView/" + m;
//                } else {
//                    //若空，未绑定，则跳到绑定页面
//                    window.location.href = "/personal/userMobileBindView";
//                }
                window.location.href = "/elife/personal/userMobileBindView";

            });



            $("#r_account").on('click', function () {
                window.location.href = "/personal/userRegister";

            });

            $("#set_pwd").on('click', function () {
                window.location.href = "/personal/userPasswordView";

            });

            $("#address_manage").on('click', function(){
                window.location.href = '{{asset('personal/address/addressList')}}';
            })
        });
    </script>
@endsection

