<?php $__env->startSection('title'); ?>
    订单列表
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('sd_css/mui.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('sd_css/common.css')); ?>">
    <style>
        *{ margin:0; padding:0; list-style:none;}
        img{ border:0;}

        .lanrenzhijia {position: fixed;left: -100%;right:100%;top:0;bottom: 0;text-align: center;font-size: 0; z-index:9999; display:none;}
        .lanrenzhijia:after {content:"";display: inline-block;vertical-align: middle;height: 100%;width: 0;}
        .content{display: inline-block; *display: inline; *zoom:1;	vertical-align: middle;position: relative;right: -100%;}
        .content_mark{ width:100%; height:100%; position:fixed; left:0; top:0; z-index:555; background:#000; opacity:0.5;filter:alpha(opacity=50); display:none;}

        .mui-segmented-control-primary{
            position: fixed;
            top: 0;
            padding: 0 4px;
            z-index: 999999;
        }
        /*改写mui顶部标签切换组件的选中样式*/
        .mui-control-item.mui-active{
            color: #f53a3a!important;
            border-bottom: 2px solid #f53a3a!important;
        }
        .mui-control-content{
            margin-top: 50px;
        }
        .order-list{
            width: 100%;
            margin-bottom: 10px;
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
            width: 80%;
        }
        .order-list-content li:nth-of-type(1) img{
            width: 80px;
        }
        .order-list-count{
            width: 100%;
            border-bottom: 1px solid rgb(230,230,230);
        }
        .order-list-count ul{
            height: 40px;
            margin-right: 3%;
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
        .show_shipping {
            width: 88px;
            height: 32px;
            border: 1px solid rgb(220,220,220);
            border-radius: 6px;
            margin-left: 3%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <body>
    <div class="mui-segmented-control mui-segmented-control-inverted mui-segmented-control-primary b-white">
        <a class="mui-control-item  font-12 <?php if(empty($state)): ?> mui-active <?php endif; ?>" href="/ys/order/index">全部</a>
        <a class="mui-control-item font-12 color-80 <?php if($state == '1'): ?> mui-active <?php endif; ?>" href="/ys/order/index/1">待付款</a>
        <a class="mui-control-item font-12 color-80 <?php if($state == '2'): ?> mui-active <?php endif; ?>" href="/ys/order/index/2">待发货</a>
        <a class="mui-control-item font-12 color-80 <?php if($state == '3'): ?> mui-active <?php endif; ?>" href="/ys/order/index/3">待收货</a>
        <a class="mui-control-item font-12 color-80 <?php if($state == '9'): ?> mui-active <?php endif; ?>" href="/ys/order/index/9">已完成</a>
    </div>
    <div  class="mui-control-content mui-active">


        <?php if($orders_info== null ): ?>
            <div style="margin:0 auto;height:200px;width:200px;margin-top:40px;text-align: center">
                <img style="width:80%" src="/sd_img/empty_quan.png">
                <p style="color:#323232;font-size:14px;margin-top:14px;">暂无相应订单！</p>
            </div>
        <?php else: ?>
            <?php $__currentLoopData = $orders_info; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order_info): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                <div class="order-list">
                    <div class="order-list-title b-white flex-between">
                        <li class="font-12 color-80">订单号： <?php echo e($order_info['plat_order_sn']); ?></li>
                        <li class="font-14 color-f53a3a">
                            <?php if($order_info['plat_order_state']==1): ?>
                                <?php if($order_info['pay_mode_id'] == 7 ): ?>
                                    <span>电子汇款</span>
                                <?php else: ?>
                                    <span>待付款</span>
                                <?php endif; ?>

                            <?php elseif($order_info['plat_order_state']==2): ?>
                                
                                <?php if($order_info['pay_mode_id'] == 5 ): ?>
                                    <span>货到付款</span>
                                <?php elseif($order_info['pay_mode_id'] == 8): ?>
                                    <span>账期支付</span>
                                <?php else: ?>
                                    <span>已付款</span>
                                <?php endif; ?>

                            <?php elseif($order_info['plat_order_state']==3): ?>

                                <?php if($order_info['pay_mode_id'] == 5 ): ?>
                                    <span>货到付款</span>
                                <?php elseif($order_info['pay_mode_id'] == 8): ?>
                                    <span>账期支付</span>
                                <?php else: ?>
                                    <span>待收货</span>
                                <?php endif; ?>

                            <?php elseif($order_info['plat_order_state']==4): ?>
                                <span>待评价</span>
                                
                                
                            <?php elseif($order_info['plat_order_state']==5): ?>
                                <span>售后处理中</span>
                            <?php elseif($order_info['plat_order_state']==9): ?>
                                <span>已完成</span>
                                
                            <?php elseif($order_info['plat_order_state']==-1): ?>
                                <span>已取消</span>
                            <?php elseif($order_info['plat_order_state']==-2): ?>
                                <span>已退单</span>
                            <?php elseif($order_info['plat_order_state']==-5): ?>
                                <span>已退货</span>
                            <?php elseif($order_info['plat_order_state']==-9): ?>
                                <span>已删除</span>
                            <?php endif; ?>

                            
                            <?php if($order_info['is_share_gifts']): ?>
                                <img src="/sd_img/wx-share-gift.png" style="width:75px;margin-top:0px;position: absolute;right: 40px;"/>
                            <?php endif; ?>
                            
                            <?php if($order_info['is_get_gift']): ?>
                                <img src="/sd_img/get-share-gift.png" style="width:75px;margin-top:0px;position: absolute;right: 40px;"/>
                            <?php endif; ?>
                        </li>
                    </div>

                    
                    <?php $__currentLoopData = $order_info['skus']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $goods): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                        <a href="<?php echo e(asset('ys/order/info/'.$order_info['plat_order_id'])); ?>">
                            <div class="order-list-content flex-between">
                                <ul class="order-list-content-l">
                                    <li><img src="<?php echo e(asset($goods['sku_image'])); ?>" alt=""></li>
                                </ul>
                                <div class="order-list-content-r">
                                    <ul>
                                        <li class="r-title font-12 color-80"><?php echo e($goods['sku_name']); ?></li>
                                        <li class="r-property font-12 color-160">规格： <?php $__currentLoopData = $goods['sku_spec']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $spec): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?><span><?php echo e($spec); ?>&nbsp;</span><?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?></li></ul>
                                    <ul class="font-14 color-80 flex-between">
                                        <li>¥&nbsp;<?php echo e($goods['settlement_price']); ?></li>
                                        <li>&nbsp;&times;&nbsp;<?php echo e($goods['number']); ?></li>
                                    </ul>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>

                    <div class="order-list-count b-white">
                        <ul class="font-12 color-80 flex-end">
                            <li>
                                
                                实付款：¥<span class="color-f53a3a"><?php echo e($order_info['pay_rmb_amount']); ?></span></li>
                        </ul>
                    </div>

                    <div class="order-list-optionBar font-12 color-80 b-white">
                        <ul class="flex-end">
                            
                            <?php if($order_info['plat_order_state']==1): ?>
                                
                                <?php if($order_info['pay_mode_id'] == 7): ?>
                                    
                                    <input type="hidden" value="<?php echo e($order_info['plat_order_id']); ?>"/>
                                    <li class="flex-center cancel-order" is-share="<?php echo e($order_info['is_share_gifts']); ?>">取消订单</li>
                                <?php else: ?>
                                    <li class="flex-center pay go-to-pay">立即付款</li>
                                    <input type="hidden" value="<?php echo e($order_info['plat_order_id']); ?>"/>
                                    <li class="flex-center cancel-order" is-share="<?php echo e($order_info['is_share_gifts']); ?>">取消订单</li>
                                <?php endif; ?>

                                

                            <?php elseif($order_info['plat_order_state']==2): ?>
                                <?php if($order_info['pay_mode_id'] == 5 || $order_info['pay_mode_id'] == 8): ?>
                                    <li class="flex-center pay go-to-pay">立即付款</li>
                                    <input type="hidden" value="<?php echo e($order_info['plat_order_id']); ?>"/>
                                    
                                <?php endif; ?>

                                
                                <?php if($order_info['is_get_gift'] == 0): ?>
                                    <input type="hidden" value="<?php echo e($order_info['plat_order_id']); ?>"/>
                                    
                                    <li class="refund-order flex-center" order="<?php echo e($order_info['plat_order_id']); ?>">申请退款</li>
                                <?php endif; ?>

                                
                            <?php elseif($order_info['plat_order_state']==3): ?>

                                <?php if($order_info['pay_mode_id'] == 5 || $order_info['pay_mode_id'] == 8): ?>
                                    <li class="flex-center pay go-to-pay">立即付款</li>
                                    <input type="hidden" value="<?php echo e($order_info['plat_order_id']); ?>"/>
                                <?php else: ?>
                                    <li class="flex-center sure_order" order="<?php echo e($order_info['plat_order_id']); ?>">确认收货</li>

                                <?php endif; ?>

                            <?php elseif($order_info['plat_order_state']==4): ?>

                                <?php if($order_info['pay_mode_id'] == 8): ?>
                                    <li class="flex-center pay go-to-pay ">立即付款</li>
                                    <input type="hidden" value="<?php echo e($order_info['plat_order_id']); ?>"/>

                                <?php else: ?>
                                    <span style="margin-right: 35%;">赠送(积分):<span style="color:red"><?php echo e($order_info['getIntegral']); ?></span></span>
                                    <span class="del-order" order="<?php echo e($order_info['plat_order_id']); ?>">删除订单</span>
                                <?php endif; ?>


                            <?php elseif($order_info['plat_order_state']==9): ?>
                                <li class="flex-center">删除订单</li>
                            <?php endif; ?>

                            <?php if($order_info['plat_order_state'] >= 3): ?>
                                <li class="show_shipping" order="<?php echo e($order_info['plat_order_id']); ?>" >查看物流</li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
        <?php endif; ?>
    </div>
    <div class="lanrenzhijia" id="pop-ad">
        <div class="content">
            <img width="270px;" height="290px" src="<?php echo e(asset('/sd_img/subscribe_pic.png')); ?>" usemap="#Map" />
            <map name="Map">
                <area shape="rect" coords="234,5,265,31" href="javascript:hideSubscribe();">
            </map>
        </div>
    </div>
    <div class="content_mark"></div>
    </body>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script src="<?php echo e(asset('sd_js/jquery.min.js')); ?>"></script>
    <script src="<?php echo e(asset('sd_js/font_wvum.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(asset('sd_js/order/order_list.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(asset('sd_js/common.js')); ?>"></script>
    <script type="text/javascript">
        function showSubscribe() {
            $('.lanrenzhijia').show(0);
            $('.content_mark').show(0);
        }

        function hideSubscribe(){
            $('.lanrenzhijia').hide(0);
            $('.content_mark').hide(0);
        }



    </script>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('inheritance', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>