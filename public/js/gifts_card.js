var InterValObj; //timer变量，控制时间
var count = 60; //间隔函数，1秒执行
var curCount;//当前剩余秒数
var reg = /^(0|86|17951)?(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/;
$(function () {
    //数量减
    $(".quantityDecrease").click(minusBuyNum);
    //数量加
    $(".quantityPlus").click(addBuyNum);
    $(".quantity").blur(buyNumer);
});

//购买数量减
function minusBuyNum() {
    var self = this;
    editQuantity(self, "minus");
}
//购买数量加
function addBuyNum() {
    var self = this;
    editQuantity(self, "add");
}
//购买数量增或减，请求获取新的价格
function editQuantity(self, type) {
    var sPrents = $(self).parents(".gift_cart_detail_list");
    var numInput = sPrents.find(".quantity");

    var buynum = parseInt(numInput.val());
    var quantity = 1;
    if (type == "add") {
        quantity = parseInt(buynum + 1);
        if (quantity >= sPrents.attr("maxnum")) {
            quantity = sPrents.attr("maxnum");
        }
    } else {
        if (buynum > 1) {
            quantity = parseInt(buynum - 1);
        }
        if(sPrents.attr("maxnum") == 0){
            quantity = 0;
        }
    }
    //点击加或者减时，若该商品没选中，这选中该商品
    if (sPrents.find('img.child_disabled').length <= 0) {
        if (sPrents.attr("event_status") == "false") {
            sPrents.attr("event_status", "ok");
            sPrents.find(".selected").show();
            sPrents.find(".noselected").hide();
            sPrents.parents(".gift_card").find(".gift_card_list").find(".left").attr("event_status", "ok");
            sPrents.parents(".gift_card").find(".gift_card_list").find(".left").find(".selected").show();
            sPrents.parents(".gift_card").find(".gift_card_list").find(".left").find(".noselected").hide();
        }
        var left = sPrents.parents(".gift_card").find(".gift_card_list").find(".left");
        $(".left").not(left).find('.noselected').show();
        $(".left").not(left).find('.selected').hide();
        $(".left").not(left).attr("event_status", "false");
        $(".select").not(sPrents.parents(".gift_cart_detail").find(".select")).find('.noselected').show();
        $(".select").not(sPrents.parents(".gift_cart_detail").find(".select")).find('.selected').hide();
        $(".gift_cart_detail_list").not(sPrents.parents(".gift_cart_detail").find(".gift_cart_detail_list")).attr("event_status", "false");
    }
    numInput.val(quantity);
    var pid = $("div.left[event_status='ok']").attr("pid");
    if (typeof (pid) == "undefined") {
        $(".btn").css({"background-color": "rgb(220,220,220)", color: "#848689"});
    } else {
        $(".btn").css({"background-color": "#f23030", color: "white"});
    }
}
function verify() {
    var sub = true;
    var memberid = $('#mid').val();
    var mobile = $('#mobile').val();
    var vcode = $('#vcode').val();
    if (mobile == '') {
        message("手机号不能为空");
        $('#mobile').focus();
        sub = false;
        return false;
    } else if (!reg.test(mobile)) {
        message("请输入正确的11位手机号");
        $('#mobile').focus();
        sub = false;
        return false;
    }
    if (vcode == '') {
        message("验证码不能为空");
        $('#vcode').focus();
        sub = false;
        return false;
    }
    if (sub) {
        $.ajax({
            type: 'post',
            url: '/personal/userMobileBindUpdate',
            data: {
                mobile: mobile, member_id: memberid, vcode: vcode
            },
            dataType: 'json',
            beforeSend: function () {
                $('#exampleModal2').modal({
                    show: true,
                    backdrop: false,
                    keyboard: false
                });
            },
            success: function (result) {
                $('#exampleModal2').modal('hide');
                if (result.code == 0) {
                    $('#exampleModal1').modal('hide');
                    return true;
                } else {
                    message(result.message);
                    return false;
                }
            }
        });
    }
}
function send_auth_code() {
    var obj = $('button[data-eventid="MLoginRegister_ReReceiveMsgCheck"]');
    curCount = count;
    //设置button效果，开始计时
    obj.unbind("click").html("重新发送(" + curCount + ")").addClass('mesg-disable');
    InterValObj = window.setInterval(SetRemainTime, 1000); //启动计时器，1秒执行一次
    if ($('#mobile').val() == '') return false;
    $.post('/personal/getYZM', {login_phonenum: $('#mobile').val()}, function (data) {
        if (data.code != 0) {
            $('.error-tips').html(data.message).show();
        }
    });
}

//timer处理函数
function SetRemainTime() {
    var obj = $('button[data-eventid="MLoginRegister_ReReceiveMsgCheck"]');
    if (curCount == 0) {
        window.clearInterval(InterValObj);//停止计时器
        obj.on('click', send_auth_code).removeAttr('disabled').html("获取验证码").removeClass('mesg-disable');
    }
    else {
        curCount--;
        obj.html("重新发送(" + curCount + ")");
    }
}

function buyNumer() {
    var sPrents = $(this).parents(".gift_cart_detail_list");
    var maxnum = sPrents.attr("maxnum");
    var numRex = /^\d+$/g;
    if (!numRex.test($(this).val()) || $(this).val() >= maxnum) {
        $(this).val(maxnum);
    }
    //点击加或者减时，若该商品没选中，这选中该商品
    if (sPrents.find('img.child_disabled').length <= 0) {
        if (sPrents.attr("event_status") == "false") {
            sPrents.attr("event_status", "ok");
            sPrents.find(".selected").show();
            sPrents.find(".noselected").hide();
            sPrents.parents(".gift_card").find(".gift_card_list").find(".left").attr("event_status", "ok");
            sPrents.parents(".gift_card").find(".gift_card_list").find(".left").find(".selected").show();
            sPrents.parents(".gift_card").find(".gift_card_list").find(".left").find(".noselected").hide();
        }
        var left = sPrents.parents(".gift_card").find(".gift_card_list").find(".left");
        $(".left").not(left).find('.noselected').show();
        $(".left").not(left).find('.selected').hide();
        $(".left").not(left).attr("event_status", "false");
        $(".select").not(sPrents.parents(".gift_cart_detail").find(".select")).find('.noselected').show();
        $(".select").not(sPrents.parents(".gift_cart_detail").find(".select")).find('.selected').hide();
        $(".gift_cart_detail_list").not(sPrents.parents(".gift_cart_detail").find(".gift_cart_detail_list")).attr("event_status", "false");
    }
    var pid = $("div.left[event_status='ok']").attr("pid");
    if (typeof (pid) == "undefined") {
        $(".btn").css({"background-color": "rgb(220,220,220)", color: "#848689"});
    } else {
        $(".btn").css({"background-color": "#f23030", color: "white"});
    }
}
