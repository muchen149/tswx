@extends('inheritance')

@section('title')
    购物车
@endsection

@section('css')
    <title>{!!config('constant')['eLife']['cart']!!}</title>
    <link href="{{asset('css/reset.css')}}" rel="stylesheet">
    <link href="{{asset('css/child.css')}}" rel="stylesheet">
    <link href="{{asset('css/main.css')}}" rel="stylesheet">
    <link href="{{asset('css/header.css')}}" rel="stylesheet">

    <style>
        body{
            background-color: rgb(240,240,240);
        }
        .quantity-wrapper {
            width: 162px;
            margin-top:5px;
            border-radius: 5px;
            height: 24px;
        }
        .quantity-decrease {
            background-position: 10px -20px;
        }
        .quantity-decrease.disabled, .quantity-increase.disabled {
            background-color: #e8e8e8;
            color: #999;
        }
        .quantity-decrease.disabled em, .quantity-increase.disabled em {
            background-position: 10px -45px;
        }
        .quantity {
            -moz-border-bottom-colors: none;
            -moz-border-left-colors: none;
            -moz-border-right-colors: none;
            -moz-border-top-colors: none;
            border-color: rgb(200,200,200);
            border-image: none;
            border-radius: 0;
            border-style: solid;
            border-width: 1px 0;
            color: #232326;
            height: 24px;
            width: 40px;
        }
        .quantity-increase {
        }
        .quantity-decrease, .quantity-increase {
            background: #fff none repeat scroll 0 0;
            border: 1px solid rgb(200,200,200);
            color: #232326;
            display: block;
            line-height: 24px;
            overflow: hidden;
            text-indent: -200px;
            width: 24px;
        }
        .quantity-decrease, .quantity, .quantity-increase {
            float: left;
            font-size: 15px;
            text-align: center;
        }
        .quantity-increase em {
            background: rgba(0, 0, 0, 0) url("/img/cart-number.png") no-repeat scroll 0 0 / 100% auto;
            display: block;
            height: 10px;
            margin: 7px;
            width: 10px;
        }
        .quantity-decrease em {
            background: rgba(0, 0, 0, 0) url("/img/cart-number.png") no-repeat scroll 0 -18px / 100% auto;
            display: block;
            height: 10px;
            margin: 7px;
            width: 10px;
        }
        .display-point{
            background-color: white;
            width: 100%;
            height: 46px;
            border-top: 1px solid rgb(220,220,220);
            border-bottom: 1px solid rgb(220,220,220);
        }
        .display-point p{
            height: 46px;
            line-height: 46px;
            margin-left:20px ;
        }
        .fenge{
            width: 100%;
            height: 10px;
            background-color: rgb(240,240,240);
        }
        .car-btn{
            width: 100%;
            position: fixed;
            bottom:0px;
            display: box;			  /* OLD - Android 4.4- */   display: -webkit-box;	  /* OLD - iOS 6-, Safari 3.1-6 */   display: -moz-box;		 /* OLD - Firefox 19- (buggy but mostly works) */   display: -ms-flexbox;	  /* TWEENER - IE 10 */   display: -webkit-flex;	 /* NEW - Chrome */   display: flex;			 /* NEW, Spec - Opera 12.1, Firefox 20+ */   /* 09版 */   -webkit-box-orient: horizontal;   /* 12版 */   -webkit-flex-direction: row;   -moz-flex-direction: row;   -ms-flex-direction: row;   -o-flex-direction: row;   flex-direction: row;;
            align-items: center;
        }
        .car-btn-left{
            width: 50%;
            height: 50px;
            background-color: #ff5500;
            text-align: center;
        }
        .car-btn-left p{
            height: 50px;
            line-height: 50px;
            color: white;
            font-size: 14px;

        }
        .car-btn-right{
            width: 50%;
            height: 50px;
            background-color:white;
            text-align: center;
            box-sizing: border-box;
            border-top: 1px solid rgb(220,220,220);
        }
        .car-btn-right p{
            height: 50px;
            line-height: 50px;
            color:rgb(100,100,100);
            font-size: 14px;
        }
        .cart-list-selecter{
            float: left;
            margin-top: 35px;
        }
        .cart-list-selecter img{
            width: 22px;
        }

        a:link, a:visited, a:hover, a:active{
            text-decoration:none;
        }
    </style>
@endsection



