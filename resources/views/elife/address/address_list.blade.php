@extends('inheritance')

@section('title')
    地址列表
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('elife_css/common.css')}}">
    <link rel="stylesheet" href="{{asset('elife_css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('elife_css/address.css')}}">
    <style>
        /*阿里图标默认样式*/
        body{
            background-color: rgb(240,240,240);
        }

        .head .nav{
            position: fixed;
            top: 0;
            padding: 0 4px;
            line-height: 40px;
            width:100%;
            z-index: 999999;
            font-family: "微软雅黑";
        }
        .head .nav .return{
            display: inline-block;
            width: 40%;
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
            opacity: 0.4;
        }



    </style>
@endsection

@section('content')
    <body id="submitOrder">
    <div class="head">
        <div class="nav b-white">
            <a class="return font-14 color-80" href="{{asset('elife/personal/index')}}"><img src="{{asset('sd_img/next.png')}}" alt="" class="size-26" style="transform:rotate(180deg);"></a>
            <a class="title font-18 color-80" href="#">地址列表</a>
        </div>
    </div>
    <div class="address b-white" style="margin-top: 45px">
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
                            <li >收货人:<span class="m-shr">{{$default_address->recipient_name}}</span></li>
                            <li  class="m-sj">{{$default_address->mobile}}</li>
                        </ul>
                        <li class="r-b font-12 color-80">收货地址：
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
    </div>

    <div class="location-border"></div>

    <!--底部固定bar-->
    {{--<div class="actual-pay flex-between font-14">
        <ul class="flex">
            <li class="color-80">合计：¥&nbsp;<span class="font-14 color-f53a3a" id="zhifu-total">{{$pay_rmb}}</span></li>
        </ul>
        <ul class="flex-center color-white zf-btn">
            立即支付
        </ul>
    </div>--}}

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
            <div class="col-xs-4 f18 text-center edit-del" style="background-color:#e42f46;color: white">
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
                        <img src="{{asset('sd_img/edit.svg')}}" height="24" width="24"/>
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
            <div class="col-xs-4 choose" style="height: 280px; overflow: hidden">
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
            <div class="col-xs-4 choose" style="height: 280px; overflow: hidden">
                <div class="swiper-container swiper-container2">
                    <div class="swiper-wrapper swiper-wrapper2">
                        <div class="swiper-slide">
                            <span>-请选择-</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-4 choose" style="height: 280px; overflow: hidden">
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

    {{--<!------------------------阴影效果  START!---------------------------->--}}
    <div class="ka-yu-e-xiaoguo">
    </div>
    <!--======================阴影效果  END!============================-->


    </body>

@endsection

@section('js')
    <script src="{{asset('elife_js/jquery.min.js')}}"></script>
    <script src="{{asset('elife_js/font_wvum.js')}}"></script>
    <script src="{{asset('elife_js/common-top.js')}}"></script>

    <script src="{{asset('elife_js/swiper.jquery.min.js')}}"></script>
    <script src="{{asset('elife_js/order/order_confirm_address.js')}}"></script>
@endsection

