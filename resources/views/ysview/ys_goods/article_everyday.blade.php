@extends('inheritance')

@section('水丁管家首页')
    每日播报
@endsection
@section('css')
    <script src="http://www.anydo.wang/resource/js/jquery-3.1.1.js"></script>
    <script src="http://www.anydo.wang/resource/js/bootstrap-3.3.0.js"></script>
    <link rel="stylesheet" href="http://www.anydo.wang/resource/css/bootstrap-3.3.0.css">
    <link rel="stylesheet" href="{{asset('sd_css/common.css')}}">
    <style>
        .article-list{
            background-color: white;
            height: 400px;
            text-align: center;
            margin-bottom: 10px;
            overflow: hidden;

        }
        .article-list h4{
            margin-top: 160px;
        }



        /*module*/
        .article-module{
            width: 100%;
            margin-bottom: 30px;
        }
        .article-module .l{
            width: 50%;
            box-sizing: border-box;
            border-right: 1px solid rgb(220,220,220);
        }
        .article-module .r{
            width: 50%;
        }
        .module1{
            height: 160px;
            overflow: hidden;
        }
        .module1 img{
            height: 94px;
        }
        .module1 .module1-top{
            text-align: center;
        }

        .module1 .module1-top li:first-child{
            margin-top: 10px;
        }
        .module1 .module1-bottom{
            width: 90%;
            height: 94px;
            margin: 8px auto;
        }
        .module1 .module1-bottom li{
            margin-left: 8px;
            width: 65%;
            height: 94px;
            overflow: hidden;
        }
        .module1 .module1-bottom li span{
            display: block;
        }
        .module2 {
            box-sizing: border-box;
            height: 80px;
        }
        .module2 img{
            width: 46px;
            margin-left: 5%;
        }
    </style>
    @endsection

@section('content')
<div class="article-list">
    <h4>article-list</h4>
    response coming from the back
</div>
<div class="article-module b-white flex-between">
    <ul class="l">
        <ul class="module1 border-b">
            <ul class="module1-top">
                <li class="font-14 color-50">每日读书</li>
                <li class="font-12" style="color: #f55d54">每日读书优惠券</li>
            </ul>
            <ul class="module1-bottom flex-between">
                <img src="{{asset('sd_img/article_module1.jpg')}}" alt="">
                <li class="color-80 font-12">
                    <span class="font-14 color-50 font-ber">魔鬼经济学</span>
                     聪明人看到的世界
                     样？经济学角度
                     看到世界的真相。

                </li>
            </ul>
        </ul>
        <a href="{{asset('sd_shop/article_list')}}">
        <ul class="module2 flex-around">
            <img src="{{asset('sd_img/article_module2_icon1.jpg')}}" alt="">
            <ul class="flex-column-between">
                <li class="moduel-title font-14 color-50">专栏订阅</li>
                <li class="font-12 color-80">定制你的专属内容</li>
            </ul>
        </ul>
        </a>
    </ul>
    <ul class="r">
        <a href="{{asset('sd_shop/article_list')}}">
        <ul class="module2 flex-around border-b">
            <img src="{{asset('sd_img/article_module2_icon2.jpg')}}" alt="">
            <ul class="flex-column-between">
                <li class="moduel-title font-14 color-50">生活小妙招</li>
                <li class="font-12 color-80">让生活更加精致</li>
            </ul>
        </ul>
        </a>
        <a href="{{asset('sd_shop/article_list')}}">
        <ul class="module2 flex-around border-b">
            <img src="{{asset('sd_img/article_module2_icon3.jpg')}}" alt="">
            <ul class="flex-column-between">
                <li class="moduel-title font-14 color-50">养生小妙招</li>
                <li class="font-12 color-80">好身体才是根本</li>
            </ul>
        </ul>
        </a>
        <a href="{{asset('sd_shop/article_list')}}">
        <ul class="module2 flex-around">
            <img src="{{asset('sd_img/article_module2_icon4.jpg')}}" alt="">
            <ul class="flex-column-between">
                <li class="moduel-title font-14 color-50">每日一笑</li>
                <li class="font-12 color-80">让欢乐无处不在</li>
            </ul>
        </ul>
        </a>
    </ul>

</div>
@endsection