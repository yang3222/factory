<?php if (!defined('THINK_PATH')) exit(); /*a:6:{s:71:"D:\project\factory\public/../application/admin\view\acount\editpwd.html";i:1584407225;s:60:"D:\project\factory\application\admin\view\common\layout.html";i:1584407225;s:60:"D:\project\factory\application\admin\view\common\member.html";i:1584407225;s:61:"D:\project\factory\application\admin\view\common\topline.html";i:1584407225;s:58:"D:\project\factory\application\admin\view\common\menu.html";i:1584407225;s:61:"D:\project\factory\application\admin\view\common\mapline.html";i:1584407225;}*/ ?>
<!doctype html>
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
   </div>
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
</ul>
    var editpwd={
        save:function(){
            if($("#new_pwd").val().lengh<6){
                alert("密码必须大于6位数");
                return;
            }
            if($("#new_pwd").val()!=$("#yes_new_pwd").val()){
                alert("新密码不一致，请重新设置");
                return;
            }
            if($("#old_pwd").val()==$("#yes_new_pwd").val()){
                alert("旧密码与新密码一致，请重新设置");
                return;
            }
            $.post("<?php echo url('/admin/acount/edit'); ?>",{old:$("#old_pwd").val(),new:$("#new_pwd").val()},function(data){
                alert(data);
            })
        }
    }
</script>
<div class="canvas_title do-clear">
   <h2>修改密码</h2>
</div>
<div class="canvas_intro">
   <ul class="add_from">
      <li>
         <label class="label">旧密码<i>*</i></label>
         <input name="old_pwd" type="password" id="old_pwd" value="" />
      </li>
      <li>
         <label class="label">新密码<i>*</i></label>
         <input name="new_pwd" type="password" id="new_pwd" value="" />
      </li>
      <li>
         <label class="label">确认新密码<i>*</i></label>
         <input name="yes_new_pwd" type="password" id="yes_new_pwd" value="" />
      </li>
      <li>
         <a href="javaScript:editpwd.save()">保存</a>
      </li>
   </ul>
</div>