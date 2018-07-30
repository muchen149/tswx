@extends('inheritance')

@section('title')
    购买会员
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('sd_css/common.css')}}">
    <link type="text/css" rel="stylesheet" href="{{asset('sd_css/swiper.min.css')}}"/>
    <style type="text/css">
        /*.p {
            margin-bottom: 100px;
        }*/

        /*顶部标签 start*/
        /*header {
            width: 100%;
            overflow: hidden;
        }*/
        .head-wrap {
            width: 100%;
            background: #fff;
            background-size: 100%;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 5px 0;
        }
        .head {
            position: relative;
            width: 90%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .head .avator {
            width: 60px;
            height: 60px;
            border: 2px solid white;
            border-radius: 50px;
            overflow: hidden;
            box-shadow: 0px 0px 1px rgba(120,120,120,.4);
        }
        .head .equal {
            width: 78%;
            text-align: left;
        }
        .head .equal li:first-child {
            font-weight: 600;
            font-size: 1.6rem;
            font-family: "微软雅黑";
        }

        .nav {
            width: 90%;
            height: 44px;
            -background-color: #c5a896;
            display: flex;
            justify-content: space-around;
            margin-top: 60px;
            margin-left: 5%;
            border-radius: 10px;
        }

        .nav li {
            box-sizing: border-box;
            -width: 33%;
            padding: 10px 0;
            text-align: center;
            -margin-top: 12px;
            -height: 26px;
            -color: rgba(255, 255, 255, 0.8);

        }


        /*顶部标签 end*/

        /*购买按钮 strat*/
        .prize {
            bottom: 0;
            padding:10px 0 0 27px;
            width: 100%;
            display: flex;
        }
        .prize li{
            font-size: 12px;
            text-align: center;
            color: #000;

        }
        .prize li input{
            vertical-align:text-bottom;margin-bottom:-1px;*margin-bottom:-2px;
        }

        .prize li a{
            -line-height: 36px;
            display: inline-block;
            margin-left: 1%;

        }
        /*.prize a {
            display: block;
            -background-color: #f53a3a;
            color: #000;
            font-size: 12px;
            width: 80%;
            height: 36px;
            text-align: center;
            line-height: 36px;
            margin-top: 7px;
            border-radius: 6px;
            margin-left: 10%;
        }

        .prize a:active {
            color: white;
        }

        .prize a:visited {
            color: white;
        }*/

        /*在sd_css/common.css修改了-webkit-appearance*/
        /*.prize input {
            -webkit-appearance: normal;
        }*/

        /*购买按钮 end*/

        .steward-welcome {
            position: relative;
            width: 85%;
            height: 145px;
            margin: 20px auto 10px;
        }

        .steward-welcome-l img {
            width: 100px;
        }

        .steward-welcome-r {
            width: 100%;
            text-align: center;
        }

        .steward-welcome-r li:first-child {
            margin-bottom: 8px;
        }

        .steward-welcome-r .color-50 {
            margin-top: 60px;
        }

        .buy-gate {
            position: absolute;
            bottom: 0;
            right: -20px;
            color: dodgerblue;
        }

        /*特权list*/
        .privilege-title {
            width: 100%;
            height: 44px;
            margin-top: 10px;
        }

        .privilege-list {
            width: 100%;
            overflow: hidden;
        }

        .privilege-list ul {
            width: 95%;
            margin: 0 auto 16px;
        }

        .privilege-list ul li {
            width: 25%;
            text-align: center;
        }

        .privilege-list img {
            width: 46px;
        }

        .privilege-list span {
            margin-top: 4px;
        }

        /*兑换码入口*/
        .ex-gate {
            width: 100%;
            margin-top: 10px;
        }

        .ex-gate ul {
            width: 95%;
            height: 50px;
            margin: 0 auto;
        }

        .ex-gate ul img {
            width: 30px;
        }

        .ex-gate ul li:nth-child(2) {
            width: 77%;
            text-align: left;
        }

        /*说明*/
        .instruction {
            width: 95%;
            margin: 0 auto 10px;
        }

        .instruction p:first-child {
            margin: 10px 0;
        }

        .instruction p:last-child {
            line-height: 26px;
        }

        /*头像的css*/
         .steward-welcome-tu20 {
             width: 40px;
             height: 40px;
             background-image: url(/sd_img/tu20.png);
             background-repeat: no-repeat;
             background-size: 100% 100%;
             position: absolute;
             top: -75%;
             left: 6%;
         }
        .steward-welcome-tu30 {
            width: 40px;
            height: 40px;
            background-image: url(/sd_img/tu30.png);
            background-repeat: no-repeat;
            background-size: 100% 100%;
            position: absolute;
            top: -75%;
            left: 6%;
        }
        .steward-welcome-tu40 {
            width: 40px;
            height: 40px;
            background-image: url(/sd_img/tu40.png);
            background-repeat: no-repeat;
            background-size: 100% 100%;
            position: absolute;
            top: -75%;
            left: 6%;;
        }

        .tab .steward-welcome-tu20 {
            background-image: url(/sd_img/tu20_01.png);

        }
        .tab .steward-welcome-tu30 {
            background-image: url(/sd_img/tu30_01.png);

        }
        .tab .steward-welcome-tu40 {
            background-image: url(/sd_img/tu40_01.png);
        }
        /*中间滑动样式*/
        #tab-body{
            margin-top: 10px;
        }
        .swiper-container {
            width: 100%;
            height: 100%;
        }
        .swiper-slide {
            text-align: center;
            font-size: 18px;
            -background: #fff;

            /* Center slide text vertically */
            display: -webkit-box;
            display: -ms-flexbox;
            display: -webkit-flex;
            display: flex;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            -webkit-justify-content: center;
            justify-content: center;
            -webkit-box-align: center;
            -ms-flex-align: center;
            -webkit-align-items: center;
            align-items: center;
        }
    </style>
