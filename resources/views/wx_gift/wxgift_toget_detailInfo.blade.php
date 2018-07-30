@extends('inheritance')

@section('title')
    填写收货信息
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
        }
        .gift-preview .left{
            margin-left: 3%;
            width: 20%;
        }
        .gift-preview .right{
            margin-left: 5%;
            margin-right: 3%;
            width: 67%;
        }
        .gift-preview img{
            width: 80px;
            border: 1px solid rgb(220,220,220);
        }
        .gift-preview .propety{
            margin-top: 4px;
        }

        .express ul{
            width: 95%;
            height: 44px;
            margin: 0 auto;
        }
        .address div{
            width: 95%;
            margin: 0 auto;
        }
        .address .r{
            margin-left: 16px;
            width: 86%;
        }
        .address .r li:first-child{
            margin-top: 10px;
            margin-bottom: 8px;
        }
        .address .r .r-b{
            margin-bottom: 10px;
            line-height: 22px;
        }

        .giving{
            margin-top: 10px;
        }

        .giving ul{
            width: 95%;
            margin: 0 auto;
        }

        .giving ul input{
            outline: none;
            width: 100%;
            height: 80px;
        }

        .getAction{
            background-color: #f53a3a;
            width: 85%;
            height: 42px;
            margin: 30px auto 0;
            border-radius: 8px;
            color: white;
        }

        .gray {
            background-color: gray !important; }
    </style>

    <link rel="stylesheet" href="{{asset('sd_css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('sd_css/address.css')}}">


@endsection

@section('content')
<body  id="submitOrder">

<div class="address b-white">
    <div class="flex-between select_add">
        <ul class="l">
            <li>
                <img class="address-img" src="{{asset('sd_img/address.svg')}}" height="22" width="22"/>

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


    <div class="gift-preview flex-between">
        <input type="hidden" id="share_gifts_info_id"  value="{{$share_info->share_gifts_info_id}}"/>

        <ul class="left">
            <li>
                <img src="{{$share_info->sku_image}}" alt="">
            </li>
        </ul>

        <div class="right">
            <ul>
                <li class="font-12 color-50 title">{{$share_info->sku_name}} </li>
            </ul>
            <ul class="flex-between">
                <li class="font-12 color-80 propety">{{$share_info->sku_price}}</li>
                <li>× &nbsp;1</li>
            </ul>
        </div>

    </div>
    {{--<div class="express b-white border-b">--}}
        {{--<ul class="flex-between font-14 color-50 ">--}}
            {{--<li class="font-14 color-50">运费</li>--}}
            {{--<li class="color-50 flex-between"><span class="font-14">快递免邮</span><img src="img/next.png" class="size-26" alt=""></li>--}}
        {{--</ul>--}}
    {{--</div>--}}

    <div class="giving b-white">
        <ul>
            {{--<input type="textarea" placeholder="表达一点谢意吧" id="textarea">--}}
            <textarea id="textarea" placeholder="说点感谢的话吧！"  style="width:100%;height:60px;border:solid 1px #e8e8e8;;box-shadow: inset 0 0px 0px rgb(255,255,255);background-color: white;margin-top: 4px;"></textarea>
        </ul>
    </div>

        <div class="getAction flex-center font-12" id="get_gift">
            确认领取
        </div>



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



</body>

@endsection

@section('js')

    <script src="{{asset('sd_js/mui.min.js')}}"></script>
    <script src="{{asset('sd_js/common-top.js')}}"></script>
    <script src="{{asset('sd_js/common.js')}}"></script>

    <script src="{{asset('sd_js/jquery.min.js')}}"></script>
    <script src="{{asset('sd_js/wx_gift/wxgift_toget.js')}}"></script>
    <script src="{{asset('sd_js/swiper.jquery.min.js')}}"></script>

@endsection

