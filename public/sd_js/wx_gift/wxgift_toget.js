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
    $('.select_add').on('click', function () {
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
                        + '<img src="../../sd_img/edit.svg" height="24" width="24"/>'
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
        slide_two = $('.detail-area').val();
        slide_three = $('.detail-city').val();
    });

    //<%--============================= 编辑中保存按钮 ===========================%>
    $('.edit-save').on('click', function () {
        var edit_shr = $('.edit-shr').val();
        var edit_sj = $('.edit-sj').val();

        var edit_xx = $('.edit-xx').val();

        var edit_province = slide_one;
        var edit_area = slide_two;
        var edit_city = slide_three;
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

    //领取礼品
    var clicktag = 0;
    $("#get_gift").on("click",function(){
        if ($('.checkbox-visited').length == 1) {
            var share_gifts_info_id = $("#share_gifts_info_id").val();
            var mess = $('#textarea').val();
            var data = {
                    'share_gifts_info_id':share_gifts_info_id,
                    'message': mess
                };

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

            $.post('/gift/get_gift_order_add',data, function (res) {
                if(res.code == 0){ //成功
                    //跳到领取成功页面
                    window.location.href = '/gift/getGiftSuccess/'+share_gifts_info_id;

                }else{
                    message(res.message,5000);
                    return false;
                }

            });

        } else {
            message('请选择有效的收货地址！');
            return false;
        }

    });


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
        $('#city').val(three_id);
        $('#area').val(two_id);
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
