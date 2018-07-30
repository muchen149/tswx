$(function () {
    var from = $("#from").val();

    //点击修改默认地址
    $("div[name='setaddress']").click(setDefaultAddress);

    $(".editor").on("click",function(){
        window.location.href="/personal/address/addressEdit/"+$(this).attr("address_id");
    });


    $("div[name='deladdress']").on('click', function () {
        var address_id = $(this).attr('address_id');
        var del = $(this);

        showConfirmDialog("确定删除该地址吗？",
            function () {
                hideConfirmDialog();
                $.get('/personal/address/delete/' + address_id, function (data) {

                    if (data['code'] == 0) {
                        del.parent().parent().parent().slideUp(300, function () {
                            del.parent().parent().parent().remove();
                        });
                        message('删除成功！');
                        return true;
                    } else {
                        message(data['message']);
                        return false;
                    }
                });

            });
    });


    //点击设置默认地址
    function setDefaultAddress() {
        var address_id = $(this).attr('address_id');
        var yn = $(this).find('img').attr("ncdefault");
        //若已经是默认地址，则点击无效
        if(yn == "yes"){
            return false;
        }else{
            $.get('/personal/address/setDefault/' + address_id, function (data) {
                if(data > 0){
                    $('img[ncdefault="yes"]').attr('src','/img/address/address-default-pre.png').attr('ncdefault','no');
                    $('.default[address_id="'+address_id+'"]').find('img').attr('src','/img/address/address-default-set.png').attr('ncdefault','yes');

                }else{
                    message("设置失败！");
                    return false;
                }

            });
        }

    }
});