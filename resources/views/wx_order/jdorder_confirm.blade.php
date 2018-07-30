@extends('inheritance')

@section('title')
    订单确认
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('sd_css/common.css')}}">
    <link rel="stylesheet" href="{{asset('sd_css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('sd_css/address.css')}}">
    <style>
        /*阿里图标默认样式*/
        body{
            background-color: rgb(240,240,240);
        }
        .icon{
            width: 1em;
            height: 1em;
            vertical-align: -0.15em;
            fill: currentColor;
            overflow: hidden;
        }
        .address{
            border-bottom: 1px solid rgb(230,230,230);
            box-shadow: 0 1px 1px rgba(80,80,80,.1);
        }
        .address div{
            width: 95%;
            margin: 0 auto;
        }
        .location-border{
            width: 100%;
            height: 4px;
            background-image: url("../../sd_img/address-location-border.png");
            background-repeat: repeat-x;
            background-size: 30% 4px;
            /*margin-bottom: 10px;*/
        }

        .address .l{
            width: 9%;
        }

        .address .r{
            width: 85%;

        }
        .address .r li:first-child{
            margin-top: 10px;
            margin-bottom: 8px;
        }
        .address .r .r-b{
            margin-bottom: 10px;
            line-height: 22px;
        }

        /*已选商品预览*/
        .goods-has-select{
            width: 100%;
            height: 110px;
        }
        .goods-has-select-l{
            margin-left: 3%;
        }
        .goods-has-select-r{
            margin-left: 4%;
            margin-right: 3%;
            width: 66%;

        }
        .goods-has-select-l img{
            width: 80px;
        }
        .r-title{
            margin-bottom: 4px;
            height: 40px;
            overflow: hidden;
            line-height: 20px;
        }
        .r-property{
            margin-bottom:2px;
        }

        /*运费*/
        .goods-express{
            width: 100%;
            margin-top: 10px;
        }
        .goods-express ul{
            width: 94%;
            height: 50px;
            margin: 0 auto;
            border-bottom: 1px solid rgb(230,230,230);
        }
        .goods-count{
            width: 100%;
        }
        .goods-count ul{
            width: 94%;
            height: 50px;
            margin: 0 auto;
            border-bottom: 1px solid rgb(230,230,230);
        }

        /*抵现选项*/
        .goods-payStyle{
            width: 100%;
            margin-top: 10px;
            margin-bottom: 10px;
        }
        .goods-payStyle ul{
            width: 94%;
            height: 50px;
            margin: 0 auto;
            border-bottom: 1px solid rgb(230,230,230);
        }
        .goods-payStyle span{
            /*display: block;*/
            text-align: left;
            width: 61%;
        }
        .switch1{
            width: 40px;
            height: 24px;
            background-image: url("../../sd_img/switch.png");
            background-position: 40px 0;
            background-size: 200% 100%;
        }
        .switch2{
            width: 40px;
            height: 24px;
            background-image: url("../../sd_img/switch.png");
            background-position: 0px 0;
            background-size: 200% 100%;
        }

        /*尚需支付*/
        .cash-pay{
            width: 100%;
            margin: 10px 0 60px;
        }
        .cash-pay ul{
            width: 94%;
            height: 50px;
            margin: 0 auto;
        }

        /*实际支付*/
        .actual-pay{
            position: fixed;
            bottom: -11px;
            width: 100%;
            background-color: rgba(255,255,255,0.8);
        }
        .actual-pay ul:first-child{
            width: 70%;
            height: 50px;
            border-top: 1px solid rgb(230,230,230);
        }
        .actual-pay ul:last-child{
            width: 30%;
            height: 50px;
            background-color:#f53a3a;
        }
        .select-giftCard{
            width: 100%;
            height: 150px;
            background-color: #2aabd2;
        }
        .select-ticket{
            width: 100%;
            height: 150px;
            background-color: darkseagreen;
        }
        textarea{
            -webkit-appearance: none;
            appearance: none;
            border: none!important;
            outline: none;
            /*text-indent: 20px;*/
            padding: 12px;
            resize: none;

        }
        /**/
        /*卡余额涉及的样式*/
        .xq-lb-new {
            position: fixed;
            bottom: -300px;
            left: 0;
            right: 0;
            max-height: 300px;
            background-color: #fff;
            z-index: 10; }

        .title-zeng-new, #submitOrder .input-dan-new, #submitOrder .title-edit-new {
            height: 50px;
            border-bottom: 1px solid #f0f0f0; }
        .title-zeng-new p, #submitOrder .input-dan-new p, #submitOrder .title-edit-new p {
            padding-top: 15px; }
        .title-zeng-new, #submitOrder .title-edit-new {
            position: relative; }

        .close-btn-new {
            position: absolute;
            right: 10px;
            top: 15px;
            cursor: pointer; }

        .addressContent-new {
            max-height: 260px;
            overflow: auto; }






        /*卡片样式*/
        .card-list{
            margin-top: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;

        }
        .select{
            width: 8%;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .select img{
            width: 23px;
        }
        .czcard-wrap{
            width: 92%;
            border-bottom: 1px solid rgb(120,120,120);
        }
        .czcard{
            width: 95%;
            height: 46px;
            margin: 0px auto;
            border-radius: 8px 8px 0 0px;
            color: white;
        }
        .color1{
            background-color:#31a195;
        }
        .color2{
            background-color:#12aeee ;
        }
        .color3{
            background-color:#ef5350 ;
        }
        .czcard-mess{
            height: 45px;
            display: flex;
            justify-content: flex-start;
            align-items: center;
        }
        .czcard-mess img{
            width: 35px;
        }
        .czcard-mess p{
            font-size: 14px;
            line-height: 22px;
            margin-left：11px;
        }
        .czcard-mess .bold{
            font-weight: bold;
        }
        .czcard-mess div:first-child{
            margin-left: 3%;
            margin-right: 3%;

        }
        .czcard-time{
            width: 82%;
            margin-left: 18%;
            text-align: right;
            border-top: 1px solid #787878;

        }
        .czcard-time p{
            font-size: 14px;
            line-height: 26px;
            margin-right: 3%;
        }

        /*阴影*/
        .yinying {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            z-index: 5;
            background-color: #000;
            opacity: 0.4; }



    </style>
@endsection

@section('content')
    <body id="submitOrder">
    {{--<div class="address b-white">
        <div class="flex select_add">
            <ul class="l">
                <li>
                    <svg class="icon font-26 color-100" aria-hidden="true"><use xlink:href="#icon-infenicon07"></use> </svg>
                </li>
            </ul>



            <ul class="r">
                @if($address_info->isEmpty())
                    <div class="add-address-btn add-address">
                        <li class="r-b font-12 color-80" style="font-size: 16px; margin-left:35px;">新增收货地址
                        </li>
                    </div>


                    <input class="default_is_who" type="hidden" value="{{$is_hasAddress}}"/>

                    <div class="default-address-hidden default-address-xq">
                        <ul class="r-t flex-between font-14 color-50">
                            <li >收货人:<span class="m-shr"></span></li>
                            <li  class="m-sj"></li>
                        </ul>
                        <li class="r-b font-12 color-80">收货地址：
                    <span>
                        <span class="m-sjdz"></span>&nbsp;&nbsp;
                        <span class="m-dz"></span>
                    </span>
                        </li>

                    </div>

                @else
                    <div class="default-address-xq">
                        <ul class="r-t flex-between font-14 color-50">
                            <li >联系人：<span class="m-shr">{{$default_address->recipient_name}}</span></li>
                            <li  class="m-sj">电话：{{$default_address->mobile}}</li>
                        </ul>
                        <li class="r-b font-12 color-80">服务/收货地址：
                    <span>
                        <span class="m-sjdz">{{$default_address->area_info}}</span>&nbsp;&nbsp;
                        <span class="m-dz">{{$default_address->address}}</span>
                    </span>
                        </li>
                    </div>

                @endif

            </ul>
            <ul class="r-1">
                <li class="color-50 flex-between"><img src="{{asset('sd_img/right-btn.svg')}}" class="size-26 right-btn" alt=""></li>
            </ul>
        </div>
    </div>--}}

    <div class="location-border"></div>
    <div>
        <li>
            <span>{{$enterdate}}入住-{{$leavedate}}离店 共{{$nights}}晚</span>
            <input type="hidden" id="enterdate" value="{{$enterdate}}"/>
            <input type="hidden" id="leavedate" value="{{$leavedate}}"/>
            <input type="hidden" id="nights" value="{{$nights}}"/>
        </li>
    </div>
    <!--第一次循环-->
    <div class="goods-has-selectWrap">

        @foreach($skus_info as $sku)
            <div class="goods-has-select flex-unbetween b-white test1">
                <input type="hidden" value="{{$sku['sku_id']}}" class="skuId"/>
                <input type="hidden" value="{{$sku['number']}}"/>
                <input type="hidden" value="{{!empty($sku['price']) ? $sku['price'] : 0}}"/>
                <input type="hidden" value="{{!empty($sku['promotions_type']) ? $sku['promotions_type'] : 1}}"/>
                <input type="hidden" value="{{!empty($sku['promotions_id']) ? $sku['promotions_id'] : 0}}"/>


                <input type="hidden" id="sku_{{$sku['sku_id']}}" value="{{$sku['sku_name']}}"/> {{--房间被售出提示使用20171027--}}

                <ul class="goods-has-select-l">
                    <li><img src="{{asset($sku['main_img'])}}" alt=""></li>
                </ul>
                <div class="goods-has-select-r">
                    <ul>
                        <li class="r-title font-12 color-80">{{$sku['sku_name']}}</li>
                        <li class="r-property font-12 color-160">
                            {{empty($sku['sku_spec']) ? '' : '规格：'}}
                            @foreach($sku['sku_spec'] as $guige)
                                <span class="f12 fzq">{{$guige}}</span>
                            @endforeach</li>
                    </ul>
                    <ul class="flex-between font-14">
                        <li class="r-prize">¥&nbsp;{{$sku['price']}}</li>
                        <li class="color-80">&times;<span id="number">{{$sku['number']}}</span></li>
                    </ul>
                </div>
            </div>

        @endforeach
        <input type="hidden" value="{{$sku_source_type}}" class='sku_source_type'/>
    </div>
    <div class="goods-express b-white">
        <ul class="flex-between font-14 color-80">
            <li>运费</li>
            <li>¥&nbsp;{{$fare_total}}</li>
        </ul>
    </div>
    <div class="goods-count b-white" >
        <ul class="flex-between font-14 color-80">
            <li>小计</li>
            <li class="font-ber color-f53a3a">¥&nbsp;<span style="margin-right: 10px" id="payable_amount">{{$payable_amount}}</span></li>
        </ul>
    </div>

    <div class="goods-payStyle b-white">
        @if(isset($my_money_info['money_vrb']))
            <ul class="goods-payStyle-item flex-between font-14 color-80 border-b">
                <input type="hidden" value="{{$my_money_info['money_vrb']['plat_vrb_rate']}}" id="plat_vrb_rate"/>
                <li>{{$my_money_info['money_vrb']['plat_vrb_caption']}}</li>
                <span class="font-12 color-160" id="n_use">
                    余额：<span id="goods_points_totals">{{$my_money_info['money_vrb']['available']}}</span>
                    @if($my_money_info['money_vrb']['available'] < $my_money_info['money_vrb']['plat_vrb_rate'])
                        满&nbsp;<span >{{$my_money_info['money_vrb']['plat_vrb_rate']}}</span>&nbsp;{{$my_money_info['money_vrb']['plat_vrb_caption']}}可用
                    @else
                        本次最多可用：<span id="my_vrb_available" >{{$my_money_info['money_vrb']['pay_max_amount']}}</span>
                    @endif
                </span>
                <span class="font-12 color-160"  id="y_use" style="display: none">
                    本次使用：<span id="this_used">{{$my_money_info['money_vrb']['pay_max_amount']}}</span>
                    抵：<span id="di_rmb">{{$my_money_info['money_vrb']['pay_max_amount_to_rmb']}}</span></span>
                <li class="switch1" data-name="{{$my_money_info['money_vrb']['plat_vrb_caption']}}" id="shuibi" ></li>
            </ul>
        @endif

        @if(isset($my_money_info['money_wallet']))
            <ul class="goods-payStyle-item flex-between font-14 color-80 border-b">
                <li>零钱</li>
                <span class="font-12 color-160" id="n_ling">可用：<span id="max_ling">{{$my_money_info['money_wallet']['available']}}</span></span>
                <span class="font-12 color-160" id="y_ling" hidden>本次使用：<span id="use_ling">0</span></span>
                <li class="switch1" data-name="零钱" id="ling_switch"></li>
            </ul>
        @endif
        @if(isset($my_money_info['money_card_balance']))
            <ul class="goods-payStyle-item sElectcArd flex-between font-14 color-80">
                <li>卡余额</li>
                <span class="font-12 color-160">共&nbsp;<span  id="card_num">{{$my_money_info['money_card_balance']['count']}}</span>&nbsp;张卡券可用，本次使用了&nbsp;<span  id="used_card_num">0</span>&nbsp;张</span>

                <li  id="ka-yu-e"><img style="float: right;height: 51px;margin-right: 10px" id="ka-yu-e-right-btn" src="{{asset('sd_img/right-btn.svg')}}" height="16" width="16"/></li>
            </ul>
            <div class="select-giftCard" style="display: none">选择卡片</div>
        @endif

        {{--<ul class="goods-payStyle-item flex-between font-14 color-80 border-b">--}}
        {{--<li>微信支付</li>--}}
        {{--<li class="switch1" data-name="微信支付" id="wx_pay"></li>--}}
        {{--</ul>--}}

    </div>

    <div class="giving b-white" >
        <ul>
            <textarea id="textarea" placeholder="备注"  style="width:100%;height:60px;border:solid 1px #e8e8e8;;box-shadow: inset 0 0px 0px rgb(255,255,255);background-color: white;margin-top: 4px;"></textarea>
        </ul>
    </div>



    <!--底部固定bar-->
    <div class="actual-pay flex-between font-14">
        <ul class="flex">
            <li class="color-80">合计：¥&nbsp;<span class="font-14 color-f53a3a" id="zhifu-total">{{$pay_rmb}}</span></li>
        </ul>
        <ul class="flex-center color-white zf-btn">
            立即支付
        </ul>
    </div>

    <!------------------------新增收货地址  START!---------------------------->
    <form role="form">
        <div class="xinzeng col-xs-12">
            <div class="title-zeng">
                <p class="text-center">新增服务/收货地址</p>
                <div class="close-btn close-set-btn" id="add-new-add">
                    <img src="{{asset('img/m-close.svg')}}" height="20" width="20"/>
                </div>
            </div>
            <div class="zeng col-xs-12">
                <div class="col-xs-12 input-dan">
                    <div class="col-xs-4" >
                        <span style="margin-left: 7px">联系人</span>
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
            <p class="text-center">编辑服务/收货地址</p>
            <div class="close-btn close-edit-btn">
                <img src="{{asset('img/m-close.svg')}}" height="20" width="20"/>
            </div>
        </div>
        <div class=" col-xs-12">
            <div class="col-xs-12 input-dan">
                <div class="col-xs-4" >
                    <span style="margin-left: 7px;">联系人</span>
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



    <!------------------------阴影效果  START!---------------------------->
    <div class="xiaoguo">

    </div>
    <!--======================阴影效果  END!============================-->


    <!--*********************卡余额  start!*********************-->
    @if(isset($my_money_info['money_card_balance']))
        <div class="xq-lb-new" id="ka-yu-e-select">
            <div class="title-zeng-new">
                <p class="text-center">卡余额选择</p>
                <div class="close-btn-new " id="ka_yu_e_close">
                    <img src="{{asset('sd_img/m-close.svg')}}" height="20" width="20"/>
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
                            <img class="select_img" src="/sd_img/defau.png"  alt="">
                        </div>

                        <div class="czcard-wrap">
                            <div class="czcard color3" >
                                <div class="czcard-mess">
                                    <div>
                                        <img src="/sd_img/logo_xns.png" alt="logo">
                                    </div>
                                    <div >
                                        <p style="margin-bottom: 0px;">面值: ¥&nbsp;<span class="bold">{{$card_lst['balance_amount']}}</span> &nbsp;&nbsp;&nbsp;余额: ¥&nbsp;<span class="bold">{{$card_lst['balance_available']}}</span></p>
                                    </div>
                                </div>

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
        {{--<!------------------------阴影效果  START!---------------------------->--}}
        <div class="ka-yu-e-xiaoguo">
        </div>
        <!--======================阴影效果  END!============================-->
    @endif



    </body>

@endsection

@section('js')
    <script src="{{asset('sd_js/jquery.min.js')}}"></script>
    <script src="{{asset('sd_js/font_wvum.js')}}"></script>
    <script src="{{asset('sd_js/common-top.js')}}"></script>

    <script src="{{asset('sd_js/swiper.jquery.min.js')}}"></script>
    <script src="{{asset('sd_js/order/order_confirm_address.js')}}"></script>
    <script src="{{asset('jiudian_js/jdorder_confirm_pay.js')}}"></script>

@endsection

