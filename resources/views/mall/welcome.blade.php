@extends('inheritance')
@section('css')
    <link href="{{asset('css/wxindex/home_index.css')}}" rel="stylesheet">
    <link href="{{asset('css/wxindex/index.css')}}" rel="stylesheet">
    {{--<link href="{{asset('css/wxindex/font-awesome.min.css')}}" rel="stylesheet">--}}
    <link href="{{asset('css/main.css')}}" rel="stylesheet">
    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet">
<style>
    .main-wrap {
        display: box; /* OLD - Android 4.4- */
        display: -webkit-box; /* OLD - iOS 6-, Safari 3.1-6 */
        display: -moz-box; /* OLD - Firefox 19- (buggy but mostly works) */
        display: -ms-flexbox; /* TWEENER - IE 10 */
        display: -webkit-flex; /* NEW - Chrome */
        display: flex; /* NEW, Spec - Opera 12.1, Firefox 20+ */ /* 09版 */
        -webkit-box-orient: horizontal; /* 12版 */
        -webkit-flex-direction: row;
        -moz-flex-direction: row;
        -ms-flex-direction: row;
        -o-flex-direction: row;
        flex-direction: row;;
        justify-content: space-around;
        align-items: center;
        background-color: white;
        height: 80px;
    }

    .icon {
        height: 60px;
        text-align: center;
    }

    .icon img {
        width: 40px;
        height: 40px;

    }

    .icon p {
        margin-top: 4px;
        font-size: 13px;
        color: rgb(100, 100, 100);
    }

    .adv_1 {
        height: 120px;
    }

    .adv_1 img {
        width: 100%;
        height: 120px;
    }

    .adv-2-1, .adv-2-2 {
        width: 100%;
        background-color: white;
        overflow: hidden;
        border-bottom: 1px solid rgb(220, 220, 220);
    }

    .adv-2-1 .left {
        width: 50%;
        background-color: white;
        float: left;
        box-sizing: border-box;
        border-right: 1px solid rgb(220, 220, 220);
    }

    .adv-2-2 .left {
        width: 50%;
        background-color: white;
        float: left;
        box-sizing: border-box;
        border-right: 1px solid rgb(220, 220, 220);
    }

    .adv-2-1 .right, .adv-2-2 .right {
        width: 50%;
        background-color: white;
        float: right;
    }

    .adv-2-1 img, .adv-2-2 img {
        width: 100%;
    }

    .adv-3 {
        width: 100%;
        background-color: white;
        margin-top: 10px;
    }

    .adv-3 img {
        width: 100%;
    }

    .search {
        width: 100%;
        background-color: #E83828;
    }

    .search-wrap {
        width: 88%;
        height: 42px;
        display: box; /* OLD - Android 4.4- */
        display: -webkit-box; /* OLD - iOS 6-, Safari 3.1-6 */
        display: -moz-box; /* OLD - Firefox 19- (buggy but mostly works) */
        display: -ms-flexbox; /* TWEENER - IE 10 */
        display: -webkit-flex; /* NEW - Chrome */
        display: flex; /* NEW, Spec - Opera 12.1, Firefox 20+ */ /* 09版 */
        -webkit-box-orient: horizontal; /* 12版 */
        -webkit-flex-direction: row;
        -moz-flex-direction: row;
        -ms-flex-direction: row;
        -o-flex-direction: row;
        flex-direction: row;;
        justify-content: space-around;
        align-items: center;
        margin: 0 auto;

    }

    .search-wrap-left {
        width: 16%;
        height: 30px;
        text-align: left;
    }

    .search-wrap-medium {
        width: 88%;
    }

    .search-wrap-medium input {
        width: 100%;
        height: 30px;
        background-color: white;
        border: 0px;
        padding: 0px;
        outline: none;
        border-radius: 4px 0 0 4px;
        text-align: center;
    }

    .search-wrap-right {
        width: 12%;
        height: 30px;
        border-left: 1px solid rgb(220, 220, 220);
        border-radius: 0 4px 4px 0;
        background-color: rgb(240, 240, 240);
        text-align: center;
    }

    .search-wrap-right img {
        width: 24px;
        height: 24px;
        margin-top: 3px;
    }

    .current{
        color: #e83828!important;
    }

    a:link, a:visited, a:hover, a:active{
        text-decoration:none;
    }



