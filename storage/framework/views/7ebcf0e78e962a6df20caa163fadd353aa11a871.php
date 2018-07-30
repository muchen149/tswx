<?php $__env->startSection('title'); ?>
    物流信息
<?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
    <link href="<?php echo e(asset('css/header.css')); ?>" rel="stylesheet">

    <link href="<?php echo e(asset('css/index.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/footer.css')); ?>" rel="stylesheet">

    <link rel="stylesheet" href="<?php echo e(asset('css/wuliu.css')); ?>">

    <style>
        /*.show-class{*/
            /*display:block !important;*/
        /*}*/

        .hide-class{
            display:none;
        }

        .box-shadow-class{
            box-shadow: 0px 3px 3px rgba(50,50,50,.2);  !important;
        }

        .img-spin{
            transform:rotate(90deg) !important;
            transition:transform 0.5s linear;

        }

        .img-more{
            transform:rotate(0deg);
            transition:transform 0.5s linear;
        }


    </style>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <body id="logistics" style="background-color: rgb(240,240,240)">
    <div id="header"></div>

    <?php if($result != null): ?>
        <?php $__currentLoopData = $result; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $item): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
            
                
                    
                
            
            <div id="myTabContent" class="tab-content col-xs-12" >
                <div class="tab-pane fade in active " >
                    <div class="col-xs-12 goods-info change-s-h" style="
                    margin-top: 10px;
                    background-color: white;
                    /*box-shadow: 0px 3px 3px rgba(50,50,50,.2);*/
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    ">
                        <div class="col-xs-3 goods-img " style="width: 20%;margin-right: 5%">
                            <img src="<?php echo e($item['img']); ?>"/>
                            <div class="end-text">
                                <span class="f12">共<?php echo e($item['num']); ?>件商品</span>
                            </div>
                        </div>
                        <div class="col-xs-9" style="width: 65%">
                            <?php if($item['transport_type'] == 0): ?>
                                <p>物流状态：<span class="state"><?php echo e($item['State']); ?></span></p>
                                <p class="fzq f12">承运公司：<span><?php echo e($item['express_name']); ?></span></p>
                                <p class="fzq f12">运单编号：<span><?php echo e($item['waybill_code']); ?></span></p>
                            <?php elseif($item['transport_type']== 2): ?>
                                <p>车牌号：<span><?php echo e($item['transport_plate_number']); ?></span></p>
                                <p>司机姓名：<span><?php echo e($item['transport_driver_name']); ?></span></p>
                                <p>联系电话：<span><?php echo e($item['transport_tel_num']); ?></span></p>
                            <?php else: ?>
                                待发货
                            <?php endif; ?>
                            
                        </div>
                        <div style="width: 10%" class="img-div">
                            <img src="/img/right-btn.svg" style="width: 30px;"  class="img-more ">
                        </div>
                    </div>
                </div>
                
                <?php if($item['transport_type'] == 0): ?>
                    <div class="col-xs-12 list hide-class" style="margin-top: 10px; background-color: rgb(245,245,245)">
                        <?php $__currentLoopData = $item['res']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $res): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                            <div class="col-xs-12 detail">
                                <div class="col-xs-1">
                                    <div class="dot">
                                        <div class="yuan">

                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-1">

                                </div>
                                <div class="col-xs-10 detail-list">
                                    <p class="f12"><?php echo e($res->AcceptStation); ?></p>
                                    <p class="f12"><?php echo e($res->AcceptTime); ?></p>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                    </div>
                <?php elseif($item['transport_type'] == 1): ?><!--自提暂时没有-->
                    自提人姓名：<?php echo e($item['delivery_name']); ?>

                    自提人其它信息:<?php echo e($item['delivery_info']); ?>

                    提货码:<?php echo e($item['dlyo_pickup_code']); ?>

                    

                <?php endif; ?>
            </div>
        
        <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
    <?php else: ?>
        
            
        
        <div style="
        width: 100%;
        text-align: center;
        ">
            <img src="/img/wuliu-img.png" style="
            width: 125px;
            margin-top: 60px;
            ">
            <p style="
            font-size: 12px;
            color: rgb(80,80,80);
            ">您已提交了订单，请等待系统处理！</p>
        </div>
    <?php endif; ?>

    </body>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script type="text/javascript" src="<?php echo e(asset('js/common-top.js')); ?>"></script>

    <script>
        $(document).ready(function () {
//            $('.dot').each(function () {
//                var height = $(this).parent().parent().find('.detail-list').height();
//                console.log(height);
//                $(this).height(height);
//                $(this).parent().next().height(height);
//                $(this).css('borderRight', '1px solid rgba(0,0,0,0.12)')
//            });
            //$('.list').first().find('.yuan').css('backgroundColor','#24aE60')



            $('.change-s-h').on('click',function(){
                  $(this).toggleClass('box-shadow-class');
                  $(this).find('img.img-more').css('transition', 'all 0.5s').toggleClass('img-spin');
                  $(this).parent().parent().children('.list').slideToggle(400);

            });

        })
    </script>
<?php $__env->stopSection(); ?>




<?php echo $__env->make('inheritance', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>