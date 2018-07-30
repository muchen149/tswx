@extends('inheritance')

@section('title')
    订单确认
@endsection

@section('css')
<link rel="stylesheet" href="{{asset('sd_css/common.css')}}">
<link rel="stylesheet" href="{{asset('sd_css/bootstrap.min.css')}}">
<link rel="stylesheet" href="{{asset('sd_css/address.css')}}">
<link rel="stylesheet" href="{{asset('sd_css/order_confirm.css')}}">
<link rel="stylesheet" href="{{asset('sd_css/idCard.css')}}">
<style>
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
    <div class="address b-white">
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
    <!--第一次循环-->
    <div class="goods-has-selectWrap">

        @foreach($skus_info as $sku)
            <div class="goods-has-select flex-unbetween b-white test1">
                <input type="hidden" value="{{$sku['sku_id']}}" class="skuId"/>
                <input type="hidden" value="{{$sku['number']}}"/>
                <input type="hidden" value="{{!empty($sku['price']) ? $sku['price'] : 0}}"/>
                <input type="hidden" value="{{!empty($sku['promotions_type']) ? $sku['promotions_type'] : 1}}"/>
                <input type="hidden" value="{{!empty($sku['promotions_id']) ? $sku['promotions_id'] : 0}}"/>

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
            <li>¥&nbsp;<span id="fare">{{$fare_total}}</span></li>
        </ul>
    </div>
    <div class="goods-count b-white" >
        <ul class="flex-between font-14 color-80">
            <li>小计</li>
            <li class="font-ber color-f53a3a">¥&nbsp;<span style="margin-right: 10px" id="payable_amount">{{$payable_amount}}</span></li>
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
            {{--<input type="textarea" placeholder="表达一点谢意吧" id="textarea">--}}
            <textarea id="textarea" placeholder="给商家留言"  style="width:100%;height:60px;border:solid 1px #e8e8e8;;box-shadow: inset 0 0px 0px rgb(255,255,255);background-color: white;margin-top: 4px;"></textarea>
        </ul>
    </div>



    <!--底部固定bar-->
    <div class="actual-pay flex-between font-14">
        <ul class="flex">
            <li class="color-80">合计：¥&nbsp;<span class="font-14 color-f53a3a" id="zhifu-total">{{$pay_rmb}}</span></li>
        </ul>
        @if($id_card == 1)
            <ul class="flex-center color-white zf-btn">
                立即支付
            </ul>
        @elseif($id_card == 2)
            <ul class="flex-center color-white id_card">
                立即支付
            </ul>
            <ul class="flex-center color-white zf-btn zhiyou_baoshui">
                立即支付
            </ul>
        @elseif($id_card == 3)
            <ul class="flex-center color-white id_card_img">
                立即支付
            </ul>
            <ul class="flex-center color-white zf-btn heitao_qinguan">
                立即支付
            </ul>
        @endif
    </div>

    {{----------------------------身份证号绑定------------------------------------}}
    <div id="light" class="white_content">
        <div class="title-zeng-new">
            <p class="text-center" style="padding-top: 22px;">实名认证</p>
            <div class="close-btn-new" id="idCard_close">
                <img src="{{asset('sd_img/m-close.svg')}}" height="30" width="30"/>
            </div>
        </div>
        <div class="font-12" style="color:#aab2bd;padding: 5px 0px;">
            <img src="{{asset('elife_img/tishi.jpg')}}"><span>海关要求购买跨境商品需提供实名信息哦!</span>
        </div>
        <div class="text_card">
            <input type="text" id="v_name" name="v_name" class="style_card" placeholder="您的真实姓名" autocomplete="off" >
            <input id="v_idcard"  name="v_idcard" class="style_card" type="text"  placeholder="您的身份证号码（将加密处理）" autocomplete="off" >
        </div>
        <div class="color-80 id_notice">
            <span class="font-12" style="color:#aab2bd"><img src="{{asset('elife_img/yiwen.jpg')}}">了解实名认证</span>
        </div>
        <button class="to_examine" style="width: 100%;height: 40px;background-color: #f53a3a;font-size: 18px;margin-top: 10px;">
            <span style="color:#ffffff;">立即验证</span>
        </button>
    </div>

    <div id="lights" class="white_content_a" style="border-radius:5px 6px 5px 6px;">
        <div>
            @if($shopNote)
                {!!$shopNote->doc_content!!}
            @endif
        </div>
        <div class="color-80  close_card_t">
            <span class="btn-buy" style="color:#e42f46;line-height: 18%;font-size: 14px">我知道了</span>
        </div>
    </div>
    </div>
    <div id="fade" class="black_overlay"></div>

    <!------------------------阴影效果  START!---------------------------->
    <div class="light-xiaoguo"></div>
    <!--======================阴影效果  END!============================-->

    {{---------------------------------身份证照片绑定-----------------------------------}}
    <div id="light_img" class="white_content_img" style="padding: 0 10px;">
        <div class="title-zeng-new">
            <p class="text-center" style="padding-top: 22px;">身份证正反面照片（必填）</p>
            <div class="close-btn-new" id="idCard_img_close">
                <img src="{{asset('sd_img/m-close.svg')}}" height="30" width="30"/>
            </div>
        </div>
        <div class="text_card">
            <span class="font-12" style="color: #b2b2b2">温馨提示：请上传原始比例的身份证正反面，请勿裁剪涂改，保证身份信息清晰显示否则无法通过审核</span>
        </div>
        <!--图片上传-->
        <section class="goods-apply-upload">
            <h5 class="mible-tit">上传图片</h5>

            <div id="img-wrapper" class="img-wrapper">

                <div id="front-img-wrapper" style="display: inline-block;width:40%;text-align: center;">
                    <div class="upload-btn-box front_img_box" style="display: inline-block;">
                        <input id="uploadPic_front" name="front_img" class="upload-btn upload-front" type="file">
                    </div>
                </div>

                <div id="back-img-wrapper" style="display: inline-block;width:40%;text-align: center;">
                    <div class="upload-btn-box back_img_box" style="display: inline-block;">
                        <input id="uploadPic_back" name="back_img" class="upload-btn upload-back" type="file">
                    </div>
                </div>

                <div style="clear:both"></div>
                <div class="msg-text">
                    用户上传身份证正反两面照片
                </div>
            </div>
            <script type="text/javascript" src="{{asset('js/ajaxfileupload.js')}}"></script>
        </section>
        <button class="to_examine_img" style="width: 100%;height: 40px;background-color: #f53a3a;font-size: 18px;margin:5px 0;">
            <span style="color:#ffffff;">立即验证</span>
        </button>
    </div>
    <input name="uploadPic_front" id="hid_front" value="" type="hidden">
    <input name="uploadPic_back" id="hid_back" value="" type="hidden">
    <!------------------------阴影效果  START!---------------------------->
    <div class="light-img-xiaoguo"></div>

    <div id="pop-confirm" class="pop pop-ctm-01" style="display: none; position: fixed; top: 30%;left: 15%;;">
        <div class="pop-msg">
            <div id="confirm-content" class="pop-msg-cnt" style="padding-top:30px;min-height: 50px">您确认要删除这张图片吗？
            </div>
            <div class="pop-msg-btm">
                <a id="confirm-close-btn" class="btn-h4 btn-c3" style="width: 96px;"
                   href="javascript:void(0);">关闭</a>
                <a id="confirm-ok-btn" class="btn-h4 btn-c3"
                   style="width: 96px;border: 1px solid #cfcfcf;color: #737373;background: #fff"
                   href="javascript:void(0);">确定</a>
            </div>
        </div>
    </div>

    <div class="__MASK_SID_DIV" style="display:none;width:100%;position: absolute; z-index: 999; left: 0px; top: 0px;">
        <div id="set_height"  style="width:100%; height: 1024px; opacity: 0.7; background: black none repeat scroll 0% 0%; position: static; margin: 0px;"></div>
    </div>
    <!--======================阴影效果  END!============================-->

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

    <!--*********************卡余额  start!*********************-->
    @if(isset($my_money_info['money_card_balance']))
        <div class="xq-lb-new" id="ka-yu-e-select">
            <div class="title-zeng-new">
                <p class="text-center">卡余额选择</p>
                <div class="close-btn-new " id="ka_yu_e_close">
                    <img src="{{asset('sd_img/m-close.svg')}}" height="30" width="30"/>
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
<script src="{{asset('sd_js/font_wvum.js')}}"></script>
<script src="{{asset('sd_js/common-top.js')}}"></script>

<script src="{{asset('sd_js/swiper.jquery.min.js')}}"></script>
<script src="{{asset('sd_js/order/order_confirm_address.js')}}"></script>
<script src="{{asset('sd_js/order/order_confirm_pay.js')}}"></script>
<script src="{{asset('sd_js/personal/id_card.js')}}"></script>
<script type="text/javascript">
    var scriptUrl = '{{asset('sd_js/personal/id_card.js')}}';
    var uploadImgUrl = '{{asset('personal/uploadIdImg')}}';
    var delImgUrl = '{{asset('personal/delIdImg')}}';
</script>
@endsection

