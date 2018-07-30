<?php $__env->startSection('title'); ?>
    我的礼包
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
    <link href="<?php echo e(asset('css/header.css')); ?>" rel="stylesheet" type="text/css"/>
    <style>
        * {
            margin: 0;
            padding: 0;
            list-style: none;
            font-family: arial;
        }

        body {
            background-color: rgb(240, 240, 240);
        }

        .lq-list {
            width: 96%;
            margin: 10px auto 0;
            height: 90px;
            background-color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /*左侧样式*/
        .lq-list .list-img {
            margin-left: 1%;
            width: 24%;
            text-align: center;
        }

        .lq-list .list-img img {
            width: 87px;
        }

        /*中间样式*/
        .lq-list .list-text {
            margin-left: 2%;
            width: 46%;
        }

        .list-text .text-title {
            font-size: 14px;
            margin-bottom: 6px;
            font-weight: 800;
            color: rgb(50, 50, 50);
        }

        .list-text .text-intro {
            font-size: 14px;
            width: 150px;
            height: 16px;
            overflow: hidden;
            margin-bottom: 6px;
            color: rgb(100, 100, 100);
        }

        .list-text .text-time {
            font-size: 12px;
            color: rgb(100, 100, 100);
        }

        /*右侧样式*/
        .list-control {
            margin-right: 3%;
            width: 24%;
            text-align: center;
        }

        .list-control p {
            font-size: 20px;
            color: rgb(80, 80, 80);
            font-weight: 800;
            margin-bottom: 8px;
        }

        .control-btn {
            width: 80px;
            margin: 0 auto;
            height: 30px;
            border-radius: 6px;
            background-color: #e83828;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 12px;
            color: white;
        }

        .control-font {
            width: 80px;
            margin: 0 auto;
            height: 30px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 12px;
            color: rgb(80, 80, 80);
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <body style="background-color: rgb(240,240,240)">
    <div id="header"></div>
    <?php if(!$giftCouponList): ?>
        <div style="margin:0 auto;height:200px;width:200px;margin-top:50px;text-align: center">
            <img style="width:47%" src="<?php echo e(asset('img/default_list.png')); ?>">
            <p class="mt20" style="margin-top:10px;font-size:14px;color:rgb(80,80,80);">暂无可用礼券</p>
        </div>
    <?php else: ?>
        <?php $__currentLoopData = $giftCouponList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $coupon): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
            <div class="lq-list">
                <div class="list-img">
                    <img src="<?php echo e($coupon['activity_images']); ?>" alt="">
                </div>
                <div class="list-text">
                    <p class="text-title"><?php echo e($coupon['activity_name']); ?></p>
                    <p class="text-time">有效期至:<?php echo e($coupon['expire_time']); ?></p>
                </div>
                <div class="list-control">
                    <p class="control-title">礼券</p>
                    <div class="control-btn" coupon_id="<?php echo e($coupon['giftcoupon_id']); ?>">
                        立即使用
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
    <?php endif; ?>
    </body>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script type="text/javascript" src="<?php echo e(asset('js/common-top.js')); ?>"></script>
    <script type="text/javascript">
        $(function () {
            $('.control-btn').on('click', function () {
                window.location.href = '<?php echo e(asset('personal/wallet/giftCoupons/choseGiftCouponGoods')); ?>' + '/' + $(this).attr('coupon_id');
            });
        });
    </script>
<?php $__env->stopSection(); ?>




<?php echo $__env->make('inheritance', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>