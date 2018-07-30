@extends('inheritance')

@section('title')
    账号设置
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('elife_css/mui.min.css')}}">
    <link rel="stylesheet" href="{{asset('elife_css/common.css')}}">
    <script src="{{asset('sd_js/font_wvum.js')}}"></script>

    <style>
        .head .nav{
            position: fixed;
            top: 0;
            padding: 0 4px;
            line-height: 40px;
            width:100%;
            z-index: 999999;
            font-family: "微软雅黑";
        }
        .head .nav .return{
            display: inline-block;
            width: 40%;
        }

        .me-user-information-item-wrap {
            width: 100%;
            background-color: white;
        }

        .me-user-information-item {
            position: relative;
            width: 96%;
            margin-left: auto;
            margin-right: auto;
            height: 50px;

        }

        .me-user-information-item .itemLeft .itemText {
            margin-left: 24px;
        }

    </style>
@endsection

@section('content')
    <body>
        <div class="head">
            <div class="nav b-white ">
                <a class="return font-14 color-80" href="{{asset('elife/personal/index')}}"><img src="{{asset('sd_img/next.png')}}" alt="" class="size-26" style="transform:rotate(180deg);"></a>
                <a class="title font-18 color-80" href="#">购物说明</a>
            </div>
        </div>
        @if($explainList)
            <div style="margin-top: 60px;">
                @foreach($explainList as $val)
                    <div style="
                     margin: 15px 5px;
                    background-color: white;
                    ">
                           {!! $val->doc_content !!}
                   </div>
                @endforeach
            </div>
        @endif
    </body>
@endsection

@section('js')


@endsection

