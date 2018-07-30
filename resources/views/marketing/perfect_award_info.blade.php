@extends('inheritance')

@section('title')
    填写领奖信息
@endsection

@section('css')
    <link href="{{asset('css/header.css')}}" rel="stylesheet">

    <link rel="stylesheet" href="{{asset('css/swiper.min.css')}}" type="text/css">

    <link href="{{asset('css/order/order_index.css')}}" rel="stylesheet">
    <link href="{{asset('css/footer.css')}}" rel="stylesheet">
    <link href="{{asset('css/after-sales.css')}}" rel="stylesheet">
    <style>
        /*填写领奖信息样式*/
        * {
            margin: 0;
            padding: 0;
            border: 0;
        }

        body {
            background-color: rgb(240, 240, 240);
        }

        .col-xs-12 {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        .col-xs-12 p {
            margin-left: 10px;
            margin-right: 10px;
        }

        .status {
            background-color: white;
            width: 100%;
            height: 68px;
            text-align: center;
        }

        .status img {
            width: 310px;
            margin-top: 4px;
        }

        .dark_color {
            background-color: rgb(240, 240, 240);
            width: 100%;
            height: 10px;
        }

        .from {
            background-color: white;
            width: 100%;
            height: 121px;
        }

        .from .true_name {
            width: 96%;
            height: 60px;
            margin-left: 5%;
            border-bottom: 1px solid rgb(220, 220, 220);
        }

        .from input {
            width: 100%;
            height: 46px;
            margin-top: 8px;
            outline: none;
        }

        .from .tel {
            width: 96%;
            height: 60px;
            margin-left: 5%;
        }

        .btn {
            background-color: #00b7ee;
            color: white;
            font-size: 18px;
            width: 90%;
            margin: 30px auto 0;
            height: 50px;
            border-radius: 10px;
            display: flex;
            justify-content: center;
            align-items: center;

        }

        .tips {
            width: 90%;
            margin: 20px auto 0;
        }

        .tips p {
            color: rgb(100, 100, 100);
            font-size: 13px;
            line-height: 20px;
        }

        .tips .title {
            color: rgb(50, 50, 50);
        }

        /*领取方式样式*/
        .style {
            height: 700px;
        }

        .kuaidi, .xianch {
            background-color: white;
            width: 100%;
            margin: 0 auto;
            height: 60px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .kuaidi p, .xianch p {
            padding-left: 5%;
            font-size: 18px;
            color: rgb(80, 80, 80);
        }

        .kuaidi img, .xianch img {
            width: 45px;
            padding-right: 5%;

        }

        /*选择地址样式*/
        .address {
            height: 700px;
        }

        .s_address, .t_address {
            background-color: white;
            width: 100%;
            margin: 0 auto;
            height: 60px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .s_address p, .t_address p {
            padding-left: 5%;
            font-size: 18px;
            color: rgb(80, 80, 80);
        }

        .s_address img, .t_address img {
            width: 25px;
            padding-right: 5%;

        }

        /*填写完成样式*/
        .succed {
            height: 700px;
        }

        .success {
            text-align: center;
        }

        .s_succeed, .t_succeed {
            background-color: white;
            width: 100%;
            margin: 0 auto;
            height: 78px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .title {
            margin-top: 40px;
            width: 100%;
            text-align: center;
        }

        .title p {
            color: rgb(80, 80, 80);
            font-size: 15px;
            padding: 10px 27px;
            line-height: 22px;
        }

        .二维码 {
            width: 100%;
            text-align: center;
        }

        .二维码 img {
            width: 230px;
            height: 230px;
            border: 10px solid #fff;
            border-radius: 3px;
        }

        .二维码 h4 {
            color: rgb(80, 80, 80);
            font-size: 14px;
        }

        .二维码 p {
            color: rgb(80, 80, 80);
            font-size: 14px;
            margin-bottom: 50px;
        }

        .logo {
            width: 100%;
            text-align: center;
            margin-top: 30px;
            margin-bottom: 20px;
        }

        .logo img {
            width: 290px;

        }
    </style>
@endsection

@section('content')
    <body id="submitOrder">
    <div class="informaition" style="display: block">
        <div class="status">
            <img src="{{asset('img/lottery/lj_status1.png')}}" alt="">
        </div>
        <div class="dark_color"></div>
        <div class="from">
            <div class="true_name">
                <input type="text" id="true_name" placeholder="请输入您的真实姓名" maxlength="6"
                       value="{{!empty($member['true_name'])?$member['true_name']:''}}">
            </div>
            <div class="tel">
                <input type="tel" id="mobile" placeholder="请输入您的手机号码" maxlength="11"
                       value="{{$member['mobile']}}">
            </div>
        </div>
        <div class="btn btn-step1">下一步</div>
        <div class="tips">
            <p style="margin:0px"><span class="title">郑重声明：</span>以上信息仅用于客服核实中奖情况，我们不会泄露您的个人信息，如不需修改可直接点“下一步”！</p>
        </div>
    </div>


    <div class="style" style="display: none">
        <div class="status">
            <img src="{{asset('img/lottery/lj_status2.png')}}" alt="">
        </div>
        <div class="dark_color"></div>
        <div class="kuaidi">
            <p>快递配送</p>
            <img class="selectType" nctype="no" ope="kuaidi" src="{{asset('img/lottery/lj_style2.png')}}" alt="">
        </div>
        <div class="dark_color"></div>
        <div class="xianch">
            <p>现场领取</p>
            <img class="selectType" nctype="no" ope="xianchang" src="{{asset('img/lottery/lj_style2.png')}}" alt="">
        </div>
        <div class="btn btn-step2">下一步</div>
    </div>


    <div class="address" style="display: none">
        <div class="status">
            <img src="{{asset('img/lottery/lj_status3.png')}}" alt="">
        </div>
        <div class="dark_color"></div>

        <div class="s_address default-address-xq" style="padding:0">
            <p>选择已有收货地址</p>
            <img src="{{asset('img/lottery/lj_next.png')}}" alt="">
        </div>
        <div class="dark_color"></div>
        <div class="t_address add-address-btn" style="padding:0">
            <p>重新填写收货地址</p>
            <img src="{{asset('img/lottery/lj_next.png')}}" alt="">
        </div>
    </div>

    <input class="default_is_who" type="hidden" value="{{$is_hasAddress}}"/>

    <form action="{{asset('order/showPay')}}" method="post" id="awardsForm">
        <input type="hidden" value="{{$awardRecord['prize']}}-1-0-100-{{$awardRecord['awardsrecord_id']}}" class="skuId"/>
        <input type="hidden" name="awards_sku_lst" id="awards_sku_lst">
        <input type="hidden" name="sku_source_type" id="sku_source_type" value="10">
    </form>

    <div class="success" style="display: none">
        <div class="status">
            <img src="{{asset('img/lottery/lj_status3-1.png')}}" alt="">
        </div>
        <div class="dark_color"></div>
        @if ($company['subscribe'] == 0)
            <p style="margin-bottom:15px;color: rgb(50,50,50);font-size: 20px;margin-top: 40px">恭喜，领取成功！</p>
            <div class="二维码">
                <h4>关注公众号即可查看订单与物流信息</h4>
                <img src="{{$company['wx_qrcode']}}" alt="">
                <p style="margin-bottom:5px">长按图片【识别二维码】关注公众号</p>
            </div>
        @else
            <p style="margin-bottom:20px;color: rgb(50,50,50);font-size: 20px;margin-top: 50px">恭喜，领取成功！</p>
            <p class="mt10" style="color:rgb(50,50,50);font-size:14px;"><span id="second">5</span>秒后自动跳转，如果没有跳转可<a
                        id="click_redirect" href="" style="color:#4226e6;">点击此处</a></p>
        @endif
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
                    <div class="col-xs-4">
                        <span style="margin-left: 7px">收货人</span>
                    </div>
                    <div class="col-xs-8">
                        <input type="text" class="name" placeholder="名字" autocomplete="off">
                    </div>
                </div>
                <div class="col-xs-12 input-dan">
                    <div class="col-xs-4">
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
                    <div class="col-xs-8">
                        <p id="choose-address" style="margin:0"></p>
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
            <div class="baocun blue-one col-xs-12 f18 text-center">
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
                <div class="col-xs-4">
                    收货人
                </div>
                <div class="col-xs-8">
                    <input type="text" class="name edit-shr" placeholder="名字" autocomplete="off">
                </div>
            </div>
            <div class="col-xs-12 input-dan">
                <div class="col-xs-4">
                    联系电话
                </div>
                <div class="col-xs-8">
                    <input type="text" class="phone edit-sj" placeholder="手机或固定电话" autocomplete="off">
                </div>
            </div>
            <div class="col-xs-12 input-dan">
                <div class="col-xs-4">
                    选择地区
                </div>
                <div class="col-xs-8">
                    {{-- <input type="text" id='city-picker' class="edit-pc"/>--}}
                    <p class="edit-pc" style="margin:0"></p>
                    <input type="hidden" value="" class="edit-province"/>
                    <input type="hidden" value="" class="edit-city"/>
                    <input type="hidden" value="" class="edit-area"/>
                </div>
            </div>
            <div class="col-xs-12 input-dan">
                <div class="col-xs-4">
                    详细地址
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
            <div class="col-xs-4 f18 text-center edit-del">
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
            <div class="close-btn close-choose-btn">
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
                                    <p class="checkbox-style checkbox-visited" style="margin:0 10px"></p>
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
            <span class="f14 fz col-xs-10">新增收货地址</span>
            <div class="col-xs-1 text-right">
                <img src="{{asset('img/right-btn.svg')}}" height="16" width="16"/>
            </div>
        </div>
    </div>
    <!--======================地址详情列表  END!============================-->

    <!------------------------阴影效果  START!---------------------------->
    <div class="xiaoguo">
    </div>
    <!--======================阴影效果  END!============================-->
    <div class="col-xs-12 choose-address">
        <div class="hang title col-xs-12" style="margin-top:0">
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
    <div id="pop-warn" class="pop pop-ctm-01" style="display: none; position: fixed; top: 30%;left: 15%;">
        <div class="pop-msg">
            <div id="warn-content" class="pop-msg-cnt" style="padding-top:30px;min-height: 50px"></div>
            <div id="warn-close-btn" class="pop-msg-btm"><a class="btn-h4 btn-c3" href="javascript:void(0);">确定</a>
            </div>
        </div>
    </div>
    <div id="pop-confirm" class="pop pop-ctm-01" style="display: none; position: fixed; top: 30%;left: 15%;">
        <div class="pop-msg">
            <div id="confirm-content" class="pop-msg-cnt" style="padding-top:30px;min-height: 50px">
            </div>
            <div class="pop-msg-btm">
                <a id="confirm-ok-btn" class="btn-h4 btn-c3" style="width: 96px;border: 1px solid #fe6666;"
                   href="javascript:void(0);">确定</a>
                <a id="confirm-close-btn" class="btn-h4 btn-c3"
                   style="width: 96px;border: 1px solid #cfcfcf;color: #737373;background: #fff"
                   href="javascript:void(0);">关闭</a>
            </div>
        </div>
    </div>
    <div class="__MASK_SID_DIV" style="display:none;width:100%;position: absolute; z-index: 999; left: 0px; top: 0px;">
        <div id="set_height"
             style="width:100%; height: 1024px; opacity: 0.7; background: black none repeat scroll 0% 0%; position: static; margin: 0px;"></div>
    </div>
    </body>
@endsection

@section('js')
    <script type="text/javascript" src="{{asset('js/common.js')}}"></script>
    <script type="text/javascript">
        var InterValObj; //timer变量，控制时间
        var count = 5; //间隔函数，1秒执行
        var curCount;//当前剩余秒数
        var ref_url = "";
        var awardsrecord_id = '{{$awardRecord['awardsrecord_id']}}';
        var member_id = '{{$member['member_id']}}';
        var subscribe = '{{$company['subscribe']}}';
        var s_arr = new Array();
        s_arr['no'] = "yes";
        s_arr['yes'] = "no";
        s_arr['{{asset('img/lottery/lj_style2.png')}}'] = "{{asset('img/lottery/lj_style1.png')}}";
        s_arr['{{asset('img/lottery/lj_style1.png')}}'] = "{{asset('img/lottery/lj_style2.png')}}";
        window.onload = function () {
            $(".btn-step1").on("click", function () {
                saveInfo();
            });
            $(".btn-step2").on("click", function () {
                if ($(".selectType[nctype='yes']").length == 0) {
                    message("请选择一种领奖方式");
                    return false;
                }
                if ($(".selectType[nctype='yes']").attr("ope") == "kuaidi") {
                    $(".style").hide();
                    $(".address").show();
                    document.title = "填写收货地址";
                } else {
                    getPrize();
                }
            });

            $(".selectType").on("click", function () {
                $(".selectType").attr("nctype", "no");
                $(".selectType").attr("src", "{{asset('img/lottery/lj_style2.png')}}");
                $(this).attr("nctype", s_arr[$(this).attr("nctype")]);
                $(this).attr("src", s_arr[$(this).attr("src")]);
            });
        };
        //现场领取
        function getPrize() {
            var true_name = $('#true_name').val();
            var mobile = $('#mobile').val();
            var result = check(true_name, mobile);
            if (!result) {
                return false;
            }
            $.ajax({
                type: "post",
                dataType: "json",
                url: '{{asset('marketing/lottery/receiveAward')}}',
                data: {awardsrecord_id: awardsrecord_id},
                async: false,   //是否异步操作，async默认的设置值为true（允许异步）；false（不允许异步）
                success: function (res) {
                    if (res.code == 0) {
                        $(".style").hide();
                        $(".success").show();
                        if (subscribe == '1') {
                            ref_url = '{{asset('personal/awardsRecord')}}';
                            $('#click_redirect').attr("href", ref_url);
                            curCount = count;
                            //开始计时
                            $("#second").html(curCount);
                            InterValObj = window.setInterval(SetRemainTime, 1000); //启动计时器，1秒执行一次
                        }
                    } else {
                        message(res.message);
                        window.location.reload();
                    }
                },
                error: function (msg) {
                    message('操作失败！请重新刷新页面！');
                    window.location.reload();
                }
            });
        }

        function saveInfo() {
            var true_name = $('#true_name').val();
            var mobile = $('#mobile').val();
            var result = check(true_name, mobile);
            if (!result) {
                return false;
            }
            var strUrl = '{{asset('marketing/lottery/perfectMemberInfo')}}';
            var objParameter = new Object;
            objParameter = {
                true_name: true_name,
                mobile: mobile,
                member_id: member_id,
                awardsrecord_id: awardsrecord_id
            };
            $.ajax({
                type: "post",
                dataType: "json",
                url: strUrl,
                data: objParameter,
                async: false,
                success: function (res) {
                    if (res.code == 0) {
                        $(".informaition").hide();
                        $(".style").show();
                        document.title = "选择领奖方式";
                    } else {
                        message(res.message);
                    }
                },
                error: function () {
                    message("保存失败！");
                }
            });
        }

        function check(name, mobile) {
            var telPattern = /^(0|86|17951)?(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/;
            var namePattern = /^[\u4e00-\u9fa5]+$/;
            if (name == "") {
                message("您尚未填写您的真实姓名");
                return false;
            } else if (mobile == "") {
                message("您尚未填写您的手机号码");
                return false;
            }
            if (!telPattern.test(mobile)) {
                message("您填写的电话号码有误，请重新填写");
                return false;
            }
            if (!namePattern.test(name)) {
                message("您填写的真实姓名有误，请重新填写");
                return false;
            }
            return true;
        }

        //timer处理函数
        function SetRemainTime() {
            if (curCount == 0) {
                window.clearInterval(InterValObj);//停止计时器
                window.location.href = ref_url;
            }
            else {
                curCount--;
                $('#second').html(curCount);
            }
        }
    </script>
    <script type="text/javascript" src="{{asset('js/address/lottery_address.js')}}"></script>
@endsection



