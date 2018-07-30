<?php $__env->startSection('title'); ?>
    申请退单
<?php $__env->stopSection(); ?>
<?php $tmp = ''; ?>
<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('sd_css/mui.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('sd_css/common.css')); ?>">

    <style>
        body {
            background-color: white;
        }
        #order_id .goods-list{
            justify-content: flex-start;
            padding: 10px 0px 5px 5px;
        }
        #order_id .goods-list .supplier_nav{
            width: 33px;
            height: 22px;
            padding: 0px 5px 0 10px;
        }
        #order_id .goods-list .supplier_name{
            font-size: 15px;
            font-family: 微软雅黑;
        }
        /*商品信息*/
        .order-list-content {
            width: 100%;
            height: 110px;
            background-color: rgb(250,250,250);
        }
        .flex-between {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .order-list-content-l {
            margin-left: 3%;
        }
        .order-list-content li:nth-of-type(1) img {
            width: 80px;
        }
        .order-list-content-r {
            margin-left: 4%;
            margin-right: 3%;
            width: 80%;
        }
        .order-discount ul {
            width: 94%;
            height: 24px;
            margin: 0 auto;
        }



        .turn-status2{
            width: 22px;
            height: 22px;
            background: url("../../sd_img/unselecte.png") no-repeat;
            background-size: 100%;
        }
        .turn-status1{
            width: 22px;
            height: 22px;
            background: url("../../sd_img/selected.png") no-repeat;
            background-size: 100%;
        }
        /*底部操作条 start*/
        .actionBar{
            position: fixed;
            bottom: 0px;
            box-sizing: border-box;
            width:100%;
            height: 50px;
            border-top: 1px solid rgb(220,220,220);
            background-color: rgba(255,255,255,0.8);
        }
        .actionBar .heji{
            text-align: center;
        }
        .actionBar .goApply{
            width: 25%;
            height: 51px;
            background-color: #f53a3a;
        }
        .actionBar .goApply a{
            color: white;
        }
        .actionBar .delete{
            width: 25%;
            height: 51px;
            background-color: #f53a3a;
        }

        .turn-status-all-2{
            width: 22px;
            height: 22px;
            background: url("../../sd_img/unselecte.png") no-repeat;
            background-size: 100%;
        }
        .turn-status-all-1{
            width: 22px;
            height: 22px;
            background: url("../../sd_img/selected.png") no-repeat;
            background-size: 100%;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <body>
    
    <div id="order_id" code="<?php echo e($order_info['plat_order_id']); ?>">
        <?php $__currentLoopData = $order_info['skus']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $goods): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                <?php if($goods['supplier_name']!=$tmp): ?>
                <div class="goods-list b-white flex-between">
                    <ul class="l">
                        <li class="turn-status2" supplier_id="<?php echo e($goods['supplier_id']); ?>" supplier_name="<?php echo e($goods['supplier_name']); ?>"></li>
                    </ul>
                    <div class="supplier_nav"><img src="<?php echo e(asset('/sd_img/supplier_nav.png')); ?>" alt="" width="100%"></div>
                    <div class="supplier_name font-14"><?php echo $tmp=$goods['supplier_name']; ?></div>
                </div>

                <?php endif; ?>
                <a href="<?php echo e(asset('order/info/'.$order_info['plat_order_id'])); ?>">
                    <div class="order-list-content flex-between">
                        <input type="hidden" value="<?php echo e($goods['supplier_id']); ?>_<?php echo e($goods['sku_id']); ?>" name="ipt_<?php echo e($goods['supplier_id']); ?>"/>
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
    </div>
    <div class="liuyan" style="width: 100%;">
        <ul class="font-14 color-80" style="padding: 10px 10px 0px 10px;border-radius:3px;">
            <li>退款原因：</li>
            <textarea class="form-control"  rows="3" placeholder="不想要了!" autocomplete="off" style="outline: none;resize: none;box-shadow: inset 0 0px 0px rgb(255,255,255);background-color: white;margin-top: 0px;">不想要了!</textarea>

        </ul>

    </div>
    <div class="actionBar flex-between">
        <ul class="flex-between" style="margin-left: 3%">
            <li class="turn-status-all-2" id="turn" >
            </li>
            <li class=" font-14 color-50" style="margin-left: 6px;margin-top: 4px">全选</li>
        </ul>
        
        <ul class="goApply color-white flex-center font-14">
            <li del="0">立即申请</li>
        </ul>

    </div>
    </body>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script src="<?php echo e(asset('sd_js/jquery.min.js')); ?>"></script>
    <script src="<?php echo e(asset('sd_js/common.js')); ?>"></script>
    <script src="<?php echo e(asset('sd_js/font_wvum.js')); ?>"></script>
    <script type="text/javascript">

        /*$("#bind_btn").on('click', function () {
            $.post('/order/saleOrder',data, function (res) {
                if(res.code == 0){
                    window.location.href = '/order/index/2';
                    plat_order_id = res.data.plat_order_id;
                     share_gifts_info_id = res.data.share_gifts_info_id;
                     var type = res.data.type;
                     if(type == 1){ //若为1表示全部用非人民支付，跳到分享页面
                     window.location.href ='/gift/giftToShare/'+share_gifts_info_id;
                     }else{ //还需要进行微信支付
                     //wxJson = res.data.wx_json;
                     Wechat_Pay();
                     window.location.href = '/wx/pay/wxPay?plat_order_id='+plat_order_id;
                     }

                }else{
                    message(res.message,5000);
                    return false;
                }
            });
        });*/
        $(".goApply").on('click', function () {
            if ($(".turn-status1").length == 0) {
                message('请选择退单商品！');
                return false;
            } else {
                $supplier_id_arr = [];
                $supplier_name_arr = [];
                $sku_ids=[];
                $('.turn-status1').each(function () {
                    var supplier_id = $(this).attr("supplier_id");
                    var supplier_name = $(this).attr('supplier_name');
                    var t_name="[name='ipt_"+supplier_id+"']";
                    $(t_name).each(function(i,item){
                        $sku_ids.push(item.value);
                    });
                    $supplier_id_arr.push(supplier_id);
                    $supplier_name_arr.push(supplier_name);
                });
                /*$('.supplier_name').each(function () {
                    var supplier_name = $(this).html();
                    $supplier_name_arr.push(supplier_name);
                });*/
                //$('#cartIds').val($arr.join(','));

                var data = {
                    'sku_ids':$sku_ids,
                    'supplier_id':$supplier_id_arr,
                    'supplier_name':$supplier_name_arr,
                    'plat_order_id': $('#order_id').attr('code'),
                    'text': $('textarea').val()
                };

                $.post('/order/saleOrder',data, function (res) {
                    if(res.code == 0){
                        window.location.href = '/order/saleOrderState';
                        //window.location.href = '/order/index/2';
                        /*plat_order_id = res.data.plat_order_id;
                         share_gifts_info_id = res.data.share_gifts_info_id;
                         var type = res.data.type;
                         if(type == 1){ //若为1表示全部用非人民支付，跳到分享页面
                         window.location.href ='/gift/giftToShare/'+share_gifts_info_id;
                         }else{ //还需要进行微信支付
                         //wxJson = res.data.wx_json;
                         Wechat_Pay();
                         window.location.href = '/wx/pay/wxPay?plat_order_id='+plat_order_id;
                         }*/

                    }else if(res.code == 1){
                        message('操作错误,其中的商品已退单');
                    }else{
                        message(res.message,5000);
                        return false;
                    }
                });

            }

        });

        //     调用函数
        $(function(){
            //layout();
            //Count();
            notAllSelect();
        });

        //     点击全选
        $('#turn').on('click',function(){
            var lengthParent=$('.goods-list').length;
            var lengthChild=$('.turn-status1').length;

            //   判断是否全选，全选时，点击后将取消全选，并改变全选按钮状态
            if(lengthChild==lengthParent){
                $('.l li').attr('class','turn-status2');
                $(this).attr('class','turn-status-all-2');
            }

            //    判断不全选，改变商品列表以及全选按钮的选中状态，并给商品列表的按钮增加一个类
            else{
                $('.l li').attr('class','turn-status1').addClass('turn-status2');//将$('.l li')的class设置为turn-status1后，与519行代码冲突，导致$('.l li')没有类名，故增加一个类，且该类的优先级小于'turn-status1'
                $(this).attr('class','turn-status-all-1');
            }

            //var total_price = get_goods_amount();
            //$(".total_price").text(total_price);
            //Count(); //统计选中的商品数
        });

        //     选择或取消选择商品
        $('.l').on('click',function(e){
            var current= e.currentTarget;
            $(current).find('li').toggleClass('turn-status1');
            notAllSelect();

            //var total_price = get_goods_amount();
            //$(".total_price").text(total_price);
            //Count(); //统计选中的商品数
        });

        //  函数-商品列表不全选时，“全选按钮”取消选中
        function notAllSelect(){
            var lengthParent=$('.goods-list').length;
            var lengthChild=$('.turn-status1').length;
            if (lengthChild<lengthParent){
                $('#turn').attr('class','turn-status-all-2');
            }
            else if(lengthChild==lengthParent){
                $('#turn').attr('class','turn-status-all-1');
            }
        }

        // 提交申请
        //去支付或者删除
        $(".goPay").on('click',function(){
            goSettlement();

        });

        function goSettlement(){

            //查看选中的商品个数，没有选择不让提交
            if ($(".turn-status1").length == 0) {
                message('请选择退单商品！');
                return false;
            } else {
                $arr = [];
                $('.turn-status1').each(function () {
                    var cart_id = $(this).attr("cart_id");
                    $arr.push(cart_id);
                });
                $('#cartIds').val($arr.join(','));
                $('#jsform').submit();

            }

        }
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('inheritance', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>