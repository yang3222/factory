<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:83:"D:\project\factory\public/../application/index\view\business\arehouse_bar_code.html";i:1584407223;}*/ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>仓库文件</title>
<script type="text/javascript" src="<?php echo ADMIN_STYLE_URL; ?>js/flash.js"></script>
<style>
body, html{
	padding:0;
	margin:0;
	height:100%;
	width:100%;
	overflow:hidden;
}
#flash{
	width:100%;
	height:100%;
}
.box{
	width:100px;
	height:100px;
	background:#00ff00;
	position:absolute;
	left:100px;
	top:100px;
	z-index:100;
}
</style>
</head>
<body>
   <div id="flash"> 
    <!--pid=产品ID config=配置信息-->
    <script>playswf_tramouse('<?php echo INDEX_STYLE_URL; ?>swf/Main.swf?url=<?php echo INDEX_STYLE_URL; ?>swf/fl',"100%","100%")</script>
  </div>
</body>
</html>