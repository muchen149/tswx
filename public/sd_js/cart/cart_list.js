/**
 * Created by Administrator on 2017/5/23.
 */

    //     调用函数
$(function(){
    layout();
    Count();
    notAllSelect();
});

//   函数-动态设置第一个与最后一个商品列表样式
function layout(){
    $('.goods-list').first().css('margin-top','54px');
    $('.goods-list').last().css('margin-bottom','100px');
    $('.goods-property').last().css('margin-bottom','20px');
}

//  函数-计算购物车数量
function Count(){
    var shopCarcount = $('.turn-status1').length;
    $('#shopCar-count').text(shopCarcount);
}


//  函数-商品列表不全选时，“全选按钮”取消选中
function notAllSelect(){
    var lengthParent=$('.goods-list').length;
    var lengthChild=$('.turn-status1').length;
    if (lengthChild<lengthParent){
        $('#turn').attr('class','turn-status-all-2');
    }
    else if(lengthChild==lengthParent){
        $('#turn').attr('class','turn-status-all-1');
    }
}



//     点击编辑
var $carStatus1=$('.car-status1');
var $carStatus2=$('.car-status2');
$carStatus1.on('click',function(){
    $(this).css('display','none');
    $carStatus2.css('display','block');
    $('.goods-list .r').css('display','none');
    $('.goods-list .car-editor').css('display','block');
    $('.heji').css('display','none');
    $('#jianshu').css('display','none');
    $('.goPay li').text('删除');
    $('.goPay li').attr('del',1);

    var total_price = get_goods_amount();
    $(".total_price").text(total_price);
    Count();

});

//     点击完成
$carStatus2.on('click',function(){
    $(this).css('display','none');
    $carStatus1.css('display','block');
    $('.goods-list .r').css('display','block');
    $('.goods-list .car-editor').css('display','none');
    $('.heji').css('display','block');
    $('#jianshu').css('display','block');

    $('.goPay li').text('去结算');
    $('.goPay li').attr('del',0);

    var total_price = get_goods_amount();
    $(".total_price").text(total_price);
    Count();
});


//     选择或取消选择商品
//$(function(){
    $('.l').on('click',function(e){
        var current= e.currentTarget;
        $(current).find('li').toggleClass('turn-status1');
        notAllSelect();

        var total_price = get_goods_amount();
        $(".total_price").text(total_price);
        Count(); //统计选中的商品数
    });
//});


//     点击全选
$('#turn').on('click',function(){

    var lengthParent=$('.goods-list').length;
    var lengthChild=$('.turn-status1').length;

//   判断是否全选，全选时，点击后将取消全选，并改变全选按钮状态
    if(lengthChild==lengthParent){
        $('.l li').attr('class','turn-status2');
        $(this).attr('class','turn-status-all-2');
    }

//    判断不全选，改变商品列表以及全选按钮的选中状态，并给商品列表的按钮增加一个类
    else{
        $('.l li').attr('class','turn-status1').addClass('turn-status2');//将$('.l li')的class设置为turn-status1后，与519行代码冲突，导致$('.l li')没有类名，故增加一个类，且该类的优先级小于'turn-status1'
        $(this).attr('class','turn-status-all-1');
    }

    var total_price = get_goods_amount();
    $(".total_price").text(total_price);
    Count(); //统计选中的商品数
});

//遍历购物车，求出选中商品的总金额
function get_goods_amount(){
    var total_price = 0;
    $('.turn-status1').each(function(){
        var pare = $(this).parent().parent();
        var goods_price = parseInt(pare.find('.g_price').text() * 100);;//parseInt($(this).parents("li").find(".goods-total-price").text() *100); //商品价格
        var goods_num   = parseInt(pare.find('.buy_num').text()); //parseInt($(this).parents("li").find(".quantity").val()); //获得商品数量

        total_price += parseInt(goods_price  * goods_num); //为了计算都转化为整数进行计算
    });
    return total_price/100;
}


//     自增与自减
$(".add").click(function(){
    var cart_id = parseInt($(this).prev().attr('cart_id'));
    var $goodsCount= parseInt($(this).prev().val());
    var buy_num = $goodsCount+1;
    $(this).prev().val(buy_num);

    //把更改的数量同步到到购物车列表数据库中
    $.get('/cart/updateNum/' + cart_id + '/' + buy_num);

    $(this).parent().parent().parent().find('.buy_num').text(buy_num);
});

$(".min").click(function(){

    var cart_id = parseInt($(this).next().attr('cart_id'));
    var goodsCount= parseInt($(this).next().val());
    var buy_num = goodsCount-1;

    if (goodsCount >= 2){
        $(this).next().val(buy_num);

        //把更改的数量同步到到购物车列表数据库中
        $.get('/cart/updateNum/' + cart_id + '/' + buy_num);

        $(this).parent().parent().parent().find('.buy_num').text(buy_num);

    }


});

$(".quantity").blur(buyNumer);

//手动输入商品数量
var reg = /^[0-9]*[1-9][0-9]*$/; //校验正整数
function buyNumer(){

    var buynum = $(this).val();

    if(!reg.test(buynum)){ //若输入的不是正整数，这默认为最低购买下限
        buynum = 1;
    }

    $(this).val(buynum);
    var cart_id = $(this).attr("cart_id");
    //购买数量同步到数据库中
    $.get('/cart/updateNum/' + cart_id + '/' + buynum);

    var total_price = get_goods_amount();
    $(".total_price").text(total_price);
    Count(); //统计选中的商品数
    $(this).parent().parent().parent().find('.buy_num').text(buynum);

}


//去支付或者删除
$(".goPay").on('click',function(){
    var if_del = $(".goPay li").attr('del');
    if(if_del == 1){ //代表删除
        //获取选中的id，进行删除
        var str_cart =[];
        $('.turn-status1').each(function(){
            str_cart.push($(this).attr("cart_id"));
        });

        $data = {
            'cartIds': str_cart
        };
        $.post('/cart/delete', $data, function (data) {
            if (data['code'] == 0) {
                $('.turn-status1').each(function(){
                    $(this).parent().parent().slideUp(300, function () {
                        $(this).remove();
                    });
                });

                message('删除成功！');
                //重新合计金额
                var total_price = get_goods_amount();
                $(".total_price").text(total_price);

            } else {
                message('系统繁忙，请稍后再试！');
            }
        });

    }else{ //代表去结算
        goSettlement();
    }

});


//去结算
function goSettlement(){

    //查看选中的商品个数，没有选择不让提交
    if ($(".turn-status1").length == 0) {
        message('请选择有效的商品！');
        return false;
    } else {

        $arr = [];
        $('.turn-status1').each(function () {
            var cart_id = $(this).attr("cart_id");
            $arr.push(cart_id);
        });
        $('#cartIds').val($arr.join(','));
        $('#jsform').submit();

    }

}


