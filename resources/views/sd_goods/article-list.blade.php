<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>文章列表</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <script src="http://www.anydo.wang/resource/js/jquery-3.1.1.js"></script>
    <style>
        *{
            margin: 0;
            padding: 0;
            border: 0;
            list-style: none;
            font-family:"Microsoft YaHei", 'Source Code Pro', Menlo, Consolas, Monaco, monospace;
        }
        body{
            background-color: rgb(240,240,240);
        }
        .article{
            width: 100%;
            background-color: white;
        }
        .article-list{
            width: 95%;
            margin: 0 auto;
            height: 100px;
            background-color: white;
            display: flex;
            justify-content: space-between;
           align-items: center;
            border-bottom: 1px solid rgb(220,220,220);
        }
        .article-list-left{
            margin-left: 2%;
            width: 28%;
        }
        .article-list-left img{
            width: 80px;
        }
        .article-list-right{
            margin-right: 2%;
            width: 68%;
        }
        .article-list-title{
            font-size: 14px;
            color: rgb(50,50,50);
        }
        .article-list-bottom{
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 18px;
        }
        .article-list-bottom li{
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
            color: rgb(100,100,100);
        }
        .article-list-bottom li img{
            width: 22px;
            margin-right: 4px;
        }
        .article-list-default{
            width:100%;
            margin: 150px 0;
            text-align: center;

        }
        .article-list-default p{
            color: rgb(120,120,120);
            font-size: 14px;
            margin-top: 10px;
        }
        .article-list-default img{
            width: 150px;
        }

    </style>
</head>
<body>
{{--<div class="article">
    <ul class="article-list">
        <div class="article-list-left">
            <li><img src="{{asset('pic/article-list-default.png')}}" alt=""></li>
        </div>

        <div class="article-list-right">
            <li class="article-list-title">我是文章的标题：对2020中日战争可能性及胜负的的分析与研究讨论</li>
            <div class="article-list-bottom">
                <li>2017-20-20</li>
                <li><img src="{{asset('sd_img/zan.png')}}" alt="" style="color: red">3256</li>
                <li><img src="{{asset('sd_img/saw.png')}}" alt="">5000</li>
            </div>
        </div>

    </ul>
</div>--}}
@if(!empty($articleList))
        @foreach($articleList as $article)
            <div class="article">
                <ul class="article-list">
                    <div class="article-list-left">
                        <li><img src="{{$article->image_url_1}}" alt=""></li>
                    </div>

                    <div class="article-list-right">
                        <li class="article-list-title">{{$article->article_title}}</li>
                        <div class="article-list-bottom">
                            <li>{{$article->create_time}}</li>
                            <li><img src="{{asset('sd_img/zan.png')}}" alt="" style="color: red">{{$article->upvote}}</li>
                            <li><img src="{{asset('sd_img/saw.png')}}" alt="">{{$article->reading}}</li>
                        </div>
                    </div>
                </ul>
            </div>

        @endforeach
@endif
<script>
    $('.article').first().css('margin-top','10px');
    $('.article').last().css('margin-bottom','30px');
    $('.article-list').last().css("border-bottom",'0px');

    $(function(){
        var $Length=$('.article');
        if ($Length.length==0){
            $('body').append($('<div class="article-list-default"><img src="img/list_default_img.svg" alt=""><p>暂无文章列表</p></div>'))
        }
    })
</script>
</body>
</html>