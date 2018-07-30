@extends('inheritance')

@section('title')
    专属商品
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('elife_css/mui.min.css')}}">
    <link rel="stylesheet" href="{{asset('elife_css/common.css')}}">
    <link rel="stylesheet" href="{{asset('/elife_css/new_common.css')}}">
    <link rel="stylesheet" href="{{asset('/elife_css/e_shop.css')}}">
    <style>

        .s-listAll{
            padding: 5px 10px;
        }
        /*阿里图标样式初始化 -start*/
        .icon{
            width: 1em;
            height: 1em;
            vertical-align: -0.15em;
            fill: currentColor;
            overflow: hidden;
        }
        /*阿里图标样式初始化 -end*/
        .head{
            width: 100%;
        }
        .head img{
            width: 100%;
        }
        /*改写mui顶部标签切换组件的选中样式*/
        .mui-control-item.mui-active{
            color: #f53a3a!important;
            border-bottom: 2px solid #f53a3a!important;
        }

        /*改写mui卡片视图content图片样式*/
        .mui-card-content img{
            width: 100%;
        }
        .mui-card-footer-r{
            border-left: 1px solid rgb(200,200,200);
            text-align: center;
            padding-left: 8px;
            margin-left: 10px;
            width: 70px;
        }
        .mui-card-footer:before{
            position: absolute;
            top: 0;
            right: 0;
            left: 0;
            height: 0px;
            content: '';
            -webkit-transform: scaleY(.5);
            transform: scaleY(.5);
            background-color: rgb(200, 199, 204);
        }
        .select{
            color: #f53a3a;
        }

        /*底部弹出的商品属性*/
        /*#popover{*/
            /*bottom: 0px!important;*/
            /*background-color: rgb(240,240,240);*/
        /*}*/
        .popover{
            bottom: 0px!important;
            background-color: rgb(240,240,240);
        }
        .property-sure{
            width: 100%;
            height: 46px;
            background-color: #f53a3a;
        }
        .property-sure a{
            color: white;
        }
        .property-close{
            position: absolute;
            right: 16px;
            top: 12px;
        }

        .property-unselect{
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 3px 10px;
            margin-right: 15px;
            margin-bottom: 10px;
            border-radius: 10px;
            font-size: 12px;
            color: rgb(80,80,80);
            background-color: #e4e4e4;
        }
        .property-select{
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 3px 10px;
            margin-right: 15px;
            margin-bottom: 10px;
            border-radius: 10px;
            font-size: 12px;
            color: rgb(192,192,192) !important;
            background-color: #f53a3a;
        }
        .select-goods-property{
            width: 100%;
        }
        .select-goods-property p{
            margin-left: 3%;
            margin-top: 5px;
            margin-bottom: 8px;
        }
        .goods-head{
            display: flex;
            margin-left: 3%;
            height: 70px;
        }
        .goods-head ul:first-child{
            background-color: white;
            padding: 5px;
            border: 1px solid rgb(220,220,220);
            border-radius: 6px;
            margin-top: -30px;
        }
        .goods-head ul:first-child img{
            width: 90px;

        }
        .goods-head ul:last-child{
            margin-left: 5%;
            margin-top: 14px;

        }
        .goods-property{
            display: flex;
            margin-left: 3%;
            justify-content: flex-start;
            flex-wrap: wrap;
            align-items: center;
        }

        /*商品选择数量*/
        .gift-amount{
            height: 50px;
            margin-bottom: 10px;
        }
        .gift-amount ul:first-child{
            margin-left: 3%;
        }
        .gift-amount ul:last-child{
            margin-right: 3%;
            width: 117px;
        }
        .gift-amount ul:last-child input{
            box-sizing: border-box;
            text-align: center;
            width: 60px;
            height: 30px;
            border-top: 1px solid rgb(220,220,220);
            border-bottom: 1px solid rgb(220,220,220);
            border-left: none;
            border-right: none;
            outline: none;
        }
        .min{
            box-sizing: border-box;
            height: 30px;
            width: 30px;
            border: 1px solid rgb(220,220,220);
            border-radius: 4px 0 0 4px;

        }
        .add{
            box-sizing: border-box;
            height: 30px;
            width: 30px;
            border: 1px solid rgb(220,220,220);
            border-radius: 0 4px 4px 0;

        }

    </style>
@endsection

