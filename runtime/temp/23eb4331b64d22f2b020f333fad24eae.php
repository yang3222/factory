<?php if (!defined('THINK_PATH')) exit(); /*a:6:{s:73:"D:\project\factory\public/../application/admin\view\expsetting\index.html";i:1584407225;s:60:"D:\project\factory\application\admin\view\common\layout.html";i:1584407225;s:60:"D:\project\factory\application\admin\view\common\member.html";i:1584407225;s:61:"D:\project\factory\application\admin\view\common\topline.html";i:1584407225;s:58:"D:\project\factory\application\admin\view\common\menu.html";i:1584407225;s:61:"D:\project\factory\application\admin\view\common\mapline.html";i:1584407225;}*/ ?>
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
</ul>      <div class="rightbox">         <div class="menumap">    <a href="<?php echo url('/user/index'); ?>" class="home">首页</a>    <?php if(isset($menu[$currentMenu['menu']])): ?><a href="javaScript:;"><?php echo $menu[$currentMenu['menu']]['title']; ?></a><?php endif; if(isset($menu[$currentMenu['menu']]['nav'][$currentMenu['nav']])): ?><span><?php echo $menu[$currentMenu['menu']]['nav'][$currentMenu['nav']]['title']; ?></span><?php endif; ?></div>         <div class="canvas">            <style>
    .openyes {
        display: inline-block;
        height: 22px;
        line-height: 22px;
        padding: 0 10px;
        -moz-border-radius: 4px;
        -webkit-border-radius: 4px;
        border-radius: 4px;
        background: #5bb75b;
        color: #fff;
        font-size: 12px;
    }
    .closeno {
        display: inline-block;
        height: 22px;
        line-height: 22px;
        padding: 0 10px;
        -moz-border-radius: 4px;
        -webkit-border-radius: 4px;
        border-radius: 4px;
        background: #e80c0c;
        color: #fff;
        font-size: 12px;
    }
</style>

<div class="canvas_title do-clear">

    <ul class="tab_btn tab_btn_fl fl">

        <li><a href="<?php echo url('/admin/expsetting/add_exp'); ?>">添加</a></li>

        <!--<li><a href="javaScript:lgstcode.deleteall('<?php echo url('/admin/product/delete'); ?>')">删除</a></li>-->


        <!--<li class="input"><input type="text" id="search" name="search" value=""

                                 placeholder="搜索：快递名称/快递code" /></li>

        <li><a href="javaScript:lgstcode.search()">搜索</a></li>-->

    </ul>

    <?php echo $pageDiv; ?>

</div>

<div class="canvas_intro">

    <table class="productli">

        <thead>

        <tr>

            <th width="10" class="center">#</th>

            <!--<th width="10" class="center"><input name="" type="checkbox" value="" id="select" /></th>-->

            <th width="30" class="center">id</th>

            <th width="100" class="center">快递名称</th>

            <th width="100" class="center">快递code</th>

            <th width="50">自动设置工厂</th>

            <th class="center" width="150">操作</th>

        </tr>

        </thead>

        <tbody>

        <?php if(is_array($lgscode) || $lgscode instanceof \think\Collection || $lgscode instanceof \think\Paginator): $k = 0; $__LIST__ = $lgscode;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$value): $mod = ($k % 2 );++$k;?>

        <tr>

            <td class="center"><?php echo $k; ?></td>

            <!--<td class="center"><input name="select" type="checkbox" value="<?php echo $value['id']; ?>" /></td>-->

            <td class="center"><?php echo $value['id']; ?></td>

            <td class="center"><?php echo $value['lgstcode_name']; ?></td>

            <td class="center"><?php echo $value['lgstcode']; ?></td>

            <td><?php if($value['status'] == 1): ?><span class="openyes">是</span><?php else: ?><span class="closeno">否</span><?php endif; ?></td>

            <td class="center operation">

                <a href="<?php echo url('/admin/expsetting/edit_exp','id='.$value['id']); ?>">编辑</a>

                <!--<a href="javaScript:lgstcode.delete('<?php echo url('/admin/product/delete'); ?>',['<?php echo $value['id']; ?>'])">删除</a>-->

            </td>

        </tr>

        <?php endforeach; endif; else: echo "" ;endif; ?>

        </tbody>

    </table>

</div>

<div class="canvas_title do-clear">

    <ul class="tab_btn tab_btn_fl fl">

        <li><a href="<?php echo url('/admin/expsetting/add_exp'); ?>">添加</a></li>

        <!--<li><a href="javaScript:lgstcode.deleteall('<?php echo url('/admin/product/delete'); ?>')">删除</a></li>-->

    </ul>

    <?php echo $pageDiv; ?>

</div>

<script>
    var lgstcode = {
        search:function() {
            var search_key = $('#search').val().replace(/^\s*|\s*$/g,"");

        },
    };
    document.getElementById('search').focus();
</script>         </div>      </div>   </div>   <div class="swf_edit_box" id="swfbox"></div></body></html>