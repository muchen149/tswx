<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('css/personal.css')); ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('css/main.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('css/header.css')); ?>">
    <link href="<?php echo e(asset('css/after-sales.css')); ?>" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            list-style: none;
            font-family: arial;
        }

        body {
            background-color: #f0f2f5;
        }

        /*卡片样式*/
        .card-list {
            margin-top: 12px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .czcard-wrap {
            width: 100%;
            /*border-bottom: 1px solid rgb(120, 120, 120);*/
        }

        .czcard {
            width: 98%;
            height: 150px;
            margin: 0px auto;
            border-radius: 8px 8px 8px 8px;
            color: white;
        }



        .czcard-mess {
            height: 75px;
            display: flex;
            justify-content: flex-start;
            align-items: center;
        }

        .czcard-mess img {
            width: 41px;
            height: 41px;
            border-radius: 41px;
        }

        .czcard-mess p {
            font-size: 14px;
            line-height: 22px;
        }

        .czcard-mess .bold {
            font-weight: 600;
        }


        .czcard-time p {
            font-size: 12px;
            line-height: 34px;
            margin-right: 3%;
        }

        .flex-between {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }


    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <body>
        <div class="card-list">
            <div class="czcard-wrap">
                <div class="czcard" style="background: url(/orgcard_img/card_demo.png);background-size: 100%;">
                    <div class="czcard-mess">
                    </div>
                    <div style="padding-right:1px;height:75px;background: url(/orgcard_img/card_back.png);border-radius: 0px 0px 8px 8px;">
                        <ul class="flex-between" style="padding-top:20px;margin: 10px;;">
                            <li style="font-size: 18px;color: #2c2c2c;float:left">内蒙古大学校友卡</li>
                            <li style="font-size: 18px;color: #2c2c2c;float:left">8.3折</li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>

        <div class="card-list">
            <div class="czcard-wrap">
                <div class="czcard" style="background: url(/orgcard_img/card_demo.png);background-size: 100%;">
                    <div class="czcard-mess">
                    </div>
                    <div style="padding-right:1px;height:75px;background: url(/orgcard_img/card_back.png);border-radius: 0px 0px 8px 8px;">
                        <ul class="flex-between" style="padding-top:20px;margin: 10px;;">
                            <li style="font-size: 18px;color: #2c2c2c;float:left">内蒙古大学校友卡</li>
                            <li style="font-size: 18px;color: #2c2c2c;float:left">8.3折</li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>


    </body>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script type="text/javascript" src="<?php echo e(asset('js/common-top.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(asset('js/common.js')); ?>"></script>
    <script type="text/javascript">
        $(function () {
            $('.card-list').on('click', function () {
                var data_str = '';
                eval('data_str = ' + $(this).attr('data-param'));
                var sku_id = data_str.sku_id;
                var amount = data_str.amount;
                var price = data_str.price;
                var skus = sku_id + '-1-' + price + '-1-0';
                showConfirmDialog("确定购买<span style='color:red'>" + amount + "</span>元的充值卡？",
                        function () {
                            hideConfirmDialog();
                            loading();
                            $.post('<?php echo e(asset('order/add')); ?>', {
                                "skus": skus,
                                "sku_source_type": 8
                            }, function (res) {
                                loadSucc();
                                if (res.code == 0) {
                                    var plat_order_id = res.data.plat_order_id;
                                    window.location.href = '<?php echo e(asset('wx/pay/wxPay?plat_order_id=')); ?>' + plat_order_id;
                                } else if (res.code == 50000) {
                                    showWarnDialog(res.message, function () {
                                        window.location.href = '<?php echo e(asset('personal/userLoginView')); ?>';
                                        hideWarnDialog();
                                    });
                                } else {
                                    message(res.message);
                                }
                            });
                        });
            });
        });
    </script>
<?php $__env->stopSection(); ?>




<?php echo $__env->make('inheritance', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>