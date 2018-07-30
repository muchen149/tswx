@extends('inheritance')

@section('title')
    我要供货
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('sd_css/common.css')}}">
    <link rel="stylesheet" href="{{asset('sd_css/mui.min.css')}}">
    <link rel="stylesheet" href="{{asset('sd_css/tabbar.css')}}">
{{--    <link rel="stylesheet" href="{{asset('sd_css/imgUp.css')}}">--}}

    <link rel="stylesheet" href="{{asset('sd_css/address.css')}}">
    <link rel="stylesheet" href="{{asset('sd_css/bootstrap.min.css')}}">

    <link rel="stylesheet" type="text/css" href="{{asset('sd_css/after-sales.css')}}" charset="utf-8">

    <style>

        .procurement-msg{
            margin-top: 54px;
        }
        .mui-bar-nav{

        }
        .supply-address{
            width:100%;
        }
        .supply-address li{
            margin-left: 15px;
            height: 60px;
            border-bottom: 1px solid rgb(220,220,220);
            text-align: left;
            font-size: 14px;
            color: rgb(80,80,80);
            line-height: 50px;
        }
        .supply-address li img{
            margin-top: -3px;
            width: 32px;
        }

        .procurement-msg .mui-input-row label{
            margin-top: 12px;
        }
        .procurement-msg .mui-input-row input{
            margin-top: 10px;
        }

        .procurement-msg .mui-input-row {
            height: 60px;
            color: rgb(80,80,80);
            font-size: 14px;
            background-color: white;
        }
        .procurement-msg .mui-input-row input{
            font-size: 12px;
        }
        .mui-input-group .mui-input-row:after {
            position: absolute;
            right: 0;
            bottom: 0;
            left: 15px;
            height: 1px;
            content: '';
            -webkit-transform: scaleY(.5);
            transform: scaleY(.5);
            background-color: rgb(200, 199, 204);
        }

        .procurement-msg .mui-input-group{
            border: none;
            background-color: rgb(240,240,240);
        }
        .mui-input-group:before {
            position: absolute;
            right: 0;
            bottom: 0;
            left: 0;
            height: 0px;
            content: '';
            -webkit-transform: scaleY(.5);
            transform: scaleY(.5);
            background-color: rgb(200, 199, 204);
        }
        .mui-input-group:after {
            position: absolute;
            right: 0;
            bottom: 0;
            left: 0;
            height: 0px;
            content: '';
            -webkit-transform: scaleY(.5);
            transform: scaleY(.5);
            background-color: rgb(200, 199, 204);
        }
        .mui-input-row .mui-input-clear~.mui-icon-clear{
            top: 20px;
        }

        /*服务协议*/
        .arguement{
            margin-top: 30px;
        }
        .arguement img{
            width: 18px;
            margin-right: 5px;
        }
        .arguement a{
            color: dodgerblue;
        }
        .arguement a:active{
            color: dodgerblue;
        }
        .arguement a:visited{
            color: dodgerblue;
        }

        /*提交按钮*/
        .pay-privilege-btn{
            width: 85%;
            height: 44px;
            margin: 18px auto 40px;
            background-color: #f53a3a;
            border-radius: 8px;
        }

        /*文本框*/
        .wordCount{
            background-color: white;
            position:relative;
            width: 100%;
            height: 180px;
            margin-bottom: 10px;
            overflow: hidden;
        }
        .wordCount p{
            margin-left: 15px;
            font-size: 14px;
            color: rgb(80,80,80);
            margin-top: 15px;
            margin-bottom: 10px;
        }
        .wordCount textarea{
            background-color: rgb(245,245,245);
            width: 100%;
            height: 100px;
            resize: none;
            font-size: 12px;
            color: rgb(100,100,100);
        }
        .wordCount .wordwrap{
            position:absolute;
            right: 6px;
            bottom: 4px;
        }
        .wordCount .word{
            color: red;
            padding: 0 4px;
            font-size: 12px;
        }


    </style>
@endsection

@section('content')
<body id="submitOrder">
<header class="mui-bar mui-bar-nav b-white">
    <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left color-80" href="me.html"></a>
    <h1 class="mui-title">我要供货</h1>
</header>
<div class="procurement-msg">

    <form class="mui-input-group" action="{{asset('member/supplyAdd')}}" id="mainForm" method="post">
        <div class="mui-input-row">
            <label>公司所在地</label>
            <input  type="hidden"   name="area_info" id="area_info">　
            <p id="choose-address"  style="width: 60%;height: 25px;margin-top:0px;margin-left: 30%;"></p>
            <img src="{{asset('sd_img/next.png')}}"  id="right-btn"  style="position: absolute;right: 2%;top: 12px;width: 30px;height: 32px;transform: rotate(0deg); transition: all 0.5s;">
            <input type="hidden" id="province"/>
            <input type="hidden" id="city"/>
            <input type="hidden" id="area"/>
        </div>

        <div class="mui-input-row">
            <label>联系人姓名</label>
            <input type="text" class="mui-input-clear" placeholder="请输入您的姓名" name="contacts_name" id="contacts_name">
        </div>
        <div class="mui-input-row">
            <label>联系电话</label>
            <input type="tel" class="mui-input-clear" placeholder="请输入您的联系电话" maxlength="11" name="contacts_mobile" id="contacts_mobile">
        </div>
        <div class="mui-input-row">
            <label>电子邮箱</label>
            <input type="email" class="mui-input-clear" placeholder="请输入您的邮箱地址" name="email" id="email">
        </div>
        <div class="mui-input-row">
            <label>公司名称</label>
            <input type="text" class="mui-input-clear" placeholder="请输入公司名称" name="company_name" id="company_name">
        </div>
        <div class="mui-input-row">
            <label>公司地址</label>
            <input type="text" class="mui-input-clear" placeholder="请输入公司详细地址" name="address" id="address">
        </div>
        <div class="mui-input-row">
            <label>公司座机</label>
            <input type="text" class="mui-input-clear" placeholder="请输入公司座机" name="company_phone" id="company_phone">
        </div>
        <div class="wordCount" id="wordCount">
            <p>经营范围</p>
            <textarea placeholder="请填写经营范围信息" name="management_scope" id="management_scope"></textarea>
            <span class="wordwrap"><var class="word">200</var>/200</span>
        </div>

        <input name="uploadPic" id="hidtxt_uploadPic" value="" type="hidden">

    </form>

