function showGoodsList(gc_id_3){
    currentPage=1;
    pageRows=10;
    var url = "/shop/goods/ajax/shopSpuList/"+1+"/"+pageRows+"/"+gc_id_3;
    var html="";
    $.ajax({
        url: url,
        type:'get',
        data: {},
        dataType: ''
    }).done(function(data) {
        //debugger;
        if (data == null || data.code != "0") {
            console.log(data.message);
        } else {
            if (data.message.length == 0) {
                var html = '<ul class="clearFix"><img src="../sd_img/noGoods.jpg" width="100%" alt=""></ul>';
                $('#shopGoodList').html(html);
            } else if (data.data.goodsSpuList.length == 0) {
                var html = '<ul class="clearFix"><img src="../sd_img/noGoods.jpg" width="100%" alt=""></ul>';
                $('#shopGoodList').html(html);
                $(".noData").show();
            } else {
                $(".noData").hide();
                var html = '<ul class="clearFix">';
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
                    html+='</ul>';

                    $('#shopGoodList').html(html);
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
                    callback(true);
                }

            }
        }

    }).fail(function(e){});

}

function showRmdList(cls){

    var url = "/shop/goods/ajax/rmdSpuList/"+cls;
    var html="";
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
            } else if (data.data.rmdSpuList.length == 0) {
                var html = '<li ><img src="../sd_img/noRmd.jpg" width="100%" alt="">';
                $('#rmdGoods').html(html);
            } else {
                var html = '';
                var data = data.data.rmdSpuList;
                if (data != "") {
                    for (var i = 0; i < data.length; i++) {
                        html += '<li style="width: 33%;">';
                        html += '<a href="/shop/goods/spuDetail/' + data[i].spu_id + '">  <span class="img"> ';
                        if (match.test(data[i].main_image)) {
                            html += '<img src="' + data[i].main_image + '" width="100%" alt=""><i class="tag">推荐</i></span>';
                        } else {
                            html += '<img src="../' + data[i].main_image + '" width="100%" alt=""><i class="tag">推荐</i></span>';
                        }
                        html += '<h4 class="rmdGood">' + data[i].spu_name + '</h4>';
                        html += '<p class="price">¥&nbsp;' + data[i].spu_price + '</p></a></li>';
                    }
                    $('#rmdGoods').html(html);
                }
            }
        }

    }).fail(function(e){});

}
function bindClick(){
    $('.navBtn').click(function () {
        $(this).addClass("active").siblings().removeClass("active");
    });
    $('.hotCls').click(function () {
        $(this).addClass("active").siblings().removeClass("active");
        var cls=$(this).attr('code');
        debugger;
        if($('.shopTabPage.active').attr('code')!=0){
            localStorage.setItem("2class",$('.shopTabPage.active').attr('code'));
        }
        localStorage.setItem("3class",cls);
        showGoodsList(cls);

    });
    $("#sd_search").focus(function(){
        $("input").css("background-color","#FFFFCC");
    });
}
bindClick();

var shopCtrl = {
    dom: function() {
        var _this = this;
    },
    bind: function() {
        var _this = this;
    },
    init: function() {
        var _this = this;
        _this.dom();
        _this.bind();


        var swiperShopB = new Swiper('.shop-banner', {
            pagination: '.shop-banner .swiper-pagination',
            paginationClickable: true,
            //loop: true,
            observer: true,//修改swiper自己或子元素时，自动初始化swiper
            observeParents: true//修改swiper的父元素时，自动初始化swiper
        });
        var swiperShopTab = new Swiper('.shop-tab', {
            pagination: '.i-tabList .tab .swiper-pagination',
            paginationClickable: true,
            observer: true,
            slidesPerView: 4,
            spaceBetween: 0,
            breakpoints: {
                1024: {
                    slidesPerView: 4,
                    spaceBetween: 0
                },
                768: {
                    slidesPerView: 4,
                    spaceBetween: 0
                },
                640: {
                    slidesPerView: 5,
                    spaceBetween:0
                },
                320: {
                    slidesPerView: 4,
                    spaceBetween: 0
                }
            }
        });
        var swiperListTab = new Swiper('.shop-typeList .tab', {
            slidesPerView: 4,
            paginationClickable: true,
            spaceBetween: 30,
            observer: true,//,//修改swiper自己或子元素时，自动初始化swiper
            freeMode: true
        });


        $('.navBtn').click(function () {
            $(this).addClass("active").siblings().removeClass("active");
        });
        $('.hotCls').click(function () {
            $(this).addClass("active").siblings().removeClass("active");
            var cls=$(this).attr('code');
            showGoodsList(cls);

        });
        $("#sd_search").focus(function(){
            $("input").css("background-color","#FFFFCC");
        });

    }
};
shopCtrl.init();


