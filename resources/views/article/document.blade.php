@extends('inheritance')

@section('title')
    {{$document->doc_title}}
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('css/header.css')}}">

    <style type="text/css">
        .content{
            margin: 0 auto;
            min-width: 320px;
            overflow: hidden;
        }
    </style>
@endsection

@section('content')
    <body>
    <div id="header"></div>
    <div class="content">
        <div class="pddetail-cnt">
            <div class="pd-detail-tab">
                <div  id="fixed-tab-pannel">
                    <div class="fixed-tab-pannel" style="padding: 0 15px;">
                            {!! $document->doc_content !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
@endsection

@section('js')
    <script type="text/javascript" src="{{asset('js/common-top.js')}}"></script>
@endsection



