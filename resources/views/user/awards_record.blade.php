@extends('inheritance')

@section('title')
    中奖记录
@endsection

@section('css')
    <link href="{{asset('sd_css/bootstrap.min.css')}}" rel="stylesheet" type="text/css"/>
    {{--<link href="{{asset('css/header.css')}}" rel="stylesheet" type="text/css"/>--}}
    <link href="{{asset('sd_css/after-sales.css')}}" rel="stylesheet">
    <style>
        * {
            margin: 0;
        }

        body {
            background-color: rgb(240, 240, 240);
        }

        .record_wrap {
            width: 100%;
            background-color: white;
        }

        .record {
            background-color: white;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            height: 80px;
            width: 94%;
            margin: 0 auto;
            border-bottom: 1px solid rgb(220, 220, 220);

        }

        .record .left {
            height: 80px;

        }

        .record .left img {
            width: 70px;
            height: 70px;
            margin-top: 5px;
        }

        .record .med {
            height: 80px;
            width: 40%;
            text-align: left;
        }

        .record .med p:first-child {
            margin-top: 18px;
            color: rgb(60, 60, 60);
            font-size: 15px;
        }

        .record .med p:last-child {
            margin-top: 10px;
            color: rgb(130, 130, 130);
            font-size: 13px;
        }

        .record .right {
            height: 80px;
        }

        .right .btn {
            width: 70px;
            height: 26px;
            margin-top: 27px;
            border: 1px solid #eb6877;
            border-radius: 6px;
            display: flex;
            justify-content: center;
            align-items: center;;
            font-size: 11px;
            color: #eb6877;
            background-color: white;
        }

        .right .btn1 {
            width: 70px;
            height: 26px;
            margin-top: 27px;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: white;
            color: rgb(50, 50, 50);
        }

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
    </style>
@endsection

@section('content')
    <body>
    <div ></div>
    @if($awardsRecordList)
        @foreach($awardsRecordList as $record)
            <div class="record_wrap">
                <div class="record">
                    <div class="left">
                        <img src="{{$record['prize_image']}}" alt="">
                    </div>
                    <div class="med" orderid="{{$record['order_id']}}">
                        <div class="title">
                            <p>{{$record['prize_name']}}</p>
                            <p>{{$record['prize_time']}}</p>
                        </div>
                    </div>
                    <div class="right">
                        <div class="{{$record['btn_class']}}"
                             data-param="{record_id:{{$record['awardsrecord_id']}},is_virtual:{{$record['prize_type']}},exchange_state:{{$record['exchange_state']}}}">{{$record['btn_name']}}</div>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div style="margin:0 auto;height:200px;width:200px;margin-top:50px;text-align: center">
            <img src="/img/no-record.png" style="width:47%">
            <p class="mt20" style="margin-top:10px;font-size:14px;color:rgb(80,80,80);">暂无中奖记录</p>
        </div>

    @endif
    <div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="background-color:inherit;border:none;box-shadow: none">
                <img style="height:38px;width:38px;margin-top:60%;margin-left:48%"
                     src="{{asset('img/loading.gif')}}">
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
    <script type="text/javascript" src="{{asset('js/common-top.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/common.js')}}"></script>
    <script type="text/javascript">
        $(".btn").on("click", function () {
            $(this).unbind("click");
            var param_str = '';
            eval('param_str=' + $(this).attr("data-param"));
            var record_id = param_str.record_id;
            var is_virtual = param_str.is_virtual;
            var exchange_state = param_str.exchange_state;
            if (exchange_state == '0') {
                if (is_virtual == "0") {
                    //完善领奖信息
                    window.location.href = '{{asset('marketing/lottery/perfectAwardInfo')}}' + '/' + record_id;
                } else {
                    $.ajax({
                        type: "post",
                        url: '{{asset('marketing/lottery/receiveRedpack')}}',
                        data: {awardsrecord_id: record_id},
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
                                $(".intro").css("height", "185px").find(".second_p").css("height", "100px");
                                $('#exampleModal2').find(".second_p").html("您获得的" + result.data.prize + "元现金红包已发送至您的微信,可在<span style='color:rgb(50,50,50)'>“公众号”</span>领取。");
                                $('#exampleModal2').modal({
                                    show: true,
                                    backdrop: 'static',
                                    keyboard: false
                                });
                                $('#exampleModal2').on('hidden.bs.modal', function () {
                                    window.location.reload();
                                });
                            } else {
                                message(result.message);
                                return false;
                            }
                        }
                    });
                }
            }
        });

        $(".med").on("click", function () {
            if ($(this).attr("orderid") != "" && $(this).attr("orderid") != "0") {
                window.location.href = '{{asset('order/info')}}' + '/' + $(this).attr("orderid");
            }
        });
    </script>
@endsection



