<?php $__env->startSection('title'); ?>
    商品详情
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>商品详情</title>
    <link rel="stylesheet" href="/sd_css/swiper.min.css">
    <link rel="stylesheet" href="/sd_css/new_common.css">
    <link rel="stylesheet" href="/sd_css/shop.css">
    
    <link href="<?php echo e(asset('sd_css/goods/good.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('sd_css/goods/product_detail.css')); ?>" rel="stylesheet">

    <style>

        *{ margin:0; padding:0; list-style:none;}
        img{ border:0;}
        .popover{
            bottom: 0px!important;
            background-color: rgb(240,240,240);
        }
        .property-sure{
            width: 100%;
            height: 46px;
            background-color: #f53a3a;
        }
        .property-sure a{
            color: white;
        }
        .property-close{
            position: absolute;
            right: 16px;
            top: 12px;
        }

        .property-unselect{
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 3px 10px;
            margin-right: 15px;
            margin-bottom: 10px;
            border-radius: 10px;
            font-size: 12px;
            color: rgb(80,80,80);
            background-color: #e4e4e4;
        }
        .property-select{
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 3px 10px;
            margin-right: 15px;
            margin-bottom: 10px;
            border-radius: 10px;
            font-size: 12px;
            color: rgb(192,192,192) !important;
            background-color: #f53a3a;
        }
        .select-goods-property{
            width: 100%;
        }
        /*.select-goods-property p{
            margin-left: 3%;
            margin-top: 5px;
            margin-bottom: 8px;
        }*/
        .goods-head{
            display: flex;
            margin-left: 3%;
            height: 70px;
        }
        .goods-head ul:first-child{
            background-color: white;
            padding: 5px;
            border: 1px solid rgb(220,220,220);
            border-radius: 6px;
            margin-top: -30px;
        }
        .goods-head ul:first-child img{
            width: 90px;

        }
        .goods-head ul:last-child{
            margin-left: 5%;
            margin-top: 14px;

        }
        .goods-property{
            display: flex;
            margin-left: 3%;
            justify-content: flex-start;
            flex-wrap: wrap;
            align-items: center;
        }

        /*商品选择数量*/
        .gift-amount{
            height: 50px;
            margin-bottom: 10px;
        }
        .gift-amount ul:first-child{
            margin-left: 3%;
        }
        .gift-amount ul:last-child{
            margin-right: 3%;
            width: 117px;
        }
        .gift-amount ul:last-child input{
            box-sizing: border-box;
            text-align: center;
            width: 60px;
            height: 30px;
            border-top: 1px solid rgb(220,220,220);
            border-bottom: 1px solid rgb(220,220,220);
            border-left: none;
            border-right: none;
            outline: none;
        }
        .min{
            box-sizing: border-box;
            height: 30px;
            width: 30px;
            border: 1px solid rgb(220,220,220);
            border-radius: 4px 0 0 4px;

        }
        .add{
            box-sizing: border-box;
            height: 30px;
            width: 30px;
            border: 1px solid rgb(220,220,220);
            border-radius: 0 4px 4px 0;

        }
        /*商品详情*/
        .detail-baseInfo .goods ul{
            padding: 5px 10px;
        }
        .goods .share {
            color: #999;
            text-align: center;
            box-sizing: border-box;
        }
        .goods .share .btn-focus {
            height: 45px;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
            display: block;
            padding-top: 5px;
            line-height: 1.4em;
            font-size: 12px;
        }
        .goods .share .icon{
            display: block;
            width: 20px;
            height: 20px;
            margin: auto;
            background: url(../sd_img/css-spd.png) 0 0 no-repeat;
            -webkit-background-size: 400px 400px;
            background-size: 400px 400px;
            background-position: 0px -47px;
        }

        .goods .share .btn-focus .icon {
            background-position: -80px 0;
        }
        /*.detail-baseInfo .goods .share .focus-out {
            background-position: 0px -3px;
        }*/
        .detail-baseInfo .price span{
            color: #e71516;
            font-weight: 600;
            font-size: 20px;
        }
        .detail-baseInfo .det {
            padding: 3px 10px;
            width: 100%;
            font-size: 12px;
            color: #525252;
            font-family: "微软雅黑";
        }
        .detail-baseInfo .ming {
            padding: 0 10px;
            font-weight: 600;
            font-weight: 600;
            font-family: "微软雅黑";
        }
        .detail-baseInfo .tong{
            height: 35px;
            background-color: #f7f7f7;
            margin-top: 2px;
            width: 95%;
            margin: 0 auto;
            border-bottom: 1px solid #f1f1f1;
        }
        .detail-baseInfo .tong li{
            margin-left: 10px;
            font-family: "微软雅黑";
        }
        .detail-baseInfo ul li .pos {
            margin-right: 10px;
            color:#ff0b01;
        }

        /*图文详情*/
        .shop-detail div{
            text-align: -webkit-center;
            height: 30px;
            background: #eee;
            margin-bottom: 10px;
            padding: 3px;
            font-size: 15px;
            font-weight: 600;
        }
        .detail-baseInfo .shop-detail img{
            width:100%;
            padding: 0 5px;
        }
        /*底部样式*/
        .bottom-menu .btn-addCart {
            height: 45px;
            line-height: 45px;
            background: #ea9b00;
            display: block;
            color: #fff;
        }
        .bottom-menu li:nth-child(1) {
            flex: 1;
            display: block;
            margin: auto;
            background: url(../sd_img/css-spd.png) 0 0 no-repeat;
            -webkit-background-size: 400px 400px;
            background-size: 400px 400px;
        }
        .bottom-menu .btn-index {
            height: 45px;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
            display: block;
            padding-top: 5px;
            line-height: 1.4em;
            font-size: 12px;
        }
        .bottom-menu .btn-index .icon {
            background-position: 0 0;
        }
        .bottom-menu li:nth-child(2) {
            flex: 0.7;
        }
        .bottom-menu li:nth-child(3) {
            flex: 0.8;
        }

    </style>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <body id="product">
    <div class="container">
        <div class="swiper-container detail-banner" id="shop">
            <div class="swiper-wrapper">
                <?php $__currentLoopData = $spuImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                    <div class="swiper-slide"><a href=""><img src="<?php echo e($img->image_url); ?>" width="100%" alt=""></a></div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
            </div>
            <!-- Add Pagination -->
            <div class="swiper-pagination"></div>
        </div>
        <input type="hidden" id="sid" value="<?php echo e($goods->spu_id); ?>">
        <div class="detail-baseInfo">
            <div class="goods">
                <ul class="flex-between  color-80">
                    <p class="price"><span>¥ <?php echo e($goods->spu_price); ?></span>
                        <?php if($grade!=10): ?>
                            <del class="font-12">¥ <?php echo e($goods->spu_market_price); ?></del>
                        <?php endif; ?>
                    </p>
                </ul>
                <div class="ming"><?php echo e($goods->spu_name); ?></div>
                <div class="det">
                    <?php echo $goods->ad_info; ?>

                </div>
            </div>
            <div class="shop-detail" id="detail">
                <div class="font-12" style="line-height: 2em;">图文详情</div>
                <?php echo $goods->mobile_content; ?>

            </div>
        </div>
        <div class="bottom-menu">
            <ul>
                <li id="directorder">
                    <a href="#popover<?php echo e($goods['spu_id']); ?>">
                        <span class="btn-buy" style="color:#ffffff;">立即购买</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    
    <!------------------------点击页脚按钮出现规格层  START!---------------------------->
    <div  class="detail">
        <div class="form-gui-ge">
            <div class="choose-gui-ge col-xs-12">
                <div class="text-right close-choose">
                    <img src="<?php echo e(asset('img/m-close.svg')); ?>" height="20" width="20"/>
                </div>
                <div class="media">
                    <a class="media-left" href="#">
                        <div>
                            <img src="<?php echo e($goods->main_image); ?>" alt="加载中..." id="choose-img">
                        </div>

                    </a>
                    <div class="media-body">
                        <h6 class="media-heading" id="choose-name" style="font-size:12px;color: rgb(80,80,80);margin-top: 14px;"><?php echo e($goods->spu_name); ?></h6>
                        <p class="jiaqian" style="color: #f13030"><span>¥&nbsp;</span><span id="choose-price"><?php echo e($goods->spu_price); ?></span>
                            
                        </p>
                        <?php if($goods->spu_points>=0): ?>
                            <p style="margin-top:5px;margin-left:-11px;margin-bottom: 5px;" class="aa" id="p_id">
                                <span  class="small-price" style="font-size:13px;color: rgb(120,120,120);padding-left: 6px;">【可支付<?php echo e($plat_vrb_caption); ?>：<span style="color: #f23030;" id="usable_points"><?php echo e($goods->spu_points); ?></span>】</span>
                            </p>
                        <?php else: ?>
                            <p style="margin-top:5px;margin-left:-11px;margin-bottom: 5px;" class="bb" id="p_id" hidden>
                                <span  class="small-price" style="font-size:13px;color: rgb(120,120,120);padding-left: 6px;">【可支付<?php echo e($plat_vrb_caption); ?>：<span style="color: #f23030;" id="usable_points">0</span>】</span>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="a-choose">
                    
                    <?php $__currentLoopData = $spuSpec; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $guige): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                        
                        <input type="hidden" value="<?php echo e(count($spuSpec)); ?>" class="spuLength"/>
                        <?php if($guige['data_type']== 'C'): ?>
                            <p class="guigeming"><?php echo e($guige['spec_name']); ?></p>
                            <div class="spu-value">
                                <?php $__currentLoopData = $guige['spec_value']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index=>$item): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                    <div style="font-size: 12px;">
                                        <a class="choose-a" style="margin: 10px 5px 10px 0px;"><?php echo e($item); ?></a>
                                        <input type="hidden" value="<?php echo e($index); ?>" class="sku_id"/>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                            </div>
                        <?php else: ?>
                            <p class="guigeming"><?php echo e($guige['spec_name']); ?></p>
                            <div class="col-xs-12 spu-value">
                                <?php $__currentLoopData = $guige['spec_value']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                    <div class="col-xs-3">
                                        <img class="dingzhiyangshi" src="<?php echo e(asset($item)); ?>" alt="<?php echo e($item); ?>" >
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                            </div>
                        <?php endif; ?>
                        
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                    
                </div>
                <div class="clearfix"></div>
                <div class="col-xs-12 cart-num">
                    <div class="col-xs-3">
                        <span>购买数量</span>
                    </div>
                    <div class="col-xs-9">
                        <div class="text-right change-number" >
                            <input class="min" name="" type="button" style="
                        width: 26px;
                        height: 26px;
                        padding: 0;
                        " value="-"/>
                            <input class="text_box text-center"  id="buy_num" name="" type="text" min_limit="1" value="1" style="
                        width: 61px;
                        height: 26px;
                        padding: 0;
                        "/>
                            <input class="add" name="" type="button" style="
                        width: 26px;
                        height: 26px;
                        padding: 0;
                        " value="+"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>

            
            <div class="col-xs-12 buy-btn">
                <form action="<?php echo e(asset('/elife/order/showPay')); ?>" method="post" name="buyForm" id="buyForm">
                    <input type="hidden" name="spuId" id="spuId">
                    <input type="hidden" name="number" id="number">
                    <input type="hidden" name="src" id="src">
                    <input type="hidden" name="guiges" id="guiges">

                    <button class="buy-now red-color f18">立即购买</button>
                </form>

            </div>

        </div>
        <!--======================点击页脚按钮出现规格层  END!============================-->

        <!------------------------选择规格时body的阴影部分  START!---------------------------->
        <div class="xiaoguo">

        </div>
        <!--======================选择规格时body的阴影部分  END!============================-->
    </div>
    </body>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script type="text/javascript" src="/sd_js/jquery-1.11.2.min.js"></script>
    <script type="text/javascript" src="/sd_js/swiper.min.js"></script>
    <script type="text/javascript" src="/sd_js/detail.js"></script>
    <script type="text/javascript" src="<?php echo e(asset('elife_js/goods/product.js')); ?>"></script>
    
<?php $__env->stopSection(); ?>
<?php echo $__env->make('inheritance', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>