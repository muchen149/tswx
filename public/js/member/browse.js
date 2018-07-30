$(function(){
    var curpage = $("#pageNumber").val();
    if(curpage == "" || curpage == "0" || curpage==null || typeof (curpage) == "undefined"){
        curpage = 1;
    }


    $("select[name=page_list]").change(function () {

        var cpage = $(this).val();

        var url = "/personal/browse/list/2/"+cpage;

        location.href = url;
    });

    $('.pre-page').click(function () {//上一页
        if (curpage <= 1) {
            return false;
        }
        var cpage = parseInt($("select[name='page_list']").val())-1;

        var url = "/personal/browse/list/2/"+cpage;

        location.href = url;
    });

    $('.next-page').click(function () {//下一页

        var page_total = $("#page_total").val();
        var cpage = parseInt($("select[name='page_list']").val())+1;
        if (page_total < cpage) {
            return false;
        }


        var url = "/personal/browse/list/2/"+cpage;

        location.href = url;
    });
});