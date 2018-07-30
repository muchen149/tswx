@extends('inheritance')

@section('title')
    充值账户详情
@endsection

@section('css')
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
            margin-top: 10px;
            display: block;
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
            box-shadow: 0 1px 3px rgba(50, 50, 50, 0.4);
        }

        .tab-contain {
            margin: 0 auto;
            display: flex;
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
            margin-bottom: 0;
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

        /*tab2标签样式*/
        .detail {
            width: 95%;
            margin: 12px auto 0;
            height: 110px;
            background-color: white;
            border-radius: 6px;
            border: 1px solid rgb(220, 220, 220);
        }

        .detail-1 {
            height: 40px;
            border-bottom: 1px solid rgb(220, 220, 220);
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
            color: rgb(80, 80, 80);
            margin-bottom: 0;
        }

        .detail-2 {
            height: 63px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 15px;
            font-weight: 600;
            color: rgb(80, 80, 80);
        }
    </style>
@endsection

@section('content')
    <body>
    <div id="tab2">
        @if(!$logs)
            <div style="margin:0 auto;height:200px;width:200px;margin-top:140px;text-align: center">
                <img style="width:47%" src="{{asset('img/default_shuitine.png')}}">
                <p class="mt20" style="margin-top:10px;font-size:14px;color:rgb(80,80,80);">暂无充值卡的交易记录</p>
            </div>
        @else
            @foreach($logs as $log)
                <div class="detail">
                    <ul class="detail-1">
                        <li style="margin-left: 2%">{{$log['create_time']}}</li>
                        <li style="margin-right: 2%">{{$log['busine_type']}}</li>
                    </ul>

                    <ul class="detail-2">
                        <li style="margin-left: 2%;color: {{$log['color']}}">{{$log['av_amount']}}</li>
                        <li style="margin-right: 2%">可用余额：<span>{{$log['realtime_balance']}}</span></li>
                    </ul>
                </div>
            @endforeach
        @endif
    </div>
    </body>
@endsection




