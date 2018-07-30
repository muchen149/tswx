/**
 * Created by yiyuanda on 2017/2/23.
 */

var mySwiper1 = new Swiper('.swiper-container1', {
    direction: 'vertical',
    slidesPerView: 'auto',
    centeredSlides: true,
    observer: true,         //修改swiper自己或子元素时，自动初始化swiper
    observeParents: true,   //修改swiper的父元素时，自动初始化swiper

    onSlideChangeEnd: function (swiper) {
        var one_id = $('.swiper-slide-active').find('.one-id').val();
        slide_one = one_id;
        $('.swiper-container2').find('.swiper-wrapper').html('<div class="swiper-slide">' +
            '<span>-请选择-</span>' +
            ' </div>');
        $.get('/personal/address/child/' + one_id, function (data) {
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
    showConfirmDialog("确认使用当前地址配送？",
        function () {
            $(this).find('.checkbox-style').addClass('checkbox-visited');
            $(this).parent().siblings().find('.checkbox-style').removeClass('checkbox-visited');
            $(this).find('.radio-op').prop('checked', true);
            $(this).find('.default').val(1);
            $(this).parent().siblings().find('.default').val(0);

            $('.xq-lb').css('bottom', '-300px').css('transition', 'all 0.5s');
            $('.xiaoguo').removeClass('yinying');
            $('.right-btn').css('transform', 'rotate(0)').css('transition', 'all 0.5s');

            var id = $("input[name='radio']:checked").next().val();
            hideConfirmDialog();
            $.get('/personal/address/setDefault/' + id);
            var data_append = '';
            var skus = $('.skuId').val().split('-');
            data_append += "<input type='hidden' name='awards_sku_lst[0][sku_id]' value='" + skus[0] + "' />";
            data_append += "<input type='hidden' name='awards_sku_lst[0][number]' value='" + skus[1] + "' />";
            data_append += "<input type='hidden' name='awards_sku_lst[0][price]' value='" + skus[2] + "' />";
            data_append += "<input type='hidden' name='awards_sku_lst[0][promotions_type]' value='" + skus[3] + "' />";
            data_append += "<input type='hidden' name='awards_sku_lst[0][promotions_id]' value='" + skus[4] + "' />";
            //跳转至确认订单页
            $('#awardsForm').append(data_append).submit();
        });
});

//<%--============================= 点击地址详情列表选中的效果 ===========================%>
$('.checkbox-style').on('click', function () {
    if ($(this).attr('class') == 'checkbox-style') {
        $(this).addClass('checkbox-visited');
        $(this).parent().parent().parent().parent().siblings().find('.checkbox-style').removeClass('checkbox-visited');
    }
});

//<%--============================= 新增地址功能 ===========================%>
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
        showConfirmDialog("确认保存并使用新地址配送？",
            function () {
                $.post('/personal/address/save', $data, function (resn) {
                    if (!resn['code']) {
                        var address_id = resn['data']['address_id'];
                        // 未报错
                        $('.close-set-btn').click();
                        $('.name').val('');
                        $('.phone').val('');
                        $('.address-info').val('');
                        hideConfirmDialog();
                        var data_append = '';
                        var skus = $('.skuId').val().split('-');
                        data_append += "<input type='hidden' name='awards_sku_lst[0][sku_id]' value='" + skus[0] + "' />";
                        data_append += "<input type='hidden' name='awards_sku_lst[0][number]' value='" + skus[1] + "' />";
                        data_append += "<input type='hidden' name='awards_sku_lst[0][price]' value='" + skus[2] + "' />";
                        data_append += "<input type='hidden' name='awards_sku_lst[0][promotions_type]' value='" + skus[3] + "' />";
                        data_append += "<input type='hidden' name='awards_sku_lst[0][promotions_id]' value='" + skus[4] + "' />";
                        //跳转至确认订单页
                        $('#awardsForm').append(data_append).submit();
                    } else {
                        message('添加地址失败！');
                        return false;
                    }
                });
            });
    }
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