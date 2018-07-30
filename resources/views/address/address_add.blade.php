@extends('inheritance')

@section('title')
    地址添加
@endsection
@section('css')
    <link rel="stylesheet" type="text/css" href="/css/header.css">
    <link rel="stylesheet" type="text/css" href="/css/address/address-add.css">

    <style>
        select {
            width: 70%;
            /*很关键：将默认的select选择框样式清除*/
            appearance: none;
            -moz-appearance: none;
            -webkit-appearance: none;

            /*为下拉小箭头留出一点位置，避免被文字覆盖*/
            color: #aeaeae;
            font-size: 14px;
            background-color: white;
            outline: none;
        }

        /*清除ie的默认选择框样式清除，隐藏下拉箭头*/
        select::-ms-expand {
            display: none;
        }

        .error-tips {
            color: red;
            display: none;
            padding: 5px 10px;
            border: 1px solid red;
            background: #FFF6D7;
            text-align: center;
        }

        .error-tips p {
            padding: 2px 0px;
        }

        a:link, a:visited, a:hover, a:active{
            text-decoration:none;
        }
    </style>
@endsection

@section('content')
    <body>
    <div id="header"></div>

    <ul class="body_ul">
        <li>
            <p>收货人</p>
            <input type="text" class="input-30" id="true_name" name="true_name" maxlength="8"
                   value="" placeholder="请填写收货人姓名"/>
        </li>
        <li>
            <p>联系电话</p>
            <input type="tel" class="input-30" id="mob_phone" name="mob_phone" maxlength="11"
                   value="" placeholder="请填写收货人联系电话"/>
        </li>
        <div class="fenge"></div>


        <div class="add_pro">
            <li>
                <p>所在省份<span class="opera-tips"></span></p>
                <select class="select-30" id="prov_select" name="prov">
                    <option value="">请选择所在省份...</option>
                    @foreach($province_dct as $p)
                        <option value="{{$p['id']}}">{{$p['name']}}</option>
                    @endforeach

                </select>
            </li>
            <li>
                <p>所在城市<span class="opera-tips"></span></p>
                <select class="select-30" id="city_select" name="city">
                    <option value="">请选择所在城市...</option>
                </select>
            </li>
            <li id="next">
                <p>所在区县<span class="opera-tips"></span></p>
                <select class="select-30" id="region_select" name="region">
                    <option value="">请选择所在区县...</option>
                </select>
            </li>
        </div>
        <li>
            <p>详细信息</p>
            <input type="text" class="input-30" id="address" name="address" maxlength="30"
                   value="" placeholder="小区名称、楼栋、门牌号等"/>
        </li>
        <div class="fenge"></div>
        <li>
            <p>设为默认</p>
            <input type="hidden"  id="is_use" name="is_use" value="0"/>
            <img nctype="setdefault" ncdefault="no" src="/img/address/address-default-pre.png">
        </li>

    </ul>

    <div class="error-tips"></div>
    <div style="width: 100%;height: 60px;background-color: rgb(240,240,240)"></div><!--用于增加页面高度-->

    <div class="btn add_address mt10" style="border-radius: 0">
        <p style="line-height: 37px;">保存地址</p>
    </div>


</body>

@endsection

@section('js')
    <script type="text/javascript" src="/js/zepto.min.js"></script>
    <script type="text/javascript" src="/js/template.js"></script>
    <script type="text/javascript" src="/js/common.js"></script>
    <script type="text/javascript" src="/js/simple-plugin.js"></script>
    <script type="text/javascript" src="/js/common-top.js"></script>
    <script type="text/javascript" src="/js/address/address_add.js"></script>
@endsection
