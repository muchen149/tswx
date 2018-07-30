$(function () {
    $('#keyword').keydown(function (event) {
        if (event.keyCode == 13) {
            var keyword = encodeURIComponent($('#keyword').val());
            var url = "/shop/goods/spuList/1/10/0/"+keyword;
            location.href = url;

        }
    });
    $('#keyword').blur(function () {

        var keyword = encodeURIComponent($('#keyword').val());

        var url = "/shop/goods/spuList/1/10/0/"+keyword;
        location.href = url;

    });
     $('#search-btn').click(function(){
         var keyword = encodeURIComponent($('#keyword').val());

         var url = "/shop/goods/spuList/1/10/0/"+keyword;
         location.href = url;


     });
});
