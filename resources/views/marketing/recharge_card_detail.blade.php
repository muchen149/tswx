@extends('inheritance')

@section('title')
    卡余额提现
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('css/main.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/header.css')}}">
    <link href="{{asset('css/after-sales.css')}}" rel="stylesheet">
    <style>
        *{
            margin: 0;
            padding: 0;
            list-style: none;
            font-family: arial;
        }

        body{
            background-color: rgb(240,240,240);
        }
        .card-list{
            width: 96%;
            margin: 20px auto 0;
            border-bottom: 1px solid rgb(120,120,120);
        }
        .czcard{
            width: 96%;
            height: 100px;
            margin: 0px auto;
            border-radius: 8px 8px 0 0px;
            color: white;
        }
        .color1{
            background-color:#31a195;
        }
        .color2{
            background-color:#12aeee ;
        }
        .color3{
            background-color:#ef5350 ;
        }
        .czcard-mess{
            height: 65px;
            display: flex;
            justify-content: flex-start;
            align-items: center;
        }
        .czcard-mess img{
            width: 41px;
            height: 41px;
            border-radius: 41px;
        }
        .czcard-mess p{
            font-size: 16px;
            line-height: 22px;
        }
        .czcard-mess .bold{
            font-weight: bold;
        }
        .czcard-mess div:first-child{
            margin-left: 3%;
            margin-right: 3%;

        }
        .czcard-time{
            width: 82%;
            margin-left: 18%;
            text-align: right;
            border-top: 1px solid #787878;
        }
        .czcard-time p{
            font-size: 14px;
            line-height: 34px;
            margin-right: 3%;
        }
        .amount {
            width: 100%;
            margin-top: 36px;
            border-top: 1px solid rgb(220, 220, 220);
            border-bottom: 1px solid rgb(220,220,220);
            background-color: white;
        }

        .amount ul:first-child {
            text-align: center;
            font-size: 16px;
            color: rgb(80,80,80);
        }
        .amount ul:last-child {
            height: 50px;
            text-align: center;
            line-height: 50px;
            font-size: 16px;
            color: rgb(50,50,50);
        }
        .amount ul:last-child li{
            display: inline;
        }
        .btn{
            width: 80%;
            height: 38px;
            margin: 40px auto 0;
            background-color: #e83828;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 6px;
            font-size: 16px;
        }
        hr{
            width: 80%;
            margin: 0 auto;
            background-color: red;
            color: red;
        }
    </style>
@endsection

@section('content')
    <body>
    <div id="header"></div>
    <div class="card-list">
        <div class="czcard color3">
            <div class="czcard-mess">
                <div>
                    <img src="{{$rechargeCardDetail['activity_images']}}" alt="logo">
                </div>
                <div>
                    <p>面值: <span class="bold">¥{{$rechargeCardDetail['total_amount']}}</span></p>
                    <p>余额: <span class="bold">¥{{$rechargeCardDetail['balance_available']}}</span> &nbsp;&nbsp;&nbsp;可提现: <span class="bold">¥{{number_format($rechargeCardDetail['max_cash_amount'], 2)}}</span></p>
                </div>
            </div>

            <div class="czcard-time">
                <p>{{$rechargeCardDetail['create_time']}}</p>
            </div>

        </div>
    </div>
    <div class="amount">
        <ul>
            <li style="margin-top: 10px">我的零钱</li>
            <li style="margin-top: 15px;margin-bottom: 10px;font-size: 18px;font-weight:bold">{{$member['wallet_available']}}</li>
        </ul>
        <div style="width: 88%;height: 1px;margin: 0 auto;background-color:#dcdcdc;"></div>
        <ul>
            <li>本次提现</li>
            <li style="font-weight: bold;color: red"> <span>{{number_format($rechargeCardDetail['max_cash_amount'], 2)}}</span></li>
        </ul>
    </div>
    <div class="btn">
        提现到零钱
    </div>
    <div id="pop-loading" style="z-index: 1001;display: none; position: fixed; top: 40%;left: 42.5%;">
        <img style="height:38px;width:38px;margin-top:60%;margin-left:48%" src="{{asset('img/loading.gif')}}">
    </div>
    <div id="pop-warn" class="pop pop-ctm-01" style="display: none; position: fixed; top: 30%;left: 15%;">
        <div class="pop-msg">
            <div id="warn-content" class="pop-msg-cnt" style="padding-top:30px;min-height: 50px">提现成功</div>
            <div id="warn-close-btn" class="pop-msg-btm"><a class="btn-h4 btn-c3" href="javascript:void(0);">确定</a>
            </div>
        </div>
    </div>
    <div class="__MASK_SID_DIV" style="display:none;width:100%;position: absolute; z-index: 999; left: 0px; top: 0px;">
        <div id="set_height" style="width:100%; height: 1024px; opacity: 0.7; background: black none repeat scroll 0% 0%; position: static; margin: 0px;"></div>
    </div>
    </body>
@endsection

@section('js')
    <script type="text/javascript" src="{{asset('js/common-top.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/common.js')}}"></script>
    <script type="text/javascript">
        $(function () {
            $('.btn').on('click', function () {
                loading();
                $(this).unbind('click');
                $.post('{{asset('personal/wallet/rechargeCards/cash')}}', {
                    "cash_amount": '{{$rechargeCardDetail['max_cash_amount']}}',
                    "rechargecard_id": '{{$rechargeCardDetail['rechargecard_id']}}'
                }, function (res) {
                    loadSucc();
                    if (res.code == 0) {
                        showWarnDialog('', function () {
                            window.location.href = '{{asset('personal/wallet/rechargeCards/myRechargeCard')}}';
                            hideWarnDialog();
                        });
                    } else {
                        message(res.message);
                    }
                });
            });
        });
    </script>
@endsection



