<?php if (!defined('THINK_PATH')) exit(); /*a:6:{s:71:"D:\project\factory\public/../application/admin\view\capacity\index.html";i:1586410897;s:60:"D:\project\factory\application\admin\view\common\layout.html";i:1584407225;s:60:"D:\project\factory\application\admin\view\common\member.html";i:1584407225;s:61:"D:\project\factory\application\admin\view\common\topline.html";i:1584407225;s:58:"D:\project\factory\application\admin\view\common\menu.html";i:1584407225;s:61:"D:\project\factory\application\admin\view\common\mapline.html";i:1584407225;}*/ ?>
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
</ul>      <div class="rightbox">         <div class="menumap">    <a href="<?php echo url('/user/index'); ?>" class="home">首页</a>    <?php if(isset($menu[$currentMenu['menu']])): ?><a href="javaScript:;"><?php echo $menu[$currentMenu['menu']]['title']; ?></a><?php endif; if(isset($menu[$currentMenu['menu']]['nav'][$currentMenu['nav']])): ?><span><?php echo $menu[$currentMenu['menu']]['nav'][$currentMenu['nav']]['title']; ?></span><?php endif; ?></div>         <div class="canvas">            <link rel="stylesheet" href="<?php echo ADMIN_STYLE_URL; ?>css/date.css" type="text/css" media="screen" />
<script type="text/javascript" src="<?php echo ADMIN_STYLE_URL; ?>js/date.js"></script>
<script>

    $(function(){
        $('.custom-date').dateRangePicker($("input[name='start_date']"),$("input[name='end_date']"));
    })

</script>

<div class="canvas_title do-clear">

    <ul class="tab_btn tab_btn_fl fl">
        <li>
            <a href="javaScript:capacity.exports('<?php echo url('/admin/capacity/exports'); ?>')">批量导出数据</a>
        </li>
        <li>
            <a href="javaScript:capacity.exports('<?php echo url('/admin/capacity/exports'); ?>', 'all')">导出全部数据</a>
        </li>

    </ul>

    <ul class="tab_btn tab_btn_fl fr">
        <li>
            <select name="capacity_type" id="capacity_type">
                <option value="0" <?php if($capacity_type=='0'): ?>selected="selected"<?php endif; ?>>全部行为</option>
                <option value="1" <?php if($capacity_type=='1'): ?>selected="selected"<?php endif; ?>>生产</option>
                <option value="2" <?php if($capacity_type=='2'): ?>selected="selected"<?php endif; ?>>派单</option>
            </select>
        </li>
        <li class="date" >
            <select class='custom-date' name='custom_date' id="date">
                <option value='7'  selected='selected'>7天</option>
                <option value='30' >30 天</option>
                <option value='90' >90 天</option>
                <option value='180' >180 天</option>
                <option value='365' >365 天</option>
                <option value='custom' >自定义</option>
            </select>
            <input type="hidden" name="start_date" id="start_date" value="<?php echo $date['start_time']; ?>">
            <input type="hidden" name="end_date" id="end_date" value="<?php echo $date['end_time']; ?>">
        </li>
        <li class="input"><input type="text" id="search" name="search" value=""

                                 onkeydown="keys_press()"

                                 placeholder="搜索：用户名" /></li>

        <li><a href="javaScript:capacity.search()">搜索</a></li>

    </ul>


</div>

<div class="canvas_intro">

    <table class="productli">

        <thead>

        <tr>

            <th width="10" class="center">#</th>
            <th width="10" class="center"><input name="" type="checkbox" value="" id="select" /></th>
            <th width="30" class="center">用户名</th>
            <th width="30" class="">订单信息</th>
            <th width="30" class="center">产品图</th>
            <th width="30" class="center">行为</th>
            <th width="30" class="center">操作</th>
            <th width="120" class="center">操作时间</th>
        </tr>

        </thead>
        <tbody>
        <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $k = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$value): $mod = ($k % 2 );++$k;?>
        <tr>
            <td class="center"><?php echo $k; ?></td>
            <td class="center"><input name="select" type="checkbox" value="<?php echo $value['id']; ?>" /></td>
            <td class="center"><?php echo $value['user_name']; ?></td>
            <td class="info">
                <label>订单号：</label><?php echo $value['order_nums']; ?><br/>

                <label>SKU：</label><?php echo $value['order_sku']; ?><br/>
                <label>数量：</label><?php echo $value['order_amount']; ?><br/>
            </td>
            <td  class="center"><img src='http://kjds-img.img-cn-shanghai.aliyuncs.com/<?php echo $value['order_img']; ?>@0e_0o_1l_500h_500w.src' /></td>

            <td class="center"><?php echo $action_arr[$value['action']]; ?></td>
            <!--if condition="value.action == 1" 生产状态else订单指派/if-->
            <td class="center"><?php echo $value['assing_fac']; ?><?php echo $value['status']; ?></td>
            <td class="center"><?php echo $value['creat_time']; ?></td>

        </tr>
        <?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>

    </table>

</div>

<div class="canvas_title do-clear">

    <ul class="tab_btn tab_btn_fl fl">



    </ul>

    <?php echo $pageDiv; ?>

</div>

<script>
    //全选
    $("#select").change(function(){

        $("input[name=select").prop('checked',$("#select").prop("checked"));

    });
    function keys_press(frm,event) {
        var events = window.event ? window.event : event;
        if (events.keyCode == 13) {
            capacity.search();
        }
    }

    //js代码
    var capacity = {

        search: function () {
            //var value = obj[0].val();

            $.StandardPost("<?php echo url('/admin/capacity/index'); ?>", {
                sdate: $("#date").val(),
                start_time: $("#start_date").val(),
                end_time: $("#end_date").val(),
                search: $("#search").val(),
                capacity_type: $("#capacity_type").val(),
            });
        },

        exports:function(url, data = '') {
            if (data == '') {
                var eids = [];
                $('input:checkbox[name=select]:checked').each(function(k){

                    if($(this).val()!='') eids.push($(this).val());
                });
                /*if (eids.length <= 0) {
                    alert('请选择需要导出的数据');
                    return false;
                }*/
                var start_time = $("#start_date").val();
                var end_time = $("#end_date").val();
                var search = $("#search").val();
                var capacity_type = $("#capacity_type").val();
                window.location.href = url + "?ids=" + eids.join(',') + "&start_time=" + start_time + "&end_time=" + end_time + "&search=" + search + "&capacity_type=" + capacity_type;
                //$.post(url, {ids:eids.join(',')});
            } else if (data == 'all') {
                window.location.href = url + "?ids=all";
                //$.post(url, {ids:'all'});
            }
        },

    }
</script>         </div>      </div>   </div>   <div class="swf_edit_box" id="swfbox"></div></body></html>