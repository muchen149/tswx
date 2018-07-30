/**
 * Created by Administrator on 2018/7/10 0010.
 */
$(function () {
    // 图片ajax上传
    /*$('input[type="file"]').bind('change', function(){
        var id = $(this).attr('id');
        ajaxFileUpload(id);
    });*/
    $('.upload-front').bind('change', function(){
        var id = $(this).attr('id');
        if(id == 'uploadPic_front'){
            ajaxFileUpload(id);
        }
    });
    $('.upload-back').bind('change', function(){
        var id = $(this).attr('id');
        if(id == 'uploadPic_back'){
            ajaxFileUpload(id);
        }
    });
    //点击图片进行删除图片
    $(".front-img").on("click",function(){
        var self = this;
        showConfirmDialog('您确认要删除这张图片吗？',function(){
            hideConfirmDialog();
            delFrontImg(self);
        });
    });
    $(".back-img").on("click",function(){
        var self = this;
        showConfirmDialog('您确认要删除这张图片吗？',function(){
            hideConfirmDialog();
            delBackImg(self);
        });
    });
    function delFrontImg(self){
        //得到图片
        var data_front_img = $(self).attr("data-front-img");
        //从服务器中删除图片
        $.ajax({
            url: delImgUrl,
            type: 'post',
            data: {data_img:data_front_img},
            dataType: 'json',
            success: function (res) {
                if(res.code == 0){
                    //删除图片
                    $('div[data-front-img="'+data_front_img+'"]').remove();
                    $('.front_img_box').show();
                }else{
                    message(res.message);
                }
            }
        });
    }
    function delBackImg(self){
        //得到图片
        var data_back_img = $(self).attr("data-back-img");
        //从服务器中删除图片
        $.ajax({
            url: delImgUrl,
            type: 'post',
            data: {data_img:data_back_img},
            dataType: 'json',
            success: function (res) {
                if(res.code == 0){
                    //删除图片
                    $('div[data-back-img="'+data_back_img+'"]').remove();
                    $('.back_img_box').show();
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
                message(data.message);
            } else {
                if(data.side == 'front'){
                    $("#hid_front").val(data.front_file_name);
                    var side = data.side;
                }
                if(data.side == 'back'){
                    $("#hid_back").val(data.back_file_name);
                    var side = data.side;
                }
                callback(side);
            }
            $.getScript(scriptUrl);
        },
        error : function (data, status, e) {
            message(data.responseText);
            console.log(e);
            $.getScript(scriptUrl);
        }
    });
    return false;
}
function callback(side){
    //回退之后初始化图片
    if(side == 'front'){
        var figw = $('#front-img-wrapper');
        var hid_front = $('#hid_front');
        var frontTxt = hid_front.val();
        if (frontTxt) {
            var frontImgNodes = [];
            frontImgNodes.push("<div class=\"front-img\" data-front-img=\"" + frontTxt + "\"><span><img src=\"" + frontTxt + "\" ></div>");
            figw.find('.front-img').remove();
            figw.prepend(frontImgNodes.join(''));
            if (frontTxt.length > 1) {
                $('.front_img_box').hide();
            }
        }
    }
    if(side == 'back'){
        var bigw = $('#back-img-wrapper');
        var hid_back = $('#hid_back');
        var backTxt = hid_back.val();
        if (backTxt) {
            var backImgNodes = [];
            backImgNodes.push("<div class=\"back-img\" data-back-img=\"" + backTxt + "\"><span><img src=\"" + backTxt + "\" ></div>");
            bigw.find('.back-img').remove();
            bigw.prepend(backImgNodes.join(''));
            if (backTxt.length > 1) {
                $('.back_img_box').hide();
            }
        }
    }
}