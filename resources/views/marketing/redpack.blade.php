@extends('inheritance')

@section('title')
    {{$scanData['activity_name']}}
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('css/csshake.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('css/after-sales.css')}}" rel="stylesheet">
    <style type="text/css">
        .modal-backdrop.in {
            filter: alpha(opacity=80);
            opacity: .7
        }

        a:hover {
            text-decoration: none
        }

        .intro {
            text-align: center;
            background-color: #fff;
            border-radius: 8px;
            font-size: 15px;
            height: 200px;
            color: #793707;
            width: 90%;
            font-weight: 400;
            font-family: '微软雅黑', Arial, Helvetica, sans-serif;
            line-height: 24px;
        }

        .btn-ok {
            width: 100%;
            height: 41px;
            color: #33a0e5;
            border-bottom-left-radius: 8px;
            border-bottom-right-radius: 8px;
            display: block;
            line-height: 41px;
            font-size: 16px;
        }

        .second_p {
            margin-bottom: 0;
            word-spacing: 8px;
            letter-spacing: 1px;
            padding: 10px 21px;
            text-align: left;
            color: rgb(80, 80, 80);
            font-size: 15px;
            height: 115px;
            border-bottom: 1px solid rgb(220, 220, 220)
        }

        .main ul {
            width: 100%;
            margin:0px;
        }

        .main li {
            list-style: none;
            width: 100%;
            border-bottom: 1px solid rgb(230, 230, 230);
            height: 40px;
            line-height: 40px;
            font-size: 14px;
            color: rgb(100, 100, 100);
        }

        .list {
            list-style: none;
            width: 100%;
            height: 40px;
            line-height: 40px;
            font-size: 14px;
            color: rgb(50, 50, 50);
        }

        .mess {
            background-color: white;
        }

        .title {
            width: 100%;
            height: 40px;
            text-align: center;
            border-bottom: 10px solid rgb(240, 240, 240);
        }

        .title p {
            color: rgb(50, 50, 50);
            font-size: 18px;
            font-weight: bold;
            height: 40px;
            line-height: 40px;
        }

        .jieshao p {
            font-size: 16px;
            color: rgb(100, 100, 100);
            line-height: 24px;
        }
        .intro-lingqu {
            text-align: center;
            background-color: #fff;
            border-radius: 8px;
            font-size: 15px;
            height: 360px;
            color: #793707;
            width: 90%;
            font-weight: 400;
            font-family: '微软雅黑', Arial, Helvetica, sans-serif;
            line-height: 24px;
        }

        .btn-lingqu {
            width: 100%;
            height: 41px;
            color: #fff;
            background-color: #c32d38;
            border-bottom-left-radius: 8px;
            border-bottom-right-radius: 8px;
            display: block;
            line-height: 41px;
            font-size: 16px;
        }
        .btn-lingqu:hover,.btn-lingqu:link,.btn-lingqu:visited{
            color:#fff
        }

       .logoActivity{
             width:98%;
             left: 1%;
             text-align: center;
             top: 0%; position: absolute;
         }

        .logoActivitySuccess{
            width:100%;
            text-align: center;
            position: absolute;
        }
        .red>.redbutton {
            position: absolute;
            top: 68%;
            left: 36%;
            width: 30%;
        }

        .red-jg {
            position: absolute;
            top: 60%;
            text-align: center;
            padding: 10px;
        }

        .red-jg>h1 {
            font-size: 16px;
            color: #fef4d4;
            line-height: 40px;
        }


    </style>
@endsection

@section('content')
    <body style="background-color: #ec6129">
    <div class="red" style="display: block">
        <img class="bg" src="{{asset('img/lottery/hb_bg.png')}}"/>
        {{--<img class="rmb" src="{{asset('img/lottery/rmb.png')}}"/>--}}
        <img id="logoImg" class="logo" src="{{asset($scanData['company_logo'])}}"/>
        <img class="redbutton" src="{{asset('img/lottery/chou.png')}}"/>
    </div>

    <div class="success" style="display: none"><!-- shake-chunk -->
        {{--<img class="bg" src="{{asset('img/lottery/result_bg.png')}}"/>--}}
        <img id="logoActSuccess" class="logo" src="{{asset($scanData['company_logo'])}}"/>
        <div class="red-jg">
            <h1>恭喜中奖，好运连连！</h1>
            <h1 style="color:#323232;margin-bottom:20px;"><strong style="font-size:38px;font-stretch:expanded;color:#fef4d4;"
                                                                  class="price">1.00</strong><span style="font-size:16px;color:#fef4d4;"
                                                                                                   class="ext">元</span></h1>
            <img class="btn-dole" style="width:55%" src="{{asset('img/lottery/redpack-btn.png')}}"/>
        </div>
    </div>

    <div class="subscribe" style="display: none"><!-- shake-chunk -->
        <img class="bg" src="{{asset('img/lottery/result_bg.png')}}"/>
        <img class="logo" src="{{asset($scanData['company_logo'])}}"/>
        <div class="red-jg" style="top:30%">
            <img class="no" style="width:75%" src="{{asset('img/lottery/sub_tishi1.png')}}"/>
            <img class="no" style="width:55%;margin-top:50px" src="{{asset($scanData['company_qrcode'])}}"/>
            <p style="color:rgb(80,80,80);margin-top:10px;font-size:14px;">长按【识别二维码】关注公众号<br/>关注后前往“中奖记录”领取</p>
        </div>
    </div>

    <div class="noredpack" style="display: none"><!-- shake-chunk -->
        {{--<img class="bg" src="{{asset('img/lottery/result_bg.png')}}"/>--}}
        {{--<img class="logo" src="{{asset($scanData['company_logo'])}}"/>--}}
        <div class="red-jg" style="top:32%">
            <img class="no" style="width:72%" src="{{asset('img/lottery/noredpack.png')}}"/>
        </div>
    </div>
    <!-- End 红包 -->

    <div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="background-color:inherit;border:none;box-shadow: none">
                <img style="height:38px;width:38px;margin-top:60%;margin-left:48%" src="{{asset('img/loading.gif')}}">
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content"
                 style="margin-top:51%;margin-left:9%;text-align:center;background-color:inherit;border:none;box-shadow: none">
                <div class="intro">
                    <p style="margin-bottom:0;padding-top:12px;color:rgb(50,50,50);font-weight:600;font-size:16px;height:42px;">
                        恭喜，成功领取红包！</p>
                    <p class="second_p"></p>
                    <a class="btn-ok" data-dismiss="modal" aria-hidden="true">确定</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal4" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel4"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content"
                 style="margin-top:32%;margin-left:9%;text-align:center;background-color:inherit;border:none;box-shadow: none">
                <div class="intro-lingqu" style="height:335px">
                    <p class="first-p"
                       style="margin-bottom:10px;padding-top:12px;color:#c32d38;font-size:17px;height:42px;word-spacing:8px; letter-spacing: 1px;">糟糕，{{$scanData['plat_vrb_name']}}不够!</p>
                    <img class="proImg" style="width:70%" src="{{asset('img/lottery/lottery_points_no.png')}}"/>
                    <p class="third_p" style="margin-bottom:0;padding-top:12px;color:#505050;font-size:16px;height:45px;word-spacing:8px; letter-spacing: 1px;">努力扫码，挣够{{$scanData['plat_vrb_name']}}再来</p>
                    <a class="btn-lingqu" data-dismiss="modal" aria-hidden="true">确定</a>
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
            <div id="confirm-content" class="pop-msg-cnt" style="padding-top:18px;min-height: 50px;font-size: 15px;">
            </div>
            <div class="pop-msg-btm">
                <a id="confirm-ok-btn" class="btn-h4 btn-c3" style="width: 96px;border: 1px solid #fe6666;"
                   href="javascript:void(0);">确定</a>
                <a id="confirm-close-btn" class="btn-h4 btn-c3"
                   style="width: 96px;border: 1px solid #cfcfcf;color: #737373;background: #fff"
                   href="javascript:void(0);">取消</a>
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
    <script type="text/javascript" src="{{asset('js/bootstrap.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/common.js')}}"></script>
    <script type="text/javascript">
        var yesb_available = '{{$scanData['yesb_available']}}';
        var cost_vrcoin = '{{$scanData['cost_vrcoin']}}';
        var subscribe = '{{$scanData['subscribe']}}';
        var company_id = '{{$scanData['company_id']}}';
        var card_id = '{{$scanData['card_id']}}';
        var activity_id = '{{$scanData['activity_id']}}';
        var lottery_infonum = '{{$scanData['lottery_infonum']}}';
        var activity_type = '{{$scanData['activity_type']}}';
        var plat_vrb_name = '{{$scanData['plat_vrb_name']}}'; //虚拟币
        var isLogo = '{{$scanData['isLogo']}}';

        var json_param = '';
        var prize_level = "";

       //alert(isLogo==0);
        if(isLogo==0){
           /// alert(isLogo);
            document.getElementById('logoImg').className ='logoActivity';
            document.getElementById('logoActSuccess').className ='logoActivitySuccess';
        }

        $(function () {
            $(".btn-ok").on("mousedown", function () {
                $(this).css("background-color", "rgb(240,240,240)");
            });
            $(".redbutton").click(function () {
                lottery();
            });
        });
        function lottery() {
            $(".redbutton").unbind('click').css("cursor", "default");
            var objParameter = new Object;
            objParameter = {
                activity_id: activity_id,
                card_id: card_id
            };
            if (activity_type == 2) {
                if (parseInt(yesb_available) < parseInt(cost_vrcoin)) {
                    $('#exampleModal4').modal({
                        show: true,
                        backdrop: 'static',
                        keyboard: false
                    });
                    $(".redbutton").click(function () {
                        lottery();
                    });
                    return false;
                } else {
                    var con = confirm('花费' + cost_vrcoin + plat_vrb_name +'玩一次吗？');
                    if (!con) {
                        $(".redbutton").click(function () {
                            lottery();
                        });
                        return false;
                    }
                }
            }
            $.ajax({
                type: 'POST',
                url: '{{asset('marketing/lottery/handleLottery')}}',
                dataType: 'json',
                data: objParameter,
                cache: false,
                async: true,
                beforeSend: function () {
                    $('.red').css({"width": "60%", margin: "25% 20%"}).find("a").css("font-size","8px");
                    $('.red').find("p").css("font-size","8px");
                    $('.red').addClass('shake-chunk');
                },
                success: function (res) {
                    $('.red').removeClass('shake-chunk').hide();
                    json_param = res.data;
                    if (res.code != 0) {
                        message(res.message);
                        return false;
                    } else {
                        if (json_param.exchange_type == 1) {
                            $('.success').show();
                            $(".price").html(number_format(json_param.price, 2));
                            $(".btn-dole").on("click", function () {
                                $(this).unbind("click");
                                if(subscribe == "1"){
                                    $.ajax({
                                        type: "post",
                                        url: '{{asset('marketing/lottery/receiveRedpack')}}',
                                        data: {awardsrecord_id: json_param.awardsrecord_id},
                                        dataType: "json",
                                        beforeSend: function () {
                                            $('#exampleModal1').modal({
                                                show: true,
                                                backdrop: 'static',
                                                keyboard: false
                                            });
                                        },
                                        success: function (result) {
                                            $('#exampleModal1').modal("hide");
                                            if (result.code == 0) {
                                                $(".intro").css("height","185px").find(".second_p").css("height","100px");
                                                $('#exampleModal2').find(".second_p").html("您获得的" + result.data.prize + "元现金红包已发送至您的微信,可在<span style='color:rgb(50,50,50)'>“公众号”</span>领取。");
                                                $('#exampleModal2').modal({
                                                    show: true,
                                                    backdrop: 'static',
                                                    keyboard: false
                                                });
                                                $('#exampleModal2').on('hidden.bs.modal', function () {
                                                    location.href = '{{asset('personal/index')}}'; //个人中心
                                                });
                                            } else {
                                                message(result.message);
                                                return false;
                                            }
                                        },
                                        error: function (XMLHttpRequest, textStatus, errorThrown) {
                                            alert("error: 红包领取失败！");
                                            return false;
                                        }
                                    });
                                }
                                else{
                                    $('.subscribe').show();
                                    $('.success').hide();
                                }
                            });
                        } else if (json_param.exchange_type == 2) {
                            $(".price").html(json_param.prize);
                            $(".ext").html(plat_vrb_name);
                            $(".btn-dole").on("click", function () {
                                $(".intro").css("height","165px").find(".second_p").css("height","82px");
                                $('#exampleModal2').find(".second_p").html("您获得的" + json_param.prize + plat_vrb_name +"已存入您的账户,可在<span style='color:rgb(50,50,50)'>“个人中心”</span>查看。");
                                $('#exampleModal2').modal({
                                    show: true,
                                    backdrop: 'static',
                                    keyboard: false
                                });
                                $('#exampleModal2').on('hidden.bs.modal', function () {
                                    location.href = '{{asset('personal/index')}}'; //个人中心
                                });
                            });
                            $('.success').show();
                        } else {
                            $(".noredpack").show();
                        }
                    }
                }
            });

        }
    </script>
@endsection



