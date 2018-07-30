@extends('inheritance')

@section('title')
    {{$plat_vrb_caption}}
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

        /*头部色块样式*/
        .head {
            position: fixed;
            top: 0px;
            width: 100%;
            height: 140px;
            text-align: center;
            background-color: #2196f3;
            overflow: hidden;
        }

        .head .top {
            margin-top: 24px;
        }

        .head .bottom {
            margin-top: 15px;
        }

        .opacity {
            color: rgba(255, 255, 255, 0.6);
            font-size: 14px;
        }

        .amount {
            margin-top: 3px;
            color: rgb(255, 255, 255);
            font-size: 16px;
            font-weight: bold;
        }

        /*tabbar样式*/
        .tabbar {
            width: 100%;
            height: 50px;
            position: fixed;
            top: 140px;
            background-color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 1px 3px rgba(50, 50, 50, 0.5);
            font-size: 16px;
            font-weight: 600;
            color: #2196f3;

        }

        .tabbar ul:first-child {
            margin: 14px 0 0 4%;
            height: 36px;
            box-sizing: border-box;
        }

        .tabbar ul:nth-child(2) {
            margin: 14px 0 0;
            height: 36px;
            box-sizing: border-box;
        }

        .tabbar ul:last-child {
            margin: 14px 4% 0 0;
            margin-right: 4%;
            height: 36px;
            box-sizing: border-box;
        }

        .current {
            border-bottom: 2px solid #2196f3;
        }

        /*金币明细样式*/
        .jb-list {
            width: 100%;
            margin-top: 10px;
            height: 50px;
            background-color: white;
            overflow: hidden;
        }

        .jb-list ul {
            height: 50px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
            color: rgb(80, 80, 80);
        }

        .jb-list ul li:first-child {
            margin-left: 4%;
        }

        .jb-list ul li:last-child {
            margin-right: 4%;
        }

        .red {
            color: red;
            font-weight: 600;
            width: 49px;
        }

        .green {
            color: green;
            font-weight: 600;
            width: 49px;
        }

        .block2 {
            margin-top: 200px;
            text-align: center;
            display: none;
            font-size: 30px;
        }
    </style>
@endsection

@section('content')
    <body>
    <div class="head">
        <ul class="top">
            <li class="opacity">可用{{$plat_vrb_caption}}</li>
            <li class="amount">{{number_format($member['yesb_available'])}}</li>
        </ul>

        <ul class="bottom">
            <li class="opacity">累计抵现</li>
            <li class="amount">{{number_format(abs($cashAmount), 2)}}</li>
        </ul>
    </div>
    <div class="tabbar">
        <ul class="current tab" data-main="block1">
            <li>全部{{$plat_vrb_caption}}</li>
        </ul>
        <ul class="tab" data-main="block2">
            <li>获得{{$plat_vrb_caption}}</li>
        </ul>
        <ul class="tab" data-main="block3">
            <li>消费{{$plat_vrb_caption}}</li>
        </ul>
    </div>
    <div class="block" id="block1">
        @if(!$allLogs)
            <div style="margin:0 auto;height:200px;width:200px;margin-top:250px;text-align: center">
                <img style="width:47%" src="{{asset('img/default_shuitine.png')}}">
                <p class="mt20" style="margin-top:10px;font-size:14px;color:rgb(80,80,80);">暂无{{$plat_vrb_caption}}的交易记录</p>
            </div>
        @else
            @foreach($allLogs as $allLog)
                <div class="jb-list">
                    <ul>
                        <li class="{{$allLog['color']}}">{{explode('.', $allLog['yesb_amount'])[0]}}</li>
                        <li>{{$allLog['busine_type']}}</li>
                        <li>{{$allLog['create_time']}}</li>
                    </ul>
                </div>
            @endforeach
        @endif
    </div>
    <div class="block block2" id="block2">
        @if(!$earnLogs)
            <div style="margin:0 auto;height:200px;width:200px;margin-top:250px;text-align: center">
                <img style="width:47%" src="{{asset('img/default_shuitine.png')}}">
                <p class="mt20" style="margin-top:10px;font-size:14px;color:rgb(80,80,80);">暂无{{$plat_vrb_caption}}的交易记录</p>
            </div>
        @else
            @foreach($earnLogs as $earnLog)
                <div class="jb-list">
                    <ul>
                        <li class="{{$earnLog['color']}}">{{explode('.', $earnLog['yesb_amount'])[0]}}</li>
                        <li>{{$earnLog['busine_type']}}</li>
                        <li>{{$earnLog['create_time']}}</li>
                    </ul>
                </div>
            @endforeach
        @endif
    </div>
    <div class="block block2" id="block3">
        @if(!$payLogs)
            <div style="margin:0 auto;height:200px;width:200px;margin-top:250px;text-align: center">
                <img style="width:47%" src="{{asset('img/default_shuitine.png')}}">
                <p class="mt20" style="margin-top:10px;font-size:14px;color:rgb(80,80,80);">暂无{{$plat_vrb_caption}}的交易记录</p>
            </div>
        @else
            @foreach($payLogs as $payLog)
                <div class="jb-list">
                    <ul>
                        <li class="{{$payLog['color']}}">{{explode('.', $payLog['yesb_amount'])[0]}}</li>
                        <li>{{$payLog['busine_type']}}</li>
                        <li>{{$payLog['create_time']}}</li>
                    </ul>
                </div>
            @endforeach
        @endif
    </div>
    </body>
@endsection

@section('js')
    <script type="text/javascript">
        $(function () {
            $('.tab').on('click', function () {
                $(this).addClass('current');
                $('.tab').not(this).removeClass('current');
                $(".block[id='" + $(this).attr("data-main") + "']").show();
                $('.block').not('#' + $(this).attr('data-main')).hide();
            });
            $('[class=jb-list]').first().css('marginTop', '200px');
        })
    </script>
@endsection



