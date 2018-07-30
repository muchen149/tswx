<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>商城</title>
    <link rel="stylesheet" href="{{asset('/sd_css/swiper.min.css')}}">
    <link rel="stylesheet" href="{{asset('/sd_css/new_common.css')}}">
    <link rel="stylesheet" href="{{asset('/sd_css/shop.css')}}">
</head>

<body>
<div class="container">
    <div class="search-header">
        <a href="{{asset('/sd_shop/index')}}" class="btn-return icon-back"></a>
        <form id="searchFrm" action="{{asset('/sd_shop/doSearch')}}">
            <i class="icon"></i>
            <input type="search" name="" id="searchKey">
            <input type="submit" value="搜索" id="search" style="right:-45px;width:50px;">
            {{--<input type="submit" onclick="javascript:search();" value="搜索">--}}
        </form>
    </div>
    <div class="search-history">
        <h3>历史搜索</h3>
        <span class="btn-del" onclick='deleteItem()'>删除</span>
        <ul class="list" id="list">
            {{--<li class="skey">有机大米</li>
            <li class="skey">有机</li>
            <li class="skey">书架</li>--}}

        </ul>
        <p class="tip">暂无搜索历史</p>
    </div>
    <div class="search-recommend">
        <h3>常用搜索</h3>
        <span class="btn-ctrl hide">显示</span>
        <ul class="list">
            @if(!empty($hotSearch))
                @foreach($hotSearch as $val)
                    <li class="skey">{{$val->name}}</li>
                @endforeach
            @endif
        </ul>
        <p class="tip">常用搜索已隐藏</p>
    </div>
</div>
<script type="text/javascript" src="{{asset('/sd_js/jquery-1.11.2.min.js')}}"></script>
<script type="text/javascript" src="{{asset('/sd_js/swiper.min.js')}}"></script>
<script type="text/javascript" src="{{asset('/sd_js/search.js')}}"></script>
</body>

</html>