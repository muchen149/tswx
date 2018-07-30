@extends('inheritance')

@section('title')
    个人中心
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('elife_css/mui.min.css')}}">
    <link rel="stylesheet" href="{{asset('elife_css/tabbar.css')}}">
    <link rel="stylesheet" href="{{asset('elife_css/common.css')}}">
    <link rel="stylesheet" href="{{asset('elife_css/new_common.css')}}">
    <script src="{{asset('elife_js/font_wvum.js')}}"></script>

    <style>
        *{ margin:0; padding:0; list-style:none;}
        img{ border:0;}

        .icon1{
            width: 1em;
            height: 1em;
            vertical-align: -0.15em;
            fill: currentColor;
            overflow: hidden;
        }

        body{
            background-color: rgb(240,240,240);
        }

        .pop-msg-btm {
            text-align: center;
        }
       .pop-msg-cnt {
            line-height: 18px;
            min-height: 105px;
            padding: 10px;
            text-align: left;
        }
        /*订单*/

        .dingdan{
            margin-bottom: 10px;
            border-bottom: 1px solid rgb(220,220,220);

        }
        .dingdan .list-wrap,.zichan .list-wrap{
            width:100%;
            background-color: white;
        }
        .list-wrap ul{
            height: 44px;
            background-color: white;
        }
        .list-wrap ul li:first-child{
            width: 10%;
            text-align: right;

        }
        .list-wrap ul li:nth-child(2){
            width: 62%;
            text-align: left;

        }
        .list-wrap ul li:nth-child(3){
            width: 84px;
            text-align: center;

        }
        .dingdan .list,.zichan .list{
            width:95%;
            margin: 0 auto;
            height: 80px;
            background-color: white;
            display: box;			  /* OLD - Android 4.4- */
            display: -webkit-box;	  /* OLD - iOS 6-, Safari 3.1-6 */
            display: -moz-box;		 /* OLD - Firefox 19- (buggy but mostly works) */
            display: -ms-flexbox;	  /* TWEENER - IE 10 */
            display: -webkit-flex;	 /* NEW - Chrome */
            display: box;			  /* OLD - Android 4.4- */   display: -webkit-box;	  /* OLD - iOS 6-, Safari 3.1-6 */   display: -moz-box;		 /* OLD - Firefox 19- (buggy but mostly works) */   display: -ms-flexbox;	  /* TWEENER - IE 10 */   display: -webkit-flex;	 /* NEW - Chrome */   display: flex;			 /* NEW, Spec - Opera 12.1, Firefox 20+ */   /* 09版 */   -webkit-box-orient: horizontal;   /* 12版 */   -webkit-flex-direction: row;   -moz-flex-direction: row;   -ms-flex-direction: row;   -o-flex-direction: row;   flex-direction: row;;			 /* NEW, Spec - Opera 12.1, Firefox 20+ */   /* 09版 */
            -webkit-box-orient: horizontal;   /* 12版 */
            -webkit-flex-direction: row;
            -moz-flex-direction: row;
            -ms-flex-direction: row;
            -o-flex-direction: row;
            flex-direction: row;;
            flex-wrap: nowrap;
            align-items: center;
        }

        .dingdan .list .list-chid{
            text-align: center;
            width: 24%;
        }
        .zichan{
            margin-bottom: 10px;
            border-bottom: 1px solid rgb(220,220,220);
        }
        .zichan .list .list-chid{
            text-align: center;
            width: 24%;
        }
        .dingdan .list .list-chid img{
            height:25px;
        }
        .zichan .list .list-chid img{
            height:26px;
        }
        .dingdan .list .list-chid p:first-child{
            color: rgb(50,50,50);
            font-size: 14px;
            font-weight: bold;
            margin: 6px 0 0;
        }
        .zichan .list .list-chid p:first-child{
            color: red;
            font-size: 14px;
            font-weight: bold;
            margin: 6px 0 0;
        }
        .dingdan .list .list-chid p:last-child{
            color: rgb(140,140,140);
            font-size: 14px;
            margin: 6px 0 0;
        }
        .zichan .list .list-chid p:last-child{
            color: rgb(140,140,140);
            font-size: 14px;
            margin: 6px 0 0;
        }
        .dingdan .list a,.zichan .list a{
            color:rgb(120, 120, 120);
            text-decoration: none;
        }
        /*很多图标开始*/
        .fenlei{
            width: 100%;
            height: 104px;
            background-color: white;
            overflow: hidden;
        }

        .line{
            width: 92%;
            height: 104px;
            margin: 0 auto;
            background-color:white;
            display: box;			  /* OLD - Android 4.4- */   display: -webkit-box;	  /* OLD - iOS 6-, Safari 3.1-6 */   display: -moz-box;		 /* OLD - Firefox 19- (buggy but mostly works) */   display: -ms-flexbox;	  /* TWEENER - IE 10 */   display: -webkit-flex;	 /* NEW - Chrome */   display: flex;			 /* NEW, Spec - Opera 12.1, Firefox 20+ */   /* 09版 */   -webkit-box-orient: horizontal;   /* 12版 */   -webkit-flex-direction: row;   -moz-flex-direction: row;   -ms-flex-direction: row;   -o-flex-direction: row;   flex-direction: row;;
            justify-content: space-between;
            align-items: center;
        }
        .line .iCon{
            text-decoration: none;
            text-align: center;
        }
        .line .iCon p{
            color:rgb(120, 120, 120);
            font-size: 13px;
            width: 56px;
            margin-top: 6px;
        }
        .line .iCon img{
            width: 40px;
        }
        /*很多图标结束*/


        .head-wrap{
            width: 100%;
            background: #e42f46 url("../../sd_img/me-bg.png");
            background-size: 100%;
            height: 167px;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;

        }
        .head{
            position: relative;
            width: 90%;
            height: 100px;
            display: flex;
            justify-content: space-between;
            align-items: center;

        }
        .head .avator{
            width: 80px;
            height: 80px;
            border: 2px solid white;
            border-radius: 50px;
            overflow: hidden;
            box-shadow: 2px 2px 2px rgba(120,120,120,.4);
        }
        .head .equal{
            width: 70%;
            text-align: left;
        }
        .head .equal a:first-child{
            color: white;
            font-size: 14px;
            font-weight: bolder;
        }
        .head .equal a:last-child{
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 8px;
            width: 80px;
            height: 20px;
            color: white;
            font-size: 13px;
        }
        . .head .equal a:last-child img{
            width: 30px;
        }
        .head .next img{
            width: 36px;
        }
        .head .avator img{
            width: 80px;
            height: 80px;
        }
        .head a{
            text-decoration: none;
        }
        .head-set{
            display: block;
            position: absolute;
            top: -6px;
            right: -5px;
        }

        .bind-phone{
            position: fixed;
            width: 100%;
            height: 50px;
            bottom: 52px;
            background: rgba(0,0,0,.7);
        }
        .bind-phone a:active{
            display: inline-block;
            color: white;
            height: 26px;
        }
    </style>

