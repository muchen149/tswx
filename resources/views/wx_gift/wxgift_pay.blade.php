@extends('inheritance')

@section('title')
    支付礼品
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('sd_css/common.css')}}">
    <style>
        .icon{
            width: 1em;
            height: 1em;
            vertical-align: -0.15em;
            fill: currentColor;
            overflow: hidden;
        }
        .gift-preview{
            height: 120px;
            background-color: #e9e9e9;
        }
        .gift-preview ul:first-child{
            margin-left: 3%;
            width: 20%;
        }
        .gift-preview ul:last-child{
            margin-left: 5%;
            margin-right: 3%;
            width: 70%;
        }
        .gift-preview img{
            width: 80px;
        }
        .gift-preview .propety{
            margin-top: 4px;
        }
        .gift-preview .prize{
            margin-top: 4px;
            color: #f53a3a;
        }

        .gift-amount{
            height: 50px;
            margin-bottom: 10px;
        }
        .gift-amount ul:first-child{
            margin-left: 3%;
        }
        .gift-amount ul:last-child{
            margin-right: 3%;
            width: 100px;
        }
        .gift-amount ul:last-child input{
            box-sizing: border-box;
            text-align: center;
            width: 40px;
            height: 30px;
            border-top: 1px solid rgb(220,220,220);
            border-bottom: 1px solid rgb(220,220,220);
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


        /*抵现选项*/
        .goods-payStyle{
            width: 100%;
            margin-top: 10px;
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
            background-image: url("../../../sd_img/switch.png");
            background-position: 40px 0;
            background-size: 200% 100%;
        }
        .switch2{
            width: 40px;
            height: 24px;
            background-image: url("../../../sd_img/switch.png");
            background-position: 0px 0;
            background-size: 200% 100%;
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


        .liuyan ul:first-child{
            width: 100%;
            height: 44px;
        }
        .liuyan ul:first-child label{
            margin-left: 3%;
        }
        .liuyan ul:first-child input{
            outline: none;
            width: 260px;
            height: 44px;
            font-size: 14px;
        }
        .liuyan ul:last-child{
            width: 100%;
            height: 72px;
            background-color:rgb(240,240,240);
        }
        .liuyan ul:last-child input{
            background-color:rgb(240,240,240);
            outline: none;
            margin-left: 3%;
            padding-top: 8px;
            width: 94%;
            height: 100%;

        }

        .express{
            margin-top: 10px;
        }
        .express ul{
            margin-left: 3%;
            height: 50px;
            margin-right: 3%;
        }

        .payStyle{
            margin-top: 10px;
        }
        .payStyle ul{
            margin-left: 3%;
            height: 50px;
            margin-right: 3%;
        }
        .payStyle ul li:last-child{
            color: #f53a3a;
        }
        .fixed{
            position: fixed;
            width: 100%;
            bottom: 0;
        }
        .fixed ul{
            height: 50px;
        }
        .fixed ul li:first-child{
            margin-left: 3%;
        }
        .fixed ul li:first-child span{
            color: #f53a3a;
            font-weight: bolder;
        }
        .fixed ul a{
            overflow: hidden;
            width: 40%;
            background-color: #f53a3a;
            height: 50px;
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
    <body>
    <div class="gift-preview flex-around">
        <ul>
            <li>
                <img src="{{$sku_arr['main_img']}}" alt="">
            </li>
        </ul>
        <ul>
            <li class="font-12 color-50 title">{{$sku_arr['sku_name']}}</li>
            <input type="hidden" value="{{$sku_arr['sku_id']}}" id="skuId"/>
            <input type="hidden" value="{{$sku_arr['goods_num']}}" id="g_num"/>
            <input type="hidden" value="{{!empty($sku_arr['price']) ? $sku_arr['price'] : 0}}" id="g_price"/>
            <input type="hidden" value="{{$amount_arr['freight_total']}}" id="g_freight"/>



        @if(!empty($sku_arr['sku_spec']))
                <li class="font-12 color-80 propety">规格：
                    @foreach($sku_arr['sku_spec'] as $guige)
                        <span >{{$guige}}</span>
                    @endforeach
                </li>
            @endif

            <li class="flex-between font-12 prize font-ber">
                <span>¥&nbsp;{{$sku_arr['price']}}</span>
                <span>X&nbsp;{{$sku_arr['goods_num']}}</span>
            </li>



        </ul>
    </div>
    <div class="express b-white">
        <ul class="flex-between font-14 color-50">
            <li>总运费</li>
            <li>
                @if($amount_arr['freight_total'] == 0)
                    (免运费)<span id="fare">0.00</span>
                @else
                    ¥&nbsp;<span id="fare">{{$amount_arr['freight_total']}}</span>
                @endif
            </li>
        </ul>

        <ul class="flex-between font-14 color-50">
            <li>小计</li>
            <li>¥&nbsp;<span id="payable_amount">{{$amount_arr['all_amount_totals']}}</span></li>
        </ul>
    </div>

    <div class="goods-payStyle b-white">
        @if(isset($my_money_info['org_card_balance']))
            <ul class="goods-payStyle-item sElectcArd flex-between font-14 color-80">
                <li>机构卡</li>
                <span class="font-12 color-160">共&nbsp;<span  id="card_num">{{$my_money_info['org_card_balance']['org_count']}}</span>&nbsp;张卡可用</span>

                <li  id="ji-gou-ka"><img style="float: right;height: 51px;margin-right: 10px" id="ji-gou-ka-right-btn" src="{{asset('sd_img/right-btn.svg')}}" height="16" width="16"/></li>
            </ul>
            <div class="select-giftCard" style="display: none">选择卡片</div>
        @endif

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
                <span class="font-12 color-160" hidden id="y_use">
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

        <ul class="goods-payStyle-item flex-between font-14 color-80 border-b">
            <li>微信支付</li>
            <li class="switch2" data-name="微信支付" id="wx_pay"></li>
        </ul>

    </div>

    <div class="liuyan" style="margin-bottom:50px;width: 100%;height: 150px;">
        {{--<ul class="b-white">
            <label for="" class="font-14">标题：</label>
            <input type="text"  placeholder="" id="title">
        </ul>--}}
        <ul class="font-14 color-80" style="padding: 10px 10px 0px 10px;border-radius:3px;">
            <li>送礼标题：</li>
            <textarea class="form-control"  rows="3" placeholder="时间在变，但是我们的情谊永不变，为你奉上我的小小礼物，希望你能喜欢！！" autocomplete="off" style="outline: none;resize: none;box-shadow: inset 0 0px 0px rgb(255,255,255);background-color: white;margin-top: 0px;">时间在变，但是我们的情谊永不变，为你奉上我的小小礼物，希望你能喜欢！！</textarea>

        </ul>

    </div>


    <div class="fixed b-white">
        <ul class="flex-between">
            <li class="font-14 color-50">合计&nbsp;&nbsp;¥&nbsp;<span id="zhifu-total">{{$amount_arr['all_amount_totals']}}</span></li>
            <a id="submit_pay" class="flex-center color-white font-14" style="text-decoration:none"><li>立即付款</li></a>
        </ul>
    </div>


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
                                        <p >面值: ¥&nbsp;<span class="bold">{{$card_lst['balance_amount']}}</span> &nbsp;&nbsp;&nbsp;余额: ¥&nbsp;<span class="bold">{{$card_lst['balance_available']}}</span></p>
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
        <!--*********************机构卡折扣  start!*********************-->
    @if(isset($my_money_info['org_card_balance']))
        <div class="xq-lb-new" id="ji-gou-ka-select">
            <div class="title-zeng-new">
                <p class="text-center">机构卡选择</p>
                <div class="close-btn-new " id="ji_gou_ka_close">
                    <img src="{{asset('sd_img/m-close.svg')}}" height="30" width="30"/>
                </div>
            </div>

            <div class="col-xs-12 addressContent-new">

                @foreach($my_money_info['org_card_balance']['org_card_lst'] as $card_lst)
                    <div class="card-list to-chose-orgCard" chose="0">
                        <input type="hidden" name="use_money" class="use_money" value="0"/>
                        <input type="hidden" name="orgCard_id" class="orgCard_id" value="{{$card_lst->id}}"/>
                        <input type="hidden" name="card_count" class="card_count" value="{{$card_lst->card_count}}"/>
                        <input type="hidden" name="price" class="price" value="{{$card_lst->price}}"/>
                        <input type="hidden" name="org_pay_amount" class="org_pay_amount" value="0"/>
                        <div class="select">
                            <img class="select_img" src="/sd_img/defau.png"  alt="">
                        </div>
                        <div class="czcard-wrap">
                            <div class="czcard color3" >
                                <div class="czcard-mess">
                                    <div>
                                        <img src="{{$my_money_info['org_card_balance']['url']}}{{$card_lst->card_image}}" alt="logo">
                                    </div>
                                    <div >
                                        @if($card_lst->discount_type == 0)
                                            <p style="margin-bottom: 0px;">{{$card_lst->card_name}}享{{$card_lst->card_count}}折优惠</p>
                                        @else
                                            <p style="margin-bottom: 0px;">{{$card_lst->card_name}}专享优惠价</p>
                                        @endif
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
        <!--*********************机构卡折扣  start!*********************-->
        {{--<!------------------------阴影效果  START!---------------------------->--}}
        <div class="ji-gou-ka-xiaoguo">
        </div>
        <!--======================阴影效果  END!============================-->
    @endif

    </body>

@endsection

@section('js')

    <script src="{{asset('sd_js/jquery.min.js')}}"></script>
    <script src="{{asset('sd_js/font_wvum.js')}}"></script>


    <script type="text/javascript" src="{{asset('sd_js/common-top.js')}}"></script>

    <script type="text/javascript" src="{{asset('sd_js/wx_gift/wxgift_pay.js')}}"></script>

@endsection

