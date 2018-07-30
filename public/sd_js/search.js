/*var searchCtrl = {
    dom: function() {
        var _this = this;
        _this.sHistory = $('.search-history');
        _this.sRecommend = $('.search-recommend');
    },
    bind: function() {
        var _this = this;
        _this.sHistory.on('click', '.btn-del', function() {
            _this.sHistory.find('.list li').remove();
            _this.sHistory.find('.tip').show();
        });
        _this.sRecommend.on('click', '.btn-ctrl', function() {
        	var $this = $(this);
        	var $list = _this.sRecommend.find('.list');
        	var $tip = _this.sRecommend.find('.tip');
        	if($this.hasClass('hide')){
                debugger;
        		$list.show();
        		$tip.hide();
        		$this.removeClass('hide').text('隐藏');

        	}else{
                debugger;
        		$list.hide();
        		$tip.show();
        		$this.addClass('hide').text('显示');

        	}
        	
        });
    },
    init: function() {
        debugger;
        var _this = this;
        _this.dom();
        _this.bind();
    }
};*/

var searchCtrl = {
    dom: function() {
        var _this = this;
        _this.sHistory = $('.search-history');
        _this.sRecommend = $('.search-recommend');
    },
    bind: function() {
        var _this = this;
        var $list = _this.sRecommend.find('.list');
        var $tip = _this.sRecommend.find('.tip');
        $list.show();
        $tip.hide();

        _this.sHistory.on('click', '.btn-del', function() {
            _this.sHistory.find('.list li').remove();
            _this.sHistory.find('.tip').show();
        });

        _this.sRecommend.on('click', '.btn-ctrl', function() {
            var $this = $(this);
            var $list = _this.sRecommend.find('.list');
            var $tip = _this.sRecommend.find('.tip');
            if(!$this.hasClass('n_btn')){
                $list.hide();
                $tip.show();
                $this.addClass('n_btn').text('显示');

            }else{
                $list.show();
                $tip.hide();
                $this.removeClass('n_btn').text('隐藏');

            }

        });
    },
    init: function() {
        var _this = this;
        _this.dom();
        _this.bind();
    }
};
searchCtrl.init();

function search(){
    var url='/1/10/0/'+$('#searchKey')[0].value;
    $('#searchFrm')[0].action+=url;
    $('#searchFrm').submit();

}
/*$('.navBtn').click(function () {
 $(this).addClass("active").siblings().removeClass("active");
 });*/
$(function () {
    var isPageHide = false;
    window.addEventListener('pageshow', function () {
        if (isPageHide) {
            window.location.reload();
        }
    });
    window.addEventListener('pagehide', function () {
        isPageHide = true;
    });
});
//表单提交
$(document).ready(function(){

    $("#searchKey").val("").focus(); // 将name=test的文本框清空并获得焦点，以便重新输入
    $(":submit[id=search]").click(function(check){
        var val = $("#searchKey").val();
        if(val==""){
            $("#searchKey").focus();
            check.preventDefault();//此处阻止提交表单
            return false;
        }
    });
});
var searchArr;
//定义一个search的，判断浏览器有无数据存储（搜索历史）
if(localStorage.search){
    //如果有，转换成 数组的形式存放到searchArr的数组里（localStorage以字符串的形式存储，所以要把它转换成数组的形式）
    searchArr= localStorage.search.split(",")
}else{
    //如果没有，则定义searchArr为一个空的数组
    searchArr = [];
}
//把存储的数据显示出来作为搜索历史
MapSearchArr();


$("#search").on("click", function(){
    var val = $("#searchKey").val();
    if(val == ""){
        return false;
    }else{
        //点击搜索按钮时，去重
        KillRepeat(val);
        //去重后把数组存储到浏览器localStorage
        localStorage.search = searchArr;
        //然后再把搜索内容显示出来
        MapSearchArr();
        search();
    }

});
//点击历史搜索内容出现在搜索框
$('.skey').click(function () {
    var v=$(this).text();
    //console.log($(this));
    $('#searchKey')[0].value=v;
    search();
    //$('#search').attr("disabled","disabled");

});

function MapSearchArr(){
    var tmpHtml = "";
    for (var i=0;i<searchArr.length;i++){
        tmpHtml += "<li class='skey'>" + searchArr[i] + "</li>&nbsp;"
    }
    $("#list").html(tmpHtml);
}
//去重
function KillRepeat(val){
    var kill = 0;
    for (var i=0;i<searchArr.length;i++){
        if(val===searchArr[i]){
            kill ++;
        }
    }
    if(kill<1){
        searchArr.push(val);
    }
}
//localStorage所用对应的值
function deleteItem(){
    localStorage.clear();
}