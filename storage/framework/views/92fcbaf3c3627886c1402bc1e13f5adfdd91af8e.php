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
    <link rel="stylesheet" href="<?php echo e(asset('/sd_css/new_index.css')); ?>">
    <script type="text/javascript" src="<?php echo e(asset('/sd_js/jquery-1.11.2.min.js')); ?>"></script>
    <style>
        a:link, a:visited, a:hover, a:active{
            text-decoration:none;
        }
    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <body>
    <div class="container container-ys" style="padding-bottom: 15px;">
        <div class="swiper-container shop-banner">
            <div class="swiper-wrapper">
                
                
                <?php $__currentLoopData = $advertList['advert_a0301']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                    <div class="swiper-slide"><a href="<?php echo e($val->out_url); ?>"><img src="<?php echo e($val->images); ?>" width="100%" alt=""></a></div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
            </div>
            <!-- Add Pagination -->
            <div class="swiper-pagination"></div>
        </div>
        
        <div class="ys-qEnter">
            <ul>
                <li><a href="/ys/goods/spuDetail/1201284"><i class="icon"></i><i class="t">月嫂服务</i></a></li>
                <li><a href="/ys/goods/spuDetail/1201282"><i class="icon"></i><i class="t">催乳师</i></a></li>
                <li><a href="/ys/goods/spuDetail/1201283"><i class="icon"></i><i class="t">育儿嫂</i></a></li>
                <li><a href="/ys/goods/spuDetail/1201285"><i class="icon"></i><i class="t">产后康复</i></a></li>
            </ul>
        </div>
        <img src="../ys_img/_01_04.png" alt="">
        <div class="title">
            <h4 style="font-family: 'Microsoft YaHei';font-weight: 400;"><img src="../ys_img/icon_14.png" width="30%">人气推荐<img src="../ys_img/icon_17.png" width="30%"></h4>
        </div>
        <div class="details">
            <ul>
                <li><a href="/ys/goods/spuDetail/1201284"><img src="../ys_img/_01_05.png" width="100%" alt></a></li>
                <li>
                    <a href="/ys/goods/spuDetail/1201282">
                        <img src="../ys_img/_01_06.png" width="100%" alt>
                    </a>
                    <a href="/ys/goods/spuDetail/1201283">
                        <img src="../ys_img/_01_07.png" width="100%" alt>
                    </a>
                    <a href="/ys/goods/spuDetail/1201285">
                        <img src="../ys_img/_01_08.png" width="100%" alt>
                    </a>
                </li>
            </ul>
        </div>
        
        



        <div class="ysmain-nav">
            <ul style="background:#F2F2F2">
                <li class="active navBtn"><a href="<?php echo e(asset('/ys')); ?>"><span class="icon"></span><span class="text">首页</span></a></li>
                <li class="navBtn"><a href="<?php echo e(asset('/ys/order/index')); ?>"><span class="icon"></span><span class="text">服务单</span></a></li>
                
                <li class="navBtn"><a href="<?php echo e(asset('/ys/index')); ?>"><span class="icon"></span><span class="text">我的</span></a></li>
            </ul>
        </div>
        
    </div>
    </body>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>
    <script type="text/javascript" src="<?php echo e(asset('/sd_js/swiper.min.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(asset('/sd_js/shop.js')); ?>"></script>
      
<?php $__env->stopSection(); ?>
<?php echo $__env->make('inheritance', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>