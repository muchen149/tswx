<!DOCTYPE html>
<html lang="zh-CN" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=no">
    <meta name="x5-fullscreen" content="true">
    <meta name="full-screen" content="yes">

    <link href="<?php echo e(asset('css/bootstrap.min.css')); ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(asset('css/font-awesome.min.css')); ?>">
    <link href="<?php echo e(asset('css/common.css')); ?>" rel="stylesheet">
    <?php echo $__env->yieldContent('css'); ?>

    <script src="<?php echo e(asset('js/jquery.min.js')); ?>"></script>
    <script src="<?php echo e(asset('js/bootstrap.min.js')); ?>"></script>
    <script src="<?php echo e(asset('js/swiper.jquery.min.js')); ?>"></script>
    <script src="<?php echo e(asset('js/fastclick.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(asset('js/common.js')); ?>"></script>

    
    <script type="text/javascript" src="<?php echo e(asset('js/wxindex/swipe.js')); ?>"></script>

    <title>
        <?php echo $__env->yieldContent('title'); ?>
    </title>
</head>

<script>
    $(function () {
        FastClick.attach(document.body);
    });
</script>

<?php echo $__env->yieldContent('content'); ?>

<?php echo $__env->yieldContent('js'); ?>

























</html>
