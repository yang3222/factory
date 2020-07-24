<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:73:"D:\project\factory\public/../application/admin\view\software\factory.html";i:1584407225;}*/ ?>
<?php echo '<?'; ?>
xml version="1.0" encoding="utf-8"?>
<data>
    <?php if(is_array($data) || $data instanceof \think\Collection || $data instanceof \think\Paginator): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$value): $mod = ($i % 2 );++$i;?>
    <factory>
      <id><?php echo $value['id']; ?></id>
      <name><?php echo $value['userinfo']['Name']; ?></name>
   </factory>
    <?php endforeach; endif; else: echo "" ;endif; ?>
   </data>