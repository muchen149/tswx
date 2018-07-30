@extends('inheritance')

@section('title')
    申请进度查询
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('css/header.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/process.css')}}" charset="utf-8">
    <style type="text/css">
        .detai-list .btns {
            padding-right: .625em;
            margin: 20px 0;
        }

        .detai-list .btn-c2 {
            display: block;
            background-color: #ebebeb;
        }

        .detai-list .btn-h2 {
            height: 25px;
            line-height: 25px;
            padding-left: 9px;
            padding-right: 9px;
            font-size: 12px;
            text-align: center;
            border-radius: 3px;
        }

        .detai-list #shenhemsg, .detai-list #goodsback_addr {
            display: inline-block;
            line-height: 1.5em;
            height: 4.5em;
            overflow: hidden;
        }
    </style>
@endsection

@section('content')
    <body style="background-color: rgb(240,240,240)">
    <div id="header"></div>
    <div class="progress-two">
        <section>
            <h1 class="title-bar probDesc" style="">申请信息</h1>
        </section>
        <input id="afsServiceProcessFlag" name="afsServiceProcessFlag" value="false" type="hidden">

        <ul class="m-list detai-list" id="wentiComp">
            <li class="probDesc" style="">
                <div class="tbl-cell full">
                    <span id="yuyuemsg"
                          style="word-break:break-all;font-size:14px;">{{$memberJoinin->company_name."      ".$memberJoinin->company_phone}}</span>
                    <input name="questionDesc" id="questionDesc" style="display:none;width:80%" value="" type="text">
                </div>
            </li>
        </ul>
        @if($memberJoinin->verify_state == -1 && !empty($memberJoinin->reason_info))
        <section class="probDesc2" style="">
            <h1 class="title-bar">审核留言
            </h1>
        </section>
        <ul class="m-list detai-list" id="wentiComp">
            <li class="probDesc2" style="">
                <div class="tbl-cell full" id="shenheId">
                    <span id="shenhemsg" style="overflow: visible; height: auto;font-size:14px;">{{$memberJoinin->reason_info}}</span>
                </div>
            </li>
        </ul>
        @endif
        <section>
            <h1>审核进度</h1>
        </section>
        <div class="deal-list">
            <ul>
                @if($memberJoinin->verify_state == 1)
                <li class="cur">
                    <span>{{date('Y-m-d H:i:s', $memberJoinin->created_at)}}</span>
                    <span>您的申请已提交，待平台管理员审核</span>
                    <span>经办：系统</span>
                    <span class="pointer"></span>
                </li>
                @elseif($memberJoinin->verify_state == 0)
                <li class="cur">
                    <span>{{date('Y-m-d H:i:s', $memberJoinin->updated_at)}}</span>
                    <span>您的申请已取消</span>
                    <span>经办：本人</span>
                    <span class="pointer"></span>
                </li>
                <li class="cur">
                    <span>{{date('Y-m-d H:i:s', $memberJoinin->created_at)}}</span>
                    <span>您的申请已提交，待平台管理员审核</span>
                    <span>经办：系统</span>
                    <span class="pointer"></span>
                </li>
                @elseif($memberJoinin->verify_state == 2)
                <li class="cur">
                    <span>{{date('Y-m-d H:i:s', $memberJoinin->updated_at)}}</span>
                    <span>您的申请审核已通过</span>
                    <span>经办：平台</span>
                    <span class="pointer"></span>
                </li>
                <li class="cur">
                    <span>{{date('Y-m-d H:i:s', $memberJoinin->created_at)}}</span>
                    <span>您的申请已提交，待平台管理员审核</span>
                    <span>经办：系统</span>
                    <span class="pointer"></span>
                </li>
                @else
                <li class="cur">
                    <span>{{date('Y-m-d H:i:s', $memberJoinin->updated_at)}}</span>
                    <span>您的申请审核不通过</span>
                    <span>经办：平台</span>
                    <span class="pointer"></span>
                </li>
                <li class="cur">
                    <span>{{date('Y-m-d H:i:s', $memberJoinin->created_at)}}</span>
                    <span>您的申请已提交，待平台管理员审核</span>
                    <span>经办：系统</span>
                    <span class="pointer"></span>
                </li>
                @endif

            </ul>
            <span class="line"></span>

        </div>
    </div>
    </body>
@endsection

@section('js')
    <script type="text/javascript" src="{{asset('js/common-top.js')}}"></script>
@endsection



