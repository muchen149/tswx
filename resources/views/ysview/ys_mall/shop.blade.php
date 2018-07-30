@extends('inheritance')
@section('水丁管家商城')
    商城
@endsection
@section('css')
    <meta charset="UTF-8">
    <title>大美管家-更贴心的家政服务</title>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link rel="stylesheet" href="{{asset('/sd_css/swiper.min.css')}}">
    <link rel="stylesheet" href="{{asset('/sd_css/new_common.css')}}">
    <link rel="stylesheet" href="{{asset('/sd_css/shop.css')}}">
    <script type="text/javascript" src="{{asset('/sd_js/jquery-1.11.2.min.js')}}"></script>
    <style>
        a:link, a:visited, a:hover, a:active{
            text-decoration:none;
        }
    </style>
@endsection
@section('content')
    <body>
    <div class="container">
        <div class="header-search">
            <a href="{{asset('/cart/index')}}" class="cart">{{--<i class="num"></i>--}}</a>
            <a href="" class="logo"></a>
            <form action="">
                <i class="icon"></i>
                <a href="{{asset('/sd_shop/search')}}">
                <input type="search" name="" id="sd_search">
                </a>
            </form>

        </div>
        <div class="swiper-container shop-tab">
            <div class="swiper-wrapper">
                <div class="swiper-slide active shopTabPage" top_class="10" code="sh"><a href="javascript:void(0)">精致生活</a></div>
                <div class="swiper-slide shopTabPage" top_class="80" code="xx"><a href="javascript:void(0)">学习工作</a></div>
                <div class="swiper-slide shopTabPage" top_class="91" code="jk"><a href="javascript:void(0)">健康医疗</a></div>
                <div class="swiper-slide shopTabPage" top_class="92" code="jr"><a href="javascript:void(0)">金融理财</a></div>
            </div>
            <!-- Add Pagination -->
            <!-- <div class="swiper-pagination"></div> -->
        </div>
        <div class="swiper-container shop-banner">
            <div class="swiper-wrapper" id="slidePics">
                <div class="swiper-slide"><a href="/shop/goods/spuList/1/10/10/0/0/1"><img src="/sd_img/sh_banner.jpg" width="100%" alt=""></a></div>
                <div class="swiper-slide"><a href="/shop/goods/spuDetail/1201128"><img src="/sd_img/jpsh_01.jpg" width="100%" alt=""></a></div>
                <div class="swiper-slide"><a href="/shop/goods/spuDetail/1201114"><img src="/sd_img/jpsh_02.jpg" width="100%" alt=""></a></div>
            </div>
            <!-- Add Pagination -->
            <div class="swiper-pagination" id="slidePagenation"></div>
        </div>
        <div class="shop-goodRmd">
            <h3 class="shop-title">好物推荐</h3>
            <ul id="rmdGoods">
                @if(!empty($rmdSpuList))
                        @foreach($rmdSpuList as $good)
                        <li style="width: 33%;"><a href="{{url('/shop/goods/spuDetail/'.$good->spu_id)}}">
                                <span class="img"><img src="{{$good->main_image}}" width="100%" alt=""><i class="tag">推荐</i></span>
                                <h4 class="rmdGood">{{$good->spu_name}}</h4><p class="price">¥&nbsp;{{$good->spu_price}}</p></a></li>
                        @endforeach
                @endif
                {{--<li><a href="">
                        <span class="img"><img src="/sd_img/img05.jpg" width="100%" alt=""><i class="tag">包邮</i></span>
                        <h4>黑巧克力35枚</h4><p class="price">¥ 25.9</p><p class="des">纯正可可不甜腻</p></a></li>--}}

            </ul>
        </div>
        <div class="shop-typeList">
            <h3 class="shop-title">热门分类</h3>
            <div class="swiper-container tab">
                <div class="swiper-wrapper" id="hotClass">
                    <div class="swiper-slide active hotCls" code="11"><a href="javascript:void(0)"><img src="/sd_img/sh01.jpg" width="100%" alt=""></a></div>
                    <div class="swiper-slide hotCls" code="12"><a href="javascript:void(0)"><img src="/sd_img/sh02.jpg" width="100%" alt=""></a></div>
                    <div class="swiper-slide hotCls" code="16"><a href="javascript:void(0)"><img src="/sd_img/sh03.jpg" width="100%" alt=""></a></div>
                    <div class="swiper-slide hotCls" code="15"><a href="javascript:void(0)"><img src="/sd_img/sh04.jpg" width="100%" alt=""></a></div>
                    <div class="swiper-slide hotCls" code="13"><a href="javascript:void(0)"><img src="/sd_img/sh05.jpg" width="100%" alt=""></a></div>
                    <div class="swiper-slide hotCls" code="160"><a href="javascript:void(0)"><img src="/sd_img/sh06.jpg" width="100%" alt=""></a></div>
                    <div class="swiper-slide hotCls" code="17"><a href="javascript:void(0)"><img src="/sd_img/sh07.jpg" width="100%" alt=""></a></div>
                    <div class="swiper-slide hotCls" code="14"><a href="javascript:void(0)"><img src="/sd_img/sh08.jpg" width="100%" alt=""></a></div>
                    <div class="swiper-slide hotCls" code="18"><a href="javascript:void(0)"><img src="/sd_img/sh09.jpg" width="100%" alt=""></a></div>
                </div>
                <!-- Add Pagination -->
                <!-- <div class="swiper-pagination"></div> -->
            </div>
            <div class="list" id="shopGoodList">
                {{--<ul class="clearFix">
                    <li>
                        <a href=""><img src="/sd_img/img07.jpg" width="100%" alt="">
                            <h4>鼻毛修剪器男士男用套装电动充电鼻毛修剪器男士男用套装电动充电鼻毛修剪器男士男用套装电动充电</h4>
                            <p class="price">¥59</p>
                            <p class="tag"><i>红包</i><i>满减</i></p>
                        </a>
                    </li>
                </ul>--}}
                @if(!empty($goodsSpuList))

                    <ul class="clearFix">
                        {{-- <li>
                             <a href=""><img src="images/img07.jpg" width="100%" alt="">
                                 <h4>鼻毛修剪器男士男用套装电动充电鼻毛修剪器男士男用套装电动充电鼻毛修剪器男士男用套装电动充电</h4>
                                 <p class="price">¥59</p>
                                 <p class="tag"><i>红包</i><i>满减</i></p>
                             </a>
                         </li>--}}

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
        {{--<div class="main-nav">
            <ul>
                <li class=" navBtn"><a href="{{asset('/shop/index')}}"><span class="icon"></span><span class="text">管家服务</span></a></li>
                <li class="active navBtn"><a href="{{asset('/sd_shop/shop')}}"><span class="icon"></span><span class="text">集市</span></a></li>
                <li class="calling navBtn"><a href="{{asset('member/call')}}"><span class="icon"><i></i></span><span class="text">召唤管家</span></a></li>
                <li class=" navBtn"><a href="{{asset('/cart/index')}}"><span class="icon"></span><span class="text">购物车</span></a></li>
                <li class=" navBtn"><a href="{{asset('/personal/index')}}"><span class="icon"></span><span class="text">我的</span></a></li>
            </ul>
        </div>--}}
        <div class="ysmain-nav">
            <ul>
                <li class="navBtn"><a href="{{asset('/ys')}}"><span class="icon"></span><span class="text">首页</span></a></li>
                <li class="navBtn"><a href=""><span class="icon"></span><span class="text">订单</span></a></li>
                <li class="navBtn"><a href="{{asset('/ys/index')}}"><span class="icon"></span><span class="text">我的</span></a></li>
            </ul>
        </div>
    </div>
    </body>