@endsection

@section('content')
    <body>
    <!--头像区域开始-->
    <div class="head-wrap" style="margin-top:0px;">
        <ul class="head">
            <a href="{{asset('/elife/personal/accountSet')}}">
                <li class="avator">
                    @if(!empty($member->avatar))
                        <img id="avatar"
                             src="{{$member->avatar}}"
                             style="border-radius: 60px;"/>
                    @else
                        <img style='border-radius: 60px;position: relative;z-index:0;height:80px;width:80px;margin-top:5%;'
                             src='{{asset('sd_img/default_user_portrait.gif')}}'>
                    @endif
                </li>
            </a>
            <div class="equal">
                {{--<a href="judgement.html">--}}<li>{{$member->nick_name}}</li>{{--</a>--}}
                {{--<a href="buy_privilege.html">--}}
                {{--{{$subscribe}}--}}
                    <li>{{$member->mobile}}</li>
                {{--</a>--}}
            </div>
            {{--<a href="{{asset('/personal/accountSet')}}">
                <li class="next">
                    <svg class="icon1 font-26 color-white" aria-hidden="true"><use xlink:href="#icon-icon07"></use></svg>
                </li>
            </a>--}}
            <a href="{{asset('/elife/personal/accountSet')}}" class="head-set">
            <span>
                <svg class="icon1 font-22 color-white" aria-hidden="true" ><use xlink:href="#icon-shezhi"></use></svg>
            </span>
            </a>
        </ul>
    </div>

    <!--订单开始-->
    <div class="dingdan">
        <div class="list-wrap">
            <ul class="flex-between  color-80 border-b1">
                <li>
                    <svg class="icon1 font-26 color-f53a3a" aria-hidden="true" ><use xlink:href="#icon-dingdan"></use></svg>
                </li>
                <li class="font-14" style="margin-top: 2px">我的订单</li>
                <li>
                    <a href="{{asset('elife/order/index/0')}}" class="flex-between color-80">
                        <span class="font-12" style="margin-top: 2px">更多订单</span>
                        <img src="{{asset('sd_img/next.png')}}" alt="" class="size-28">
                    </a>
                </li>
            </ul>
            <div class="list">
                <a class="list-chid" href="{{asset('/elife/order/index/1')}}">
                    <p>{{$state_num_arr['payment_num']}}</p>
                    <p>待付款</p>
                </a>
                <a class="list-chid" href="{{asset('/elife/order/index/2')}}">
                    <p>{{$state_num_arr['delivered_num']}}</p>
                    <p>待发货</p>
                </a>
                <a class="list-chid" href="{{asset('/elife/order/index/3')}}">
                    <p>{{$state_num_arr['received_num']}}</p>
                    <p>待收货</p>
                </a>
                <a class="list-chid" href="{{asset('/elife/order/index/9')}}">
                    <p>{{$state_num_arr['success_num']}}</p>
                    <p>已完成</p>
                </a>
                <a class="list-chid" href="{{asset('/elife/order/saleOrderState/0')}}">
                    <p>{{$state_num_arr['refund_num']}}</p>
                    <p>退款/售后</p>
                </a>
            </div>
        </div>
    </div>

    <!--订单结束-->
    <div class="fenge"></div>
    <!--分割线-->

    <div class="main-nav">
        <ul>
            <li class=" navBtn"><a href="{{asset('/elife/eLifeIndex')}}"><span class="icon"></span><span class="text" style="color: #000000">首页</span></a></li>
            <li class=" navBtn"><a href="{{asset('/elife/cart/index')}}">@if($goods_num_in_cart < 100)<span class="cart_num">{{$goods_num_in_cart}}</span>@else<span class="cart_big_num">99+</span>@endif<span class="icon"></span><span class="text" style="color: #000000">购物车</span></a></li>
            <li class="active navBtn"><a href="{{asset('/elife/personal/index')}}"><span class="icon"></span><span class="text"  style="color: #000000">我的</span></a></li>
        </ul>
    </div>
{{--    <div id="pop-confirm" class="pop pop-ctm-01" style="display: none; position: fixed; top: 18%;left: 10%;align-self: center">
        <div class="pop-msg">
            <div id="confirm-content" class="pop-msg-cnt" style="padding-top:18px;min-height: 50px;font-size: 15px;">
                <img  style="width: 300px;height:322px;" src="{{asset('/sd_img/subscribe_pic.png')}}" usemap="#Map1"/>
                <map name="Map1">
                    <area shape="rect" coords="245,4,282,41" href="#">
                </map>
            </div>
            <img onclick="hideSubscribe();" style="width: 25px;height:25px;position: fixed;left: 50%;top:68%"  src="{{asset('/sd_img/close_sub.png')}}" />
        </div>
    </div>--}}
    </body>
@endsection

@section('js')
    <script src="//at.alicdn.com/t/font_b67h1q5eg2mlsor.js"></script>
    <script>
        $('.fenlei').last().css({
            "border-bottom":"1px solid rgb(220,220,220)",
            "margin-bottom":'150px'
        });
        $('.bind-phone').on('click',function(){
            $(this).fadeOut(1000);
        })

        $('.navBtn').click(function () {
            $(this).addClass("active").siblings().removeClass("active");
        });


    </script>

@endsection