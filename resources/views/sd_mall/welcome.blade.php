@extends('inheritance')
@section('水丁管家首页')
    管家中心
@endsection
@section('css')
    <meta charset="UTF-8">
    <title>{!!config('constant')['comTitle']['title']!!}</title>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link rel="stylesheet" href="{{asset('/sd_css/swiper.min.css')}}">
    <link rel="stylesheet" href="{{asset('/sd_css/new_common.css')}}">
    <link rel="stylesheet" href="{{asset('/sd_css/shop.css')}}">
    <link rel="stylesheet" href="{{asset('/sd_css/new_index.css')}}">
    <style>
        a:link, a:visited, a:hover, a:active{
            text-decoration:none;
        }
    </style>
    <script type="text/javascript" src="{{asset('/sd_js/jquery-1.11.2.min.js')}}"></script>
@endsection
@section('content')
    <body>
    {{--follow--}}
    @if($subscribe==0)
        <div style="position: fixed;z-index: 10;top:0">
            <a href="javascript:showSubscribe();"><img src="{{asset('sd_img/t_banner.jpg')}}" alt="" width="100%"></a>
        </div>
    @endif
    {{--follow end--}}
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
    {{--<input type="hidden" value="{{$separatePage['pageNumber']}}" id="pageNumber"/>
    <input type="hidden" value="{{$separatePage['pageSize']}}" id="pageSize"/>
    <input type="hidden" value="{{$queryParameter['gcId']}}" id="gcId"/>
    <input type="hidden" value="{{$queryParameter['gcType']}}" id="gcType"/>
    <input type="hidden" value="{{$queryParameter['orderBy']}}" id="orderBy"/>--}}
    <div class="container" style="margin-top:0px;" id="content">
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
        <div class="swiper-container shop-tab">
            <div class="swiper-wrapper">
                {{--@foreach($pName as $p)
                <div class="swiper-slide active shopTabPage" top_class="10" code="sh"><a href="javascript:void(0)">{{$p->name}}{{$p->id}}</a></div>
                @endforeach
                <div class="swiper-slide active shopTabPage" code="10" top_class="a0401"><a href="javascript:void(0)">精致生活</a></div>
                <div class="swiper-slide shopTabPage" code="80" top_class="a0402"><a href="javascript:void(0)">学习工作</a></div>
                <div class="swiper-slide shopTabPage" code="91" top_class="a0403"><a href="javascript:void(0)">健康医疗</a></div>
                <div class="swiper-slide shopTabPage" code="92" top_class="a0404"><a href="javascript:void(0)">金融理财</a></div>--}}
                <div class="swiper-container shop-tab">
                    <div class="swiper-wrapper" id="gc_id_2">
                        <div class="swiper-slide active shopTabPage" code=""><a href="">首页</a></div>

                            @foreach($num_gcId_2 as $toclass)
                                <div class="swiper-slide active shopTabPage gcId_num_{{$loop->index }}" code="{{$toclass['id']}}" ><a href="/sd_shop/shop/{{$toclass['id']}}">{{$toclass['name']}}</a></div>
                            @endforeach
                    </div>
                    <!-- Add Pagination -->
                    <!-- <div class="swiper-pagination"></div> -->
                </div>
            </div>
            <!-- Add Pagination -->
            <!-- <div class="swiper-pagination"></div> -->
        </div>

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
                    <li class="tabs class_num_{{$toclass['id']}}" num="{{$loop->index }}">
                        {{--<div>@if(!empty($toclass['image_url']))<img src="{{$img_domain}}{{$toclass['image_url']}}" width="60%" alt="">@endif</div>--}}
                        <a href="{{asset('sd_shop/shop/'.$toclass['id'])}}"><div class="gcId_name_{{$toclass['id']}}" code="{{$toclass['id']}}">{{$toclass['name']}}</div></a>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="swiper-container i-banner">
            <div class="swiper-wrapper">
                @foreach($advertList['advert_a0100'] as $val)
                    <div class="swiper-slide"><a href="{{$val->out_url}}"><img src="{{$val->images}}" width="100%" alt=""></a></div>
                    {{--<div class="swiper-slide"><a href="{{asset('article/28?from=singlemessage')}}"><img src="{{asset('sd_img/index_banner.jpg')}}" width="100%" alt=""></a></div>--}}
                @endforeach
            </div>
            <!-- Add Pagination -->
            <div class="swiper-pagination"></div>
        </div>
        <div class="i-qEnter">
            <ul>
                <li><a href="{{asset('member/center')}}"><i class="icon"></i><i class="t">管家中心</i></a></li>
                <li><a href="{{asset('wx/pay/rechargeCardList')}}"><i class="icon"></i><i class="t">充值中心</i></a></li>
                <li><a href="{{asset('member/inviteFriend')}}"><i class="icon"></i><i class="t">邀请好友</i></a></li>
                <li><a href="{{asset('gift/index')}}"><i class="icon"></i><i class="t">微信送礼</i></a></li>
                {{--<li><a href="{{asset('order/index')}}"><i class="icon"></i><i class="t">我的订单</i></a></li>--}}
                <li><a href="{{asset('ys')}}"><i class="icon"></i><i class="t">月嫂服务</i></a></li>

                {{--<li>--}}{{--<a href="{{asset('jd/goods/spuList')}}"><i class="icon"></i><i class="t">酒店商品</i></a>--}}{{--
                    <a href="{{asset('jd/goods/spuList')}}">
                        <img style="width: 55px;height: 55px;" src="{{asset('sd_img/index_topnav_icon4.png')}}" alt="">
                        <p class="font-12">酒店商品</p>
                    </a>

                </li>--}}
                {{--<li><a href="{{asset('jd/order/index')}}"><i class="icon"></i><i class="t">酒店订单</i></a></li>--}}
            </ul>
        </div>
        @if($member_exp != 1)
            @foreach($advertList['advert_a0113'] as $val)
                <div class="i-linkBan"><a href="{{$val->out_url}}"><img src="{{$val->images}}" width="100%" alt=""></a></div>
                {{--<div class="i-linkBan"><a href="{{asset('member/center')}}"><img src="{{asset('sd_img/img02.jpg')}}" width="100%" alt=""></a></div>--}}
            @endforeach
        @endif
        <div class="i-linkGoods">
            <ul>
                @foreach($advertList['advert_a0121_a0131'] as $val)
                    <li><a href="{{$val->out_url}}"><img src="{{$val->images}}" width="100%" alt=""></a></li>
                    {{--<li><a href="{{asset('gift/index')}}"><img src="{{asset('sd_img/img03.jpg')}}" width="100%" alt=""></a></li>
                    <li><a href="{{asset('article/27')}}"><img src="{{asset('sd_img/img04.jpg')}}" width="100%" alt=""></a></li>--}}
                @endforeach
            </ul>
        </div>
        {{-- -----------------------------------server plat------------------------- --}}

        {{--<div class="i-tabList">
            <div id="oneclass">
                <div class="swiper-container tab">
                    <div class="swiper-wrapper">
                        @foreach($one_serve_class as $k=>$v)
                            <div class="swiper-slide keyorder {{$k == 0 ? " cur" : ""}}" name="wapfly_none_fl_one-fenlei{{$k}}"><a href="javascript:void(0)">{{$v['name']}}</a></div>
                            --}}{{--<div class="swiper-slide keyorder" key="jj"><a href="javascript:void(0)">居家管家</a></div>
                            <div class="swiper-slide keyorder" key="my"><a href="javascript:void(0)">母婴管家</a></div>
                            <div class="swiper-slide keyorder"key="cx"><a href="javascript:void(0)">出行管家</a></div>--}}{{--
                        @endforeach
                    </div>
                    <!-- Add Pagination -->
                    <div class="swiper-pagination hide"></div>
                </div>
            </div>

            @foreach($one_serve_class as $i=>$one)
            <div id="showTabList" class="list-detail {{$i == 0 ? "" : "cur"}}">
                <div class="list-slide-wrap" id="listSwipe{{$i}}">
                    <ul class="list-slide">
                    </ul>
                </div>
                @if($two_serve_class[$one['id']])
                    @foreach($two_serve_class[$one['id']] as $kk=>$two)
                            <div style="padding: 0 10px 10px 10px;"><a href="{{$two['out_url']}}"><img src="{{$two['image_url']}}" alt="" width="100%"></a></div>
                            @if($two['is_show'] == 1)
                            <h3>{{$two['name']}}</h3>
                            @endif
                            <div class="swiper-container sh-swiper base">
                                <div class="swiper-wrapper">
                                    @if($three_serve_class[$two['id']])
                                        @foreach($three_serve_class[$two['id']] as $three)
                                            <div class="swiper-slide"><a href="{{$three['out_url']}}" name="wapfly_none_fl_three-fenlei1"><img src="{{$three['image_url']}}" width="100%" alt=""><h4>{{$three['description']}}</h4></a></div>
                                            --}}{{--<div class="swiper-slide"><a href="{{asset('/article/3')}}"><img src="{{asset('sd_img/sy02.jpg')}}" width="100%" alt=""><h4>茗茶冲饮</h4><p>源生优质 放心饮用</p></a></div>
                                            <div class="swiper-slide"><a href="{{asset('/article/4')}}"><img src="{{asset('sd_img/sy03.jpg')}}" width="100%" alt=""><h4>休闲零食</h4><p>营养美味 不二之选</p></a></div>--}}{{--
                                        @endforeach
                                    @else
                                        <div class="swiper-slide"><a href="" name="wapfly_none_fl_three-fenlei1"><img src="{{asset('sd_img/noGoods.jpg')}}" width="100%" alt=""></a></div>
                                    @endif
                                </div>
                            </div>
                    @endforeach
                @else
                    <div class="swiper-slide"><a href="" name="wapfly_none_fl_three-fenlei1"><img src="{{asset('sd_img/noGoods.jpg')}}" width="100%" alt=""></a></div>
                @endif
            </div>
            @endforeach
        </div>--}}

        {{-- ----------------------------------- end server plat------------------------- --}}

        {{-- -----------------------------------server-new plat------------------------- --}}
        {{--<div class="i-tabList">
            @foreach($one_serve_class as $i=>$one)
                <div style="margin-top:10px;">
                    <div class="list-slide-wrap">
                        <ul class="list-slide">
                            <li class="swiper-slide keyorder" style="font-weight: normal;line-height: 2em;color: #999;font-size: 14px;text-align: -webkit-center;"><a href="javascript:void(0)">{{$one['name']}}</a></li>
                        </ul>
                    </div>
                    @if($two_serve_class[$one['id']])
                        @foreach($two_serve_class[$one['id']] as $kk=>$two)
                                <div>
                                    @if($two['is_show'] == 1)
                                        <h3 style="padding: 0px 0px;font-size:12px;">{{$two['name']}}</h3>
                                    @endif
                                    <div style="padding: 0 10px 0px 10px;"><a href="{{$two['out_url']}}"><img src="{{$two['image_url']}}" alt="" width="100%"></a></div>
                                </div>
                                <ul style="margin: 0px 10px;">
                                    @if($three_serve_class[$two['id']])
                                        @foreach($three_serve_class[$two['id']] as $three)
                                            <li style="display: inline-block;font-size: 10px;width: 49%;padding:10px 10px;vertical-align: middle;text-align: center;border: 1px solid #c3c3c3;background: #f6f7fb;border: 1px solid #dddddf;margin-top:10px;"><a href="{{$three['out_url']}}" name="wapfly_none_fl_three-fenlei1"><img src="{{$three['image_url']}}" width="100%" alt=""><h6 style="padding-top:10px;">{{$three['description']}}</h6></a></li>
                                            --}}{{--<div class="swiper-slide"><a href="{{asset('/article/3')}}"><img src="{{asset('sd_img/sy02.jpg')}}" width="100%" alt=""><h4>茗茶冲饮</h4><p>源生优质 放心饮用</p></a></div>
                                            <div class="swiper-slide"><a href="{{asset('/article/4')}}"><img src="{{asset('sd_img/sy03.jpg')}}" width="100%" alt=""><h4>休闲零食</h4><p>营养美味 不二之选</p></a></div>--}}{{--
                                        @endforeach
                                    @else
                                        <li><a href=""><img src="{{asset('sd_img/noGoods.jpg')}}" width="100%" alt=""></a></li>
                                    @endif
                                </ul>
                        @endforeach
                    @else
                        <div><a href=""><img src="{{asset('sd_img/noGoods.jpg')}}" width="100%" alt=""></a></div>
                    @endif
                </div>
            @endforeach
        </div>--}}
        <div class="i-tabList">
            @if(!empty($label_goods_list))
                @foreach($label_goods_list as $key=>$label_list)
                     <div class="recommend_shop">
                        <h3>{{$label_list['index_shop_recommend_name']}}</h3>
                        @if($label_list['is_display'] == 1)
                            <div class="describe">{{$label_list['index_shop_recommend_descrition']}}</div>
                            <div><a href="{{$label_list['out_url']}}"><img src="{{$label_list['image_url']}}" alt="" width="100%"></a></div>
                        @endif
                        @if($label_list['style'] == 1)
                            <ul class="clearFix vertical">
                                @foreach($label_list['g_list'] as $k => $good_info)
                                    <li class="goods">
                                        <a href="{{url('/shop/goods/spuDetail/'.$good_info['spu_id'])}}"><img src="{{$good_info['main_image']}}" width="100%" alt="">
                                            <h3>{{$good_info['spu_name']}}</h3>
                                            @if(!empty($good_info['ad_info']))
                                                <div class="shop_info">
                                                    {!! $good_info['ad_info'] !!}
                                                </div>
                                            @endif
                                            <p style="color:red;padding-left: 5px;">￥&nbsp;{{$good_info['spu_price']}}</p>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                             <ul class="transverse">
                                 @foreach($label_list['g_list'] as $k => $good_info)
                                     <li class="clearFix">
                                         <a href="{{url('/shop/goods/spuDetail/'.$good_info['spu_id'])}}"><img src="{{$good_info['main_image']}}" width="50%" alt="">
                                             @if(!empty($good_info['ad_info']))
                                                 <div class="goods">
                                                     <h3>{{$good_info['spu_name']}}</h3>
                                                     <div class="shop_info">
                                                         {!! $good_info['ad_info'] !!}
                                                     </div>
                                                     <p style="color:red;padding-top:10px;">￥&nbsp;{{$good_info['spu_price']}}</p>
                                                 </div>
                                             @else
                                                 <div class="goods" style="padding-top:15%;">
                                                     <h3>{{$good_info['spu_name']}}</h3>
                                                     <p style="color:red;padding-top:10px;">￥&nbsp;{{$good_info['spu_price']}}</p>
                                                 </div>
                                             @endif
                                         </a>
                                     </li>
                                 @endforeach
                             </ul>
                        @endif
                     </div>
                @endforeach
                <div style="color: #ccc;text-align: -webkit-center;">————— 我是有底线的 —————</div>
            @else
                <div><a href="javascript:void(0)"><img src="{{asset('sd_img/noGoods.jpg')}}" width="100%" alt=""></a></div>
            @endif
        </div>
        {{-- ----------------------------------- end-new server plat------------------------- --}}

        {{-- -----------------------------------shop plat------------------------- --}}
        {{--<div>
            <div style="height:10px;width:100%;background: #f3f3f3;"></div>
            <img src="{{asset('sd_img/recommend.png')}}" width="100%" alt="">
        </div>--}}
        {{--<div class="s-listAll" id="product_list" style="padding:0px 10px 0;">
            @if(!empty($goodsSpuList))
                <ul class="clearFix">
                    @foreach($goodsSpuList as $good)
                        <li>
                            <a href="{{url('/shop/goods/spuDetail/'.$good->spu_id)}}"><img src="{{$good->main_image}}" width="100%" alt="">
                                <h4>{{$good->spu_name}}</h4>
                                <p class="price">¥&nbsp;{{$good->spu_price}}</p>
                                --}}{{--<p class="tag"><i></i><i></i></p>--}}{{--
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>--}}
        {{-- -----------------------------------end shop plat------------------------- --}}
        <div class="main-nav">
            <ul>
                <li class="active navBtn"><a href="{{asset('/shop/index')}}"><span class="icon"></span><span class="text">管家服务</span></a></li>
                {{--<li class=" navBtn"><a href="{{asset('/sd_shop/shop')}}"><span class="icon"></span><span class="text">分类</span></a></li>--}}
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
    <script type="text/javascript" src="{{asset('/sd_js/index.js')}}"></script>
    <script type="text/javascript" src="{{asset('/sd_js/search.js')}}"></script>
    <script type="text/javascript" src="{{asset('/sd_js/shop.js')}}"></script>
    {{--<script type="text/javascript" src="{{asset('/sd_js/scrollLoadData.js')}}"></script>--}}
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script type="text/javascript">
        //分享相关数据——start
        var appId = '{{$signPackage['appId']}}';
        var timestamp = '{{$signPackage['timestamp']}}';
        var nonceStr = '{{$signPackage['nonceStr']}}';
        var signature = '{{$signPackage['signature']}}';
        var link = 'http://ets.shuitine.com';
        var title = '水丁管家--精致生活服务管家';
        var imgUrl = '{{asset('sd_img/share_Index.png')}}';
        var desc = '精挑细选，甄选优质商品及服务；定制生活、尽情享受；一对一贴心服务，省去后顾之忧。';

        wx.config({
            debug: false,
            appId: appId,
            timestamp: timestamp,
            nonceStr: nonceStr,
            signature: signature,
            jsApiList: [
                // 所有要调用的 API 都要加到这个列表中
                'onMenuShareTimeline',
                'onMenuShareAppMessage'
            ]
        });
        wx.ready(function () {
            // 在这里调用 API
            wx.onMenuShareTimeline({
                title: title, // 分享标题
                imgUrl: imgUrl, // 分享图标
                link:link,
                success: function (msg) {
                    // 用户确认分享后执行的回调函数
                },
                cancel: function (msg) {
                    // 用户取消分享后执行的回调函数
                },
                fail: function () {
                    // 用户取消分享后执行的回调函数
                    alert("分享失败，请稍后再试");
                }
            });


            wx.onMenuShareAppMessage({
                title: title, // 分享标题
                desc: desc,//'分享送好礼', // 分享描述
                imgUrl: imgUrl, // 分享图标
                link:link,
                type: '', // 分享类型,music、video或link，不填默认为link
                dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
                success: function (msg) {
                    // 用户确认分享后执行的回调函数
                },
                cancel: function (msg) {
                    // 用户取消分享后执行的回调函数
                },
                fail: function () {
                    // 用户取消分享后执行的回调函数
                    alert("分享失败，请稍后再试");
                }
            });
        });

        //分享相关数据——end


        //类别管理
        $('#gc_id_2 div:first-child').addClass('active').siblings().removeClass('active');
        //管家服务类别切换
        /*var content=$("#showTabList").html();
        $(document).ready(function(){
            $('.keyorder:first').addClass("active");
            $('.i-tabList #showTabList:first').removeClass('hide').siblings().addClass('hide');
            $('#oneclass').removeClass('hide');
            //@todo 处理标签切换

            $("div[name^=wapfly_]").on("click", function () {
                $(this).addClass("active").siblings().removeClass("active");
                var sa = $(this).attr('name');
                var patt = new RegExp("wapfly_none_fl_one-fenlei");
                var num = sa.replace(patt, '');
                var obj_d = $('#listSwipe' + num);
                $('.i-tabList div.list-detail').each(function () {
                    if (!$(this).hasClass('hide')) {
                        $(this).addClass('hide');
                    }
                });
                obj_d.parent().removeClass('hide');
                $('#listItems').find('li.cur').removeClass('cur');
                $(this).addClass('cur');
                indexCtrl.init();
            });

        });*/
        $('.navBtn').click(function () {
            $(this).addClass("active").siblings().removeClass("active");
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
            $('.container').css('marginTop',35);
        }

        //头部导航
        /*$('.shopTabPage').click(function () {
            $(this).addClass("active").siblings().removeClass("active");

        });*/

        //spu懒加载
        /*var currentPage=1;
        var pageRows=10;
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
            var url = "/shop/goods/ajax/spuList/"+page+"/"+rows+"/"+$('#gcId').val()+"/"+$('#orderBy').val()+"/0/"+$('#gcType').val();
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
                        /!*$.each(data.data.goodsSpuList,function(i,n)
                         {
                         alert("索引:"+ i ,"对应值为："+n.name);
                         });*!/

                        callback(true);
                    }
                }

            }).fail(function(e){});
        }

        function callback(loaded){
            alert(loaded);
        }*/
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
            $(".tabs a").eq(tabsSwiper.activeIndex).addClass('active')

            }
        });
        $(".tabs a").click(function(){
            localStorage.clear();
            $(this).addClass('active').siblings().removeClass("active");
        });
        $(".swiper-slide").click(function(){
            localStorage.clear();
            $(this).addClass('active').siblings().removeClass("active");
        });
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
    </script>
@endsection