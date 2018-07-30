<?php $__env->startSection('title'); ?>
    <?php echo e($scanData['activity_name']); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
    <link href="<?php echo e(asset('css/bootstrap.min.css')); ?>" rel="stylesheet" type="text/css"/>
    <link href="<?php echo e(asset('css/giftcard/gifts.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/giftcard/hexiao.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/after-sales.css')); ?>" rel="stylesheet">
    <style>
        .modal-backdrop.in {
            filter: alpha(opacity=80);
            opacity: .7
        }

        a:hover {
            text-decoration: none
        }

        p {
            margin: 0;
        }

        .fenge {
            border: none
        }

        .quantity-wrapper {
            width: 162px;
            border-radius: 5px;
            height: 24px;
            float: right;
        }

        .quantity-decrease {
            background-position: 10px -20px;
        }

        .quantity-decrease.disabled, .quantity-increase.disabled {
            background-color: #e8e8e8;
            color: #999;
        }

        .quantity-decrease.disabled em, .quantity-increase.disabled em {
            background-position: 10px -45px;
        }

        .quantity {
            -moz-border-bottom-colors: none;
            -moz-border-left-colors: none;
            -moz-border-right-colors: none;
            -moz-border-top-colors: none;
            border-color: rgb(200, 200, 200);
            border-image: none;
            border-radius: 0;
            border-style: solid;
            border-width: 1px 0;
            color: #232326;
            height: 26px;
            width: 40px;
        }

        .quantity-increase {
        }

        .quantity-decrease, .quantity-increase {
            background: #fff none repeat scroll 0 0;
            border: 1px solid rgb(200, 200, 200);
            color: #232326;
            display: block;
            line-height: 24px;
            overflow: hidden;
            text-indent: -200px;
            width: 24px;
        }

        .quantity-decrease, .quantity, .quantity-increase {
            float: left;
            font-size: 15px;
            text-align: center;
        }

        .quantity-increase em {
            background: rgba(0, 0, 0, 0) url("<?php echo e(asset('img/giftcard/cart-number.png')); ?>") no-repeat scroll 0 0 / 100% auto;
            display: block;
            height: 10px;
            margin: 7px;
            width: 10px;
        }

        .quantity-decrease em {
            background: rgba(0, 0, 0, 0) url("<?php echo e(asset('img/giftcard/cart-number.png')); ?>") no-repeat scroll 0 -18px / 100% auto;
            display: block;
            height: 10px;
            margin: 7px;
            width: 10px;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <body>
    <div style="margin-bottom:40px;">
        <img class="top_img" src="<?php echo e($scanData['activity_images']); ?>" width="100%">
        <div style="background-color: rgb(240,240,240);height: 10px;width: 100%;"></div>
        <input type="hidden" id="mid" value="<?php echo e($scanData['member_id']); ?>">
        <input type="hidden" id="giftcoupon_id" value="<?php echo e($scanData['giftcoupon_id']); ?>">
        <input type="hidden" id="card_id" value="<?php echo e($scanData['card_id']); ?>">
        <?php $__currentLoopData = $scanData['packages']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $package): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
            <div class="gift_card">
                <div class="gift_card_list">
                    <div class="left" event_status="false" pid="<?php echo e($package['package_id']); ?>">
                        <?php if($package['useable_num'] == 0): ?>
                            <img class="disabled" style="width:26px;height:26px;"
                                 src="<?php echo e(asset('img/address/address-default-disabled.png')); ?>">
                        <?php else: ?>
                            <img class="noselected" src="<?php echo e(asset('img/address/address-default-pre.png')); ?>">
                            <img class="selected" src="<?php echo e(asset('img/address/address-default-set.png')); ?>"
                                 style="display: none">
                        <?php endif; ?>
                    </div>
                    <div class="images">
                        <img src="<?php echo e($package['package_images']); ?>">
                    </div>
                    <div class="mid">
                        <p class="title"><?php echo e($package['package_name']); ?></p>
                        <p class="amount">数量：<?php echo e($package['goods_num']); ?>种</p>
                    </div>
                    <div class="right">
                        <?php if($k == 0): ?>
                            <img src="<?php echo e(asset('img/giftcard/gift_down.png')); ?>">
                            <img src="<?php echo e(asset('img/giftcard/gift_next.png')); ?>" style="display: none">
                        <?php else: ?>
                            <img src="<?php echo e(asset('img/giftcard/gift_down.png')); ?>" style="display: none">
                            <img src="<?php echo e(asset('img/giftcard/gift_next.png')); ?>">
                        <?php endif; ?>
                    </div>
                </div>
                <div class="gift_cart_detail" <?php if($k != 0): ?>style="display: none"<?php endif; ?>>
                    <?php $__currentLoopData = $package['goodsList']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k1 => $goods): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                        <div style="width:9%;display: block;float:left;height:20px;"></div>
                        <div class="gift_cart_detail_list" event_status="false" pid="<?php echo e($package['package_id']); ?>"
                             maxnum="<?php echo e($goods['useable_num']); ?>" price="<?php echo e($goods['price']); ?>"
                             sid="<?php echo e($goods['sku_id']); ?>" cgid="">
                            <div class="select">
                                <?php if($goods['useable_num'] == 0): ?>
                                    <img class="child_disabled" style="width:26px;height:26px;"
                                         src="<?php echo e(asset('img/address/address-default-disabled.png')); ?>">
                                <?php else: ?>
                                    <img class="noselected" src="<?php echo e(asset('img/address/address-default-pre.png')); ?>">
                                    <img class="selected" src="<?php echo e(asset('img/address/address-default-set.png')); ?>"
                                         style="display: none">
                                <?php endif; ?>
                            </div>
                            <div class="left1">
                                <div class="photo" sid="<?php echo e($goods['sku_id']); ?>">
                                    <img src="<?php echo e($goods['sku_image']); ?>">
                                </div>
                            </div>
                            <div class="right">
                                <div class="title" sid="<?php echo e($goods['sku_id']); ?>">
                                    <p><?php echo e($goods['sku_name']); ?></p>
                                </div>
                                <div class="amount">数量：
                                    <div class="quantity-wrapper" style="float:right;">
                                        <a href="javascript:void(0)" class="quantity-decrease quantityDecrease">
                                            <em id="minus">-</em>
                                        </a>
                                        <input type="text" class="quantity" value="<?php echo e($goods['useable_num'] <= 0 ? 0 : 1); ?>" size="4"/>
                                        <a href="javascript:void(0)" class="quantity-increase quantityPlus">
                                            <em id="plus">+</em>
                                        </a>
                                        <span style="padding:5px;line-height:27px;font-size:12px;color:rgb(80,80,80)">库存：<?php echo e($goods['useable_num']); ?>件</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                </div>
            </div>
            <div class="fenge"></div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
    </div>
    <form action="<?php echo e(asset('order/showPay')); ?>" method="post" id="giftForm">
        <input type="hidden" name="gift_sku_lst" id="gift_sku_lst">
        <input type="hidden" name="sku_source_type" id="sku_source_type" value="9">
    </form>
    <div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel1"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content"
                 style="background-color:inherit;border:none;box-shadow: none">
                <div
                        style="background-color:rgba(255,255,255,.85);width: 90%;height: 100%;border-radius:10px;margin:0 auto;margin-top:30%;">
                    <div class="access-title" style="height:100px;padding:25px 0;">
                        <p style="font-size:15px;color:#3d3d3d;font-weight: 700;">为保证您的账户安全</p>
                        <p style="font-size:15px;color:#3d3d3d;font-weight: 700;margin-top:5px;">
                            请输入手机号码进行验证</p>
                    </div>
                    <div class="access-content">
                        <input nctype="txt" type="tel" class="mobile" id="mobile"
                               style="border:1px solid #13b5b1;height:40px;border-radius:2px;font-size:12px;margin-bottom: 25px;"
                               placeholder="请输入11位手机号码"
                               maxlength="11">
                        <div class="key" style="margin-bottom: 30px;">
                            <input nctype="txt" type="number" id="vcode"
                                   style="border-radius:2px;height:40px;font-size:12px;"
                                   placeholder="请输入手机验证码" maxlength="6">
                            <button id="get_key" data-mesCode="true"
                                    style="border-radius:2px;height:40px;font-size:12px;"
                                    class="mesg-abled mesg-disable"
                                    report-eventid="MLoginRegister_ReceiveMsgCheck"
                                    data-eventid="MLoginRegister_ReReceiveMsgCheck">
                                获取验证码
                            </button>
                        </div>
                        <div class="submit" id="regBtn" report-eventid="MLoginRegister_Finish"
                             style="border-radius:2px;height:40px;font-size:14px;margin-bottom:80px;">
                            立即认证
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel2"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content"
                 style="background-color:inherit;border:none;box-shadow: none">
                <img style="height:38px;width:38px;margin-top:60%;margin-left:48%"
                     src="<?php echo e(asset('img/loading.gif')); ?>">
            </div>
        </div>
    </div>
    <div class="footer">
        <div class="btn">立即领取</div>
    </div>
    <div id="pop-loading" style="z-index: 1001;display: none; position: fixed; top: 40%;left: 42.5%;">
        <img style="height:38px;width:38px;margin-top:60%;margin-left:48%" src="<?php echo e(asset('img/loading.gif')); ?>">
    </div>
    <div id="pop-confirm" class="pop pop-ctm-01" style="display: none; position: fixed; top: 30%;left: 15%;">
        <div class="pop-msg">
            <div id="confirm-content" class="pop-msg-cnt" style="padding-top:30px;min-height: 50px">
            </div>
            <div class="pop-msg-btm">
                <a id="confirm-close-btn" class="btn-h4 btn-c3"
                   style="width: 96px;border: 1px solid #cfcfcf;color: #737373;background: #fff"
                   href="javascript:void(0);">关闭</a>
                <a id="confirm-ok-btn" class="btn-h4 btn-c3"
                   style="width: 96px;"
                   href="javascript:void(0);">确定</a>
            </div>
        </div>
    </div>
    <div class="__MASK_SID_DIV" style="display:none;width:100%;position: absolute; z-index: 999; left: 0px; top: 0px;">
        <div id="set_height"
             style="width:100%; height: 1024px; opacity: 0.7; background: black none repeat scroll 0% 0%; position: static; margin: 0px;"></div>
    </div>
    </body>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script type="text/javascript" src="<?php echo e(asset('js/bootstrap.min.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(asset('js/common.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(asset('js/gifts_card.js')); ?>"></script>
    <script type="text/javascript">
        var arr = new Array();
        arr['ok'] = "false";
        arr['false'] = "ok";
        $(function () {
            if ('<?php echo e($scanData['member']['mobile_bind']); ?>' == '0') {
                $('#exampleModal1').modal({
                    show: true,
                    backdrop: 'static',
                    keyboard: false
                });
            }
            $(".tishi").on("click", function () {
                $(this).parents(".modal").modal('hide');
            });

            $('.btn').on('click', function () {
                $(this).css("color", "#fff");
                var pid = $("div[event_status='ok']").attr("pid");
                if (typeof (pid) == "undefined") {
                    message("请选择您心仪的礼包！");
                    return;
                }
                showConfirmDialog('确认领取当前选中的商品吗？选择后礼品套餐将不可变更，套餐内剩余礼品将保存至我的礼包', function () {
                    hideConfirmDialog();
                    loading();
                    var data_append = "";
                    var proArr = new Array();
                    var package_id = '';
                    $("div.gift_cart_detail_list[event_status='ok']").each(function (k) {
                        package_id = $(this).attr("pid");
                        proArr[k] = $(this).attr("sid");
                    });
                    $.post('<?php echo e(asset('personal/wallet/giftCoupons/saveCouponGoods')); ?>', {
                        "giftcoupon_id": $("#giftcoupon_id").val(),
                        "card_id": $("#card_id").val(),
                        "package_id": package_id,
                        "skus": proArr.join(',')
                    }, function (res) {
                        loadSucc();
                        if (res.code == 0) {
                            var ids = res.data.ids;
                            var idsarr = ids.split(',');
                            $("div.gift_cart_detail_list[event_status='ok']").each(function (k) {
                                var sid = $(this).attr("sid");
                                var price = $(this).attr("price");
                                var quantity = $(this).find('.quantity').val();
                                for (var i = 0; i < idsarr.length; i++) {
                                    var idarr = idsarr[i].split('-');
                                    if (sid == idarr[0]) {
                                        var id = idarr[1];
                                    }
                                }
                                data_append += "<input type='hidden' name='gift_sku_lst[" + k + "][sku_id]' value='" + sid + "' />";
                                data_append += "<input type='hidden' name='gift_sku_lst[" + k + "][number]' value='" + quantity + "' />";
                                data_append += "<input type='hidden' name='gift_sku_lst[" + k + "][price]' value='" + price + "' />";
                                data_append += "<input type='hidden' name='gift_sku_lst[" + k + "][promotions_type]' value='99' />";
                                data_append += "<input type='hidden' name='gift_sku_lst[" + k + "][promotions_id]' value='" + id + "' />";
                            });
                            //跳转至确认订单页
                            $('#giftForm').append(data_append).submit();
                        } else {
                            message(res.message);
                        }
                    });
                });
            });
            $('.left').on('click', function () {
                var obj = $('.left');
                if (obj.find('img.disabled').length <= 0) {
                    var event_status = $(this).attr("event_status");
                    $(this).attr("event_status", arr[event_status]);
                    $(this).parents(".gift_card").find(".gift_cart_detail_list").attr('event_status', arr[event_status]);
                    if ($(this).attr("event_status") == "ok") {
                        $(this).find('.noselected').hide();
                        $(this).find('.selected').show();
                        $(this).parents(".gift_card").find(".gift_cart_detail_list").find('.noselected').hide();
                        $(this).parents(".gift_card").find(".gift_cart_detail_list").find('.selected').show();
                        obj.not(this).find('.noselected').show();
                        obj.not(this).find('.selected').hide();
                        obj.not(this).attr("event_status", "false");
                        $(".select").not($(this).parents(".gift_card").find(".select")).find('.noselected').show();
                        $(".select").not($(this).parents(".gift_card").find(".select")).find('.selected').hide();
                        $(".gift_cart_detail_list").not($(this).parents(".gift_card").find(".gift_cart_detail_list")).attr("event_status", "false");
                    } else {
                        $(this).find('.selected').hide();
                        $(this).find('.noselected').show();
                        $(this).parents(".gift_card").find(".gift_cart_detail_list").find('.noselected').show();
                        $(this).parents(".gift_card").find(".gift_cart_detail_list").find('.selected').hide();
                    }
                }
                var pid = $("div.left[event_status='ok']").attr("pid");
                if (typeof (pid) == "undefined") {
                    $(".btn").css({"background-color": "rgb(220,220,220)", color: "#848689"});
                } else {
                    $(".btn").css({"background-color": "#f23030", color: "white"});
                }
            });
            $('.select').on('click', function () {
                var obj = $(this).parents(".gift_cart_detail_list");
                if (obj.find('img.child_disabled').length <= 0) {
                    obj.attr('event_status', arr[obj.attr("event_status")]);
                    if (obj.attr("event_status") == "ok") {
                        $(this).find('.noselected').hide();
                        $(this).find('.selected').show();
                        $(this).parents(".gift_card").find(".gift_card_list").find(".left").find('.noselected').hide();
                        $(this).parents(".gift_card").find(".gift_card_list").find(".left").find('.selected').show();
                        $(this).parents(".gift_card").find(".gift_card_list").find(".left").attr('event_status', "ok");
                        var left = obj.parents(".gift_card").find(".gift_card_list").find(".left");
                        $(".left").not(left).find('.noselected').show();
                        $(".left").not(left).find('.selected').hide();
                        $(".left").not(left).attr("event_status", "false");
                        $(".select").not(obj.parents(".gift_cart_detail").find(".select")).find('.noselected').show();
                        $(".select").not(obj.parents(".gift_cart_detail").find(".select")).find('.selected').hide();
                        $(".gift_cart_detail_list").not(obj.parents(".gift_cart_detail").find(".gift_cart_detail_list")).attr("event_status", "false");
                    } else {
                        $(this).find('.selected').hide();
                        $(this).find('.noselected').show();
                        if (obj.parents(".gift_cart_detail").find(".gift_cart_detail_list[event_status='ok']").length <= 0) {
                            $(this).parents(".gift_card").find(".gift_card_list").find(".left").attr('event_status', "false");
                            $(this).parents(".gift_card").find(".gift_card_list").find(".left").find('.noselected').show();
                            $(this).parents(".gift_card").find(".gift_card_list").find(".left").find('.selected').hide();
                        }
                    }
                }
                var pid = $("div.left[event_status='ok']").attr("pid");
                if (typeof (pid) == "undefined") {
                    $(".btn").css({"background-color": "rgb(220,220,220)", color: "#848689"});
                } else {
                    $(".btn").css({"background-color": "#f23030", color: "white"});
                }
            });
            $('.right,.mid').on('click', function () {
                $(this).find('img').toggle();
                $(this).parent("div.gift_card_list").next('.gift_cart_detail').slideToggle(300);
            });
            //跳转至商品详情页
            $(".photo").on("click", function () {
                //location.href = WapSiteUrl + '/wx_exchange_list_detail.php?if=false&exchange_id=' + $(this).attr("sid");
            });
        });
        $(function () {
            $("input[nctype='txt']").on({
                blur: function () {
                    $(this).css('border', "0px")
                },
                focus: function () {
                    $(this).css('border', "1px solid #13b5b1");
                }
            });
            $('#mobile').select();
            $('#mobile').focus();
            $('#mobile').on('keyup', function () {
                if ($(this).val().match(reg) != null) {
                    $('button[data-eventid="MLoginRegister_ReReceiveMsgCheck"]').removeClass('mesg-disable').removeAttr('disabled').on('click', send_auth_code);
                } else {
                    $('button[data-eventid="MLoginRegister_ReReceiveMsgCheck"]').addClass('mesg-disable').attr('disabled', 'true');
                }
            });
            $('input[nctype=txt]').on('keyup', function () {
                var res = true;
                $('input[nctype=txt]').each(function () {
                    if ($(this).val() == null || ($(this).val() == '')) {
                        res = false;
                    }
                });
                if (res) {
                    $('div[report-eventid="MLoginRegister_Finish"]').addClass('btn-active');
                } else {
                    $('div[report-eventid="MLoginRegister_Finish"]').removeClass('btn-active');
                }
            });
            $('div[report-eventid="MLoginRegister_Finish"]').on("click", function () {
                if ($(this).hasClass('btn-active')) {
                    verify();
                }
            });
        });
        function check_phone(num) {
            var phreg = /^(((13[0-9]{1})|(15[0-9]{1})|(16[0-9]{1})|(17[0-9]{1})|(18[0-9]{1}))+\d{8})$/;
            if (!phreg.test(num) || num == "") {
                return false;
            }
            return true;
        }
    </script>
<?php $__env->stopSection(); ?>




<?php echo $__env->make('inheritance', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>