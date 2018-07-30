@extends('inheritance')

@section('title')
    更多卡券
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('sd_css/common.css')}}">
    <style>
        a {
            color: #333;
        }

        header {
            height: 66px;
        }

        header a {
            display: block;
            box-sizing: border-box;
            text-align: center;
            width: 33%;
        }

        header a:nth-child(2) {
            border-left: 1px solid rgb(220, 220, 220);
            border-right: 1px solid rgb(220, 220, 220);
        }

        header a:nth-child(3) {
            border-right: 1px solid rgb(220, 220, 220);
        }

        header ul li:first-child {
            color: #ff7022;
            font-weight: bolder;
            margin-bottom: 6px;
        }

        main {
            overflow: hidden;
            margin-top: 10px;
        }

        .line1 {
            margin-top: 16px;
            padding-bottom: 16px;
            border-bottom: 1px solid rgb(220, 220, 220);
        }

        .line2 {
            padding-top: 16px;
            margin-bottom: 16px;
        }

        main ul li:first-child {
            margin-bottom: 6px;
        }

        main img {
            width: 50px;
            height: 50px;
        }
    </style>

@endsection

@section('content')
    <body>
    <header class="flex-between b-white">
        <a href="{{asset('personal/walletLog')}}">
            <ul class="font-14 color-80 money">
                <li>¥{{$member->wallet_available}}</li>
                <li>零钱</li>
            </ul>
        </a>
        <a href="{{asset('personal/wallet/rechargeCards/myRechargeCard')}}">
            <ul class="font-14 color-80 balance">
                <li>¥{{$member->card_balance_available}}</li>
                <li>卡余额</li>
            </ul>
        </a>

        <a href="{{asset('personal/wallet/giftCoupons/myGiftCoupon')}}">
            <ul class="font-14 color-80 balance">
                <li>{{$wallet_num_arr['coupon_num']}}</li>
                <li>礼券</li>
            </ul>
        </a>

        <a href="{{asset('personal/vrcoinLog')}}">
            <ul class="font-14 color-80 coin">
                <li>{{intval($member->yesb_available)}}</li>
                <li>{{$plat_vrb_caption}}</li>
            </ul>
        </a>
    </header>

    <main class="b-white">
        <ul class="flex-around b-white line1">
            <li>
                <a href="">
                    <ul class="flex-column-between font-14 color-80">
                        <li><img src="{{asset('sd_img/more-card1.png')}}" alt=""></li>
                        <li>线上优惠券</li>
                    </ul>
                </a>
            </li>

            <li>
                <a href="">
                    <ul class="flex-column-between font-14 color-80">
                        <li><img src="{{asset('sd_img/more-card2.png')}}" alt=""></li>
                        <li>礼券</li>
                    </ul>
                </a>
            </li>

            <li>
                <a href="{{ asset('membership/getCardList') }}">
                    <ul class="flex-column-between font-14 color-80">
                        <li><img src="{{ asset('sd_img/more-card3.png') }}" alt=""></li>
                        <li>会员卡</li>
                    </ul>
                </a>
            </li>
        </ul>

        <ul class="flex-around b-white line2">

            <li>
                <a href="">
                    <ul class="flex-column-between font-14 color-80">
                        <li><img src="{{asset('sd_img/more-card4.png')}}" alt=""></li>
                        <li>门店消费券</li>
                    </ul>
                </a>
            </li>

            <li>
                <a href="">
                    <ul class="flex-column-between font-14 color-80">
                        <li><img src="{{asset('sd_img/more-card5.png')}}" alt=""></li>
                        <li>门店卡</li>
                    </ul>
                </a>
            </li>

            <li>
                <a href="">
                    <ul class="flex-column-between font-14 color-80">
                        <li><img src="{{asset('sd_img/more-card6.png')}}" alt=""></li>
                        <li>红包</li>
                    </ul>
                </a>
            </li>
        </ul>
    </main>
    </body>
@endsection




