
function GetQueryString(name){
	var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
	var r = window.location.search.substr(1).match(reg);
	if (r!=null) return unescape(r[2]); return null;
}

function addcookie(name,value,expireHours){
	var cookieString=name+"="+escape(value)+"; path=/";
	//判断是否设置过期时间
	if(expireHours>0){
		var date=new Date();
		date.setTime(date.getTime+expireHours*3600*1000);
		cookieString=cookieString+"; expire="+date.toGMTString();
	}
	document.cookie=cookieString;
}

function getcookie(name){
	var strcookie=document.cookie;
	var arrcookie=strcookie.split("; ");
	for(var i=0;i<arrcookie.length;i++){
	var arr=arrcookie[i].split("=");
	if(arr[0]==name)return arr[1];
	}
	return "";
}

function delCookie(name){//删除cookie
	var exp = new Date();
	exp.setTime(exp.getTime() - 1);
	var cval=getcookie(name);
	if(cval!=null) document.cookie= name + "="+cval+"; path=/;expires="+exp.toGMTString();
}


function contains(arr, str) {
    var i = arr.length;
    while (i--) {
           if (arr[i] === str) {
           return true;
           }
    }
    return false;
}


// 两个浮点数相减
function accSub(num1,num2){
	var r1,r2,m;
	try{
		r1 = num1.toString().split('.')[1].length;
	}catch(e){
		r1 = 0;
	}
	try{
		r2=num2.toString().split(".")[1].length;
	}catch(e){
		r2=0;
	}
	m=Math.pow(10,Math.max(r1,r2));
	n=(r1>=r2)?r1:r2;
	return (Math.round(num1*m-num2*m)/m).toFixed(n);
}

function accDiv(num1,num2){
	var t1,t2,r1,r2;
	try{
		t1 = num1.toString().split('.')[1].length;
	}catch(e){
		t1 = 0;
	}
	try{
		t2=num2.toString().split(".")[1].length;
	}catch(e){
		t2=0;
	}
	r1=Number(num1.toString().replace(".",""));
	r2=Number(num2.toString().replace(".",""));
	return (r1/r2)*Math.pow(10,t2-t1);
}

function accAdd(arg1,arg2){
	var r1,r2,m;
	try{r1=arg1.toString().split(".")[1].length}catch(e){r1=0}
	try{r2=arg2.toString().split(".")[1].length}catch(e){r2=0}
	m=Math.pow(10,Math.max(r1,r2));
	return (arg1*m+arg2*m)/m
}

function accMul(arg1,arg2)
{
	var m=0,s1=arg1.toString(),s2=arg2.toString();
	try{m+=s1.split(".")[1].length}catch(e){}
	try{m+=s2.split(".")[1].length}catch(e){}
	return Number(s1.replace(".",""))*Number(s2.replace(".",""))/Math.pow(10,m)
}




function number_format(num, ext){
	if(ext < 0){
		return num;
	}
	num = Number(num);
	if(isNaN(num)){
		num = 0;
	}
	var _str = num.toString();
	var _arr = _str.split('.');
	var _int = _arr[0];
	var _flt = _arr[1];
	if(_str.indexOf('.') == -1){
		/* 找不到小数点，则添加 */
		if(ext == 0){
			return _str;
		}
		var _tmp = '';
		for(var i = 0; i < ext; i++){
			_tmp += '0';
		}
		_str = _str + '.' + _tmp;
	}else{
		if(_flt.length == ext){
			return _str;
		}
		/* 找得到小数点，则截取 */
		if(_flt.length > ext){
			_str = _str.substr(0, _str.length - (_flt.length - ext));
			if(ext == 0){
				_str = _int;
			}
		}else{
			for(var i = 0; i < ext - _flt.length; i++){
				_str += '0';
			}
		}
	}

	return _str;
}
//bottom nav 33 hao-v3 by 33h ao.com Qq 1244 986 40
$(function(){
	setTimeout(function(){
		if($("#content .container").height()<$(window).height())
		{
			$("#content .container").css("min-height",$(window).height());
		}
	},300);
	$("#bottom .nav .get_down").click(function(){
		$("#bottom .nav").animate({"bottom":"-50px"});
		$("#nav-tab").animate({"bottom":"0px"});
	});
	$("#nav-tab-btn").click(function(){
		$("#bottom .nav").animate({"bottom":"0px"});
		$("#nav-tab").animate({"bottom":"-40px"});
		
	});
	setTimeout(function(){$("#bottom .nav .get_down").click();},500);
	$("#scrollUp").click(function(t) {
		$("html, body").scrollTop(300);
		$("html, body").animate( {
			scrollTop : 0
		}, 300);
		t.preventDefault()
	});
});

