@extends('inheritance')

@section('title')
    申请进度查询
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('css/header.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/after-sales.css')}}" charset="utf-8">
    <style>
        #tab2 {
            display: none;
        }

        /*头部标签*/
        .head-tab {
            width: 100%;
            height: 80px;
            background-color: white;
            position: fixed;
            top: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 1px 3px rgba(50, 50, 50, 0.4);
        }

        .tab-contain {
            width: 220px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
        }

        .card {
            width: 110px;
            height: 34px;
            background-color: #e83828;
            color: white;
            border-radius: 6px 0 0 6px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .card-1 {
            width: 110px;
            height: 34px;
            border-top: 1px solid rgb(200, 200, 200);;
            border-left: 1px solid rgb(200, 200, 200);
            border-bottom: 1px solid rgb(200, 200, 200);
            border-radius: 6px 0px 0px 6px;
            background-color: white;
            color: rgb(80, 80, 80);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .list {
            width: 110px;
            height: 34px;
            border-top: 1px solid rgb(200, 200, 200);;
            border-right: 1px solid rgb(200, 200, 200);
            border-bottom: 1px solid rgb(200, 200, 200);
            border-radius: 0px 6px 6px 0px;
            background-color: white;
            color: rgb(80, 80, 80);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .list-1 {
            width: 120px;
            height: 34px;
            background-color: #e83828;
            color: white;
            border-radius: 0px 6px 6px 0px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
@endsection

@section('content')
    <body style="background-color: rgb(240,240,240)">
    <div id="header"></div>
    <div class="head-tab">
        <div class="tab-contain">
            <div class="card" id="card">团采商用户</div>
            <div class="list" id="list">代理商用户</div>
        </div>
    </div>
    <div id="tab1">
        @if(!$is_apply_group)
            <div style="margin:0 auto;height:200px;width:200px;margin-top:50px;text-align: center">
                <img style="width:80%" src="{{asset('img/empty_quan.png')}}">
                <p class="mt20" style="color:#323232;">您还不是团采商用户</p>
                <p style="margin-top:20px;text-align: center">
                    <a href="{{asset('personal/perfectApply/groupbuy')}}"
                       style="background:#fff;color:#323232;border:1px solid #646464;margin-top:20px;padding:8px 20px;letter-spacing:3px;border-radius: 5px;">立即申请</a>
                </p>
            </div>
        @else
            @if(!$groupList)
                <div style="margin:0 auto;height:200px;width:200px;margin-top:40px;text-align: center">
                    <img style="width:80%" src="{{asset('img/empty_quan.png')}}">
                    <p class="mt20" style="color:#323232;">暂无团采商用户申请记录!</p>
                    <p style="margin-top:20px;text-align: center">
                        <a href="{{asset('personal/perfectApply/groupbuy')}}"
                           style="background:#fff;color:#323232;border:1px solid #646464;margin-top:20px;padding:8px 20px;letter-spacing:3px;border-radius: 5px;">立即申请</a>
                    </p>
                </div>
            @else
                <div class='new-ct' style="margin-top:40px;">
                    @foreach($groupList as $joinin)
                        @if($joinin->apply_grade == 20)
                            <div class="section">
                                <div class="s-num">申请类别：{{$joinin->apply_grade_name}}
                                    <a href="{{asset('personal/joininDetail') . '/' . $joinin->id}}"
                                       class="btn-search">进度查询</a>
                                </div>
                                <div class="s-cont">
                                    <div class="p-name">
                                        <a>{{$joinin->company_name}}</a>
                                    </div>
                                    <span class="p-static">状态：<span
                                                class="red">{{$joinin->state_name}}</span> <br></span>
                                    <span class="p-time">申请时间：{{date('Y-m-d H:i:s', $joinin->created_at)}}</span>
                                    @if($joinin->verify_state == 1)
                                        <a val="{{$joinin->id}}" style="margin-right:9px;float:right"
                                           class="btn-cancel">取消申请</a>
                                    @endif
                                </div>
                            </div>
                            <div class="bg-c"></div>
                        @endif
                    @endforeach
                </div>
            @endif
        @endif
    </div>
    <div id="tab2">
        @if(!$is_apply_proxy)
            <div style="margin:0 auto;height:200px;width:200px;margin-top:50px;text-align: center">
                <img style="width:80%" src="{{asset('img/empty_quan.png')}}">
                <p class="mt20" style="color:#323232;">您还不是代理商用户</p>
                <p style="margin-top:20px;text-align: center">
                    <a href="{{asset('personal/perfectApply/proxy')}}"
                       style="background:#fff;color:#323232;border:1px solid #646464;margin-top:20px;padding:8px 20px;letter-spacing:3px;border-radius: 5px;">立即申请</a>
                </p>
            </div>
        @else
            @if(!$proxyList)
                <div style="margin:0 auto;height:200px;width:200px;margin-top:40px;text-align: center">
                    <img style="width:80%" src="{{asset('img/empty_quan.png')}}">
                    <p class="mt20" style="color:#323232;">暂无代理商用户申请记录!</p>
                    <p style="margin-top:20px;text-align: center">
                        <a href="{{asset('personal/perfectApply/proxy')}}"
                           style="background:#fff;color:#323232;border:1px solid #646464;margin-top:20px;padding:8px 20px;letter-spacing:3px;border-radius: 5px;">立即申请</a>
                    </p>
                </div>
            @else
                <div class='new-ct' style="margin-top:40px;">
                    @foreach($proxyList as $joinin)
                        @if($joinin->apply_grade == 30)
                            <div class="section">
                                <div class="s-num">申请类别：{{$joinin->apply_grade_name}}
                                    <a href="{{asset('personal/joininDetail') . '/' . $joinin->id}}"
                                       class="btn-search">进度查询</a>
                                </div>
                                <div class="s-cont">
                                    <div class="p-name">
                                        <a>{{$joinin->company_name}}</a>
                                    </div>
                                    <span class="p-static">状态：<span
                                                class="red">{{$joinin->state_name}}</span> <br></span>
                                    <span class="p-time">申请时间：{{date('Y-m-d H:i:s', $joinin->created_at)}}</span>
                                    @if($joinin->verify_state == 1)
                                        <a val="{{$joinin->id}}" style="margin-right:9px;float:right"
                                           class="btn-cancel">取消申请</a>
                                    @endif
                                </div>
                            </div>
                            <div class="bg-c"></div>
                        @endif
                    @endforeach
                </div>
            @endif
        @endif
    </div>
    <div id="pop-loading" style="z-index: 1001;display: none; position: fixed; top: 40%;left: 42.5%;">
        <img style="height:38px;width:38px;margin-top:60%;margin-left:48%" src="{{asset('img/loading.gif')}}">
    </div>
    <div id="pop-confirm" class="pop pop-ctm-01" style="display: none; position: fixed; top: 30%;left: 15%;;">
        <div class="pop-msg">
            <div id="confirm-content" class="pop-msg-cnt" style="padding-top:30px;min-height: 50px">
            </div>
            <div class="pop-msg-btm">
                <a id="confirm-close-btn" class="btn-h4 btn-c3" style="width: 96px;"
                   href="javascript:void(0);">关闭</a>
                <a id="confirm-ok-btn" class="btn-h4 btn-c3"
                   style="width: 96px;border: 1px solid #cfcfcf;color: #737373;background: #fff"
                   href="javascript:void(0);">确定</a>
            </div>
        </div>
    </div>
    <div class="__MASK_SID_DIV" style="display:none;width:100%;position: absolute; z-index: 999; left: 0px; top: 0px;">
        <div id="set_height"  style="width:100%; height: 1024px; opacity: 0.7; background: black none repeat scroll 0% 0%; position: static; margin: 0px;"></div>
    </div>
    </body>
@endsection

@section('js')
    <script type="text/javascript" src="{{asset('js/common.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/common-top.js')}}"></script>
    <script type="text/javascript">
        $(function () {
            $('#list').on('click', function () {
                $('#card').addClass('card-1').removeClass('card');
                $('#list').addClass('list-1').removeClass('list');
                $('#tab1').css('display', 'none');
                $('#tab2').css('display', 'block');

            });
            $('#card').on('click', function () {
                $('#card').addClass('card').removeClass('card-1');
                $('#list').addClass('list').removeClass('list-1');
                $('#tab2').css('display', 'none');
                $('#tab1').css('display', 'block');
            });

            //取消服务单
            $(".btn-cancel").click(function () {
                var id = $(this).attr("val");
                showConfirmDialog('确定取消申请？', function () {
                    hideConfirmDialog();
                    loading();
                    var url = '{{asset('personal/cancelApply')}}';
                    $.post(
                            url,
                            {id: id},
                            function (data) {
                                loadSucc();
                                message(data.message);
                                if (data.code == 0) {
                                    location.href = '{{asset('personal/joininList')}}';
                                }
                            },
                            'json'
                    );
                });
            });
        });
    </script>
@endsection