</div>


<!--图片上传-->
<section class="miblewrap01 goods-apply-upload">
    <h3 class="mible-tit">提交认证资料</h3>

    <div id="img-wrapper" class="img-wrapper">

        <div class="upload-btn-box" style="display: inline-block">
            <input id="pageUpload" name="uploadPic" class="upload-btn" type="file">
        </div>
        <div style="clear:both"></div>
        <div class="msg-text" style="color: red">
            注：认证信息包括营业执照、组织机构代码证、身份证共3张
        </div>
    </div>

</section>
<div id="pop-warn" class="pop pop-ctm-01" style="display: none; position: fixed; top: 30%;left: 15%;;">
    <div class="pop-msg">
        <div id="warn-content" class="pop-msg-cnt" style="padding-top:30px;min-height: 50px">
        </div>
        <div id="warn-close-btn" class="pop-msg-btm"><a class="btn-h4 btn-c3" href="javascript:void(0);">关闭</a>
        </div>
    </div>
</div>
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


<div class="arguement flex-center font-12 color-80">
    <img src="{{asset('sd_img/agree.png')}}" alt="">
    <img src="{{asset('sd_img/unagree.png')}}" alt="" style="display: none">
    我已阅读并同意水丁管家<a href="arguement_supply.html">采购协议</a>
</div>
<div class="pay-privilege-btn flex-center color-white font-14" id="sub_btn">提交资料</div>

<div style="text-align: center;margin-bottom: 40px">
    <li class="font-12 color-100">提交资料1-3个工作日后会有工作人员与您联系</li>
</div>

<!------------------------阴影效果  START!---------------------------->
<div class="xiaoguo">

</div>
<!--======================阴影效果  END!============================-->

{{--选择地址--}}
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
    <script src="{{asset('sd_js/jquery.min.js')}}"></script>
    <script src="{{asset('sd_js/font_wvum.js')}}"></script>
{{--    <script src="{{asset('sd_js/imgUp1.js')}}"></script>--}}

    <script type="text/javascript" src="{{asset('sd_js/common.js')}}"></script>
    <script type="text/javascript" src="{{asset('sd_js/ajaxfileupload.js')}}"></script>
    <script type="text/javascript" src="{{asset('sd_js/member_supply.js')}}"></script>
    <script type="text/javascript" src="{{asset('sd_js/manage_procurement/company_address.js')}}"></script>
    <script src="{{asset('sd_js/swiper.jquery.min.js')}}"></script>

    <script>

        var scriptUrl = '{{asset('sd_js/member_supply.js')}}';
        var uploadImgUrl = '{{asset('personal/uploadImg')}}';
        var delImgUrl = '{{asset('personal/delImg')}}';

        var phoneReg = /^(0|86|17951)?(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/;

        $(function(){
            //先选出 textarea 和 统计字数 dom 节点
            var wordCount = $("#wordCount"),
                    textArea = wordCount.find("textarea"),
                    word = wordCount.find(".word");
            //调用
            statInputNum(textArea,word);
        });
        /*
         * 剩余字数统计
         * 注意 最大字数只需要在放数字的节点哪里直接写好即可 如：<var class="word">200</var>
         */
        function statInputNum(textArea,numItem) {
            var max = numItem.text(),
                    curLength;
            textArea[0].setAttribute("maxlength", max);
            curLength = textArea.val().length;
            numItem.text(max - curLength);
            textArea.on('input propertychange', function () {
                numItem.text(max - $(this).val().length);
            });
        }


        $("#sub_btn").on("click", function () {

            if ($('#choose-address').text() == '') {
                message("请填写公司所在地");
                return false;
            }

            if ($('#contacts_name').val() == '') {
                message("请填写联系人姓名");
                return false;
            }

            if ($('#contacts_mobile').val() == '') {
                message("请填写联系人电话");
                return false;
            }

            if (!phoneReg.test($('#contacts_mobile').val())) {
                message("请填写正确的联系人电话");
                return false;
            }

            if ($('#company_name').val() == '') {
                message("请填写公司名字");
                return false;
            }

            if ($('#address').val() == '') {
                message("请填写公司详细地址");
                return false;
            }

//            if ($('#company_phone').val() == '') {
//                message("请填写公司座机电话");
//                return false;
//            }

            if ($('#management_scope').val() == '') {
                message("请填写公司的经营范围信息");
                return false;
            }

            if ($('#hidtxt_uploadPic').val() == '') {
                message("请上传相应资质照片");
                return false;
            }

            $('#com_uploadPic').val($('#hidtxt_uploadPic').val());
            $('#area_info').val($('#choose-address').text());

            $("#mainForm").submit();

        });


    </script>

@endsection



