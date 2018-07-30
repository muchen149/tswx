@extends('inheritance')

@section('title')
    账号设置
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('elife_css/mui.min.css')}}">
    <link rel="stylesheet" href="{{asset('elife_css/common.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/after-sales.css')}}" charset="utf-8">
    <script src="{{asset('sd_js/font_wvum.js')}}"></script>

    <style>
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
        .me-user-information-item .itemLeft .itemText {
            margin-left: 24px;
        }
        p{
            margin-left: 5px;
        }

    </style>
@endsection

@section('content')
    <body>
        <div class="head">
            <div class="nav b-white ">
                <a class="return font-14 color-80" href="{{asset('elife/personal/index')}}"><img src="{{asset('sd_img/next.png')}}" alt="" class="size-26" style="transform:rotate(180deg);"></a>
                <a class="title font-18 color-80" href="#">身份验证</a>
            </div>
        </div>


        @if($explainList)
            <div style="margin-top: 50px;">
                <div id="light" class="white_content">
                    <div class="color-80">
                        <span style="font-size: 5px;margin: 0px 5px">身份信息（必填）</span> </div>
                    <div class="text_card" style="margin-top: 10px">
                        <input type="text" id="v_name" name="v_name" class="style_card" style="margin-bottom : 0px;font-size: 12px;" placeholder="您的真实姓名" autocomplete="off" >
                        <input id="v_idcard"  name="v_idcard" class="style_card" type="text" style="margin-bottom : 0px;font-size: 12px;" placeholder="您的身份证号码（将加密处理）" autocomplete="off" >
                    </div>
                </div>
                <div id="light" class="white_content">
                    <div class="color-80" style="margin-top: 10px">
                        <span style="font-size: 5px;margin: 5px 5px">身份证正反面照片（选填）</span>
                    </div>
                    <div class="text_card" style="margin-top: 10px; background-color: white">
                        <span class="font-12" style="color: #b2b2b2">温馨提示：请上传原始比例的身份证正反面，请勿裁剪涂改，保证身份信息清晰显示否则无法通过审核</span>
                    </div>
                    <!--图片上传-->
                    <section class="miblewrap01 goods-apply-upload">
                        <h3 class="mible-tit">上传图片</h3>

                        <div id="img-wrapper" class="img-wrapper">

                            <div class="upload-btn-box" style="display: inline-block">
                                <input id="pageUpload" name="uploadPic" class="upload-btn" type="file">
                            </div>

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
                </div>

                <div>
                    <button class="to_examine" style="width: 100%;height: 18%;background-color: #e42f46;font-size: 18px;">
                        <span class="btn-buy" style="color:#ffffff;">立即验证</span>
                    </button>
                </div>
                <div id="light" class="white_content" style="margin-top: 5px">
                    <div class="color-80">
                             @foreach($explainList as $val)
                                <div style=" margin: 5px 0px;background-color: white;width: 100%">
                                    {!! $val->doc_content !!}
                                </div>
                            @endforeach
                    </div>
                </div>
            </div>
        @endif
    </body>
@endsection

@section('js')

    <script type="text/javascript">
        {{--var scriptUrl = '{{asset('js/prefect_apply.js')}}';--}}
        {{--var uploadImgUrl = '{{asset('personal/uploadImg')}}';--}}
        {{--var delImgUrl = '{{asset('personal/delImg')}}';--}}

        $(function () {
            $('.list-item').on('click', function () {
                $(this).addClass('active');
                $('#applyType').val($(this).attr('data-type'));
                $('.list-item').not(this).removeClass('active');
            });

            $('.to_examine').on('click', function () {
                v_name = $("#v_name").val(); //获取真实姓名
                v_idcard = $("#v_idcard").val(); //获取身份证号码
                y_name = /^([\u4e00-\u9fa5]){2,7}$/;       //只能是中文，长度为2-7位
                if (!y_name.test(v_name)) {
                    alert("请正确输入姓名!");//请将“字符串类型”要换成你要验证的那个属性名称！
                    return false;
                }
                var y_idcard = /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/;
                if (y_idcard.test(v_idcard) === false) {
                    alert("身份证输入不合法");
                    return false;
                }
                $("#mainForm").submit();
            })
        });
    </script>
    <script type="text/javascript" src="{{asset('js/prefect_apply.js')}}"></script>
@endsection

