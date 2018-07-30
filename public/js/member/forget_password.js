var InterValObj; //timer变量，控制时间
var count = 120; //间隔函数，1秒执行
var curCount;//当前剩余秒数
var reg = /^(0|86|17951)?(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/;
function show_dialog(){
    if ($('#username').val() == '' || !reg.test($('#username').val())) {
        return '手机号不能为空';
    }
    send_auth_code();

}
function register() {
    var sub = true;
    var username = $('#username').val();
    var vcode = $('#vcode').val();
    var userpwd = $('#userpwd').val();
    var userpwd_len = userpwd.length;
    if ($('#username').val() == '') {
        message("用户名不能为空");
        //$('.error-tips').html("用户名不能为空").show();
        sub = false;
        return false;
    } else if (!reg.test(username)) {
        message("请输入正确的11位手机号");
        sub = false;
        return false;
    } else {
        $('.error-tips').html("").hide();
    }
    if ($('#vcode').val() == '') {
        message("验证码不能为空");
        sub = false;
        return false;
    } else {
        $('.error-tips').html("").hide();
    }
    if ($('#userpwd').val() == '') {
        message("密码不能为空");
        sub = false;
        return false;
    } else if (userpwd_len < 6 || userpwd_len > 20) {
        message("密码长度应在6-20个字符之间");
        sub = false;
        return false;
    } else {
        $('.error-tips').html("").hide();
    }
    if (sub) {
        $.ajax({
            type: 'post',
            url: "/personal/userPasswordUpdate",
            data: {
                username: username,
                password: userpwd,
                vcode: vcode
            },
            dataType: 'json',
            success: function (data) {
                data = data.data;
                if (data.state) {
                    message(data.msg);
                    location.href = '/personal/userLoginView';
                    //$(".error-tips").hide();
                    return true;
                } else {
                    message(data.msg);
                    return false;
                }
            }
        });
    }
}
function send_auth_code(){
    var obj = $('button[data-eventid="MLoginRegister_ReReceiveMsgCheck"]');
    $("#vcode").val('');
    curCount = count;
    //设置button效果，开始计时
    obj.unbind("click").html("重新发送(" + curCount + "秒)").addClass('mesg-disable');
    InterValObj = window.setInterval(SetRemainTime, 1000); //启动计时器，1秒执行一次
    if ($('#username').val() == '') return false;
    $.post('/personal/getYZM',{login_phonenum:$('#username').val()},function(data){
        // data.code int '0':成功, '1':错误，'2':参数不合规则
        if (data.code) {
            message(data.message);
            return false;
            // $('.error-tips').html(data).show();
        } else {
            return true;
        }
    });
}

//timer处理函数
function SetRemainTime() {
    var obj = $('button[data-eventid="MLoginRegister_ReReceiveMsgCheck"]');
    if (curCount == 0) {
        window.clearInterval(InterValObj);//停止计时器
        obj.on('click',send_auth_code).removeAttr('disabled').html("重新发送验证码").removeClass('mesg-disable');
    }
    else {
        curCount--;
        obj.html("重新发送(" + curCount + "秒)");
    }
}
