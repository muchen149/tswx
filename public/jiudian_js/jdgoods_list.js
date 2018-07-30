
var curpage = $("#pageNumber").val();
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

    var url = "/jd/goods/spuList/"+cpage+"/10/"+gcId+"/0/"+orderBy+"/"+gcType;

    location.href = url;
});


$('.keyorder').click(function () {

    var order = $(this).attr('order');
    $(this).addClass("active").siblings().removeClass("active");


    /*var keyword = encodeURIComponent($('#keyword').val());


     if(keyword==''){
     keyword = 0;
     }*/

    keyword = 0;
 //当点击某个排序规则时，显示的是该规则的第一页
    var url = "/jd/goods/spuList/1/10/"+gcId+"/"+keyword+"/"+order+"/"+gcType;

    location.href = url;
});

$('.pre-page').click(function () {//上一页

    if (curpage <= 1) {
        return false;
    }
    var cpage = parseInt($("select[name='page_list']").val())-1;

    //得到排序项
    var orderBy = $(".cur").attr('order');

    /*var keyword = encodeURIComponent($('#keyword').val());

    if(keyword==''){
        keyword = 0;
    }*/
    keyword = 0;

    var url = "/jd/goods/spuList/"+cpage+"/10/"+gcId+"/"+keyword+"/"+orderBy+"/"+gcType;

    location.href = url;
});

$('.next-page').click(function () {//下一页

    /*var keyword = encodeURIComponent($('#keyword').val());

    if(keyword==''){
        keyword = 0;
    }*/
    keyword = 0;


    var page_total = $("#page_total").val();
    var cpage = parseInt($("select[name='page_list']").val())+1;
    if (page_total < cpage) {
        return false;
    }

    //得到排序项
    var orderBy = $(".cur").attr('order');
    var url = "/jd/goods/spuList/"+cpage+"/10/"+gcId+"/"+keyword+"/"+orderBy+"/"+gcType;

    location.href = url;
});
$('#keyword').keydown(function (event) {
    if (event.keyCode == 13) {
        var keyword = encodeURIComponent($('#keyword').val());
        var url = "/jd/goods/spuList/1/10/0/"+keyword;
        location.href = url;
    }
});
$('.search-btn1').click(function () {
    var keyword = encodeURIComponent($('#keyword').val());
    if(keyword==''){
        var url = "/jd/goods/spuList/1/10/"+gcId;

        location.href = url;
    }else{
        //得到排序项
        var orderBy = $(".cur").attr('order');

        var url = "/jd/goods/spuList/1/10/"+gcId+"/"+keyword+"/"+orderBy+"/"+gcType;

        location.href = url;

    }


});