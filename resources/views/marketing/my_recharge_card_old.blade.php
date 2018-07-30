@extends('inheritance')

@section('title')
    充值卡
@endsection

@section('css')
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

        /*头部色块样式*/
        .head {
            position: fixed;
            top: 0px;
            width: 100%;
            height: 140px;
            text-align: center;
            background-color: #673ab7;
            overflow: hidden;
            box-shadow: 0 2px 3px rgba(50, 50, 50, 0.5);
        }

        .head .top {
            margin-top: 24px;
        }

        .head .bottom {
            margin-top: 10px;
        }

        .opacity {
            color: rgba(255, 255, 255, 0.6);
            font-size: 14px;
        }

        .amount {
            color: rgb(255, 255, 255);
            font-size: 16px;
            font-weight: bold;
        }

        /*账户详情按钮*/
        .zhxq, .czhi {
            width: 100%;
            height: 50px;
            margin-top: 150px;
            border-top: 1px solid rgb(220, 220, 220);
            border-bottom: 1px solid rgb(220, 220, 220);
            background-color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .czhi {
            width: 100%;
            height: 50px;
            margin-top: 10px;
            border-top: 1px solid rgb(220, 220, 220);
            border-bottom: 1px solid rgb(220, 220, 220);
            background-color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .zhxq p, .czhi p {
            margin-left: 3%;
            font-size: 16px;
            color: rgb(80, 80, 80);
        }

        .zhxq img, .czhi img {
            margin-right: 3%;
            width: 32px;
        }

        /*京东E卡列表*/
        .jde-list {
            width: 100%;
            height: 90px;
            background-color: white;
            margin-top: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .jde-list .list1 {
            margin-left: 2%;
            width: 32%;
        }

        .jde-list .list1 img {
            width: 100%;
        }

        .jde-list .list2 {
            margin-left: 2%;
            width: 40%;
        }

        .jde-list .list2 p:first-child {
            font-size: 14px;
            margin-bottom: 6px;
            font-weight: 600;
        }

        .jde-list .list2 p:last-child {
            font-size: 14px;
            height: 38px;
            overflow: hidden;
        }

        .jde-list .list3 {
            margin-left: 3%;
            margin-right: 3%;
            width: 22%;
            height: 26px;
            border-radius: 4px;
            background-color: #e83828;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 12px;
        }
    </style>
@endsection

@section('content')
    <body>
    <div class="head">
        <ul class="top">
            <li class="opacity">可用余额</li>
            <li class="amount">{{$member['card_balance_available']}}</li>
        </ul>

        <ul class="bottom">
            <li class="opacity">可提现</li>
            <li class="amount">{{number_format($totalCashAmount, 2)}}</li>
        </ul>
    </div>
    <div class="zhxq" id="zhxq">
        <p>账户详情</p>
        <img src="{{asset('img/next.png')}}" alt="">
    </div>
    <div class="czhi" id="czhi">
        <p>充值</p>
        <img src="{{asset('img/next.png')}}" alt="">
    </div>
    {{--<div class="jde-list">
        <div class="list1">
            <img src="images/jde1.png" alt="">
        </div>
        <div class="list2">
            <p>¥500.00</p>
            <p>京东E卡福卡300面值(实体卡)</p>
        </div>
        <div class="list3">
            立即兑换
        </div>
    </div>
    <div class="jde-list">
        <div class="list1">
            <img src="images/jde2.png" alt="">
        </div>
        <div class="list2">
            <p>¥500.00</p>
            <p>京东E卡福卡300面值(实体卡)</p>
        </div>
        <div class="list3">
            立即兑换
        </div>
    </div>
    <div class="jde-list">
        <div class="list1">
            <img src="images/jde3.png" alt="">
        </div>
        <div class="list2">
            <p>¥500.00</p>
            <p>京东E卡福卡300面值(实体卡)</p>
        </div>
        <div class="list3">
            立即兑换
        </div>
    </div>--}}
    </body>
@endsection

@section('js')
    <script type="text/javascript">
        $(function () {
            $('#zhxq').on('click', function () {
                window.location.href = '{{asset('personal/wallet/rechargeCards/rechargeAccountDetail')}}';
            });
            $('#czhi').on('click', function () {
                window.location.href = '{{asset('personal/wallet/rechargeCards/rechargeCardList')}}';
            });
            $('[class=jde-list]').last().css('marginBottom', '30px');
        })
    </script>
@endsection



