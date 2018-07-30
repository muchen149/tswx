<?php $__env->startSection('title'); ?>
    卡券绑定
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>

    <link rel="stylesheet" href="<?php echo e(asset('css/common.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/mui.min.css')); ?>"/>
    <style>
        body{
            background-color: white;
        }
        .bind{
            width: 100%;
            margin-top: 80px;
        }
        .mui-input-row{
            width: 85%;
            height: 50px;
            margin: 20px auto 0;
            border-bottom: 1px solid rgb(220,220,220);
        }
        .mui-btn{
            background-color: #f53a3a;
            border: 0;
            width: 85%;
            height: 46px;
            padding-top: 12px;
            margin: 40px auto 0;
            border-radius: 8px;
        }
        .yanzheng{
            width: 50%;
            border: 0!important;
        }
        .btn{
            width: 120px;
            height: 30px;
            margin-top: 5px;
            color: deepskyblue;
            border: 0;
        }

    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <script type="text/javascript" src="<?php echo e(asset('js/common.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(asset('js/mui.min.js')); ?>"></script>
    <body>
    <div class="bind">
        <div class="mui-input-row">
            <input type="password" id="cardPassword_rsainput" class="mui-input-password font-14" placeholder="请输入10位卡密" maxlength="10" style="border: 0px">
        </div>
        <div class="mui-input-row">
            <input type="tel" id="mobile_num" class="mui-input-clear font-14" placeholder="请输入手机号码"  style="border: 0px">
        </div>
        <div class="mui-input-row" style="display: flex;justify-content: space-between;">
            <input type="number" id="vcode" class="yanzheng font-14" placeholder="请输入验证码" maxlength="6">
            <button class="btn font-12 color-80" id="btn">获取验证码</button>

        </div>
        <button type="button"
                data-loading-icon="mui-spinner mui-spinner-custom"
                data-loading-text="核验中"
                data-loading-icon-position="right"
                class="mui-btn mui-btn-blue mui-btn-block flex-center">
            立即绑定
        </button>
    </div>
    </body>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script type="text/javascript">
        var reg = /^(0|86|17951)?(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/;
        mui(document.body).on('tap', '.mui-btn', function(e) {
            mui(this).button('loading');
            //提交绑定前进行校验
            if(ck_password()){
                if ($('#mobile_num').val() == '' || !reg.test($('#mobile_num').val())) {
                    message('请输入正确的手机号！') ;
                    mui(this).button('reset');
                    return false;
                }else if($("#vcode").val() == ''){
                    message('请输入验证码！') ;
                    mui(this).button('reset');
                    return false;
                }

                //提交绑定
                var mobile_num = $('#mobile_num').val();
                var vcode = $("#vcode").val();

                $.post('<?php echo e(asset('marketing/dispatch/submit')); ?>',
                        {
                            "numberCode": $("#cardPassword_rsainput").val(),
                            "mobile": mobile_num,
                            "vcode": vcode
                        }, function (res) {
                            loadSucc();
                            if (res.code == 0) {
                                var url = '<?php echo e(asset('personal/index')); ?>';
                                switch (parseInt(res.data.type)) {
                                    case 3:
                                        url = '<?php echo e(asset('personal/wallet/giftCoupons/choseGiftCouponGoods')); ?>' + '/' + res.data.giftcoupon_id;
                                        break;
                                }
                                var obj = $('<div  class="success" style="width:150px;margin:0 auto"><h4>恭喜，绑定成功!</h4><h4>即将跳转...</h4></div>');
                                $('.bind').empty().append(obj);
                                setTimeout(turn_to(url), 5000);
                            } else  if(res.code == 50000){
                                url = '<?php echo e(asset('personal/userLoginView')); ?>';
                                var obj = $('<div  class="success"style="width:150px;margin:0 auto" ><h4>'+res.message+'</h4><h4>即将跳转登陆...</h4></div>');
                                $('.bind').empty().append(obj);
                                setTimeout(turn_to(url), 5000);
                            } else {
                                message(res.message);
                            }
                        });
            }else{
                mui(this).button('reset');
                return false;
            }
            setTimeout(function() {
                mui(this).button('reset');
            }.bind(this), 2000);
        });


        $(function () {
            $('#btn').click(function () {
                var count = 60;
                var countdown = setInterval(CountDown, 1000);
                function CountDown() {
                    $("#btn").attr("disabled", true);
                    $("#btn").text("重新获取" + count + " 秒");
                    if (count == 0) {
                        $("#btn").text("获取验证码").removeAttr("disabled");
                        clearInterval(countdown);
                    }
                    count--;
                }
            })
        });


        $(function () {
            function send_code(){
                var count = 120;
                var countdown = setInterval(CountDown, 1000);
                function CountDown() {
                    $("#btn").attr("disabled", true);
                    $("#btn").text("重新获取" + count + " 秒");
                    if (count == 0) {
                        $("#btn").text("获取验证码").removeAttr("disabled");
                        clearInterval(countdown);
                    }
                    count--;
                }

                $.post('/personal/getYZM',{login_phonenum:$('#mobile_num').val()},function(data){
                    // data.code int '0':成功, '1':错误，'2':参数不合规则
                    if (data.code) {
                        message(data.message);
                        return false;
                    } else {
                        return true;
                    }
                });
            }

            $("#btn").on('click',function(){

                if ($('#mobile_num').val() == '' || !reg.test($('#mobile_num').val())) {
                    message('请输入正确的手机号！') ;
                    return false;
                }
                send_code();
            });
        });
        function ck_password() {
            var pattern = /^\d{10}$/;
            if ( $("#cardPassword_rsainput").val() == '') {
                message('卡密不能为空');
                ckpassword = false;
            } else if (!pattern.test( $("#cardPassword_rsainput").val())) {
                message('请输入正确的10位卡密');
                ckpassword = false;
            } else {
                ckpassword = true;
            }
            return ckpassword;
        }

        function turn_to(url) {
            location.href = url;
        }
    </script>

<?php $__env->stopSection(); ?>




<?php echo $__env->make('inheritance', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>