<?php if (!defined('THINK_PATH')) exit(); /*a:6:{s:92:"D:\project\factory\public/../application/admin\view\productionstatusset\edit_pro_status.html";i:1584407224;s:60:"D:\project\factory\application\admin\view\common\layout.html";i:1584407225;s:60:"D:\project\factory\application\admin\view\common\member.html";i:1584407225;s:61:"D:\project\factory\application\admin\view\common\topline.html";i:1584407225;s:58:"D:\project\factory\application\admin\view\common\menu.html";i:1584407225;s:61:"D:\project\factory\application\admin\view\common\mapline.html";i:1584407225;}*/ ?>
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
</div>
<input type="hidden" id="pro_status_id" value="<?php echo $pro_status_id; ?>" />
<div class="canvas_intro">

    <ul class="add_from">

        <li>
            <label class="label">工厂生产流水名称<i>*</i>：</label>

            <input name="status_name" type="text" value="<?php echo $prostatus['status_name']; ?>" id="status_name" placeholder="" />

        </li>
        <li>
            <label class="label">简称<i>*</i>：</label>

            <input name="abbreviation" type="text" value="<?php echo $prostatus['abbreviation']; ?>" id="abbreviation" placeholder="字数控制在10字以内" />

        </li>


        <li>

            <a href="javaScript:addprostatus.save()">保存</a>

        </li>

    </ul>

</div>
<script>
    var addprostatus = {
        status_name:'',
        abbreviation:'',
        status:0,
        pro_status_id:'',

        getdata:function() {
            addprostatus.status_name = $('#status_name').val().replace(/^\s*|\s*$/g,"");//去除空格
            addprostatus.abbreviation = $('#abbreviation').val().replace(/^\s*|\s*$/g,"");
            addprostatus.pro_status_id = $().val('#pro_status_id');
        },
        DataLength:function(fData) {
            var intLength=0;
            for (var i=0;i<fData.length;i++)
            {
                if ((fData.charCodeAt(i) < 0) || (fData.charCodeAt(i) > 255))
                    intLength=intLength+2;
                else
                    intLength=intLength+1;
            }
            return intLength;
        },
        save:function() {
            addprostatus.getdata();
            if (addprostatus.status_name == '') {
                alert('请输入工厂生产流水名称');
                return;
            }
            if (addprostatus.abbreviation == '') {
                alert('请输入简称');
                return;
            }
            if (addprostatus.DataLength(addprostatus.abbreviation) > 26) {
                alert('简称字数过多，请减少');
                return;
            }
            var pro_status_id = $('#pro_status_id').val();
            if (pro_status_id == '') {
                alert('编辑出错，请重试');
                window.location = "<?php echo url('/admin/productionstatusset/index'); ?>";
            }
            $.post("<?php echo url('/admin/productionstatusset/edit_pro_status'); ?>", {id:pro_status_id, status_name:addprostatus.status_name, abbreviation:addprostatus.abbreviation}, function(res) {
                if (res['code'] == 1000) {
                    alert(res['msg']);
                    window.location = "<?php echo url('/admin/productionstatusset/index'); ?>";
                } else {
                    alert(res['msg']);
                    return;
                }
            });

        },
    }
</script>