/**
 * Created by Administrator on 2017/4/12.
 */
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


function accMul(arg1,arg2)
{
    var m=0,s1=arg1.toString(),s2=arg2.toString();
    try{m+=s1.split(".")[1].length}catch(e){}
    try{m+=s2.split(".")[1].length}catch(e){}
    return Number(s1.replace(".",""))*Number(s2.replace(".",""))/Math.pow(10,m)
}

