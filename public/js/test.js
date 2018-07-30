$(function (){
    $(document).ready(function () {
        $("#app_url").focus();
    })
});


//信息提交
function Info_Submit() {
    //验证信息输入的合法性
    var _app_url = "";
    var _app_data = "";
    var _app_method = "get";
    var _token = "";

    _app_method = $.trim($("#app_method").val());
    _app_url = $.trim($("#app_url").val());
    _app_data = $.trim($("#app_data").val());
    _token = $('meta[name="csrf-token"]').attr('content');

    if (_app_method == "") {
        alert("请求方法不能为空！");
        return false;
    }

    if (_app_url == "" || _app_url == "0") {
        alert("接口url路由不能为空！");
        return false;
    }

    if (_token == "") {
        alert("token 没有读取出来！");
        return false;
    }


    // 地址参数如果有中文汉字，为了防止参数值乱码，地址进行encodeURI 编码
    _app_url = encodeURI(_app_url);
    // alert(_app_method);
    // alert(_app_url);
    // alert(_app_data);
    // alert(_token);

    //请注意参数的格式：值必须为字符型，数字或逻辑类型的传不过去
    var objParameter = new Object;
    if (_app_data != "")
    {
        //1、字符串转换成对象前，属性名称和属性值，必须是这样的格式："subjectId":"194"，不能是这样的格式：'subjectId':'194'
        //_app_data ='{"subjectType":"3","subjectId":"194","content":"我已经购买了相关产品！","userId":"1"}';
        //alert("处理前:" + _app_data);
        try
        {
            objParameter = JSON.parse(_app_data);
        }
        catch(e)
        {
            alert("数据格式不对！“"  +  _app_data + "”中含有非法字符或不符合JSON语法，例如属性名和属性值没有用双引号。");
            return false;
        }

        //2、字符串转换成对象后
        //objParameter = {"subjectType":"3","subjectId":"194","content":"我已经购买了相关产品！","userId":"1"};
        //alert("处理后:subjectType的值为“" + objParameter.subjectType + "”");

        // 3、再次转换成字符串
        //var json_string = JSON.stringify(objParameter);
        //alert(json_string);
    }

    //提交信息, async:false(默认是true：异步，false：同步)
    try
    {
        // headers:{"X-XSRF-TOKEN": $.cookie('XSRF-TOKEN')}
        $.ajax({
            url: _app_url,
            type: _app_method,
            data: objParameter,
            dataType: "json",
            headers: {
                "X-CSRF-TOKEN": _token
            },
            async:false,
            success: function (result) {
                // alert(result.code);
                $("#return_code").val(result.code);
                // alert(result.message);
                $("#return_message").val(result.message);

                var _return_data = "";
                if (result.data)
                {
                    //JSON 对象转换为字符串
                    _return_data = JSON.stringify(result.data);
                }
                else
                {
                    _return_data = result.data;
                }

                //alert(_return_data);
                $("#return_data").val(_return_data);
            },
            error: function(XMLHttpRequest,textStatus,errorThrown) {
                    //alert(XMLHttpRequest.status);
                    //alert(XMLHttpRequest.readyState);
                    alert(textStatus);
                    alert('接口测试出现未知的错误，请检查路由及HTTP请求方法是否匹配！');
                    $("#app_url").focus();
                    return false;
            }
        })
    }
    catch(e)
    {
        alert(e.name + ": " + e.message);
    }
}