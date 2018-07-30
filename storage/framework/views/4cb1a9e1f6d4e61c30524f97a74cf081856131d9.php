<?php $__env->startSection('title'); ?>
    充值卡
<?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('css/common.css')); ?>">
    <link href="<?php echo e(asset('css/after-sales.css')); ?>" rel="stylesheet">
    <style>
        header ul{
            width: 95%;
            height: 56px;
            margin: 0 auto;
        }
        header ul span{
            color: #f53a3a;
        }
        footer{
            position: fixed;
            width: 100%;
            bottom: 0;
            left: 0;
        }
        footer a{
            display: block;
            width: 50%;
            height: 49px;
            float: left;
            text-align: center;
            line-height: 49px;
        }
        footer a:first-child{
            background-color:#f53a3a;
            color:white;
        }
        footer a:last-child{
            color:black;
        }
        .top-tabbar{
            width: 100%;
            height: 50px;
            background:white;
            box-shadow: 2px 2px 2px rgba(50,50,50,0.4);
        }
        .top-tabbar ul{
            width: 90%;
            margin: 0 auto;
            color: rgba(80,80,80,.5);
        }
        .top-tabbar li{
            width: 49%;
            text-align: center;
            box-sizing: border-box;
            height: 31px;
            padding-bottom: 10px;
        }
        .select{
            border-bottom: 2px solid #f53a3a;
            color: rgba(245,58,58,1);
        }
        /*虚拟卡列表*/
        .virtual-card{
            width: 100%;
            margin: 20px 0 20px;
        }
        .virtual-card p{
            margin: 0 0 20px 2%;
            margin-left: 2%;
        }
        .virtual-card-list{
            width: 96%;
            margin: 0 auto 10px;
        }
        .virtual-card-list ul{
            box-sizing: border-box;
            width: 38%;
            height: 46px;
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: #37b7fb;
            margin:auto 20px;

        }
        .card-unselect{
            background-image: url('/img/unselected_card.png');
            background-size: 136px 43px;
            -border: 1px solid rgb(210,210,210);
            -background-color: rgb(240,240,240);
        }
        .card-select{
            background-image: url('/img/selected_card.png');
            background-size: 136px 44px;
            -border: 2px solid #f53a3a;
            -background-color: rgb(255,255,255);
        }

        /*购买选项*/
        .pay-giftCard-option ul{
            width: 95%;
            height: 50px;
            margin: 0 auto;
        }
        .pay-giftCard-option ul li:nth-child(2){
            width: 77%;
            text-align: left;
        }
        /*.pay-giftCard-btn{
            width: 80%;
            height: 44px;
            border-radius: 8px;
            margin: 30px auto 50px;
            background-color: #f53a3a;
        }*/

        /*选项卡2样式*/
        .giftCard-class{
            width: 100%;
            text-align: center;
            height: 44px;
            overflow: hidden;
        }
        .giftCard-class ul{
            color:rgb(80,80,80);
            width: 90%;
        }
        .giftCard-class ul li{
            width: 72px;
        }
        .classSelect{
            background-color: rgb(120,120,120);
            color: white;
            border-radius: 20px;
            padding: 3px 14px;
        }
        .virtual-card p {
            color:#ab6237;
            font-size: 1.6rem;
            font-weight: 500;
            font-family: "微软雅黑";
        }
        .pay-giftCard-option p {
            padding-left: 7px;
            color:#ab6237;
            font-size: 1.6rem;
            font-weight: 500;
            font-family: "微软雅黑";
        }

    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <footer>
        <a href="javascript:void(0)" class="pay-giftCard-btn b-white color-80 font-14"><li >立即充值</li></a>
        <a href="<?php echo e(asset('marketing/dispatch/checkNumberPage')); ?>" class="color-white font-14"><li >绑定新卡</li></a>
    </footer>
    <body>
        <header class="b-white border-b">
            <ul class="b-white flex-between font-16 color-50">
                <li>当前余额：<span class="font-b">￥<?php echo e($member['card_balance_available']); ?>元</span></li>
                <a href="<?php echo e(asset('personal/wallet/rechargeCards/rechargeAccountDetail')); ?>" class="flex-between color-80"><li>查看记录</li> <img src="<?php echo e(asset('img/next.png')); ?>" alt="" class="size-24"></a>
            </ul>
        </header>

        
        <div class="div div1">
            <div class="virtual-card">
                <p class="font-14 color-50">充值金额</p>

                <div class="virtual-card-list flex-between">

                    <?php $__currentLoopData = $vrgoodsList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                        <ul class="card-unselect" data-param="{sku_id:<?php echo e($item->sku_id); ?>,amount:<?php echo e($item->total_amount); ?>,price:<?php echo e($item->price); ?>,activity_id:<?php echo e($item->activity_id); ?>}">
                            <li class="font-14  color-50" style="font-size:1.6rem;color:#fff;font-family: 微软雅黑"><?php echo e($item->total_amount); ?>元</li>
                            
                        </ul>

                        <?php if(($loop->index + 1) % 3 == 0): ?>
                            <?php echo '</div><div class="virtual-card-list flex-between">'; ?>

                        <?php endif; ?>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                </div>
            </div>
            <div class="pay-giftCard-option">
                <p class="font-14 color-50">支付方式</p>
                <div class="b-white">
                    <ul class="flex-between font-14 color-50">
                        <li><img src="<?php echo e(asset('img/pay-giftCard-style1.png')); ?>" alt="" class="size-28"></li>
                        <li>微信支付</li>
                        <li class="tab-select"><img src="<?php echo e(asset('img/selected1.png')); ?>" alt="" class="size-22"><img src="<?php echo e(asset('img/unselect1.png')); ?>" alt="" class="size-22" style="display: none"></li>
                    </ul>
                </div>
            </div>
            
        </div>

        
    </body>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>
    <script type="text/javascript" src="<?php echo e(asset('js/common-top.js')); ?>"></script>
    <script type="text/javascript">
        //    充值与购买的标签
        /*$('.top-tabbar li').on('click',function(e){
            var currtentObj= e.currentTarget;
            $(this).addClass('select');
            $('.top-tabbar li').not(currtentObj).removeClass('select');
            $('.div').hide();
            if (currtentObj.id=='div1'){
                $('.div').first().fadeIn(800);
            }
            else{
                $('.div').last().fadeIn(800);
            }
        });*/

        //    选择充值卡面值
        $('.virtual-card-list ul').on('click',function(e){
            var currtentObj= e.currentTarget;
            $(this).addClass('card-select');
            $('.virtual-card-list ul').not(currtentObj).removeClass('card-select');
        });

        //    切换支付方式
        $('.tab-select').on('click',function(e){
            var target= e.currentTarget;
            $(target).find('img').toggle();
        });

        //    切换礼品卡的类型
        $('.giftCard-class li').on('click',function(e){
            var currentObj= e.currentTarget;
            $(this).addClass('classSelect');
            $('.giftCard-class li').not(currentObj).removeClass('classSelect');

        })


        var wxJson = '';
        //立即支付处理
        $('.pay-giftCard-btn').on('click',wx_pay_event);
        function wx_pay_event() {
            var data_str = '';
            if($('.card-select').attr('data-param')){
                $(".pay-giftCard-btn").unbind('click');
                eval('data_str = ' + $('.card-select').attr('data-param'));
                var sku_id = data_str.sku_id;
                var amount = data_str.amount;
                var price = data_str.price;
                var activity_id=data_str.activity_id;
                var skus = sku_id + '-1-' + price + '-1-0';
                //alert("skus"+skus+"amount"+amount);

                $.post('<?php echo e(asset('personal/getPayJosnForRechargeByajax')); ?>', {
                    "skus": skus,
                    "sku_source_type": 8,
                    "activity_id":activity_id,
                    "amount":amount
                }, function (res) {
                    if (res.code == 0) {
                        wxJson = res.data.wx_json;
                        Wechat_Pay();
                    } else {
                        message(res.message, 2000);
                    }
                });



            }else{
                message("请选择充值面额！");

            }
        }

        function onBridgeReady() {
            WeixinJSBridge.invoke(
                    'getBrandWCPayRequest', wxJson, function (res) {
                        if (res.err_msg == "get_brand_wcpay_request:ok") {
                            window.location.href = '<?php echo e(asset('personal/wallet/rechargeCards/rechargeAccountDetail')); ?>';
                        }else if(res.err_msg == "get_brand_wcpay_request:cancel"){
                            window.history.go(-1);
                        }else if(res.err_msg == "get_brand_wcpay_request:fail"){
                            window.history.go(-1);
                        }
                    }
            );
        }

        function Wechat_Pay() {
            if (typeof WeixinJSBridge == "undefined") {
                if (document.addEventListener) {
                    document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
                } else if (document.attachEvent) {
                    document.attachEvent('WeixinJSBridgeReady', onBridgeReady);
                    document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
                }
            } else {
                onBridgeReady();
            }
        }
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('inheritance', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>