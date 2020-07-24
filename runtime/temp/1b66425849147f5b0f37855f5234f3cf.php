<?php if (!defined('THINK_PATH')) exit(); /*a:6:{s:84:"D:\project\factory\public/../application/admin\view\warehouse\in_warehouse_next.html";i:1587879610;s:60:"D:\project\factory\application\admin\view\common\layout.html";i:1584407225;s:60:"D:\project\factory\application\admin\view\common\member.html";i:1584407225;s:61:"D:\project\factory\application\admin\view\common\topline.html";i:1584407225;s:58:"D:\project\factory\application\admin\view\common\menu.html";i:1584407225;s:61:"D:\project\factory\application\admin\view\common\mapline.html";i:1584407225;}*/ ?>
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
</ul>      <div class="rightbox">         <div class="menumap">    <a href="<?php echo url('/user/index'); ?>" class="home">首页</a>    <?php if(isset($menu[$currentMenu['menu']])): ?><a href="javaScript:;"><?php echo $menu[$currentMenu['menu']]['title']; ?></a><?php endif; if(isset($menu[$currentMenu['menu']]['nav'][$currentMenu['nav']])): ?><span><?php echo $menu[$currentMenu['menu']]['nav'][$currentMenu['nav']]['title']; ?></span><?php endif; ?></div>         <div class="canvas">            
<div class="canvas_title do-clear">

    <ul class="tab_nav fl">
        <li class="current"><a href="javaScript:void(0)">入库</a></li>
    </ul>

</div>
<div class="canvas_intro">

    <ul class="add_from">

        <li id='li_materiald_id'>
            <label class="label">材料id：<i></i></label>
            <input oninput = "value=value.replace(/[^\d]/g,'')" name="materiald_id" type="text" id="materiald_id" onkeydown="outInWarehouse.key(event,13,outInWarehouse.getmaterialDetail,['<?php echo url('/admin/warehouse/getmaterialDetail'); ?>','materiald_id'])"    />
        </li>

        <li id='li_material_name'>
            <label class="label">材料：<i></i></label>
            <input name="materiald_name" type="text" value="" id="materiald_name" />
        </li>
        <li id='li_count'>
            <label  class="label">数量：<i>*</i></label>
            <input name="count" type="text" value="" id="count" oninput = "value=value.replace(/[^\d]/g,'')"/><!--readonly="readonly"-->
        </li>
        <li>
            <label class="label">仓位：<i></i></label>
            <input name="wt_name" type="text" value="<?php echo $wt_name; ?>" id="wt_name"  onkeydown="keyspress()" />
            <input name="wt_id" type="hidden" value="<?php echo $wt_id; ?>" id="wt_id" />
        </li>
        <li>
<!--            <a id='btnconfig' href="javaScript:outInWarehouse.configWarehouse()">确认仓位</a>-->
            <a id='btnsave' href="javaScript:outInWarehouse.searchArrAjax(['<?php echo url('/admin/warehouse/inWarehouse'); ?>','materiald_id','wt_id','count'],'incallback')">保存</a>
        </li>


    </ul>
</div><div id="msg" style="margin-left: 180px"></div>
</div>

<script type="text/javascript">
    document.getElementById('wt_name').focus();
    function keyspress(frm,event) {
        var events = window.event ? window.event : event;
        if (events.keyCode == 13) {
            outInWarehouse.configWarehouse();
            //pro_status.search();
        }
    }
</script>

         </div>      </div>   </div>   <div class="swf_edit_box" id="swfbox"></div></body></html>