<?php $__env->startSection('title'); ?>
    商品列表
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link rel="stylesheet" href="<?php echo e(asset('/sd_css/swiper.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('/sd_css/new_common.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('/sd_css/shop.css')); ?>">
    <script type="text/javascript" src="<?php echo e(asset('/sd_js/jquery-1.11.2.min.js')); ?>"></script>
    <style>
        a:link, a:visited, a:hover, a:active{
            text-decoration:none;
        }
    </style>
<?php $__env->stopSection(); ?>



<?php $__env->startSection('content'); ?>
    <body>
    <div class="container">
        <input type="hidden" value="<?php echo e($separatePage['pageNumber']); ?>" id="pageNumber"/>
        <input type="hidden" value="<?php echo e($separatePage['pageSize']); ?>" id="pageSize"/>
        <input type="hidden" value="<?php echo e($queryParameter['gcId']); ?>" id="gcId"/>
        <input type="hidden" value="<?php echo e($queryParameter['gcType']); ?>" id="gcType"/>
        <div class=" s-listTab" id="result-sort">
            <ul>
                
                <li class="
                       <?php if($orderBy == 0): ?>
                        active
                       <?php endif; ?>
                        keyorder" key='' order="0"><a href="#">综合</a></li>
                <li class="
                        <?php if($orderBy == 1): ?>
                        active
                       <?php endif; ?>
                        keyorder" id="isSales" key='salenum' order="1"><a href="#">销量</a>
                </li>
                <li class="
                      <?php if($orderBy == 2): ?>
                        active
                       <?php endif; ?>
                        keyorder" key='cose_price' order="2"><a href="#">价格</a>
                </li>
                <li class="
                      <?php if($orderBy == 3): ?>
                        active
                       <?php endif; ?>
                        keyorder" key='click' order="3"><a href="#">人气</a>
                </li>
            </ul>
        </div>
        <div class="s-listAll" id="product_list">
            <?php if(!empty($goodsSpuList)): ?>

                <ul class="clearFix">
                   

                    <?php $__currentLoopData = $goodsSpuList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $good): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                        <li>
                            <a href="<?php echo e(url('/ys/goods/spuDetail/'.$good->spu_id)); ?>"><img src="<?php echo e($good->main_image); ?>" width="100%" alt="">
                                <h4><?php echo e($good->spu_name); ?></h4>
                                <p class="price">¥&nbsp;<?php echo e($good->spu_price); ?></p>
                                
                            </a>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>

                </ul>

            <?php endif; ?>
        </div>

        <input name="page_total" type="hidden" value="<?php echo e(ceil($separatePage['allRows'] / $separatePage['pageSize'])); ?>" id="page_total">


        <div class="main-nav">
            <ul>
                <li class=" navBtn"><a href="<?php echo e(asset('/shop/index')); ?>"><span class="icon"></span><span class="text">管家服务</span></a></li>
                <li class=" navBtn"><a href="<?php echo e(asset('/sd_shop/shop')); ?>"><span class="icon"></span><span class="text">集市</span></a></li>
                <li class="calling navBtn"><a href="<?php echo e(asset('member/call')); ?>"><span class="icon"><i></i></span><span class="text">召唤管家</span></a></li>
                <li class=" navBtn"><a href="<?php echo e(asset('/cart/index')); ?>"><span class="icon"></span><span class="text">购物车</span></a></li>
                <li class=" navBtn"><a href="<?php echo e(asset('/personal/index')); ?>"><span class="icon"></span><span class="text">我的</span></a></li>
            </ul>
        </div>
    </div>
    </body>


<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>

    <script type="text/javascript" src="<?php echo e(asset('/sd_js/shop.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(asset('/sd_js/swiper.min.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(asset('ys_js/ysgoods_list.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(asset('/sd_js/scrollLoadData.js')); ?>"></script>
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

        var url = "/ys/goods/ajax/spuList/"+page+"/"+rows+"/"+$('#gcId').val()+"/0/0/"+$('#gcType').val();
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
                            html += '<a href="/ys/goods/spuDetail/' + data[i].spu_id + '">';
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

<?php $__env->stopSection(); ?>

<?php echo $__env->make('inheritance', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>