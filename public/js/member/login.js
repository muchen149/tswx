$(function(){
    //回到顶部
    $(".gotop").click(function (){
        $(window).scrollTop(0);
    });
	
	$('#loginbtn').on("click", function(){//会员登陆
        $username = $("#username").val();
        $password = $("#password").val();

        if($username == ''){
            message("用户名必须填写！");
            return false;
        }else if($password == ''){
            message("密码必填！");
            return false;
        }else{
            $.post( '/personal/userLoginSubmit',
                {
                    username: $username,
                    password: $password
                },
                function(data){
                    if (data.code) {
                        window.location.href="/personal/index";
                        return true;
                    } else {
                        message(data.message);
                        return false;
                    }
                });


        }

	});
});