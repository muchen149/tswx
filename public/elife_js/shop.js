bindClick();
function bindClick(){
    $('#class:first').addClass('active').siblings().removeClass("active");
    var cls = parseInt($('#class:first').attr('code'));
    var currentPage = 2;
    showGoodsList(cls,currentPage);
    $('.swiper-slide').click(function () {
        $(this).addClass("active").siblings().removeClass("active");
        var cls=parseInt($(this).attr('code'));
        var currentPage = 1;
        showGoodsList(cls,currentPage);
    });
}

function showGoodsList(cls,currentPage){
    var pageRows=10;
    var match = /((http|https):\/\/)+(\w+\.)+(\w+)[\w\/\.\-]*(jpg|gif|png|JPG|PNG|GIF|JPEG)/;
    var url = "/elife/goods/ajax/shopSpuList/"+currentPage+"/"+pageRows+"/"+cls;
    $.ajax({
        url: url,
        type: 'get',
        data: {},
        dataType: ''
    }).done(function (data) {
        if (data == null || data.code == 100) {
            var html = '<ul class="clearFix"><img src="../../elife_img/noGoods.jpg" width="100%" alt=""></ul>';
            $('.clearFix').html(html);
            $('.scroll-load-wait').hide();
        } else {
            var html = '';
            if (data.code == 300) {
                callback(false);
            }else {
                /*var message = data.message;
                $('.scroll-load-wait').html(message);*/
                $('.scroll-load-wait').remove();
                var data = data.data;
                if (data != "") {
                    for (var i = 0; i < data.length; i++) {
                        html += '<li>';
                        html += '<a href="/elife/goods/spuDetail/' + data[i].spu_id + '">';
                        if (match.test(data[i].main_image)) {
                            html += '<img src="' + data[i].main_image + '" width="100%" alt="">';
                        } else {
                            html += '<img src="../' + data[i].main_image + '" width="100%" alt="">';
                        }
                        html += '<h4>' + data[i].spu_name + '</h4>';
                        html += '<p class="price">¥&nbsp;' + data[i].spu_plat_price + '&nbsp;<del class="market_price">¥&nbsp;'+data[i].spu_market_price+'</del></p>';
                        html += '</a></li>';
                    }
                    if(currentPage==1){
                        $('.clearFix').html(html);
                    }else{
                        $(".clearFix").append(html);
                    }

                    scrollLoadData({
                        container: '.clearFix',
                        currentPage: currentPage,
                        pageRows: pageRows,
                        requestData: function(currentPage, pageRows, callbacks) {
                            // currentPage 当前加载的页码
                            // pageRows 每页加载多少条
                            // callback 加载完成后的回调函数
                            // callback 说明：由于加载新数据为动态加载ajax，是用户自定义方法并非组件内部ajax无法控制；保证在数据请求过程中不能再次请求发送请求，callback内包含参数的值为true/false;
                            // true 表示仍有数据，false表示没有数据
                            //ajax请求函数
                            pageLoads(++currentPage, pageRows, callbacks);
                        }

                    });
                }
            }
        }

    }).fail(function (e) {});

}

function pageLoads(page, rows, callback) {
    var _this = this;
    var url = "/elife/goods/ajax/shopSpuList/"+page+"/"+rows+"/"+parseInt($('.swiper-slide').filter('.active').attr('code'));
    var match = /((http|https):\/\/)+(\w+\.)+(\w+)[\w\/\.\-]*(jpg|gif|png|JPG|PNG|GIF|JPEG)/;
    $.ajax({
        url: url,
        type:'get',
        data: {},
        dataType: ''
    }).done(function(data) {
        if (data == null || data.code == 100) {
            var html = '<ul class="clearFix"><img src="../../elife_img/noGoods.jpg" width="100%" alt=""></ul>';
            $('.clearFix').html(html);
            $('.scroll-load-wait').hide();
        } else {
            if (data.code == 300) {
                callback(false);
                //没有更多了
            } else {
                var html = "";
                var data = data.data;
                if (data != "") {
                    for (var i = 0; i < data.length; i++) {

                        html += '<li>';
                        html += '<a href="/elife/goods/spuDetail/' + data[i].spu_id + '">';
                        if (match.test(data[i].main_image)) {
                            html += '<img src="' + data[i].main_image + '" width="100%" alt="">';
                        } else {
                            html += '<img src="../' + data[i].main_image + '" width="100%" alt="">';
                        }
                        html += '<h4>' + data[i].spu_name + '</h4>';
                        html += '<p class="price">¥&nbsp;' + data[i].spu_plat_price + '&nbsp;<del class="market_price">¥&nbsp;'+data[i].spu_market_price+'</del></p>';
                        html += '</a></li>';
                    }
                    $(".clearFix").append(html);
                }
                callback(true);
            }
        }
    }).fail(function(e){});
}

