@extends('inheritance')

@section('title')
    关于水丁管家
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('sd_css/mui.min.css')}}">
    <link rel="stylesheet" href="{{asset('sd_css/common.css')}}">
    <style>
        body{
            text-align: center;
        }
        .logo img{
            margin-top: 50px;
            width: 100px;
        }
        .erwm{
            margin-top: 20px;
            width: 200px;
            padding: 5px;
            background-color: white;
            border-radius: 4px;
        }
    </style>

@endsection

@section('content')
<body style="background-color: rgb(240,240,240)">
    <div class="logo">
        <img src="{{asset('pic/logo_shuitine.png')}}" alt="">
    </div>
    <p>让生活的每个细节都精致</p>
    <img src="{{asset('sd_img/sd_qrcode.jpg')}}" alt="" class="erwm">
    <p>分享二维码给好友</p>


</body>
@endsection

@section('js')


@endsection



