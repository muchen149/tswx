$(function (){
        //var user_id = $("#user_id").val();
        //删除购物车
        $(".cart-list-del").click(sureDelCartList);
        //购买数量，减
        $(".quantityDecrease").click(minusBuyNum);
        //购买数量加
        $(".quantityPlus").click(addBuyNum);
        //去结算
        $("#goto-settlement").click(goSettlement);
        $(".quantity").blur(buyNumer);

        function sureDelCartList(){
            var  cart_id = $(this).attr("cart_id");

            if(confirm("确定要删除购物车吗？")){
                delCartList(cart_id,$(this));
            }

        }
        //删除购物车
        function delCartList(cart_id,self){

            $arr = [];
            $arr.push(cart_id);
            $data = {
                'cartIds': $arr
            };

            $.post('/cart/delete', $data, function (data) {
                if (data['code'] == 0) {
                    self.parents("li").slideUp(300, function () {
                        $(this).remove();
                    });
                    //self.parents("li").remove();
                    message('删除成功！');
                    //重新合计金额
                    var total_price = get_goods_amount();
                    $(".total_price").text(total_price);

                } else {
                    message('系统繁忙，请稍后再试！');
                }
            });


        }

        //遍历购物车，求出选中商品的总金额
        function get_goods_amount(){
            var total_price = 0;
            $('.eachGoodsCheckBox').each(function(){
                var is_check = $(this).attr('is_check');
                if(is_check == 1){
                    var goods_price = parseInt($(this).parents("li").find(".goods-total-price").text() *100); //商品价格
                    var goods_num = parseInt($(this).parents("li").find(".quantity").val()); //获得商品数量
                    total_price += parseInt(goods_price  * goods_num); //为了计算都转化为整数进行计算
                }
            });
            return total_price/100;
        }


        //购买数量减
        function minusBuyNum(){
            var self = this;
            editQuantity(self,"minus");
        }
        //购买数量加
        function addBuyNum(){
            var self = this;
            editQuantity(self,"add");
        }
        //购买数量增或减，请求获取新的价格
        function editQuantity(self,type){

            var sPrents = $(self).parents(".cart-litemw-cnt");
            var cart_id = sPrents.attr("cart_id");
            var numInput = sPrents.find(".quantity");

            //购买该商品的最低下限
            var min_limit = parseInt(numInput.attr('min_limit'));

            //var p_integral = sPrents.find(".quantity").attr("p_integral");
            var is_check = sPrents.parents("li").find(".eachGoodsCheckBox").attr("is_check");

            var buynum = parseInt(numInput.val());

            var quantity = 1;
            if(type == "add"){
                quantity = parseInt(buynum+1);

                // 
            }else {

                //若目前购买数量大于最低下限，直接减1
                if(buynum > min_limit){
                    quantity = parseInt(buynum-1);

                }else {
                    quantity = min_limit;
                    if(min_limit > 1){
                        message('该规格的商品最低购买数量为 ' +　min_limit);
                    }
                    if(is_check == 1){ //若商品处于选中状态，且商品数量减到最低下限不能再减时直接退出
                        return;
                    }else{ //商品处于未选中状态，且商品数量为最低购买下限，点击减少数量时，商品数量不会变还是最低购买下限，单商品的状态改为选中，所以进行遍历求求商品总额
                        //更改商品为选中状态
                        sPrents.parents("li").find(".eachGoodsCheckBox").attr("is_check",1);
                        sPrents.parents("li").find(".eachGoodsCheckBox").attr('src',"../../img/address/address-default-set.png");

                        //遍历购物车中选中商品的总金额
                        var total_price = get_goods_amount();
                        $(".total_price").text(total_price);
                        return;
                    }

                }
            }

            //点击加或者减时，若该商品没选中，这选中该商品
            if(is_check == 0){
                //选中该商品
                sPrents.parents("li").find(".eachGoodsCheckBox").attr("is_check",1);
                sPrents.parents("li").find(".eachGoodsCheckBox").attr('src',"../../img/address/address-default-set.png");

            }


            numInput.val(quantity);
            //把更改的数量同步到到购物车列表数据库中

            $.get('/cart/updateNum/' + cart_id + '/' + quantity);

            var total_price = get_goods_amount();
            $(".total_price").text(total_price);

        }

    //手动输入商品数量
    var reg = /^[0-9]*[1-9][0-9]*$/; //校验正整数
    function buyNumer(){

        //先把商品处于选中状态
        var is_check = $(this).parents("li").find(".eachGoodsCheckBox").attr("is_check");
        if(is_check == 0){
            //选中该商品
            $(this).parents("li").find(".eachGoodsCheckBox").attr("is_check",1);
            $(this).parents("li").find(".eachGoodsCheckBox").attr('src',"../../img/address/address-default-set.png");

        }
        var buynum = $(this).val();
        var min_limit = parseInt($(this).attr('min_limit'));
        if(!reg.test(buynum)){ //若输入的不是正整数，这默认为最低购买下限
            buynum = min_limit;
        }else{
            if(parseInt(buynum) < min_limit){
                if(min_limit > 1){
                    message('该规格的商品最低购买数量为 ' +　min_limit);
                }
                buynum = min_limit;
            }
        }

        $(this).val(buynum);
        var cart_id = $(this).attr("cart_id");
        //购买数量同步到数据库中
        $.get('/cart/updateNum/' + cart_id + '/' + buynum);

        var total_price = get_goods_amount();
        $(".total_price").text(total_price);

    }

        //去结算
        function goSettlement(){

            //查看选中的商品个数，没有选择不让提交
            if ($("img[is_check='1']").length == 0) {
                message('请选择有效的商品！');
                return false;
            } else {

                $arr = [];
                var tag = 0;
                $('.cart-list-item').each(function () {
                    var is_check = $(this).find(".eachGoodsCheckBox").attr("is_check");
                    if (is_check == 1) {
                       //提交前判断选中商品的购买数量是否达到最低购买数量
                        var buy_name = $(this).find("input[name=b_num_input]").val();
                        var limit_num = $(this).find("input[name=b_num_input]").attr('min_limit');

                        if(buy_name < limit_num){
                            var p_name = $(this).find('.cart-litemwc-pdname').text();
                            alert(p_name+' 的最低购买数量为 '+ limit_num);
                            tag = 1;
                            return false;
                        }

                        var cart_id = $(this).find("input[name='cart_id']").val();
                        $arr.push(cart_id);
                    }
                });

                if(tag == 1){
                    return false;
                }

                $('#cartIds').val($arr.join(','));
                $('#jsform').submit();

            }


        }

});