</style>
@endsection
@section('title')
    商城
@endsection

@section('js')

    <script type="text/javascript" src="{{asset('js/template.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/wxindex/jquery.lazyload.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/wxindex/index.js')}}"></script>

    {{--    <script type="text/javascript" src="{{asset('js/zepto.js')}}"></script>--}}
    <script type="text/javascript" src="{{asset('js/footer_more.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/common.js')}}"></script>

@endsection


@section('content')

    {{--<body id="home">--}}
    <body >
    {{--<div class="load-show" style="">--}}
        {{--<div class="load-img">--}}
            {{--<img src="{{asset('img/load-logo.jpg')}}"/>--}}
        {{--</div>--}}
        {{--<div class="spinner">--}}
            {{--<div class="dot1"></div>--}}
            {{--<div class="dot2"></div>--}}
        {{--</div>--}}
    {{--</div>--}}

    {{--<div class="col-md-12 nav-top hidden-xs">--}}
        {{--<nav class="container">--}}
            {{--<div class="col-md-6 col-sm-6 nav-left">--}}
                {{--<span>纯酒客Logo</span>--}}
            {{--</div>--}}

        {{--</nav>--}}
    {{--</div>--}}

    <div class="showfixedtop" id="fixedtop" style="position: fixed;top:0;z-index: 100000;width:100%">
        <div id="index_search_head" class="sd-search-container on-blur">
            <div class="sd-search-box-cover" style="opacity: 0"></div>
            <div class="sd-search-box">
                <div class="sd-search-tb">
                    <div class="sd-search-icon">
                        <span class="sd-search-icon-logo"><i class="sd-sprite-icon"></i></span>
                    </div>
                    <form class="sd-search-form" id="index_searchForm">
                        <div class="sd-search-form-box">
                            <span class="sd-search-form-icon sd-sprite-icon" id="search-btn"></span>
                            <div class="sd-search-form-input">
                                <input type="text" class="hilight1" placeholder="搜索心仪的商品" value="" name="keyword"
                                       id="keyword" autocomplete="off" maxlength="20">
                            </div>
                            <a id="index_clear_keyword" class="sd-search-icon-close sd-sprite-icon"
                               href="javascript:void(0);"></a>
                            <a class="sd-search-form-action" id="index_search_submit" href="javascript:void(0)"><span
                                        class="sd-sprite-icon"></span></a>
                        </div>
                    </form>
                    <div class="sd-search-login login-ed">
                        <a class="J_ping" href="{{asset('/personal/index')}}">
                            <span class="sd-search-icon-logined"><i class="sd-sprite-icon"></i></span>
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="main" id="main-container">

        {{--<div class="swiper-container"> <!-- 顶部轮播-->--}}
            {{--<div class="swiper-wrapper adv_list" >--}}

                {{--<div class="swiper-slide item" >--}}
                    {{--<a href="www.baidu.com" >--}}
                        {{--<img style="width: 100%;" data-src="//m.360buyimg.com/mobilecms/s480x214_jfs/t3055/263/4888899061/94067/c0317415/5858f809N30580dfc.jpg!q70.jpg" class="swiper-lazy"/>--}}
                    {{--</a>--}}
                    {{--<div class="swiper-lazy-preloader"></div>--}}
                {{--</div>--}}

                {{--<div class="swiper-slide" >--}}
                    {{--<a href="www.baidu.com" >--}}
                        {{--<img style="width: 100%;" data-src="//m.360buyimg.com/mobilecms/s480x214_jfs/t3055/263/4888899061/94067/c0317415/5858f809N30580dfc.jpg!q70.jpg" class="swiper-lazy"/>--}}
                    {{--</a>--}}
                    {{--<div class="swiper-lazy-preloader"></div>--}}
                {{--</div>--}}

                {{--<div class="swiper-slide" >--}}
                    {{--<a href="www.baidu.com" >--}}
                        {{--<img style="width: 100%;" data-src="//m.360buyimg.com/mobilecms/s480x214_jfs/t3055/263/4888899061/94067/c0317415/5858f809N30580dfc.jpg!q70.jpg" class="swiper-lazy"/>--}}
                    {{--</a>--}}
                    {{--<div class="swiper-lazy-preloader"></div>--}}
                {{--</div>--}}

            {{--</div>--}}
            {{--<div class="swiper-pagination"></div>--}}
        {{--</div>--}}

        <div class="adv_list" style="position: relative;">
            <div class="swipe-wrap">
                    @if(!empty($advertList['advert_a0100']))
                        @foreach($advertList['advert_a0100'] as $k =>$v )
                            <div class="item" >
                                <img src = "{{$v->images}}" alt = "" >
                            </div >
                        @endforeach
                        {{--若空，则默认显示1张默认图片--}}
                    @else
                        <div class="item" >
                            <img src = "/img/lunbo.png" alt = "" >
                        </div >
                    @endif
            </div>
            <div class="focus-btn">
                <div id="pager">

                    @for($i = 0;$i < count($advertList['advert_a0100']);$i++)
                        <span data-tab="{{$i}}" class="
                        @if($i == 0)
                                active
                        @endif
                                " ></span>
                    @endfor

                </div>
            </div>
        </div>
        <script type="text/javascript">
            var topSwipe = '';
            $('.adv_list').each(function () {
                if ($(this).find('.item').length < 2) {
                    return;
                }

                topSwipe = Swipe(this, {
                    speed: 400,
                    auto: 3000,
                    continuous: true,
                    disableScroll: false,
                    stopPropagation: false,
                    callback: function (index) {
                        slideTab(index);
                    }
                });
            });
            var bullets = document.getElementById('pager').getElementsByTagName('span');
            for (var i = 0; i < bullets.length; i++) {
                var elem = bullets[i];
                elem.setAttribute('data-tab', i);
                elem.onclick = function () {
                    topSwipe.slide(parseInt(this.getAttribute('data-tab'), 10), 500);
                }
            }
            function slideTab(index) {
                var i = bullets.length;
                while (i--) {
                    bullets[i].className = bullets[i].className.replace('active', ' ');
                }
                bullets[index].className = 'active';
            }
            (function () {
                var win = window,
                        doc = win.document;

                if (!location.hash || !win.addEventListener) {
                    window.scrollTo(0, 1);
                    var scrollTop = 1,

                            bodycheck = setInterval(function () {
                                if (doc.body) {
                                    clearInterval(bodycheck);
                                    scrollTop = "scrollTop" in doc.body ? doc.body.scrollTop : 1;
                                    win.scrollTo(0, scrollTop === 1 ? 0 : 1);
                                }
                            }, 15);
                    if (win.addEventListener) {
                        win.addEventListener("load", function () {
                            setTimeout(function () {
                                win.scrollTo(0, scrollTop === 1 ? 0 : 1);
                            }, 0);
                        }, false);
                    }
                }

            })();
        </script>



        <nav class="main">
            <div class="main-wrap"><!-- 四个快速入口-->
                <div class="icon">
                    <a href="{{asset('personal/vrcoinLog')}}">
                        <img src="/img/wxindex/index-icon-1.png">
                        <p>我的{{$plat_vrb_caption}}</p>
                    </a>
                </div>

                <div class="icon">
                    <a href="{{asset('order/index')}}">
                        <img src="/img/wxindex/index-icon-2.png">
                        <p>我的订单</p>
                    </a>
                </div>

                <div class="icon">
                    <a href="/personal/collect/list/2">
                        <img src="/img/wxindex/index-icon-3.png">
                        <p>我的收藏</p>
                    </a>
                </div>

                <div class="icon">
                    <a href="/personal/browse/list/2">
                        <img src="/img/wxindex/index-icon-4.png">
                        <p>我的足迹</p>
                    </a>
                </div>
            </div>
        </nav>
        <div style="width: 100%;height: 10px;background-color: rgb(240,240,240)"></div>
        <div class="main">
            <div class="adv-2-1">

                <div class="left">
                    @if(!empty($advertList['advert_a0121_a0131'][0]))
                        <img src="{{$advertList['advert_a0121_a0131'][0]->images}}">
                    @else
                        <img src="/img/wxindex/xiao.png">
                    @endif

                </div>
                <div class="right">
                    @if(!empty($advertList['advert_a0121_a0131'][1]))
                        <img src="{{$advertList['advert_a0121_a0131'][1]->images}}">
                    @else
                        <img src="/img/wxindex/xiao.png">
                    @endif
                </div>
            </div>

            <div class="adv-2-2">
                <div class="left">
                    @if(!empty($advertList['advert_a0123_a0133'][0]))
                        <img src="{{$advertList['advert_a0123_a0133'][0]->images}}">
                    @else
                        <img src="/img/wxindex/xiao.png">
                    @endif
                </div>

                <div class="right">
                    @if(!empty($advertList['advert_a0123_a0133'][1]))
                        <img src="{{$advertList['advert_a0123_a0133'][1]->images}}">
                    @else
                        <img src="/img/wxindex/xiao.png">
                    @endif
                </div>
            </div>
        </div>
        <div class="main">
            <div class="adv-3">
                @if(!empty($advertList['advert_a0113'][0]))
                    <img src="{{$advertList['advert_a0113'][0]->images}}">
                @else
                    <img  src="/img/wxindex/da.png">

                @endif
            </div>
            <div class="adv-3">
                    @if(!empty($advertList['advert_a0113'][1]))
                            <img src="{{$advertList['advert_a0113'][1]->images}}">
                    @else
                            <img  src="/img/wxindex/da.png">
                    @endif
            </div>

        </div>
        <div class="index_block goods">
            <div class="content">
                @foreach($goodsSpuList as $good)
                    <div class="goods-item">  {{--区分京东还是系统图片--}}
                        <a href="{{asset('/shop/goods/spuDetail/'.$good->spu_id)}}">
                            <div class="goods-item-pic"><img src="{{$good->main_image}}"></div>
                            <div class="goods-item-name">{{$good->spu_name}}</div>
                            <div class="goods-item-price">¥&nbsp;{{$good->spu_price}} <span style="color: #999;font-weight: normal;font-size: 12px;">&nbsp;¥&nbsp;<s>{{$good->spu_market_price}}</s></span></div>
                        </a>
                    </div>
                @endforeach

            </div>

            <div style="display: block;" class="swipe-up love-loading" id="recommendListload">
                <div class="swipe-up-wrapper ">
                    <div style="display: block;" id="recommendListloadImg" class="loading-con"><span class="pagenum"
                                                                                                     id="showPageID"></span><span
                                class="loading"><i class="love-loading-icon">加载中...</i></span>
                        <div class="clear"></div>
                    </div>
                    <div style="display: none;" id="recommendListLoadMoreBtn" class="click-loading"><a
                                href="javascript:void(0);">点击继续加载</a></div>
                    <div style="display: none;" id="recommendListLoadNoMore" class="click-loading">没有更多商品了</div>

                </div>
            </div>
            <footer id="footer"></footer>
        </div>
    </div>

