@extends('inheritance')

@section('title')
    微信礼品支付成功
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('sd_css/common.css')}}">
    <link rel="stylesheet" href="{{asset('sd_css/mui.min.css')}}">

    <style>
        body{
            background: #bd1641 url("../../sd_img/paySucceed_bg.jpg") no-repeat;
            background-size: 100%;
        }
        .Temporary-link{
            width: 80%;
            text-align: center;
            display: block;
            margin: 60px auto 0;

        }
        .sent-list{
            background-color: white;
            position: fixed;
            width: 96%;
            height: 210px;
            left: 2%;
            bottom: 8px;
            text-align: center;
        }
        .mui-slider{
            height: 172px!important;
        }
        .mui-slider-item{
            text-align: center;
        }
        .mui-slider .mui-slider-group .mui-slider-item img{
            width: 140px;
        }
        .mui-slider-indicator{
            bottom: 0px;

        }
        .mui-slider-indicator .mui-active.mui-indicator{
            background: #f53a3a;
            box-shadow: none;
        }
        .mui-slider-indicator .mui-indicator{
            box-shadow: none;
        }
    </style>
@endsection

@section('content')
<body >
    <a href="{{asset('member/join_group')}}" class="Temporary-link  font-14">A temporary Link to get gifts</a>
    <div class="sent-list">
        <span class="font-16 color-80" style="display:block;margin-top: 10px">快来看一看吧，一起买立省29元！</span>
        <div class="mui-slider">
            <div class="mui-slider-group">
                <div class="mui-slider-item">
                    <a href=""><img src="{{asset('pic/sent-list1.jpg')}}" alt=""></a>
                    <p>Name of the Goods-name1</p>
                </div>

            </div>

            <script>
                var gallery = mui('.mui-slider');
                gallery.slider({
                    interval:0//自动轮播周期，若为0则不自动播放，默认为0；
                });
            </script>
        </div>
    </div>
</body>
@endsection

@section('js')

    <script src="{{asset('sd_js/mui.min.js')}}"></script>

    <script>
        var gallery = mui('.mui-slider');
        gallery.slider({
            interval:0//自动轮播周期，若为0则不自动播放，默认为0；
        });
    </script>

@endsection



