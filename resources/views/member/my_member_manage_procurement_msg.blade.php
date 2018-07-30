@extends('inheritance')

@section('title')
    采购详情信息
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('sd_css/common.css')}}">

    <style>
        .msg{
            width: 100%;
            margin-top: 10px;

        }
        .msg ul{
            width: 95%;
            margin: 0 auto;
        }
        .msg-text{
            position: relative;
            height: 50px;
            display: flex;
            align-items: center;
        }
        .msg2 li{
            width: 32%;
        }
        .msg2 li img{
            width: 100%;
        }
    </style>
@endsection

@section('content')
<body>
<div class="msg msg1 b-white">
    @if($memberJoinin->joinin_style == 1)
        {{--企业--}}
        <ul class="msg-text borderBottom">
            <li class="font-14 color-80">公司名字：</li>
            <li class="font-14 color-50">{{$memberJoinin->company_name}}</li>
        </ul>
        <ul class="msg-text borderBottom">
            <li class="font-14 color-80">公司所在地：</li>
            <li class="font-14 color-50">{{$memberJoinin->area_info}}</li>
        </ul>
        <ul class="msg-text borderBottom">
            <li class="font-14 color-80">公司详细地址：</li>
            <li class="font-14 color-50">{{$memberJoinin->address}}</li>
        </ul>
        <ul class="msg-text borderBottom">
            <li class="font-14 color-80">公司电话：</li>
            <li class="font-14 color-50">{{$memberJoinin->company_phone}}</li>
        </ul>
        <ul class="msg-text borderBottom">
            <li class="font-14 color-80">联系人姓名：</li>
            <li class="font-14 color-50">{{$memberJoinin->contacts_name}}</li>
        </ul>
        <ul class="msg-text borderBottom">
            <li class="font-14 color-80">联系电话：</li>
            <li class="font-14 color-50">{{$memberJoinin->contacts_mobile}}</li>
        </ul>

    @else
        {{--个人--}}
        <ul class="msg-text borderBottom">
            <li class="font-14 color-80">联系人姓名：</li>
            <li class="font-14 color-50">{{$memberJoinin->contacts_name}}</li>
        </ul>
        <ul class="msg-text borderBottom">
            <li class="font-14 color-80">联系电话：</li>
            <li class="font-14 color-50">{{$memberJoinin->contacts_mobile}}</li>
        </ul>
        <ul class="msg-text">
            <li class="font-14 color-80">电子邮箱：</li>
            <li class="font-14 color-50">{{$memberJoinin->email}}</li>
        </ul>

    @endif


</div>


<div class="msg msg2">
    <ul class="msg-img flex-between">
        @foreach($pic_arr as $pic)
             <li><img src="{{$pic}}" alt=""></li>
        @endforeach
    </ul>
</div>

</body>
@endsection

@section('js')

@endsection