</body>

<script>

//    var mySwiper = new Swiper('.swiper-container',{
//        loop : true,
//        pagination: '.swiper-pagination',
//        lazyLoading : true,
//        paginationClickable :true,
//        autoplay : 3000,
//        autoplayDisableOnInteraction : false,
//        speed:500,
//        //autoplay: 5000,
//        /* pagination: '.swiper-pagination',
//         paginationType: 'fraction',*/
//        // autoHeight: true
//    });
//
//        var front_url =document.referrer;
//
//        if(front_url == ''){
//            $('.load-img img').css('transform','scale(1)');
//            setTimeout(function () {
//                $('.spinner').css('opacity',1);
//            },500);
//            window.onload=function(){
//                setTimeout(function () {
//                    $(".load-show").fadeOut();
//                },1000);
//            }
//        }else{
//            //alert(1);
//            $('.load-show').css('display','none');
//        }

//new
        var count = 2;
        //var public = checkbrowse();
        var showeffect = "";
        /*if ((public['is'] == 'msie' && public['ver'] < 8.0)) {
            showeffect = "show"
        } else {
            showeffect = "fadeIn"
        }*/
        jQuery(document).ready(function ($) {
            scrollHeader();
            $(document).scroll(scrollLoadProductEvent);

            $(".content").find("img").lazyload({
                placeholder: "img/wxindex/loading1.png",
                effect: "fadeIn",
                failurelimit: 10
            });
        });

        function scrollLoadProductEvent() {
            var scrollTop = $(document).scrollTop();
            if (scrollTop > $(".index_block").height() - 400 && count <= 3) {
                $(document).unbind("scroll");
                getProduct();
            } else if (count > 3) {
                $(document).unbind("scroll");
                $("#recommendListloadImg").hide();
                $("#recommendListLoadMoreBtn").show().find("a").on("click", function () {
                    getProduct();
                });
            }
            $(document).on("scroll", scrollHeader);
        }

        function scrollHeader() {
            var scrollTop = $(document).scrollTop();
            var img_height = $('.adv_list').find(".item").height();
//            alert("imgheight " +img_height );
            if (scrollTop > img_height) {
                $(".sd-search-box-cover").css("opacity", "0.85");
            } else {
                var opacity = scrollTop * accDiv(0.85, img_height);
//                alert('opacity  '+ opacity);
                $(".sd-search-box-cover").css("opacity", opacity);
            }
        }
        function getProduct() {
            var match = /((http|https):\/\/)+(\w+\.)+(\w+)[\w\/\.\-]*(jpg|gif|png|JPG|PNG|GIF|JPEG)/;
            $.ajax({
                url: "/shop/goods/ajax/spuList/"+count,
                type: 'get',
                data: {

                }, //$pageNumber: count
                dataType: 'json',
                beforeSend: function () {
                    $("#recommendListloadImg").show();
                    $("#recommendListLoadMoreBtn").hide();
                },
                success: function (data) {
                    var html = "";
                    var data = data.data.goodsSpuList;
                    if (data != "") {
                        for (var i = 0; i < data.length; i++) {
                            html += '<div class="goods-item">';
                            html += '<a href="/shop/goods/spuDetail/' + data[i].spu_id + '">';
                            if (match.test(data[i].main_image)) {
                                html += '<div class="goods-item-pic"><img src="' + data[i].main_image + '"></div>';
                            } else {
                                html += '<div class="goods-item-pic"><img src="../' + data[i].main_image + '"></div>';
                            }
                            html += '<div class="goods-item-name">' + data[i].spu_name + '</div>';
                            html += '<div class="goods-item-price">¥&nbsp;' + data[i].spu_price + '<span style="color: #999;font-weight: normal;font-size: 12px;">&nbsp;¥&nbsp;<s>'+data[i].spu_market_price+'</s></span></div>';
                            html += '</a></div>';
                        }
                        count++;
                        $(document).on("scroll", scrollLoadProductEvent);
                    }else{
                        $("#recommendListloadImg").hide();
                        $("#recommendListLoadMoreBtn").hide();
                        $("#recommendListLoadNoMore").show();

//                        $("#recommendListloadImg").hide();
//                        $("#recommendListLoadMoreBtn").show().find("a").on("click", function () {
//                            getProduct();
//                        });
                    }
                    $(".content").append(html);
                }
            });
        }
        /*function checkbrowse() {
            var ua = navigator.userAgent.toLowerCase();
            var is = (ua.match(/\b(chrome|opera|safari|msie|firefox)\b/) || ['', 'mozilla'])[1];
            var r = '(?:' + is + '|version)[\\/: ]([\\d.]+)';
            var v = (ua.match(new RegExp(r)) || [])[1];
            jQuery.browser.is = is;
            jQuery.browser.ver = v;
            return {
                'is': jQuery.browser.is,
                'ver': jQuery.browser.ver
            }
        }
*/



    </script>


@endsection

