@extends('inheritance')
@section('css')
    {{--页面头部导航--}}
    <link href="{{asset('css/header.css')}}" rel="stylesheet">

    <link rel="stylesheet" href="{{asset('css/swiper.min.css')}}" type="text/css">

    <link href="{{asset('css/order/order_index.css')}}" rel="stylesheet">
    <link href="{{asset('css/footer.css')}}" rel="stylesheet">
    <style>
        .col-xs-12{
            padding-left: 0!important;
            padding-right: 0!important;
        }
        .col-xs-12 p{
            margin-left: 10px;
            margin-right: 10px;
        }
        .pay-style{
            width: 100%;
            background-color: rgb(245,245,245);
        }
        .pay-style ul{
            width: 95%;
            margin: 0 auto;
            height: 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgb(230,230,230);
            font-size: 12px;
            color: rgb(80,80,80);
        }
        .pay-style ul li img{
            width: 20px;

        }

        .hide-class{
            display:none;
        }

        .img-spin{
            transform:rotate(90deg) !important;
            transition:transform 0.5s linear;

        }

        .img-more{
            transform:rotate(0deg);
            transition:transform 0.5s linear;
        }

    </style>

@endsection
@section('title')
    确定订单
@endsection
@section('content')
    <body id="submitOrder" class="col-xs-12">
    <div id="header"></div>
    <!------------------------地址模块  START!---------------------------->
    <div class="address col-xs-12" style = "height:80px; background-color: white">
        @if($address_info->isEmpty())
            <div class="add-address-btn add-address">
                <span class="glyphicon glyphicon-plus blue-one tianjia"></span>
                <span class="f14 fz">新增收货地址</span>
                <img class="right-btn" src="{{asset('img/right-btn.svg')}}" height="16" width="16"/>
            </div>
            <input class="default_is_who" type="hidden" value="{{$is_hasAddress}}"/>
            <div class="col-xs-12 default-address-hidden default-address-xq">
                <div class="col-xs-1 ">
                    <img class="address-img" src="{{asset('img/address.svg')}}" height="22" width="22"/>
                </div>
                <div class="col-xs-10">
                    <div class="col-xs-6">
                        <span>收货人：</span> <span class="m-shr"></span>
                    </div>
                    <div class="col-xs-5 text-right m-sj"></div>
                    <div class="col-xs-12 default-address">
                        收货地址：<span class="m-sjdz"></span><span class="m-dz"></span>
                    </div>
                </div>
                <div class="col-xs-1">
                    <img class="right-btn" src="{{asset('img/right-btn.svg')}}" height="16" width="16"/>
                </div>
            </div>
        @else
            <div class="col-xs-12 default-address-xq" style="background-color: white">
                <div class="col-xs-1" style="margin-top:4px;margin-left: 3%">
                    <img class="address-img" src="{{asset('img/address.svg')}}" height="22" width="22"/>
                </div>
                <div class="col-xs-10" style="padding-left:5px;width: 78%">
                    <div class="col-xs-6" style="width: 60%">
                        <span >收货人：</span> <span class="m-shr">{{$default_address->recipient_name}}</span>
                    </div>
                    <div class=" text-right m-sj" style="text-align: left;">
                        {{$default_address->mobile}}
                    </div>
                    {{-- <div class="col-xs-1">

                     </div>--}}
                    <div class="col-xs-12 default-address" style="margin-top: 4px;">
                        收货地址：
                        <span class="m-sjdz">{{$default_address->area_info}}</span>
                        <span class="m-dz">{{$default_address->address}}</span>
                    </div>
                </div>
                <div class="col-xs-1" style="width: 10%">
                    <img class="right-btn" src="{{asset('img/right-btn.svg')}}" height="16" width="16"/>
                </div>
            </div>
        @endif
    </div>
    <div class="clearfix"></div>
    <!--======================地址模块  END!============================-->

    <!------------------------分割线  START!---------------------------->
    {{--<div class="col-xs-12 fengexian"></div>--}}
    <!--======================分割线  END!============================-->

    <!------------------------订单商品详情  START!---------------------------->
    <div class="col-xs-12 order-detail">

        @foreach($skus_info as $sku)
            {{--<div class="col-xs-12 orderContentXq">
                <input type="hidden" value="{{$sku['sku_id']}}" class="skuId"/>
                <input type="hidden" value="{{$sku['number']}}"/>
                <input type="hidden" value="{{empty($sku['is_fastBuy']) ? 0 : 1}}" class='fastBuy'/>
                <div class="m-media-left">
                    <img src="{{asset($goods['sku_image'])}}" alt="..."><!--@todo:如果是图片，显示描述-->
                    <div class="media-xq">
                        <p class="f12">{{$goods['sku_name']}}</p>
                        <p class="f10 fzq">
                            @foreach($goods['sku_spec'] as $spec)<span>{{$spec}}&nbsp;</span>@endforeach
                        </p>
                    </div>
                </div>
                <div class="m-media-right">
                    <div class="detail">
                        <p class="f12">￥{{$goods['settlement_price']}}</p>
                        <p class="f12 fzq">&times;{{$goods['number']}}</p>
                    </div>
                </div>
            </div>--}}
            <div class="col-xs-12 goods-order">
                <input type="hidden" value="{{$sku['sku_id']}}" class="skuId"/>
                <input type="hidden" value="{{$sku['number']}}"/>
                <input type="hidden" value="{{!empty($sku['price']) ? $sku['price'] : 0}}"/>
                <input type="hidden" value="{{!empty($sku['promotions_type']) ? $sku['promotions_type'] : 1}}"/>
                <input type="hidden" value="{{!empty($sku['promotions_id']) ? $sku['promotions_id'] : 0}}"/>
                <div class=" goods-xiangqing">

                    <img class="goods-img" src="{{asset($sku['main_img'])}}">

                    <div class="sure-detail">
                        <p class="goods-ming f12 fz">{{$sku['sku_name']}}</p>
                        <p class="guige">
                            <span class="f12 fzq">
                                {{empty($sku['sku_spec']) ? '' : '规格：'}}</span>
                            @foreach($sku['sku_spec'] as $guige)
                                <span class="f12 fzq">{{$guige}}</span>
                            @endforeach
                        </p>
                    </div>
                </div>
                <div class="money">
                    <div class="detail">
                        <p class="jiaqian">¥&nbsp;{{$sku['price']}}</p>
                        <p class="f12 fzq "><span>&times;</span> <span id="number">{{$sku['number']}}</span></p>
                    </div>
                </div>

            </div>
        @endforeach

        <div class="yunfei col-xs-12 text-right">
            <p><span class="yunfont">运费</span><span class="jiaqian">¥&nbsp;{{$fare_total}}</span></p>
        </div>
        <div class="clearfix"></div>
    </div>
    <!------------------------订单金额展示  START!---------------------------->
    <div class="col-xs-12 shanghang"style="background-color:white">
        <div class="col-xs-6 text-left" >
            <span style="margin-left: 10px">总金额（含运费）：</span>
        </div>
        <div class="col-xs-6 jiaqian text-right">
            ¥&nbsp;<span style="margin-right: 10px" id="payable_amount">{{$payable_amount}}</span>
        </div>
    </div>
    <!--======================订单金额展示  END! ============================-->


    <!------------------------分割线  START!---------------------------->
    <div class="col-xs-12 fengexian" style="border-top:0;border-bottom:0;height:5px;"></div>
    <!--======================分割线  END!============================-->

    <!---------------------------虚拟币  START!---------------------------->
    @if($grade == 20 || $grade == 30)
        <div class="col-xs-12 pay" >

            {{--<div id="chose_pay" chose_pay_id="0" class=" hang jbky" style="display: flex;justify-content: space-between;align-items: center;">--}}
                {{--<div style="margin-left: 10px;">--}}
                    {{--<span id="pay_name">请选择支付方式</span>--}}
                {{--</div>--}}
                {{--<div style="--}}
                 {{--margin-right: 2%;--}}
                {{--width: 10%;--}}
                {{--text-align: right;--}}
                {{--">--}}
                    {{--<img src="{{asset('img/right-btn.svg')}}" style="width: 22px" class="img-more">--}}
                {{--</div>--}}
            {{--</div>--}}

             <div class="pay-style " id="chose_pay" chose_pay_id="0">
                 @if($payment_type)
                     @foreach($payment_type as $payment)
                         <ul id="{{$payment['id']}}" class="pay_ul" is_chose="0">
                             <li class="p_name">{{$payment['name']}}</li>
                             <li><img src="{{asset('/img/defau.png')}}" alt=""></li>
                         </ul>
                     @endforeach
                 @endif

             </div>

            @if(isset($my_money_info['money_wallet']))
                <div class="col-xs-12 hang jbky " id="wallet_pay" hidden>
                    <div class="col-xs-2 ">
                        <span style="margin-left: 10px;">零钱</span>
                    </div>
                    <div class="col-xs0 " style="font-size: 13px;">
                    <span id="n_ling">
                        可用：¥&nbsp;<span style="color: #ff5500" id="max_ling">{{$my_money_info['money_wallet']['available']}}</span>
                    </span>

                    <span id="y_ling" hidden>
                        本次使用：¥&nbsp;<span style="color: #ff5500" id="use_ling">0</span>
                    </span>

                        <span><img id="ling_switch" style="margin-top:15px;height: 20px;float: right;margin-right:10px;" tag="0" src="/img/switch-off.png"></span>

                    </div>
                </div>
                <div class="clearfix"></div>
            @endif

            @if(isset($my_money_info['money_card_balance']))
                <div class="col-xs-12 hang jbky" id="ka-yu-e" hidden >
                    <div class="col-xs-2 ">
                        <span style="margin-left: 10px;margin-right: 5px">卡余额</span>
                    </div>
                    <span id="total_card_num" style="font-size: 13px;">共&nbsp;<span style="color: #ff5500" id="card_num">{{$my_money_info['money_card_balance']['count']}}</span>&nbsp;张卡券可用，本次使用了&nbsp;<span style="color: #ff5500" id="used_card_num">0</span>&nbsp;张</span>
                    <img style="float: right;height: 51px;margin-right: 10px" id="ka-yu-e-right-btn" src="{{asset('img/right-btn.svg')}}" height="16" width="16"/>
                </div>
                <div class="clearfix"></div>
            @endif

            @if(!empty($expire_arr))

                <div class="col-xs-12 shanghang" style="background-color:white" id="zq_time" hidden>
                    <div class="col-xs-6 text-left" >
                        <span style="margin-left: 10px">账期支付延迟时间</span>
                    </div>
                    <div class="col-xs-6 jiaqian text-right">
                        <span  id="expire_time">{{$expire_arr['expire_time']}}</span>
                        <span style="margin-right: 10px">天</span>
                    </div>
                </div>
                <div class="clearfix"></div>

            @endif

        </div>
    @else
        <div class="col-xs-12 pay">
            @if(isset($my_money_info['money_vrb']))
                <div class="col-xs-12 hang jbky">
                    {{--虚拟币换算率 1元 = plat_vrb_rate虚拟币--}}
                    <input type="hidden" value="{{$my_money_info['money_vrb']['plat_vrb_rate']}}" id="plat_vrb_rate"/>
                    <div class="col-xs-2 ">
                        <span style="margin-left: 10px;">{{$my_money_info['money_vrb']['plat_vrb_caption']}}</span>

                    </div>
                    <div class="col-xs0 " style="font-size: 13px;">
                    <span id="n_use" >
                        余额：<span style="color: #ff5500" id="goods_points_totals">{{$my_money_info['money_vrb']['available']}}</span>&nbsp;,
                        @if($my_money_info['money_vrb']['available'] < $my_money_info['money_vrb']['plat_vrb_rate'])
                            满&nbsp;<span style="color: #ff5500">{{$my_money_info['money_vrb']['plat_vrb_rate']}}</span>&nbsp;{{$my_money_info['money_vrb']['plat_vrb_caption']}}可用
                        @else
                            本次最多可用：<span id="my_vrb_available" style="color: #ff5500">{{$my_money_info['money_vrb']['pay_max_amount']}}</span>
                        @endif

                    </span>

                     <span id="y_use" hidden >
                        本次使用：<span style="color: #ff5500" id="this_used">{{$my_money_info['money_vrb']['pay_max_amount']}}</span>&nbsp;
                        抵：¥&nbsp;<span  style="color: #ff5500" id="di_rmb">{{$my_money_info['money_vrb']['pay_max_amount_to_rmb']}}</span>
                    </span>

                        @if($my_money_info['money_vrb']['available'] < $my_money_info['money_vrb']['plat_vrb_rate'])
                            <span ><img  style="margin-top:15px;height: 20px;float: right;margin-right:10px;"  src="/img/gantanhao.png"></span>
                        @else
                            <span ><img id="switch" style="margin-top:15px;height: 20px;float: right;margin-right:10px;" use="0" src="/img/switch-off.png"></span>
                        @endif

                        {{--<span>我的{{$plat_vrb_caption}}余额：<span id="my_vrb_available" style="color: #ff5500">{{$my_vrb_available}}</span></span>--}}
                    </div>
                </div>
                <div class="clearfix"></div>
            @endif

            @if(isset($my_money_info['money_wallet']))
                <div class="col-xs-12 hang jbky">
                    <div class="col-xs-2 ">
                        <span style="margin-left: 10px;">零钱</span>
                    </div>
                    <div class="col-xs0 " style="font-size: 13px;">
                    <span id="n_ling">
                        可用：¥&nbsp;<span style="color: #ff5500" id="max_ling">{{$my_money_info['money_wallet']['available']}}</span>
                    </span>

                    <span id="y_ling" hidden>
                        本次使用：¥&nbsp;<span style="color: #ff5500" id="use_ling">0</span>
                    </span>

                        <span><img id="ling_switch" style="margin-top:15px;height: 20px;float: right;margin-right:10px;" tag="0" src="/img/switch-off.png"></span>

                    </div>
                </div>
                <div class="clearfix"></div>
            @endif

            @if(isset($my_money_info['money_card_balance']))
                <div class="col-xs-12 hang jbky" id="ka-yu-e">
                    <div class="col-xs-2 ">
                        <span style="margin-left: 10px;margin-right: 5px">卡余额</span>
                    </div>
                    <span id="total_card_num" style="font-size: 13px;">共&nbsp;<span style="color: #ff5500" id="card_num">{{$my_money_info['money_card_balance']['count']}}</span>&nbsp;张卡券可用，本次使用了&nbsp;<span style="color: #ff5500" id="used_card_num">0</span>&nbsp;张</span>
                    <img style="float: right;height: 51px;margin-right: 10px" id="ka-yu-e-right-btn" src="{{asset('img/right-btn.svg')}}" height="16" width="16"/>
                </div>
                <div class="clearfix"></div>
            @endif

            @if(isset($my_money_info['youhuiquan']))
                <div class="col-xs-12  jbky">
                    <div class="col-xs-2 ">
                        <span style="margin-left: 10px;">优惠券</span>
                    </div>
                    <span style="font-size: 13px;">共&nbsp;<span style="color: #ff5500">aa</span>&nbsp;张优惠券可用</span>
                    <img style="float: right;height: 51px;;" class="right-btn" src="{{asset('img/right-btn.svg')}}" height="16" width="16"/>
                </div>
            @endif


        </div>
    @endif

    <!---------------------------虚拟币  END!---------------------------->

    <!--======================分割线  START!======================--->
    {{--<div class="col-xs-12 fengexian" style="border-top:0;border-bottom:0"></div>--}}
    <!--======================分割线  END!============================-->

    <!--======================订单商品详情  END!============================-->

    <!------------------------商家留言模块  START!---------------------------->
    <form role="form">
        <div class="form-group col-xs-12 liuyan" >
            <textarea class="form-control" rows="3" placeholder="给商家留言" autocomplete="off" style="box-shadow: inset 0 0px 0px rgb(255,255,255);background-color: white;margin-top: 4px;"></textarea>
        </div>
    </form>
    <!--======================商家留言模块  END!============================-->
    <div class="clearfix"></div>
    <!------------------------分割线  START!---------------------------->
    {{--<div class="col-xs-12 fengexian"></div>--}}
    <!--======================分割线  END!============================-->

    <!------------------------分割线  START!---------------------------->
    {{--<div class="col-xs-12 fengexian"></div>--}}
    <!--======================分割线  END!============================-->


    <div class="clearfix"></div>

    <!------------------------页脚去支付  START!---------------------------->
    {{--<div class="footer-zhifu">--}}
        {{--<div class="col-xs-8 text-left jiaqian " style="margin-left: 11px;width: 63.666667%">--}}
            {{--<span>合计：</span><span>￥</span><span id="zhifu-total">{{$pay_rmb}}</span>--}}
        {{--</div>--}}
        {{--<div class="col-xs-4 text-center f18 a-style zf-btn" >--}}
            {{--去支付--}}
        {{--</div>--}}
    {{--</div>--}}

    <div class="footer-zhifu" style="display: flex;align-items: center">
        <div class="col-xs-8 text-left jiaqian " style="width: 63%;background-color: white">
            <span style="margin-left: 11px;color: rgb(80,80,80)">合计：¥&nbsp;</span>
            <span id="zhifu-total">{{$pay_rmb}}</span>
{{--            --}}
        </div>
        <div class="col-xs-4 text-center f18 a-style zf-btn " style="width: 37%">
            提交订单
        </div>
    </div>
    <!--======================页脚去支付  END!============================-->

    <!------------------------新增收货地址  START!---------------------------->
    <form role="form">
        <div class="xinzeng col-xs-12">
            <div class="title-zeng">
                <p class="text-center">新增收货地址</p>
                <div class="close-btn close-set-btn" id="add-new-add">
                    <img src="{{asset('img/m-close.svg')}}" height="20" width="20"/>
                </div>
            </div>
            <div class="zeng col-xs-12">
                <div class="col-xs-12 input-dan">
                    <div class="col-xs-4" >
                        <span style="margin-left: 7px">收货人</span>
                    </div>
                    <div class="col-xs-8">
                        <input type="text" class="name" placeholder="名字" autocomplete="off">
                    </div>
                </div>
                <div class="col-xs-12 input-dan">
                    <div class="col-xs-4" >
                        <span style="margin-left: 7px">联系电话</span>
                    </div>
                    <div class="col-xs-8">
                        <input type="text" class="phone" placeholder="手机或固定电话" autocomplete="off">
                    </div>
                </div>
                <div class="col-xs-12 input-dan">
                    <div class="col-xs-4">
                         <span style="margin-left: 7px">选择地区</span>
                    </div>
                    <div class="col-xs-8" style="width: 61.6%;text-align: right">
                        {{-- <input type="text" id="choose-address"/>--}}
                        <p id="choose-address"></p>
                        <input type="hidden" id="province"/>
                        <input type="hidden" id="city"/>
                        <input type="hidden" id="area"/>
                    </div>
                </div>

                <div class="col-xs-12 input-dan">
                    <div class="col-xs-4">
                        <span style="margin-left: 7px">详细地址</span>
                    </div>
                    <div class="col-xs-8">
                        <input type="text" class="address-info" placeholder="街道门牌，无需重复地区信息" autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="baocun  col-xs-12 f18 text-center" style="background-color: #f23030">
                保存
            </div>
        </div>
    </form>
    <!--======================新增收货地址  END!============================-->


    <div class="clearfix"></div>
    <!------------------------编辑收货地址  START!---------------------------->
    <div class="edit col-xs-12">
        <div class="title-edit">
            <p class="text-center">编辑收货地址</p>
            <div class="close-btn close-edit-btn">
                <img src="{{asset('img/m-close.svg')}}" height="20" width="20"/>
            </div>
        </div>
        <div class=" col-xs-12">
            <div class="col-xs-12 input-dan">
                <div class="col-xs-4" >
                    <span style="margin-left: 7px;">收货人</span>
                </div>
                <div class="col-xs-8">
                    <input type="text" class="name edit-shr" placeholder="名字" autocomplete="off">
                </div>
            </div>
            <div class="col-xs-12 input-dan">
                <div class="col-xs-4" >
                    <span style="margin-left: 7px;">联系电话</span>
                </div>
                <div class="col-xs-8">
                    <input type="text" class="phone edit-sj" placeholder="手机或固定电话" autocomplete="off">
                </div>
            </div>
            <div class="col-xs-12 input-dan">
                <div class="col-xs-4" >
                    <span style="margin-left: 7px;">选择地区</span>
                </div>
                <div class="col-xs-8" style="width: 61.6%;text-align: right">
                    {{-- <input type="text" id='city-picker' class="edit-pc"/>--}}
                    <p class="edit-pc" style="margin-left: 0px;"></p>
                    <input type="hidden" value="" class="edit-province"/>
                    <input type="hidden" value="" class="edit-city"/>
                    <input type="hidden" value="" class="edit-area"/>
                </div>
            </div>

            <div class="col-xs-12 input-dan">
                <div class="col-xs-4" >
                    <span style="margin-left: 7px;">详细地址</span>
                </div>
                <div class="col-xs-8">
                    <input type="text" class="address-info edit-xx" placeholder="街道门牌，无需重复地区信息" autocomplete="off">
                </div>
            </div>
        </div>
        <div class="col-xs-12 footer-btn">
            <div class="blue-one col-xs-8 f18 text-center edit-save ">
                保存
            </div>
            <div class="col-xs-4 f18 text-center edit-del" style="background-color: red;color: white">
                删除
            </div>
        </div>

    </div>
    <!--======================编辑收货地址  END!============================-->

    <div class="clearfix"></div>

    <!------------------------地址详情列表  START!---------------------------->
    <div class="xq-lb">
        <div class="title-zeng">
            <p class="text-center">选择收货地址</p>
            <div class="close-btn close-choose-btn" style="right: 6%">
                <img src="{{asset('img/m-close.svg')}}" height="20" width="20"/>
            </div>
        </div>

        <div class="col-xs-12 addressContent">
            @foreach($address_info as $address)
                <div class="col-xs-12 xuanzekuang">
                    <div class="col-xs-10 xuanzhong">
                        <div class="col-xs-1 radio">
                            @if($address->is_default ==1)
                                <label>
                                    <input type="radio" name="radio" checked="checked" value="" autocomplete="off"
                                           class="radio-op">
                                    <input type="hidden" value="{{$address->address_id}}">
                                    <input type="hidden" value="{{$address->is_default}}" class="default">
                                    <p class="checkbox-style checkbox-visited"></p>
                                </label>
                            @else
                                <label>
                                    <input type="radio" name="radio" value="" autocomplete="off" class="radio-op">
                                    <input type="hidden" value="{{$address->address_id}}">
                                    <input type="hidden" value="{{$address->is_default}}" class="default">
                                    <p class="checkbox-style"></p>
                                </label>
                            @endif
                        </div>
                        <div class="col-xs-11 xiangqinglan">
                            <span class="shr">{{$address->recipient_name}}</span>&nbsp;<span
                                    class="sj">{{$address->mobile}}</span>
                            <p class="fzq f12" style="margin-left: 0">
                                <span class="dz" style="text-align: left">
                                    收货地址：
                                    <span class="p-c">{{$address->area_info}}</span>
                                    <span class="xx">{{$address->address}}</span>
                                    <input type="hidden" value="{{$address->province_id}}" class="detail-province"/>
                                    <input type="hidden" value="{{$address->city_id}}" class="detail-city"/>
                                    <input type="hidden" value="{{$address->area_id}}" class="detail-area"/>
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="col-xs-2 edit-btn text-center">
                        <img src="{{asset('img/edit.svg')}}" height="24" width="24"/>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="add-address-btn col-xs-12 model-add">
            <div class="col-xs-1">
                <p class="model-add-btn"></p>
            </div>
            <span class="f16 fz col-xs-10" style="margin-left:9px;">新增收货地址</span>
            <div class="col-xs-1 text-right">
                {{--<img src="{{asset('img/right-btn.svg')}}" height="16" width="16"/>--}}
            </div>
        </div>
    </div>

    <!--======================地址详情列表  END!============================-->


    <!------------------------阴影效果  START!---------------------------->
    <div class="xiaoguo">

    </div>
    <!--======================阴影效果  END!============================-->

    <div class="col-xs-12 choose-address">

        <div class="hang title col-xs-12">
            选择收货地址
            <div class="address-sure-btn">
                <p>确定</p>
            </div>
        </div>
        <div class="col-xs-12 address-module">
            <div class="module-top">

            </div>
            <div class="module-bottom">

            </div>
            <div class="col-xs-4 choose">
                <div class="swiper-container swiper-container1">

                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <span>-请选择-</span>
                        </div>
                        @foreach($province_dct as $p)
                            <div class="swiper-slide">
                                <span class="one-address">{{$p['name']}}</span>
                                <input type="hidden" class="one-id" value="{{$p['id']}}"/>
                                <input type="hidden" class="one-pid" value="{{$p['pid']}}"/>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>
            <div class="col-xs-4 choose">
                <div class="swiper-container swiper-container2">
                    <div class="swiper-wrapper swiper-wrapper2">
                        <div class="swiper-slide">
                            <span>-请选择-</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-4 choose">
                <div class="swiper-container swiper-container3">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <span>-请选择-</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>

    <!--*********************卡余额  start!*********************-->
    @if(isset($my_money_info['money_card_balance']))
        <div class="xq-lb-new" id="ka-yu-e-select">
            <div class="title-zeng-new">
                <p class="text-center">卡余额选择</p>
                <div class="close-btn-new " id="ka_yu_e_close">
                    <img src="{{asset('img/m-close.svg')}}" height="20" width="20"/>
                </div>
            </div>

            <div class="col-xs-12 addressContent-new">

                @foreach($my_money_info['money_card_balance']['card_lst'] as $card_lst)
                    <div class="card-list to-chose-card" chose="0">
                        {{--<input type="hidden" name="use_money" class="use_money" value="0"/>--}}

                        <input type="hidden" name="rechargecard_id" class="rechargecard_id" value="{{$card_lst['rechargecard_id']}}"/>
                        <input type="hidden" name="card_id" class="card_id" value="{{$card_lst['card_id']}}"/>

                        <input type="hidden" name="balance_amount" class="balance_amount" value="{{$card_lst['balance_amount']}}"/>
                        <input type="hidden" name="balance_available" class="balance_available" value="{{$card_lst['balance_available']}}"/>
                        <input type="hidden" name="balance_pay_amount" class="balance_pay_amount" value="0"/>

                        <div class="select">
                            <img class="select_img" src="/img/defau.png"  alt="">
                        </div>

                        <div class="czcard-wrap">
                            <div class="czcard color3" >
                                <div class="czcard-mess">
                                    <div>
                                        <img src="/img/logo_xns.png" alt="logo">
                                    </div>
                                    <div >
                                        <p >面值: ¥&nbsp;<span class="bold">{{$card_lst['balance_amount']}}</span> &nbsp;&nbsp;&nbsp;余额: ¥&nbsp;<span class="bold">{{$card_lst['balance_available']}}</span></p>
                                    </div>
                                </div>
                                {{--<div class="czcard-time">--}}
                                    {{--<p>2017-1-1</p>--}}
                                {{--</div>--}}

                            </div>
                        </div>
                    </div>

                @endforeach
                <div>
                        <p style="padding-top: 21px;"></p>
                </div>
            </div>
        </div>
        <!--*********************卡余额  start!*********************-->
        <!------------------------阴影效果  START!---------------------------->
        <div class="ka-yu-e-xiaoguo">
        </div>
        <!--======================阴影效果  END!============================-->
    @endif



    <form >
        <input type="hidden" value="{{$sku_source_type}}" class='sku_source_type'/>
    </form>
    </body>


@endsection

@section('js')
{{--页面头部所需js--}}
    <script type="text/javascript" src="{{asset('js/common-top.js')}}"></script>

    <script src="{{asset('js/common.js')}}"></script>

    <script src="{{asset('js/submitOrder.js')}}"></script>
    <script>
        /* $(document).ready(function () {
         $("#city-picker").cityPicker({
         toolbarTemplate: '<header class="bar bar-nav">\
         <button class="button button-link pull-right close-picker">确定</button>\
         <h1 class="title">选择收货地址</h1>\
         </header>'
         });
         })*/
    </script>
    <script>
        $(document).ready(function () {
            /* var mySwiper1 = new Swiper('.swiper-container1', {
             direction : 'vertical',
             slidesPerView :'auto',
             centeredSlides : true,
             observer:true,//修改swiper自己或子元素时，自动初始化swiper
             observeParents:true,//修改swiper的父元素时，自动初始化swiper

             onSlideChangeEnd: function(swiper){
             // $('.swiper-wrapper2').css('transform', 'translate3d(0px, 105px, 0px)');
             var one_id = $('.swiper-slide-active').find('.one-id').val();
             console.log(one_id);
             $('.swiper-container2').find('.swiper-wrapper').html('<div class="swiper-slide">'+
             '<span>-请选择-</span>'+
             ' </div>');
             $.get('/address/child/'+one_id,function (data) {
             // console.log(data);
             var str = '<div class="swiper-slide">'+
             '<span>-请选择-</span>'+
             ' </div>';

             $.each(data['data'], function (index, item) {
             str += '<div class="swiper-slide">'+
             ' <span>'+item['name']+'</span>'+
             '<input type="hidden" class="two-id" value="'+item['id']+'"/>'+
             ' <input type="hidden" class="two-pid" value="'+item['pid']+'"/>'+
             '</div>';

             });

             $('.swiper-container2').find('.swiper-wrapper').html(str);
             $('.swiper-container3').find('.swiper-wrapper')
             .html('<div class="swiper-slide">'+
             '<span>-请选择-</span>'+
             ' </div>');

             })
             }
             });*/

        });

        /*var mySwiper2 = new Swiper('.swiper-container2', {

         direction : 'vertical',
         slidesPerView :'auto',
         centeredSlides : true,
         observer:true,//修改swiper自己或子元素时，自动初始化swiper
         observeParents:true,//修改swiper的父元素时，自动初始化swiper

         onSlideChangeEnd: function(swiper){
         $('.swiper-container3').find('.swiper-wrapper').html('<div class="swiper-slide">'+
         '<span>-请选择-</span>'+
         ' </div>');
         var two_id = $('.swiper-slide-active').find('.two-id').val();
         $.get('/address/child/'+two_id,function (data) {

         var str = '<div class="swiper-slide default">'+
         '<span>-请选择-</span>'+
         ' </div>';

         $.each(data['data'], function (index, item) {
         str += '<div class="swiper-slide">'+
         ' <span>'+item['name']+'</span>'+
         '<input type="hidden" class="two-id" value="'+item['id']+'"/>'+
         ' <input type="hidden" class="two-pid" value="'+item['pid']+'"/>'+
         '</div>';

         });
         $('.swiper-container3').find('.swiper-wrapper').html(str);
         })

         }
         });
         var mySwiper3 = new Swiper('.swiper-container3', {

         direction : 'vertical',
         slidesPerView :'auto',
         centeredSlides : true,
         observer:true,//修改swiper自己或子元素时，自动初始化swiper
         observeParents:true,//修改swiper的父元素时，自动初始化swiper

         onSlideChangeEnd: function(swiper){

         }
         });*/
    </script>
@endsection
