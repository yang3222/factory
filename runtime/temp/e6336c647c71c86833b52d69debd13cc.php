<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:78:"D:\project\factory\public/../application/admin\view\software\umbrellalist.html";i:1584407225;}*/ ?>
<?php echo '<?'; ?>
xml version="1.0" encoding="utf-8"?>
<data>
    <?php if(is_array($data) || $data instanceof \think\Collection || $data instanceof \think\Paginator): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$value): $mod = ($i % 2 );++$i;?>
    <umbrella>
        <id><?php echo $value['id']; ?></id>
        <name><?php echo $value['name']; ?></name>
	<img><?php echo WEB; ?><?php echo PRODUCT_IMG; ?><?php echo $value['imgurl']; ?></img>
    </umbrella>
    <?php endforeach; endif; else: echo "" ;endif; ?>
   </data>