@extends('inheritance')

@section('title')
    绑定手机号
@endsection

@section('css')

    <link rel="stylesheet" type="text/css" href="/css/header.css">

    <style>
        *{
            margin: 0;
            padding: 0;
            list-style: none;
            font-family:"Microsoft YaHei", 'Source Code Pro', Menlo, Consolas, Monaco, monospace;

        }
        body{
            background-color: rgb(240,240,240);
        }
        .main{
            width: 100%;
            height: 252px;
            background-color: white;
            text-align: center;
        }
        .main h5{
            margin-top: 20px;
            color: rgb(50,50,50);
        }
        .main ul{
            margin: 30px auto 0;
            width: 120px;
            text-align: left;
            font-size: 12px;
            color: rgb(80,80,80);
        }
        .main ul li{
            margin-bottom: 4px;
        }
        .main ul img{
            width: 14px;
            margin-right: 8px;
        }
        .btn{
            width: 85%;
            height: 40px;
            margin: 40px auto 0;
            background-color: #e83828;
            border-radius: 6px;
            color: white;
            font-size: 16px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
@endsection

@section('content')
<body style="background-color: white">
<div id="header"></div>

<div class="main" >
    <img src="/img/unlink_mark.png" alt="" style="width: 53px;margin-top: 20px">
    <h5>已绑定手机号：{{$mobile}}</h5>
    <ul>
        <li><img src="/img/success.png" alt="">手机号直接登录</li>
        <li><img src="/img/success.png" alt="">尊贵身份标识</li>
        <li><img src="/img/success.png" alt="">会员特权</li>
        <li><img src="/img/success.png" alt="">领优惠券</li>
    </ul>
    {{--<div class="btn">立即绑定</div>--}}
</div>
</body>
@endsection

@section('js')
    <script type="text/javascript" src="/js/common-top.js"></script>

    <script type="text/javascript">
        $(function () {
           $(".btn").on('click',function(){
               alert('bind');
               return true;

           });
        })
    </script>



@endsection