@endsection
@section('js')
    <script type="text/javascript" src="{{asset('/sd_js/swiper.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('/sd_js/shop.js')}}"></script>
    <script type="text/javascript" src="{{asset('/sd_js/scrollLoadData.js')}}"></script>
    <script type="text/javascript">
        var currentPage=1;
        var pageRows=10;
        var curCls='11';


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

        var match = /((http|https):\/\/)+(\w+\.)+(\w+)[\w\/\.\-]*(jpg|gif|png|JPG|PNG|GIF|JPEG)/;
        function pageLoad(page, rows, callback) {
            var _this = this;

            var url = "/shop/goods/ajax/shopSpuList/"+page+"/"+rows+"/"+curCls;
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
                        $(".noData").show();
                    } else if (data.data.goodsSpuList.length == 0) {
                        $(".noData").show();
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

        $('.shopTabPage').click(function () {
            $(this).addClass("active").siblings().removeClass("active");
            //alert($(this).attr('top_class')+$(this).attr('code'));

            var slide_content='';

            var subClassContent="";
            var cls=$(this).attr('code');
            var type=$(this).attr('top_class');
            showRmdList(type);
            if(cls=='sh'){
                slide_content='<div class="swiper-slide"><a href="/shop/goods/spuList/1/10/10/0/0/1"><img src="/sd_img/'+$(this).attr('code')+'_banner.jpg" width="100%" alt=""></a></div>';
                slide_content+='<div class="swiper-slide"><a href="/shop/goods/spuDetail/1201128"><img src="/sd_img/jpsh_01.jpg" width="100%" alt=""></a></div>';
                slide_content+='<div class="swiper-slide"><a href="/shop/goods/spuDetail/1201114"><img src="/sd_img/jpsh_02.jpg" width="100%" alt=""></a></div>';
                $('#slidePics').html(slide_content);

                subClassContent= '<div class="swiper-slide active hotCls" code="11"><a href="javascript:void(0)"><img src="/sd_img/sh01.jpg" width="100%" alt=""></a></div>'+
                        '<div class="swiper-slide hotCls" code="12"><a href="javascript:void(0)"><img src="/sd_img/sh02.jpg" width="100%" alt=""></a></div>'+
                        '<div class="swiper-slide hotCls" code="16"><a href="javascript:void(0)"><img src="/sd_img/sh03.jpg" width="100%" alt=""></a></div>'+
                        '<div class="swiper-slide hotCls" code="15"><a href="javascript:void(0)"><img src="/sd_img/sh04.jpg" width="100%" alt=""></a></div>'+
                        '<div class="swiper-slide hotCls" code="13"><a href="javascript:void(0)"><img src="/sd_img/sh05.jpg" width="100%" alt=""></a></div>'+
                        '<div class="swiper-slide hotCls" code="160"><a href="javascript:void(0)"><img src="/sd_img/sh06.jpg" width="100%" alt=""></a></div>'+
                        '<div class="swiper-slide hotCls" code="17"><a href="javascript:void(0)"><img src="/sd_img/sh07.jpg" width="100%" alt=""></a></div>'+
                        '<div class="swiper-slide hotCls" code="14"><a href="javascript:void(0)"><img src="/sd_img/sh08.jpg" width="100%" alt=""></a></div>'+
                        '<div class="swiper-slide hotCls" code="18"><a href="javascript:void(0)"><img src="/sd_img/sh09.jpg" width="100%" alt=""></a></div>';
                showGoodsList(11);
            }else if(cls=='xx'){
                slide_content='<div class="swiper-slide"><a href="/shop/goods/spuList/1/10/80/0/0/1"><img src="/sd_img/'+$(this).attr('code')+'_banner.jpg" width="100%" alt=""></a></div>';
                slide_content+='<div class="swiper-slide"><a href="/shop/goods/spuDetail/1201192"><img src="/sd_img/xxlb_01.jpg" width="100%" alt=""></a></div>';
                slide_content+='<div class="swiper-slide"><a href="/shop/goods/spuDetail/1201157"><img src="/sd_img/xxlb_02.jpg" width="100%" alt=""></a></div>';
                $('#slidePics').html(slide_content);

                subClassContent= '<div class="swiper-slide active hotCls" code="81"><a href="javascript:void(0)"><img src="/sd_img/xx01.jpg" width="100%" alt=""></a></div>'+
                        '<div class="swiper-slide hotCls" code="82"><a href="javascript:void(0)"><img src="/sd_img/xx02.jpg" width="100%" alt=""></a></div>'+
                        '<div class="swiper-slide hotCls" code="83"><a href="javascript:void(0)"><img src="/sd_img/xx03.jpg" width="100%" alt=""></a></div>'+
                        '<div class="swiper-slide hotCls" code="84"><a href="javascript:void(0)"><img src="/sd_img/xx04.jpg" width="100%" alt=""></a></div>';
                showGoodsList(81);
            }else if(cls=='jk'){
                slide_content='<div class="swiper-slide"><a href="/shop/goods/spuList/1/10/91/0/0/1"><img src="/sd_img/'+$(this).attr('code')+'_banner.jpg" width="100%" alt=""></a></div>';
                $('#slidePics').html(slide_content);

                subClassContent= '<div class="swiper-slide active hotCls" code="94"><a href="javascript:void(0)"><img src="/sd_img/jk01.jpg" width="100%" alt=""></a></div>'+
                        '<div class="swiper-slide hotCls" code="95"><a href="javascript:void(0)"><img src="/sd_img/jk02.jpg" width="100%" alt=""></a></div>'+
                        '<div class="swiper-slide hotCls" code="115"><a href="javascript:void(0)"><img src="/sd_img/jk03.jpg" width="100%" alt=""></a></div>'+
                        '<div class="swiper-slide hotCls" code="97"><a href="javascript:void(0)"><img src="/sd_img/jk04.jpg" width="100%" alt=""></a></div>'+
                        '<div class="swiper-slide hotCls" code="93"><a href="javascript:void(0)"><img src="/sd_img/jk05.jpg" width="100%" alt=""></a></div>';
                showGoodsList(94);
            }else if(cls=='jr'){
                slide_content='<div class="swiper-slide"><a href="/shop/goods/spuList/1/10/92/0/0/1"><img src="/sd_img/'+$(this).attr('code')+'_banner.jpg" width="100%" alt=""></a></div>';
                $('#slidePics').html(slide_content);

                subClassContent= '<div class="swiper-slide active hotCls" code="98"><a href="javascript:void(0)"><img src="/sd_img/jr01.jpg" width="100%" alt=""></a></div>'+
                        '<div class="swiper-slide hotCls" code="99"><a href="javascript:void(0)"><img src="/sd_img/jr02.jpg" width="100%" alt=""></a></div>'+
                        '<div class="swiper-slide hotCls" code="100"><a href="javascript:void(0)"><img src="/sd_img/jr03.jpg" width="100%" alt=""></a></div>';
                showGoodsList(98);
            }
            $('#hotClass').html(subClassContent);
            shopCtrl.init();
            currentPage=1;
            pageRows=10;
         });

    </script>

@endsection