@section('content')
<body>
    <div id="header"></div>
    <div class="cart-list-wp">
        <input id="usable_points" name="usable_points" type="hidden" value="usable_points"/>
        {{--<input id="user_id" name="user_id" type="hidden" value="user_id"/>--}}

        <div id="cart-list-wp"></div>
        @if(empty($skus))
            <div style="margin:0 auto;height:200px;width:200px;margin-top:50px;text-align: center">
                <img style="width:80%" src="/img/empty_car.png">
                <p class="mt20" style="color:#323232;font-size:14px;">亲，您的购物车空空如也！</p>
                <p class="mt30" style="text-align: center">
                    <a href="/shop/goods/spuList" style="background:#fff;color:#323232;border:1px solid #646464;margin-top:20px;padding:8px 30px;letter-spacing:3px;border-radius: 5px;">去逛逛</a>
                </p>
            </div>
        @else
            {{--若购物车不空--}}
            <ul class="cart-list" style="margin-bottom:60px;">

               @foreach($skus as $sku)
                   {{--@if($sku['state'] !=1)--}}

                    <li class="cart-list-item">
                        <input type="hidden" name="cart_id"  value="{{$sku['cart_id']}}"  />

                        <div class="cart-list-selecter " >
                            {{--@if(1==1)--}}
                                {{--该商品处于选中状态--}}
                                {{--<img src="{{asset('/img/address/address-default-set.png')}}" alt="" is_check="'.$is_check.'" class="eachGoodsCheckBox" cart_id={{"123456"}}>--}}
                            {{--@else--}}
                                <img src="{{asset('/img/address/address-default-pre.png')}}" alt="" is_check="0" class="eachGoodsCheckBox" >
                            {{--@endif--}}
                        </div>
                        {{--<input type="hidden" id="user_id" value="12">--}}
                        <div class="cart-litem-wp clearfix">
                            <a class="cart-litemw-imgwp" href="{{url('/shop/goods/spuDetail/'.$sku['spu_id'])}}">
                                {{--@if (preg_match('/((http|https):\/\/)+(\w+\.)+(\w+)[\w\/\.\-]*(jpg|gif|png|JPG|PNG|GIF|JPEG)/',"img"))--}}
                                    {{--<img src="//gw.alicdn.com/imgextra/i3/147/TB22Po8cheI.eBjSsplXXX6GFXa_!!147-0-yamato.jpg"/>--}}
                                {{--@else--}}
                                    <img src="{{asset($sku['main_img'])}}"/>
                                {{--@endif--}}
                            </a>
                            <div class="cart-litemw-cnt" cart_id="{{$sku['cart_id']}}">
                                <a class="cart-litemwc-pdname" href="{{url('/shop/goods/spuDetail/'.$sku['spu_id'])}}">{{$sku['sku_name']}}</a>
                                <p class="mt5" style="color:#8e8e8e ;font-size:12px;">
                                    @if( !empty($sku['sku_spec']) )
                                        规格:
                                        @foreach($sku['sku_spec'] as $guige)
                                            &nbsp;{{$guige}}
                                        @endforeach

                                    @endif
                                </p>
                                <p class="mt5" style="color:#f15353;font-size:14px;">
                                    ￥&nbsp;<span class="goods-total-price">{{$sku['price']}}</span>
                                </p>
                                <div class="quantity-wrapper">
                                    <a  href="javascript:void(0)" class="quantity-decrease quantityDecrease">
                                        <em id="minus">-</em>
                                    </a>
                                    <input type="text" name="b_num_input" class="quantity" p_integral="12" min_limit="{{$sku['minimum_limit']}}" value="{{$sku['number']}}" size="4" style="height: 26px;" cart_id="{{$sku['cart_id']}}" />
                                    <a  href="javascript:void(0)" class="quantity-increase quantityPlus">
                                        <em id="plus">+</em>
                                    </a>
                                    {{--<input class="skuid" type="hidden" value="{{$sku['sku_id']}}" >--}}
                                        <span class="cart-list-del" cart_id="{{$sku['cart_id']}}">
                                             <img src="{{asset('/img/delete.png')}}" style="width:20px;padding-top: 5px">
                                        </span>
                                </div>
                            </div>
                        </div>
                    </li>
                  {{--@endif--}}
               @endforeach
            </ul>
            <div class="car-btn">
                <div class="car-btn-right">
                    <p><img src="{{asset('/img/address/address-default-pre.png')}}" class="selectAll" is_all="0" style="float: left;width: 22px;margin-top:12px;margin-left:7px;margin-right: 4px">
                        <span style="float: left;width: 36px;height: 26px;display:block">全选</span>
                        合计：<span class="clr-d94">¥</span><span class="clr-d94 total_price">0</span></p>
                </div>
                <div class="car-btn-left">
                    <a href="javascript:void(0)" id="goto-settlement"><p>去结算</p></a>
                </div>
            </div>

        @endif
    </div>

    <!------------------------form提交数据生成表单  START!---------------------------->
    <form action="{{asset('/elife/order/showPay')}}" method="post" id="jsform">
        <input type="hidden" name="cartIds" id="cartIds">
        <input type="hidden" name="sku_source_type" value="1">
    </form>
    <!--======================form提交数据生成表单  END!============================-->


</body>

