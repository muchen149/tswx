@extends('inheritance')

@section('title')
    充值
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('css/personal.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('css/main.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/header.css')}}">
    <link href="{{asset('css/after-sales.css')}}" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            list-style: none;
            font-family: arial;
        }

        body {
            background-color: rgb(240, 240, 240);
        }

        #tab2 {
            margin-top: 90px;
            display: none;
        }

        /*头部标签*/
        .head-tab {
            width: 100%;
            height: 80px;
            background-color: white;
            position: fixed;
            top: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 2px 3px rgba(50, 50, 50, 0.4);
        }

        .tab-contain {
            width: 220px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
        }

        .card {
            width: 110px;
            height: 34px;
            background-color: #e83828;
            color: white;
            border-radius: 6px 0 0 6px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .card-1 {
            width: 110px;
            height: 34px;
            border-top: 1px solid rgb(200, 200, 200);;
            border-left: 1px solid rgb(200, 200, 200);
            border-bottom: 1px solid rgb(200, 200, 200);
            border-radius: 6px 0px 0px 6px;
            background-color: white;
            color: rgb(80, 80, 80);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .list {
            width: 110px;
            height: 34px;
            border-top: 1px solid rgb(200, 200, 200);;
            border-right: 1px solid rgb(200, 200, 200);
            border-bottom: 1px solid rgb(200, 200, 200);
            border-radius: 0px 6px 6px 0px;
            background-color: white;
            color: rgb(80, 80, 80);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .list-1 {
            width: 120px;
            height: 34px;
            background-color: #e83828;
            color: white;
            border-radius: 0px 6px 6px 0px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /*卡片样式*/
        .card-list {
            margin-top: 12px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .select {
            width: 12%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .select img {
            width: 26px;
        }

        .czcard-wrap {
            width: 95%;
            border-bottom: 1px solid rgb(120, 120, 120);
        }

        .czcard {
            width: 95%;
            height: 100px;
            margin: 0px auto;
            border-radius: 8px 8px 0 0px;
            color: white;
        }

        .color1 {
            background-color: #31a195;
        }

        .color2 {
            background-color: #12aeee;
        }

        .color3 {
            background-color: #ef5350;
        }

        .czcard-mess {
            height: 65px;
            display: flex;
            justify-content: flex-start;
            align-items: center;
        }

        .czcard-mess img {
            width: 41px;
            height: 41px;
            border-radius: 41px;
        }

        .czcard-mess p {
            font-size: 14px;
            line-height: 22px;
        }

        .czcard-mess .bold {
            font-weight: 600;
        }

        .czcard-mess div:first-child {
            margin-left: 3%;
            margin-right: 3%;

        }

        .czcard-time {
            width: 82%;
            margin-left: 18%;
            text-align: right;
            border-top: 1px solid rgb(180, 180, 180);

        }

        .czcard-time p {
            font-size: 12px;
            line-height: 34px;
            margin-right: 3%;
        }
    </style>
@endsection

@section('content')
    <body>
    <div id="header"></div>
    @foreach($vrgoodsList as $item)
        <div class="card-list"
             data-param="{sku_id:{{$item->sku_id}},amount:{{$item->total_amount}},price:{{$item->price}}}">
            <div class="czcard-wrap">
                <div class="czcard {{'color'.rand(1,3)}}">
                    <div class="czcard-mess">
                        <div>
                            <img src="{{$item->main_image}}" alt="logo">
                        </div>
                        <div>
                            <p style="margin-top:10px;margin-bottom:0px;">面值&nbsp;&nbsp;<span
                                        class="bold">¥{{number_format($item->total_amount,0)}}</span></p>
                            <p style="margin-bottom:20px;">
                                @if($item->balance_cash_type > 0)
                                    可提现&nbsp;&nbsp;<span class="bold">¥{{number_format($item->balance_amount,0)}}</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="czcard-time">
                        <p>售价：{{$item->price}}元</p>
                    </div>

                </div>
            </div>
        </div>
    @endforeach
    <div id="pop-loading" style="z-index: 1001;display: none; position: fixed; top: 40%;left: 42.5%;">
        <img style="height:38px;width:38px;margin-top:60%;margin-left:48%" src="{{asset('img/loading.gif')}}">
    </div>
    <div id="pop-warn" class="pop pop-ctm-01" style="display: none; position: fixed; top: 30%;left: 15%;">
        <div class="pop-msg">
            <div id="warn-content" class="pop-msg-cnt" style="padding-top:30px;min-height: 50px">充值成功</div>
            <div id="warn-close-btn" class="pop-msg-btm"><a class="btn-h4 btn-c3" href="javascript:void(0);">确定</a>
            </div>
        </div>
    </div>
    <div id="pop-confirm" class="pop pop-ctm-01" style="display: none; position: fixed; top: 30%;left: 15%;">
        <div class="pop-msg">
            <div id="confirm-content" class="pop-msg-cnt" style="padding-top:30px;min-height: 50px">确认充值吗？
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
    <script type="text/javascript" src="{{asset('js/common-top.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/common.js')}}"></script>
    <script type="text/javascript">
        $(function () {
            $('.card-list').on('click', function () {
                var data_str = '';
                eval('data_str = ' + $(this).attr('data-param'));
                var sku_id = data_str.sku_id;
                var amount = data_str.amount;
                var price = data_str.price;
                var skus = sku_id + '-1-' + price + '-1-0';
                showConfirmDialog("确定购买<span style='color:red'>" + amount + "</span>元的充值卡？",
                        function () {
                            hideConfirmDialog();
                            loading();
                            $.post('{{asset('order/add')}}', {
                                "skus": skus,
                                "sku_source_type": 8
                            }, function (res) {
                                loadSucc();
                                if (res.code == 0) {
                                    var plat_order_id = res.data.plat_order_id;
                                    window.location.href = '{{asset('wx/pay/wxPay?plat_order_id=')}}' + plat_order_id;
                                } else if (res.code == 50000) {
                                    showWarnDialog(res.message, function () {
                                        window.location.href = '{{asset('personal/userLoginView')}}';
                                        hideWarnDialog();
                                    });
                                } else {
                                    message(res.message);
                                }
                            });
                        });
            });
        });
    </script>
@endsection



