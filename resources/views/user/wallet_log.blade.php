@extends('inheritance')

@section('title')
    零钱
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
            background-color: rgb(238, 238, 238);
        }

        /*头部色块样式*/
        .head {
            position: fixed;
            top: 0px;
            width: 100%;
            height: 140px;
            text-align: center;
            background-color: #f44336;
            overflow: hidden;
            box-shadow: 0 2px 3px rgba(50, 50, 50, 0.5);
        }

        .head .bottom li {
            display: inline;
        }

        .head .top {
            margin-top: 30px;
        }

        .head .bottom {
            margin-top: 20px;
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

        /*明细列表样式*/

        .detail {
            width: 95%;
            margin: 12px auto 0;
            height: 120px;
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
            height: 80px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 15px;
            font-weight: 600;
            color: rgb(80, 80, 80);
        }

        .cricle {
            position: fixed;
            right: 30px;
            width: 52px;
            height: 52px;
            background-color: #ea2929;
            color: white;
            font-size: 14px;
            border-radius: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 3px 3px rgba(50, 50, 50, 0.6);
        }

        .add {
            bottom: 160px;
        }

        .minus {
            bottom: 80px;
        }
    </style>
@endsection

@section('content')
    <body>
    <div class="head">
        <ul class="top">
            <li class="opacity">可用余额</li>
            <li class="amount">{{$member['wallet_available']}}</li>
        </ul>

        <ul class="bottom">
            <li class="opacity">累计收入</li>
            <li class="amount">{{number_format($totalRecharge, 2)}}</li>
            <li class="amount">丨</li>
            <li class="opacity">累计消费</li>
            <li class="amount">{{number_format($totalPay, 2)}}</li>
        </ul>
    </div>
    @if(!$logs)
        <div style="margin:0 auto;height:200px;width:200px;margin-top:200px;text-align: center">
            <img style="width:47%" src="{{asset('img/default_lingqian.png')}}">
            <p class="mt20" style="margin-top:20px;font-size:14px;color:rgb(80,80,80);">暂无零钱消费明细，赶紧充值吧!</p>
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
    {{--<div class="cricle add">充值</div>
    <div class="cricle minus">提现</div>--}}
    </body>
@endsection

@section('js')
    <script type="text/javascript">
        $(function () {
            $('[class=detail]').first().css('marginTop', '152px');
            $('[class=detail]').last().css('marginBottom', '30px');
        })
    </script>
@endsection



