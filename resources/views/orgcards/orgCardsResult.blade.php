@extends('inheritance')

{{--@section('title')
    绑定成功
@endsection--}}

@section('css')
    <link rel="stylesheet" href="{{asset('sd_css/common.css')}}">
    <style>
        .wxGift-succeed-tip{
            margin: 40% auto;
            width: 65%;
            text-align: center;
            background-color: #f7ebeb;
            border-radius: 25px;
        }
        .wxGift-succeed-tip img{
            margin-top: 30px;
            width: 30%;
        }
        .wxGift-succeed-tip p{
            margin-top: 10%;
        }
        .wxGift-succeed-btn-Group{
            margin-top: 20%;
            width: 100%;
            text-align: center;
        }
        .wxGift-succeed-btn-Group .btn{
            display: flex;
            justify-content: center;
            align-items: center;
            width: 80%;
            height: 44px;
            margin-left: 10%;
            border-radius: 6px;
            box-shadow: 1px 1px 2px rgba(50,50,50,.4);
        }
        .wxGift-succeed-btn-Group .btn li:first-child{
            margin-right: 10px;
        }
        .wxGift-succeed-attention img{
            width: 120px;
            height: 120px;
        }
        .wxGift-succeed-attention li:last-child{
            margin-top: 6px;
        }


    </style>
@endsection

@section('content')
    <body>
    <div class="wxGift-succeed-tip">
        <img src="{{asset('orgcard_img/get_succeed.png')}}" alt="">
        <p class="font-36" style="color: #30981b;">领取成功</p>
        <p class="font-14 color-80" style="padding-bottom: 20px;"><a href="/orgcards/orgcardInfo/{{$orgcard_id}}"><img src="{{asset('orgcard_img/btnConfirm.png')}}" style="width: 60%;"></a></p>
        <p class="font-14"></p>
    </div>

    </body>

@endsection

