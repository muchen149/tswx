@extends('inheritance')

@section('title')
    个人中心
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('css/personal.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('css/main.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/header.css')}}">
@endsection

@section('content')
    <body>
    <div id="header"></div>
    <!--头像区域开始-->
    <div class="user">
        <a class="setting" href="{{asset('/personal/userInfo')}}"><img src="{{asset('img/setting.png')}}" alt=""></a>
        <div class="user-left">
            @if(!empty($member->avatar))
                <img id="avatar"
                     src="{{$member->avatar}}"
                     style="border-radius: 60px;"/>
            @else
                <img style='border-radius: 60px;position: relative;z-index:0;height:80px;width:80px;margin-top:5%;'
                     src='{{asset('img/default_user_portrait.gif')}}'>
            @endif
        </div>
        <div class="user-right">
            <p>
                <span id="username">{{$member->nick_name}}</span>
                <span class="badge"
                      style="background-color: red;font-size: 10px">{{$member->grade_name}}</span>
            </p>
            <p>精致生活，品味人生！</p>
        </div>
    </div>
    <!--头像区域结束-->

    <!--订单开始-->
    <div class="dingdan">
        <div class="list-wrap">
            <div class="list">
                <a class="list-chid" href="{{asset('order/index')}}">
                    <p>{{$state_num_arr['all_num']}}</p>
                    <p>全部</p>
                </a>
                <a class="list-chid" href="{{asset('order/index/1')}}">
                    <p>{{$state_num_arr['payment_num']}}</p>
                    <p>待付款</p>
                </a>
                <a class="list-chid" href="{{asset('order/index/2')}}">
                    <p>{{$state_num_arr['delivered_num']}}</p>
                    <p>待发货</p>
                </a>
                <a class="list-chid" href="{{asset('order/index/3')}}">
                    <p>{{$state_num_arr['received_num']}}</p>
                    <p>待收货</p>
                </a>
                <a class="list-chid" href="{{asset('order/index/9')}}">
                    <p>{{$state_num_arr['success_num']}}</p>
                    <p>已完成</p>
                </a>
                {{--<a class="list-chid" href="{{asset('order/index/4')}}">--}}
                {{--<p>{{$state_num_arr['pingjia_num']}}</p>--}}
                {{--<p>待评价</p>--}}
                {{--</a>--}}
            </div>
        </div>
    </div>
    <!--订单结束-->
    <div class="fenge"></div>
    <!--分割线-->
    <div class="zichan">
        <div class="list-wrap">
            <div class="list">
                <a class="list-chid" href="{{asset('personal/walletLog')}}">
                    <p>￥{{$member->wallet_available}}</p>
                    <p>零钱</p>
                </a>
                <a class="list-chid" href="{{asset('personal/wallet/rechargeCards/myRechargeCard')}}">
                    <p>￥{{$member->card_balance_available}}</p>
                    <p>卡余额</p>
                </a>
                <a class="list-chid" href="{{asset('personal/vrcoinLog')}}">
                    <p>{{intval($member->yesb_available)}}</p>
                    <p>{{$plat_vrb_caption}}</p>
                </a>
                <a class="list-chid">
                    <p>0</p>
                    <p>优惠券</p>
                </a>
                <a class="list-chid" href="{{asset('personal/wallet/giftCoupons/myGiftCoupon')}}">
                    <p>{{$wallet_num_arr['coupon_num']}}</p>
                    <p>礼券</p>
                </a>
            </div>
        </div>
    </div>
    <div class="fenge"></div>
    <!--第一行开始-->
    <div class="fenlei1">
        <div class="line">
            <a class="icon" href="{{asset('personal/vrcoinLog')}}">
                <img src="{{asset('img/jifen.png')}}">

                <p>我的{{$plat_vrb_caption}}</p>
            </a>
            {{--<a class="icon" href="{{asset('personal/address/addressList')}}">
                <img src="{{asset('img/dizhi.png')}}">

                <p>地址管理</p>
            </a>--}}
            <a class="icon" href="{{asset('personal/wallet/giftCoupons/myGiftCoupon')}}">
                <img src="{{asset('img/manger_gift.png')}}">
                <p>我的礼包</p>
            </a>
            <a class="icon" href="{{asset('personal/awardsRecord')}}">
                <img src="{{asset('img/jilu.png')}}">
                <p>中奖记录</p>
            </a>
            <a class="icon" href="{{asset('personal/browse/list/2')}}">
                <img src="{{asset('img/zuji.png')}}">

                <p>我的足迹</p>
            </a>

        </div>
    </div>
    <!--第一行结束-->

    <!--第二行开始-->
    <div class="fenlei2">
        <div class="line">
            <a class="icon" href="{{asset('personal/collect/list/2')}}">
                <img src="{{asset('img/shoucang.png')}}">
                <p>我的收藏</p>
            </a>

            {{--<a class="icon" href="wx_order_return.php">
                <img src="{{asset('img/help.png')}}">
                <p>售后服务</p>
            </a>--}}

            <a class="icon" href="{{asset('marketing/dispatch/checkNumberPage')}}">
                <img src="{{asset('img/bind_card.png')}}">
                <p>绑卡</p>
            </a>

            <a class="icon" href="{{asset('personal/joininList')}}">
                <img src="{{asset('img/apply.png')}}">
                <p>用户申请</p>
            </a>

            <a class="icon" href="{{asset('gift/index')}}">
                <img src="{{asset('img/apply.png')}}">
                <p>微信送礼</p>
            </a>

            {{--<a class="icon">--}}
            {{--<img src="{{asset('img/default.png')}}">--}}
            {{--<p></p>--}}
            {{--</a>--}}
        </div>
    </div>
    <!--第二行结束-->

    <!--第三行开始-->
    <div class="fenlei2">
        <div class="line">
            <a class="icon" href="{{asset('personal/membership/buyStep1')}}">
                <img src="{{asset('img/shoucang.png')}}">
                <p>会员购买</p>
            </a>
            <a class="icon">
                <img src="{{asset('img/default.png')}}">
                <p></p>
            </a>
            <a class="icon">
                <img src="{{asset('img/default.png')}}">
                <p></p>
            </a>
            <a class="icon">
                <img src="{{asset('img/default.png')}}">
                <p></p>
            </a>
        </div>
    </div>

    <!--第三行结束-->
    <div class="banner" style="margin-bottom:25px;">
        <img src="{{asset('img/banner.png')}}" alt="" class="img-rounded">
    </div>

    <!--第三行结束-->
    <footer id="footer"></footer>

    </body>
@endsection

@section('js')
    <script type="text/javascript" src="{{asset('js/common-top.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/footer.js')}}"></script>
@endsection



