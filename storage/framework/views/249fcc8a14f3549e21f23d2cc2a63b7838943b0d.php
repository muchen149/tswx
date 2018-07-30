<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('sd_css/common.css')); ?>">
    <style>
        .wxGift-succeed-tip{
            margin-top: 40%;
            width: 100%;
            text-align: center;
        }
        .wxGift-succeed-tip img{
            width: 50%;
        }
        .wxGift-succeed-tip p{
            margin-top: 12px;
        }
        .wxGift-succeed-btn-Group{
            margin-top: 20%;
            width: 100%;
            text-align: center;
        }
        .wxGift-succeed-btn-Group .btn{
            display: flex;
            justify-content: center;
            align-items: center;
            width: 80%;
            height: 44px;
            margin-left: 10%;
            border-radius: 6px;
            box-shadow: 1px 1px 2px rgba(50,50,50,.4);
        }
        .wxGift-succeed-btn-Group .btn li:first-child{
            margin-right: 10px;
        }
        .wxGift-succeed-attention img{
            width: 120px;
            height: 120px;
        }
        .wxGift-succeed-attention li:last-child{
            margin-top: 6px;
        }


    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <body>
    <div class="wxGift-succeed-tip">
        <p class="font-22 color-80">您已领取过该卡片。</p>
        <p class="font-22 color-80">请直接使用，谢谢！</p>
        <p class="font-14 color-80"><a href="/orgcards/orgcardInfo/<?php echo e($orgcard_id); ?>"><img src="<?php echo e(asset('orgcard_img/btnConfirm.png')); ?>" alt=""></a></p>
    </div>

    </body>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('inheritance', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>