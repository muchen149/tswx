$(function(){
	$(".cart-list-del").on("click", function(){
		var goods_id = $(this).attr('goods_id');
		sureDelFavorites(goods_id);
	});


	function sureDelFavorites(goods_id){
		if(confirm("确定要删除收藏的商品吗？")){
			delFavorites(goods_id);

		}
	}
	//删除收藏
	function delFavorites(goods_id){

		$.ajax({
			type:'post',
			url:"/personal/collect/cancel",
			data:{subject_id:goods_id,'subject_type':2},
			dataType:'json',
			success:function(result){
				if(result['code']==0){
					var len = $('#productsList').children('li').length;
					//删除最后一个时，把页脚也删掉
					if(len == 1){
						$("li[goods_id='"+goods_id+"']").slideUp(300, function () {
							$(this).remove();
							$(".pagination").remove();
						});

					}else{
						$("li[goods_id='"+goods_id+"']").slideUp(300, function () {
							$(this).remove();
						});
					}


					//$("li[goods_id='"+goods_id+"']").remove();
					return true;
				}else if(result['code']== 102){
					message("您还没有登录，请进行登录！");
					location.href = "/personal/userLoginView";
					return false;
				}else{
					message("删除失败，请稍后再试！");
					return false;
				}
			}
		});
		return true;
	}


	var curpage = $("#pageNumber").val();
	if(curpage == "" || curpage == "0" || curpage==null || typeof (curpage) == "undefined"){
		curpage = 1;
	}


	$("select[name=page_list]").change(function () {

		var cpage = $(this).val();

		var url = "/personal/collect/list/2/"+cpage;

		location.href = url;
	});

	$('.pre-page').click(function () {//上一页
		if (curpage <= 1) {
			return false;
		}
		var cpage = parseInt($("select[name='page_list']").val())-1;

		var url = "/personal/collect/list/2/"+cpage;

		location.href = url;
	});

	$('.next-page').click(function () {//下一页

		var page_total = $("#page_total").val();
		var cpage = parseInt($("select[name='page_list']").val())+1;
		if (page_total < cpage) {
			return false;
		}

		var url = "/personal/collect/list/2/"+cpage;

		location.href = url;
	});


});