@extends('inheritance')

@section('title')
    用户申请
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('css/header.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/after-sales.css')}}" charset="utf-8">
    <style type="text/css">
        .len-input {
            border: 1px solid #e8e8e8;
            border-radius: 0;
            color: #848689;
            float: left;
            font-size: 14px;
            height: 25px;
            text-indent: 0;
            padding: 10px;
        }
    </style>
@endsection

@section('content')
    <body style="background-color: rgb(240,240,240)">
    <div id="header"></div>
    <div class="jd-wrap">
        <form id="mainForm" action="{{asset('personal/submitApply')}}" method="post">
            <section class="miblewrap01 goods-apply-service">
                <h3 class="mible-tit">申请类型</h3>
                <ul class="list-check-box">
                    <li class="list-item" data-type="groupbuy">
                        <div class="linein">团采商用户</div>
                    </li>
                    <li class="list-item" data-type="proxy">
                        <div class="linein">代理商用户</div>
                    </li>
                </ul>
                <div id="showPopHint" style="font-weight: bolder;font-size:10px;color:#686868"></div>
            </section>
            <section class="miblewrap01 goods-apply-cout">
                <h3 class="mible-tit">用户名称</h3>

                <div class="add-del">
                    <input id="companyName" name="companyName" class="len-input" type="text">
                </div>
                <div class="msg-text">
                    个人用户请填写真实姓名，企业用户请填写公司名称</div>
            </section>
            <section class="miblewrap01 goods-apply-cout">
                <h3 class="mible-tit">联系电话</h3>

                <div class="add-del">
                    <input id="companyPhone" name="companyPhone" class="len-input" type="text">
                </div>
            </section>

            <input id="applyType" name="applyType" type="hidden" value="{{$applyType}}">
            <input name="uploadPic" id="hidtxt_uploadPic" value="" type="hidden">
        </form>
        <!--图片上传-->
        <section class="miblewrap01 goods-apply-upload">
            <h3 class="mible-tit">上传图片</h3>

            <div id="img-wrapper" class="img-wrapper">

                    <div class="upload-btn-box" style="display: inline-block">
                        <input id="pageUpload" name="uploadPic" class="upload-btn" type="file">
                    </div>
                <div style="clear:both"></div>
                <div class="msg-text">
                    个人用户上传身份证正反两面照片，企业用户上传营业执照照片
                </div>
            </div>
            <script type="text/javascript" src="{{asset('js/ajaxfileupload.js')}}"></script>
        </section>
        <div class="jd-btns"><a href="javascript:;" class="btn-h3 btn-c3">提交</a></div>
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
    </div>
    <div class="__MASK_SID_DIV" style="display:none;width:100%;position: absolute; z-index: 999; left: 0px; top: 0px;">
        <div id="set_height"  style="width:100%; height: 1024px; opacity: 0.7; background: black none repeat scroll 0% 0%; position: static; margin: 0px;"></div>
    </div>
    </body>
@endsection

@section('js')
    <script type="text/javascript" src="{{asset('js/common.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/common-top.js')}}"></script>
    <script type="text/javascript">
        var scriptUrl = '{{asset('js/prefect_apply.js')}}';
        var uploadImgUrl = '{{asset('personal/uploadImg')}}';
        var delImgUrl = '{{asset('personal/delImg')}}';

        $(function () {
            var phoneReg = /^(0|86|17951)?(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/;

            var applyType = '{{$applyType}}';
            $('.list-item[data-type=' + applyType + ']').addClass('active');

            $('.list-item').on('click', function () {
                $(this).addClass('active');
                $('#applyType').val($(this).attr('data-type'));
                $('.list-item').not(this).removeClass('active');
            });

            $(".btn-h3").on("click", function () {
                if ($('#companyName').val() == '') {
                    message("请填写用户名称");
                    return false;
                }
                if ($('#companyPhone').val() == '') {
                    message("请填写联系电话");
                    return false;
                }
                if (!phoneReg.test($('#companyPhone').val())) {
                    message("请填写正确的联系电话");
                    return false;
                }
                if ($('#hidtxt_uploadPic').val() == '') {
                    message("请上传相应资质照片");
                    return false;
                }
                $("#mainForm").submit();
            });
        });
    </script>
    <script type="text/javascript" src="{{asset('js/prefect_apply.js')}}"></script>
@endsection



