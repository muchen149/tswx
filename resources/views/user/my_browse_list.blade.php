@extends('inheritance')

{{--@section('title')--}}
    {{--我的足迹--}}
{{--@endsection--}}

@section('css')
    <link rel="stylesheet" type="text/css" href="/css/main.css">
    <link rel="stylesheet" type="text/css" href="/css/search_list.css">
    {{--<link rel="stylesheet" type="text/css" href="/css/header.css">--}}
    <link rel="stylesheet" type="text/css" href="/css/base_goods.css">
    <style>
        body{
            background-color: rgb(240,240,240);
        }
        *{
            margin: 0;
            padding: 0;
            border: 0;
            list-style: none;
        }
        a{
            text-decoration: none;
            color: rgb(80,80,80);
        }
        .list li{
            display: flex;
            justify-content: space-around;
            align-items: center;
            width: 100% !important;
            margin-bottom: 10px;
            height: 80px !important;
            background-color: white;
        }
        .list li .list-left{
            margin-left: 3%;
            width: 27%;
        }
        .list li .list-left img{

            width: 80px;
        }
        .list li .list-right{
            margin-right: 3%;
            width: 67%;
            height: 80px;
        }

        .title{
            font-size: 13px !important;
            color: rgb(80,80,80);
            line-height: 22px !important;
            height: 32px !important;
            border-left:0 !important;
            padding-left: 0!important;
        }
        .top{
            margin-top: 6px;
        }
        .bottom{
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;;
        }
        .delete{
            width: 20px;
        }
        .prize{
            font-size: 14px;
            color: red;
        }

    </style>

@endsection

@section('content')
<body style="background-color: rgb(240,240,240)">
    <div id="header"></div>
    <div class="result-hot-pro lazyimg">
        @if(!$subjectList)
            <div style="margin:0 auto;height:200px;width:200px;margin-top:40px;text-align: center">
                <img style="width:80%" src="/img/empty_quan.png">
                <p class="mt20" style="color:#323232;">亲，您暂时还没有浏览商品！</p>
            </div>
        @else
            <ul id="viewlist" class="list" style="margin-top: 10px;">
              @foreach($subjectList as $key => $good)

                    <li goods_id="{{$good->subject_id}}">
                        <div class="list-left">
                            <a href="{{url('/shop/goods/spuDetail/'.$good->subject_id)}}"><img src="{{$good->main_image}}" alt=""></a>
                        </div>
                        <div class="list-right">
                            <div class="top">
                                <a href="{{url('/shop/goods/spuDetail/'.$good->subject_id)}}">
                                    <p class="title">{{$good->subject_name}}</p>
                                </a>
                            </div>
                            <div class="bottom">
                                <p class="prize">¥&nbsp;{{$good->price}}</p>
                                <p>{{date('Y-m-d H:i:s',$good->browse_time)}}</p>
                            </div>
                        </div>
                    </li>

              @endforeach

            </ul>
             <div style="text-align: center">
                <div class="pagination mt10">

                    <input type="hidden" value="{{$separatePage['pageNumber']}}" id="pageNumber"/>
                    {{--<input type="hidden" value="{{$separatePage['pageSize']}}" id="pageSize"/>--}}

                    <a href="javascript:void(0);" class="pre-page
                            @if($separatePage['pageNumber'] == 1)
                            disabled
                    @endif
                            ">上一页
                    </a>
                    <select name="page_list" style="padding: 6px 4px;  vertical-align: top; border: 0.8px solid rgb(169,169,169)">
                        @for($i=1; $i<= ceil($separatePage['allRows'] / $separatePage['pageSize']); $i++)
                            @if($i == $separatePage['pageNumber'])
                                <option value="{{$i}}" selected>{{$i}}</option>
                            @else
                                <option value="{{$i}}">{{$i}}</option>
                            @endif
                        @endfor
                    </select>

                    <input name="page_total" type="hidden" value="{{ceil($separatePage['allRows'] / $separatePage['pageSize'])}}" id="page_total">
                    <a href="javascript:void(0);" class="next-page
                             @if($separatePage['pageNumber'] == ceil($separatePage['allRows'] / $separatePage['pageSize']))
                            disabled
                         @endif
                            ">下一页</a>
                </div>
              </div>
        @endif
    </div>
</body>
@endsection

@section('js')
    <script type="text/javascript" src="/js/common-top.js"></script>
    <script type="text/javascript" src="/js/member/browse.js"></script>

@endsection



