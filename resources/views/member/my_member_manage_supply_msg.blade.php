@extends('inheritance')

@section('title')
    供货详情信息
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
        .msg2{
            margin-bottom: 50px;
        }
        .msg2 li{
            width: 32%;
        }
        .msg2 li img{
            width: 100%;
        }
        @media screen and (max-width: 320px){
            #address{
                width: 220px;
                overflow: hidden;
                white-space: nowrap;
                text-overflow: ellipsis;

            }
        }
        @media screen and (min-width: 320px) and (max-width: 360px){
            #address{
                width: 220px;
                overflow: hidden;
                white-space: nowrap;
                text-overflow: ellipsis;

            }
        }
        @media screen and (min-width: 360px)and (max-width: 375px){
            #address{
                width: 256px;
                overflow: hidden;
                white-space: nowrap;
                text-overflow: ellipsis;

            }
        }
        @media screen and (min-width: 375px)and (max-width: 414px){
            #address{
                width: 270px;
                overflow: hidden;
                white-space: nowrap;
                text-overflow: ellipsis;

            }
        }
        @media screen and (min-width: 414px){
            #address{
                width: 304px;
                overflow: hidden;
                white-space: nowrap;
                text-overflow: ellipsis;

            }
        }

    </style>
@endsection

@section('content')
<body>
<div class="msg msg1 b-white">
    <ul class="msg-text borderBottom">
        <li class="font-14 color-80">公司名字：</li>
        <li class="font-14 color-50">{{$supply_info->company_name}}</li>
    </ul>
    <ul class="msg-text borderBottom">
        <li class="font-14 color-80">公司所在地：</li>
        <li class="font-14 color-50">{{$supply_info->area_info}}</li>
    </ul>
    <ul class="msg-text borderBottom">
        <li class="font-14 color-80">公司详细地址：</li>
        <li class="font-14 color-50">{{$supply_info->address}}</li>
    </ul>
    @if($supply_info->company_phone)
        <ul class="msg-text borderBottom">
            <li class="font-14 color-80">公司电话：</li>
            <li class="font-14 color-50">{{$supply_info->company_phone}}</li>
        </ul>
    @endif

    <ul class="msg-text borderBottom">
        <li class="font-14 color-80">联系人姓名：</li>
        <li class="font-14 color-50">{{$supply_info->contacts_name}}</li>
    </ul>
    <ul class="msg-text borderBottom">
        <li class="font-14 color-80">联系电话：</li>
        <li class="font-14 color-50">{{$supply_info->contacts_mobile}}</li>
    </ul>

   @if($supply_info->email)
        <ul class="msg-text borderBottom">
            <li class="font-14 color-80">电子邮箱：</li>
            <li class="font-14 color-50">{{$supply_info->email}}</li>
        </ul>
   @endif

    <ul class="msg-text" style="height: 40px">
        <li class="font-14 color-80">经营范围</li>
    </ul>
    <ul style="width: 94%;padding-left: 3%;padding-right: 3%;padding-bottom: 8px;line-height: 22px" class="font-12 color-80">
        {{$supply_info->management_scope}}
    </ul>

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



