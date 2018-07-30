/**
 * Created by Administrator on 2017/5/18.
 */

$(document).ready(function () {

    var payable_total = $('#payable_amount').text(); //商品总金额（含运费）

    var use_amount = 0; //记录用户使用几种支付(水丁币，零钱等)共使用的金额

    var sdb_rmb = 0; //记录实际使用的水丁币支付的人民币金额
    var sdb_amount = 0; //记录水丁币的使用总数
    var lingqian = 0; //记录实际使用的零钱金额
    var card_pay_total = 0; //保存选中的卡余额支付的总金额



    //当选中某个支付方式时，计算需要再支付多少钱
// chose_amount:点击选中的支付方式可以支付的最大金额
//
// 返回值 amount：点击选中的该支付方式需要支付的金额（若为该支付方式的最大金额，说明选中该支付方式恰好可以支付完，若小于，说明该支付方式金额用不完就可支付成功）
    function get_pay_amount(chose_amount){
        var heji = 0;
        var amount = 0;
        //若已选中的加上刚选中的金额之和大于商品总金额，说明足够支付
        if( accAdd_new(use_amount, chose_amount)  >= payable_total){
            amount = accSub(payable_total, use_amount);
            use_amount = payable_total;
            heji = 0;
            $('#zhifu-total').text(heji);
            return amount;
        }else{ //若二者之和小于商品金额，说明已选中的金额加上刚选中的最大金额也不够支付该商品

            use_amount = accAdd_new(use_amount, chose_amount);
            amount = chose_amount;
            heji = accSub(payable_total, use_amount);
            $('#zhifu-total').text(heji);
            return amount;
        }

    }

    //点击取消某个支付方式时 把该支付方式实际支付的金额从use_amount减掉，把调整合计金额
    //balance_pay_amount为取消的支付方式实际支付的金额
    function cancle_pay(balance_pay_amount){
        var zhifu_total = $('#zhifu-total').text();
        use_amount = accSub(use_amount, balance_pay_amount);
        var heji = accAdd_new(zhifu_total, balance_pay_amount);

        $('#zhifu-total').text(heji);
    }

    //使用水丁币选择按钮,水丁币支付时只能支付的金额必须为整数
    $('#shuibi').on('click',function(){
        if ($(this).hasClass('switch1')){ //进行使用虚拟币
            //判断合计是否为零，若为零，说明不在需要其他支付方式了。
            var zhifu = parseInt($('#zhifu-total').text() * 100);
            if(zhifu == 0){
                message('您选中的支付方式已经足够支付该商品！');
                return false;
            }

            //由于水丁币支付只能为整数，因此取出合计还需要支付的金额（舍去小数）
            var zf_int = Math.floor($('#zhifu-total').text());
            var zhifu_total = $('#zhifu-total').text();
            //最多可用的水丁币对应的人民币
            var di_rmb = parseInt($('#di_rmb').text());

            var heji = 0;

            if(zf_int == 0){ //为零说明合计里面是小于1元的
                message('支付金额必须为整数，该小于一元的金额无法支付！');
                return false;
            }else if( zf_int >= di_rmb ){ //说明合计里面的整数大于等于水丁币可以支付的最大rmb金额，因此全部支付
                sdb_rmb = di_rmb;
                heji = accSub(zhifu_total,di_rmb);
                $('#zhifu-total').text(heji);

            }else if(zf_int < di_rmb){ //说明合计里面的整数小于水丁币可以支付的最大rmb金额，因此水丁币只需支付合计里面的整数
                sdb_rmb = zf_int;
                heji = accSub(zhifu_total,zf_int);
                $('#zhifu-total').text(heji);
            }

            //调整use_amount
            use_amount = accAdd_new(use_amount,sdb_rmb);

            //得到水丁比的换算率,换算出支付的人民币对应的水丁币
            var plat_vrb_rate = $('#plat_vrb_rate').val();
            sdb_amount = Math.ceil(sdb_rmb * plat_vrb_rate);
            //本次使用的水丁币，以及对应的金额
            $('#this_used').text(sdb_amount);
            $('#di_rmb').text(sdb_rmb);
            $(this).attr('class','switch2');
            $("#y_use").show();
            $("#n_use").hide();
        }else{
            $(this).attr('class','switch1');

            //当取消水丁币支付时，调整合计和use_amount，sdb_rmb清空，
            var zhifu_total = $('#zhifu-total').text();
            var zf = accAdd_new(zhifu_total,sdb_rmb);
            $('#zhifu-total').text(zf);

            use_amount = accSub(use_amount,sdb_rmb);
            sdb_rmb = 0;

            $("#y_use").hide();
            $("#n_use").show();
        }

    });

    //使用零钱按钮
    $("#ling_switch").on('click',function(){
        if ($(this).hasClass('switch1')){ //进行使用虚拟币

            //最多可用的零钱数
            var max_ling = $('#max_ling').text();

            //判断合计是否为零，若为零，说明不在需要其他支付方式了。
            var zhifu = parseInt($('#zhifu-total').text() * 100);
            if(zhifu == 0){
                message('您选中的支付方式已经足够支付该商品！');
                return false;
            }


            //获得选中的支付方式实际需要支付的金额，并保存该支付金额
            lingqian = get_pay_amount(max_ling);
            $('#use_ling').text(lingqian);

            $(this).attr('class','switch2');
            $("#y_ling").show();
            $("#n_ling").hide();

        }else{ //取消零钱支付
            $(this).attr('class','switch1');

            //把该支付方式实际支付的金额从use_amount减掉，把调整合计金额
            cancle_pay(lingqian);
            lingqian = 0;


            $("#y_ling").hide();
            $("#n_ling").show();
        }

    });


    //使用卡余额，点击卡余额时，从下面弹出各个卡的列表供用户进行选择
    $('#ka-yu-e').on('click', function () {

        //判断可供用户使用的卡余额个数，若为零，表示用户没有卡可以用
        var card_num = $("#card_num").text();
        if(card_num == 0){
            message("您还没有卡余额可以使用！");
            return false;
        }
        $('#ka-yu-e-select').css('bottom', 0).css('transition', 'all 0.5s');
        $('.ka-yu-e-xiaoguo').addClass('yinying');
        $('#ka-yu-e-right-btn').css('transform', 'rotate(90deg)').css('transition', 'all 0.5s');
    });


    //点击关闭按钮
    $('#ka_yu_e_close').on('click', function () {
        var len = $(".to-chose-card[chose=1]").length;
        $("#used_card_num").text(len);

        $('#ka-yu-e-select').css('bottom', '-300px').css('transition', 'all 0.5s');
        $('.ka-yu-e-xiaoguo').removeClass('yinying');
        $('#ka-yu-e-right-btn').css('transform', 'rotate(0)').css('transition', 'all 0.5s');
    });

    //选择卡余额
    $(".to-chose-card").on('click',function(){

        var chose = $(this).attr("chose");
        var balance_available = $(this).children('input[name=balance_available]').val(); //该卡最大能够支付的金额
        var balance_pay_amount = $(this).children('input[name=balance_pay_amount]').val();

        if(chose == 0){
            //判断合计是否为零，若为零，说明不在需要其他支付方式了。
            var zhifu = parseInt($('#zhifu-total').text() * 100);
            if(zhifu == 0){
                message('您选中的支付方式已经足够支付该商品！');
                return false;
            }
            //获得选中的支付方式实际需要支付的金额，并保存该支付金额
            var need_amount = get_pay_amount(balance_available);
            $(this).children('input[name=balance_pay_amount]').val(need_amount);

            //把支付的金额加到记录卡余额支付的总金额变量card_pay_total中
            card_pay_total = accAdd_new(card_pay_total, need_amount);

            $(this).find('.select_img').attr('src','/sd_img/selected.png');
            $(this).attr("chose",1);
        }else{
            //把该支付方式实际支付的金额从use_amount减掉，把调整合计金额
            cancle_pay(balance_pay_amount);
            //把保存实际支付的调整为0
            $(this).children('input[name=balance_pay_amount]').val(0);

            //从记录卡余额支付的总金额变量card_pay_total中减去实际支付的
            card_pay_total = accSub(card_pay_total, balance_pay_amount);

            $(this).find('.select_img').attr('src','/sd_img/defau.png');
            $(this).attr("chose",0);
        }
    });

    //<%--============================= 点击“去支付” ===========================%>
    var clicktag = 0;
    $('.zf-btn').on('click', function () {

        $skus_arr = [];
        /* var hospital_info=$('#hospital_info').val();
         var member_name=$('#member_name').val();
         var mobile=$('#mobile').val();
         if(hospital_info==''){
         message('请输入生产医院！');
         return false;
         }else if(member_name==''){
         message('请输入联系人！');
         return false;
         }else if(mobile==''){
         message('请输入联系电话！');
         return false;
         }*/

        /*if($('#textarea').val()==''){
         message('请输入生产医院！');
         return false;
         }*/


        /*        if ($('.checkbox-visited').length == 1) {
         $('.skuId').each(function () {
         $skus_arr.push([
         $(this).val(), $(this).next().val(),
         $(this).next().next().val(),
         $(this).next().next().next().val(),
         $(this).next().next().next().next().val()
         ].join('-'));
         });
         } else {
         message('请选择有效的服务/收货地址！');
         return false;
         }*/
        $('.skuId').each(function () {
            $skus_arr.push([
                $(this).val(), $(this).next().val(),
                $(this).next().next().val(),
                $(this).next().next().next().val(),
                $(this).next().next().next().next().val()
            ].join('-'));
        });

        //20s内不能重复点击
        if (clicktag == 0) {
            clicktag = 1;
            var self = $(this);
            $(this).addClass("gray");
            setTimeout(function () {
                clicktag = 0;
                self.removeClass("gray");
            }, 20000);
        } else {
            message('请勿重复点击！');
            return false;
        }

        //商品来源【0:立即购买;1:购物车;7:电子充值卡;8:直接充值;9:礼品（礼券）;】
        $sku_source_type = $('.sku_source_type').val();
        //判断房间情况
        $.post('/jd/chekcSkus', {
            skus: $skus_arr.join(','),
            enterdate: $('#enterdate').val(),
            leavedate: $('#leavedate').val(),
            nights: $('#nights').val()
        }, function (res) {
            if (res.code == 0) //若code等于0说明程序没错，然后再判断data是否可以购买
            {
                if (res.data == 0) { //判断是否可以购买
                    message('房间' + $('#sku_' + res.message).val() + '已被售出，请选择其他房间，谢谢！', 5000);
                    return false;

                } else {
                    //获取卡余额支付的详情列表
                    var card_lst = new Array();

                    //对于团采用户和代理用户选择使用的支付方式id
                    //var pay_id = 0;
                    //if($("#chose_pay").length > 0){
                    //    pay_id = $("#chose_pay").attr('chose_pay_id');
                    //    if(pay_id == 0){
                    //        message('请选择其中一种支付方式',5000);
                    //        return false;
                    //    }
                    //
                    //}
                    //
                    ////若选择的是账期支付，得到其账期支付延迟支付时间
                    //var expire_time = 0;
                    //if(pay_id == 8){
                    //    expire_time = $('#expire_time').text();
                    //}
                    $(".to-chose-card[chose=1]").each(function () {
                        var arr = {
                            'rechargecard_id': $(this).children("input[name=rechargecard_id]").val(),
                            'card_id': $(this).children("input[name=card_id]").val(),
                            'balance_amount': $(this).children("input[name=balance_amount]").val(),
                            'balance_available': $(this).children("input[name=balance_available]").val(),
                            'balance_pay_amount': $(this).children("input[name=balance_pay_amount]").val(),
                        };

                        card_lst.push(arr);
                    });

                    var data = {
                        'skus': $skus_arr.join(','),
                        'message': $('#textarea').val(),
                        /*'hospital_info':hospital_info,
                         'member_name':member_name,
                         'mobile':mobile,*/
                        //'pay_vrb': $('#pay_vrb').val(),
                        //'buyMethod': $('.change-icon').next().val(),
                        'sku_source_type': $sku_source_type,
                        //水丁币支付金额
                        'pay_vrb_amount': sdb_amount,
                        //零钱支付金额
                        'pay_wallet_amount': lingqian,
                        //卡余额支付的总金额
                        'pay_card_balance_amount': card_pay_total,
                        //卡支付详情列表
                        'card_lst': card_lst,
                        'enterdate': $('#enterdate').val(),
                        'leavedate': $('#leavedate').val(),
                        'nights': $('#nights').val()
                        //'pay_id' : pay_id,
                    };

                    console.log(data);
                    $.post('/jd/order/add', data, function (res) {
                        if (res.code == 0) {
                            switch ($sku_source_type) {
                                case 9 || 10:
                                    var plat_order_id = res.data.plat_order_id;
                                    //window.location.href = '/ys/order/info/'+plat_order_id; 月嫂生产医院填入留言，原单独写的订单信息页面取消20170904
                                    window.location.href = '/jd/order/info/' + plat_order_id;
                                    break;
                                default :
                                    var plat_order_id = res.data.plat_order_id;

                                    //if(pay_id == 5 || pay_id == 8){
                                    //    window.location.href = '/wx/pay/wxPay?plat_order_id='+plat_order_id+'&flag='+pay_id;
                                    //}else if(pay_id == 7){
                                    //    //若是电子付款，则生成订单后就直接跳到订单详情，等到用户上传银行支付凭证，则后台进行修改订单已完成，进行派单，前端只负责生成订单即可
                                    //    window.location.href = '/order/info/'+plat_order_id;
                                    //}else{
                                    //    window.location.href = '/wx/pay/wxPay?plat_order_id='+plat_order_id;
                                    //}

                                    window.location.href = '/wx/pay/wxPay?plat_order_id=' + plat_order_id;
                                    break;
                            }
                        } else {
                            message(res.message, 5000);
                            return false;
                        }

                    });
                }

            }else{ //程序有错，显示错误直接退出

                if(res.code == 104){ //若为104，为手机没绑定，跳到绑定手机号页面进行绑定
                    message(res.message, 5000);
                    window.location.href = "/personal/userMobileBindView";
                    return false;
                }else if(res.code == 102){
                    message(res.message, 5000);
                    window.location.href = "/personal/userLoginView";
                    return false;
                }else {
                    message(res.message, 5000);
                    return false;
                }

            }
        });
    });

});

function accAdd_new(arg1,arg2){
    //进行相加的两个数同时乘以100，进行整数运算

    var arg1 = parseInt(parseFloat(arg1) * 100);
    var arg2 = parseInt(parseFloat(arg2) * 100);

    var sum = (arg1 +　arg2) / 100;
    return sum;
}


