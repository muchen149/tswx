@extends('inheritance')
@section('水丁管家商城')
    商城
@endsection
@section('css')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>{!!config('constant')['comTitle']['title']!!}</title>
    <link rel="stylesheet" href="{{asset('/sd_css/swiper.min.css')}}">
    <link rel="stylesheet" href="{{asset('/sd_css/new_common.css')}}">
    <link rel="stylesheet" href="{{asset('/sd_css/shop.css')}}">
    <script type="text/javascript" src="{{asset('/sd_js/jquery-1.11.2.min.js')}}"></script>
    <style>
        a:link, a:visited, a:hover, a:active{
            text-decoration:none;
        }

        #hotClass {
            padding: 0 10px;
            margin-bottom: 10px;
        }
        #hotClass .active {
            border:1px solid #c3c3c3;
        }
        .threeGoodsClass {
            display: inline-block;
            margin-top: 10px;
            width: 24%;
        }
        .threeGoodsClass .threeClass {
            vertical-align: middle;
            display: table-cell;
            text-align: center;
            padding: 5px 8px;
            width: 85%;
        }
        .threeClass img {
            width:100%;
        }
        .two_class_btn{
            position: absolute;
            z-index: 10;
            width:20%;
            top: 38px;
            right: 0;
        }
        .two_class_display{
            width:100%;
            position: absolute;
            background: #fff;
            z-index: 12;
            padding: 5px 10px 25px 10px;
        }
        .two_class_display li{
            display: inline-block;
            border: 1px solid #c3c3c3;
            font-size: 10px;
            width: 24%;
            height: 30px;
            vertical-align: middle;
            text-align: center;
            margin-top: 20px;
        }
        .two_class_display .active a {
            color: #f55c54;
        }
        #btn_left {
            position: absolute;
            z-index: 10;
            width: 15%;
            top: 5px;
            right: 10px;
        }
        .all_gc {
            width: 100%;
            height: 38px;
            position: absolute;
            z-index: 9;
            top: 38px;
            right: 0;
            background: #fff;
        }
        .all_gc .font-13{
            display: inline-block;
            margin-left: 40%;
            margin-top: 2%;
        }
        .rotate {
            transform: rotate(90deg);
        }
        .rotate_back {
            transform: rotate(180deg);
        }
        #btn_left img {
            transition: all 0.8s;
        }
        /*阴影*/
        .yinying {
            position: fixed;
            top: 75px;
            right: 0;
            bottom: 0;
            left: 0;
            z-index: 11;
            background-color: #c3c3c3;
            opacity: 0.4; }
        .tabs .active {
            color:red;
        }
    </style>
