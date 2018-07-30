@extends('inheritance')

@section('title')
    更多服务
@endsection

@section('css')
    <script src="http://www.anydo.wang/resource/js/jquery-3.1.1.js"></script>
    <script src="http://www.anydo.wang/resource/js/bootstrap-3.3.0.js"></script>
    <script src="{{asset('sd_js/mui.min.js')}}"></script>
    <link rel="stylesheet" href="http://www.anydo.wang/resource/css/bootstrap-3.3.0.css">
    <link rel="stylesheet" href="{{asset('sd_css/mui.min.css')}}">
    <link rel="stylesheet" href="{{asset('sd_css/common.css')}}">
    <style>
        .mui-scroll-wrapper{
            height: 44px!important;
            box-shadow: 0 2px 2px rgba(50,50,50,0.4);
        }
        .mui-scroll{
            background-color: #f53a3a;
            height: 44px!important;
        }
        .mui-scroll a{
            box-sizing: border-box;
            height: 40px;
            margin-top: 4px;
            color: white!important;
            text-decoration: none;
        }
        .mui-active{
            border-bottom: 2px solid white!important;
        }


        .service-content{
            text-align: center;
        }
        .service-content h5{
            margin-top: 16px;
            margin-bottom: 6px;
        }
        .service-content .class{
            width: 95%;
            margin: 0 auto 20px;
          }
        .service-content .class ul{
            height: 140px;
            background-color: white;
            line-height: 30px;
        }
        .service-content .class ul img{
            width: 110px;
        }
    </style>
    <script type="text/javascript">
        mui.init()
    </script>
@endsection


@section('content')
<div class="mui-scroll-wrapper mui-slider-indicator mui-segmented-control mui-segmented-control-inverted">
    <div class="mui-scroll">
        <a class="mui-control-item mui-active">
            生活
        </a>
        <a class="mui-control-item">
            社交
        </a>
        <a class="mui-control-item">
            金融
        </a>
        <a class="mui-control-item">
            学习
        </a>
        <a class="mui-control-item">
            工作
        </a>
        <a class="mui-control-item">
            健康
        </a>
        <a class="mui-control-item">
            医疗
        </a>
    </div>
</div>

<div class="service-content">
    <h5 class="font-14 color-50">吃</h5>
    <div class="class flex-between font-12 color-80">
    <ul>
        <li><img src="http://placehold.it/110x110" alt=""></li>
        <li>精选食材</li>
    </ul>
    <ul>
        <li><img src="http://placehold.it/110x110" alt=""></li>
        <li>美食</li>
    </ul>
    <ul>
        <li><img src="http://placehold.it/110x110" alt=""></li>
        <li>茶饮冲调</li>
    </ul>
    </div>
    <h5 class="font-14 color-50">穿</h5>
    <div class="class flex-between font-12 color-80">
        <ul>
            <li><img src="http://placehold.it/110x110" alt=""></li>
            <li>精品服饰</li>
        </ul>
        <ul>
            <li><img src="http://placehold.it/110x110" alt=""></li>
            <li>量身定做</li>
        </ul>
        <ul>
            <li><img src="http://placehold.it/110x110" alt=""></li>
            <li>设计师</li>
        </ul>
    </div>
    <h5 class="font-14 color-50">住</h5>
    <div class="class flex-between font-12 color-80">
        <ul>
            <li><img src="http://placehold.it/110x110" alt=""></li>
            <li>酒店住宿</li>
        </ul>
        <ul>
            <li><img src="http://placehold.it/110x110" alt=""></li>
            <li>家庭服务</li>
        </ul>
        <ul>
            <li><img src="http://placehold.it/110x110" alt=""></li>
            <li>家居家纺</li>
        </ul>
    </div>

</div>
@endsection
@section('js')
<script>
//    导航栏配置项
    mui('.mui-scroll-wrapper').scroll({
        scrollY: false,
        scrollX: true,
        startX: 0,
        startY: 0,
        indicators: false,
        deceleration:0.0006,
        bounce: false

    });

//点击移动导航条
    $('.mui-scroll a:eq(1)').on('click',function(){
        $('.mui-scroll').css({
            'transform':'translateX(-73px)',
            '-moz-transform':'translateX(-73px)',
            '-webkit-transform':'translateX(-73px)',
            '-o-transform':'translateX(-73px)',
            'transition':'all 1s cubic-bezier(0.165, 0.84, 0.44, 1)',
            '-moz-transition':'all 1s cubic-bezier(0.165, 0.84, 0.44, 1)',
            '-webkit-transition':'all 1s cubic-bezier(0.165, 0.84, 0.44, 1)',
            '-0-transition':'all 1s cubic-bezier(0.165, 0.84, 0.44, 1)'
        })
    });

//    $('.mui-scroll a:eq(2)').on('click',function(){
//        $('.mui-scroll').css({
//            'transform':'translateX(-130px)',
//            '-moz-transform':'translateX(-130px)',
//            '-webkit-transform':'translateX(-130px)',
//            '-o-transform':'translateX(-130px)',
//            'transition':'all 1s cubic-bezier(0.165, 0.84, 0.44, 1)',
//            '-moz-transition':'all 1s cubic-bezier(0.165, 0.84, 0.44, 1)',
//            '-webkit-transition':'all 1s cubic-bezier(0.165, 0.84, 0.44, 1)',
//            '-0-transition':'all 1s cubic-bezier(0.165, 0.84, 0.44, 1)'
//        })
//    });
//    $('.mui-scroll a:eq(3)').on('click',function(){
//        $('.mui-scroll').css({
//            'transform':'translateX(-150px)',
//            '-moz-transform':'translateX(-150px)',
//            '-webkit-transform':'translateX(-150px)',
//            '-o-transform':'translateX(-150px)',
//            'transition':'all 1s cubic-bezier(0.165, 0.84, 0.44, 1)',
//            '-moz-transition':'all 1s cubic-bezier(0.165, 0.84, 0.44, 1)',
//            '-webkit-transition':'all 1s cubic-bezier(0.165, 0.84, 0.44, 1)',
//            '-0-transition':'all 1s cubic-bezier(0.165, 0.84, 0.44, 1)'
//        })
//    })
</script>
@endsection
