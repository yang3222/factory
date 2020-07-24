<?php if (!defined('THINK_PATH')) exit(); /*a:6:{s:72:"D:\project\factory\public/../application/admin\view\checkbill\index.html";i:1584407225;s:60:"D:\project\factory\application\admin\view\common\layout.html";i:1584407225;s:60:"D:\project\factory\application\admin\view\common\member.html";i:1584407225;s:61:"D:\project\factory\application\admin\view\common\topline.html";i:1584407225;s:58:"D:\project\factory\application\admin\view\common\menu.html";i:1584407225;s:61:"D:\project\factory\application\admin\view\common\mapline.html";i:1584407225;}*/ ?>
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
<script>

    function importent(){
        if($("#file_stu").val()==""){
            alert("请选择要导入的文件");
            return false;
        }
        //if (!isValueNumber($("#rate").val()) || $("#rate").val() == 0) {
            //alert("请输入正确的汇率");
            //return false;
        //}
        return true;
    }

    function isValueNumber(value) {

        var reg = (/(^-?[0-9]+\.{1}\d+$)|(^-?[1-9][0-9]*$)|(^-?0{1}$)/);
        var flag = reg.test(value+'');
        return flag;
    }

</script>
<style>
    .im_form {

    }
    .im_form h3 {
        font-size: 18px;
    }
    .im_file {

    }
</style>
<div class="canvas_title do-clear">

    <ul class="tab_btn tab_btn_fl fl">

        <!--<li><a href="javaScript:uploadExcel.openFile('<?php echo url('/admin/orderexcel/uploadexcel'); ?>')">选择运单号excel文件</a>
        </li>-->
        <!--<li><a href="javaScript:uploadExcel.openFile('<?php echo url('/admin/orderexcel/exportexcel'); ?>')">导出结果</a>
        </li>-->

    </ul>

</div>

<div class="canvas_intro">

    <form method="post" action="<?php echo url('/admin/checkbill/exportdata'); ?>" enctype="multipart/form-data"  onsubmit="return importent();" class="im_form">
        <h3>导入Excel表：</h3><input  type="file" name="file" id="file_stu" class="im_file" />

        </br>
        <input type="submit"  value="导出结果" />
    </form>
</div>

<div class="canvas_title do-clear">

    <ul class="tab_btn tab_btn_fl fl">

    </ul>

</div>

<script>
    //全选
    $("#select").change(function(){

        $("input[name=select").prop('checked',$("#select").prop("checked"));

    });
    function keys_press(frm,event) {
        var events = window.event ? window.event : event;
        if (events.keyCode == 13) {
            excel9610.search();
        }
    }
</script>         </div>      </div>   </div>   <div class="swf_edit_box" id="swfbox"></div></body></html>