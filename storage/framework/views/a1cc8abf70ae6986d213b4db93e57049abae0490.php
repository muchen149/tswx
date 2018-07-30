<?php $__env->startSection('title'); ?>
    订单详情
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('sd_css/mui.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('sd_css/common.css')); ?>">
    <style>
        .icon{
            width: 1em;
            height: 1em;
            vertical-align: -0.15em;
            fill: currentColor;
            overflow: hidden;
        }
        .address{
            border-bottom: 1px solid rgb(230,230,230);
            box-shadow: 0 1px 1px rgba(80,80,80,.1);
        }
        .address div{
            width: 95%;
            margin: 0 auto;
        }
        .location-border{
            width: 100%;
            height: 4px;
            background-image: url("../../../sd_img/address-location-border.png");
            background-repeat: repeat-x;
            background-size: 30% 4px;
            margin-bottom: 10px;
        }
        .address .r{
            margin-left: 16px;
            width: 91%;
        }
        .address .r li:first-child{
            margin-top: 10px;
            margin-bottom: 8px;
        }
        .address .r .r-b{
            margin-bottom: 10px;
            line-height: 22px;
        }



        .order-list{
            width: 100%;
        }

        .order-list .order-list-title{
            width: 100%;
            height: 40px;
        }
        .order-list .order-list-title li:first-child{
            margin-left: 3%;
        }
        .order-list .order-list-title li:last-child{
            margin-right: 3%;
        }

        .order-list-content{
            width: 100%;
            height: 110px;
            background-color: rgb(250,250,250);
        }
        .order-list-content-l{
            margin-left: 3%;
        }

        .order-list-content-r{
            margin-left: 4%;
            margin-right: 3%;

        }
        .order-list-content li:nth-of-type(1) img{
            width: 80px;
        }
        .order-list-optionBar{
            width: 100%;
        }
        .order-list-optionBar ul{
            height: 50px;
            margin-right: 3%;
        }
        .order-list-optionBar li{
            width: 88px;
            height: 32px;
            border: 1px solid rgb(220,220,220);
            border-radius: 6px;
            margin-left: 3%;
        }
        .order-list-optionBar .pay{
            border: 1px solid #f53a3a;
        }

        .order-discount{
            width: 100%;
            background-color: rgb(250,250,250);
        }
        .order-discount ul{
            width: 94%;
            height: 24px;
            margin: 0 auto;
        }
        .order-discount ul:last-child{
            height: 30px;
        }
        .order-detail{
            margin-top: 10px;
            margin-bottom: 20px;
            overflow: hidden;
        }
        .order-detail ul{
            width: 94%;
            margin: 10px auto 10px;
        }
        .show_shipping {
            background-color: white;
            border: 1px solid rgb(50, 142, 200);
            border-radius: 6px;
            width: 90px;
            height: 32px;
            margin-right: 5%;
            margin-top: 9px;
            color: rgb(50, 142, 200);
            font-size: 13px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <body>
    <div class="order-list">
        <div class="order-list-title b-white flex-between">
            <li class="font-12 color-80">订单号：<?php echo e($order_info['plat_order_sn']); ?></li>
            <li class="font-14 color-f53a3a">
                <?php if($order_info['plat_order_state']==1): ?>

                    <?php if($order_info['pay_mode_id'] == 7 ): ?>
                        电子汇款
                    <?php else: ?>
                        待付款
                    <?php endif; ?>

                <?php elseif($order_info['plat_order_state']==2): ?>

                    <?php if($order_info['pay_mode_id'] == 5 ): ?>
                        货到付款
                    <?php elseif($order_info['pay_mode_id'] == 8): ?>
                        账期支付
                    <?php else: ?>
                        已付款
                    <?php endif; ?>

                <?php elseif($order_info['plat_order_state']==3): ?>

                    <?php if($order_info['pay_mode_id'] == 5 ): ?>
                        货到付款
                    <?php elseif($order_info['pay_mode_id'] == 8): ?>
                        账期支付
                    <?php else: ?>
                        待收货
                    <?php endif; ?>


                <?php elseif($order_info['plat_order_state']==4): ?>
                    已完成
                <?php elseif($order_info['plat_order_state']==9): ?>
                    已完成
                <?php elseif($order_info['plat_order_state']==-1): ?>
                    已取消
                <?php elseif($order_info['plat_order_state']==-2): ?>
                    已退单
                <?php elseif($order_info['plat_order_state']==-5): ?>
                    已退货
                <?php elseif($order_info['plat_order_state']==-9): ?>
                    已删除
                <?php endif; ?>

            </li>
        </div>


        <!--商品信息-->
        <?php $__currentLoopData = $order_info['skus']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $goods): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
            <a href="/ys/goods/spuDetail/<?php echo e($goods['spu_id']); ?>/<?php echo e($OrderExtend->month); ?>">
            <div class="order-list-content flex-between">
                <ul class="order-list-content-l">
                    <li><img src="<?php echo e(asset($goods['sku_image'])); ?>" alt=""></li>
                </ul>
                <div class="order-list-content-r" style="width: 90%;">
                    <ul>
                        <li class="r-title font-12 color-80"><?php echo e($goods['sku_name']); ?></li>
                        <li class="r-property font-12 color-160">规格： <?php $__currentLoopData = $goods['sku_spec']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $spec): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?><span><?php echo e($spec); ?>&nbsp;</span><?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?></li></ul>
                    <ul class="font-14 color-80 flex-between">
                        <li>¥<?php echo e($goods['settlement_price']); ?></li>
                        <li>&nbsp;&times;&nbsp;<?php echo e($goods['number']); ?></li>
                    </ul>
                </div>
            </div>
            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>


            <div class="select-time" style="font-size: 12px;background-color: white;">
                <span class="time entertime"></span>
                <span>月份：</span><?php echo e($OrderExtend->month); ?>


            </div>



        <div class="order-list-optionBar font-12 color-80 b-white">
            <ul class="flex-end">

                <?php if($order_info['plat_order_state']==1): ?>
                    
                    <?php if($order_info['pay_mode_id'] == 7): ?>
                        <li class="flex-center" order_id="<?php echo e($order_info['plat_order_id']); ?>">上传支付凭证</li>

                    <?php else: ?>
                        <li class="flex-center pay" order_id="<?php echo e($order_info['plat_order_id']); ?>" id="go-to-pay">立即付款</li>

                    <?php endif; ?>
                    
                <?php elseif($order_info['plat_order_state']==2): ?>

                    <?php if($order_info['pay_mode_id'] == 5 || $order_info['pay_mode_id'] == 8): ?>
                        <li class="flex-center pay" order_id="<?php echo e($order_info['plat_order_id']); ?>" id="go-to-pay">立即付款</li>
                    <?php endif; ?>

                    
                    
                    
                    
                    
                    
                <?php elseif($order_info['plat_order_state'] == 3): ?>
                    <?php if($order_info['pay_mode_id'] == 5 || $order_info['pay_mode_id'] == 8): ?>
                        <li class="flex-center pay" order_id="<?php echo e($order_info['plat_order_id']); ?>" id="go-to-pay">立即付款</li>

                    <?php else: ?>
                        <li class="flex-center" order_id="<?php echo e($order_info['plat_order_id']); ?>">查看物流</li>
                        <li class="flex-center" order_id="<?php echo e($order_info['plat_order_id']); ?>">确认收货</li>

                    <?php endif; ?>

                <?php elseif($order_info['plat_order_state']==4 || $order_info['plat_order_state']== 9): ?>

                    <?php if($order_info['plat_order_state']== 4 && $order_info['pay_mode_id'] == 8): ?>
                        <li class="flex-center pay" order_id="<?php echo e($order_info['plat_order_id']); ?>" id="go-to-pay">立即付款</li>

                    <?php else: ?>
                        <li class="show_shipping" order_id="<?php echo e($order_info['plat_order_id']); ?>">查看物流</li>

                    <?php endif; ?>

                <?php endif; ?>


            </ul>
        </div>
    </div>

    <div class="order-discount">
        <ul class="font-12 color-160 flex-between">
            <li>商品总价</li>
            <li>¥<?php echo e($order_info['goods_amount_totals']); ?></li>
        </ul>
        <ul class="font-12 color-160 flex-between">
            <li>运费</li>
            <li>¥<?php echo e($order_info['fare_amount']); ?></li>
        </ul>
        <?php if(isset($order_info['payment']['pay_wallet'])): ?>
            <ul class="font-12 color-160 flex-between">
                <li>使用零钱</li>
                <li>-¥<?php echo e($order_info['payment']['pay_wallet']['pay_amount']); ?></li>
            </ul>
        <?php endif; ?>
        <?php if(isset($order_info['payment']['pay_card_balance'])): ?>
            <ul class="font-12 color-160 flex-between">
                <li>使用卡余额</li>
                <li>-¥<?php echo e($order_info['payment']['pay_card_balance']['pay_amount']); ?></li>
            </ul>
        <?php endif; ?>
        <?php if(isset($order_info['payment']['pay_vrb'])): ?>
            <ul class="font-12 color-160 flex-between">
                <li>使用虚拟币</li>
                <li>-¥<?php echo e($order_info['payment']['pay_vrb']['pay_amount']); ?>

                    (抵现：¥<?php echo e($order_info['payment']['pay_vrb']['pay_amount_to_rmb']); ?>)</li>
            </ul>
        <?php endif; ?>

        
        
        
        
        <ul class="font-14 flex-between">
            <li class="color-80">实付款</li>
            <li class="color-f53a3a">¥<?php echo e($order_info['pay_rmb_amount']); ?></li>
        </ul>
    </div>

    <?php if($message!=null): ?>
        <textarea style="width: 100%;height: 90px;font-size: 14px;" readonly="readonly"><?php echo e($message); ?></textarea>
    <?php endif; ?>

    
    
    
    
    
    
    

    </body>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script type="text/javascript" src="<?php echo e(asset('sd_js/mui.min.js')); ?>"></script>
    <script src="<?php echo e(asset('sd_js/jquery.min.js')); ?>"></script>
    <script src="<?php echo e(asset('sd_js/font_wvum.js')); ?>"></script>
    <script src="<?php echo e(asset('sd_js/common.js')); ?>"></script>

    <script>
        $(function(){
            orderListContent();
        });
        function orderListContent(){
            $('.order-list').find('.order-list-content:gt(0)').css('border-top','2px solid white');
        }

        //去付款
        $('#go-to-pay').on('click', function () {
            var order_id = $(this).attr('order_id');
            window.location.href = '/wx/pay/wxPay?plat_order_id=' + order_id;
        });

        //取消订单
        $('.cancel-order').on('click',function () {

            var order_id = $(this).attr('order_id');
            var cancel = $(this);
            var is_share = $(this).attr('is-share');
            var r=confirm("确定取消该订单吗？");
            if (r==true){
                if(is_share == 1){
                    //用于微信礼品分享的订单
                    $.get('/gift/cancelShareGiftOrder/'+ order_id, function (data) {
                        if (data['code'] == 0) {
                            //刷新本页面
                            window.location.reload();//刷新当前页面.
                            return true;
                        } else {
                            message(data['message']);
                        }
                    });

                }else{
                    //普通，状态为未付款的订单取消
                    $.get('/order/cancel/'+ order_id,function (data) {
                        if (data['code'] == 0) {
                            message("取消成功！");
                            //刷新本页面
                            window.location.reload();//刷新当前页面.
                            return true;
                        } else {
                            message(data['message']);
                            return false;
                        }
                    });

                }

            }

        });

        //查看物流
        $(".show_shipping").on('click', function () {
            var data = $(this).attr("order");
            window.location.href = '/order/logistics/' + data;
        });


    </script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('inheritance', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>