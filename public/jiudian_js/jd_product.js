/**
 * Created by liding on 16-9-2.
 */

//确认送礼后继续之前的送礼逻辑——20170912
function showGiftChoose(){
    hideNotice();
    $('.form-gui-ge').css('bottom', 0);
    $('.join-btn').css('display', 'none');
    $('.buy-btn').css('display', 'none');
    $('.xiaoguo').addClass('yinying');
}

//隐藏送礼提示——20170912
function hideNotice(){
    $('.lanrenzhijia').hide(0);
    $('.content_mark').hide(0);
}

$(document).ready(function () {

    $('.xiaoguo').click(function (e) {
        e.stopPropagation();
       $('.close-choose').click();
    });
    removeYinYing('.xiaoguo','.close-choose');

    //<%--============================= 点击加入购物车和立即购买出现规格选择模块 ===========================%>
    $('#add_cart').click(function () {
        //加入购物车前先判断是否是虚拟商品，虚拟商品不能加入购物车只能进行直接购买，若加入购物车，虚拟商品和实物商品无法同时支付
        var is_virtual = parseInt($('#is_virtual').val());
        if(is_virtual){
            message('虚拟商品不能加入购物车，只能立即购买！');
            return false;
        }
        $('.form-gui-ge').css('bottom', 0);
        $('.buy-btn').css('display', 'none');
        $('.share-gift').css('display', 'none');
        $('.xiaoguo').addClass('yinying');

    });
    $('#directorder').click(function () {
        /*$('.form-gui-ge').css('bottom', 0);
        $('.join-btn').css('display', 'none');
        $('.share-gift').css('display', 'none');
        $('.xiaoguo').addClass('yinying');*/

        $arr = [];
        $("input[name='sku2buy']").each(function (index, item) {
            //index = $(this).next().val();
            if(item.checked){
                var v = item.value + 'CONNECTOR' + item.getAttribute("text");
                $arr.push(v);
            }
        });

        if($arr.length==0){
            message('请选择房间！');
            return false;
        }else{
            $('#spuId').val($('#sid').val());
            $('#number').val($('.text_box').val());
            $('#guiges').val($arr.join('SEPARATOR'));
            $('#src').val($('.img-border').attr('src'));
            $('#nights').val($('#night').attr("value"));
            $('#enterdate').val($('#enterdatet').val());
            $('#leavedate').val($('#leavedatet').val());
            $('#buyForm').submit();
        }


    });

    //点击送礼
    $('#gift').click(function () {

        //显示送礼提示--start-20170912
        $('.lanrenzhijia').show(0);
        $('.content_mark').show(0);
        //显示送礼提示--end
    });

    //<%--============================= 点击关闭，还原 ===========================%>
    $('.close-choose').click(function (e) {

        $('.form-gui-ge').css('bottom', '-600px');
        $('.xiaoguo').removeClass('yinying');

        setTimeout(function () {
            $('.buy-btn').css('display', 'block');
            $('.join-btn').css('display', 'block');
            $('.share-gift').css('display', 'block');

        },300);

    });

    //<%--============================= 减数量 ===========================%>
    $(".min").click(function () {
        //虚拟商品只能购买一件
        var is_virtual = parseInt($('#is_virtual').val());
        if(is_virtual){
            message('虚拟商品只能购买一件！');
            return false;
        }else{
            var t = $(this).parent().find('input[class*=text_box]');
            var min_limit =parseInt( $('#buy_num').attr('min_limit'));
            //每个商品都有一个购买下限，默认为1，所以当减时，不能小于购买下限
            if(parseInt(t.val()) <= min_limit  ){
                if(min_limit > 1){
                    message('该规格的商品最低购买数量为 ' + min_limit);
                }
                t.val(min_limit);
            }else{
                t.val(parseInt(t.val()) - 1);
            }

        }

    });

    //<%--============================= 加数量 ===========================%>
    $(".add").click(function () {
        //虚拟商品只能购买一件
        var is_virtual = parseInt($('#is_virtual').val());
        if(is_virtual){
            message('虚拟商品只能购买一件！');
            return false;
        }else{
            var t = $(this).parent().find('input[class*=text_box]');
            t.val(parseInt(t.val()) + 1);
        }

    });

    //<%--============================= 规格文字选中样式 ===========================%>

    var spuLength = $('.spuLength').val();//后台返回规格数组的长度
    $('.choose-a').bind('click', function (e) {
        e.stopPropagation();
        if ($(this).attr('class') == 'choose-a') {
            $(this).addClass('a-style');
            $(this).parent().siblings().find('.choose-a').removeClass('a-style');
        } else {
            $(this).removeClass('a-style');
        }
        //console.log($('.a-style').length);  //获取选中的个数

        //var sku_id = $(this).next().val();
        var spu_id = $('#sid').val();
        $arr = [];
        $('.a-style').each(function (index, value) {
            index = $(this).next().val();
            $str = index;
            $arr.push($str);
        });
        //console.log($arr);
        var data = {
            "stoken" : stoken,
            "spu_id" : spu_id,
            "ids" : $arr,
            "enterdate":$('#enterdate').val(),
            "leavedate":$('#leavedate').val(),
        };
        $.post('/jd/getSku',data,function (data) {
            $('.sku_id').each(function () {
                var sku_this =$(this);
                sku_this.prev().css({'color':'#333','pointer-events':'auto'});
                $.each(data['ids'], function (index, item) {
                    if(sku_this.val() == item){
                        sku_this.prev().css({'color':'#c0c0c0','pointer-events':'none'});
                    }
                })
            });
            if(data['type'] == 1){
                $('#choose-name').text(data['sku']['sku_name']);
                $('#choose-price').text(data['sku']['price']);
                $('#choose-img').attr('src',data['sku']['img']);
                //若有购买下限，这显示的为购买下限，默认为1
                $('#buy_num').val(data['sku']['minimum_limit']);
                $('#buy_num').attr('min_limit',data['sku']['minimum_limit']);
                if(data['sku']['sku_points']>=0){
                    $('#usable_points').text(data['sku']['sku_points']);
                    $('#p_id').show();
                }else{
                    $('#p_id').hide();
                }
                if(!data['sku']['is_can_buy']){
                    $('.join-btn').unbind('click').find('.join-now').addClass('disabled').removeClass('red-color');
                    $('.buy-btn').unbind('click').find('.buy-now').addClass('disabled').removeClass('red-color');
                    $('.share-gift').unbind('click').find('.share-now').addClass('disabled').removeClass('yellow-color');

                }else{
                    $('.join-btn').bind('click', joinClick).find('.join-now').removeClass('disabled').addClass('red-color');
                    $('.buy-btn').bind('click', buyClick).find('.buy-now').removeClass('disabled').addClass('red-color');
                    $('.share-gift').bind('click', buyClick).find('.share-now').removeClass('disabled').addClass('yellow-color');

                }
            }
        });

    });

    //<%--============================= 规格图片选中样式 ===========================%>
    $('.dingzhiyangshi').bind('click', function (e) {
        e.stopPropagation();
        if ($(this).attr('class') == 'dingzhiyangshi') {
            $(this).addClass('img-border');
            $(this).parent().siblings('div').find('img').removeClass('img-border');
        } else {
            $(this).removeClass('img-border');
        }
    });


    //购买数量没有限制，必须大于0
    var reg = /^[0-9]*[1-9][0-9]*$/; //校验正整数
    $(".text_box").blur(function () {
        var value = $(this).val();
        var min_limit = parseInt($(this).attr('min_limit'));

        if(!reg.test(value)){ //若输入的不是正整数，这默认为最低购买下限
            $(".text_box").val(min_limit);
        }else{
            //若输入的整数小于购买下限，则值为最低购买下限
            if(parseInt(value) < min_limit ){
                if(min_limit > 1){
                    message('该规格的商品最低购买数量为 ' + min_limit);
                }
                $(".text_box").val(min_limit);
            }
        }
    });

    //<%--============================= 提交数据 ===========================%>
    $('.join-btn').click(function (e) {

        if (($('.a-style').length + $('.img-border').length) != $('.guigeming').length) {
            message('请检查规格是否选择完整！');
            return false;
        } else {
            joinClick();//20170803-解决正式环境添加购物车数量变成2倍的问题，测试环境和正式环境注释后均可用，本地调制不可以，暂时注释掉。
        }
        e.stopPropagation();
    });

    function joinClick(){
        $('.close-choose').click();
        $arr = [];
        $('.a-style').each(function (index, value) {
            index = $(this).next().val();
            $str = index + 'CONNECTOR' + $(value).text();
            $arr.push($str);
        });
        $data = {
            'spu_id': $('#sid').val(),
            'shuliang': $('.text_box').val(),
            'spec': $arr.join('SEPARATOR'),
            'dingzhi': $('.img-border').attr('src')
        };


        $.post('/cart/add', $data, function (data) {
            if (data['code'] == 0) {
                message('商品已成功加入购物车！');
                return true;
            }else if(data['code'] == 10010){
                message("您还没有登录，请进行登录！");
                location.href = "/personal/userLoginView";
                return false;
            } else {
                message('系统繁忙，请稍后重试！');
                return false;
            }

        });
    }
    //<%--============================= 立即购买 ===========================%>
    $('.buy-btn').on('click', function goodsBuy() {

       /* $('.form-gui-ge').css('bottom', '-600px');
        $('.xiaoguo').removeClass('yinying');*/
        $arr = [];
        $("input[name='sku2buy']").each(function (index, item) {
            //index = $(this).next().val();
            if(item.checked){
                var v = item.value + 'CONNECTOR' + item.getAttribute("text");
                $arr.push(v);
            }
        });

        if($arr.length==0){
            message('请选择房间！');
            return false;
        }else{
            $('#spuId').val($('#sid').val());
            $('#number').val($('.text_box').val());
            $('#guiges').val($arr.join('SEPARATOR'));
            $('#src').val($('.img-border').attr('src'));
            debugger;
            $('#nights').val($('#night').attr("value"));
            $('#enterdate').val($('#enterdatet').val());
            $('#leavedate').val($('#leavedatet').val());
            $('#buyForm').submit();
        }
    });

/*    function buyClick(){

    }*/
    //商品收藏
    $("#focusOn").click(function () {

        //若没有收藏，则收藏
        if($("#attentionFocus").hasClass('focus-out')){
            var spu_id = $('#sid').val();

            $.post('/personal/collect/add',{'subject_id':spu_id,'subject_type':2},function (data) {
                if (data['code'] == 0) {
                  $("#attentionFocus").removeClass("focus-out").addClass("focus-on");
                  return true;
                }else if(data['code'] == 102){
                    message("您还没有登录，请进行登录！");
                    location.href = "/personal/userLoginView";
                    return false;
                } else {
                    message('收藏失败，请稍后重试！');
                    return false;
                }
            });
            return true;
        }else{
            //取消收藏的该商品
            var spu_id = $('#sid').val();
            $.post('/personal/collect/cancel',{'subject_id':spu_id,'subject_type':2},function (data) {
                if (data['code'] == 0) {
                    $("#attentionFocus").removeClass("focus-on").addClass("focus-out");
                    return true;
                }else if(data['code'] == 102){
                    message("您还没有登录，请进行登录！");
                    location.href = "/personal/userLoginView";
                    return false;
                } else {
                    message('取消收藏失败，请稍后重试！');
                    return false;
                }
            });

            return true;
        }

    });

   $("#tuwen").on('click',function(){
       $('#tuwen').submit();
   });

    //<%--============================= 送礼 ===========================%>
    $('.share-gift').on('click', function () {
        if (($('.a-style').length + $('.img-border').length) != $('.guigeming').length) {
            message('请检查规格是否选择完整！');
            return false;
        } else {
            /* $('.close-choose').click();*/
            giftbuyClick();
        }
    });

    function giftbuyClick(){
        $('.form-gui-ge').css('bottom', '-600px');
        $('.xiaoguo').removeClass('yinying');
        $arr = [];

        $('.a-style').each(function (index, value) {
            index = $(this).next().val();
            var v = index + 'CONNECTOR' + $(value).text();
            $arr.push(v);
        });


        $("#spu_id_gifts").val($('#sid').val());
        $("#spec").val($arr.join('SEPARATOR'));
        $("#gift_num").val($('.text_box').val());

        $("#send-gift").submit();

    }



});