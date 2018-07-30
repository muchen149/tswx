@extends('inheritance')

@section('title')
    请先关注公众号
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('sd_css/common.css')}}">
    <style>
        .wxGift-succeed-tip{
            margin-top: 40px;
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
            width: 220px;
            height: 220px;
        }
        .wxGift-succeed-attention li:last-child{
            margin-top: 6px;
        }

    </style>
@endsection

@section('content')
    <body>
    <div class="wxGift-succeed-btn-Group">
        <ul class="wxGift-succeed-attention">
            <li><img src="{{asset('sd_img/sdw_dwcode.jpg')}}" alt=""></li>
            <li class="font-14 color-50" style="font-size:14px;color: #ffffff;font-weight: bold;">长按关注公众号</li>
        </ul>
    </div>
    <div class="wxGift-succeed-tip">
        <p class="font-14 color-80">请先关注公众号，然后才可以接受微信提醒，谢谢！</p>
    </div>

    </body>

@endsection


