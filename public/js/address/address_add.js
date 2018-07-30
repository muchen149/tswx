$(function () {

    $("select[name=prov]").change(function () {

        var prov_id = $(this).val();
        $.get('/personal/address/child/' + prov_id, function (data) {

            var city_html = '<option value="">请选择所在城市...</option>';

            $.each(data['data'], function (index, item) {
                city_html += '<option value="' + item['id'] + '">' + item['name'] + '</option>';

            });

            $("select[name=city]").html(city_html);
            $("select[name=region]").html('<option value="">请选择所在区县...</option>');

        });

    });

    $("select[name=city]").change(function () {
        var city_id = $(this).val();
        $.get('/personal/address/child/' + city_id, function (data) {

            var region_html = '<option value="">请选择所在区县...</option>';

            $.each(data['data'], function (index, item) {
                region_html += '<option value="' + item['id'] + '">' + item['name'] + '</option>';

            });

            $("select[name=region]").html(region_html);

        });

    });

    //设置默认
    $("img[nctype='setdefault']").on("click", function () {
        if ($(this).attr("ncdefault") == "yes") {
            $(this).attr({ncdefault: "no", src: "/img/address/address-default-pre.png"});
            $("#is_use").val("0");
        } else {
            $(this).attr({ncdefault: "yes", src: "/img/address/address-default-set.png"});
            $("#is_use").val("1");
        }
    });


    $('.add_address').on('click', function () {
        var name = $('input[name=true_name]').val();
        var tel = $('input[name=mob_phone]').val();
        var address_info = $('input[name=address]').val();
        var is_default = $('input[name=is_use]').val();

        var province_id = $('select[name=prov]').val();
        var area_id= $('select[name=city]').val();
        var city_id = $('select[name=region]').val();

        $data = {
            'name': name,
            'mobile': tel,
            'address': address_info,
            'province_id': province_id,
            'city_id': city_id,
            'area_id': area_id,
            'is_default':is_default
        };

        if (name == '' || name == null) {
            message('收货人姓名不能为空！');
        } else if (tel == '' || tel == null || !/^(13[0-9]|14[0-9]|15[0-9]|17[0-9]|18[0-9])\d{8}$/i.test(tel)) {
            message('请输入有效的手机号！');
        } else if (address_info == '' || address_info == null) {
            message('详细地址不能为空！');
        }
        else if(province_id == '' || city_id =='' || area_id == ''){
            message('省市区地址不完整！');
        } else {
            $.post('/personal/address/addressSave', $data, function (data) {
                if (!data['code']) {
                    // 未报错
                    message('保存成功！');
                    location.href = '/personal/address/addressList';
                } else {
                    message("保存失败："+data['message']);
                }
            });
        }

    });

});