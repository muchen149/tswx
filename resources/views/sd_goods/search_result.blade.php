@extends('inheritance')

@section('title')
    商品列表
@endsection

@section('css')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link rel="stylesheet" href="{{asset('/sd_css/swiper.min.css')}}">
    <link rel="stylesheet" href="{{asset('/sd_css/new_common.css')}}">
    <link rel="stylesheet" href="{{asset('/sd_css/shop.css')}}">
    <script type="text/javascript" src="{{asset('/sd_js/jquery-1.11.2.min.js')}}"></script>
    <style>
        a:link, a:visited, a:hover, a:active{
            text-decoration:none;
        }
    </style>
@endsection



@section('content')
    <body>
    <div class="container">
        <input type="hidden" value="{{$separatePage['pageNumber']}}" id="pageNumber"/>
        <input type="hidden" value="{{$goodsName}}" id="goodsName"/>
        <input type="hidden" value="{{$queryParameter['gcId']}}" id="gcId"/>
        <input type="hidden" value="{{$separatePage['pageSize']}}" id="pageSize"/>
        <div class=" s-listTab" id="result-sort">
            <ul>
                {{--<li class=" active"><a href="">综合</a></li>
                <li class=""><a href="">销量</a></li>
                <li class=""><a href="">价格</a></li>
                <li class=""><a href="">人气</a></li>--}}
                <li class="
                       @if($orderBy == 0)
                        active
                       @endif
                        keyorder" key='' order="0"><a href="#">综合</a></li>
                <li class="
                        @if($orderBy == 1)
                        active
                       @endif
                        keyorder" id="isSales" key='salenum' order="1"><a href="#">销量</a></li>
                <li class="
                      @if($orderBy == 2)
                        active
                       @endif
                        keyorder" key='cose_price' order="2"><a href="#">价格</a></li>
                <li class="
                      @if($orderBy == 3)
                        active
                       @endif
                        keyorder" key='click' order="3"><a href="#">人气</a></li>
            </ul>
        </div>
        <div class="s-listAll" id="product_list">
            @if(!empty($goodsSpuList))

                <ul class="clearFix">
                    {{-- <li>
                         <a href=""><img src="images/img07.jpg" width="100%" alt="">
                             <h4>鼻毛修剪器男士男用套装电动充电鼻毛修剪器男士男用套装电动充电鼻毛修剪器男士男用套装电动充电</h4>
                             <p class="price">¥59</p>
                             <p class="tag"><i>红包</i><i>满减</i></p>
                         </a>
                     </li>--}}

                    @foreach($goodsSpuList as $good)
                        <li>
                            <a href="{{url('/shop/goods/spuDetail/'.$good->spu_id)}}"><img src="{{$good->main_image}}" width="100%" alt="">
                                <h4>{{$good->spu_name}}</h4>
                                <p class="price">¥&nbsp;{{$good->spu_price}}</p>
                            </a>
                        </li>
                    @endforeach

                </ul>

            @endif
        </div>
{{--        <div class="pagination mt10"
             style="width: 100%;
                         text-align:center;
                         margin-top: 22px!important;
                         overflow: hidden!important;

                         ">
            <div>
                <a href="javascript:void(0);" class="pre-page
                        @if($separatePage['pageNumber'] == 1)
                        disabled
                @endif
                        ">上一页
                </a>
                <select name="page_list" style="padding: 6px 4px;  vertical-align: top;">
                    @for($i=1; $i<= ceil($separatePage['allRows'] / $separatePage['pageSize']); $i++)
                        @if($i == $separatePage['pageNumber'])
                            <option value="{{$i}}" selected>{{$i}}</option>
                        @else
                            <option value="{{$i}}">{{$i}}</option>
                        @endif
                    @endfor
                </select>

                <input name="page_total" type="hidden" value="{{ceil($separatePage['allRows'] / $separatePage['pageSize'])}}" id="page_total">
                <a href="javascript:void(0);" class="next-page
                         @if($separatePage['pageNumber'] == ceil($separatePage['allRows'] / $separatePage['pageSize']))
                        disabled
                     @endif
                        ">下一页
                </a>
            </div>
            </div>--}}
            {{--<div class="main-nav">
                <ul>
                    <li class=" navBtn"><a href="{{asset('/shop/index')}}"><span class="icon"></span><span class="text">管家服务</span></a></li>
                    <li class="active navBtn"><a href="{{asset('/sd_shop/shop')}}"><span class="icon"></span><span class="text">集市</span></a></li>
                    <li class="calling navBtn"><a href="{{asset('member/call')}}"><span class="icon"><i></i></span><span class="text">召唤管家</span></a></li>
                    <li class=" navBtn"><a href="{{asset('/cart/index')}}"><span class="icon"></span><span class="text">购物车</span></a></li>
                    <li class=" navBtn"><a href="{{asset('/personal/index')}}"><span class="icon"></span><span class="text">我的</span></a></li>
                </ul>
            </div>--}}
            <div class="main-nav">
                <ul>
                    <li class=" navBtn"><a href="{{asset('/shop/index')}}"><span class="icon"></span><span class="text">管家服务</span></a></li>
                    {{--<li class=" navBtn"><a href="{{asset('/sd_shop/shop')}}"><span class="icon"></span><span class="text">分类</span></a></li>--}}
                     {{--<li class="calling navBtn"><a href="{{asset('member/call')}}"><span class="icon"><i></i></span><span class="text">召唤管家</span></a></li>--}}
                    <li class=" navBtn"><a href="{{asset('/cart/index')}}">@if($goods_num_in_cart < 100)<span class="cart_num">{{$goods_num_in_cart}}</span>@else<span class="cart_big_num">99+</span>@endif<span class="icon"></span><span class="text">购物车</span></a></li>
                    <li class=" navBtn"><a href="{{asset('/personal/index')}}"><span class="icon"></span><span class="text">我的</span></a></li>
                </ul>
            </div>
        </div>
    </body>


