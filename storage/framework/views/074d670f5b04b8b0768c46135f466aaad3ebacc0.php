<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>商城</title>
    <link rel="stylesheet" href="<?php echo e(asset('/sd_css/swiper.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('/sd_css/new_common.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('/sd_css/shop.css')); ?>">
</head>

<body>
<div class="container">
    <div class="search-header">
        <a href="<?php echo e(asset('/sd_shop/index')); ?>" class="btn-return icon-back"></a>
        <form id="searchFrm" action="<?php echo e(asset('/sd_shop/doSearch')); ?>">
            <i class="icon"></i>
            <input type="search" name="" id="searchKey">
            <input type="submit" value="搜索" id="search" style="right:-45px;width:50px;">
            
        </form>
    </div>
    <div class="search-history">
        <h3>历史搜索</h3>
        <span class="btn-del" onclick='deleteItem()'>删除</span>
        <ul class="list" id="list">
            

        </ul>
        <p class="tip">暂无搜索历史</p>
    </div>
    <div class="search-recommend">
        <h3>常用搜索</h3>
        <span class="btn-ctrl hide">显示</span>
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
<script type="text/javascript" src="<?php echo e(asset('/sd_js/jquery-1.11.2.min.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('/sd_js/swiper.min.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('/sd_js/search.js')); ?>"></script>
</body>

</html>