@endsection

@section('content')
    <body>
    {{--<header>

        <nav class="font-12">
            @foreach($member_class_arr as $item)
                <li id="{{ 'p' . ($loop->index + 1) }}">{{ $item['grade_name'] }}</li>
            @endforeach
        </nav>
    </header>--}}
    <div class="font-16 head-wrap">
        <ul class="head">
            <li class="avator"><img src="{{ $member_obj->avatar }}" alt="" width="100%"></li>
            <div class="equal">
                <li>{{ $member_obj->nick_name }}&nbsp;,&nbsp;您好!</li>
                <li style="font-size:12px;">
                    @if($member_obj->grade == 10)
                        您尚未开通管家服务,开通可享受以下权益
                    @endif


                </li>
            </div>
        </ul>
    </div>

    <div id="tab-body">
        @if($member_obj->grade == 10 && $member_obj->exp_member == 0)
            <div class="swiper-container" id="listSwipe20">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <img src="{{asset('sd_img/discount_0301.png')}}" alt="" width="70%">
                    </div>
                    <div class="swiper-slide">
                        <img src="{{asset('sd_img/discount_1.png')}}" alt="" width="70%">
                    </div>
                    <div class="swiper-slide">
                        <img src="{{asset('sd_img/discount_2.png')}}" alt="" width="70%">
                    </div>
                    <div class="swiper-slide">
                        <img src="{{asset('sd_img/discount_3.png')}}" alt="" width="70%">
                    </div>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        @endif
            <div class="swiper-container" id="listSwipe30">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <img src="{{asset('sd_img/discount_0201.png')}}" alt="" width="70%">
                    </div>
                    <div class="swiper-slide">
                        <img src="{{asset('sd_img/discount_1.png')}}" alt="" width="70%">
                    </div>
                    <div class="swiper-slide">
                        <img src="{{asset('sd_img/discount_2.png')}}" alt="" width="70%">
                    </div>
                    <div class="swiper-slide">
                        <img src="{{asset('sd_img/discount_3.png')}}" alt="" width="70%">
                    </div>
                </div>
                <div class="swiper-pagination"></div>
            </div>
            {{--<div class="swiper-container" id="listSwipe40">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <img src="{{asset('sd_img/discount_0101.png')}}" alt="" width="70%">
                    </div>
                    <div class="swiper-slide">
                        <img src="{{asset('sd_img/discount_1.png')}}" alt="" width="70%">
                    </div>
                    <div class="swiper-slide">
                        <img src="{{asset('sd_img/discount_2.png')}}" alt="" width="70%">
                    </div>
                    <div class="swiper-slide">
                        <img src="{{asset('sd_img/discount_3.png')}}" alt="" width="70%">
                    </div>
                </div>
                <div class="swiper-pagination"></div>
            </div>--}}
        <div class="font-12 nav">
            @foreach($member_class_arr as $key=>$item)
                    <li id="{{ 'p' . ($loop->index + 1) }}" name="wapfly_feilei{{$key}}">
                        <div class="steward-welcome-tu{{$key}}"></div>
                        {{ $item['grade_name'] }}
                    </li>
            @endforeach
        </div>
        @foreach($member_class_arr as $item)
            <div class="p {{ 'p' . ($loop->index + 1) }}" style="display: none">
                {{--<div class="steward-welcome flex-between" style="background-image : url(../sd_img/back.png);background-repeat : no-repeat;position: relative;background-size:100% 100%;">
                    <div class="steward-welcome-tu{{($loop->index + 1)}}">
                    </div>

                    <ul class="steward-welcome-r font-14 ">
                        <li class="color-50">亲爱的用户您好！<br/>我是"{{ $item['grade_name'] }}"有什么可以帮助您的？<br/>拥有我，您就可以享有多重特权。</li>
                    </ul>
                </div>

                <div class="privilege-title b-white flex-center font-14">
                    <p>开通{{ $item['grade_name'] }}, 即刻享有</p>
                </div>--}}

                {{--<div class="privilege-list b-white color-50 p1">
                    <ul class="flex-between font-12 ">

                        <li class="flex-column-between"><a href="{{asset('/member/privilege_detail')}}"><img src="{{asset('sd_img/mymember_privilege1.png')}}"
                                                             alt=""></a><span>尊享优惠</span></li>
                        <li class="flex-column-between"><a href="{{asset('/member/privilege_detail')}}"><img src="{{asset('sd_img/mymember_privilege2.png')}}"
                                                             alt=""></a><span>尊享商品</span></li>
                        <li class="flex-column-between"><a href="{{asset('/member/privilege_detail')}}"><img src="{{asset('sd_img/mymember_privilege3.png')}}"
                                                             alt=""></a><span>尊享活动</span></li>
                    </ul>

                    <ul class="flex-between font-12 ">
                        <li class="flex-column-between"><a href="{{asset('/member/privilege_detail')}}"><img src="{{asset('sd_img/mymember_privilege4.png')}}"
                                                             alt=""></a><span>尊享推荐</span></li>
                        <li class="flex-column-between"><a href="{{asset('/member/privilege_detail')}}"><img src="{{asset('sd_img/mymember_privilege5.png')}}"
                                                             alt=""></a><span>尊享兑换</span></li>
                        <li class="flex-column-between"><a href="{{asset('/member/privilege_detail')}}"><img src="{{asset('sd_img/mymember_privilege6.png')}}"
                                                             alt=""></a><span>专属管家</span></li>
                    </ul>

                </div>--}}
                <from id="buySubmit">
                    {{--@if($member_obj->grade > 20 || $member_obj->exp_member == 1)
                        <div style="color: white; text-align: center;">
                            <input type="button" id="sbtn_{{$item['grade_code']}}" onclick="submit()" value="开通{{ $item['grade_name'] }}服务享@if($item['grade_code'] == 20)8折-95折@elseif($item['grade_code'] == 30)7折-9折@elseif($item['grade_code'] == 40)6折-85折@endif优惠" style="width:80%;height: 40px;background: #c5a896;border-radius: 10px;margin-top: 10px;">
                        </div>
                    @else
                        <div style="color: white; text-align: center;">
                            <input type="button" id="sbtn_{{$item['grade_code']}}" onclick="experience()" value="开通{{ $item['grade_name'] }}服务享8折-95折" style="width:80%;height: 40px;background: #c5a896;border-radius: 10px;margin-top: 10px;">
                        </div>
                    @endif--}}
                    <div style="color: white; text-align: center;">
                        <input type="button" id="sbtn_{{$item['grade_code']}}" onclick="experience()" value="开通{{ $item['grade_name'] }}服务享@if($item['grade_code'] == 20)8折-95折@elseif($item['grade_code'] == 30)7折-9折@elseif($item['grade_code'] == 40)6折-85折@endif优惠" style="width:80%;height: 40px;background: #c5a896;border-radius: 10px;margin-top: 10px;">
                    </div>
                    <ul class="prize color-white font-14">
                        {{--@foreach($item['pay_item_arr'] as $pay_item)
                            <li style="width: 50%;float: left;">
                            <a href="{{ asset('/wx/pay/payMemberShip?id=' . $pay_item['activity_id']) }}">
                                {{ $pay_item['exp_date_name'] }} 服务 {{ $pay_item['price'] }}
                                元 / {{ $pay_item['exp_date'] . ' ' . $pay_item['exp_date_name']}}
                            </a>
                            </li>
                        @endforeach--}}
                        @if($item['grade_code'] != 20)
                            {{--<li style="width: 45%;">
                                <input type="checkbox" name="id" value="{{$pay_item['activity_id']}}"  id="{{$pay_item['activity_id']}}" style="border:0;outline:none;"/><a style="color: #000;text-align: center"><label for="{{$pay_item['activity_id']}}">{{ $pay_item['exp_date_name'] }} 服务 {{ $pay_item['price'] }}
                                        元 / {{ $pay_item['exp_date'] . ' ' . $pay_item['exp_date_name']}}</label>
                                </a>
                            </li>--}}
                        @foreach($item['pay_item_arr'] as $pay_item)
                            <li style="width: 45%;">
                                <input type="checkbox" name="id" value="{{$pay_item['activity_id']}}"  id="{{$pay_item['activity_id']}}" style="border:0;outline:none;"/><a style="color: #000;text-align: center"><label for="{{$pay_item['activity_id']}}">{{ $pay_item['exp_date_name'] }} 服务 {{ $pay_item['price'] }}
                                        元 / {{ $pay_item['exp_date'] . ' ' . $pay_item['exp_date_name']}}</label>
                                </a>
                            </li>
                        @endforeach
                        @endif
                    </ul>
                </from>
            </div>
        @endforeach
    </div>
    </body>
