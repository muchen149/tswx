@extends('inheritance')

@section('title')
    商品列表
@endsection

@section('css')
    <link href="{{asset('css/main.css')}}" rel="stylesheet">
    <link href="{{asset('css/search_list.css')}}" rel="stylesheet">
    <link href="{{asset('css/base_goods.css')}}" rel="stylesheet">
    <style>
        a:link, a:visited, a:hover, a:active{
            text-decoration:none;
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
                    <input id="keyword" name="keyword" class="searchInp" type="text" cleardefault="no" autocomplete="off"
                           placeholder="查找心仪的商品" value="{{$goodsName}}"/>
                </form>
                <input type="hidden" value="{{$separatePage['pageNumber']}}" id="pageNumber"/>
                <input type="hidden" value="{{$separatePage['pageSize']}}" id="pageSize"/>
            </div>
            <div class="clo-se-input"></div>
        </div>

        <a href="javascript:void(0);" class="search-btn1 J_filter">搜索</a>
    </div>

    <div class="content" >

        <div class="result-wrap">
            <ul class="result-sort wbox" id="result-sort">
                <li class="wbox-flex
                       @if($orderBy == 0)
                        cur
                       @endif
                        keyorder" key='' order="0">综合</li>
                <li class="wbox-flex
                        @if($orderBy == 1)
                        cur
                       @endif
                        keyorder" id="isSales" key='salenum' order="1">销量
                </li>
                <li class="wbox-flex
                      @if($orderBy == 2)
                        cur
                       @endif
                      keyorder" key='cose_price' order="2">价格
                </li>
                <li class="wbox-flex
                      @if($orderBy == 3)
                        cur
                       @endif
                        keyorder" key='click' order="3">人气
                </li>

            </ul>
        </div>


        <div class="product-cnt">
            <div id="product_list" class="result-hot-pro lazyimg">
              @if(!empty($goodsSpuList))
                    <ul id="productsList" >
                        @foreach($goodsSpuList as $good)
                                <li>
                                    <a class="link" href="{{url('/shop/goods/spuDetail/'.$good->spu_id)}}" style="
                                    width: 100%;
                                    display: flex;
                                    justify-content: space-between;
                                    align-items: center;
                                    border-bottom: 1px solid rgb(220,220,220);
                                    ">

                                        <div class="img" style="width: 30%;">
                                            <div class="img-occupied" d-width="100px">
                                                <img src="{{$good->main_image}}"/>
                                            </div>
                                        </div>

                                        <div class="txt" style="width: 70%;">
                                            <p class="name" style="min-height:48px;">{{$good->spu_name}}</p>

                                            <div style=" color:#D50609; padding-top:5px;">
                                                <p class="price-box">
                                                   <span class="price-txt">
                                                      <em style="font-weight:bold; color:rgba(231,23,26,1.00);">¥&nbsp;{{$good->spu_price}}</em>
                                                   </span>
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </li>

                        @endforeach

                    </ul>

                    <div class="pagination mt10"
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
              @else
                 <div class="no-record">
                     暂无记录</div>
                 </div>
              @endif
            </div>
        </div>
    </div>
    </div>
    <footer id="footer"></footer>

</body>


@endsection

@section('js')
    <script type="text/javascript" src="{{asset('js/common.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/footer.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/goods/goods_list.js')}}"></script>

@endsection
