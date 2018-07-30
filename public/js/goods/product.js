/**
 * Created by liding on 16-9-2.
 */


$(document).ready(function () {
    ///*****************************
    // 图片轮播
    function picSwipe(){
        var elem = $("#mySwipe")[0];
        window.mySwipe = Swipe(elem, {
            continuous: true,
            // disableScroll: true,
            stopPropagation: true,
            callback: function(index, element) {
                $("#slide-nub").html(index+1);
            }
        });
    }

    picSwipe();
 //****************************

    var mySwiper = new Swiper('.swiper-container', {
        pagination: '.swiper-pagination',
        paginationType: 'fraction',
        /* effect: 'flip'*/
    });

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
        $('.xiaoguo').addClass('yinying');

    });
    $('#directorder').click(function () {
        $('.form-gui-ge').css('bottom', 0);
        $('.join-btn').css('display', 'none');
        $('.xiaoguo').addClass('yinying');
    });

    //<%--============================= 点击关闭，还原 ===========================%>
    $('.close-choose').click(function (e) {

        $('.form-gui-ge').css('bottom', '-600px');
        $('.xiaoguo').removeClass('yinying');

        setTimeout(function () {
            $('.buy-btn').css('display', 'block');
            $('.join-btn').css('display', 'block');
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
            "ids" : $arr
        };
        $.post('/shop/getSku',data,function (data) {
            $('.sku_id').each(function () {
                var sku_this =$(this);
                sku_this.prev().css({'color':'#333','pointer-events':'auto'});
                //alert(JSON.stringify(data));
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
                if(data['sku']['sku_points']){
                    $('#usable_points').text(data['sku']['sku_points']);
                    $('#p_id').show();
                }else{
                    $('#p_id').hide();
                }
                if(!data['sku']['is_can_buy']){
                    $('.join-btn').unbind('click').find('.join-now').addClass('disabled').removeClass('yellow-color');
                    $('.buy-btn').unbind('click').find('.buy-now').addClass('disabled').removeClass('red-color');
                }else{
                    $('.join-btn').bind('click', joinClick).find('.join-now').removeClass('disabled').addClass('yellow-color');
                    $('.buy-btn').bind('click', buyClick).find('.buy-now').removeClass('disabled').addClass('red-color');
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
        } else {
            joinClick();
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
                var shop_cart = $('#toCart');
                shop_cart.find('.num').css('opacity',0);
                //var cart_right = shop_cart.width()/2 - 12;
                //alert(cart_right);
                $(".goods_num").hide();
                shop_cart.find('img').addClass('rotate-css-z');
                shop_cart.find('img').attr('src','../../../img/m-cart-red.svg');
                setTimeout(function () {
                    shop_cart.find('img').attr('src','../../../img/m-cart.svg');
                    shop_cart.find('img').addClass('rotate-css-f');
                },150);
                setTimeout(function () {
                    shop_cart.find('img').attr('src','../../../img/m-cart-red.svg');
                    shop_cart.find('img').addClass('rotate-css');
                },450);
                setTimeout(function () {
                    shop_cart.find('img').attr('src','../../../img/m-cart.svg');
                    shop_cart.find('img').removeClass('rotate-css-z rotate-css-f rotate-css');
                },600);
                setTimeout(function () {
                    shop_cart.find('img').attr('src','../../../img/m-cart-red.svg');
                    shop_cart.find('.cart-font').css('color','#ff4e00');
                },700);
                setTimeout(function () {
                    shop_cart.find('img').attr('src','../../../img/m-cart.svg');
                    shop_cart.find('.cart-font').css('color','#515151');
                },800);
                setTimeout(function () {
                    shop_cart.find('img').attr('src','../../../img/m-cart-red.svg');
                    shop_cart.find('.cart-font').css('color','#ff4e00');
                },900);
                setTimeout(function () {
                    //shop_cart.find('img').attr('src','../../../img/cart_img1.png');
                    shop_cart.find('img').attr('src','../../../img/m-cart.svg');
                    shop_cart.find('.cart-font').css('color','#515151');
                    //shop_cart.find('.num').css({'opacity':1,'right': '23px'});
                },1000);

                //显示购物车中商品的总数
                $(".goods_num").text(data['data']);
                $(".goods_num").fadeIn(2000);
            }else if(data['code'] == 10010){
                message("您还没有登录，请进行登录！");
                location.href = "/personal/userLoginView";
                return false;
            } else {
                message('系统繁忙，请稍后重试！');
            }

        });
    }
    //<%--============================= 立即购买 ===========================%>
    $('.buy-btn').on('click', function () {
        if (($('.a-style').length + $('.img-border').length) != $('.guigeming').length) {
            message('请检查规格是否选择完整！');
        } else {
            /* $('.close-choose').click();*/
            buyClick();
        }       
    });

    function buyClick(){
        $('.form-gui-ge').css('bottom', '-600px');
        $('.xiaoguo').removeClass('yinying');
        $arr = [];

        $('.a-style').each(function (index, value) {
            index = $(this).next().val();
            var v = index + 'CONNECTOR' + $(value).text();
            $arr.push(v);
        });

        $('#spuId').val($('#sid').val());
        $('#number').val($('.text_box').val());
        $('#guiges').val($arr.join('SEPARATOR'));
        $('#src').val($('.img-border').attr('src'));

        $('#buyForm').submit();
    }
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

   $(".mobile_content").on('click',function(){
       $('#tuwen').submit();
   });


});