/**
 * 验证提示消息
 * message_text : 消息提示的文本信息
 * time : 显示多久消失，默认2000
 * 用法 ： message('出错啦！'); message('出错啦！',3000);
 * 不影响功能的不足之处：
 *    其实左右距离浏览器还会有一点不对称，主要未考虑导航条情况
 */
function message(message_text,time) {
	$('.message-prompt').remove();
	var message = "<div class='message-prompt'>"+message_text+"</div>";
	$('body').append(message);
	var message_prompt = $('.message-prompt');
	message_prompt.css({
		"position" : "fixed",
		"padding" : "6px 8px",
		"top" : "45%",
		"max-width" : "260px",
		"z-index" : "9999",
		"display" : "none",
		"background-color" : "rgba(0,0,0,0.5)",
		"color" : "#fff",
		"letter-spacing" : "0.5px",
		"text-align" : "center"
	});
	var width = message_prompt.width();
	var height = message_prompt.height();
	message_prompt.css({
		'margin-left' : -width/2,
		'margin-top' : -height/2,
		'left' : '50%',
		'display' : 'block'
	});
	setTimeout(function () {
		message_prompt.css("display", "none");
		message_prompt.remove();
	}, time == null ? 2000 : time);
}

function loading(){
	set_height();
	$("#pop-loading").show();
	$(".__MASK_SID_DIV").show();
}

function loadSucc(){
	$("#pop-loading").hide();
	$(".__MASK_SID_DIV").hide();
}

function showConfirmDialog(message, okFn, cancelFn) {
	set_height();
	if(typeof (okFn) == 'undefined'){
		okFn = function(){ hideConfirmDialog();}
	}
	if(typeof (cancelFn) == 'undefined'){
		cancelFn = function(){ hideConfirmDialog();}
	}
	$("#pop-confirm").show();
	if(message != ''){
		$("#confirm-content").html(message);
	}
	$(".__MASK_SID_DIV").show();
	$("#confirm-ok-btn").on("click", okFn);
	$("#confirm-close-btn").on("click", cancelFn);
}

function hideConfirmDialog(){
	$("#confirm-ok-btn").unbind("click");
	$("#confirm-close-btn").unbind("click");
	$("#pop-confirm").hide();
	$(".__MASK_SID_DIV").hide();
}

function showWarnDialog(message, cancelFn) {
	set_height();
	if(typeof (cancelFn) == 'undefined'){
		cancelFn = function(){ hideWarnDialog();}
	}
	$("#pop-warn").show();
	if(message != '') {
		$("#warn-content").html(message);
	}
	$(".__MASK_SID_DIV").show();
	$("#warn-close-btn").on("click", cancelFn);
}

function hideWarnDialog(){
	$("#warn-close-btn").unbind("click");
	$("#pop-warn").hide();
	$(".__MASK_SID_DIV").hide();
}

function removeYinYing(select1,select2) {
	$(select1).click(function (e) {
		e.stopPropagation();
		$(select2).click();
	});
}

//设置整个屏幕的背景为灰色
function set_height(){
	var obj = document.getElementById("set_height");
	var h = $(document.body).height();
	//当body的高度为过低时只有一部分会变灰，因此和700比较保证整个屏幕都是灰色
	if(h < 700 )
	{
		h = 700;
	}
	obj.style.height = h + 'px';
}

