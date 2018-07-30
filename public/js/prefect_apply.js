$(function () {
    // 图片ajax上传
    $('input[type="file"]').bind('change', function(){
        var id = $(this).attr('id');
        ajaxFileUpload(id);
    });
    //点击图片进行删除图片
    $(".img-item").on("click",function(){
        var self = this;
        showConfirmDialog('您确认要删除这张图片吗？',function(){
            hideConfirmDialog();
            delImg(self);
        });
    });
    function delImg(self){
        //得到图片
        var data_img = $(self).attr("data-img");
        $uploadPic = $("#hidtxt_uploadPic").val();
        var pics = $uploadPic.split(",");
        pics.splice($.inArray(data_img,pics),1);

        var new_uploadPic = pics.join(',');
        //设置新的图片路径字符串
        $("#hidtxt_uploadPic").val(new_uploadPic);

        //从服务器中删除图片
        $.ajax({
            url: delImgUrl,
            type: 'post',
            data: {data_img:data_img},
            dataType: 'json',
            success: function (res) {
                if(res.code == 0){
                    //删除图片
                    $('div[data-img="'+data_img+'"]').remove();
                    $('.upload-btn-box').show();
                }else{
                    message(res.message);
                }
            }
        });

    }
});
// 图片上传ajax
function ajaxFileUpload(id) {
    $.ajaxFileUpload({
        url : uploadImgUrl,
        secureuri : false,
        fileElementId : id,
        dataType : 'json',
        success : function (data, status) {
            if (typeof(data.message) != 'undefined') {
                console.log(data.message);
                message(data.message);
            } else {
                console.log(data);
                var pic_val = $("#hidtxt_uploadPic").val();
                var pic_div = $("#hidtxt_uploadPic");
                if(pic_val == ""){
                    pic_div.val(data.file_name);
                }else{
                    pic_div.val(pic_val + ',' + data.file_name);
                }
                callback();
                $.getScript(scriptUrl);
            }
        },
        error : function (data, status, e) {
            console.log(e);
            message(e);
        }
    });
    return false;
}
function callback(){

    //回退之后初始化图片
    var igw = $('#img-wrapper');
    var pic = $('#hidtxt_uploadPic');
    var picsTxt = pic.val();
    if (picsTxt) {
        var pics = picsTxt.split(",");
        var imgNodes = [];
        for (var i = 0; i < pics.length; i++) {
            imgNodes.push("<div class=\"img-item\" data-img=\"" + pics[i] + "\"><span><img src=\"" + pics[i] + "\" ></div>");
        }
        igw.find('.img-item').remove();
        igw.prepend(imgNodes.join(''));
        if (pics.length > 1) {
            $('.upload-btn-box').hide();
        }
    }
}
