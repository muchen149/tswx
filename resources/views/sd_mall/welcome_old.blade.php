@extends('inheritance')
@section('水丁管家首页')
    管家中心
@endsection
@section('css')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <script src="http://www.anydo.wang/resource/js/jquery-3.1.1.js"></script>
    <link rel="stylesheet" href="{{asset('sd_css/mui.min.css')}}">
    <link rel="stylesheet" href="{{asset('sd_css/common.css')}}">
    <link rel="stylesheet" href="{{asset('sd_css/tabbar.css')}}">
    <style>
        .banner {
            width: 100%;
            background-color: white;
        }

        .banner img {
            width: 100%;
        }

        /*图标导航*/
        .top-nav {
            width: 100%;
            background-color: white;
            display: flex;
            justify-content: space-around;
            align-items: center;
        }

        .top-nav div {
            margin: 12px 0 6px;
            text-align: center;
        }

        .top-nav div img {
            width: 42px;
            margin-bottom: 4px;
        }

        .top-nav div a {
            color: rgb(80, 80, 80);
        }

        /*三个主题*/
        .theme-list {
            width: 100%;
            margin: 10px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .theme-list .theme {
            width: 32.5%;
            height: 110px;
            background-color: white;
            box-shadow: 0px 1px 1px rgba(150, 150, 150, 0.4);
        }

        .theme-list ul {
            text-align: center;
        }

        .theme-list ul .tit1 {
            font-size: 14px;
            font-weight: bolder;
            color: rgb(80, 80, 80);
            margin: 6px 0 0px;
        }

        .theme-list ul .tit2 {
            font-size: 12px;
            color: rgb(80, 80, 80);
            margin-bottom: 6px;
        }

        .theme-list ul img {
            width: 52px;
        }

        /*横向导航*/
        .normal {
            background-color: white;
            width: 100%;
            height: 40px;
        }

        .normal ul {
            width: 100%;
            height: 40px;
            display: flex;
            /*justify-content: space-around;*/
            align-items: center;
        }

        .normal ul li {
            height: 23px;
            box-sizing: border-box;
            font-size: 13px;
        }

        .normal .select {
            /*padding-bottom: 4px;*/
            border-bottom: 2px solid #f53a3a;
            color: #f53a3a;
        }

        .fixed {
            position: fixed;
            top: 0;
            z-index: 99999;
            background-color: white;
            width: 100%;
            height: 40px;
            box-shadow: 0px 3px 3px rgba(120, 120, 120, 0.6);
        }

        .fixed ul {
            width: 100%;
            height: 40px;
            display: flex;
            justify-content: space-around;
            align-items: center;
        }

        .fixed ul li {
            height: 23px;
            box-sizing: border-box;
            font-size: 13px;
        }

        .fixed .select {
            /*padding-bottom: 4px;*/
            border-bottom: 2px solid #f53a3a;
            color: #f53a3a;
        }

        /*商品列表*/
        .sp-list {
            width: 96%;
            margin: 10px auto;
            background-color: white;
        }

        .sp-list .img {
            width: 100%;
            /*height: 180px;*/
        }

        .sp-list .img img {
            width: 100%;
            height: 100%;

        }

        .sp-list .content {
            width: 100%;
            /*height: 78px;*/
            box-shadow: 0px 2px 2px rgba(150, 150, 150, 0.4);
            border-radius: 0 0 4px 4px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /*左侧*/
        .content .cont-left {
            margin-left: 2%;
            width: 74%;
            /*height: 64px;*/
            height: 44px;
        }

        .title {
            font-size: 13px;
            color: rgb(50, 50, 50);
            font-weight: bolder;
            margin: 4px 0 4px;
            text-align: left;
            line-height: 20px;
            /*height: 40px;*/
            height: 20px;
            width: 99%;
            overflow: hidden;
        }

        .title li {
            width: 100%;
        }

        .prize {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0px;
            font-size: 12px;
            color: rgb(80, 80, 80);
        }

        /*右侧*/
        .content .cont-right {
            padding-left: 5%;
            margin-right: 4%;
            width: 15%;
            height: 64px;
            text-align: center;
            border-left: 1px solid rgb(150, 150, 150);
        }

        .content .cont-right img {
            width: 26px;
            margin: 7px 0 8px;
        }

        .amount-style1 {
            font-size: 13px;
            color: rgb(120, 120, 120);
        }

        /*点击加粗*/
        .amount-style2 {
            font-size: 13px;
            color: #f53a3a;
            font-weight: bold;

        }

        #goods-list {
            margin-bottom: 56px;
        }

        #loading {
            text-align: center;
        }
    </style>
