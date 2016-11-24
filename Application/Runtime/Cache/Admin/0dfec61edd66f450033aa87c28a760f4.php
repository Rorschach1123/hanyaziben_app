<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html class="login-bg">
<head>
	<title><?php echo ($title); ?></title>
    
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	
    
    <link href="/hanyaziben_app/Public/Admin/css/bootstrap.css" rel="stylesheet" />
    <link href="/hanyaziben_app/Public/Admin/css/bootstrap-responsive.css" rel="stylesheet" />
    <link href="/hanyaziben_app/Public/Admin/css/bootstrap-overrides.css" type="text/css" rel="stylesheet" />

    <!-- global styles -->
    <link rel="stylesheet" type="text/css" href="/hanyaziben_app/Public/Admin/css/layout.css" />
    <link rel="stylesheet" type="text/css" href="/hanyaziben_app/Public/Admin/css/elements.css" />
    <link rel="stylesheet" type="text/css" href="/hanyaziben_app/Public/Admin/css/icons.css" />

    <!-- libraries -->
    <link rel="stylesheet" type="text/css" href="/hanyaziben_app/Public/Admin/css/font-awesome.css" />
    
    <!-- this page specific styles -->
    <link rel="stylesheet" href="/hanyaziben_app/Public/Admin/css/signin.css" type="text/css" media="screen" />

 
    <style type="text/css">
		.login-wrapper .box {
			margin: 35px auto!important;
		}
        #sxg{    font-size: 15px;
    height: 40px;
    border-color: #B2BFC7;
    float:left;
    width:140px;
    height:30px;
    margin-left:-8px;
}}
    </style>
    
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>
<body>


    <!-- background switcher -->
    <div class="bg-switch visible-desktop">
        <div class="bgs">
            <a href="#" data-img="landscape.jpg" class="bg active">
                <img src="/hanyaziben_app/Public/Admin/images/landscape.jpg" />
            </a>
            <a href="#" data-img="blueish.jpg" class="bg">
                <img src="/hanyaziben_app/Public/Admin/images/blueish.jpg" />
            </a>            
            <a href="#" data-img="7.jpg" class="bg">
                <img src="/hanyaziben_app/Public/Admin/images/7.jpg" />
            </a>
            <a href="#" data-img="8.jpg" class="bg">
                <img src="/hanyaziben_app/Public/Admin/images/8.jpg" />
            </a>
            <a href="#" data-img="9.jpg" class="bg">
                <img src="/hanyaziben_app/Public/Admin/images/9.jpg" />
            </a>
            <a href="#" data-img="10.jpg" class="bg">
                <img src="/hanyaziben_app/Public/Admin/images/10.jpg" />
            </a>
            <a href="#" data-img="11.jpg" class="bg">
                <img src="/hanyaziben_app/Public/Admin/images/11.jpg" />
            </a>
        </div>
    </div>


    <div class="row-fluid login-wrapper">
        <a href="<?php echo U('Index/index');?>">
            <img class="logo" src="/hanyaziben_app/Public/Admin/images/hanya_logo.png" />
        </a>

        <div class="span4 box">
            <form action="<?php echo U('Admin/Login/checked');?>" method="post">
                <div class="content-wrap">
                    <h6><?php echo ($title); ?></h6>
                    <input class="span12" type="text" placeholder="用户名" name="username" />
                    <input class="span12" type="password" placeholder="密码" name="pass" />
                    <div class="span4_1">
                    <input id="sxg" type="text" placeholder="验证码" /><img src="<?php echo U('Admin/Login/code');?>" name="vcode" title="看不清，换一张" onclick="change_code()" width="120" height="50">
                    </div>
                    <a href="#" class="forgot">忘记密码?</a>
                    <div class="remember">
                        <input id="remember-me" type="checkbox" />
                        <label for="remember-me">记住密码</label>
                    </div>
                
                    <input type="button" class="btn-glow primary login" value="登陆">
                </div>
            </form>
        </div>

       <!--  <div class="span4 no-account">
            <p>忘记密码?</p>
            <a href="signup.html">注册</a>
        </div> -->
    </div>

	<!-- scripts -->
    <script src="/hanyaziben_app/Public/Admin/js/jquery-latest.js"></script>
    <script src="/hanyaziben_app/Public/Admin/js/bootstrap.min.js"></script>
    <script src="/hanyaziben_app/Public/Admin/js/theme.js"></script>

    <!-- pre load bg imgs -->
    <script type="text/javascript">
        $(function () {
            // bg switcher
            var $btns = $(".bg-switch .bg");
            $btns.click(function (e) {
                e.preventDefault();
                $btns.removeClass("active");
                $(this).addClass("active");
                var bg = $(this).data("img");
                $("html").css("background-image", "url('/hanyaziben_app/Public/Admin/images/" + bg + "')");
            });
			$('.login').click(function(){
				
				var yzm = $('#sxg').val();
				t1='';
				$.post("<?php echo U('Admin/Login/check_code');?>",{'code':yzm},function(t){
					if (t){
						$('form').submit();
					}
					else{
						alert('验证码不正确，请重新填写！')
					}
				});
			});
        });
        function change_code(){
        	var verifyURL = "<?php echo U('Admin/Login/code');?>";
        	$("[name='vcode']").prop('src',verifyURL+'?'+Math.random());
        	return false;
        }
    </script>

</body>
</html>