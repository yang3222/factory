<?php if (!defined('THINK_PATH')) exit(); /*a:6:{s:86:"D:\project\factory\public/../application/admin\view\warehouse\out_in_detail_lists.html";i:1584407226;s:60:"D:\project\factory\application\admin\view\common\layout.html";i:1584407225;s:60:"D:\project\factory\application\admin\view\common\member.html";i:1584407225;s:61:"D:\project\factory\application\admin\view\common\topline.html";i:1584407225;s:58:"D:\project\factory\application\admin\view\common\menu.html";i:1584407225;s:61:"D:\project\factory\application\admin\view\common\mapline.html";i:1584407225;}*/ ?>
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
</ul>      <div class="rightbox">         <div class="menumap">    <a href="<?php echo url('/user/index'); ?>" class="home">首页</a>    <?php if(isset($menu[$currentMenu['menu']])): ?><a href="javaScript:;"><?php echo $menu[$currentMenu['menu']]['title']; ?></a><?php endif; if(isset($menu[$currentMenu['menu']]['nav'][$currentMenu['nav']])): ?><span><?php echo $menu[$currentMenu['menu']]['nav'][$currentMenu['nav']]['title']; ?></span><?php endif; ?></div>         <div class="canvas">            <link rel="stylesheet" href="<?php echo USER_STYLE_URL; ?>css/date.css" type="text/css" media="screen" /><script type="text/javascript" src="<?php echo USER_STYLE_URL; ?>js/date.js"></script><script type="text/javascript"> $(function(){        $('.custom-date').dateRangePicker($("input[name='start_date']"),$("input[name='end_date']"));          })  </script><div class="canvas_title do-clear">    <ul class="tab_btn tab_btn_fl fl">                                               <li class="input"><input type="text" id="operator" name="operator" value="<?php echo $operator; ?>"             placeholder="操作员" /></li>                <li class="input"><input type="text" id="search" name="search" value="<?php echo $search; ?>"       onkeydown="keyFun.key(event,13,search.searchArray,['<?php echo url('/admin/warehouse/outInDetailLists'); ?>','search','type','start_date','end_date','date','operator'])"       placeholder="搜索：库位/材料" /></li>                <li class="input">            <select name="type" id="type" style="width: 120px;">                <option value="">全部(出入库)</option>                <option <?php if($type == '1'): ?>selected="selected"<?php endif; ?> value="1" >出库</option>                <option <?php if($type == '2'): ?>selected="selected"<?php endif; ?>  value="2" >入库</option>            </select>          </li>                 <li class="date">            <select class='custom-date' name='custom_date' id="sdate">                <option value='7'  selected='selected'>7天</option>                <option value='30' >30 天</option>                <option value='90' >90 天</option>                <option value='180' >180 天</option>                <option value='365' >365 天</option>                <option value='custom' >自定义</option>            </select>           <input type="hidden" name="start_date" id="start_date" value="<?php echo $date['start_date']; ?>">            <input type="hidden" name="end_date" id="end_date" value="<?php echo $date['end_date']; ?>">        </li>              <li><a href="javaScript:search.searchArray(['<?php echo url('/admin/warehouse/outInDetailLists'); ?>','search','type','start_date','end_date','sdate','operator'])">搜索</a></li>    </ul>    <?php echo $pageDiv; ?></div><div class="canvas_intro">    <table class="productli">        <thead>            <tr>                <th width="10" class="center">#</th>                <th width="120" class="center">库位</th>                <th width="120" >材料</th>                <th width="120" >数量</th>                <th width="120">类型</th>                                <td >操作员</td>                <th >备注</th>                <th width="120" class="center">时间</th>            </tr>        </thead>        <tbody>            <?php if(is_array($data) || $data instanceof \think\Collection || $data instanceof \think\Paginator): $k = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$value): $mod = ($k % 2 );++$k;?>            <tr>                <td><?php echo $k; ?></td>                <td><?php echo $value['wt_name']; ?></td>                <td><?php echo $value['m_name']; ?></td>                <td><?php echo $value['count']; ?>(<?php echo $value['unit']; ?>)</td>                <td ><?php if($value['type']=='2'): ?>入库  <?php elseif($value['type']=='1'): ?>出库<?php endif; ?>  </td>                                <td ><?php echo $value['operator']; ?></td>                                <td><?php echo $value['memo']; ?></td>                <td class="center"><?php echo $value['create_time']; ?></td>            </tr>            <?php endforeach; endif; else: echo "" ;endif; ?>        </tbody>    </table></div><div class="canvas_title do-clear">       <?php echo $pageDiv; ?></div>         </div>      </div>   </div>   <div class="swf_edit_box" id="swfbox"></div></body></html>