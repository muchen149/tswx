@extends('inheritance')

@section('title')
    申请采购
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('sd_css/common.css')}}">
    <link rel="stylesheet" href="{{asset('sd_css/mui.min.css')}}">
    <link rel="stylesheet" href="{{asset('sd_css/tabbar.css')}}">
    <link rel="stylesheet" href="{{asset('sd_css/imgUp.css')}}">
    <link rel="stylesheet" href="{{asset('sd_css/address.css')}}">
    <link rel="stylesheet" href="{{asset('sd_css/bootstrap.min.css')}}">

    <link rel="stylesheet" type="text/css" href="{{asset('sd_css/after-sales.css')}}" charset="utf-8">

    <style>
        /*头部标签*/
        .head-tab{
            width: 100%;
            height: 60px;
            background-color: white;
            position: fixed;
            z-index: 99;
            top: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 1px 1px rgba(100,100,100,0.3);
        }
        .tab-contain{
            width: 220px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
        }
        .card{
            width: 110px;
            height: 36px;
            background-color: #e83828;
            color: white;
            border-radius: 6px 0 0 6px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .card-1{
            width: 110px;
            height: 36px;
            border-top:1px solid rgb(200,200,200);;
            border-left: 1px solid rgb(200,200,200);
            border-bottom: 1px solid rgb(200,200,200);
            border-radius: 6px 0px 0px 6px;
            background-color: white;
            color: rgb(80,80,80);
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .list{
            width: 110px;
            height: 36px;
            border-top:1px solid rgb(200,200,200);;
            border-right: 1px solid rgb(200,200,200);
            border-bottom: 1px solid rgb(200,200,200);
            border-radius: 0px 6px 6px 0px;
            background-color: white;
            color: rgb(80,80,80);
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .list-1{
            width: 120px;
            height: 36px;
            background-color: #e83828;
            color: white;
            border-radius: 0px 6px 6px 0px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .procurement-msg{
            margin-top: 70px;
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
            margin: 18px auto 10px;
            background-color: #f53a3a;
            border-radius: 8px;
        }

    </style>
@endsection

@section('content')
<body  id="submitOrder">
<div class="head-tab">
    <div class="tab-contain">
        <div class="card" id="card">企业用户</div>
        <div class="list" id="list">个人用户</div>
    </div>
</div>

<div class="procurement-msg" id="company">
    <form class="mui-input-group" action="{{asset('member/submitAdd')}}" id="mainForm" method="post">

        <div class="mui-input-row" style="position: relative" >
            <label>公司所在地</label>
            <input  type="hidden"   name="area_info" id="area_info">　
            <p id="choose-address"  style="width: 60%;height: 25px;margin-top:0px;margin-left: 30%;"></p>
            <img src="{{asset('sd_img/next.png')}}"  id="right-btn"  style="position: absolute;right: 2%;top: 12px;width: 30px;height: 32px;transform: rotate(0deg); transition: all 0.5s;">
            {{--<p  style="margin-top: 20px;"></p>--}}
            <input type="hidden" id="province"/>
            <input type="hidden" id="city"/>
            <input type="hidden" id="area"/>

        </div>


        <div class="mui-input-row">
            <label>联系人姓名</label>
            <input type="text" class="mui-input-clear" placeholder="请输入姓名" name="contacts_name" id="contacts_name">
        </div>
        <div class="mui-input-row">
            <label>联系电话</label>
            <input type="tel" class="mui-input-clear" placeholder="请输入电话" maxlength="11" name="contacts_mobile" id="contacts_mobile">
        </div>

        <div class="mui-input-row">
            <label>公司名字</label>
            <input type="text" class="mui-input-clear" placeholder="请输入公司名字" name="company_name" id="company_name">
        </div>
        <div class="mui-input-row">
            <label>公司地址</label>
            <input type="text" class="mui-input-clear" placeholder="请输入公司详细地址" name="address" id="address">
        </div>

        <div class="mui-input-row">
            <label>公司电话</label>
            <input type="text" class="mui-input-clear" placeholder="请输入电话" name="company_phone" id="company_phone">
        </div>


        <input id="applyType" name="applyType" type="hidden" value="1">
        <input name="com_uploadPic" id="com_uploadPic" value="" type="hidden">

    </form>


</div>


<div class="procurement-msg" id="personal" hidden>
    <form class="mui-input-group" action="{{asset('member/submitAdd')}}" id="p_mainForm" method="post">
        <div class="mui-input-row">
            <label>联系人姓名</label>
            <input type="text" class="mui-input-clear" placeholder="请输入姓名" name="contacts_name" id="p_contacts_name">
        </div>
        <div class="mui-input-row">
            <label>联系电话</label>
            <input type="tel" class="mui-input-clear" placeholder="请输入电话" maxlength="11" name="contacts_mobile" id="p_contacts_mobile">
        </div>
        <div class="mui-input-row">
            <label>电子邮箱</label>
            <input type="email" class="mui-input-clear" placeholder="请输入邮箱地址" name="email" id="email">
        </div>

        <input id="applyType" name="applyType" type="hidden" value="2">

        <input name="per_uploadPic" id="per_uploadPic" value="" type="hidden">

    </form>

</div>

<input name="uploadPic" id="hidtxt_uploadPic" value="" type="hidden">

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


<!--图片上传-->
<section class="miblewrap01 goods-apply-upload">
    <h3 class="mible-tit">提交认证资料</h3>

    <div id="img-wrapper" class="img-wrapper">

        <div class="upload-btn-box" style="display: inline-block">
            <input id="pageUpload" name="uploadPic" class="upload-btn" type="file">
        </div>
        <div style="clear:both"></div>
        <div class="msg-text">
            企业用户上传营业执照照片，个人用户上传身份证正反两面照片
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
<div class="pay-privilege-btn flex-center color-white font-14" id="per_sub" style="display: none">提交资料</div>
<div class="pay-privilege-btn flex-center color-white font-14" id="com_sub">提交资料</div>

<div style="text-align: center;margin-bottom: 40px">
    <li class="font-12 color-100">提交资料1-3个工作日后会有工作人员与您联系</li>
</div>

</body>
@endsection

@section('js')

    <script src="{{asset('sd_js/jquery.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('sd_js/common.js')}}"></script>
    <script type="text/javascript" src="{{asset('sd_js/ajaxfileupload.js')}}"></script>
    <script type="text/javascript" src="{{asset('sd_js/prefect_apply.js')}}"></script>

    <script type="text/javascript" src="{{asset('sd_js/manage_procurement/company_address.js')}}"></script>
    <script src="{{asset('sd_js/swiper.jquery.min.js')}}"></script>

    <script>

        var scriptUrl = '{{asset('sd_js/prefect_apply.js')}}';
        var uploadImgUrl = '{{asset('personal/uploadImg')}}';
        var delImgUrl = '{{asset('personal/delImg')}}';

        $(function () {

            $('#list').on('click',function(){
                $('#card').addClass('card-1').removeClass('card');
                $('#list').addClass('list-1').removeClass('list');
                $('#tab1').css('display','none');
                $('#tab2').css('display','block');

                $('#company').hide();
                $('#personal').show();

                $('#com_sub').hide();
                $('#per_sub').show();
            });


            $('#card').on('click',function(){
                $('#card').addClass('card').removeClass('card-1');
                $('#list').addClass('list').removeClass('list-1');
                $('#tab2').css('display','none');
                $('#tab1').css('display','block');


                $('#company').show();
                $('#personal').hide();

                $('#com_sub').show();
                $('#per_sub').hide();
            });

            var phoneReg = /^(0|86|17951)?(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/;



            $("#com_sub").on("click", function () {

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

//                if ($('#company_phone').val() == '') {
//                    message("请填写公司座机电话");
//                    return false;
//                }

                if ($('#hidtxt_uploadPic').val() == '') {
                    message("请上传相应资质照片");
                    return false;
                }
                $('#com_uploadPic').val($('#hidtxt_uploadPic').val());
                $('#area_info').val($('#choose-address').text());

                $("#mainForm").submit();
            });


           //个人信息
            $("#per_sub").on("click", function () {

                if ($('#p_contacts_name').val() == '') {
                    message("请填写联系人姓名");
                    return false;
                }

                if ($('#p_contacts_mobile').val() == '') {
                    message("请填写联系人电话");
                    return false;
                }

                if (!phoneReg.test($('#p_contacts_mobile').val())) {
                    message("请填写正确的联系人电话");
                    return false;
                }

                if ($('#hidtxt_uploadPic').val() == '') {
                    message("请上传相应资质照片");
                    return false;
                }

                $('#per_uploadPic').val($('#hidtxt_uploadPic').val());

                $("#p_mainForm").submit();
            });
        });

    </script>


@endsection



