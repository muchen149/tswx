function GetQueryString(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
    var r = window.location.search.substr(1).match(reg);
    if (r != null) return unescape(r[2]);
    return null;
}

function addcookie(name, value, expireHours) {
    var cookieString = name + "=" + escape(value) + "; path=/";
    //判断是否设置过期时间
    if (expireHours > 0) {
        var date = new Date();
        date.setTime(date.getTime + expireHours * 3600 * 1000);
        cookieString = cookieString + "; expire=" + date.toGMTString();
    }
    document.cookie = cookieString;
}

function getcookie(name) {
    var strcookie = document.cookie;
    var arrcookie = strcookie.split("; ");
    for (var i = 0; i < arrcookie.length; i++) {
        var arr = arrcookie[i].split("=");
        if (arr[0] == name)return arr[1];
    }
    return "";
}

function delCookie(name) {//删除cookie
    var exp = new Date();
    exp.setTime(exp.getTime() - 1);
    var cval = getcookie(name);
    if (cval != null) document.cookie = name + "=" + cval + "; path=/;expires=" + exp.toGMTString();
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
function accSub(num1, num2) {
    var r1, r2, m;
    try {
        r1 = num1.toString().split('.')[1].length;
    } catch (e) {
        r1 = 0;
    }
    try {
        r2 = num2.toString().split(".")[1].length;
    } catch (e) {
        r2 = 0;
    }
    m = Math.pow(10, Math.max(r1, r2));
    n = (r1 >= r2) ? r1 : r2;
    return (Math.round(num1 * m - num2 * m) / m).toFixed(n);
}

function accDiv(num1, num2) {
    var t1, t2, r1, r2;
    try {
        t1 = num1.toString().split('.')[1].length;
    } catch (e) {
        t1 = 0;
    }
    try {
        t2 = num2.toString().split(".")[1].length;
    } catch (e) {
        t2 = 0;
    }
    r1 = Number(num1.toString().replace(".", ""));
    r2 = Number(num2.toString().replace(".", ""));
    return (r1 / r2) * Math.pow(10, t2 - t1);
}

function accAdd(arg1, arg2) {
    var r1, r2, m;
    try {
        r1 = arg1.toString().split(".")[1].length
    } catch (e) {
        r1 = 0
    }
    try {
        r2 = arg2.toString().split(".")[1].length
    } catch (e) {
        r2 = 0
    }
    m = Math.pow(10, Math.max(r1, r2));
    return (arg1 * m + arg2 * m) / m
}

function accMul(arg1, arg2) {
    var m = 0, s1 = arg1.toString(), s2 = arg2.toString();
    try {
        m += s1.split(".")[1].length
    } catch (e) {
    }
    try {
        m += s2.split(".")[1].length
    } catch (e) {
    }
    return Number(s1.replace(".", "")) * Number(s2.replace(".", "")) / Math.pow(10, m)
}


function number_format(num, ext) {
    if (ext < 0) {
        return num;
    }
    num = Number(num);
    if (isNaN(num)) {
        num = 0;
    }
    var _str = num.toString();
    var _arr = _str.split('.');
    var _int = _arr[0];
    var _flt = _arr[1];
    if (_str.indexOf('.') == -1) {
        /* 找不到小数点，则添加 */
        if (ext == 0) {
            return _str;
        }
        var _tmp = '';
        for (var i = 0; i < ext; i++) {
            _tmp += '0';
        }
        _str = _str + '.' + _tmp;
    } else {
        if (_flt.length == ext) {
            return _str;
        }
        /* 找得到小数点，则截取 */
        if (_flt.length > ext) {
            _str = _str.substr(0, _str.length - (_flt.length - ext));
            if (ext == 0) {
                _str = _int;
            }
        } else {
            for (var i = 0; i < ext - _flt.length; i++) {
                _str += '0';
            }
        }
    }

    return _str;
}
//bottom nav 33 hao-v3 by 33h ao.com Qq 1244 986 40
$(function () {
    setTimeout(function () {
        if ($("#content .container").height() < $(window).height()) {
            $("#content .container").css("min-height", $(window).height());
        }
    }, 300);
    $("#bottom .nav .get_down").click(function () {
        $("#bottom .nav").animate({"bottom": "-50px"});
        $("#nav-tab").animate({"bottom": "0px"});
    });
    $("#nav-tab-btn").click(function () {
        $("#bottom .nav").animate({"bottom": "0px"});
        $("#nav-tab").animate({"bottom": "-40px"});

    });
    setTimeout(function () {
        $("#bottom .nav .get_down").click();
    }, 500);
    $("#scrollUp").click(function (t) {
        $("html, body").scrollTop(300);
        $("html, body").animate({
            scrollTop: 0
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
function message(message_text, time) {
    $('.message-prompt').remove();
    var message = "<div class='message-prompt'>" + message_text + "</div>";
    $('body').append(message);
    var message_prompt = $('.message-prompt');
    message_prompt.css({
        "position": "fixed",
        "padding": "6px 8px",
        "top": "45%",
        "max-width": "260px",
        "z-index": "9999",
        "display": "none",
        "background-color": "rgba(0,0,0,0.5)",
        "color": "#fff",
        "letter-spacing": "0.5px",
        "text-align": "center"
    });
    var width = message_prompt.width();
    var height = message_prompt.height();
    message_prompt.css({
        'margin-left': -width / 2,
        'margin-top': -height / 2,
        'left': '50%',
        'display': 'block'
    });
    setTimeout(function () {
        message_prompt.css("display", "none");
        message_prompt.remove();
    }, time == null ? 2000 : time);
}

function loading() {
    set_height();
    $("#pop-loading").show();
    $(".__MASK_SID_DIV").show();
}

function loadSucc() {
    $("#pop-loading").hide();
    $(".__MASK_SID_DIV").hide();
}

function showConfirmDialog(message, okFn, cancelFn) {
    set_height();
    if (typeof (okFn) == 'undefined') {
        okFn = function () {
            hideConfirmDialog();
        }
    }
    if (typeof (cancelFn) == 'undefined') {
        cancelFn = function () {
            hideConfirmDialog();
        }
    }
    $("#pop-confirm").show();
    if (message != '') {
        $("#confirm-content").html(message);
    }
    $(".__MASK_SID_DIV").show();
    $("#confirm-ok-btn").on("click", okFn);
    $("#confirm-close-btn").on("click", cancelFn);
}

function hideConfirmDialog() {
    $("#confirm-ok-btn").unbind("click");
    $("#confirm-close-btn").unbind("click");
    $("#pop-confirm").hide();
    $(".__MASK_SID_DIV").hide();
}

function showWarnDialog(message, cancelFn) {
    set_height();
    if (typeof (cancelFn) == 'undefined') {
        cancelFn = function () {
            hideWarnDialog();
        }
    }
    $("#pop-warn").show();
    if (message != '') {
        $("#warn-content").html(message);
    }
    $(".__MASK_SID_DIV").show();
    $("#warn-close-btn").on("click", cancelFn);
}

function hideWarnDialog() {
    $("#warn-close-btn").unbind("click");
    $("#pop-warn").hide();
    $(".__MASK_SID_DIV").hide();
}

function removeYinYing(select1, select2) {
    $(select1).click(function (e) {
        e.stopPropagation();
        $(select2).click();
    });
}

//设置整个屏幕的背景为灰色
function set_height() {
    var obj = document.getElementById("set_height");
    var h = $(document.body).height();
    //当body的高度为过低时只有一部分会变灰，因此和700比较保证整个屏幕都是灰色
    if (h < 700) {
        h = 700;
    }
    obj.style.height = h + 'px';
}