@section('content')
    <body>
    {{--<div class="head">
        <a href="{{$advertList['advert_a0201'][0]->out_url}}"><img src="{{$advertList['advert_a0201'][0]->images}}" alt=""></a>
    </div>--}}

    <div class="mui-segmented-control mui-segmented-control-inverted mui-segmented-control-primary" style="padding: 0 6px;background-color: white">
       {{-- @foreach($label_goods_list as $key => $label_list)
             @if($key == 0)
                <a class="mui-control-item  font-12 mui-active" href="#item{{$label_list['share_gifts_label_id']}}">{{$label_list['share_gifts_label_name']}}</a>
             @else
                <a class="mui-control-item font-12 color-80" href="#item{{$label_list['share_gifts_label_id']}}">{{$label_list['share_gifts_label_name']}}</a>
            @endif
        @endforeach--}}

    </div>
    <div class="container">
    <div class="s-listAll" id="product_list">
       {{-- @foreach($label_goods_list as $key => $label_list)--}}
            <div id="item1" class="mui-control-content mui-active">

                <ul class="clearFix">
                @foreach($label_goods_list[0]['g_list'] as $k => $good_info)
                        <li>
                            <a href="{{url('elife/goods/spuDetail/'.$good_info['spu_id'])}}"><img src="{{$good_info['main_image']}}" width="100%" alt="">
                                <h4>{{$good_info['spu_name']}}</h4>
                                <p class="price">￥&nbsp;{{$good_info['spu_price']}}</p>
                                {{--<p class="tag"><i></i><i></i></p>--}}
                            </a>
                        </li>
                @endforeach
                </ul>
            </div>
    </div>
    </div>
    <form action="{{asset('/wx/pay/orderConfirm')}}" method="post" id="orderConfirm">
        <input type="hidden" name="spu_id" id="spu_id">
        <input type="hidden" name="spec" id="spec">
        <input type="hidden" name="gift_num" id="gift_num" value="1">
    </form>

</body>

@endsection

@section('js')
    <script type="text/javascript" src="{{asset('elife_js/mui.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('elife_js/common-top.js')}}"></script>

    <script src="{{asset('elife_js/jquery.min.js')}}"></script>
    <script src="{{asset('elife_js/font_wvum.js')}}"></script>

    <script>

        $('.like').on('click',function(e){
            var currentValue= e.currentTarget;
            $(currentValue).toggleClass('select');
        });

        //单机商品数量+
        $(".add").click(function(){
            var goodsCount= parseInt($(this).prev('input[name=giftNum]').val());
            ++goodsCount;
            $(this).prev('input[name=giftNum]').val(goodsCount);
        });

        $(".min").click(function(){
            var goodsCount= parseInt($(this).next('input[name=giftNum]').val());
            if (goodsCount>=2){
                --goodsCount;
                $(this).next('input[name=giftNum]').val(goodsCount);
            }

        });

        //手动输入商品数量
        var reg = /^[0-9]*[1-9][0-9]*$/; //校验正整数
        $(".quantity").blur(buyNumer);
        function buyNumer(){
            var buynum = $(this).val();
            if(!reg.test(buynum)){ //若输入的不是正整数，这默认为1
                buynum = 1;
            }

            $(this).val(buynum);
        }

        $(document).ready(function () {

            var spuLength = $('.spuLength').val();//后台返回规格数组的长度
            $('li[attr=pro]').bind('click', function (e) {
                e.stopPropagation();
                //当前标签处于选中状态
                $(this).attr('class','property-select');
                //除当前元素外的所有兄弟li均设为未选中状态
                $(this).siblings('li').attr('class','property-unselect');

                var spu_id = $(this).parent().parent().find('input[name=spu_id]').val();

                $arr = [];
                $(this).parent().parent().find('.property-select').each(function (index, value) {
                    index = $(this).next().val();
                    $str = index;
                    $arr.push($str);
                });
                var data = {
                    "spu_id" : spu_id,
                    "ids" : $arr
                };
//                console.log(data);
                var parent = $(this).parent().parent();
                $.post('/shop/getSku',data,function (data) {
                    //console.log(data);
                    parent.find('.sku_id').each(function () {
                        var sku_this =$(this);
                        sku_this.prev().css({'color':'#333','pointer-events':'auto'});
                        $.each(data['ids'], function (index, item) {
                            if(sku_this.val() == item){
                                sku_this.prev().css({'color':'#c0c0c0','pointer-events':'none'});
                            }
                        })
                    });
                    if(data['type'] == 1){
                        parent.find('#choose-name').text(data['sku']['sku_name']);
                        parent.find('#choose-price').text(data['sku']['price']);
                        parent.find('#choose-img').attr('src',data['sku']['img']);
                    }
                });

            });

            //点击确认
            $('.confirm_click').click(function(){
                var pre = $(this).parent().children(".select-goods-property");
                var select_len =　pre.find('.property-select').length;
                var guige_len = 　pre.children('#guigeming').length;
                if (select_len != guige_len) {
                    message('请检查规格是否选择完整！');
                }else{

                    //获取选择的规格值
                    $arr = [];
                    pre.find('.property-select').each(function (index, value) {
                        index = $(this).next().val();
                        $str = index + 'CONNECTOR' + $(value).text();
                        $arr.push($str);
                    });

                    var spu_id = pre.find('input[name=spu_id]').val();

                    //商品数量
                    var gift_num = $(this).prev().find('input[name=giftNum]').val();
                    $("#spu_id").val(spu_id);
                    $("#spec").val($arr.join('SEPARATOR'));
                    $("#gift_num").val(gift_num);

                    $("#orderConfirm").submit();

                }
            });

        });

    </script>
@endsection

