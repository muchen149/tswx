@extends('inheritance')

@section('title')
    采购审核结果
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('sd_css/common.css')}}">

    <style>

        .procurement-step-wrap{
            position: relative;
            width: 100%;
            height: 60px;
            overflow: hidden;
        }
        .procurement-step{
            width: 94%;
            margin-left: 3%;
            margin-top: 6px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .step{
            text-align: center;
            font-size: 12px;
        }
        .stepText-normal{
            margin-bottom: 5px;
            color: rgb(200,200,200);
        }
        .stepText-active{
            margin-bottom: 5px;
            color: rgb(50,50,50);
        }
        .stepIcon-normal{
            color: rgb(200,200,200);
        }
        .stepIcon-active{
            color: #f53a3a;
        }
        .stepLine-normal{
            width: 66px;
            height: 2px;
            background: rgb(200,200,200);
        }
        .stepLine-active{
            width: 66px;
            height: 2px;
            background:rgb(80,80,80);
        }

        .procurement-status{
            width: 100%;
            margin-top: 110px;
            text-align: center;
        }
        .procurement-status ul{
            width: 86%;
            margin-left: 7%;
        }
        .procurement-status .status-text{
            margin-top: 30px;
        }
        .procurement-status .preview{
            display: flex;
            justify-content: center;
            align-items: center;
            width: 112px;
            height: 30px;
            margin: 20px auto 0;
            border: 1px solid #f53a3a;
            border-radius: 6px;
        }
        .procurement-status .procurement-btn{
            display: flex;
            justify-content: center;
            align-items: center;
            width: 114px;
            height: 32px;
            margin: 24px auto 0;
            background-color: #f53a3a;
            border-radius: 6px;
        }
        .status-reason{
            margin-top: 50px;
            text-align: left;
        }
        .status-reason p{
            margin-bottom: 6px;
        }
        .status-reason li{
            line-height: 26px;
        }

    </style>
@endsection

@section('content')
<body>
<div class="procurement-step-wrap b-white borderBottom">
    <div class="procurement-step">
        <ul class="step ">
            <li class="stepText-active">提交资料</li>
            <li class="stepIcon-active">
                <svg class="icon"  font-size="22" aria-hidden="true"><use xlink:href="#icon-buzhou"></use></svg>
            </li>
        </ul>
        <ul class="stepLine-active">
            <li></li>
        </ul>
        <ul class="step">
            <li class="stepText-active">后台审核</li>
            <li class="stepIcon-active">
                <svg class="icon" font-size="22" aria-hidden="true"><use xlink:href="#icon-buzhou1"></use></svg>
            </li>
        </ul>
        <ul class="stepLine-normal">
            <li></li>
        </ul>
        <ul class="step">

            @if( $memberJoinin->verify_state != 1)
                <li class="stepText-active">审核完成</li>
                <li class="stepIcon-active">
                    <svg class="icon" font-size="22" aria-hidden="true"><use xlink:href="#icon-buzou3"></use></svg>
                </li>
            @else
                <li class="stepText-normal">审核完成</li>
                <li class="stepIcon-normal">
                    <svg class="icon" font-size="22" aria-hidden="true"><use xlink:href="#icon-buzou3"></use></svg>
                </li>
            @endif



        </ul>
    </div>
</div>

{{--正在审核--}}

    <div class="procurement-status status1" style="@if( $memberJoinin->verify_state == 1) display: block; @else display: none; @endif">
        <ul>
            <li class="font-14 color-80 font-ber">您提交的资料正在审核，需3-7个工作日，请您耐心等待！</li>
            <a href="{{asset('member/manage_procurement_detail')}}" class="preview font-12 color-50">预览提交的资料</a>
        </ul>
    </div>

    {{--审核成功--}}
    <div class="procurement-status status2" style="@if( $memberJoinin->verify_state == 2) display: block; @else display: none; @endif">
        <ul>
            <li>
                <svg class="icon font-40" style="color: #f53a3a" aria-hidden="true"><use xlink:href="#icon-xuanzhong2"></use></svg>
            </li>
        </ul>
        <ul class="status-text">
            <li class="font-14 color-80 font-ber">恭喜,您的采购申请已经通过审核!</li>
        </ul>
        <ul class="flex-between">
            <a href="{{asset('member/manage_procurement_detail')}}" class="preview font-12 color-50">预览提交的资料</a>
            <a href="{{asset('personal/index')}}" class="procurement-btn color-white font-12">立即采购</a>
        </ul>

    </div>


    {{--审核失败--}}
    <div class="procurement-status status3" style="@if( $memberJoinin->verify_state == -1 || $memberJoinin->verify_state == 0 ) display: block; @else display: none; @endif">
        <ul>
            <li>
                <svg class="icon font-40" style="color: #f53a3a" aria-hidden="true"><use xlink:href="#icon-shibai"></use></svg>
            </li>
        </ul>
        <ul class="status-text">
            <li  class="font-14 color-80 font-ber">抱歉,您的采购申请未通过审核!
            </li>
        </ul>
        <ul>
            <a href="{{asset('member/manage_procurement/1')}}" class="procurement-btn font-12 color-white">重新申请</a>
        </ul>
        <ul class="status-reason">
            <p class="font-14 color-50">说明</p>
            <li class="font-12 color-80">1、申请人必须是账号的所有者；</li>
            <li class="font-12 color-80">2、身份证照片不可用图片软件处理；</li>
            <li class="font-12 color-80">3、人脸照片需保证清晰；</li>
        </ul>
    </div>





</body>
@endsection

@section('js')
    <script src="{{asset('sd_js/jquery.min.js')}}"></script>
    <script src="{{asset('js/font_wvum.js')}}"></script>
@endsection



