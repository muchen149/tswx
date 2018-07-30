/**
 * Created by liding on 16-9-7.
 * 1.新用户，没有添加过收货地址的，进去支付页面显示“新增收货地址”；
 * 2.点击新增收货地址，下面弹出一层“新增收货地址”的框；
 * 3.若有地址，则一开始显示默认的地址；
 * 4.点击默认的地址，可切换到地址详情列表，点击详细的地址进行切换；
 * 5.点击任何一个地址，显示被选中样式，替换默认地址栏，更新数据库；
 * 6.点击新增按钮，弹出新增模块，进行数据添加操作，
 *
 */
$(document).ready(function () {

    var payable_total = $('#payable_amount').text(); //商品总金额（含运费）

    var use_amount = 0; //记录用户使用几种支付(水丁币，零钱等)共使用的金额

    var sdb_rmb = 0; //记录实际使用的水丁币支付的人民币金额
    var sdb_amount = 0; //记录水丁币的使用总数
    var lingqian = 0; //记录实际使用的零钱金额
    var card_pay_total = 0; //保存选中的卡余额支付的总金额

    var slide_one;
    var slide_two;
    var slide_three;
    var mySwiper1 = new Swiper('.swiper-container1', {
        direction: 'vertical',
        slidesPerView: 'auto',
        centeredSlides: true,
        observer: true,         //修改swiper自己或子元素时，自动初始化swiper
        observeParents: true,   //修改swiper的父元素时，自动初始化swiper

        onSlideChangeEnd: function (swiper) {
            // $('.swiper-wrapper2').css('transform', 'translate3d(0px, 105px, 0px)');
            var one_id = $('.swiper-slide-active').find('.one-id').val();
            //console.log(one_id);
            slide_one = one_id;
            $('.swiper-container2').find('.swiper-wrapper').html('<div class="swiper-slide">' +
                '<span>-请选择-</span>' +
                ' </div>');
            $.get('/personal/address/child/' + one_id, function (data) {
                // console.log(data);

                var str = '<div class="swiper-slide">' +
                    '<span>-请选择-</span>' +
                    ' </div>';

                $.each(data['data'], function (index, item) {
                    str += '<div class="swiper-slide">' +
                        ' <span class="two-address">' + item['name'] + '</span>' +
                        '<input type="hidden" class="two-id" value="' + item['id'] + '"/>' +
                        ' <input type="hidden" class="two-pid" value="' + item['pid'] + '"/>' +
                        '</div>';

                });

                $('.swiper-container2').find('.swiper-wrapper').html(str);
                $('.swiper-container3').find('.swiper-wrapper')
                    .html('<div class="swiper-slide">' +
                        '<span>-请选择-</span>' +
                        ' </div>');

            })
        }
    });
    var mySwiper2 = new Swiper('.swiper-container2', {

        direction: 'vertical',
        slidesPerView: 'auto',
        centeredSlides: true,
        observer: true,            //修改swiper自己或子元素时，自动初始化swiper
        observeParents: true,      //修改swiper的父元素时，自动初始化swiper

        onSlideChangeEnd: function (swiper) {
            $('.swiper-container3').find('.swiper-wrapper').html('<div class="swiper-slide">' +
                '<span>-请选择-</span>' +
                ' </div>');
            var two_id = $('.swiper-slide-active').find('.two-id').val();
            //console.log(two_id);
            slide_two = two_id;
            $.get('/personal/address/child/' + two_id, function (data) {

                var str = '<div class="swiper-slide default">' +
                    '<span>-请选择-</span>' +
                    ' </div>';

                $.each(data['data'], function (index, item) {
                    str += '<div class="swiper-slide">' +
                        ' <span class="three-address">' + item['name'] + '</span>' +
                        '<input type="hidden" class="three-id" value="' + item['id'] + '"/>' +
                        ' <input type="hidden" class="three-pid" value="' + item['pid'] + '"/>' +
                        '</div>';

                });
                $('.swiper-container3').find('.swiper-wrapper').html(str);
            })

        }
    });
    var mySwiper3 = new Swiper('.swiper-container3', {

        direction: 'vertical',
        slidesPerView: 'auto',
        centeredSlides: true,
        observer: true,         //修改swiper自己或子元素时，自动初始化swiper
        observeParents: true,   //修改swiper的父元素时，自动初始化swiper

        onSlideChangeEnd: function (swiper) {
            var three_id = $('.swiper-slide-active').find('.three-id').val();
            //console.log(three_id);
            slide_three = three_id;
        }
    });

    //<%--============================= 如果没有地址，点击从下面弹出新增地址框 ===========================%>
    $('.add-address-btn').on('click', function () {
        $('.xinzeng').css('bottom', 0).css('transition', 'all 0.5s');
        $('.xiaoguo').addClass('yinying');  //阴影层效果
        $('#choose-address').text('');
        $('body').on('touchmove', function (ev) {
            ev.preventDefault();
        });
    });

    //<%--============================= 点击关闭新增地址框 ===========================%>
    $('.close-set-btn').on('click', function () {
        $('.xinzeng').css('bottom', '-350px').css('transition', 'all 0.5s');
        if ($('.xq-lb').css('bottom') == '-300px') {
            $('.xiaoguo').removeClass('yinying');
        }
        $('body').unbind("touchmove");
    });

    //<%--============================= 点击切换地址，弹出地址详情列表 ===========================%>
    $('.default-address-xq').on('click', function () {
        $('.xq-lb').css('bottom', 0).css('transition', 'all 0.5s');
        $('.xiaoguo').addClass('yinying');
        $('.right-btn').css('transform', 'rotate(90deg)').css('transition', 'all 0.5s');
    });

    //<%--============================= 点击关闭地址详情列表，更新默认地址显示，提交默认地址到后台进行数据库更新 ===========================%>
    $('.close-choose-btn').on('click', function () {
        $('.xq-lb').css('bottom', '-300px').css('transition', 'all 0.5s');
        $('.xiaoguo').removeClass('yinying');
        $('.right-btn').css('transform', 'rotate(0)').css('transition', 'all 0.5s');

    });

    //<%--============================= 点击任何一个地址，显示被选中样式，替换默认地址栏，更新数据库，关闭详情地址栏 ===========================%>
    $('.addressContent').delegate('.xuanzhong', 'click', function () {
        $(this).find('.checkbox-style').addClass('checkbox-visited');
        $(this).parent().siblings().find('.checkbox-style').removeClass('checkbox-visited');
        $(this).find('.radio-op').prop('checked', true);
        $(this).find('.default').val(1);
        $(this).parent().siblings().find('.default').val(0);

        $('.xq-lb').css('bottom', '-300px').css('transition', 'all 0.5s');
        $('.xiaoguo').removeClass('yinying');
        $('.right-btn').css('transform', 'rotate(0)').css('transition', 'all 0.5s');

        var shr = $("input[name='radio']:checked").parent().parent().next().find('.shr').text();
        var sj = $("input[name='radio']:checked").parent().parent().next().find('.sj').text();
        var sjdz = $("input[name='radio']:checked").parent().parent().next().find('.p-c').text();
        var dz = $("input[name='radio']:checked").parent().parent().next().find('.xx').text();

        var id = $("input[name='radio']:checked").next().val();

        $('.m-shr').text(shr);
        $('.m-sj').text(sj);
        $('.m-sjdz').text(sjdz);
        $('.m-dz').text(dz);
        //$('.m-sjdz').text('');

        $.get('/personal/address/setDefault/' + id, function (data) {
            return data;
        })
    });

    //<%--============================= 点击地址详情列表选中的效果 ===========================%>
    $('.checkbox-style').on('click', function () {
        if ($(this).attr('class') == 'checkbox-style') {
            $(this).addClass('checkbox-visited');
            $(this).parent().parent().parent().parent().siblings().find('.checkbox-style').removeClass('checkbox-visited');
        }
    });

    //<%--============================= 新增地址功能 ===========================%>
    var who_is_default = $('.default_is_who').val(); //用来判断数据库中一开始有没有地址数据,0为无,1为有
    $('.baocun').on('click', function () {
        var name = $('.name').val();
        var tel = $('.phone').val();
        var address_info = $('.address-info').val();


        $data = {
            'name': name,
            'mobile': tel,
            'address': address_info,
            'province_id': $('#province').val(),
            'city_id': $('#city').val(),
            'area_id': $('#area').val()
        };

        if (name == '' || name == null) {
            message('收货人姓名不能为空！');
        } else if (tel == '' || tel == null || !/^(13[0-9]|14[0-9]|15[0-9]|18[0-9])\d{8}$/i.test(tel)) {
            message('请输入有效的手机号！');
        } else if (address_info == '' || address_info == null) {
            message('详细地址不能为空！');
        } else {
            $.post('/personal/address/save', $data, function (data) {

                if (!data['code']) {
                    // 未报错
                    $('.close-set-btn').click();
                    $('.name').val('');
                    $('.phone').val('');
                    $('.address-info').val('');

                    message('保存成功！');

                    if (who_is_default == 0) {
                        $('.add-address').css('display', 'none');
                        $('.default-address-hidden').css('display', 'block');
                        $('.xiaoguo').removeClass('yinying');
                    }

                    $('.m-shr').text(name);
                    $('.m-sj').text(tel);
                    $('.m-dz').text(address_info);
                    $('.m-sjdz').text($('#choose-address').text());

                    var addContent =
                        '<div class="col-xs-12 xuanzekuang">'
                        + '<div class="col-xs-10 xuanzhong">'
                        + '<div class="col-xs-1 radio">'
                        + '<label>'
                        + '<input type="radio" name="radio" checked="checked"  value="" autocomplete="off" class="radio-op">'
                        + '<input type="hidden" value="' + data['data']['address_id'] + '">'
                        + '<input type="hidden" value="' + data['data']['is_default'] + '" class="default">'
                        + '<p class="checkbox-style checkbox-visited"></p>'
                        + '</label>'
                        + '</div>'
                        + '<div class="col-xs-11 xiangqinglan">'
                        + '<span class="shr">' + data['data']['recipient_name'] + '</span>&nbsp;<span class="sj">' + data['data']['mobile'] + '</span>'
                        + '<p class="fzq f12"><span class="dz">收货地址：<span class="p-c">' + data['data']['area_info'] + '</span>'
                        + '<input type="hidden" value="' + data['data']['province_id'] + '" class="detail-province"/>'
                        + '<input type="hidden" value="' + data['data']['city_id'] + '" class="detail-city"/>'
                        + '<input type="hidden" value="' + data['data']['area_id'] + '" class="detail-area"/>'
                        + '<span class="xx">' + data['data']['address'] + '</span></span></p>'
                        + '</div>'
                        + '</div>'
                        + '<div class="col-xs-2 edit-btn text-center" >'
                        + '<img src="../img/edit.svg" height="24" width="24"/>'
                        + '</div>'
                        + '</div>';

                    var addressContent = $('.addressContent');
                    addressContent.prepend(addContent);
                    addressContent.children().first().siblings().find('.checkbox-style').removeClass('checkbox-visited');
                    addressContent.children().first().next().find('.default').val(0);
                } else {
                    message('请选择有效的地址信息！');
                }
            });
        }

        /* }*/
    });

    //<%--============================= 编辑地址功能 ===========================%>
    $('.close-edit-btn').on('click', function () {
        $('.edit').css('bottom', '-300px').css('transition', 'all 0.5');
        $('body').unbind("touchmove");
    });
    var ycid;
    var ycbtn;

    //<%--============================= 编辑按钮 ===========================%>
    $('.addressContent').delegate('.edit-btn', 'click', function () {

        $('.edit').css('bottom', 0).css('transition', 'all 0.5s');
        $('body').on('touchmove', function (ev) {
            ev.preventDefault();
        });

        var shr = $(this).prev().find('.shr').text();   //姓名
        var sj = $(this).prev().find('.sj').text(); //手机号
        var pc = $(this).prev().find('.p-c').text(); // 三级地址
        var xx = $(this).prev().find('.xx').text();    //详情地址

        $('.edit-shr').val(shr);
        $('.edit-sj').val(sj);
        $('.edit-pc').text(pc);
        $('.edit-xx').val(xx);


        ycid = $(this).prev().find('.radio-op').next().val();
        ycbtn = $(this);
        slide_one = $('.detail-province').val();
        slide_two = $('.detail-city').val();
        slide_three = $('.detail-area').val();
    });

    //<%--============================= 编辑中保存按钮 ===========================%>
    $('.edit-save').on('click', function () {
        var edit_shr = $('.edit-shr').val();
        var edit_sj = $('.edit-sj').val();

        var edit_xx = $('.edit-xx').val();

        var edit_province = slide_one;
        var edit_city = slide_two;
        var edit_area = slide_three;
        $data = {
            'id': ycid,
            'name': edit_shr,
            'mobile': edit_sj,
            'province_id': edit_province,
            'city_id': edit_city,
            'area_id': edit_area,
            'address': edit_xx
        };
        //console.log($data);
        if (edit_shr == '' || edit_shr == null) {
            message('收货人姓名不能为空！');
        } else if (edit_sj == '' || edit_sj == null || !/^(13[0-9]|14[0-9]|15[0-9]|18[0-9])\d{8}$/i.test(edit_sj)) {
            message('请输入有效的手机号！');
        } else if (edit_xx == '' || edit_xx == null) {
            message('详细地址不能为空！');
        } else {
            $.post('/personal/address/save', $data, function ($data) {
                var def = ycbtn.prev().find('.default').val();
                if ($data['code'] == 0) {
                    $('.close-edit-btn').click();
                    message('保存成功！');
                    if (def == 1) { //如果修改的是默认的
                        ycbtn.prev().find('.shr').text($data['data']['recipient_name']);
                        ycbtn.prev().find('.sj').text($data['data']['mobile']);
                        ycbtn.prev().find('.p-c').text($data['data']['area_info']);
                        ycbtn.prev().find('.xx').text($data['data']['address']);
                        $('.m-shr').text($data['data']['recipient_name']);
                        $('.m-sj').text($data['data']['mobile']);
                        $('.m-sjdz').text($data['data']['area_info']);
                        $('.m-dz').text(edit_xx);
                    } else {    //如果修改的不是默认的
                        ycbtn.prev().find('.shr').text($data['data']['recipient_name']);
                        ycbtn.prev().find('.sj').text($data['data']['mobile']);
                        ycbtn.prev().find('.p-c').text($data['data']['area_info']);
                        ycbtn.prev().find('.xx').text($data['data']['address']);
                    }
                } else {
                    message('系统繁忙，请稍后再试！');
                }
            });
        }
    });

    //<%--============================= 编辑中删除按钮 ===========================%>
    $('.edit-del').on('click', function () {
        var def = ycbtn.prev().find('.default').val();
        if (def == 1) {
            message('您正在删除默认地址，请更换后再删除！');
        } else {
            $.get('/personal/address/delete/' + ycid, function (data) {
                if (data['code'] == 0) {
                    ycbtn.parent().remove();
                    $('.close-edit-btn').click();
                    message('删除成功！');
                } else {
                    message('系统繁忙，请稍后重试！');
                }
            });
        }
    });

    //<%--============================= 选择支付方式效果 ===========================%>
    $('.choose-label').on('click', function () {
        $(this).find('.xuan').addClass('change-icon');
        $(this).parent().parent().siblings().find('.xuan').removeClass('change-icon');
    });

    //<%--============================= 点击“去支付” ===========================%>
    var clicktag = 0;
    $('.zf-btn').on('click', function () {

        $skus_arr = [];
        if ($('.checkbox-visited').length == 1) {
            $('.skuId').each(function () {
                $skus_arr.push([
                    $(this).val(), $(this).next().val(),
                    $(this).next().next().val(),
                    $(this).next().next().next().val(),
                    $(this).next().next().next().next().val()
                ].join('-'));
            });
        } else {
            message('请选择有效的收货地址！');
            return false;
        }

        if($("#chose_pay").length > 0){
            var id = $("#chose_pay").attr('chose_pay_id');
            if(id == 0){
                message('请选择其中一种支付方式',5000);
                return false;
            }
        }

      //20s内不能重复点击
        if (clicktag == 0) {
            clicktag = 1;
            var self = $(this);
            $(this).addClass("gray");
            setTimeout(function () {
                clicktag = 0;
                self.removeClass("gray");
            }, 20000);
        }else{
            message('请勿重复点击！');
            return false;
        }

        //商品来源【0:立即购买;1:购物车;7:电子充值卡;8:直接充值;9:礼品（礼券）;】
        $sku_source_type = $('.sku_source_type').val();
        $.post('/order/canBuy', {skus: $skus_arr,'sku_source_type':$sku_source_type}, function (res) {

            if(res.code == 0) //若code等于0说明程序没错，然后再判断data是否可以购买
            {

                if(res.data == 0){ //判断是否可以购买
                    message(res.message, 5000);
                    return false;

                } else {
                    //去下单

                    //获取卡余额支付的详情列表
                    var card_lst= new Array();

                    //对于团采用户和代理用户选择使用的支付方式id
                    var pay_id = 0;
                    if($("#chose_pay").length > 0){
                        pay_id = $("#chose_pay").attr('chose_pay_id');
                        if(pay_id == 0){
                            message('请选择其中一种支付方式',5000);
                            return false;
                        }

                    }

                    //若选择的是账期支付，得到其账期支付延迟支付时间
                    var expire_time = 0;
                    if(pay_id == 8){
                        expire_time = $('#expire_time').text();
                    }
                    $(".to-chose-card[chose=1]").each(function(){
                        var arr = {
                            'rechargecard_id' : $(this).children("input[name=rechargecard_id]").val(),
                            'card_id' : $(this).children("input[name=card_id]").val(),
                            'balance_amount' : $(this).children("input[name=balance_amount]").val(),
                            'balance_available' : $(this).children("input[name=balance_available]").val(),
                            'balance_pay_amount' : $(this).children("input[name=balance_pay_amount]").val(),
                        };

                        card_lst.push(arr);
                    });

                    var data = {
                        'skus': $skus_arr.join(','),
                        'message': $('textarea').val(),
                        //'pay_vrb': $('#pay_vrb').val(),
                        //'buyMethod': $('.change-icon').next().val(),
                        'sku_source_type' : $sku_source_type,
                        //水丁币支付金额
                        'pay_vrb_amount' : sdb_amount,
                        //零钱支付金额
                        'pay_wallet_amount' : lingqian,
                        //卡余额支付的总金额
                        'pay_card_balance_amount' : card_pay_total,
                        //卡支付详情列表
                        'card_lst': card_lst,
                        'pay_id' : pay_id,
                        'expire_time' : expire_time
                    };

                    //console.log(data);
                    $.post('/order/add',data, function (res) {
                        if(res.code == 0){
                            switch($sku_source_type){
                                case 9 || 10:
                                    var plat_order_id = res.data.plat_order_id;
                                    window.location.href = '/order/info/'+plat_order_id;
                                    break;
                                default :
                                    var plat_order_id = res.data.plat_order_id;

                                    if(pay_id == 5 || pay_id == 8){
                                        window.location.href = '/wx/pay/wxPay?plat_order_id='+plat_order_id+'&flag='+pay_id;
                                    }else if(pay_id == 7){
                                        //若是电子付款，则生成订单后就直接跳到订单详情，等到用户上传银行支付凭证，则后台进行修改订单已完成，进行派单，前端只负责生成订单即可
                                        window.location.href = '/order/info/'+plat_order_id;
                                    }else{
                                        window.location.href = '/wx/pay/wxPay?plat_order_id='+plat_order_id;
                                    }
                                    break;
                            }
                        }else{
                            message(res.message,5000);
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

    //<%=============================== 虚拟币支付，验证 ===========================%>

    $('#pay_vrb').on('blur', function () {
        $this = $(this);

        //输入的虚拟币必须是整数，且是最多可用虚拟币和我的虚拟币余额中的最小值
        // 订单虚拟币限额、我的虚拟币余额
        var _goods_points_totals = $("#goods_points_totals").text();
        var _my_vrb_available = $("#my_vrb_available").text();

        // 买家最大可支付的虚拟币数额
        var _max_pay_vrb = Math.min(_goods_points_totals,_my_vrb_available);

        // 当前买家拟支付的虚拟币额数额
        var use_num = $this.val();
        var reg = /^\d+$/;

        // 输入的必须为整数，且不能大于最小值
        if(reg.test(use_num) && use_num <= _max_pay_vrb){
            $.post('/order/payNum', {
                'pay_vrb': $this.val(),
                'payable_amount': $('#payable_amount').text()
            }, function (res) {
                $('#zhifu-total').text(res.data.new_pay_rmb);
                $this.val(res.data.new_pay_vrb);
            });
        }else{
            //当用户输入支付的虚拟币数额不符合规则时，默认为最大可支付的虚拟币数额
            $.post('/order/payNum', {
                'pay_vrb': _max_pay_vrb,
                'payable_amount': $('#payable_amount').text()
            }, function (res) {
                $('#zhifu-total').text(res.data.new_pay_rmb);
                $this.val(_max_pay_vrb);
            });

            return false;
        }
    });

    $('#choose-address').on('click', function () {
        $('.choose-address').css('bottom', 0);
        $('body').css({'overflow-x': 'hidden', 'overflow-y': 'hidden'});
        $('html').css({'overflow-x': 'hidden', 'overflow-y': 'hidden'});

    });

    $('.address-sure-btn').on('click', function () {

        var active = $('.swiper-slide-active');
        var one_id = active.find('.one-id').val();
        var two_id = active.find('.two-id').val();
        var three_id = active.find('.three-id').val();
        var one_address = active.find('.one-address').text();
        var two_address = active.find('.two-address').text();
        var three_address = active.find('.three-address').text();
        var address = one_address + two_address + three_address;

        $('#choose-address').text(address);
        $('.edit-pc').text(address);

        if (one_id == undefined || two_id == undefined || three_id == undefined) {
            message('请选择有效的收货地址！');
        } else {
            $('body').css('overflow', 'hidden');
            $('#province').val(one_id);
            $('#city').val(two_id);
            $('#area').val(three_id);
            $('.choose-address').css('bottom', '-330px');
        }
    });

    $('.edit').delegate('.edit-pc', 'click', function () {
        $('.choose-address').css('bottom', 0);
        $('body').css({'overflow-x': 'hidden', 'overflow-y': 'hidden'});
        $('html').css({'overflow-x': 'hidden', 'overflow-y': 'hidden'});
    });
    $('.xiaoguo').click(function () {
        if ($('.choose-address').css('bottom') == '0px') {
            message('请选择有效的收货地址！');
        } else {
            $('.close-btn').click();
        }

    });

    //使用水丁币选择按钮,水丁币支付时只能支付的金额必须为整数
    $('#switch').on('click',function(){
        var use = $(this).attr('use');
        //为零说明没有选择使用水丁币，因此进行使用选择使用水丁币
        if(use == 0){

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

            $(this).attr('src','/img/switch-on.png');
            $(this).attr('use',1);
            $("#y_use").show();
            $("#n_use").hide();
        }else{

            //当取消水丁币支付时，调整合计和use_amount，sdb_rmb清空，
            var zhifu_total = $('#zhifu-total').text();
            var zf = accAdd_new(zhifu_total,sdb_rmb);
            $('#zhifu-total').text(zf);

            use_amount = accSub(use_amount,sdb_rmb);
            sdb_rmb = 0;

            $(this).attr('src','/img/switch-off.png');
            $(this).attr('use',0);
            $("#y_use").hide();
            $("#n_use").show();
        }

    });

   //使用零钱按钮
   $("#ling_switch").on('click',function(){
       var tag = $(this).attr('tag');
       if(tag == 0){
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

           $(this).attr('src','/img/switch-on.png');
           $(this).attr('tag',1);
           $("#y_ling").show();
           $("#n_ling").hide();

       }else{ //取消零钱支付

           //把该支付方式实际支付的金额从use_amount减掉，把调整合计金额
           cancle_pay(lingqian);
           lingqian = 0;

           $(this).attr('src','/img/switch-off.png');
           $(this).attr('tag',0);
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

            $(this).find('.select_img').attr('src','/img/selected.png');
            $(this).attr("chose",1);
        }else{
            //把该支付方式实际支付的金额从use_amount减掉，把调整合计金额
            cancle_pay(balance_pay_amount);
            //把保存实际支付的调整为0
            $(this).children('input[name=balance_pay_amount]').val(0);

            //从记录卡余额支付的总金额变量card_pay_total中减去实际支付的
            card_pay_total = accSub(card_pay_total, balance_pay_amount);

            $(this).find('.select_img').attr('src','/img/defau.png');
            $(this).attr("chose",0);
        }
    });

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


    //accAdd有问题，当有小数计算时出现10.000000000000002错误
    function accAdd_new(arg1,arg2){
        //进行相加的两个数同时乘以100，进行整数运算

        var arg1 = parseInt(parseFloat(arg1) * 100);
        var arg2 = parseInt(parseFloat(arg2) * 100);

        var sum = (arg1 +　arg2) / 100;
        return sum;
    }


    //$('#chose_pay').on('click',fu nction(){
    //    //$(this).toggleClass('box-shadow-class');
    //    $(this).find('img.img-more').css('transition', 'all 0.5s').toggleClass('img-spin');
    //    $(this).parent().children('.pay-style').slideToggle(400);
    //
    //});

    $('.pay_ul').on('click',function(){
        var pay_id = $(this).attr('id');
        var is_chose = $(this).attr('is_chose');
        var pay_name = $(this).children('.p_name').text();

        if(is_chose == 0){
            //当点击某个未选中的支付方式，则把其他选中的支付方式修改为未选中的
           $(this).parent().children('ul[is_chose=1]').each(function(){
               var id = $(this).attr('id');
               $(this).attr('is_chose',0);
               $(this).find('img').attr('src','/img/defau.png');
               //把存放选中支付方式id的div属性改为0，表示没有选择其中一个支付方式
               $('#chose_pay').attr('chose_pay_id',0);
               //$('#pay_name').text('请选择支付方式');

               //若选中的是微信支付则把零钱支付方式隐藏
               if(id == 1 && $('#wallet_pay').length > 0){
                   //隐藏零钱前先判断是否使用了零钱，使用了零钱，则把零钱扣掉，计算订单合计
                   var tag = $('#ling_switch').attr('tag');
                   //tag为1说明使用了零钱，这重新计算合计
                   if(tag == 1){
                       //取消零钱支付
                       //把该支付方式实际支付的金额从use_amount减掉，把调整合计金额
                       cancle_pay(lingqian);
                       lingqian = 0;

                       $('#ling_switch').attr('src','/img/switch-off.png');
                       $('#ling_switch').attr('tag',0);
                       $("#y_ling").hide();
                       $("#n_ling").show();
                   }

                   $('#wallet_pay').hide();
               }


               //若选中的是微信支付，判断该用户是否有没有卡余额，若有，判断其是否使用过，若使用过，取消使用的卡余额,同时把卡余额支付方式隐藏
               if(id == 1 && $('#ka-yu-e').length > 0 ){

                   if( $(".to-chose-card[chose=1]").length > 0 ){
                       //把选中的卡取消
                       $(".to-chose-card[chose=1]").each(function(){

                           var balance_pay_amount = $(this).children('input[name=balance_pay_amount]').val();
                           //把该支付方式实际支付的金额从use_amount减掉，把调整合计金额
                           cancle_pay(balance_pay_amount);
                           //把保存实际支付的调整为0
                           $(this).children('input[name=balance_pay_amount]').val(0);

                           //从记录卡余额支付的总金额变量card_pay_total中减去实际支付的
                           card_pay_total = accSub(card_pay_total, balance_pay_amount);

                           $(this).find('.select_img').attr('src','/img/defau.png');
                           $(this).attr("chose",0);

                       });

                       //把显示'本次使用了X张'改为0张
                       $("#used_card_num").text(0);

                       //隐藏卡余额支付方式
                       $('#ka-yu-e').hide();
                   }else{ //说明有卡余额可以选择，但没有选择，直接隐藏卡余额支付方式

                       //隐藏卡余额支付方式
                       $('#ka-yu-e').hide();
                   }

               }

               //若选中的是账期支付，则把显示的账期支付的天数隐藏掉
               if(id == 8){
                  $("#zq_time").hide();
               }

           });

          //把未选中的改为选中
          $(this).attr('is_chose',1);
          $(this).find('img').attr('src','/img/selected.png');
          //把该支付方式的id放到存放id的div属性中
            $('#chose_pay').attr('chose_pay_id',pay_id);
            //$('#pay_name').text(pay_name);

            //若选中的是微信支付，若该用户有零钱支付框则把零钱支付框显示出来
            if(pay_id == 1 && $('#wallet_pay').length > 0){
                $('#wallet_pay').show();
            }
            //若选中的是微信支付，若该用户有卡余额支付，则把卡余额支付显示出来
            if(pay_id == 1 && $('#ka-yu-e').length > 0){
                $('#ka-yu-e').show();
            }

            //若选中的是账期支付，则把显示账期支付的天数
            if(pay_id == 8){
                $("#zq_time").show();
            }

        }else{
            //处于选中的改为未选中
            $(this).attr('is_chose',0);
            $(this).find('img').attr('src','/img/defau.png');
            //把存放选中支付方式id的div属性改为0，表示没有选择其中一个支付方式
            $('#chose_pay').attr('chose_pay_id',0);
            //$('#pay_name').text('请选择支付方式');

            //若取消的是微信支付，则把零钱支付隐藏 wallet_pay
            if(pay_id == 1 && $('#wallet_pay').length > 0){
                //隐藏零钱钱先判断是否使用了零钱，使用了零钱，则把零钱扣掉，计算订单合计
                var tag = $('#ling_switch').attr('tag');
                //tag为1说明使用了零钱，这重新计算合计
                if(tag == 1){
                    //取消零钱支付
                    //把该支付方式实际支付的金额从use_amount减掉，把调整合计金额
                    cancle_pay(lingqian);
                    lingqian = 0;

                    $('#ling_switch').attr('src','/img/switch-off.png');
                    $('#ling_switch').attr('tag',0);
                    $("#y_ling").hide();
                    $("#n_ling").show();
                }
                $('#wallet_pay').hide();
            }

            //若取消的是微信支付，则把卡余额支付隐藏，若卡余额中有选中的，则同时进行清空
            if(pay_id == 1 && $('#ka-yu-e').length > 0  ){

                if( $(".to-chose-card[chose=1]").length > 0 ){
                    //隐藏卡余额前先判断是否使用了某些卡，使用了卡则取消，计算订单合计
                    //把选中的卡取消
                    $(".to-chose-card[chose=1]").each(function(){

                        var balance_pay_amount = $(this).children('input[name=balance_pay_amount]').val();
                        //把该支付方式实际支付的金额从use_amount减掉，把调整合计金额
                        cancle_pay(balance_pay_amount);
                        //把保存实际支付的调整为0
                        $(this).children('input[name=balance_pay_amount]').val(0);

                        //从记录卡余额支付的总金额变量card_pay_total中减去实际支付的
                        card_pay_total = accSub(card_pay_total, balance_pay_amount);

                        $(this).find('.select_img').attr('src','/img/defau.png');
                        $(this).attr("chose",0);

                    });

                    //把显示'本次使用了X张'改为0张
                    $("#used_card_num").text(0);
                    //隐藏卡余额支付方式
                    $('#ka-yu-e').hide();

                }else{ //说明有卡余额可以选择，但没有选择，直接隐藏卡余额支付方式

                    //隐藏卡余额支付方式
                    $('#ka-yu-e').hide();
                }


            }

            //若选中的是账期支付，则把显示的账期支付的天数隐藏掉
            if(pay_id == 8){
                $("#zq_time").hide();
            }


        }


    });

 });