<script>
    $(function(){
//        var check_is_all = 1;
//        //跳到购物车列表时，先遍历购物车列表中商品是不是全部都选中，若都选中，则全选按钮也处于全选中状态，否则全选按钮处于默认未选中状态
//        $('.eachGoodsCheckBox').each(function(){
//            is_check = $(this).attr('is_check');
//            if(is_check == 0){ //若有一个处于未选中状态就推出
//                check_is_all = 0;
//            }
//        });
//
//        if(check_is_all == 1){ //没有改变说明商品都出选中状态，因此全选按钮处于全选状态
//            $(".selectAll").attr('src','{{asset('/img/address/address-default-set.png')}}');
//            $(".selectAll").attr('is_all',1);
//        }


        function change_is_all() { //遍历购物车列表中商品是不是全部都选中，若都选中，则全选按钮也处于全选中状态，否则全选按钮处于未选中状态

            var flags = 1;
            //跳到购物车列表时，先遍历购物车列表中商品是不是全部都选中，若都选中，则全选按钮也处于全选中状态，否则全选按钮处于默认未选中状态
            $('.eachGoodsCheckBox').each(function(){
                is_check = $(this).attr('is_check');
                if(is_check == 0){ //若有一个处于未选中状态就推出
                    flags = 0;
                }
            });

            if(flags == 1){ //没有改变说明商品都出选中状态，因此全选按钮处于全选状态
                $(".selectAll").attr('src','{{asset('/img/address/address-default-set.png')}}');
                $(".selectAll").attr('is_all',1);
                return;
            }else{ //有商品处于未选中转态，全选按钮处于未选中状态
                $(".selectAll").attr('src','{{asset('/img/address/address-default-pre.png')}}');
                $(".selectAll").attr('is_all',0);
                return;
            }


        }





        $('.eachGoodsCheckBox').on('click', function () {

            var goods_num = $(this).parents("li").find(".quantity").val(); //获得商品数量

            var goods_points = $(this).parents("li").find(".goods-total-price").text(); //获得商品单价

            var good_amount = (parseInt(goods_num) * parseInt(goods_points * 100)) / 100;

            //获得商品总金额
            var total_price = $(".total_price").text();


            var cart_id = $(this).attr('cart_id');
            var is_check = $(this).attr('is_check'); //该商品是否被选中，0未选中，1选中

            var change_check = 0;

            if( is_check == 0)//若当前购物车未选中，则点击后商品选中，否则不选中
            {
                $(this).attr('src','{{asset('/img/address/address-default-set.png')}}');
                $(this).attr('is_check',1);

                change_is_all(); //更改全选状态

                //选中商品时，把选中的商品的单价*个数得到的总金额加入到总金额中
                total_price = ( parseInt(total_price * 100) + parseInt(good_amount * 100) ) / 100;
                $(".total_price").text(total_price);
                change_check = 1;
            }else{
                $(this).attr('src','{{asset('/img/address/address-default-pre.png')}}');
                $(this).attr('is_check',0);

                //若有一个商品处于未选中，则全选按钮就处于为选中状态
                $(".selectAll").attr('src','{{asset('/img/address/address-default-pre.png')}}');
                $(".selectAll").attr('is_all',0);


                //取消商品时，把商品的单价*个数得到的总金额从总金额中减去
                total_price = parseInt(total_price * 100) - parseInt(good_amount * 100);
                result_price = total_price / 100;
                $(".total_price").text(result_price);
                change_check = 0;
            }

        });

        //全选按钮
        $('.selectAll').on('click', function () {
            //判断点击全选按钮时，是全部取消还是全部选择
//            var user_id = $("#user_id").val();
            var is_all = $(this).attr('is_all');
            var change_check = 0;
            var total_price= 0;
            if(is_all == 0){ //若全部选中，则遍历所有按钮为选中
                $(this).attr('is_all','1');
                $(this).attr('src','{{asset('/img/address/address-default-set.png')}}');
                change_check = 1;
                $('.eachGoodsCheckBox').each(function(){
                    $(this).attr('src','{{asset('/img/address/address-default-set.png')}}');
                    $(this).attr('is_check',1);
                    //统计全部选中的商品总积分
                    var goods_price = parseFloat($(this).parents("li").find(".goods-total-price").text()); //商品价格
                    var goods_num = parseInt($(this).parents("li").find(".quantity").val()); //获得商品数量
                    var goods_amount = parseInt(goods_price * 100) * goods_num;
                    total_price = total_price  + goods_amount ;
                });
                $r = total_price / 100;
                $(".total_price").text($r);

            }else{ //全部取消，则所有全部按钮取消
                $(this).attr('is_all','0');
                $(this).attr('src','{{asset('/img/address/address-default-pre.png')}}');
                change_check = 0;

                $('.eachGoodsCheckBox').each(function(){
                    $(this).attr('src','{{asset('/img/address/address-default-pre.png')}}');
                    $(this).attr('is_check',0);
                });

                $(".total_price").text(0); //全部取消总积分为0
            }

        });

    });


</script>


@endsection

@section('js')
    <script type="text/javascript" src="{{asset('elife_js/zepto.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('elife_js/simple-plugin.js')}}"></script>

    <script type="text/javascript" src="{{asset('elife_js/common-top.js')}}"></script>
    <script type="text/javascript" src="{{asset('elife_js/common.js')}}"></script>
    <script type="text/javascript" src="{{asset('elife_js/cart/cart-list.js')}}"></script>

@endsection
