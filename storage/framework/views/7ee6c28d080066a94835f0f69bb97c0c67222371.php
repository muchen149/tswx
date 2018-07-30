    

<?php $__env->startSection('css'); ?>
    <title>地址列表</title>
    <link rel="stylesheet" type="text/css" href="/css/reset.css">
    <link rel="stylesheet" type="text/css" href="/css/font-awesome.min.css">
    
    <link rel="stylesheet" type="text/css" href="/css/address/address.css">

    <link href="<?php echo e(asset('css/after-sales.css')); ?>" rel="stylesheet">


    <style type="text/css">
        body {
            font-size: 14px;
        }

        a:link, a:visited, a:hover, a:active{
            text-decoration:none;
        }
    </style>
<?php $__env->stopSection(); ?>



<?php $__env->startSection('content'); ?>
<body style="background-color: rgb(240,240,240)">
    
    <div class="address-list" id="address_list" style="margin-bottom:45px;">
     
     <?php if(empty($address_info)): ?>
            <div style="margin:0 auto;height:200px;width:200px;margin-top:40px;text-align: center">
                <img style="width:80%" src="/img/empty_quan.png">
                <p class="mt20" style="color:#323232;">亲，您暂时还没有收货地址！</p>
            </div>
     <?php else: ?>
         <?php $__currentLoopData = $address_info; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $address): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
            <div class="address"  address_id="<?php echo e($address["address_id"]); ?>">
                <ul>
                    <li>
                        <p ><?php echo e($address["recipient_name"]); ?></p >
                        <p ><?php echo e($address["mobile"]); ?></p >
                    </li >
                    <li ><p ><?php echo e($address["area_info"]); ?>&nbsp;&nbsp;<?php echo e($address["address"]); ?></p ></li >
                    <li >
                        <div class="default" name = "setaddress" address_id = "<?php echo e($address["address_id"]); ?>" >
                            <?php if($address["is_default"] == "1"): ?>
                              <img ncdefault="yes" src ="/img/address/address-default-set.png"/>
                            <?php else: ?>
                              <img ncdefault="no" src ="/img/address/address-default-pre.png"/>
                            <?php endif; ?>
                                <p > 设为默认</p >
                        </div >
                        <div class="editor"  address_id="<?php echo e($address["address_id"]); ?>">
                            <img src = "/img/address/address-edi.png" />
                            <p > 编辑</p >
                        </div >
                        <div class="delete" name = "deladdress" address_id = "<?php echo e($address["address_id"]); ?>" >
                            <img src = "/img/address/address-del.png" />
                            <p> 删除</p >
                        </div >
                    </li>
                </ul >
                <div class="fenge" style="border-bottom:0px;border-top:0px;"></div>
            </div >

         <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>

     <?php endif; ?>
    </div >
    <a class="add_address" style="position: fixed;bottom: 0;z-index: 1000;" href="<?php echo e(asset('/personal/address/addressAdd')); ?>">添加地址</a>

    <div id="pop-confirm" class="pop pop-ctm-01" style="display: none; position: fixed; top: 30%;left: 15%;">
        <div class="pop-msg">
            <div id="confirm-content" class="pop-msg-cnt" style="padding-top:18px;min-height: 50px;font-size: 15px;">
            </div>
            <div class="pop-msg-btm">
                <a id="confirm-ok-btn" class="btn-h4 btn-c3" style="width: 96px;border: 1px solid #fe6666;"
                   href="javascript:void(0);">确定</a>
                <a id="confirm-close-btn" class="btn-h4 btn-c3"
                   style="width: 96px;border: 1px solid #cfcfcf;color: #737373;background: #fff"
                   href="javascript:void(0);">取消</a>
            </div>
        </div>
    </div>
    <div class="__MASK_SID_DIV" style="display:none;width:100%;position: absolute; z-index: 999; left: 0px; top: 0px;">
        <div id="set_height" style="width:100%; height: 1024px; opacity: 0.7; background: black none repeat scroll 0% 0%; position: static; margin: 0px;"></div>
    </div>

</body>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script type="text/javascript" src="/js/zepto.min.js"></script>
    <script type="text/javascript" src="/js/template.js"></script>
    <script type="text/javascript" src="/js/common.js"></script>
    <script type="text/javascript" src="/js/simple-plugin.js"></script>
    <script type="text/javascript" src="/js/common-top.js"></script>
    <script type="text/javascript" src="/js/address/address_list.js"></script>
<?php $__env->stopSection(); ?>






<?php echo $__env->make('inheritance', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>