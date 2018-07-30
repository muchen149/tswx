<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>图文详情</title>
    <link rel="stylesheet" href="http://mimg.127.net/hxm/yanxuan-web/p/20150730/style/css/style-2181e276ae.css" type="text/css">
    <style type="text/css">
        .content{
            margin: 0 auto;
            min-width: 320px;
            overflow: hidden;
        }
        img {
            width: 100%;
        }

    </style>
</head>
<body>
@if(!$mobilecontent)
    <div style="margin:0 auto;height:400px;width:200px;margin-top:40px;text-align: center">
        <img style="width:100%" src="/img/empty_quan.png">
        <p class="mt20" style="color:#323232;">亲，暂时还没有图文详情介绍！</p>
    </div>
@else
    {!! $mobilecontent !!}
@endif
</body>
</html>