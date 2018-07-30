<?php $__env->startSection('水丁管家商城'); ?>
    商城
<?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
    <meta charset="UTF-8">
    <title>大美管家-更贴心的家政服务</title>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link rel="stylesheet" href="<?php echo e(asset('/sd_css/swiper.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('/sd_css/new_common.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('/sd_css/shop.css')); ?>">
    <script type="text/javascript" src="<?php echo e(asset('/sd_js/jquery-1.11.2.min.js')); ?>"></script>
    <style>
        a:link, a:visited, a:hover, a:active{
            text-decoration:none;
        }
    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <body>
    <div class="container">
        <div class="header-search">
            <a href="<?php echo e(asset('/cart/index')); ?>" class="cart"></a>
            <a href="" class="logo"></a>
            <form action="">
                <i class="icon"></i>
                <a href="<?php echo e(asset('/sd_shop/search')); ?>">
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
                <?php if(!empty($rmdSpuList)): ?>
                        <?php $__currentLoopData = $rmdSpuList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $good): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                        <li style="width: 33%;"><a href="<?php echo e(url('/shop/goods/spuDetail/'.$good->spu_id)); ?>">
                                <span class="img"><img src="<?php echo e($good->main_image); ?>" width="100%" alt=""><i class="tag">推荐</i></span>
                                <h4 class="rmdGood"><?php echo e($good->spu_name); ?></h4><p class="price">¥&nbsp;<?php echo e($good->spu_price); ?></p></a></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                <?php endif; ?>
                

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
                
                <?php if(!empty($goodsSpuList)): ?>

                    <ul class="clearFix">
                        

                        <?php $__currentLoopData = $goodsSpuList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $good): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                            <li>
                                <a href="<?php echo e(url('/shop/goods/spuDetail/'.$good->spu_id)); ?>"><img src="<?php echo e($good->main_image); ?>" width="100%" alt="">
                                    <h4><?php echo e($good->spu_name); ?></h4>
                                    <p class="price">¥&nbsp;<?php echo e($good->spu_price); ?></p>

                                </a>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>

                    </ul>

                <?php endif; ?>
            </div>
        </div>
        
        <div class="ysmain-nav">
            <ul>
                <li class="navBtn"><a href="<?php echo e(asset('/ys')); ?>"><span class="icon"></span><span class="text">首页</span></a></li>
                <li class="navBtn"><a href=""><span class="icon"></span><span class="text">订单</span></a></li>
                <li class="navBtn"><a href="<?php echo e(asset('/ys/index')); ?>"><span class="icon"></span><span class="text">我的</span></a></li>
            </ul>
        </div>
    </div>
    </body>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>
    <script type="text/javascript" src="<?php echo e(asset('/sd_js/swiper.min.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(asset('/sd_js/shop.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(asset('/sd_js/scrollLoadData.js')); ?>"></script>
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

<?php $__env->stopSection(); ?>
<?php echo $__env->make('inheritance', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>