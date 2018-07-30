<?php $__env->startSection('title'); ?>
    更多卡券
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('sd_css/common.css')); ?>">
    <style>
        a {
            color: #333;
        }

        header {
            height: 66px;
        }

        header a {
            display: block;
            box-sizing: border-box;
            text-align: center;
            width: 33%;
        }

        header a:nth-child(2) {
            border-left: 1px solid rgb(220, 220, 220);
            border-right: 1px solid rgb(220, 220, 220);
        }

        header a:nth-child(3) {
            border-right: 1px solid rgb(220, 220, 220);
        }

        header ul li:first-child {
            color: #ff7022;
            font-weight: bolder;
            margin-bottom: 6px;
        }

        main {
            overflow: hidden;
            margin-top: 10px;
        }

        .line1 {
            margin-top: 16px;
            padding-bottom: 16px;
            border-bottom: 1px solid rgb(220, 220, 220);
        }

        .line2 {
            padding-top: 16px;
            margin-bottom: 16px;
        }

        main ul li:first-child {
            margin-bottom: 6px;
        }

        main img {
            width: 50px;
            height: 50px;
        }
    </style>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <body>
    <header class="flex-between b-white">
        <a href="<?php echo e(asset('personal/walletLog')); ?>">
            <ul class="font-14 color-80 money">
                <li>¥<?php echo e($member->wallet_available); ?></li>
                <li>零钱</li>
            </ul>
        </a>
        <a href="<?php echo e(asset('personal/wallet/rechargeCards/myRechargeCard')); ?>">
            <ul class="font-14 color-80 balance">
                <li>¥<?php echo e($member->card_balance_available); ?></li>
                <li>卡余额</li>
            </ul>
        </a>

        <a href="<?php echo e(asset('personal/wallet/giftCoupons/myGiftCoupon')); ?>">
            <ul class="font-14 color-80 balance">
                <li><?php echo e($wallet_num_arr['coupon_num']); ?></li>
                <li>礼券</li>
            </ul>
        </a>

        <a href="<?php echo e(asset('personal/vrcoinLog')); ?>">
            <ul class="font-14 color-80 coin">
                <li><?php echo e(intval($member->yesb_available)); ?></li>
                <li><?php echo e($plat_vrb_caption); ?></li>
            </ul>
        </a>
    </header>

    <main class="b-white">
        <ul class="flex-around b-white line1">
            <li>
                <a href="">
                    <ul class="flex-column-between font-14 color-80">
                        <li><img src="<?php echo e(asset('sd_img/more-card1.png')); ?>" alt=""></li>
                        <li>线上优惠券</li>
                    </ul>
                </a>
            </li>

            <li>
                <a href="">
                    <ul class="flex-column-between font-14 color-80">
                        <li><img src="<?php echo e(asset('sd_img/more-card2.png')); ?>" alt=""></li>
                        <li>礼券</li>
                    </ul>
                </a>
            </li>

            <li>
                <a href="<?php echo e(asset('membership/getCardList')); ?>">
                    <ul class="flex-column-between font-14 color-80">
                        <li><img src="<?php echo e(asset('sd_img/more-card3.png')); ?>" alt=""></li>
                        <li>会员卡</li>
                    </ul>
                </a>
            </li>
        </ul>

        <ul class="flex-around b-white line2">

            <li>
                <a href="">
                    <ul class="flex-column-between font-14 color-80">
                        <li><img src="<?php echo e(asset('sd_img/more-card4.png')); ?>" alt=""></li>
                        <li>门店消费券</li>
                    </ul>
                </a>
            </li>

            <li>
                <a href="">
                    <ul class="flex-column-between font-14 color-80">
                        <li><img src="<?php echo e(asset('sd_img/more-card5.png')); ?>" alt=""></li>
                        <li>门店卡</li>
                    </ul>
                </a>
            </li>

            <li>
                <a href="">
                    <ul class="flex-column-between font-14 color-80">
                        <li><img src="<?php echo e(asset('sd_img/more-card6.png')); ?>" alt=""></li>
                        <li>红包</li>
                    </ul>
                </a>
            </li>
        </ul>
    </main>
    </body>
<?php $__env->stopSection(); ?>





<?php echo $__env->make('inheritance', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>