@extends('inheritance')

@section('title')
    卡券绑定
@endsection

@section('css')
    <link href="{{asset('css/after-sales.css')}}" rel="stylesheet">
    <link href="{{asset('css/search_list.css')}}" rel="stylesheet">
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

        .card-list {
            width: 96%;
            margin: 10px auto 0;
            border-bottom: 1px solid rgb(120, 120, 120);
        }

        .czcard {
            width: 96%;
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
            font-weight: bold;
        }

        .czcard-mess div:first-child {
            margin-left: 3%;
            margin-right: 3%;

        }

        .czcard-time {
            width: 82%;
            margin-left: 18%;
            text-align: right;
            border-top: 1px solid #787878;

        }

        .czcard-time p {
            font-size: 14px;
            line-height: 34px;
            margin-right: 3%;
        }

        .tips {
            width: 92%;
            margin: 30px auto 0;
            font-size: 15px;
            color: rgb(80, 80, 80);
            line-height: 24px;
        }

        .tips span {
            font-weight: 600;
        }

        .btn {
            width: 80%;
            height: 38px;
            margin: 20px auto 20px;
            background-color: #e83828;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 6px;
            font-size: 16px;
        }

        /*商品信息*/
        .line-shangpin {
            border-bottom: 1px solid rgb(220, 220, 220);
            padding: 5px 0;
            font-size: 0.52rem;
            display: box; /* OLD - Android 4.4- */
            display: -webkit-box; /* OLD - iOS 6-, Safari 3.1-6 */
            display: -moz-box; /* OLD - Firefox 19- (buggy but mostly works) */
            display: -ms-flexbox; /* TWEENER - IE 10 */
            display: -webkit-flex; /* NEW - Chrome */
            display: flex; /* NEW, Spec - Opera 12.1, Firefox 20+ */ /* 09版 */
            -webkit-box-orient: horizontal; /* 12版 */
            -webkit-flex-direction: row;
            -moz-flex-direction: row;
            -ms-flex-direction: row;
            -o-flex-direction: row;
            flex-direction: row;;
            align-items: center;
            color: rgb(80, 80, 80);
            width: 93%;
            margin: 0 auto 0;
        }

        .line-shangpin .left {
            /*float: left;*/
            margin-left: 2%;
            margin-top: 4px;
            display: inline-block;
            width: 23%;
        }

        .line-shangpin .right {
            /*float: left;*/
            margin-left: 2%;
            color: rgb(80, 80, 80);
            width: 69%;
        }

        .line-shangpin .right p:nth-child(1) {
            color: rgb(80, 80, 80);
            font-size: 13px;
            margin-top: 1px;
            min-height: 0px;
        }

        .line-shangpin .right p:nth-child(2) {
            color: #ff5500;
            font-size: 13px;
            font-weight: 500;
        }

        .line-shangpin .right p:nth-child(3) {
            color: #ff5500;
            font-size: 13px;
            font-weight: 500;
        }

        .title {
            width: 93%;
            padding: 10px 0;
            margin: 0 auto 0;
            border-bottom: 1px solid rgb(220, 220, 220);
            color: rgb(80, 80, 80)
        }
    </style>
@endsection

@section('content')
    <body>
    <div style="background-color: white;padding-bottom:20px;">
        <div class="title">
            充值卡1张
        </div>
        <div class="card-list">
            <div class="czcard color3">
                <div class="czcard-mess">
                    <div>
                        <img src="{{$scanData['activity_images']}}" alt="logo">
                    </div>
                    <div>
                        <p>面值: <span class="bold">¥{{$scanData['total_amount']}}</span></p>
                        <p>余额: <span class="bold">¥{{$scanData['balance_amount']}}</span> &nbsp;&nbsp;&nbsp;可提现: <span
                                    class="bold">¥{{number_format($scanData['max_cash_amount'], 2)}}</span></p>
                    </div>
                </div>
                <div class="czcard-time">
                    <p>{{$scanData['end_time']}}</p>
                </div>
            </div>
        </div>
    </div>
    @if(!empty($scanData['goods']))
        <div style="background-color: white">
            <div class="title">
                商品{{$scanData['goods_num']}}件
            </div>
            @foreach($scanData['goods'] as $item)
                <div class="line-shangpin">
                    <div class="left">
                        <img style="width:90%" src="{{$item['sku_image']}}"/>
                    </div>
                    <div class="right">
                        <p style="margin: 0 0 0;height:40px;">{{$item['sku_name']}}</p>
                        <p style="height:8px;color: #ff5500;font-size: 12px;">
                            ¥{{$item['price']}} &nbsp;&times;&nbsp;{{$item['number']}}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
    <div class="tips">
        <p><span>用卡须知：</span></p>
        <p>本卡为充值卡，可用于商城普通消费，亦可兑换指定商品或虚拟商品，部分卡支持提现至零钱
            @if(!empty($scanData['goods']))
                ，礼品可在“个人中心”-“我的礼包”处领取。
            @endif
            。</p>
    </div>
    <div class="btn">
        立即绑定
    </div>
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
            <div id="confirm-content" class="pop-msg-cnt" style="padding-top:30px;min-height: 50px">充值成功
            </div>
            <div class="pop-msg-btm">
                <a id="confirm-close-btn" class="btn-h4 btn-c3"
                   style="width: 96px;border: 1px solid #cfcfcf;color: #737373;background: #fff"
                   href="javascript:void(0);">关闭</a>
                <a id="confirm-ok-btn" class="btn-h4 btn-c3"
                   style="width: 96px;"
                   href="javascript:void(0);">确定</a>
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
    <script type="text/javascript" src="{{asset('js/common.js')}}"></script>
    <script type="text/javascript">
        $(function () {
            $('.btn').on('click', function () {
                $(this).unbind('click');
                loading();
                $.post('{{asset('personal/wallet/rechargeCards/bind')}}', {
                    "activity_id": '{{$scanData['activity_id']}}',
                    "card_id": '{{$scanData['card_id']}}'
                }, function (res) {
                    loadSucc();
                    if (res.code == 0) {
                        showWarnDialog('', function () {
                            window.location.href = '{{asset('personal/index')}}';
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



