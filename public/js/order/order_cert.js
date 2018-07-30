
$(document).ready(function () {

    $('input[type="file"]').bind('change', function(){
        var id = $(this).attr('id');
        var order_id = $(this).attr('order_id');

        ajaxFileUpload(id, order_id);
    });

    //点击图片进行删除图片
    $(".append").on("click",function(){
        var self = this;
        if(confirm('您确认要删除这张图片吗？')){
            delImg(self);
        }

    });

    function delImg(self){
        //得到图片
        var data_img = $(self).attr("data-img");
        var order_id = $('#plat_order_id').val();

        //从服务器中删除图片
        $.ajax({
            url: delImgUrl,
            type: 'post',
            data: {data_img:data_img,order_id:order_id},
            dataType: 'json',
            success: function (res) {
                if(res.code == 0){
                    //删除图片
                    $('.append').remove();
                    $('.upload-btn-box').show();
                    message('删除成功！');

                }else{
                    message(res.message);
                }
            }
        });

    }


});



// 图片上传ajax
function ajaxFileUpload(id, order_id) {
    $.ajaxFileUpload({
        url : uploadImgUrl,
        secureuri : false,
        fileElementId : id,
        dataType : 'json',
        data : {'order_id':order_id},
        success : function (data, status) {
            console.log(data);
            if (typeof(data.error) != 'undefined') {
                message(data.error);
            } else {
                //把图片显示出来
                var igw = $('#img-wrapper');
                igw.prepend("<div class=\"append\" data-img=\"" + data.file_name + "\"  order_id=\"" + order_id + "\"> <img src=\"" + data.file_name + "\"   width=\"60px\"></div>");

                $('.upload-btn-box').hide();
                message('图片已上传成功，请耐心等待系统审核！');
                $.getScript(scriptUrl);

            }
        },
        error : function (data, status, e) {
            message(e);
        }
    });
    return false;
}