var commonJs = {
	dom: function() {
		var _this = this;
		_this.nav = $('.navbar');
		_this.footer = $('.footer');
		_this.aside = $('.common-aside');
	},
	// 判断是否是手机端浏览器
	getBrowser: function() {
		var ua = navigator.userAgent.toLowerCase();
		var btypeInfo = (ua.match(/firefox|chrome|safari|opera/g) || "other")[0];
		if ((ua.match(/msie|trident/g) || [])[0]) {
			btypeInfo = "msie";
		}
		var pc = "";
		var prefix = "";
		var plat = "";
		//如果没有触摸事件 判定为PC
		var isTocuh = ("ontouchstart" in window) || (ua.indexOf("touch") !== -1) || (ua.indexOf("mobile") !== -1);
		if (isTocuh) {
			if (ua.indexOf("ipad") !== -1) {
				pc = "pad";
			} else if (ua.indexOf("mobile") !== -1) {
				pc = "mobile";
			} else if (ua.indexOf("android") !== -1) {
				pc = "androidPad";
			} else {
				pc = "pc";
			}
		} else {
			pc = "pc";
		}
		switch (btypeInfo) {
			case "chrome":
			case "safari":
			case "mobile":
				prefix = "webkit";
				break;
			case "msie":
				prefix = "ms";
				break;
			case "firefox":
				prefix = "Moz";
				break;
			case "opera":
				prefix = "O";
				break;
			default:
				prefix = "webkit";
				break
		}
		plat = (ua.indexOf("android") > 0) ? "android" : navigator.platform.toLowerCase();
		return {
			version: (ua.match(/[\s\S]+(?:rv|it|ra|ie)[\/: ]([\d.]+)/) || [])[1], //版本
			plat: plat, //系统
			type: btypeInfo, //浏览器
			pc: pc,
			prefix: prefix, //前缀
			isMobile: (pc == "pc") ? false : true //是否是移动端
		}
	},
	navScroll: function() {
		var _this = this;

		function _navScroll() {
			var scrollTop = $(window).scrollTop();
			var navH = _this.nav.height();
			// console.log(scrollTop + "=" + _this.footer.position().top);
			scrollTop >= _this.footer.position().top && _this.nav.css({
				webkitTransitionProperty: 'top',
				transitionProperty: 'top',
				WebkitTransitionDuration: '.3s',
				transitionDuration: '.3s',
				transitionTimingFunction:'linear',
				position: 'fixed',
				top: '0'
			})
		}
		_navScroll();
		$(document).bind('mousewheel', function(event, delta, deltaX, deltaY) {
			var scrollTop = $(window).scrollTop();
			var navH = _this.nav.height();
			// _navScroll();
			if (deltaX) return;

			if (scrollTop > navH) {
				if (delta > 0) { //向上
					_this.nav.css({
						webkitTransitionProperty: 'top',
						transitionProperty: 'top',
						webkitTransitionDuration: '.3s',
						transitionDuration: '.3s',
						transitionTimingFunction:'linear',
						position: 'fixed',
						top: '0',
					})
				} else {
					_this.nav.css({
						webkitTransitionProperty: 'top',
						transitionProperty: 'top',
						webkitTransitionDuration: '0',
						transitionDuration: '0',
						transitionTimingFunction:'linear',
						position: 'fixed',
						top: '-90px',
					})

					scrollTop >= _this.footer.position().top && _this.nav.css({
						webkitTransitionProperty: 'top',
						transitionProperty: 'top',
						webkitTransitionDuration: '.3s',
						transitionDuration: '.3s',
						transitionTimingFunction:'linear',
						position: 'fixed',
						top: '0'
					})
				}
			} else {
				_this.nav.css({
					webkitTransitionProperty: 'top',
					transitionProperty: 'top',
					webkitTransitionDuration: '0s',
					transitionDuration: '0s'

				})

				scrollTop <= 0 && _this.nav.css({
					webkitTransitionProperty: 'top',
					transitionProperty: 'top',
					webkitTransitionDuration: '0',
					transitionDuration: '0',
					transitionTimingFunction:'linear',
					position: 'absolute',
					top: '0',
				})

			}
		});
	},
	footerPos: function() {
		var _this = this;
		var winH = $(window).height();
		var footPosTop = _this.footer.position().top;
		var scrollTop = $(window).scrollTop();

		_this.footer.find('.footer-wrap').css({
			height: winH
		})
		if (scrollTop >= footPosTop) {
			_this.nav.css({
				top: 0
			})
		}
		$(window).bind('scroll', function() {
			var winH = $(window).height();
			var footPosTop = _this.footer.position().top;
			var scrollTop = $(window).scrollTop();

			if (scrollTop >= footPosTop) {
				_this.nav.removeClass('navUp').addClass('navDown');
			}
		});
	},
	asideFunc:function(){
		var _this = this;
		_this.aside.on('click','.endTop',function(){
			$('html,body').animate({
				scrollTop:0
			},500)
		});
	},
	bind: function() {
		var _this = this;
		_this.footerPos();
		_this.navScroll();
		_this.asideFunc();

	},
	init: function() {
		var _this = this;
		_this.dom();
		_this.bind();
		var wow = new WOW({
			boxClass: 'wow',
			animateClass: 'animated',
			offset: 200,
			mobile: true,
			live: true
		});
		wow.init();
	},

}
commonJs.init();