@endsection

@section('js')
    {{--    <script type="text/javascript" src="{{asset('js/common.js')}}"></script>
        <script type="text/javascript" src="{{asset('js/footer.js')}}"></script>
        <script type="text/javascript" src="{{asset('js/goods/goods_list.js')}}"></script>--}}
    <script type="text/javascript" src="{{asset('/sd_js/shop.js')}}"></script>
    <script type="text/javascript" src="{{asset('/sd_js/swiper.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/goods/goods_list.js')}}"></script>
    <script type="text/javascript" src="{{asset('/sd_js/scrollLoadData.js')}}"></script>
    <script type="text/javascript">
        var currentPage=1;
        var pageRows=10;


        //使用方法
        scrollLoadData({
            container: '.clearFix',
            currentPage: currentPage,
            pageRows: pageRows,
            requestData: function(currentPage, pageRows, callback) {
                // currentPage 当前加载的页码
                // pageRows 每页加载多少条
                // callback 加载完成后的回调函数
                // callback 说明：由于加载新数据为动态加载ajax，是用户自定义方法并非组件内部ajax无法控制；保证在数据请求过程中不能再次请求发送请求，callback内包含参数的值为true/false;
                // true 表示仍有数据，false表示没有数据



                //ajax请求函数
                pageLoad(++currentPage, pageRows, callback);
            }

        });

        var match = /((http|https):\/\/)+(\w+\.)+(\w+)[\w\/\.\-]*(jpg|gif|png|JPG|PNG|GIF|JPEG)/;
        function pageLoad(page, rows, callback) {
            var _this = this;

            var url = "/shop/goods/ajax/spuList/"+page+"/"+rows+"/"+$('#gcId').val()+"/0/"+$('#goodsName').val();
            //alert(url);
            $.ajax({
                url: url,
                type:'get',
                data: {},
                dataType: ''
            }).done(function(data) {
                if (data == null || data.code != "0") {
                    console.log(data.message);
                } else {
                    console.log(data);
                    if (data.message.length == 0 && page == 1) {
                        $(".noData").show();
                    } else if (data.data.goodsSpuList.length == 0) {
                        $(".noData").show();
                        callback(false);
                        //没有更多了
                    } else {
                        $(".noData").hide();
                        console.log(data.data.goodsSpuList);
                        var html = "";
                        var data = data.data.goodsSpuList;
                        if (data != "") {
                            for (var i = 0; i < data.length; i++) {

                                html += '<li>';
                                html += '<a href="/shop/goods/spuDetail/' + data[i].spu_id + '">';
                                if (match.test(data[i].main_image)) {
                                    html += '<img src="' + data[i].main_image + '" width="100%" alt="">';
                                } else {
                                    html += '<img src="../' + data[i].main_image + '" width="100%" alt="">';
                                }
                                html += '<h4>' + data[i].spu_name + '</h4>';
                                html += '<p class="price">¥&nbsp;' + data[i].spu_price + '</p>';
                                html += '</a></li>';


                            }

                            currentPage++;
                            $(".clearFix").append(html);
                        }

                        /*$.each(data.data.goodsSpuList,function(i,n)
                         {
                         alert("索引:"+ i ,"对应值为："+n.name);
                         });*/

                        callback(true);
                    }
                }

            }).fail(function(e){});
        }

        function callback(loaded){
            alert(loaded);
        }
    </script>

@endsection
