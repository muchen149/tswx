@extends('inheritance')

@section('title')
    {{$article->article_title}}
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('css/header.css')}}">

    <style type="text/css">
        .content{
            margin: 0 auto;
            min-width: 320px;
            overflow: hidden;
        }
		.head{
            width: 100%;						
			margin-top:10px;
        }
		 /*头部导航*/
        .head .nav_tab{
            position: fixed;
            top: 0;
            padding: 0 4px;
            line-height: 38px;
            width:100%;
            z-index: 999999;
            font-family: "微软雅黑";
            text-align: center;
        }
        .head .nav_tab .return{
            display: inline-block;
            width: 30px;
        }
        .row{
            margin-left:0px;
        }
    </style>
@endsection

@section('content')
    <body>
{{--    <div id="header"></div>--}}
    <div class="content">
		<div class="head">
            <div class="nav_tab b-white" onclick="hybrid_app.back();">
                   <a class="return font-14 color-80" style="position:absolute;left:0px;top:0px;"><img src="{{asset('sd_img/next.png')}}" alt="" class="size-26" style="transform:rotate(180deg);"></a>
                   <a href="#" class="title font-18" style="color: #000000">{!! $article->article_title !!}</a>
            </div>
        </div>
        <div class="pddetail-cnt">
            <div class="pd-detail-tab">
                <div  id="fixed-tab-pannel">
                    <div class="fixed-tab-pannel" style="padding: 0 0px;">
                            {!! $article->content !!}
                    </div>
                    <div id="index_btn"><img src="{{$article->image_url_2}}" alt=""></div>
                </div>
            </div>
        </div>
    </div>
</body>
@endsection

@section('js')
    <script type="text/javascript" src="{{asset('elife_js/mui.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('elife_js/zepto.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('elife_js/common.js')}}"></script>
    <script type="text/javascript" src="{{asset('elife_js/common-top.js')}}"></script>
	<script type="text/javascript" src="{{asset('elife_js/hybrid_app.js')}}"></script>
    <script type="text/javascript">
        $(function () {
            $(".content").css({"font-size":"14px"}).find("img").attr("height","").css({"max-width":"640px",width:"100%"});
            $(".content").find("div").attr("height","").css({"max-width":"640px",width:"100%"});
            $("table").attr("height","").css({"max-width":"640px",width:"100%","font-size":"14px"});
            $(".content .row").css({"font-size":"14px"}).find("img").attr("height","").css({"max-width":"640px",width:"50%",height:"290px",});
            $(".content .rows").css({"font-size":"14px"}).find("img").attr("height","").css({"max-width":"640px",width:"33.3%",height:"173px",});
        });
       $('#index_btn').click(function () {
           window.location.href = '/elife/eLifeIndex?eLifeShop=eLifeShop';
       });
    </script>
@endsection