@endsection
@section('content')
    <body>
    <div class="banner">
        {{--<a href="{{asset('member/my_member')}}"><img src="{{asset('sd_img/index_banner.jpg')}}" alt=""></a>--}}
        <img src="{{asset('sd_img/index_banner.jpg')}}" alt="">
    </div>

    <div class="top-nav">
        <div>
            <a href="{{asset('member/center')}}">
                <img src="{{asset('sd_img/index_topnav_icon1.png')}}" alt="">
                <p class="font-12">管家中心</p>
            </a>
        </div>
        <div>
            <a href="{{asset('personal/wallet/rechargeCards/myRechargeCard')}}">
                <img src="{{asset('sd_img/index_topnav_icon2.png')}}" alt="">
                <p class="font-12">充值中心</p>
            </a>
        </div>
        <div>
            {{--<a href="{{asset('sd_shop/article_everyday')}}">
                <img src="{{asset('sd_img/index_topnav_icon3.png')}}" alt="">
                <p class="font-12">每日播报</p>
            </a>--}}
            <a href="{{asset('member/inviteFriend')}}">
                <img src="{{asset('sd_img/me_icon_add.png')}}" alt="">
                <p class="font-12">邀请好友</p>
            </a>
        </div>
        <div>
            {{--<a href="service_life.html">
                <img src="{{asset('sd_img/index_topnav_icon4.png')}}" alt="">
                <p class="font-12">生活服务</p>
            </a>--}}
            <a href="{{asset('gift/index')}}">
                <img src="{{asset('sd_img/me_icon_lipin.png')}}" alt="">
                <p class="font-12">微信送礼</p>
            </a>
        </div>
        <div>
            {{--<a href="{{asset('sd_shop/service_more')}}">
                <img src="{{asset('sd_img/index_topnav_icon5.png')}}" alt="">
                <p class="font-12">更多服务</p>
            </a>--}}
            <a href="{{asset('order/index')}}">
                <img src="{{asset('sd_img/index_topnav_icon5.png')}}" alt="">
                <p class="font-12">我的订单</p>
            </a>
        </div>
    </div>
{{--    <div class="theme-list">

        <div class="theme">
            <a href="{{asset('gift/index')}}">
                <ul>
                    <li class="tit1">微信送礼</li>
                    <li class="tit2">分享你的精彩</li>
                    <li><img src="{{asset('sd_img/index_theme_1.png')}}" alt=""></li>
                </ul>
            </a>
        </div>

        <div class="theme">
            <a href="share_judgement.html">
                <ul>
                    <li class="tit1">管家分享</li>
                    <li class="tit2">管家独家分享</li>
                    <li><img src="{{asset('sd_img/index_theme_2.png')}}" alt=""></li>
                </ul>
            </a>
        </div>
        <div class="theme">
            <a href="{{asset('member/callFriend')}}">
                <ul>
                    <li class="tit1">呼朋唤友</li>
                    <li class="tit2">意气相投一起嗨</li>
                    <li><img src="{{asset('sd_img/index_theme_3.png')}}" alt=""></li>
                </ul>
            </a>
        </div>
    </div>--}}
    <div class="normal" id="col-nav">
        <ul>
            {{--<li class="select">推荐</li>
            <li>最新</li>
            <li>热门</li>
            <li>精选食材</li>
            <li>家政优选</li>--}}
            <li class="select" style="margin-left: 20px;">精品生活</li>
        </ul>
    </div>

    <div id="goods-list">
        @foreach($goodsSpuList as $good)
            <div class="sp-list">
                <a href="{{asset('/sd_shop/goods/spuDetail/'.$good->spu_id)}}">
                    <div class="img"><img src="{{$good->main_image}}"></div>
                </a>

                <div class="content">
                    <div class="cont-left">
                        <ul class="title ">
                            <li>{{$good->spu_name}}</li>
                        </ul>
                        <ul class="prize">
                            <li class="amount-style2">¥{{$good->spu_price}}</li>
                            <li></li>
                        </ul>
                    </div>

                    {{--<div class="cont-right">
                        <img src="{{asset('sd_img/like.png')}}" alt="" style="display: none">
                        <img src="{{asset('sd_img/unlike.png')}}" alt="">
                        <p class="amount-style1">125</p>
                    </div>--}}
                </div>
            </div>
        @endforeach
    </div>

    <div id="loading" hidden>
        正在加载数据...
        <img style="padding-bottom: 5px" src="{{asset('img/loading.gif')}}" width="24" height="24">
    </div>

    <div class="tabbar">
        <a href="{{asset('/shop/index')}}">
            <ul>
                <li><img src="{{asset('sd_img/tab_index_set.png')}}" alt=""></li>
                <li class="tabbar-set">首页</li>
            </ul>
        </a>
       {{-- <a href="{{asset('member/call')}}">
            <ul>
                <li><img src="{{asset('sd_img/tab_call_pre.png')}}" alt=""></li>
                <li>召唤管家</li>
            </ul>
        </a>--}}
        <a href="{{asset('shop/goods/spuList')}}">
            <ul>
                <li><img src="{{asset('img/tab_lei_pre.png')}}" alt=""></li>
                <li>商品列表</li>
            </ul>
        </a>
        <a href="{{asset('/cart/index')}}">
            <ul>
                <li><img src="{{asset('sd_img/tab_car_pre.png')}}" alt=""></li>
                <li>购物车</li>
            </ul>
        </a>
        <a href="{{asset('/personal/index')}}">
            <ul>
                <li><img src="{{asset('sd_img/tab_user_pre.png')}}" alt=""></li>
                <li>我的</li>
            </ul>
        </a>
    </div>
    </body>
