@extends('inheritance')

@section('title')
    报名结果
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('sd_css/common.css')}}">
    <style>
        .wxGift-succeed-tip{
            margin-top: 40%;
            width: 100%;
            text-align: center;
        }
        .wxGift-succeed-tip img{
            width: 80px;
        }
        .wxGift-succeed-tip p{
            margin-top: 12px;
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
        <img src="{{asset('sd_img/wxGift-succeed-tip.png')}}" alt="">
        @if($user_id)
            <p class="font-14 color-80">您好,不可重复报名!</p>
            <a href="/form/index/{{$eid}}"><button style="margin-top: 10%;background: #fe586f;width:45%;padding:10px;"><<-- 返回 -->></button></a>
        @else
            <p class="font-14 color-80">恭喜,报名成功!</p>
            <a href="/form/index/{{$eid}}"><button style="margin-top: 10%;background: #fe586f;width:45%;padding:10px;"><<-- 返回 -->></button></a>
        @endif
    </div>

    </body>

@endsection
