//var curpage = GetQueryString('curpage');
//var order = GetQueryString('order');
//var order_state =  GetQueryString('state');
//var pagesize = 16;
//if(curpage == "" || curpage == "0" || curpage==null || typeof (curpage) == "undefined"){
//    curpage = 1;
//}
$(function(){
    //去付款
    $('.go-to-pay').on('click', function () {
        var order_id = $(this).next().val();
        window.location.href = '/wx/pay/wxPay?plat_order_id=' + order_id;
    });

    //再次购买
    $('.buyagain').on('click', function () {
        var spu_id = $(this).val();
        window.location.href = '/ys/goods/spuDetail/' + spu_id;
    });

   //取消订单
    $(".cancel-order").on('click', function () {
        var order_id = $(this).prev().val();
        var cancel = $(this);
        var is_share = $(this).attr('is-share');
        var r=confirm("确定取消该订单吗？");
        if (r==true){
            if(is_share == 1){
             //用于微信礼品分享的订单
                $.get('/gift/cancelShareGiftOrder/'+ order_id , function (data) {
                    if (data['code'] == 0) {
                        cancel.parent().parent().parent().slideUp(300, function () {
                            cancel.parent().parent().parent().remove();
                            message('取消成功！');
                        });

                    } else {
                        message(data['message']);
                    }
                });

            }else{
                //普通，状态为未付款的订单取消
                $.get('/order/cancel/'+ order_id,function (data) {
                    if (data['code'] == 0) {
                        cancel.parent().parent().parent().slideUp(300, function () {
                            cancel.parent().parent().parent().remove();
                            message('取消成功！');
                        });

                    } else {
                        message(data['message']);
                    }
                });

            }

        }


    });



    //确认订单
    $('.sure_order').on('click', function () {
        var sure_complete = $(this);
        var data = sure_complete.attr("order");
        $.get('/order/confirm/' + data, function (data) {
            if (data['code'] == 0) {
                sure_complete.parent().parent().parent().slideUp(300, function () {
                    sure_complete.parent().parent().parent().remove();
                    message('已完成！');

                });
            } else {
               message('系统繁忙，请稍后重试！');
            }

        })
    });


    //删除订单
    $('.del-order').on('click', function () {
        var data = $(this).attr("order");
        var del = $(this);
        $.get('/order/delete/' + data, function (data) {
            console.log(data);
            if (data['code'] == 0) {
                del.parent().parent().parent().slideUp(300, function () {
                    del.parent().parent().parent().remove();
                    message('删除成功！');
                });
            } else {
                message('系统繁忙，请稍后重试！');
            }
        })
    });


    //退单
    $('.refund-order').on('click', function () {
        var data = $(this).attr("order");
        var del = $(this);
        var refund_order=confirm("确定要退单吗？");
        if(refund_order == true){
            $.get('/wx/pay/wxRefund/' + data, function (data) {
                //console.log(data);
                //alert(JSON.stringify(data));
                //return true;
                if (data['code'] == 0) {
                    //return true;
                    del.parent().parent().parent().slideUp(300, function () {
                        del.parent().parent().parent().remove();
                        message('退单成功！');
                    });

                } else if (data['code'] == 2) {
                    message(data['message']);
                }else{
                    message('系统繁忙，请稍后重试！');
                }
            });
            return true;
        }
    });

    //查看物流
    $(".show_shipping").on('click', function () {
        var data = $(this).attr("order");
        window.location.href = '/order/logistics/' + data;
    });

    ////当点击某个标签时，该标签对应的的列表显示出来，其他的隐藏
    //$(".nav-content").on('click',function(){
    //    var state = $(this).attr('state');
    //
    //    $('.nav-title').find(".current").removeClass("current");
    //    $(this).addClass("current");
    //
    //    $('.order-list-wp').each(function(){
    //        var s = $(this).attr('state');
    //        if(s == state){
    //            $(this).show();
    //        }else{
    //            $(this).hide();
    //        }
    //    });
    //
    //
    //});

});