<?php $__env->startSection('title'); ?>
    账号信息
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('sd_css/mui.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('sd_css/common.css')); ?>">
    <script src="<?php echo e(asset('sd_js/font_wvum.js')); ?>"></script>
    <style>
        body{
            position: relative;
        }
        .user-information-item{
            position: relative;
            width: 100%;
            height: 50px;
        }
        .user-information-item div{
            position: relative;
            width: 95%;
            height: 50px;
            margin-left:auto;
            margin-right: auto;
        }
        .user-information-item div ul img{
            width: 50px;
        }

    </style>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <body>

    <div class="user-information-item flex-center b-white marginTop borderTop borderBottom" style="height: 80px">
        <div class="flex-between">
            <ul class="user-information-item-left">
                <li class="font-14 color-80">头像</li>
            </ul>
            <ul  class="user-information-item-right">
                <?php if(!empty(Auth::user()->avatar)): ?>
                    <img id="avatar"
                         src="<?php echo e(Auth::user()->avatar); ?>"
                         style="border-radius: 60px;"/>
                <?php else: ?>
                    <img style='border-radius: 60px;position: relative;z-index:0;height:80px;width:80px;margin-top:5%;'
                         src='/img/default_user_portrait.gif'>
                <?php endif; ?>
            </ul>
        </div>
    </div>

    <div class="user-information-item flex-center b-white marginTop borderTop ">
        <div class="flex-between borderBottom">
            <ul class="user-information-item-left">
                <li class="font-14 color-80">昵称</li>
            </ul>
            <ul class="user-information-item-right flex-between">
                <li class="font-12 color-100"><?php echo e(Auth::user()->nick_name); ?></li>

            </ul>
        </div>
    </div>

    <div class="user-information-item flex-center b-white">
        <div class="flex-between  borderBottom">
            <ul class="user-information-item-left">
                <li class="font-14 color-80">性别</li>
            </ul>
            <ul class="user-information-item-right flex-between">
                <li class="font-12 color-100">
                    <?php if(Auth::user()->sex == 1): ?>
                        男
                    <?php else: ?>
                        女
                    <?php endif; ?>
                </li>
            </ul>
        </div>
    </div>


        <div class="user-information-item flex-center b-white borderBottom" id="bind_mobile">
            <div class="flex-between  ">
                <input type="hidden" name="mobile" value="<?php echo e(empty(Auth::user()->mobile) ? '' : Auth::user()->mobile); ?>">

                <ul class="user-information-item-left">
                    <li class="font-14 color-80">手机号码</li>
                </ul>
                <ul class="user-information-item-right flex-between">
                    <li class="font-12 color-100">
                        <?php if(Auth::user()->mobile): ?>
                            <?php echo e(Auth::user()->mobile); ?>

                        <?php else: ?>
                            绑定手机号
                        <?php endif; ?>
                    </li>
                    <li>
                        <svg class="icon font-24 color-100" aria-hidden="true" ><use xlink:href="#icon-icon07"></use></svg>
                    </li>

                </ul>
            </div>
        </div>





    </body>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script type="text/javascript" src="/js/common-top.js"></script>
    <script>
        $(function () {
            var m = $("input[name=mobile]").val();
            $("#bind_mobile").on("click", function () {
//                alert(m);
//                if (m) {
//                    //不空说明已经绑定，跳到页面显示已绑定信息
//                    window.location.href = "/personal/userBindedView/" + m;
//                } else {
//                    //若空，未绑定，则跳到绑定页面
//                    window.location.href = "/personal/userMobileBindView";
//                }
                window.location.href = "/personal/userMobileBindView";

            });



            $("#r_account").on('click', function () {
                window.location.href = "/personal/userRegister";

            });

            $("#set_pwd").on('click', function () {
                window.location.href = "/personal/userPasswordView";

            });

            $("#address_manage").on('click', function(){
                window.location.href = '<?php echo e(asset('personal/address/addressList')); ?>';
            })
        });
    </script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('inheritance', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>