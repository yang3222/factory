<?php if (!defined('THINK_PATH')) exit(); /*a:6:{s:69:"D:\project\factory\public/../application/admin\view\upplier\edit.html";i:1584407225;s:60:"D:\project\factory\application\admin\view\common\layout.html";i:1584407225;s:60:"D:\project\factory\application\admin\view\common\member.html";i:1584407225;s:61:"D:\project\factory\application\admin\view\common\topline.html";i:1584407225;s:58:"D:\project\factory\application\admin\view\common\menu.html";i:1584407225;s:61:"D:\project\factory\application\admin\view\common\mapline.html";i:1584407225;}*/ ?>
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
</ul>      <div class="rightbox">         <div class="menumap">    <a href="<?php echo url('/user/index'); ?>" class="home">首页</a>    <?php if(isset($menu[$currentMenu['menu']])): ?><a href="javaScript:;"><?php echo $menu[$currentMenu['menu']]['title']; ?></a><?php endif; if(isset($menu[$currentMenu['menu']]['nav'][$currentMenu['nav']])): ?><span><?php echo $menu[$currentMenu['menu']]['nav'][$currentMenu['nav']]['title']; ?></span><?php endif; ?></div>         <div class="canvas">            <div class="canvas_intro">

    <ul class="add_from">

        <li>

           <label class="label">供应商属性<i>*</i></label>

           <input name="attribute" type="text" value="<?php echo $data['attribute']; ?>" id="attribute" />

       </li>

       <!-- <li>

           <label class="label">品类<i>*</i></label>

           <select name="type" id="type">
           <option value="1">布料供应</option>
           <option value="2">辅料供应</option>
           <option value="3">材料加工</option>
           <option value="4">印花供应</option>
         </select>

       </li> -->

        <li>

           <label class="label">供应商名称<i>*</i></label>

           <input type="hidden" id="id" name="id" value="<?php echo $data['id']; ?>" />

           <input type="hidden" id="type" name="type" value="<?php echo $data['type']; ?>" />

           <input name="company" type="text" value="<?php echo $data['company']; ?>" id="company" />

        </li>

        <li>

           <label class="label">供应商简称<i>*</i></label>

           <input name="company_for_short" type="text" value="<?php echo $data['company_for_short']; ?>" id="company_for_short" />

        </li>

        <li>

           <label class="label">账期/付款方式<i>*</i></label>

           <input name="payment_days" type="text" value="<?php echo $data['payment_days']; ?>" id="payment_days" />

        </li>

        <li>

           <label class="label">联系人<i>*</i></label>

           <input name="contacts" type="text" value="<?php echo $data['contacts']; ?>" id="contacts" />

       </li>

       <li>

           <label class="label">联系人职位<i>*</i></label>

           <input name="contacts_position" type="text" value="<?php echo $data['contacts_position']; ?>" id="contacts_position" />

       </li>

        <li>

           <label class="label">联系电话<i>*</i></label>

           <input name="tel" type="text" value="<?php echo $data['tel']; ?>" id="tel" />

        </li>

        <li>

           <label class="label">邮箱<i>*</i></label>

           <input name="email" type="text" value="<?php echo $data['email']; ?>" id="email" />

        </li>

        <li>

           <label class="label">汇款账号(对公)<i>*</i></label>

           <input name="remittance_num_company" type="text" value="<?php echo $data['remittance_num_company']; ?>" id="remittance_num_company" />

        </li>

        <li>

           <label class="label">汇款账号(对私)<i>*</i></label>

           <input name="remittance_num_private" type="text" value="<?php echo $data['remittance_num_private']; ?>" id="remittance_num_private" />

        </li>


        <li>

           <label class="label">供应商地址<i>*</i></label>

           <input name="adress" type="text" value="<?php echo $data['adress']; ?>" id="adress" />

       </li>

        

       <li>

           <label class="label">主要购入材料<i>*</i></label>

           <textarea placeholder="材料名称" name="main_purchased_materials" id="main_purchased_materials"><?php echo $data['main_purchased_materials']; ?></textarea>

       </li>

       <li>

           <label class="label">备注</label>

           <textarea placeholder="备注" name="note" id="note"><?php echo $data['note']; ?></textarea>

       </li>

       <li>

           <label class="label">状态:</label>

           <input type="radio" name="display" id="display" <?php if($data['display']=='1'): ?>checked="checked"<?php endif; ?> value="1" />

               <label for="display">开</label>

           <input type="radio" name="display" id="display1" <?php if($data['display']=='0'): ?>checked="checked"<?php endif; ?> value="0" />

               <label for="display1">关</label>

       </li>

       <li>

           <a href="javaScript:upplier.save('<?php echo url('/admin/upplier/save'); ?>')">保存</a>

       </li>

    </ul>

</div>         </div>      </div>   </div>   <div class="swf_edit_box" id="swfbox"></div></body></html>