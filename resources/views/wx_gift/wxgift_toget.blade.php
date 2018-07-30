@extends('inheritance')

@section('title')
    领取礼品
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('sd_css/common.css')}}">
    <style>
        body{
            background: #ecac2e url("/sd_img/wxGift2Get.jpg") no-repeat;
            background-size: 100%;
        }
        /*whois*/
        .wxGift-whois{
            width: 100%;
            height: 50px;
            /*margin: 0 auto;*/
            margin-top: 10%;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .wxGift-whois .avator{
            align-self: center;
            width: 80px;
            height: 80px;
            border-radius:80px;
            overflow: hidden;
        }
        .avator img{
            width: 80px;
            height: 80px;
        }
        .wxGift-obj{
            width: 100%;
            text-align: center;
            overflow: hidden;
        }
        .wxGift-obj img{
            width: 200px;
        }
        .wxGift-obj p{
            margin-top: 10px;;
            margin-bottom: 10px;
        }


        .wxGift-bless{
            border-top: 1px dotted rgb(220,220,220);
            line-height: 24px;
            overflow: hidden;
        }
        .wxGift-bless li{
            margin: 15px 10px;
        }

        /*领取按钮*/
        .getAction{
            background-color: #f53a3a;
            width: 70%;
            height: 42px;
            margin: 30px auto 60px;
            border-radius: 8px;
        }
        .footer{
            width: 100%;
            margin-top: 50px;
            margin-bottom: 50px;
            text-align: center;
        }
        .footer img{
            width: 100%;
        }
    </style>
@endsection

@section('content')
    <body>

    <ul class="wxGift-whois">
        <li class="avator"><img src="{{$share_info->avatar}}" alt=""></li>
    </ul>
    <ul class="wxGift-obj" style="padding-top: 20px;">
        <li><span style="color: #7a4138;font-size: 18px;font-weight: bold;">{{$share_info->nick_name}}</span></li>
        <li><span style="color: #7a4138;font-size: 18px;font-weight: bold;">向你扔来一个礼物</span></li>
    </ul>


    {{--@foreach($order_info as $order)--}}
        <ul class="wxGift-obj" >
            <li><img style="background-color:#ffffff;height:200px;width: 200px;border-radius:50%; overflow:hidden;border:solid rgb(100,100,100) 1px;"src="{{$share_info->sku_image}}" alt=""></li>
            <p class="font-14 color-80" style="color: #7a4138;font-weight: bold;">{{$share_info->sku_name}}<p/>
        </ul>
    {{--@endforeach--}}

    @if($share_info->gifts_message)

        <div style="resize: none;width: 98%;height: 90px;font-size: 14px;padding-left: 20px;margin-top: 10px;c" class="youzi" >{{$share_info->gifts_message}}</div>
        {{--<ul class="wxGift-bless b-white">--}}
            {{----}}
            {{----}}
            {{--<li class="font-18 youzi color-80">--}}
                        {{--{{$share_info->gifts_message}}--}}
            {{--</li>--}}
        {{--</ul>--}}
    @else
        <div style="border-radius:3px;resize: none;width: 100%;height: 90px;font-size: 14px;padding-left: 20px;margin-top: 10px;" class="youzi" >一年一度，生命与轮回相逢狭路，我满载祝福千里奔赴，一路向流星倾诉，愿你今天怀里堆满礼物，耳里充满祝福，心里溢满温度，生日过得最酷！</div>

    @endif

        {{--<div class="getAction flex-center color-white " style="font-size: 18px;" id="toGet">
            打开礼物
        </div>--}}



    <div id="toGet" style="position:fixed ; bottom:10px;width:100%; text-align:center;" >
        <img src="/sd_img/open_gift.png" width="267" height="40" alt=""/>
    </div>



    </body>

@endsection

@section('js')

    {{--<script src="sd_js/mui.min.js"></script>--}}

    <script src="{{asset('sd_js/jquery.min.js')}}"></script>
    <script src="{{asset('sd_js/font_wvum.js')}}"></script>
    <script src="{{asset('sd_js/common.js')}}"></script>

    <script>
        $(function(){

            var share_gifts_info_id = '{{$share_info->share_gifts_info_id}}';

            {{--领取前判断该礼品是否已被领取完--}}
            $('#toGet').on('click', function () {
                $.get('/gift/checkToGetGift/' + share_gifts_info_id, function (data) {
                    if (data['code'] == 1) {
                        window.location.href = '/gift/getGiftDetailInfo/' + share_gifts_info_id;
                    } else {
                        message(data['message']);
                    }

                })
            });

        });

    </script>
@endsection

