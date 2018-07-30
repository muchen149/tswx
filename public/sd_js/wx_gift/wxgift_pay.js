/**
 * Created by Administrator on 2017/4/14.
 */
$(document).ready(function () {

    var payable_total = $('#payable_amount').text(); //商品总金额（含运费）

    var use_amount = 0; //记录用户使用几种支付(水丁币，零钱等)共使用的金额

    var sdb_rmb = 0; //记录实际使用的水丁币支付的人民币金额
    var sdb_amount = 0; //记录水丁币的使用总数
    var lingqian = 0; //记录实际使用的零钱金额
    var card_pay_total = 0; //保存选中的卡余额支付的总金额

    var fare = $('#fare').text(); //运费
    var org_pirce = 0;      //折扣价和实际相差的价格
    var orgCard_id = 0;     //机构卡id
    var select;

    var wxJson = '';
    var plat_order_id = '';
    var share_gifts_info_id = '';
    function onBridgeReady() {
        WeixinJSBridge.invoke(
            'getBrandWCPayRequest',wxJson, function (res) {
                console.log(res.err_msg);
                if (res.err_msg == "get_brand_wcpay_request:ok") {
                    //礼品订单生成后，跳到分享页面进行分享
                    window.location.href ='/gift/giftToShare/'+share_gifts_info_id;
                    //{{--window.location.href = '{{asset('order/info/'.$plat_order->plat_order_id)}}';--}}
                } else if (res.err_msg == "get_brand_wcpay_request:cancel") {
                    $.get('/gift/cancelShareGiftOrder/'+ plat_order_id, function (data) {
                        window.location.href ='/order/index';
                    });

                } else {

                    $.get('/gift/cancelShareGiftOrder/'+ plat_order_id, function (data) {
                        window.location.href ='/order/index';
                    });
                }
            });
    }

    function Wechat_Pay(){
        if (typeof WeixinJSBridge == "undefined") {
            if (document.addEventListener) {
                document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
            } else if (document.attachEvent) {
                document.attachEvent('WeixinJSBridgeReady', onBridgeReady);
                document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
            }
        } else {
            onBridgeReady();
        }

    }


    //当选中某个支付方式时，计算需要再支付多少钱
// chose_amount:点击选中的支付方式可以支付的最大金额
//
// 返回值 amount：点击选中的该支付方式需要支付的金额（若为该支付方式的最大金额，说明选中该支付方式恰好可以支付完，若小于，说明该支付方式金额用不完就可支付成功）
    function get_pay_amount(chose_amount){
        var heji = 0;
        var amount = 0;
        //若已选中的加上刚选中的金额之和大于商品总金额，说明足够支付
        if( accAdd_new(use_amount, chose_amount)  >= payable_total){
            amount = accSub(payable_total, use_amount);
            use_amount = payable_total;
            heji = 0;
            $('#zhifu-total').text(heji);
            return amount;
        }else{ //若二者之和小于商品金额，说明已选中的金额加上刚选中的最大金额也不够支付该商品

            use_amount = accAdd_new(use_amount, chose_amount);
            amount = chose_amount;
            heji = accSub(payable_total, use_amount);
            $('#zhifu-total').text(heji);
            return amount;
        }

    }

    //点击取消某个支付方式时 把该支付方式实际支付的金额从use_amount减掉，把调整合计金额
    //balance_pay_amount为取消的支付方式实际支付的金额
    function cancle_pay(balance_pay_amount){
        var zhifu_total = $('#zhifu-total').text();
        use_amount = accSub(use_amount, balance_pay_amount);
        var heji = accAdd_new(zhifu_total, balance_pay_amount);

        $('#zhifu-total').text(heji);
    }

    //使用水丁币选择按钮,水丁币支付时只能支付的金额必须为整数
    $('#shuibi').on('click',function(){
        if ($(this).hasClass('switch1')){ //进行使用虚拟币
            //判断合计是否为零，若为零，说明不在需要其他支付方式了。
            var zhifu = parseInt($('#zhifu-total').text() * 100);
            if(zhifu == 0){
                message('您选中的支付方式已经足够支付该商品！');
                return false;
            }

            //由于水丁币支付只能为整数，因此取出合计还需要支付的金额（舍去小数）
            var zf_int = Math.floor($('#zhifu-total').text());
            var zhifu_total = $('#zhifu-total').text();
            //最多可用的水丁币对应的人民币
            var di_rmb = parseInt($('#di_rmb').text());

            var heji = 0;

            if(zf_int == 0){ //为零说明合计里面是小于1元的
                message('支付金额必须为整数，该小于一元的金额无法支付！');
                return false;
            }else if( zf_int >= di_rmb ){ //说明合计里面的整数大于等于水丁币可以支付的最大rmb金额，因此全部支付
                sdb_rmb = di_rmb;
                heji = accSub(zhifu_total,di_rmb);
                $('#zhifu-total').text(heji);

            }else if(zf_int < di_rmb){ //说明合计里面的整数小于水丁币可以支付的最大rmb金额，因此水丁币只需支付合计里面的整数
                sdb_rmb = zf_int;
                heji = accSub(zhifu_total,zf_int);
                $('#zhifu-total').text(heji);
            }

            //调整use_amount
            use_amount = accAdd_new(use_amount,sdb_rmb);

            //得到水丁比的换算率,换算出支付的人民币对应的水丁币
            var plat_vrb_rate = $('#plat_vrb_rate').val();
            sdb_amount = Math.ceil(sdb_rmb * plat_vrb_rate);
            //本次使用的水丁币，以及对应的金额
            $('#this_used').text(sdb_amount);
            $('#di_rmb').text(sdb_rmb);
            $(this).attr('class','switch2');
            $("#y_use").show();
            $("#n_use").hide();
        }else{
            $(this).attr('class','switch1');

            //当取消水丁币支付时，调整合计和use_amount，sdb_rmb清空，
            var zhifu_total = $('#zhifu-total').text();
            var zf = toDecimal2(accAdd_new(zhifu_total,sdb_rmb));
            $('#zhifu-total').text(zf);

            use_amount = accSub(use_amount,sdb_rmb);
            sdb_rmb = 0;

            $("#y_use").hide();
            $("#n_use").show();
        }

    });

    //使用零钱按钮
    $("#ling_switch").on('click',function(){

        $(".to-chose-card").each(function () {
            select = $('.to-chose-card[chose=1]').attr('chose');

        });
        if(select == 1){
            message('您已经选择卡余额');
            return false;
        }

        if ($(this).hasClass('switch1')){ //进行使用虚拟币

            //最多可用的零钱数
            var max_ling = $('#max_ling').text();

            //判断合计是否为零，若为零，说明不在需要其他支付方式了。
            var zhifu = parseInt($('#zhifu-total').text() * 100);
            if(zhifu == 0){
                message('您选中的支付方式已经足够支付该商品！');
                return false;
            }


            //获得选中的支付方式实际需要支付的金额，并保存该支付金额
            lingqian = get_pay_amount(max_ling);
            $('#use_ling').text(lingqian);

            $(this).attr('class','switch2');
            $("#y_ling").show();
            $("#n_ling").hide();

        }else{ //取消零钱支付
            $(this).attr('class','switch1');

            //把该支付方式实际支付的金额从use_amount减掉，把调整合计金额
            cancle_pay(lingqian);
            lingqian = 0;


            $("#y_ling").hide();
            $("#n_ling").show();
        }

    });


    //使用卡余额，点击卡余额时，从下面弹出各个卡的列表供用户进行选择
    $('#ka-yu-e').on('click', function () {

        //判断可供用户使用的卡余额个数，若为零，表示用户没有卡可以用
        var card_num = $("#card_num").text();
        if(card_num == 0){
            message("您还没有卡余额可以使用！");
            return false;
        }
        $('#ka-yu-e-select').css('bottom', 0).css('transition', 'all 0.5s');
        $('.ka-yu-e-xiaoguo').addClass('yinying');
        $('#ka-yu-e-right-btn').css('transform', 'rotate(90deg)').css('transition', 'all 0.5s');
    });


    //点击关闭按钮
    $('#ka_yu_e_close').on('click', function () {
        var len = $(".to-chose-card[chose=1]").length;
        $("#used_card_num").text(len);

        $('#ka-yu-e-select').css('bottom', '-300px').css('transition', 'all 0.5s');
        $('.ka-yu-e-xiaoguo').removeClass('yinying');
        $('#ka-yu-e-right-btn').css('transform', 'rotate(0)').css('transition', 'all 0.5s');
    });

    //选择卡余额
    $(".to-chose-card").on('click',function(){
        if($("#ling_switch").hasClass('switch2')){
            message('您已经选择零钱');
            return false;
        }
        var chose = $(this).attr("chose");
        var balance_available = $(this).children('input[name=balance_available]').val(); //该卡最大能够支付的金额
        var balance_pay_amount = $(this).children('input[name=balance_pay_amount]').val();

        if(chose == 0){
            //判断合计是否为零，若为零，说明不在需要其他支付方式了。
            var zhifu = parseInt($('#zhifu-total').text() * 100);
            if(zhifu == 0){
                message('您选中的支付方式已经足够支付该商品！');
                return false;
            }
            //获得选中的支付方式实际需要支付的金额，并保存该支付金额
            var need_amount = get_pay_amount(balance_available);
            $(this).children('input[name=balance_pay_amount]').val(need_amount);

            //把支付的金额加到记录卡余额支付的总金额变量card_pay_total中
            card_pay_total = accAdd_new(card_pay_total, need_amount);

            $(this).find('.select_img').attr('src','/sd_img/selected.png');
            $(this).attr("chose",1);
        }else{
            //把该支付方式实际支付的金额从use_amount减掉，把调整合计金额
            cancle_pay(balance_pay_amount);
            //把保存实际支付的调整为0
            $(this).children('input[name=balance_pay_amount]').val(0);

            //从记录卡余额支付的总金额变量card_pay_total中减去实际支付的
            card_pay_total = accSub(card_pay_total, balance_pay_amount);

            $(this).find('.select_img').attr('src','/sd_img/defau.png');
            $(this).attr("chose",0);
        }
    });
    //清除水币,余额,卡折扣
    function clear(){
        if($('#shuibi').hasClass('switch2')){
            $('#shuibi').attr('class','switch1');

            //当取消水丁币支付时，调整合计和use_amount，sdb_rmb清空，
            var zhifu_total = $('#zhifu-total').text();
            var zf = accAdd_new(zhifu_total,sdb_rmb);
            $('#zhifu-total').text(zf);

            use_amount = accSub(use_amount,sdb_rmb);
            sdb_rmb = 0;

            $("#y_use").hide();
            $("#n_use").show();
        }
        if($("#ling_switch").hasClass('switch2')){
            $('#ling_switch').attr('class','switch1');
            //把该支付方式实际支付的金额从use_amount减掉，把调整合计金额
            cancle_pay(lingqian);
            lingqian = 0;
            $("#y_ling").hide();
            $("#n_ling").show();
        }
        $(".to-chose-card").each(function () {
            select = $('.to-chose-card[chose=1]').attr('chose');
            if(select == 1){
                var balance_pay_amount = $('.to-chose-card[chose=1]').children('input[name=balance_pay_amount]').val();
                //把该支付方式实际支付的金额从use_amount减掉，把调整合计金额
                cancle_pay(balance_pay_amount);
                //把保存实际支付的调整为0
                $('.to-chose-card[chose=1]').children('input[name=balance_pay_amount]').val(0);

                //从记录卡余额支付的总金额变量card_pay_total中减去实际支付的
                card_pay_total = accSub(card_pay_total, balance_pay_amount);
                $('.to-chose-card[chose=1]').find('.select_img').attr('src','/sd_img/defau.png');
                $('.to-chose-card[chose=1]').attr("chose",0);
                var len = 0;
                $('#used_card_num').text(len);
            }
        });

    }
    //使用机构卡，点击机构卡时，从下面弹出各个卡的列表供用户进行选择
    $('#ji-gou-ka').on('click', function () {

        //判断可供用户使用的卡余额个数，若为零，表示用户没有卡可以用
        var card_num = $("#card_num").text();
        if(card_num == 0){
            message("您还没有卡余额可以使用！");
            return false;
        }
        $('#ji-gou-ka-select').css('bottom', 0).css('transition', 'all 0.5s');
        $('.ji-gou-ka-xiaoguo').addClass('yinying');
        $('#ji-gou-ka-right-btn').css('transform', 'rotate(90deg)').css('transition', 'all 0.5s');
    });
    //点击关闭按钮
    $('#ji_gou_ka_close').on('click', function () {
        $('#ji-gou-ka-select').css('bottom', '-300px').css('transition', 'all 0.5s');
        $('.ji-gou-ka-xiaoguo').removeClass('yinying');
        $('#ji-gou-ka-right-btn').css('transform', 'rotate(0)').css('transition', 'all 0.5s');
    });

    //选择机构卡
    $(".to-chose-orgCard").on('click',function(){

        $(this).attr("chose",1).siblings().attr("chose",0);
        var heji = 0;
        var orgCardId = $(this).children('input[name=orgCard_id]').val();  //卡id
        var price = $(this).children('input[name=price]').val(); //市场价
        var discount_price = toDecimal2($(this).children('input[name=card_count]').val()/10); //折扣
        var dis_price = toDecimal2(accAdd(accMul(discount_price,price),fare)); //卡折扣价
        var payable = $('#payable_amount').text();

        if(dis_price < parseFloat(payable)){
            $(this).attr("chose",1).siblings().attr("chose",0);
            if(orgCard_id != 0){
                debugger;
                cancle_pay(toDecimal2(org_pirce));
                var original_price = 0;
                $(this).children('input[name=org_pay_amount]').val(original_price);
                $(this).find('.select_img').attr('src','/sd_img/defau.png');
                $(this).attr("chose",0);
                clear();
            }

            $(this).attr("chose",1).siblings().attr("chose",0);
            if(orgCard_id == orgCardId){
                orgCard_id = 0;
            }else {
                $('.to-chose-orgCard[chose=1]').each(function () {
                    heji = toDecimal2(accSub(dis_price, toDecimal2(use_amount))); //市场价减去其他（水丁币，领取，卡余额）抵消的价格
                    $('#zhifu-total').text(heji); //总计

                    org_pirce = accSub(toDecimal2(payable), dis_price);
                    if (org_pirce >= 0) {
                        //获得选中的支付方式实际的折扣金额，并保存该折扣金额
                        $(this).children('input[name=org_pay_amount]').val(org_pirce);
                    }
                    use_amount = toDecimal2(accAdd_new(use_amount, org_pirce));
                    $(this).find('.select_img').attr('src', '/sd_img/selected.png');
                    orgCard_id = orgCardId; //记录点击id
                });
            }
        }else{
            message('此选项不是最优折扣');
            return;
        }

        $('.to-chose-orgCard[chose=0]').each(function () {
            $(this).find('.select_img').attr('src','/sd_img/defau.png');

        });
    });
    //强制保留两位数
    function toDecimal2(x) {
        var f = parseFloat(x);
        if (isNaN(f)) {
            return false;
        }
        var f = Math.round(x*100)/100;
        var s = f.toString();
        var rs = s.indexOf('.');
        if (rs < 0) {
            rs = s.length;
            s += '.';
        }
        while (s.length <= rs + 2) {
            s += '0';
        }
        return s;
    }

   //微信支付按钮
    $("#wx_pay").on('click',function(){
        if ($(this).hasClass('switch1')){ //进行微信支付
            $(this).attr('class','switch2');

        }else{ //取消微信支付
            $(this).attr('class','switch1');
        }
    });


  //立即支付
  $("#submit_pay").on('click',function(){
      //点击立即支付时候判断合计金额是否为零，若不为零，则需要进行微信支付，只有支付成功才能进行分享礼品，不存在未支付订单
      var zhifu = parseInt($('#zhifu-total').text() * 100);
      var b = $("#wx_pay").hasClass('switch1');
      if(zhifu != 0 && b){ //若合计不为零且微信支付没有勾选，则进行勾选微信支付进行支付
          message("您需要微信支付 "+$('#zhifu-total').text() +" ,请勾选微信支付");
          return false;
      }


      //获取卡余额支付的详情列表
      var card_lst= new Array();
      var orgCard_lst = new Array();
      $(".to-chose-card[chose=1]").each(function(){
          var arr = {
              'rechargecard_id' : $(this).children("input[name=rechargecard_id]").val(),
              'card_id' : $(this).children("input[name=card_id]").val(),
              'balance_amount' : $(this).children("input[name=balance_amount]").val(),
              'balance_available' : $(this).children("input[name=balance_available]").val(),
              'balance_pay_amount' : $(this).children("input[name=balance_pay_amount]").val(),
          };
          card_lst.push(arr);
      });

      $(".to-chose-orgCard[chose=1]").each(function(){
          var arr = {
              'orgCard_id' : $(this).children("input[name=orgCard_id]").val(),
              'org_pay_amount' : $(this).children("input[name=org_pay_amount]").val(),
          };

          orgCard_lst.push(arr);
      });

      var data = {
          'skus': $('#skuId').val(),
          'num': $('#g_num').val(),
          'price': $('#g_price').val(),
          'g_freight': $('#g_freight').val(),

         // 'title': $("#title").val(),
          'message': $('textarea').val(),


          //水丁币支付金额
          'pay_vrb_amount' : sdb_amount,
          //零钱支付金额
          'pay_wallet_amount' : lingqian,
          //卡余额支付的总金额
          'pay_card_balance_amount' : card_pay_total,
          //卡支付详情列表
          'card_lst': card_lst,
          //机构卡折扣
          'orgCard_lst':orgCard_lst
      };

      /*$.post('/gift/gift_order_add',data, function (res) {
          if(res.code == 0){
              plat_order_id = res.data.plat_order_id;
              share_gifts_info_id = res.data.share_gifts_info_id;
              var type = res.data.type;
              if(type == 1){ //若为1表示全部用非人民支付，跳到分享页面
                  window.location.href ='/gift/giftToShare/'+share_gifts_info_id;
              }else{ //还需要进行微信支付
                  wxJson = res.data.wx_json;
                  Wechat_Pay();
              }

          }else{
              message(res.message,5000);
              return false;
          }
      });*/
      $.post('/gift/gift_order_add',data, function (res) {
          if(res.code == 0){
              plat_order_id = res.data.plat_order_id;
              share_gifts_info_id = res.data.share_gifts_info_id;
              var type = res.data.type;
              if(type == 1){ //若为1表示全部用非人民支付，跳到分享页面
                  window.location.href ='/gift/giftToShare/'+share_gifts_info_id;
              }else{ //还需要进行微信支付
                  //wxJson = res.data.wx_json;
                  Wechat_Pay();
                  window.location.href = '/wx/pay/wxPay?plat_order_id='+plat_order_id;
              }

          }else{
              message(res.message,5000);
              return false;
          }
      });

  });


});

function accAdd_new(arg1,arg2){
    //进行相加的两个数同时乘以100，进行整数运算

    var arg1 = parseInt(parseFloat(arg1) * 1000);
    var arg2 = parseInt(parseFloat(arg2) * 1000);

    var sum = (arg1 +　arg2) / 1000;
    return sum;
}