@endsection

@section('js')
    <script type="text/javascript" src="{{asset('/sd_js/swiper.min.js')}}"></script>
    <script>
            $(function () {
                $('.nav li:first').addClass('tab');
                $('#tab-body .p:first').css('display', 'block');

                $('.nav li').on('click', function (e) {
                    var current = e.currentTarget;
                    $(this).addClass('tab');
                    $('.nav li').not(current).removeClass('tab');
                    $('.' + current.id).addClass("p");
                    $('.p').hide();
                    $('.' + current.id).fadeIn(800);
                });
                /*$('.nav li ').on('click', function (e) {
                    var current = e.currentTarget;
                    $(this).addClass('tab');
                    $('.nav li').not(current).removeClass('tab');
                    $('.' + current.id).addClass("p");
                    $('.p').hide();
                    $('.' + current.id).fadeIn(800);
                })*/

            });

        function submit(){
           if($('.nav li').hasClass('tab')){
               var id = $('.tab').attr('id');
               var val=$('.'+id+' input:checkbox[name="id"]:checked').val();
               if(val==null){
                   message("请选择服务类型！");
                   return false;

               } else {
                   var url='{{asset('/wx/pay/payMemberShip')}}'+'?id='+$('.'+id+' input[type="checkbox"]:checked').val();
                   window.location.href=url;
               }
           }


        }

        /*免费领取*/
        function experience(){
            $.post('{{asset('membership/getExpJosnForMemberShip')}}', {
                'grade': 20
            }, function (res) {
                if (res.code == 0) {
                    message(res.message);
                    window.location.href = '{{asset('member/center')}}';
                } else if(res.code == 1){
                    message(res.message);
                }
            });
        }

        $('#tab-body').find('input[type=checkbox]:first').attr("checked", true);
        $('#tab-body').find('input[type=checkbox]').bind('click', function(){
            $('#tab-body').find('input[type=checkbox]').not(this).attr("checked", false);
        });

        /*中间滑动*/
        /*var swiper = new Swiper('.swiper-container',{
            effect : 'coverflow',
            slidesPerView: 1.5,
            centeredSlides: true
        });*/
        var swiper = new Swiper('.swiper-container', {

            pagination: '.swiper-pagination',
            paginationClickable: true,
            spaceBetween:-100,
            observer: true//,//,//修改swiper自己或子元素时，自动初始化swiper
        });

        $(document).ready(function(){
            debugger;
            $('.swiper-container').addClass('hide');
            var id = '{!!$member_obj->grade  !!}';
            var pgrade = '{!! $param_grade !!}';
            var exp_member = '{!! $member_obj->exp_member !!}';
            //$(selector).click()

             /*if(id = 30){
                $('#tab-body').children('.swiper-container').eq(1).removeClass('hide');
            }else{
                $('#tab-body').children('.swiper-container').eq(0).removeClass('hide');
            }*/

            $("li[name^=wapfly_]").on("click", function () {
                var sa = $(this).attr('name');
                var patt = new RegExp("wapfly_feilei");
                var num = sa.replace(patt, '');
                var obj_d = $('#listSwipe' + num);
                $('.swiper-container').each(function () {
                    if (!$('#listSwipe').hasClass('hide')) {
                        $('.swiper-container').addClass('hide');
                    }
                });
                obj_d.removeClass('hide');

                var sbtid='#sbtn_'+num;
                if(num==20){
                    $(sbtid).attr('onclick','').click(eval(function(){experience()}));
                }else{
                    $(sbtid).attr('onclick','').click(eval(function(){submit()}));
                }


            });

            if(pgrade==0){
                if(id==30){
                    $('li[name="wapfly_feilei40"]').click();
                }else if(id==20){
                    $('li[name="wapfly_feilei30"]').click();
                }else if(id==10 && exp_member==1){
                    $('li[name="wapfly_feilei30"]').click();
                }else{
                    $('.swiper-container:first').removeClass('hide');
                }
            }else if(pgrade==30){
                $('li[name="wapfly_feilei30"]').click();
            }else if(pgrade==40){
                $('li[name="wapfly_feilei40"]').click();
            }

        });

    </script>


@endsection