@endsection
@section('js')
    <script type="text/javascript">
        var is_request = true;
        var goodslist_ajax_params = {
            allRows: '{{$separatePage['allRows']}}',
            pageNumber: '{{$separatePage['pageNumber']}}',
            pageSize: '{{$separatePage['pageSize']}}',
            gcId: '{{$queryParameter['gcId']}}',
            goodsName: '{{$queryParameter['goodsName']}}',
            orderBy: '{{$queryParameter['orderBy']}}'
        };

        $(function () {
            $('#col-nav li').on('click', function (e) {
                var current = e.currentTarget;
                $(this).addClass('select');
                $('#col-nav li').not(current).removeClass('select');
            });

            $('.cont-right').click(function (e) {
                var target = e.currentTarget;
                $(target).find('img').toggle();
                $(target).find('p').toggleClass('amount-style2');
            });
        });

        window.onscroll = function () {
            var clients = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
            var scrollTop = document.body.scrollTop;
            var wholeHeight = document.body.scrollHeight;

            if (scrollTop >= 429.14) {
                $('#col-nav').attr('class', 'fixed');
            } else {
                $('#col-nav').attr('class', 'normal');
            }

            if (clients + scrollTop >= wholeHeight && is_request) {
                loading_div_show(true);
                ajax_loading_goods();
            }
        };

        function ajax_loading_goods() {
            var api_domain = window.location.protocol + '//' + document.domain;

            goodslist_ajax_params.pageNumber = parseInt(goodslist_ajax_params.pageNumber) + 1;
            $.get(api_domain + '/sd_shop/goods/ajax/spuList/'
                    + goodslist_ajax_params.pageNumber + '/'
                    + goodslist_ajax_params.pageSize + '/'
                    + goodslist_ajax_params.gcId + '/'
                    + goodslist_ajax_params.orderBy + '/'
                    + goodslist_ajax_params.goodsName,
                    function (res) {
                        var data = res.data;
                        var goodslist = data.goodsSpuList;

                        if (goodslist.length == 0) {
                            is_request = false;
                            $('#loading').html('全部加载完成');
                            loading_div_show(true);
                            return;
                        }

                        var goods_list_dom = $('#goods-list');
                        var append_html = '';
                        for (var i = 0; i < data.goodsSpuList.length; i++) {
                            append_html +=
                                    '<div class="sp-list">' +
                                    '<a href="' + api_domain + '/sd_shop/goods/spuDetail/' + data.goodsSpuList[i].spu_id + '">' +
                                    '<div class="img"><img src="' + data.goodsSpuList[i].main_image + '">' + '</div>' +
                                    '</a>' +
                                    '<div class="content">' +
                                    '<div class="cont-left">' +
                                    '<ul class="title">' +
                                    '<li>' + data.goodsSpuList[i].spu_name + '</li>' +
                                    '</ul>' +
                                    '<ul class="prize">' +
                                    '<li class="amount-style2">¥' + data.goodsSpuList[i].spu_price + '</li>' +
                                    '</ul>' +
                                    '</div>' +
                                    /*'<div class="cont-right">' +
                                    '<img src="' + api_domain + '/sd_img/like.png" style="display: none">' +
                                    '<img src="' + api_domain + '/sd_img/unlike.png">' +
                                    '<p class="amount-style1">125</p>' +
                                    '</div>' +*/
                                    '</div>' +
                                    '</div>';
                        }
                        goods_list_dom.html(goods_list_dom.html() + append_html);
                        loading_div_show(false);
                    }
            );
        }

        function loading_div_show(flag) {
            if (flag) {
                $('#loading').css('margin-bottom', '60px').show();
                $('#goods-list').css('margin-bottom', '0');
            } else {
                $('#loading').css('margin-bottom', '0').hide();
                $('#goods-list').css('margin-bottom', '60px');
            }
        }
    </script>
@endsection
