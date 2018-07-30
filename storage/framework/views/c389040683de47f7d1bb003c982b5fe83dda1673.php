<?php $__env->startSection('水丁管家首页'); ?>
    管家中心
<?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
    <meta charset="UTF-8">
    <title><?php echo config('constant')['comTitle']['title']; ?></title>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link rel="stylesheet" href="<?php echo e(asset('/sd_css/swiper.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('/sd_css/new_common.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('/sd_css/shop.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('/sd_css/new_index.css')); ?>">
    <style>
        a:link, a:visited, a:hover, a:active{
            text-decoration:none;
        }
    </style>
    <script type="text/javascript" src="<?php echo e(asset('/sd_js/jquery-1.11.2.min.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <body>
    
    <?php if($subscribe==0): ?>
        <div style="position: fixed;z-index: 10;top:0">
            <a href="javascript:showSubscribe();"><img src="<?php echo e(asset('sd_img/t_banner.jpg')); ?>" alt="" width="100%"></a>
        </div>
    <?php endif; ?>
    
    
    <div class="container hide" id="SEO">
        <div class="search-header">
            <a href="javascript:void(0);" class="btn-return icon-back"></a>
            <form id="searchFrm" action="<?php echo e(asset('/sd_shop/doSearch')); ?>">
                <i class="icon"></i>
                <input type="search" name="" id="searchKey" autofocus="autofocus">
                <input type="submit" value="搜索" id="search" style="right:-45px;width:50px;">
                
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
                <?php if(!empty($hotSearch)): ?>
                    <?php $__currentLoopData = $hotSearch; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                        <li class="skey"><?php echo e($val->name); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                <?php endif; ?>
            </ul>
            <p class="tip">常用搜索已隐藏</p>
        </div>
    </div>
    
    
    <div class="container" style="margin-top:0px;" id="content">
        <div class="header-search">
            <a href="<?php echo e(asset('/cart/index')); ?>" class="cart"></a>
            <a href="" class="logo"></a>
            <form action="" id="search_btn" style="color:#ccc;font-size:12px;">
                <i class="icon"></i>
                <input type="search" name="" value="点击此处搜索商品" readonly="readonly">
                
            </form>

        </div>
        <div class="swiper-container shop-tab">
            <div class="swiper-wrapper">
                
                <div class="swiper-container shop-tab">
                    <div class="swiper-wrapper" id="gc_id_2">
                        <div class="swiper-slide active shopTabPage" code=""><a href="">首页</a></div>

                            <?php $__currentLoopData = $num_gcId_2; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $toclass): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                <div class="swiper-slide active shopTabPage gcId_num_<?php echo e($loop->index); ?>" code="<?php echo e($toclass['id']); ?>" ><a href="/sd_shop/shop/<?php echo e($toclass['id']); ?>"><?php echo e($toclass['name']); ?></a></div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
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
                <li><img src="<?php echo e(asset('sd_img/two_btn.png')); ?>" width="100%"></li>
                <li id="btn_left"><img src="<?php echo e(asset('sd_img/two_btn_left.png')); ?>" width="100%"></li>
            </ul>
            <div class="all_gc" style="display:none">
                <span class="font-13">全部分类</span>
            </div>
            <ul class="two_class_display" style="display:none">
                <?php $__currentLoopData = $num_gcId_2; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $toclass): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                    <li class="tabs class_num_<?php echo e($toclass['id']); ?>" num="<?php echo e($loop->index); ?>">
                        
                        <a href="<?php echo e(asset('sd_shop/shop/'.$toclass['id'])); ?>"><div class="gcId_name_<?php echo e($toclass['id']); ?>" code="<?php echo e($toclass['id']); ?>"><?php echo e($toclass['name']); ?></div></a>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
            </ul>
        </div>

        <div class="swiper-container i-banner">
            <div class="swiper-wrapper">
                <?php $__currentLoopData = $advertList['advert_a0100']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                    <div class="swiper-slide"><a href="<?php echo e($val->out_url); ?>"><img src="<?php echo e($val->images); ?>" width="100%" alt=""></a></div>
                    
                <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
            </div>
            <!-- Add Pagination -->
            <div class="swiper-pagination"></div>
        </div>
        <div class="i-qEnter">
            <ul>
                <li><a href="<?php echo e(asset('member/center')); ?>"><i class="icon"></i><i class="t">管家中心</i></a></li>
                <li><a href="<?php echo e(asset('wx/pay/rechargeCardList')); ?>"><i class="icon"></i><i class="t">充值中心</i></a></li>
                <li><a href="<?php echo e(asset('member/inviteFriend')); ?>"><i class="icon"></i><i class="t">邀请好友</i></a></li>
                <li><a href="<?php echo e(asset('gift/index')); ?>"><i class="icon"></i><i class="t">微信送礼</i></a></li>
                
                <li><a href="<?php echo e(asset('ys')); ?>"><i class="icon"></i><i class="t">月嫂服务</i></a></li>

                
                
            </ul>
        </div>
        <?php if($member_exp != 1): ?>
            <?php $__currentLoopData = $advertList['advert_a0113']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                <div class="i-linkBan"><a href="<?php echo e($val->out_url); ?>"><img src="<?php echo e($val->images); ?>" width="100%" alt=""></a></div>
                
            <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
        <?php endif; ?>
        <div class="i-linkGoods">
            <ul>
                <?php $__currentLoopData = $advertList['advert_a0121_a0131']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                    <li><a href="<?php echo e($val->out_url); ?>"><img src="<?php echo e($val->images); ?>" width="100%" alt=""></a></li>
                    
                <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
            </ul>
        </div>
        

        

        

        
        
        <div class="i-tabList">
            <?php if(!empty($label_goods_list)): ?>
                <?php $__currentLoopData = $label_goods_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$label_list): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                     <div class="recommend_shop">
                        <h3><?php echo e($label_list['index_shop_recommend_name']); ?></h3>
                        <?php if($label_list['is_display'] == 1): ?>
                            <div class="describe"><?php echo e($label_list['index_shop_recommend_descrition']); ?></div>
                            <div><a href="<?php echo e($label_list['out_url']); ?>"><img src="<?php echo e($label_list['image_url']); ?>" alt="" width="100%"></a></div>
                        <?php endif; ?>
                        <?php if($label_list['style'] == 1): ?>
                            <ul class="clearFix vertical">
                                <?php $__currentLoopData = $label_list['g_list']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $good_info): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                    <li class="goods">
                                        <a href="<?php echo e(url('/shop/goods/spuDetail/'.$good_info['spu_id'])); ?>"><img src="<?php echo e($good_info['main_image']); ?>" width="100%" alt="">
                                            <h3><?php echo e($good_info['spu_name']); ?></h3>
                                            <?php if(!empty($good_info['ad_info'])): ?>
                                                <div class="shop_info">
                                                    <?php echo $good_info['ad_info']; ?>

                                                </div>
                                            <?php endif; ?>
                                            <p style="color:red;padding-left: 5px;">￥&nbsp;<?php echo e($good_info['spu_price']); ?></p>
                                        </a>
                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                            </ul>
                        <?php else: ?>
                             <ul class="transverse">
                                 <?php $__currentLoopData = $label_list['g_list']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $good_info): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                     <li class="clearFix">
                                         <a href="<?php echo e(url('/shop/goods/spuDetail/'.$good_info['spu_id'])); ?>"><img src="<?php echo e($good_info['main_image']); ?>" width="50%" alt="">
                                             <?php if(!empty($good_info['ad_info'])): ?>
                                                 <div class="goods">
                                                     <h3><?php echo e($good_info['spu_name']); ?></h3>
                                                     <div class="shop_info">
                                                         <?php echo $good_info['ad_info']; ?>

                                                     </div>
                                                     <p style="color:red;padding-top:10px;">￥&nbsp;<?php echo e($good_info['spu_price']); ?></p>
                                                 </div>
                                             <?php else: ?>
                                                 <div class="goods" style="padding-top:15%;">
                                                     <h3><?php echo e($good_info['spu_name']); ?></h3>
                                                     <p style="color:red;padding-top:10px;">￥&nbsp;<?php echo e($good_info['spu_price']); ?></p>
                                                 </div>
                                             <?php endif; ?>
                                         </a>
                                     </li>
                                 <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                             </ul>
                        <?php endif; ?>
                     </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                <div style="color: #ccc;text-align: -webkit-center;">————— 我是有底线的 —————</div>
            <?php else: ?>
                <div><a href="javascript:void(0)"><img src="<?php echo e(asset('sd_img/noGoods.jpg')); ?>" width="100%" alt=""></a></div>
            <?php endif; ?>
        </div>
        

        
        
        
        
        <div class="main-nav">
            <ul>
                <li class="active navBtn"><a href="<?php echo e(asset('/shop/index')); ?>"><span class="icon"></span><span class="text">管家服务</span></a></li>
                
                
                <li class=" navBtn"><a href="<?php echo e(asset('/cart/index')); ?>"><?php if($goods_num_in_cart < 100): ?><span class="cart_num"><?php echo e($goods_num_in_cart); ?></span><?php else: ?><span class="cart_big_num">99+</span><?php endif; ?><span class="icon"></span><span class="text">购物车</span></a></li>
                <li class=" navBtn"><a href="<?php echo e(asset('/personal/index')); ?>"><span class="icon"></span><span class="text">我的</span></a></li>
            </ul>
        </div>
    </div>
    <div class="lanrenzhijia" id="pop-ad">
        <div class="content">
            <img width="270px;" height="332px" src="<?php echo e(asset('/sd_img/subscribe_share.jpg')); ?>" usemap="#Map" />
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
<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>
    <script type="text/javascript" src="<?php echo e(asset('/sd_js/swiper.min.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(asset('/sd_js/index.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(asset('/sd_js/search.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(asset('/sd_js/shop.js')); ?>"></script>
    
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script type="text/javascript">
        //分享相关数据——start
        var appId = '<?php echo e($signPackage['appId']); ?>';
        var timestamp = '<?php echo e($signPackage['timestamp']); ?>';
        var nonceStr = '<?php echo e($signPackage['nonceStr']); ?>';
        var signature = '<?php echo e($signPackage['signature']); ?>';
        var link = 'http://tswx.shuitine.com';
        var title = '水丁管家--精致生活服务管家';
        var imgUrl = '<?php echo e(asset('sd_img/share_Index.png')); ?>';
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
            //@todo  处理标签切换

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

        if('<?php echo e($subscribe); ?>'==0){
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('inheritance', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>