@endsection
@section('content')
    <body>
    @if($subscribe==0)
        <div style="position: fixed;z-index: 10;top:0">
            <a href="javascript:showSubscribe();"><img src="{{asset('sd_img/t_banner.jpg')}}" alt="" width="100%"></a>
        </div>
    @endif
    {{--search--}}
    <div class="container hide" id="SEO">
        <div class="search-header">
            <a href="javascript:void(0);" class="btn-return icon-back"></a>
            <form id="searchFrm" action="{{asset('/sd_shop/doSearch')}}">
                <i class="icon"></i>
                <input type="search" name="" id="searchKey" autofocus="autofocus">
                <input type="submit" value="搜索" id="search" style="right:-45px;width:50px;">
                {{--<input type="submit" onclick="javascript:search();" value="搜索">--}}
            </form>
        </div>
        <div class="search-history">
            <h3>历史搜索</h3>
            <span class="btn-del" onclick='deleteItem()'>删除</span>
            <ul class="list" id="list"></ul>
            <p class="tip">暂无搜索历史</p>
        </div>
        <div class="search-recommend">
            <h3>常用搜索</h3>
            <span class="btn-ctrl">隐藏</span>
            <ul class="list">
                @if(!empty($hotSearch))
                    @foreach($hotSearch as $val)
                        <li class="skey">{{$val->name}}</li>
                    @endforeach
                @endif
            </ul>
            <p class="tip">常用搜索已隐藏</p>
        </div>
    </div>
    {{--search end--}}

    <div class="container" style="margin-top:0px;" id="content">
        {{--<div class="header-search">
            <a href="{{asset('/cart/index')}}" class="cart">--}}{{--<i class="num"></i>--}}{{--</a>
            <a href="" class="logo"></a>
            <form action="">
                <i class="icon"></i>
                <a href="{{asset('/sd_shop/search')}}">
                <input type="search" name="" id="sd_search">
                </a>
            </form>

        </div>--}}
        <div class="header-search">
            <a href="{{asset('/cart/index')}}" class="cart">{{--<i class="num"></i>--}}</a>
            <a href="" class="logo"></a>
            <form action="" id="search_btn" style="color:#ccc;font-size:12px;">
                <i class="icon"></i>
                <input type="search" name="" value="点击此处搜索商品" readonly="readonly">
                {{--<a href="{{asset('/sd_shop/search')}}">
                    <input type="search" name="" id="sd_search">
                </a>--}}
            </form>

        </div>
        {{-- -------------------------商品一级类导航---------------------------- --}}
        {{--<div class="swiper-container shop-tab">
            <div class="swiper-wrapper">
                --}}{{--@foreach($pName as $p)
                <div class="swiper-slide active shopTabPage" top_class="10" code="sh"><a href="javascript:void(0)">{{$p->name}}{{$p->id}}</a></div>
                @endforeach--}}{{--
                <div class="swiper-slide active shopTabPage" code="10" top_class="a0401"><a href="javascript:void(0)">精致生活</a></div>
                <div class="swiper-slide shopTabPage" code="80" top_class="a0402"><a href="javascript:void(0)">学习工作</a></div>
                <div class="swiper-slide shopTabPage" code="91" top_class="a0403"><a href="javascript:void(0)">健康医疗</a></div>
                <div class="swiper-slide shopTabPage" code="92" top_class="a0404"><a href="javascript:void(0)">金融理财</a></div>
            </div>
            <!-- Add Pagination -->
            <!-- <div class="swiper-pagination"></div> -->
        </div>--}}
        {{-- -------------------------商品一级类导航 end---------------------------- --}}

        {{-- -------------------------商品二级类导航---------------------------- --}}
        <div  id="tabs-container" class="swiper-container shop-tab">
            <div class="swiper-wrapper">
                <div class="swiper-slide shopTabPage" code="0"><a href="{{asset('/shop/index')}}">首页</a></div>
                @foreach($num_gcId_2 as $toclass)
                    <div class="swiper-slide gcId_name_{{$toclass['id']}}  gcId_num_{{$loop->index }} shopTabPage" num="{{$loop->index }}" code="{{$toclass['id']}}"><a href="javascript:void(0)">{{$toclass['name']}}</a></div>
                @endforeach
            </div>
            <!-- Add Pagination -->
            <!-- <div class="swiper-pagination"></div> -->
        </div>
        {{-- -------------------------商品二级类导航 end---------------------------- --}}
        <div class="two_class">
            <ul class="two_class_btn">
                <li><img src="{{asset('sd_img/two_btn.png')}}" width="100%"></li>
                <li id="btn_left"><img src="{{asset('sd_img/two_btn_left.png')}}" width="100%"></li>
            </ul>
            <div class="all_gc" style="display:none">
                <span class="font-13">全部分类</span>
            </div>
            <ul class="two_class_display" style="display:none">
                @foreach($num_gcId_2 as $toclass)
                    <li class="tabs gcId_name_{{$toclass['id']}}" num="{{$loop->index }}">
                            {{--<div>@if(!empty($toclass['image_url']))<img src="{{$img_domain}}{{$toclass['image_url']}}" width="60%" alt="">@endif</div>--}}
                        <a href="javascript:void(0)"><div code="{{$toclass['id']}}">{{$toclass['name']}}</div></a>
                    </li>
                @endforeach
            </ul>
        </div>
        {{-- ------------------------- 二级类广告图 -----------------------------------}}
        <div>
            <div id="slidePics">
                @foreach($num_gcId_2 as $toclass)
                        <div class="swiper-slide img_{{$toclass['id']}}" code="{{$toclass['id']}}" id="img"><a href="javascript:void(0)">@if(!empty($toclass['image_url']))<img src="{{$img_domain}}{{$toclass['image_url']}}" width="100%" alt="">@endif</a></div>
                @endforeach
            </div>
            <!-- Add Pagination -->
            <div class="swiper-pagination"></div>
        </div>
        {{-- ------------------------- 二级类广告图 end--------------------------------}}

        {{-- -------------------------轮播图---------------------------- --}}
        {{--<div class="swiper-container shop-banner">
            <div class="swiper-wrapper" id="slidePics">
                @foreach($advertList['advert_a0401'] as $val)
                    <div class="swiper-slide"><a href="{{$val->out_url==""?'javascript:void(0)':$val->out_url}}javascript:void(0)"><img src="{{$val->images}}" width="100%" alt=""></a></div>
                @endforeach

                <div class="swiper-slide"><a href="javascript:void(0)"><img src="/sd_img/sh_banner.jpg" width="100%" alt=""></a></div>
                <div class="swiper-slide"><a href="{{asset('shop/goods/spuList/1/10/22/0/0/3')}}"><img src="/sd_img/sh_banner01.jpg" width="100%" alt=""></a></div>
                <div class="swiper-slide"><a href="{{asset('shop/goods/spuDetail/1201335')}}"><img src="/sd_img/sh_banner02.jpg" width="100%" alt=""></a></div>
                <div class="swiper-slide"><a href=""><img src="/sd_img/img06.jpg" width="100%" alt=""></a></div>
                <div class="swiper-slide"><a href=""><img src="/sd_img/img06.jpg" width="100%" alt=""></a></div>
            </div>
            <!-- Add Pagination -->
            <div class="swiper-pagination"></div>
        </div>--}}
        {{-- -------------------------轮播图 end---------------------------- --}}

        {{--<div class="shop-goodRmd">
            <h3 class="shop-title">好物推荐</h3>
            <ul id="rmdGoods">
                @if(!empty($rmdSpuList))
                        @foreach($rmdSpuList as $good)
                        <li style="width: 33%;"><a href="{{url('/shop/goods/spuDetail/'.$good->spu_id)}}">
                                <span class="img"><img src="{{$good->main_image}}" width="100%" alt=""><i class="tag">推荐</i></span>
                                <h4 class="rmdGood">{{$good->spu_name}}</h4><p class="price">¥&nbsp;{{$good->spu_price}}</p></a></li>
                        @endforeach
                @endif
            </ul>
        </div>--}}
        {{-- ------------------------ 三级类 ----------------------------------}}
        {{--<div class="shop-typeList">
            <div class="swiper-container tab">
                <div class="swiper-wrapper">--}}
                <div id="hotClass">
                    @foreach($num_gcId_3 as $threeclass)

                        @if($loop->first)
                            <div  class="threeGoodsClass hotCls gc3_{{$threeclass->id}}" code="{{$threeclass->id}}">
                                <div class="threeClass">
                                    {{--<div style="width:60%;">--}}
                                    <a href="javascript:void(0)"><img src="{{$img_domain}}{{$threeclass->image_url}}" width="65px" alt=""></a>
                                    {{--</div>--}}
                                </div>
                            </div>
                        @else
                            <div  class="threeGoodsClass hotCls gc3_{{$threeclass->id}}" code="{{$threeclass->id}}">
                                <div class="threeClass">
                                    {{--<div style="width:60%;">--}}
                                        <a href="javascript:void(0)"><img src="{{$img_domain}}{{$threeclass->image_url}}" width="65px" alt=""></a>
                                    {{--</div>--}}
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

        {{--        </div>
                <!-- Add Pagination -->
                <!-- <div class="swiper-pagination"></div> -->
            </div>
        </div>--}}
        {{--------------------------- 三级类 end-------------------}}
        <div class="shop-typeList">
            {{--@if(!empty($toGoodsClass))--}}
            {{--<h3 class="shop-title">热门分类</h3>
            <div class="swiper-container tab">
                <div class="swiper-wrapper" id="hotClass">

                    @foreach($toGoodsClass as $toclass)
                    <div class="swiper-slide hotCls" code="{{$toclass['id']}}"><a href="javascript:void(0)"><img src="{{$img_domain}}{{$toclass->image_url}}" width="100%" alt=""></a></div>
                    @endforeach
                </div>
                <!-- Add Pagination -->
                <!-- <div class="swiper-pagination"></div> -->
            </div>--}}
            {{--@endif--}}
            <div class="list" id="shopGoodList">
                @if(!empty($goodsSpuList))
                    <ul class="clearFix">
                        @foreach($goodsSpuList as $good)
                            <li>
                                <a href="{{url('/shop/goods/spuDetail/'.$good->spu_id)}}"><img src="{{$good->main_image}}" width="100%" alt="">
                                    <h4>{{$good->spu_name}}</h4>
                                    <p class="price">¥&nbsp;{{$good->spu_price}}</p>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
        <div class="main-nav">
            <ul>
                <li class=" navBtn"><a href="{{asset('/shop/index')}}"><span class="icon"></span><span class="text">管家服务</span></a></li>
                {{--<li class="active navBtn"><a href="{{asset('/sd_shop/shop')}}"><span class="icon"></span><span class="text">分类</span></a></li>--}}
                {{--<li class="calling navBtn"><a href="{{asset('member/call')}}"><span class="icon"><i></i></span><span class="text">召唤管家</span></a></li>--}}
                <li class=" navBtn"><a href="{{asset('/cart/index')}}">@if($goods_num_in_cart < 100)<span class="cart_num">{{$goods_num_in_cart}}</span>@else<span class="cart_big_num">99+</span>@endif<span class="icon"></span><span class="text">购物车</span></a></li>
                <li class=" navBtn"><a href="{{asset('/personal/index')}}"><span class="icon"></span><span class="text">我的</span></a></li>
            </ul>
        </div>
    </div>
    <div class="lanrenzhijia" id="pop-ad">
        <div class="content">
            <img width="270px;" height="332px" src="{{asset('/sd_img/subscribe_share.jpg')}}" usemap="#Map" />
            <map name="Map">
                <area shape="rect" coords="234,5,265,31" href="javascript:hideSubscribe();">
            </map>
        </div>
    </div>
    <div class="content_mark"></div>
    <!------------------------选择二级类时的阴影部分  START!---------------------------->
    <div class="xiaoguo">

    </div>
    </body>
