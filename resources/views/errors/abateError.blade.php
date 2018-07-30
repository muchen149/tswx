<!DOCTYPE html>
<html>
    <head>
        <title>提示页面</title>
        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="{{asset('/elife_css/layer.css')}}">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0"> 
    </head>
    <body onload="autoloading();"></body>
</html>
<script type="text/javascript" src="{{asset('/elife_js/layer.js')}}"></script>
<script type="text/javascript" src="{{asset('/elife_js/hybrid_app.js')}}"></script>
<script type="text/javascript">
window.onload=
        function autoloading(){
			  //信息框
			  layer.open({
				content: '您的用户信息已过期，请重新登录！'
				,btn: '我知道了'
				,time:10
				,yes: function(index){
					hybrid_app.back();
					layer.close(index);
				}
			  });
			var curCount = 8;
			var InterValObj = window.setInterval(SetRemainTime, 1000); //启动计时器，1秒执行一次
			function SetRemainTime() {
				if (curCount == 0) {
					window.clearInterval(InterValObj);//停止计时器
					hybrid_app.back();
				} else {
					curCount--;
				}
			   return false;
			}
        }
    </script>