var indexCtrl = {
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

        var swiperTopB = new Swiper('.i-banner', {
            pagination: '.i-banner .swiper-pagination',
            paginationClickable: true,
            observer: true
        });
        var swiperTabList = new Swiper('.i-tabList .tab', {
            pagination: '.i-tabList .tab .swiper-pagination',  //swiper-pagination 原点
            paginationClickable: true,    //点击原点时切换
            slidesPerView: 4,
            spaceBetween: 0,
            observer:true,
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
                    slidesPerView: 4,
                    spaceBetween: 0
                },
                320: {
                    slidesPerView: 4,
                    spaceBetween: 0
                }
            }
        });

        var swiperSH = new Swiper('.sh-swiper', {
            slidesPerView: 2,
            //paginationClickable: true,
            spaceBetween: 10,
            observer: true//,//,//修改swiper自己或子元素时，自动初始化swiper
            //freeMode: true
        });

    }
};
indexCtrl.init();


//综合 ， 销量 ，价格 ， 人气
/*var curpage = $("#pageNumber").val();
var gcId = $("#gcId").val();
var gcType = $("#gcType").val();
var key = GetQueryString('key');
var order = GetQueryString('order');
if(curpage == "" || curpage == "0" || curpage==null || typeof (curpage) == "undefined"){
    curpage = 1;
}
$(function () {
    $(".page-warp").click(function () {
        $(this).find(".pagew-size").toggle();
    });
});

$("select[name=page_list]").change(function () {
    var cpage = $(this).val();

    //得到排序项
    var orderBy = $(".cur").attr('order');

    var url = "/shop/index/"+cpage+"/10/"+gcId+"/0/"+orderBy+"/"+gcType;

    location.href = url;
});


$('.keyorder').click(function () {

    var order = $(this).attr('order');
    $(this).addClass("active").siblings().removeClass("active");


    /!*var keyword = encodeURIComponent($('#keyword').val());


     if(keyword==''){
     keyword = 0;
     }*!/

    keyword = 0;
    //当点击某个排序规则时，显示的是该规则的第一页
    var url = "/shop/index/1/10/"+gcId+"/"+keyword+"/"+order+"/"+gcType;

    location.href = url;
});*/


