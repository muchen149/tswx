$(function (){
	var headTitle = document.title;

    //渲染页面
	var tmpl = '<header class="jd-header">'+
		'<div class="jd-header-bar">'+
		'<div id="m_common_header_goback" class="jd-header-icon-back"><a href="javascript:history.back();"><span></span></a></div>'+
		'<div class="jd-header-title">'+headTitle+'</div>'+
		'<div report-eventid="MCommonHead_NavigateButton" report-eventparam="" page_name="" id="m_common_header_jdkey" class="jd-header-icon-shortcut J_ping"><a id="btn-opera"><span></span></a></div>'+
		'</div>'+
		'<ul id="m_common_header_shortcut" class="jd-header-shortcut" style="display: none">'+
		'<li id="m_common_header_shortcut_m_index"><a class="J_ping" report-eventid="MCommonHead_home" report-eventparam="" page_name="" href="/shop/index">'+
		'<span class="shortcut-home"></span> <strong>首页</strong> </a></li>'+
		'<li class="J_ping" report-eventid="MCommonHead_CategorySearch" report-eventparam="" page_name="" id="m_common_header_shortcut_category_search"><a href="/shop/goods/spuList">'+
		'<span class="shortcut-categories"></span> <strong>商品列表</strong> </a></li>'+
		'<li class="J_ping" report-eventid="MCommonHead_Cart" report-eventparam="" page_name="" id="m_common_header_shortcut_p_cart">'+
		'<a href="/cart/index" id="html5_cart">'+
		'<span class="shortcut-cart"></span> <strong>购物车</strong> </a></li>'+
		'<li id="m_common_header_shortcut_h_home"><a class="J_ping" report-eventid="MCommonHead_MYJD" report-eventparam="" page_name="" href="/personal/index">'+
		'<span class="shortcut-my-account"></span> <strong>个人中心</strong> </a></li>'+
		'</ul>'+
		'</header>';
	var html = tmpl;
	$("#header").html(html);
	$("#btn-opera").click(function (){
		$("#m_common_header_shortcut").toggle();
	});
	//当前页面
	if(headTitle == "商品列表"){
		$("#m_common_header_shortcut_category_search").addClass("current");
	}else if(headTitle == "购物车"){
		$("#m_common_header_shortcut_p_cart").addClass("current");
	}else if(headTitle == "个人中心"){
		$("#m_common_header_shortcut_h_home").addClass("current");
	}
});