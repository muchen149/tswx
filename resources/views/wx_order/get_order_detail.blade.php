@extends('inheritance')

@section('title')
    领取礼品订单详情
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('sd_css/mui.min.css')}}">
    <link rel="stylesheet" href="{{asset('sd_css/common.css')}}">
    <style>
        .icon{
            width: 1em;
            height: 1em;
            vertical-align: -0.15em;
            fill: currentColor;
            overflow: hidden;
        }
        .address{
            border-bottom: 1px solid rgb(230,230,230);
            box-shadow: 0 1px 1px rgba(80,80,80,.1);
        }
        .address div{
            width: 95%;
            margin: 0 auto;
        }
        .location-border{
            width: 100%;
            height: 4px;
            background-image: url("../../../sd_img/address-location-border.png");
            background-repeat: repeat-x;
            background-size: 30% 4px;
            margin-bottom: 10px;
        }
        .address .r{
            margin-left: 16px;
            width: 91%;
        }
        .address .r li:first-child{
            margin-top: 10px;
            margin-bottom: 8px;
        }
        .address .r .r-b{
            margin-bottom: 10px;
            line-height: 22px;
        }



        .order-list{
            width: 100%;
        }

        .order-list .order-list-title{
            width: 100%;
            height: 40px;
        }
        .order-list .order-list-title li:first-child{
            margin-left: 3%;
        }
        .order-list .order-list-title li:last-child{
            margin-right: 3%;
        }

        .order-list-content{
            width: 100%;
            height: 110px;
            background-color: rgb(250,250,250);
        }
        .order-list-content-l{
            margin-left: 3%;
        }

        .order-list-content-r{
            margin-left: 4%;
            margin-right: 3%;

        }
        .order-list-content li:nth-of-type(1) img{
            width: 80px;
        }
        .order-list-optionBar{
            width: 100%;
        }
        .order-list-optionBar ul{
            height: 50px;
            margin-right: 3%;
        }
        .order-list-optionBar li{
            width: 88px;
            height: 32px;
            border: 1px solid rgb(220,220,220);
            border-radius: 6px;
            margin-left: 3%;
        }
        .order-list-optionBar .pay{
            border: 1px solid #f53a3a;
        }

        .order-discount{
            width: 100%;
            background-color: rgb(250,250,250);
        }
        .order-discount ul{
            width: 94%;
            height: 24px;
            margin: 0 auto;
        }
        .order-discount ul:last-child{
            height: 30px;
        }
        .order-detail{
            margin-top: 10px;
            margin-bottom: 20px;
            overflow: hidden;
        }
        .order-detail ul{
            width: 94%;
            margin: 10px auto 10px;
        }

        .lingquzt{
            width: 98%;
            margin: 1px auto;
            border-radius: 6px;
            overflow: hidden;
        }
        .lingquzt div{
            width: 95%;
            margin: 0 auto;
        }
        .lingquzt .zhuangtat{
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
            margin-bottom: 10px;
        }
        .lingquzt .zhuangtat ul:first-child{
            width: 63%;
        }
        .lingquzt .zhuangtat li:first-child{
            width: 50px;
            height: 50px;
            overflow: hidden;
        }
        .lingquzt .zhuangtat li:first-child img{
            width: 50px;
            height: 50px;
        }
        .lingquzt .ganxieyu{
            min-height: 6px;
        }
        .lingquzt .time{
            text-align: right;
            margin: 0px 0;
        }

        .lipinstate{
            margin-top: 10px;
            width: 100%;
            height: 44px;
        }
        .lipinstate li{
            margin-left: 3%;
            line-height: 44px;
        }
        .giftFrom{
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 50px;
            line-height: 50px;
        }
        .giftFrom ul:first-child{
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-left: 3%;
            font-size: 12px;
            color: rgb(50,50,50);
        }
        .giftFrom ul:first-child li{
            width: 40px;
            height: 40px;
            border-radius: 30px;
            overflow: hidden;
        }
        .giftFrom ul:first-child img{
            width: 40px;
            height: 40px;
            margin-right: 10px;
        }

        .giftFrom ul:last-child{
            margin-right: 3%;
            font-size: 12px;
            color: rgb(50,50,50);
        }

    </style>
@endsection

