$(function (){

	//var memberHtml = '<li class=""><a href="/personal/userLoginView" class="J_ping" rel="nofollow">登录</a></li>'+
     //   '<li><a href="/personal/userRegister" class="J_ping" rel="nofollow">注册</a></li>'+
     //   '<li style="border-left:none"><a onclick="window.scrollTo(0,0);" href="javascript:void(0)" class="J_ping">回到顶部</a></li>';

    var memberHtml = '';
    var tmpl = '<div class="jd-1px-line-up"></div>'+
    '<ul class="jd-footer-links" style="margin-bottom:6px;">'+memberHtml+'</ul>'+
    '<div class="jd-1px-line-up"></div>'+

    '<div class="jd-1px-line-up"></div>'

	 var tmpl2 = '<div id="bottom">'
		+'<div style=" height:40px;">'
  +'<div id="nav-tab" style="bottom:-40px;">'
            +'<div id="nav-tab-btn"><i class="fa fa-chevron-down"></i></div>'
            +'<div class="clearfix tab-line nav">'
      //       <i class="fa fa-home"></i> <span class="class_start glyphicon glyphicon-home" ></span> <span class="class_plist glyphicon glyphicon-th-list" ></span>  <span class="class_cart glyphicon glyphicon-shopping-cart" ></span>  <span class="class_user glyphicon glyphicon-user" ></span>
      +'<div class="tab-line-item" style="width:25%;" ><a href="/shop/index" style="text-decoration:none"><i class="fa home-class"></i><img id="home-id" class="img-width" src="/img/tab_index_pre.png"><br>首页</a></div>'
      +'<div class="tab-line-item tab-categroy" style="width:25%;" ><a href="/shop/goods/spuList" style="text-decoration:none"><i class="fa good-list-class"></i><img id="good-list-id" class="img-width" src="/img/tab_lei_pre.png"><br>商品列表</a></div>'
      +'<div class="tab-line-item" style="width:25%;position: relative;" ><a href="/cart/index"><i class="fa cart-class" style="text-decoration:none"></i><img id="cart-id" class="img-width" src="/img/tab_car_pre.png"><br>购物车</a></div>'
      +'<div class="tab-line-item" style="width:25%;" ><a href="/personal/index"><i class="fa user-class" style="text-decoration:none"></i><img id="user-id" class="img-width" src="/img/tab_user_pre.png"><br>个人中心</a></div>'
    +'</div>'
   +'</div>'
+'</div>'
		+'<div style="z-index: 10000; border-radius: 3px; position: fixed; background: none repeat scroll 0% 0% rgb(255, 255, 255); display: none;" id="myAlert" class="modal hide fade">'
  +'<div style="text-align: center;padding: 15px 0 0;" class="title"></div>'
  +'<div style="min-height: 40px;padding: 15px;" class="modal-body"></div>'
  +'<div style="padding:3px;height: 35px;line-height: 35px;" class="alert-footer">'
  +'<a style="padding-top: 4px;border-top: 1px solid #ddd;display: block;float: left;width: 50%;text-align: center;border-right: 1px solid #ddd;margin-right: -1px;" class="confirm" href="javascript:;">Save changes</a><a aria-hidden="true" data-dismiss="modal" class="cancel" style="padding-top: 4px;border-top: 1px solid #ddd;display: block;float: left;width: 50%;text-align: center;" href="javascript:;">关闭</a></div>'
+'</div>'
		+'<div style="display:none;" class="tips"><i class="fa fa-info-circle fa-lg"></i><span style="margin-left:5px" class="tips_text"></span></div>'
		+'<div class="bgbg" id="bgbg" style="display: none;"></div>'
        +'</div>'
	+'</div>';
	var render = template.compile(tmpl);
	var html = render();
	$("#footer").html(html+tmpl2);

	var headTitle = document.title;
	//当前页面
	if(headTitle.indexOf("商城") != -1){
		$(".home-class").parent().addClass("current");
        $("#home-id").attr('src','/img/tab_index_set.png');

    }else if(headTitle == "商品列表"){
		$(".good-list-class").parent().addClass("current");
        $("#good-list-id").attr('src','/img/tab_lei_set.png');

    }else if(headTitle == "购物车"){
		$(".cart-class").parent().addClass("current");
        $("#cart-id").attr('src','/img/tab_car_set.png');

    }else if(headTitle == "个人中心"){
		$(".user-class").parent().addClass("current");
        $("#user-id").attr('src','/img/tab_user_set.png');
	}
});