@endsection
@section('js')
    <script type="text/javascript" src="{{asset('/sd_js/swiper.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('/sd_js/shop.js')}}"></script>
    <script type="text/javascript" src="{{asset('/sd_js/search.js')}}"></script>
    <script type="text/javascript" src="{{asset('/sd_js/scrollLoadData.js')}}"></script>
    <script type="text/javascript">



        //轮播图处理171124
        /*var content_a0401='';
        var content_a0402='';
        var content_a0403='';
        var content_a0404='';*/
        {{--@foreach($advertList['advert_a0401'] as $val)
                content_a0401+='<div class="swiper-slide"><a href="{{$val->out_url==""?'javascript:void(0)':$val->out_url}}javascript:void(0)"><img src="{{$val->images}}" width="100%" alt=""></a></div>';
        @endforeach
        @foreach($advertList['advert_a0402'] as $val)
                content_a0402+='<div class="swiper-slide"><a href="{{$val->out_url==""?'javascript:void(0)':$val->out_url}}javascript:void(0)"><img src="{{$val->images}}" width="100%" alt=""></a></div>';
        @endforeach
        @foreach($advertList['advert_a0403'] as $val)
                content_a0403+='<div class="swiper-slide"><a href="{{$val->out_url==""?'javascript:void(0)':$val->out_url}}javascript:void(0)"><img src="{{$val->images}}" width="100%" alt=""></a></div>';
        @endforeach
        @foreach($advertList['advert_a0404'] as $val)
                content_a0404+='<div class="swiper-slide"><a href="{{$val->out_url==""?'javascript:void(0)':$val->out_url}}javascript:void(0)"><img src="{{$val->images}}" width="100%" alt=""></a></div>';
        @endforeach--}}
        //轮播图处理171124 end

        var currentPage=1;
        var pageRows=10;
        /*var curCls=$('.threeGoodsClass:first-child').attr('code');
        console.log(curCls);*/
        //使用方法
        scrollLoadData({
            container: '.clearFix',
            currentPage: currentPage,
            pageRows: pageRows,
            requestData: function(currentPage, pageRows, callback) {
                // currentPage 当前加载的页码
                // pageRows 每页加载多少条
                // callback 加载完成后的回调函数
                // callback 说明：由于加载新数据为动态加载ajax，是用户自定义方法并非组件内部ajax无法控制；保证在数据请求过程中不能再次请求发送请求，callback内包含参数的值为true/false;
                // true 表示仍有数据，false表示没有数据
                //ajax请求函数
                pageLoad(++currentPage, pageRows, callback);
            }

        });
        var html="";
        var match = /((http|https):\/\/)+(\w+\.)+(\w+)[\w\/\.\-]*(jpg|gif|png|JPG|PNG|GIF|JPEG)/;
        function pageLoad(page, rows, callback) {
            var _this = this;
            var url = "/shop/goods/ajax/shopSpuList/"+page+"/"+rows+"/"+$('.threeGoodsClass').filter('.active').attr('code');
            $.ajax({
                url: url,
                type:'get',
                data: {},
                dataType: ''
            }).done(function(data) {
                if (data == null || data.code != "0") {
                    console.log(data.message);
                } else {
                    console.log(data);
                    if (data.message.length == 0 && page == 1) {
                        //$(".noData").show();
                        var html = '<ul class="clearFix"><img src="../sd_img/noGoods.jpg" width="100%" alt=""></ul>';
                        $('#shopGoodList').html(html);
                    } else if (data.data.goodsSpuList.length == 0) {
                        //$(".noData").show();
                        /*var html = '<ul class="clearFix"><img src="../sd_img/noGoods.jpg" width="100%" alt=""></ul>';
                        $('#shopGoodList').html(html);*/
                        callback(false);
                        //没有更多了
                    } else {
                        $(".noData").hide();
                        console.log(data.data.goodsSpuList);
                        var html = "";
                        var data = data.data.goodsSpuList;
                        if (data != "") {
                            for (var i = 0; i < data.length; i++) {

                                html += '<li>';
                                html += '<a href="/shop/goods/spuDetail/' + data[i].spu_id + '">';
                                if (match.test(data[i].main_image)) {
                                    html += '<img src="' + data[i].main_image + '" width="100%" alt="">';
                                } else {
                                    html += '<img src="../' + data[i].main_image + '" width="100%" alt="">';
                                }
                                html += '<h4>' + data[i].spu_name + '</h4>';
                                html += '<p class="price">¥&nbsp;' + data[i].spu_price + '</p>';
                                html += '</a></li>';


                            }

                            currentPage++;
                            $(".clearFix").append(html);
                        }

                        /*$.each(data.data.goodsSpuList,function(i,n)
                         {
                         alert("索引:"+ i ,"对应值为："+n.name);
                         });*/

                        callback(true);
                    }
                }

            }).fail(function(e){});
        }

        function callback(loaded){
            //alert(loaded);
        }

        //初始化的时选中起一个二级类
        //$('.index').addClass('active').siblings().removeClass("active");
        var name = 'gcId_name_'+'{!! $gcId !!}';
        $('.'+name).addClass('active').siblings().removeClass("active");
        var num = $('.'+name).attr('num');
        //二级类轮播初始化第一个
        var img = 'img_'+ '{!! $gcId  !!}';
        $('.'+img).removeClass('hide').siblings().addClass('hide');

        //$('.shopTabPage:first').addClass('active').siblings().removeClass("active");
        //$('.tabs:first').addClass('active').siblings().removeClass("active");

        //$('#img').removeClass('hide').siblings().addClass('hide');
        $('.hotCls:first').addClass('active').siblings().removeClass("active");

        $('.shopTabPage').click(function () {
            $(this).addClass("active").siblings().removeClass("active");
            localStorage.setItem("2class", $(this).attr('code'));

            var class_num = 'gcId_name_'+$(this).attr('code');
            $('.'+class_num).addClass('active').siblings().removeClass('active');
            var val='img_'+$(this).attr('code');
            $('.'+val).removeClass('hide').siblings().addClass('hide');
            var subClassContent="";
            var cls=$(this).attr('code');
            if(cls == 0){
                window.location.href = "/shop/index";
            }else{
                //var type=$(this).attr('top_class');
                var url = "/sd_shop/toGoodsClass/"+cls;
                $.ajax({
                    url: url,
                    type:'get',
                    data: {},
                    dataType: ''
                }).done(function(res) {
                    //  debugger;
                    if (res == null || res.code != "0") {
                        console.log(res.message);
                    }else{
                        if (res.message.length == 0) {
                            var html = '<ul class="clearFix"><img src="../sd_img/noGoods.jpg" width="100%" alt=""></ul>';
                            $('#shopGoodList').html(html);
                        } else if (res.data.length == 0) {
                            $('.hotCls').addClass('hide');
                            var html = '<ul class="clearFix"><img src="../sd_img/noGoods.jpg" width="100%" alt=""></ul>';
                            $('#shopGoodList').html(html);
                            callback(false);
                            //没有更多了
                        }else{
                            var dat = res.data;
                            for (var i = 0; i < dat.length; i++) {
                                subClassContent += '<div class="threeGoodsClass hotCls gc3_'+dat[i].id+'" code="' + dat[i].id + '">';
                                subClassContent += '<div class="threeClass">';
                                subClassContent += '<a href="javascript:void(0)"><img src="'+dat[i].image_url+'" alt=""></a>';
                                subClassContent += '</div>';
                                subClassContent += '</div>';
                                //subClassContent += '<div class="active hotCls" code="' + dat[i].id + '"><a href="javascript:void(0)"><img src="'+dat[i].image_url+'" width="100%" alt=""></a></div>';
                            }
                            $('#hotClass').html(subClassContent);
                            if(localStorage.getItem("3class")){
                                var gc_id_3 = localStorage.getItem("3class");
                                $('.gc3_'+gc_id_3).addClass("active").siblings().removeClass("active");
                                localStorage.clear();
                            }else{
                                var gc_id_3 = res.data[0].id;
                                //点击其中一个大类自动加载时，让其选中第一个二级类
                                $('.hotCls:first').addClass('active').siblings().removeClass("active");
                            }
                            showGoodsList(gc_id_3);
                            //shopCtrl.init();
                            bindClick();
                        }

                    }
                }).fail(function(e){});
            }


         });

        function showSubscribe() {
            $('.lanrenzhijia').show(0);
            $('.content_mark').show(0);
        }

        function hideSubscribe(){
            $('.lanrenzhijia').hide(0);
            $('.content_mark').hide(0);
        }

        if('{{$subscribe}}'==0){
            $('.container').css('marginTop',37);
        }

        //二级类全部展示
        $('.two_class_btn').on('click', function () {
            $(this).find('#btn_left img').toggleClass('rotate');
            $('.all_gc').slideToggle(100);
            $('.two_class_display').slideToggle(500);
            if($('.xiaoguo').hasClass('yinying')){
                $('.xiaoguo').removeClass('yinying');
            }else{
                $('.xiaoguo').addClass('yinying');
            }
        });
        $('.xiaoguo').click(function (e) {
            e.stopPropagation();
            $('.xiaoguo').removeClass('yinying');
            $('.two_class_btn').find('#btn_left img').toggleClass('rotate');
            $('.all_gc').slideToggle(100);
            $('.two_class_display').slideToggle(500);
        });

        var tabsSwiper = new Swiper('#tabs-container',{
            speed:500,
            observer: true,
            slidesPerView: 5,
            spaceBetween: 0,
            onSlideChangeStart: function(){

                $(".tabs .active").removeClass('active');
                /*var num = parseInt($('.tabs a:last').attr('num'))+1;
                if(num/tabsSwiper.activeIndex >=4 ){*/
                    $(".tabs").eq(tabsSwiper.activeIndex).addClass('active');
                /*}else{
                    debugger;
                    tabsSwiper.activeIndex = 4;
                    $(".tabs a").eq(tabsSwiper.activeIndex).addClass('active')
                }*/

            }
        });

        $(".tabs").on('touchstart mousedown',function(e){
            e.preventDefault();
            debugger;
            $(".tabs .active").removeClass('active');
            $(this).addClass('active');
            var num = $(this).attr('num');
            //var n=Math.floor(num/4);
            tabsSwiper.slideTo(num);
            $('.gcId_num_'+num).addClass('active').siblings().removeClass("active");
            $('.gcId_num_'+num).click();
            $('.xiaoguo').removeClass('yinying');
            $('.two_class_btn').find('#btn_left img').toggleClass('rotate');
            $('.all_gc').slideToggle(100);
            $('.two_class_display').slideToggle(500);
        });
        tabsSwiper.slideTo(num);

        //点击搜索触发
        $('#search_btn').click(function(){
            $('#SEO').removeClass('hide');
            $('#content').addClass('hide');
            $('#searchKey').trigger("click").focus();
            searchCtrl.init();
        });
        $('.icon-back').click(function(){
            $('#SEO').addClass('hide');
            $('#content').removeClass('hide');
            $('#searchKey').removeClass('hide');
        });

        if(localStorage.getItem("2class")){
            var two_class_id = localStorage.getItem("2class");
            $('.swiper-wrapper .gcId_name_'+two_class_id).click();
        }
    </script>

@endsection