@section('content')
<body>
  @if (!empty($order_address))
    <div class="address b-white">
        <div class="flex-between">
            <ul class="l">
                <li>
                    <svg class="icon font-26 color-100" aria-hidden="true"><use xlink:href="#icon-infenicon07"></use> </svg>
                </li>
            </ul>
            <ul class="r">
                <ul class="r-t flex-between font-14 color-50">
                    <li >收货人:<span>{{$order_address['recipient_name']}}</span></li>
                    <li>{{$order_address['mobile']}}</li>
                </ul>
                <li class="r-b font-12 color-80">收货地址：
                <span>
                    {{$order_address['address']}}
                </span>
                </li>
            </ul>
        </div>
    </div>
    <div class="location-border"></div>

  @endif


    <div class="order-list">
        <div class="order-list-title b-white flex-between">
            <li class="font-12 color-80">订单号：{{$order_info['plat_order_sn']}}</li>
            <li class="font-14 color-f53a3a">
                @if($order_info['plat_order_state']==1)
                        待付款
                @elseif($order_info['plat_order_state']==2)
                        已付款
                @elseif($order_info['plat_order_state']==3)
                        待收货

                @elseif($order_info['plat_order_state']==4)
                    已完成
                @elseif($order_info['plat_order_state']==9)
                    已完成
                @elseif($order_info['plat_order_state']==-1)
                    已取消
                @elseif($order_info['plat_order_state']==-2)
                    已退单
                @elseif($order_info['plat_order_state']==-5)
                    已退货
                @elseif($order_info['plat_order_state']==-9)
                    已删除
                @endif

            </li>
        </div>


        <!--商品信息-->
        @foreach($order_info['skus'] as $goods)
            <div class="order-list-content flex-between">
                <ul class="order-list-content-l">
                    <li><img src="{{asset($goods['sku_image'])}}" alt=""></li>
                </ul>
                <div class="order-list-content-r" style="width: 90%;">
                    <ul>
                        <li class="r-title font-12 color-80">{{$goods['sku_name']}}</li>
                        <li class="r-property font-12 color-160">规格： @foreach($goods['sku_spec'] as $spec)<span>{{$spec}}&nbsp;</span>@endforeach</li></ul>
                    <ul class="font-14 color-80 flex-between">
                        <li>¥{{$goods['settlement_price']}}</li>
                        <li>&nbsp;&times;&nbsp;{{$goods['number']}}</li>
                    </ul>
                </div>
            </div>
        @endforeach


        {{--得到的微信礼品不能取消订单--}}
        @if($order_info['plat_order_state']==3 || $order_info['plat_order_state']== 4 || $order_info['plat_order_state'] == 9)

            <div class="order-list-optionBar font-12 color-80 b-white">
                <ul class="flex-end">
                            <li class="flex-center" order_id="{{$order_info['plat_order_id']}}">查看物流</li>
                </ul>
            </div>
        @endif
    </div>

    <div class="order-discount">
        <ul class="font-12 color-160 flex-between">
            <li>商品总价</li>
            <li>¥{{$order_info['goods_amount_totals']}}</li>
        </ul>
        <ul class="font-12 color-160 flex-between">
            <li>运费</li>
            <li>¥{{$order_info['fare_amount']}}</li>
        </ul>
        @if(isset($order_info['payment']['pay_wallet']))
            <ul class="font-12 color-160 flex-between">
                <li>使用零钱</li>
                <li>-¥{{$order_info['payment']['pay_wallet']['pay_amount']}}</li>
            </ul>
        @endif
        @if(isset($order_info['payment']['pay_card_balance']))
            <ul class="font-12 color-160 flex-between">
                <li>使用卡余额</li>
                <li>-¥{{$order_info['payment']['pay_card_balance']['pay_amount']}}</li>
            </ul>
        @endif
        @if(isset($order_info['payment']['pay_vrb']))
            <ul class="font-12 color-160 flex-between">
                <li>使用{{$plat_vrb_name}}</li>
                <li>-¥{{$order_info['payment']['pay_vrb']['pay_amount']}}
                    (抵现：¥{{$order_info['payment']['pay_vrb']['pay_amount_to_rmb']}})</li>
            </ul>
        @endif

        {{--<ul class="font-12 color-160 flex-between">--}}
            {{--<li>优惠券</li>--}}
            {{--<li>-¥20.00</li>--}}
        {{--</ul>--}}
        <ul class="font-14 flex-between">
            <li class="color-80">实付款</li>
            <li class="color-f53a3a">¥{{$order_info['pay_rmb_amount']}}</li>
        </ul>
    </div>

  @if($message!=null)
        <textarea style="width: 100%;height: 90px;font-size: 14px;" readonly="readonly">{{$message}}</textarea>
  @endif

    {{--<div class="order-detail b-white">--}}
        {{--<ul class="font-12 color-80">--}}
            {{--<li>订单编号：<span>201403312589</span></li>--}}
            {{--<li>创建时间：{{date( "Y-m-d H:i:s", $order_info['create_time'])}}</li>--}}
            {{--<li>支付时间：2017-03-31 20:14:02</li>--}}
        {{--</ul>--}}
    {{--</div>--}}
      <ul class="lipinstate font-14 b-white">
          <li>礼品来源</li>
      </ul>

  @if($share_info)

          <div class="giftFrom b-white">
                  <ul>
                      <li>
                          <img src="{{$share_info->avatar}}" alt="">
                      </li>
                      <li>{{$share_info->nick_name}}</li>
                  </ul>
                  <ul>
                      <li>{{date('Y-m-d H:i:s', $share_info->create_time)}}</li>
                  </ul>
          </div>

  @endif

  <style>

  </style>


</body>

@endsection

@section('js')
    <script type="text/javascript" src="{{asset('sd_js/mui.min.js')}}"></script>
    <script src="{{asset('sd_js/jquery.min.js')}}"></script>
    <script src="{{asset('sd_js/font_wvum.js')}}"></script>
    <script src="{{asset('sd_js/common.js')}}"></script>

@endsection

