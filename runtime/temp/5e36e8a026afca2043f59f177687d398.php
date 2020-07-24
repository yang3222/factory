<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:67:"D:\project\factory\public/../application/user\view\login\index.html";i:1584407226;s:59:"D:\project\factory\application\user\view\common\member.html";i:1584407226;}*/ ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8"><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>后台登录</title><link rel="stylesheet" href="<?php echo USER_STYLE_URL; ?>css/r.css" type="text/css" media="screen" /><link rel="stylesheet" href="<?php echo USER_STYLE_URL; ?>css/style.css" type="text/css" media="screen" /><script type="text/javascript" src="<?php echo USER_STYLE_URL; ?>js/jquery.min.js"></script><?php if(isset($eventJS)): ?><script type="text/javascript" src="<?php echo USER_STYLE_URL; ?>js/<?php echo $eventJS; ?>.js"></script><?php endif; ?>
<script>
    $(function(){
        code.imgurl="<?php echo url('/user/login/code'); ?>";
        code.Img=$("#codeimg");
        login.UserName=$('#user');
        login.PassWord=$('#pwd');
        login.Code=$('#code');
        login.Tip=$('#tiptxt');
        login.PostURL="<?php echo url('/user/login/checklogin'); ?>";
        login.IndexURL="<?php echo url('/user/index'); ?>";
        login.entEvent();
    })
</script>
</head>

<body class="login">
   <div class="loginbox">
      <h1><a href="#"><img src="<?php echo ADMIN_STYLE_URL; ?>images/login_logo.png" /></a></h1>
      <div class="login-form">
         <h2>会员登录</h2>
         <input name="" id="user" type="text" placeholder="账号">
         <input name="" id="pwd" type="password" placeholder="密码">
         <div class="yzm do-clear">
            <input name="" id="code" type="text" class="code" placeholder="验证码">
            <img title="点击刷新" id="codeimg" src="<?php echo url('/user/login/code'); ?>" onclick="code.img()" />
         </div>
         <a href="javaScript:login.post()" class="btn">登录</a>
         <span id="tiptxt" class="logintip"></span>
      </div>
   </div>
</body>
</html>
