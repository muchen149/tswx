@extends('inheritance')
@section('title')
    购买会员
@endsection
@section('css')
    <link href="{{asset('sd_css/common.css')}}" rel="stylesheet">
    <style>
        .p{
            margin-bottom: 100px;
        }
        /*顶部标签 start*/
        header{
            width: 100%;
            overflow: hidden;
        }
        nav{
            width: 100%;
            height: 44px;
            background-color: white;
            display: flex;
            justify-content: space-around;
        }
        nav li{
            box-sizing: border-box;
            margin-top: 12px;
            height: 26px;
            color: rgba(80,80,80,0.8);

        }
        .tab{
            border-bottom: 2px solid #f53a3a;
            color: rgba(245,58,58,1);
        }
        /*顶部标签 end*/

        /*购买按钮 strat*/
        .prize{
            position: fixed;
            bottom: 0;
            background: rgba(255,255,255,0.9);
            width: 100%;
            height: 48px;
        }
        .prize a{
            display: inline-block;
            background-color:#f53a3a ;
            color: white;
            font-size: 12px;
            width: 40%;
            height: 36px;
            text-align: center;
            line-height: 36px;
            margin-top: 7px;
            border-radius: 6px;
        }
        .prize a:active{
            color: white;
        }
        .prize a:visited{
            color: white;
        }
        .prize a:first-child{
            margin-left: 5%;
            margin-right: 10%;
        }
        /*购买按钮 end*/

        /*sb start*/
        .steward-welcome{
            position: relative;
            width: 85%;
            height: 145px;
            margin: 20px auto 10px;
        }
        .steward-welcome-l img{
            width: 100px;
        }
        .steward-welcome-r{
            width: 63%;
            text-align: left;
        }
        .steward-welcome-r li:first-child{
            margin-bottom: 8px;
        }

        .buy-gate{
            position: absolute;
            bottom: 0;
            right: -20px;
            color: dodgerblue;
        }
        /*sb end*/

        /*特权list*/
        .privilege-title{
            width: 100%;
            height: 44px;
            margin-top: 10px;
        }
        .privilege-list{
            width: 100%;
            overflow: hidden;
        }
        .privilege-list ul{
            width: 95%;
            margin: 0 auto 16px;
        }
        .privilege-list img{
            width: 50px;
        }
        .privilege-list span{
            margin-top: 4px;
        }
        /*兑换码入口*/
        .ex-gate{
            width:100%;
            margin-top: 10px;
        }
        .ex-gate ul{
            width: 95%;
            height: 50px;
            margin: 0 auto;
        }
        .ex-gate ul img{
            width: 30px;
        }
        .ex-gate ul li:nth-child(2){
            width:77%;
            text-align: left;
        }
        /*说明*/
        .instruction{
            width: 95%;
            margin: 0 auto 10px;
        }
        .instruction p:first-child{
            margin: 10px 0;
        }
        .instruction p:last-child{
            line-height: 26px;
        }
    </style>
