<?php $__env->startSection('title'); ?>
    地址编辑
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
    <link href="<?php echo e(asset('css/header.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/address/address-edit.css')); ?>" rel="stylesheet">

    <style>
        select {
            width: 70%;
            /*很关键：将默认的select选择框样式清除*/
            appearance: none;
            -moz-appearance: none;
            -webkit-appearance: none;

            /*为下拉小箭头留出一点位置，避免被文字覆盖*/
            color: #aeaeae;
            font-size: 14px;
            background-color: white;
            outline: none;
        }

        /*清除ie的默认选择框样式清除，隐藏下拉箭头*/
        select::-ms-expand {
            display: none;
        }

        .error-tips {
            color: red;
            display: none;
            padding: 5px 10px;
            border: 1px solid red;
            background: #FFF6D7;
            text-align: center;
        }

        .error-tips p {
            padding: 2px 0px;
        }

    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<body>
        <div id="header"></div>
     <?php $__currentLoopData = $address_info; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $address): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
        <input type="hidden" name="address_id" value="<?php echo e($address['address_id']); ?>">

        <input type="hidden" id="province_id" name="province_id" value="<?php echo e($address['province_id']); ?>">
        <input type="hidden" id="city_id" name="city_id" value="<?php echo e($address['city_id']); ?>">
        <input type="hidden" id="area_id" name="area_id" value="<?php echo e($address['area_id']); ?>">

        <input type="hidden" id="is_click_edit" name="is_click_edit" value="0">

        <ul class="body_ul">
            <li>
                <p>收货人</p>
                <input type="text" class="input-30" id="true_name" name="true_name" maxlength="8"
                       value="<?php echo e($address['recipient_name']); ?>" placeholder="请填写收货人姓名"/>
            </li>
            <li>
                <p>联系电话</p>
                <input type="tel" class="input-30" id="mob_phone" name="mob_phone" maxlength="11"
                       value="<?php echo e($address['mobile']); ?>" placeholder="请填写收货人联系电话"/>
            </li>
            <div class="fenge"></div>

            <div class="edit_div">
                <li >
                    <p style="margin-bottom: 0">所在地区</p>
                    <p class="edit_pro">
                        <span id="edit_p_c_r"><?php echo e($address['area_info']); ?></span>
                        <a class="edit_a">编辑</a>
                    </p>
                </li>
            </div>

            <div class="add_pro" style="display: none">
                <li>
                    <p>所在省份<span class="opera-tips"></span></p>
                    <select class="select-30" id="prov_select" name="prov">
                        <option value="">请选择所在省份...</option>
                        <?php $__currentLoopData = $province_dct; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                            <option value="<?php echo e($p['id']); ?>"><?php echo e($p['name']); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>

                    </select>
                </li>
                <li>
                    <p>所在城市<span class="opera-tips"></span></p>
                    <select class="select-30" id="city_select" name="city">
                        <option value="">请选择所在城市...</option>
                    </select>
                </li>
                <li id="next">
                    <p>所在区县<span class="opera-tips"></span></p>
                    <select class="select-30" id="region_select" name="region">
                        <option value="">请选择所在区县...</option>
                    </select>
                </li>
            </div>
            <li>
                <p>详细信息</p>
                <input type="text" class="input-30" id="address" name="address" maxlength="30"
                       value="<?php echo e($address['address']); ?>" placeholder="小区名称、楼栋、门牌号等"/>
            </li>
            <div class="fenge"></div>
            <li>
                <p>设为默认</p>
                <?php if($address['is_default']=='1'): ?>
                   <img nctype="setdefault" ncdefault="yes"  src="/img/address/address-default-set.png">
                    <input type="hidden"  id="is_use" name="is_use" value="1"/>
                <?php else: ?>
                   <img nctype="setdefault" ncdefault="no"   src="/img/address/address-default-pre.png">
                    <input type="hidden"  id="is_use" name="is_use" value="0"/>
                <?php endif; ?>

            </li>

        </ul>
     <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
        <div class="error-tips"></div>
        <div style="width: 100%;height: 60px;background-color: rgb(240,240,240)"></div><!--用于增加页面高度-->

        <div class="btn save_address mt10">
            <p>保存地址</p>
        </div>



</body>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script type="text/javascript" src="/js/zepto.min.js"></script>
    <script type="text/javascript" src="/js/template.js"></script>
    <script type="text/javascript" src="/js/common.js"></script>
    <script type="text/javascript" src="/js/simple-plugin.js"></script>
    <script type="text/javascript" src="/js/common-top.js"></script>
    <script type="text/javascript" src="/js/address/address_edit.js"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('inheritance', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>