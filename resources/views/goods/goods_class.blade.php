@extends('inheritance')

@section('title')
    商品分类
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('css/main.css')}}">
    <link href="{{asset('css/search_list.css')}}" rel="stylesheet">
    <link href="{{asset('css/base_goods.css')}}" rel="stylesheet">
    <style>
        a:link, a:visited, a:hover, a:active {
            text-decoration: none;
        }
    </style>
@endsection



@section('content')
    <body id="home-320">
    <div class="sd-nav sd-block sd-nav-search">
        <div class="sd-nav-img" style="z-index:-1">
        </div>
        <div class="sd-nav-back"><a class="sd-iconbtn" href="javascript:history.back();">返回</a></div>
        <div class="sd-nav-inp wbox">
            <i class="sd-search-iconbtn"></i>
            <div class="wbox-flex">
                <form action="javascript:searchSubmit();" id="search_form" autocomplete="off">
                    <input id="keyword" name="keyword" class="searchInp" type="text" cleardefault="no"
                           autocomplete="off"
                           placeholder="查找心仪的商品" value=""/>
                </form>
                <input type="hidden" value="" id="pageNumber"/>
                <input type="hidden" value="" id="pageSize"/>
            </div>
            <div class="clo-se-input"></div>
        </div>

        <a href="javascript:void(0);" class="search-btn1 J_filter">搜索</a>
    </div>

    <div class="content">
        <div class="categroy" id="categroy-cnt">
            <div class="list-wrap wbox" id="J_listWrap">
                <div class="list-items overtouch" id="listItems">
                    <ul>
                        @foreach($one_goods_class as $k => $one)
                            <li onclick="" class="{{$k == 0 ? " cur" : ""}}" name="wapfly_none_fl_one-fenlei{{$k}}">
                                <em>{{$one['name']}}</em></li>
                        @endforeach
                    </ul>
                </div>
                <div class="list-details wbox-flex overtouch">
                    @foreach($one_goods_class as $i => $one1)
                        <div class="list-detail {{$i == 0 ? "" : "cur"}}">
                            <div class="list-slide-wrap" id="listSwipe{{$i}}">
                                <ul class="list-slide">
                                </ul>
                            </div>
                            <div class="list-label list-label-img">
                                @foreach($two_goods_class[$one1['id']] as $two)
                                    <dl>
                                        <dt>{{$two['name']}}</dt>
                                        <dd>
                                            <ul style="overflow: hidden">
                                                @foreach($three_goods_class[$two['id']] as $three)
                                                    <li><a name="wapfly_none_fl_three-fenlei1"
                                                           href="{{asset('shop/goods/spuList/1/10/' . $three['id'])}}"><span>{{$three['name']}}</span></a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </dd>
                                    </dl>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <footer id="footer"></footer>
    </body>
@endsection

@section('js')
    <script type="text/javascript" src="{{asset('js/footer.js')}}"></script>
    <script type="text/javascript">
        $(function () {

            $('#keyword').keydown(function (event) {
                if (event.keyCode == 13) {
                    var keyword = encodeURIComponent($('#keyword').val());
                    location.href = '{{asset('shop/goods/spuList/1/10/0')}}' + '/' + keyword;
                }
            });
            $('.search-btn1').click(function () {
                var keyword = encodeURIComponent($('#keyword').val());
                location.href = '{{asset('shop/goods/spuList/1/10/0')}}' + '/' + keyword;
            });

            $("li[name^=wapfly_]").on("click", function () {
                var sa = $(this).attr('name');
                var patt = new RegExp("wapfly_none_fl_one-fenlei");
                var num = sa.replace(patt, '');
                var obj_d = $('#listSwipe' + num);
                $('.list-details div.list-detail').each(function () {
                    if (!$(this).hasClass('hide')) {
                        $(this).addClass('hide');
                    }
                });
                obj_d.parent().removeClass('hide');
                $('#listItems').find('li.cur').removeClass('cur');
                $(this).addClass('cur');
            });
        });
    </script>

@endsection