@endsection
@section('content')
    <body>
    <header>
        <nav class="font-12">
            <li class="tab" id="p1">{{$first_class}}</li>
            <li id='p2'>{{$second_class}}</li>
            <li id="p3">{{$third_class}}</li>
        </nav>
    </header>

    @foreach($grades as $grade => $item)
        @if($grade <= 30)
            <div class="p" style="display:{{$item['display']}}">
                <div class=" steward steward-welcome flex-between">
                    <ul class="steward-welcome-l">
                        <li><img src="{{asset('sd_img/steward-avator1.png')}}" alt=""></li>
                    </ul>
                    <ul class="steward-welcome-r font-14 ">
                        <li class="color-50">亲爱的用户,我是!</li>
                        <li class="color-80">购买包月或包年会员，享多重特权。</li>
                    </ul>

                </div>
                <div class="privilege-title b-white flex-center font-14"><p>即将享有</p></div>
                <div class="privilege-list b-white color-50 p1">
                    <ul class="flex-between font-12 ">
                        <li class="flex-column-between"><img src="" alt=""><span>尊享优惠</span></li>
                        <li class="flex-column-between"><img src="" alt=""><span>尊享商品</span></li>
                        <li class="flex-column-between"><img src="" alt=""><span>尊享活动</span></li>
                    </ul>
                    <ul class="flex-between font-12 ">
                        <li class="flex-column-between"><img src="" alt=""><span>尊享推荐</span></li>
                        <li class="flex-column-between"><img src="" alt=""><span>尊享兑换</span></li>
                        <li class="flex-column-between"><img src="" alt=""><span>专属管家</span></li>
                    </ul>
                </div>


                <div class="ex-gate b-white ">
                    <ul class="flex-between color-80 font-14">
                        <li><img src="{{asset('sd_img/ex_gate.png')}}" alt=""></li>
                        <li>兑换码入口</li>
                        <li><img src="{{asset('img/next.png')}}" alt=""></li>
                    </ul>
                </div>
                <ul class="prize color-white font-14">
                    <a class="go-buy" act="{{$item['month']->activity_id}}">开通月服务&nbsp;{{$item['month']->price}}元/月</a>
                    <a class="go-buy" act="{{$item['year']->activity_id}}">开通年服务&nbsp;{{$item['year']->price}}元/年 </a>
                </ul>
            </div>
        @else
            <div class="p" style="display: none">
                <div class="privilege-title b-white flex-center font-14"><p>{{$item['grade_name']}}专属特权</p></div>
                <div class="privilege-list b-white color-50 p1">
                    <ul class="flex-between font-12 ">
                        <li class="flex-column-between"><img src="" alt=""><span>尊享优惠</span></li>
                        <li class="flex-column-between"><img src="" alt=""><span>尊享商品</span></li>
                        <li class="flex-column-between"><img src="" alt=""><span>尊享活动</span></li>
                    </ul>
                    <ul class="flex-between font-12">
                        <li class="flex-column-between"><img src="" alt=""><span>尊享推荐</span></li>
                        <li class="flex-column-between"><img src="" alt=""><span>尊享兑换</span></li>
                        <li class="flex-column-between"><img src="" alt=""><span>专属管家</span></li>
                    </ul>
                </div>
                <div class="ex-gate b-white">
                    <ul class="flex-between color-80 font-14">
                        <li><img src="{{asset('sd_img/ex_gate.png')}}" alt=""></li>
                        <li>兑换码入口</li>
                        <li><img src="{{asset('img/next.png')}}" alt=""></li>
                    </ul>
                </div>
                <div class="instruction color-80 p1">
                    <p class="font-14 font-ber">关于{{$item['grade_name']}}</p>
                    <p class="font-14">
                        据韩联社报道，韩国产业部7日将举行工作会议，讨论如何处理化妆品、食品、
                        电子产品等各行业在中国反制措施之下所受的影响，并启动韩中经贸检查小组，开始逐日检查对
                        华出口情况以及与中国做生意的韩国出口商的处境变化，以便尽快对不公平行为做出回应。
                        韩国产业通商资源部5日称，周亨焕3日通过中国驻韩大使馆向中国政府转达，
                        希望中国商务部能向驻华韩企提供有诚意的关注和保护。
                    </p>
                </div>

                <ul class="prize color-white font-14">
                    <a class="go-buy" act="{{$item['year']->activity_id}}" style="width: 90%">开通年服务&nbsp;{{$item['year']->price}}元/年 </a>
                </ul>
            </div>
        @endif
    @endforeach
    <form id="buyForm" method="post" action="{{asset('wx/pay/buyMemberShipStep2')}}">
        <input type="hidden" id="activity_id" name="activity_id" value="">
    </form>
    <script>
        $(function(){
            $('nav li').on('click',function(e){
                var current= e.currentTarget;
                $(this).addClass('tab');
                $('nav li').not(current).removeClass('tab');
                $('.p,.steward').hide();
                if(current.id=='p1'){
                    $('.p').first().fadeIn(800);
                    $('.steward').first().fadeIn(800);
                }
                else if (current.id=='p2'){
                    $('.p').eq(1).fadeIn(800);
                    $('.steward').eq(1).fadeIn(800);
                }
                else {
                    $('.p').last().fadeIn(800);
                }
            });

            $('.go-buy').on('click', function(){
                $('#activity_id').val($(this).attr('act'));
                $('#buyForm').submit();
            });
            $('.ex-gate').on('click', function(){
                location.href = '{{asset('marketing/dispatch/checkNumberPage')}}';
            });
        });

    </script>

    </body>
@endsection

