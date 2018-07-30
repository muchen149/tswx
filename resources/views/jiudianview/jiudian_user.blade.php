@extends('inheritance')

{{--@section('title')
    个人中心
@endsection--}}

@section('css')
    <title>大美管家-更贴心的家政服务</title>
    <link rel="stylesheet" href="{{asset('sd_css/mui.min.css')}}">
    <link rel="stylesheet" href="{{asset('sd_css/tabbar.css')}}">
    <link rel="stylesheet" href="{{asset('sd_css/common.css')}}">
    <link rel="stylesheet" href="{{asset('/sd_css/new_common.css')}}">
    <script src="{{asset('sd_js/font_wvum.js')}}"></script>

    <style>
        *{ margin:0; padding:0; list-style:none;}
        img{ border:0;}

        .lanrenzhijia {position: fixed;left: -100%;right:100%;top:0;bottom: 0;text-align: center;font-size: 0; z-index:9999; display:none;}
        .lanrenzhijia:after {content:"";display: inline-block;vertical-align: middle;height: 100%;width: 0;}
        .content{display: inline-block; *display: inline; *zoom:1;	vertical-align: middle;position: relative;right: -100%;}
        .content_mark{ width:100%; height:100%; position:fixed; left:0; top:0; z-index:555; background:#000; opacity:0.5;filter:alpha(opacity=50); display:none;}

        .icon1{
            width: 1em;
            height: 1em;
            vertical-align: -0.15em;
            fill: currentColor;
            overflow: hidden;
        }

        body{
            background-color: rgb(240,240,240);
            padding-bottom: 20px;
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
            font-size: 12px;
            font-weight: bold;
            margin: 6px 0 0;
        }
        .zichan .list .list-chid p:first-child{
            color: red;
            font-size: 12px;
            font-weight: bold;
            margin: 6px 0 0;
        }
        .dingdan .list .list-chid p:last-child{
            color: rgb(140,140,140);
            font-size: 12px;
            margin: 6px 0 0;
        }
        .zichan .list .list-chid p:last-child{
            color: rgb(140,140,140);
            font-size: 12px;
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
            background: #fb6159 url("../../sd_img/me-bg.png");
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
            width: 53%;
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
        .list .list-chid{
            position: relative;
        }
        .list .list-chid .num_one{
            position: absolute;
            left: 57%;top:-9%;
            border-radius:50%;
            height:26%;
            width:20%;
            background: red;
            font-size:10px;
            line-height: 200%;
            color:#fff;
        }
        .list .list-chid .num_two{
            position: absolute;
            left: 57%;top:-9%;
            border-radius:15%;
            height:26%;
            background: red;
            font-size:10px;
            line-height: 200%;
            color:#fff;
        }
    </style>

@endsection

@section('content')
    <body>
    <!--头像区域开始-->
    <div class="head-wrap">
        <ul class="head">
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
            <div class="equal">
                {{--<a href="judgement.html">--}}<li>{{$member->nick_name}}</li>{{--</a>--}}
                {{--<a href="buy_privilege.html">--}}
                {{--{{$subscribe}}--}}
                    <li>{{$member->grade_name}}</li>
                {{--</a>--}}
            </div>
           {{-- <a href="{{asset('/personal/accountSet')}}">
                <li class="next">
                    <svg class="icon1 font-26 color-white" aria-hidden="true"><use xlink:href="#icon-icon07"></use></svg>
                </li>
            </a>--}}
            {{--<span>--}}
                {{--<svg class="icon1 font-22 color-white" aria-hidden="true" ><use xlink:href="#icon-shezhi"></use></svg>--}}
            {{--</span>--}}
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
                    <a href="{{asset('order/index')}}" class="flex-between color-80">
                        <span class="font-12" style="margin-top: 2px">更多订单</span>
                        <img src="{{asset('sd_img/next.png')}}" alt="" class="size-28">
                    </a>
                </li>
            </ul>
            <div class="list">
                <a class="list-chid" href="{{asset('order/index/1')}}">
                    <img src="{{asset('ys_img/fu_01.png')}}" alt="">
                    @if(strlen($state_num_arr['payment_num']) < 3)
                        <span class="num_one">{{$state_num_arr['payment_num']}}</span>
                    @else
                        <span class="num_two">{{$state_num_arr['payment_num']}}</span>
                    @endif
                    {{--<p>{{$state_num_arr['payment_num']}}</p>--}}
                    <p>待付款</p>
                </a>
                <a class="list-chid" href="{{asset('order/index/2')}}">
                    <img src="{{asset('ys_img/fa_02.png')}}" alt="">
                    @if(strlen($state_num_arr['delivered_num']) < 3)
                        <span class="num_one">{{$state_num_arr['delivered_num']}}</span>
                    @else
                        <span class="num_two">{{$state_num_arr['delivered_num']}}</span>
                    @endif
                    {{--<p>{{$state_num_arr['delivered_num']}}</p>--}}
                    <p>待发货</p>
                </a>
                <a class="list-chid" href="{{asset('order/index/3')}}">
                    <img src="{{asset('ys_img/shou_03.png')}}" alt="">
                    @if(strlen($state_num_arr['received_num']) < 3)
                        <span class="num_one">{{$state_num_arr['received_num']}}</span>
                    @else
                        <span class="num_two">{{$state_num_arr['received_num']}}</span>
                    @endif
                    {{--<p>{{$state_num_arr['received_num']}}</p>--}}
                    <p>待收货</p>
                </a>
                <a class="list-chid" href="{{asset('order/index/9')}}">
                    <img src="{{asset('ys_img/complete_04.png')}}" alt="">
                    @if(strlen($state_num_arr['success_num']) < 3)
                        <span class="num_one">{{$state_num_arr['success_num']}}</span>
                    @else
                        <span class="num_two">{{$state_num_arr['success_num']}}</span>
                    @endif
                    {{--<p>{{$state_num_arr['success_num']}}</p>--}}
                    <p>已完成</p>
                </a>
                {{--<a class="list-chid" href="{{asset('order/index/4')}}" >--}}
                    {{--<p>{{$state_num_arr['pingjia_num']}}</p>--}}
                    {{--<p>待评价</p>--}}
                {{--</a>--}}
            </div>
        </div>
    </div>


    <!--订单结束-->
    <div class="swiper-container shop-banner">
        <div class="swiper-wrapper">
            <div class="swiper-slide"><a href="/shop/goods/spuList/1/10/16/0/0/2"><img src="/ys_img/ad_ys.jpg" width="100%" alt=""></a></div>
        </div>
        <!-- Add Pagination -->
        {{--<div class="swiper-pagination"></div>--}}
    </div>
    <!--第一行开始-->
    <div class="fenlei">
        <div class="line">
            <a class="iCon" href="{{asset('/ys/shop')}}">
                <img src="{{asset('ys_img/jpsj.png')}}">
                <p>精品集市</p>
            </a>

            <a class="iCon" href="{{asset('gift/index')}}">
                <img src="{{asset('sd_img/me_icon_lipin.png')}}">
                <p>微信送礼</p>
            </a>

            <a class="iCon" href="{{asset('personal/address/addressList')}}">
                <img src="{{asset('ys_img/address_39.png')}}">
                <p>常用地址</p>
            </a>

            <a class="iCon" href="">
                <img src="{{asset('ys_img/link_40.png')}}">
                <p>分享链接</p>
            </a>
        </div>

            {{-- <a class="iCon" href="{{asset('member/callFriend/index')}}">
                 <img src="{{asset('sd_img/me_icon_call.png')}}">
                 <p>呼朋唤友</p>
             </a>

             <a class="iCon" href="{{asset('personal/perfectApply/groupbuy')}}">
                 <img src="{{asset('sd_img/me_icon_service.png')}}">
                 <p>服务中心</p>
             </a>--}}

           {{-- <a class="iCon" href="{{asset('personal/browse/list/2')}}">
                <img src="{{asset('sd_img/zuji.png')}}">
                <p>我的足迹</p>
            </a>
            <a class="iCon" href="{{asset('personal/awardsRecord')}}">
                <img src="{{asset('sd_img/jilu.png')}}">
                <p>中奖记录</p>
            </a>--}}
    </div>
    <div class="fenlei">
        <div class="line">
            <a class="iCon" href="">
                <img src="{{asset('ys_img/report_41.png')}}">
                <p>投诉举报</p>
            </a>

            <a class="iCon" href="">
                <img src="{{asset('ys_img/feedback_42.png')}}">
                <p>建议反馈</p>
            </a>
            <a class="iCon" href="javascript:void(0);"><p></p>
            </a>
            <a class="iCon" href="javascript:void(0)"><p></p>
            </a>
        </div>
    </div>
    <div class="fenge" style="padding-bottom: 20px;"></div>
    <div class="ysmain-nav">
        <ul>
            <li class="navBtn"><a href="{{asset('/ys')}}"><span class="icon"></span><span class="text">首页</span></a></li>
            <li class="navBtn"><a href="/ys/order/index"><span class="icon"></span><span class="text">服务单</span></a></li>
            {{--<li class="navBtn"><a href="{{asset('/cart/index')}}"><span class="icon"></span><span class="text">购物车</span></a></li>--}}
            <li class="active navBtn"><a href="{{asset('/ys/index')}}"><span class="icon"></span><span class="text">我的</span></a></li>
        </ul>
    </div>
    <div id="pop-confirm" class="pop pop-ctm-01" style="display: none; position: fixed; top: 15%;left: 10%;">
        <div class="pop-msg">
            <div id="confirm-content" class="pop-msg-cnt" style="padding-top:18px;min-height: 50px;font-size: 15px;">
                <img  style="width: 300px;height:322px;" src="{{asset('/sd_img/subscribe_pic.png')}}" />
            </div>
            <img onclick="hideSubscribe();" style="width: 25px;height:25px;position: fixed;left: 50%;top:68%"  src="{{asset('/sd_img/close_sub.png')}}" />
        </div>
    </div>

   {{-- @if($member->mobile_bind == 0)
        <div class="bind-phone flex-around">
            <a href="/personal/userMobileBindView" class="color-white flex-between">
                <svg class="icon1"  style="font-size: 26px" aria-hidden="true"><use xlink:href="#icon-shouji"></use></svg>
                <span class="font-14">绑定手机号登录更加方便!</span>
            </a>
            <svg class="icon1 color-white font-26"aria-hidden="true"><use xlink:href="#icon-iconfonterror2" class="bind-phone"></use></svg>

        </div>
    @endif--}}

    </body>
@endsection

@section('js')

    <script src="http://www.anydo.wang/resource/js/jquery-3.1.1.js"></script>
    <script src="//at.alicdn.com/t/font_b67h1q5eg2mlsor.js"></script>
    <script>
        /*$('.fenlei').last().css({
            "border-bottom":"1px solid rgb(220,220,220)",
            "margin-bottom":'150px'
        });
        $('.bind-phone').on('click',function(){
            $(this).fadeOut(1000);
        })*/

        $('.navBtn').click(function () {
            $(this).addClass("active").siblings().removeClass("active");
        });

        /*function hideSubscribe(){
            $("#pop-confirm").css('display','none');
        }*/

    </script>
   {{-- @if($subscribe==0)
        <script>
            $("#pop-confirm").css('display','block');
        </script>
    @endif--}}
@endsection



