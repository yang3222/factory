<?php if (!defined('THINK_PATH')) exit(); /*a:6:{s:74:"D:\project\factory\public/../application/admin\view\warehouse\upplier.html";i:1584407226;s:60:"D:\project\factory\application\admin\view\common\layout.html";i:1584407225;s:60:"D:\project\factory\application\admin\view\common\member.html";i:1584407225;s:61:"D:\project\factory\application\admin\view\common\topline.html";i:1584407225;s:58:"D:\project\factory\application\admin\view\common\menu.html";i:1584407225;s:61:"D:\project\factory\application\admin\view\common\mapline.html";i:1584407225;}*/ ?>
<!doctype html><html><head><meta charset="utf-8"><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"><meta name="viewport" content="width=device-width, initial-scale=1.0"><meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0,  user-scalable=0" name="viewport" /><title>管理后台</title><link rel="stylesheet" href="<?php echo ADMIN_STYLE_URL; ?>css/r.css" type="text/css" media="screen" /><link rel="stylesheet" href="<?php echo ADMIN_STYLE_URL; ?>css/style.css?v=1.0" type="text/css" media="screen" /><script type="text/javascript" src="<?php echo ADMIN_STYLE_URL; ?>js/jquery.min.js"></script><script type="text/javascript" src="<?php echo LAYER_JS_URL; ?>layer.js"></script><?php if(isset($eventJS)): ?><script type="text/javascript" src="<?php echo ADMIN_STYLE_URL; ?>js/<?php echo $eventJS; ?>.js"></script><?php endif; ?><script type="text/javascript" src="<?php echo ADMIN_STYLE_URL; ?>js/move.js"></script></head><body class="content">   <div class="topline">
      <h1><img src="<?php echo ADMIN_STYLE_URL; ?>images/logo.png" /></h1>
      <ul class="rightbox">
         <li>
            <a href="javaScript:;" class="fa move_over"><span><?php echo session('admin_name'); ?></span></a>
            <ul class="nav">
                <li><a href="<?php echo url('/admin/login/logout'); ?>">退出登录</a></li>
               <li><a href="<?php echo url('/admin/acount/editpwd'); ?>">修改密码</a></li>
            </ul>
         </li>
         <!--<li class="delete"><a href="#">删除缓存</a></li>-->
      </ul>
   </div>   <div class="contentbox do-clear">      <ul class="menubox">
    <?php if(is_array($menu) || $menu instanceof \think\Collection || $menu instanceof \think\Paginator): $mkey = 0; $__LIST__ = $menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$value): $mod = ($mkey % 2 );++$mkey;?>
    <li <?php if($currentMenu['menu']==$key): ?>class="open"<?php endif; ?>>
        <?php $menuKey = $key; ?>
        <a href="<?php if(isset($value['url'])): ?><?php echo $value['url']; else: ?>javaScript:;<?php endif; ?>"><i class="<?php echo $value['class']; ?>"></i><?php echo $value['title']; ?></a>
        <?php if(!empty($value['nav'])): ?>
        <ul>
        <?php if(is_array($value['nav']) || $value['nav'] instanceof \think\Collection || $value['nav'] instanceof \think\Paginator): $navKey = 0; $__LIST__ = $value['nav'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$li): $mod = ($navKey % 2 );++$navKey;?>
            <li <?php if($currentMenu['nav']==$key&&$currentMenu['menu']==$menuKey): ?>class="current"<?php endif; ?>><a href="<?php echo $li['url']; ?>" <?php if(isset($li['target'])): ?>target="<?php echo $li['target']; ?>"<?php endif; ?> ><?php echo $li['title']; ?></a></li>
        <?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
        <?php endif; ?>
    </li>
    <?php endforeach; endif; else: echo "" ;endif; ?>
</ul>      <div class="rightbox">         <div class="menumap">    <a href="<?php echo url('/user/index'); ?>" class="home">首页</a>    <?php if(isset($menu[$currentMenu['menu']])): ?><a href="javaScript:;"><?php echo $menu[$currentMenu['menu']]['title']; ?></a><?php endif; if(isset($menu[$currentMenu['menu']]['nav'][$currentMenu['nav']])): ?><span><?php echo $menu[$currentMenu['menu']]['nav'][$currentMenu['nav']]['title']; ?></span><?php endif; ?></div>         <div class="canvas">            <script>
    $(function(){
        select.select();
    })
</script>
<div class="canvas_title do-clear">
    <ul class="tab_nav fl">
        <li><a href="<?php echo url('/admin/warehouse/edit','id='.$data['id']); ?>">材料信息</a></li>
        <li  class="current"><a href="javaScript:void(0)">供应商</a></li>
    </ul>
</div>
<div class="canvas_intro">
    <table class="productli">
        <thead>
            <tr>
                <th width="10" class="center">#</th>
                <th width="10" class="center"><input name="" type="checkbox" value="" id="select" /></th>
                <th>供应商</th>
                <th>地址</th>
                <th>联系人</th>
                <th>电话</th>
                <th>商家类型</th>
                <th class="center" width="130">操作</th>
            </tr>
        </thead>
        <tbody>
            <?php if(is_array($data['materialupplier']) || $data['materialupplier'] instanceof \think\Collection || $data['materialupplier'] instanceof \think\Paginator): $k = 0; $__LIST__ = $data['materialupplier'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$upplier): $mod = ($k % 2 );++$k;?>
             <tr>
                <td class="center"><?php echo $k; ?></td>
                <td class="center"><input name="select" type="checkbox" value="<?php echo $upplier['pivot']['id']; ?>" /></td>
                <td><?php echo $upplier['company']; ?></td>
                <td><?php echo $upplier['adress']; ?></td>
                <td><?php echo $upplier['contacts']; ?></td>
                <td><?php echo $upplier['tel']; ?></td>
                <td><?php if($upplier['type']=='1'): ?>布料供应<?php elseif($upplier['type']=='2'): ?>辅料供应<?php else: ?>材料加工<?php endif; ?></td>
                <td class="center operation">
                    <a href="javaScript:material.delete('<?php echo url('/admin/warehouse/deleteUpplier'); ?>',['<?php echo $upplier['pivot']['id']; ?>'])">删除</a>
                </td>
            </tr>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
    </table>
</div>
<div class="canvas_title do-clear">
    <ul class="tab_btn tab_btn_fl fl">
        <li><a href="<?php echo url('/admin/warehouse/addUpplier','id='.$data['id']); ?>">添加</a></li>
        <li><a href="javaScript:material.deleteall('<?php echo url('/admin/warehouse/deleteUpplier'); ?>')">删除</a></li>
    </ul>
</div>         </div>      </div>   </div>   <div class="swf_edit_box" id="swfbox"></div></body></html>