<?php if (!defined('THINK_PATH')) exit(); /*a:6:{s:79:"D:\project\factory\public/../application/admin\view\materialforecast\index.html";i:1584407225;s:60:"D:\project\factory\application\admin\view\common\layout.html";i:1584407225;s:60:"D:\project\factory\application\admin\view\common\member.html";i:1584407225;s:61:"D:\project\factory\application\admin\view\common\topline.html";i:1584407225;s:58:"D:\project\factory\application\admin\view\common\menu.html";i:1584407225;s:61:"D:\project\factory\application\admin\view\common\mapline.html";i:1584407225;}*/ ?>
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

</script>

<div class="canvas_title do-clear">

    <ul class="tab_btn tab_btn_fl fl">

        <li class="input"><input type="text" id="search" name="search" value=""

                                 onkeydown="keyFun.key(event,13,product.search,[$('#search'),'<?php echo url('/admin/materialforecast/index'); ?>'])"

                                 placeholder="搜索：产品名称/产品ID" /></li>

        <li><a href="javaScript:product.search([$('#search'),'<?php echo url('/admin/materialforecast/index'); ?>'])">搜索</a></li>

    </ul>

    <?php echo $pageDiv; ?>

</div>

<div class="canvas_intro">

    <table class="productli">

        <thead>

        <tr>

            <th width="10" class="center">#</th>

            <th width="10" class="center"><input name="" type="checkbox" value="" id="select" /></th>

            <th width="30" class="center">id</th>

            <th width="50" class="center">产品ID</th>

            <th width="100" class="center">产品编号</th>

            <th width="150">产品名称</th>

            <th width="150">缩略图</th>

            <th width="150">型号</th>
            <th width="150"><?php echo $today; ?>预估量</th>
            <th width="150" class="center">操作</th>

        </tr>

        </thead>

        <tbody>

        <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $k = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$value): $mod = ($k % 2 );++$k;?>

        <tr>

            <td class="center"><?php echo $k; ?></td>

            <td class="center"><input name="select" type="checkbox" value="<?php echo $value['id']; ?>" /></td>

            <td class="center"><?php echo $value['id']; ?></td>

            <td class="center"><?php echo $value['product_id']; ?></td>

            <td class="center"><?php echo $value['product_num']; ?></td>

            <td><?php echo $value['name']; ?></td>

            <td><?php if(strstr($value['smallimg'],'http')): ?><img src='<?php echo $value['smallimg']; ?>@0e_0o_1l_500h_500w.src' /><?php else: ?><img src='<?php echo PRODUCT_IMG; ?><?php echo $value['smallimg']; ?>' /><?php endif; ?></td>

            <td>

                <?php if(is_array($value['productsize']) || $value['productsize'] instanceof \think\Collection || $value['productsize'] instanceof \think\Paginator): $i = 0; $__LIST__ = $value['productsize'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$size): $mod = ($i % 2 );++$i;?>

                <?php echo $size['Size']; ?><br/>

                <?php endforeach; endif; else: echo "" ;endif; ?>

            </td>
            <td>
                <?php echo $value['predict']; ?>
            </td>
            <td class="center operation">
                <a href="<?php echo url('/admin/materialforecast/predict_details','product_id='.$value['product_id']); ?>">查看详情</a>
            </td>

        </tr>

        <?php endforeach; endif; else: echo "" ;endif; ?>

        </tbody>

    </table>

</div>

<div class="canvas_title do-clear">

    <ul class="tab_btn tab_btn_fl fl">

        <!--<li><a href="<?php echo url('/admin/product/add'); ?>">添加</a></li>

        <li><a href="javaScript:product.deleteall('<?php echo url('/admin/product/delete'); ?>')">删除</a></li>-->

    </ul>

    <?php echo $pageDiv; ?>

</div>

<script>
    var materialforecast = {

        search:function(){
            var value=$('#search').val();
            if (value == '') {
                alert('搜索词为空');
                return;
            }
            var url = "<?php echo url('/admin/orderexcel/index'); ?>";
            $.StandardPost(url, {search:value});
        },
    }
</script>         </div>      </div>   </div>   <div class="swf_edit_box" id="swfbox"></div></body></html>