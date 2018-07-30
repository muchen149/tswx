<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>我的礼品卡</title>
    <link rel="stylesheet" href="{{asset('css/common.css')}}">
    <style>
        header ul{
            width: 95%;
            height: 56px;
            margin: 0 auto;
        }
        header ul span{
            color: #f53a3a;
        }
        footer{
            position: fixed;
            width: 100%;
            bottom: 0;
            left: 0;
        }
        footer a{
            display: block;
            width: 50%;
            height: 49px;
            float: left;
            text-align: center;
            line-height: 49px;
        }
        footer a:last-child{
            background-color:#f53a3a;

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
            box-shadow: 0 1px 3px rgba(50, 50, 50, 0.4);
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
</head>
<footer>
    <a href="{{asset('wx/pay/rechargeCardList')}}" class="b-white color-80 font-14"><li >充值/购买新卡</li></a>
    <a href="{{asset('marketing/dispatch/checkNumberPage')}}" class="color-white font-14"><li >绑定新卡</li></a>
</footer>
<body>
<header class="b-white border-b">
    <ul class="b-white flex-between font-16 color-50">
        <li>当前余额：<span class="font-b">￥{{$member['card_balance_available']}}元</span></li>
        <a href="{{asset('personal/wallet/rechargeCards/rechargeAccountDetail')}}" class="flex-between color-80"><li>查看记录</li> <img src="{{asset('img/next.png')}}" alt="" class="size-24"></a>
    </ul>
</header>

<div id="tab1">
    @if(!$rechargeCardList)
        <div style="margin:0 auto;height:200px;width:200px;margin-top:140px;text-align: center">
            <img style="width:47%" src="{{asset('img/default_list.png')}}">
            <p class="mt20" style="margin-top:10px;font-size:14px;color:rgb(80,80,80);">暂无充值卡的充值记录</p>
        </div>
    @else
        @foreach($rechargeCardList as $item)
            <div class="card-list"
                 data-param="{card_id:{{$item['rechargecard_id']}},max_cash_amount:{{$item['max_cash_amount']}},balance_available:{{$item['balance_available']}}}">
                <div class="czcard-wrap">
                    <div class="czcard {{$item['color']}}">
                        <div class="czcard-mess">
                            <div>
                                <img src="{{$item['activity_images']}}" alt="logo">
                            </div>
                            <div>
                                <p>面值: <span class="bold">¥{{$item['total_amount']}}</span></p>
                                <p>余额: <span class="bold">¥{{$item['balance_available']}}</span>
                                    @if($item['max_cash_amount'] > 0)
                                        &nbsp;&nbsp;&nbsp;可提现:
                                        <span class="bold">¥{{number_format($item['max_cash_amount'], 2)}}</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="czcard-time">
                            <p>{{$item['create_time']}}</p>
                        </div>

                    </div>
                </div>
            </div>
        @endforeach
    @endif

</div>
</body>
</html>