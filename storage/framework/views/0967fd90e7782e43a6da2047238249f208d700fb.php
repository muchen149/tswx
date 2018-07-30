<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('sd_css/common.css')); ?>">


    <style>
       /*.express{*/
           /*margin-top: 10px;*/
       /*}*/
       .express ul{
           position: relative;
           margin-left: 3%;
           height: 50px;
           margin-right: 3%;
       }
        .payBtn{
            width: 80%;
            margin: 40px auto ;
            height: 40px;
            border-radius: 12px;
            background-color: #f45c54;
        }
   </style>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('title'); ?>
    水丁收银台
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<body>

<div class="express b-white">
    <ul class="flex-between font-14 color-50 borderBottom">
        <li>订单金额：</li>
        <li>¥&nbsp;<?php echo e($plat_order->payable_amount); ?></li>
    </ul>
</div>

<?php if(isset($arr_payment_info['pay_vrb'])): ?>
    <div class="express b-white">
        <ul class="flex-between font-14 color-50 borderBottom">
            <li><?php echo e($plat_vrb_caption); ?>抵：</li>
            <li> ¥&nbsp;<?php echo e($arr_payment_info['pay_vrb']['pay_amount_to_rmb']); ?></li>
        </ul>
    </div>
<?php endif; ?>
<?php if(isset($arr_payment_info['pay_wallet'])): ?>

    <div class="express b-white">
        <ul class="flex-between font-14 color-50 borderBottom">
            <li>零钱支付：</li>
            <li>¥&nbsp;<?php echo e($arr_payment_info['pay_wallet']['pay_amount']); ?>

            </li>
        </ul>
    </div>
<?php endif; ?>

<?php if(isset($arr_payment_info['pay_card_balance'])): ?>
    <div class="express b-white">
        <ul class="flex-between font-14 color-50 borderBottom">
            <li>卡余额支付：</li>
            <li>
                ¥&nbsp;<?php echo e($arr_payment_info['pay_card_balance']['pay_amount']); ?>

            </li>
        </ul>
    </div>

<?php endif; ?>

<?php if(isset($arr_payment_info['org_pay_card'])): ?>
    <div class="express b-white">
        <ul class="flex-between font-14 color-50 borderBottom">
            <li>机构卡抵：</li>
            <li>
                ¥&nbsp;<?php echo e($arr_payment_info['org_pay_card']['pay_amount']); ?>

            </li>
        </ul>
    </div>

<?php endif; ?>

<div class="express b-white marginBottom">
    <ul class="flex-between font-14 color-50 ">
        <li>实付金额：</li>
        <li> ¥&nbsp;<?php echo e($plat_order->pay_rmb_amount); ?></li>
    </ul>
</div>

<div class="express b-white">
    <ul class="flex-between font-14 color-50 borderBottom">
        <li>支付方式</li>
    </ul>
</div>

<div class="express b-white mode-of-payment" p_mode = 'WechatPay' is-chose="1">
    <ul class="flex-between font-14 color-50">
        <li>
            <img src="<?php echo e(asset('img/m-weixin.svg')); ?>" height="28" width="28"/>
            <span class="f14 fz">微信支付</span>
        </li>

        <li><img src="<?php echo e(asset('img/selected.png')); ?>" height="24" class="child_img"/></li>
    </ul>
</div>


    
        
            
            
        

        
    


<div id="to_pay" class="flex-center color-white font-12 payBtn">
    立即支付
</div>
</body>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script type="text/javascript">

    $(document).ready(function () {
        function onBridgeReady() {
            WeixinJSBridge.invoke(
                    'getBrandWCPayRequest',<?php echo $wx_json; ?>, function (res) {
                        WeixinJSBridge.log(res.err_msg);
                        if (res.err_msg == "get_brand_wcpay_request:ok") {
                            //订单支付成功后，不能跳到详情，跳到订单列表中，为了刷新订单列表，因为从未付款标签中进行支付的订单，
                            //全部标签中的订单还是未付款，所以应跳到订单列表刷新订单列表
                            window.location.href ='/order/index';
                            
                        } else if (res.err_msg == "get_brand_wcpay_request:cancel") {
                            window.location.href = "<?php echo e(asset('order/index')); ?>";
                        } else {
                            window.location.href = "<?php echo e(asset('order/index')); ?>";
                        }
                    }
            );
        }

//            $('.weixin').on('click', function () {
//                if (typeof WeixinJSBridge == "undefined") {
//                    if (document.addEventListener) {
//                        document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
//                    } else if (document.attachEvent) {
//                        document.attachEvent('WeixinJSBridgeReady', onBridgeReady);
//                        document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
//                    }
//                } else {
//                    onBridgeReady();
//                }
//            });

//            $('.zhifubao').on('click', function () {
//                alert('支付宝还没写');
//            });


        function Wechat_Pay(){
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

        //点击某个支付方式时，进行判断是否是选择,微信支付是默认的支付方式
        $('.mode-of-payment').on('click',function(){
            var isChose = $(this).attr('is-chose');
            //若为1，说明要取消该支付方式
            if(isChose == 1){
                $(this).attr('is-chose',0);
                $(this).find(".child_img").attr('src','/img/defau.png');

            }else{
                //取消原来选中的支付方式
                $('.mode-of-payment').each(function(){
                    var c = $(this).attr('is-chose');
                    if(c == 1){
                        $(this).attr('is-chose',0);
                        $(this).find(".child_img").attr('src','/img/defau.png');
                    }

                });
                //设置选中的支付方式
                $(this).attr('is-chose',1);
                $(this).find(".child_img").attr('src','/img/selected.png');
            }

        });


        $("#to_pay").on("click", function () {
            //找到支付方式
            var len = $(".mode-of-payment[is-chose=1]").length;
            if(len == 1){
                var mode = $(".mode-of-payment[is-chose=1]").attr('p_mode');

                switch (mode){
//                        case 'Alipay':
//                            alert('还没有支付宝支付！');
//                            break;
                    case "WechatPay":
                        Wechat_Pay();
                        break;
                }

            }else{
                alert("请选择一种支付方式");
                return false;
            }

        });


    });

</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('inheritance', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>