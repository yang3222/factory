<?php if (!defined('THINK_PATH')) exit(); /*a:6:{s:69:"D:\project\factory\public/../application/admin\view\member\index.html";i:1584407225;s:60:"D:\project\factory\application\admin\view\common\layout.html";i:1594716292;s:60:"D:\project\factory\application\admin\view\common\member.html";i:1584407225;s:61:"D:\project\factory\application\admin\view\common\topline.html";i:1594716292;s:58:"D:\project\factory\application\admin\view\common\menu.html";i:1584407225;s:61:"D:\project\factory\application\admin\view\common\mapline.html";i:1584407225;}*/ ?>
<!doctype html>
      <h1><img src="<?php echo ADMIN_STYLE_URL; ?>images/logo.png" /></h1>
      <ul class="rightbox">
          <audio id="fba_music">
              <source src="<?php echo ROOT_FBA_MUSIC_SRC; ?>555982.mp3" >
              Your browser does not support the audio element.
          </audio>
          <li class="fba_msg" id="fba_auto_msg_li" style="display: none;">
              <a href="javaScript:;" class="fba_msg"><span>新消息</span></a>
              <ul class="nav" id="fba_auto_msg_ul">

              </ul>
              <!--<audio id="fba_music" src="<?php echo ROOT_FBA_MUSIC_SRC; ?>555982.mp3" ></audio>--><!--autoplay="autoplay" loop="loop"-->
          </li>
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
<button id="fba_btn_mn" style="display: none;"></button>

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
    $(function() {
        //checkFun.init($("#selecttitle"), $("input[name='select']"));
    })
</script>
<div class="canvas_title do-clear">
    <ul class="tab_btn tab_btn_fl fl">
        <li><a href="<?php echo url('/admin/member/add'); ?>">添加</a></li>
    </ul>
</div>
<div class="canvas_intro">
    <table class="productli">
        <thead>
            <tr>
                <th width="10" class="center">#</th>
                <th width="150">账号</th>
                <th width="150">用户名</th>
                <th width="200">电话</th>
                <th class="center">所属组</th>
                <th>备注</th>
                <th class="center" width="40">状态</th>
                <th class="center" width="40">订单操作权限</th>
                <th class="center">添加时间</th>
                <th class="center">用户登入码</th>
                <th class="center" width="100">操作</th>
            </tr>
        </thead>
        <tbody>
            <?php if(is_array($member) || $member instanceof \think\Collection || $member instanceof \think\Paginator): $k = 0; $__LIST__ = $member;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$value): $mod = ($k % 2 );++$k;?>
            <tr class="navline">
                <td class="center"><?php echo $k; ?></td>
                <td><?php echo $value['User']; ?></td>
                <td><?php echo $value['userinfo']['Name']; ?></td>
                <td><?php echo $value['userinfo']['Tel']; ?></td>
                <td class="center">
                    <?php if(is_array($value['authGroup']) || $value['authGroup'] instanceof \think\Collection || $value['authGroup'] instanceof \think\Paginator): $authk = 0; $__LIST__ = $value['authGroup'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$authv): $mod = ($authk % 2 );++$authk;?>
                    <?php echo $authv['title']; if($authk < count($value['authGroup'])): ?>，<?php else: endif; endforeach; endif; else: echo "" ;endif; ?>
                </td>
                <td><?php echo htmlspecialchars_decode($value['userinfo']['Memo']); ?></td>
                <td class="center radio">
                    <?php if($value['reviewed']=='1'): ?><span>开</span>
                    <?php elseif($value['reviewed']=='2'): ?><span class="close">关</span>
                    <?php else: ?><span class="inside">审</span>
                    <?php endif; ?>
                </td>
                <td class="center radio">
                    <?php if($value['operation_auth']==1): ?><span>是</span>
                    <?php else: ?><span class="close">否</span>
                    <?php endif; ?>
                </td>
                <td class="center"><?php echo $value['Timer']; ?></td>
                <td class="center"><img src="<?php echo url('/admin/Member/getUserCodeBar','text='.$value['user_code']); ?>"></td>
                <td class="center operation">
                    <a href="<?php echo url('/admin/member/edit','id='.$value['id']); ?>">编辑</a>
                </td>
            </tr>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
    </table>
</div>
<div class="canvas_title do-clear">
    <ul class="tab_btn tab_btn_fl fl">
        <li><a href="<?php echo url('/admin/member/add'); ?>">添加</a></li>
    </ul>
</div>