<?php if (!defined('THINK_PATH')) exit(); /*a:6:{s:75:"D:\project\factory\public/../application/admin\view\expsetting\add_exp.html";i:1584407225;s:60:"D:\project\factory\application\admin\view\common\layout.html";i:1584407225;s:60:"D:\project\factory\application\admin\view\common\member.html";i:1584407225;s:61:"D:\project\factory\application\admin\view\common\topline.html";i:1584407225;s:58:"D:\project\factory\application\admin\view\common\menu.html";i:1584407225;s:61:"D:\project\factory\application\admin\view\common\mapline.html";i:1584407225;}*/ ?>
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
</ul>      <div class="rightbox">         <div class="menumap">    <a href="<?php echo url('/user/index'); ?>" class="home">首页</a>    <?php if(isset($menu[$currentMenu['menu']])): ?><a href="javaScript:;"><?php echo $menu[$currentMenu['menu']]['title']; ?></a><?php endif; if(isset($menu[$currentMenu['menu']]['nav'][$currentMenu['nav']])): ?><span><?php echo $menu[$currentMenu['menu']]['nav'][$currentMenu['nav']]['title']; ?></span><?php endif; ?></div>         <div class="canvas">            <div class="canvas_title do-clear">
</div>

<div class="canvas_intro">

    <ul class="add_from">

        <li>
            <label class="label">快递名称<i>*</i>：</label>

            <input name="lgstcode_name" type="text" value="" id="lgstcode_name" />

        </li>
        <li>
            <label class="label">快递code<i>*</i>：</label>

            <input name="lgstcode" type="text" value="" id="lgstcode" />

        </li>
        <li>
            <label class="label">开启自动设置工厂：</label>
            <input type="radio" name="status"  id="statusyes" value="1"><label for="statusyes">是</label>
            <input type="radio" name="status"  id="statusno" value="0" checked><label for="statusno">否</label>
        </li>

        <li>

            <a href="javaScript:addlgstcode.save()">保存</a>

        </li>

    </ul>

</div>
<script>
    var addlgstcode = {
        lgstcode_name:'',
        lgstcode:'',
        status:0,

        getdata:function() {
            addlgstcode.lgstcode = $('#lgstcode').val().replace(/^\s*|\s*$/g,"");//去除空格
            addlgstcode.lgstcode_name = $('#lgstcode_name').val().replace(/^\s*|\s*$/g,"");
            addlgstcode.status = $("input[type='radio']:checked").val();
        },


        save:function() {
            addlgstcode.getdata();
            if (addlgstcode.lgstcode == '') {
                alert('请输入快递名称');
                return;
            }
            if (addlgstcode.lgstcode_name == '') {
                alert('请输入快递code');
                return;
            }
            $.post("<?php echo url('/admin/expsetting/add_exp'); ?>", {lgstcode:addlgstcode.lgstcode, lgstcode_name:addlgstcode.lgstcode_name, status:addlgstcode.status}, function(res) {
                if (res['code'] == 1000) {
                    alert(res['msg']);
                    window.location = "<?php echo url('/admin/expsetting/index'); ?>";
                } else {
                    alert(res['msg']);
                    return;
                }
            });

        },
    }
</script>         </div>      </div>   </div>   <div class="swf_edit_box" id="swfbox"></div></body></html>