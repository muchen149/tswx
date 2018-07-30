<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title>易远达微商城测试页</title>
</head>
<body>
<DIV align = "center">
    <p>{{$return_info['welcomeInfo']}} </p>
    <br />
</DIV>

<DIV align="left">
    <FORM encType="multipart/form-data" method="post" action="">
        <DIV style="width:100%;" align="left">
            <TABLE class="tableborder" border="0" cellSpacing="1" cellPadding="3" width="100%" align="center">
                <TBODY>
                <TR bgColor="#ffffff" valign="middle">
                    <TD height="30" align="right">请求方法（*）：</TD>
                    <TD height="30" >
                        <select id="app_method" name="app_method" style="width:150px;height:30px">
                            <option value="get" selected>Http get 方法</option>
                            <option value="post" >Http post 方法</option>
                        </select>
                        {{csrf_field()}}
                    </TD>
                </TR>

                <TR bgColor="#ffffff" valign="middle">
                    <TD height="80" align="right">url路由（*）：</TD>
                    <TD height="80" >
                        <INPUT style="height:25px" id="app_url" name="app_url" size="110" type="text">
                        <br />格式：http://ynes.yininet.com/api/v1/ynplat/goodsbrand/index/@keyword/@pageNo/@pageSize
                    </TD>
                </TR>

                <TR bgColor="#ffffff">
                    <TD height="100" align="right">post数据：</TD>
                    <TD height="100">
						<textarea rows="1000" cols="600" wrap="soft"
                                  style="width:673px;height:150px;"
                                  name="app_data" id="app_data">
						</textarea>
                        <br />格式：{"username":"admin","password":"666666","lifestyle":[21,40]}或
                        <br />&nbsp;&nbsp;&nbsp;&nbsp;{"data":"{\"userId\":13,\"signature\":\"我是大海中的一条船\",\"lifestyle\":[21,40]}"}
                    </TD>
                </TR>

                <TR bgColor="#ffffff">
                    <TD height="30">&nbsp;</TD>
                    <TD height="30">
                        <INPUT id="cmd_submit" onClick="Info_Submit();" value="提交" type="button">
                        <INPUT id="cmd_reset" value="重置" type="reset">
                    </TD>
                </TR>

                <TR height="30" valign="middle" >
                    <TD height="30" colSpan="2" align="center"></TD>
                </TR>
                <TR height="30" valign="middle" >
                    <TD height="30" colSpan="2" align="center">返回状态及信息</TD>
                </TR>

                <TR bgColor="#ffffff" valign="middle">
                    <TD height="50" align="right">状态码（code）：</TD>
                    <TD height="50">
                        <INPUT style="height:25px"  id="return_code" name="return_code" size="110" type="text">
                    </TD>
                </TR>

                <TR bgColor="#ffffff">
                    <TD height="200" align="right">数据（data）：</TD>
                    <TD height="200">
						<textarea rows="1000" cols="600" wrap="soft"
                                  style="width:673px;height:300px;"
                                  name="return_data" id="return_data">
						</textarea>
                    </TD>
                </TR>

                <TR bgColor="#ffffff">
                    <TD height="50" align="right">提示信息（message）：</TD>
                    <TD height="50">
						<textarea rows="1000" cols="600" wrap="soft"
                                  style="width:673px;height:50px;"
                                  name="return_message" id="return_message">
						</textarea>
                    </TD>
                </TR>

                <TR bgColor="#ffffff">
                    <TD height="30" align="right">用户ID：</TD>
                    <TD height="30" align="left">
                        <input type='text' name='fileControlName' id='fileControlName' value="file1" size="30" />
                        <input type="file" name="file1" id="file1" size="60"  />&nbsp;&nbsp;
                        <button type="submit">上传图片</button>
                    </TD>
                </TR>
                </TBODY>
            </TABLE>
        </DIV>
    </FORM>
</DIV>
</body>
<SCRIPT type="text/javascript" src="{{asset('js/jquery.min.js')}}"></SCRIPT>
<SCRIPT type="text/javascript" src="{{asset('js/test.js')}}"></SCRIPT>
</html>