<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:83:"D:\project\factory\public/../application/admin\view\epwarehouse\warehouse_list.html";i:1591251645;s:67:"D:\project\factory\application\admin\view\common\mobile_layout.html";i:1591235859;}*/ ?>
<!DOCTYPE html>
<html lang="en" style="font-size: 50px;">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0,  user-scalable=0" name="viewport" />
    <title>订单管理</title>

    <!--<link rel="stylesheet" href="<?php echo ADMIN_STYLE_URL; ?>css/mobile_main.css" type="text/css" media="screen" />-->
    <link rel="stylesheet" type="text/css" href="http://at.alicdn.com/t/font_1434617_rvv6b44rjr.css"/>
    <link rel="stylesheet" type="text/css" href="http://at.alicdn.com/t/font_1434617_nhsftvdrjsg.css"/>
    <script type="text/javascript" src="<?php echo ADMIN_STYLE_URL; ?>js/jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo ADMIN_STYLE_URL; ?>js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="<?php echo ADMIN_STYLE_URL; ?>js/jquery.simplesidebar.js"></script>
    <script type="text/javascript" src="<?php echo ADMIN_STYLE_URL; ?>js/outOrInWarehouse.js"></script>
<style>
    @charset "utf-8";
    *{margin:0;padding:0;list-style-type:none;}
    img{border:0;color:#fff;text-decoration:none;}
    a{border:0;color:#999;text-decoration:none;}
    body{font:12px/180% Arial, Helvetica, sans-serif, "新宋体";}
    h5,h6{font-size:18px;}

    section{margin:4em 0;}
    .toolbar{position:fixed;left:50px;top:10px;background-color:transparent;line-height:68px;}
    .menu-button{position:relative;margin:5px;height:36px;width:36px;cursor:pointer;}
    .menu-button:before{content:"";position:absolute;top:5px;right:3px; border-bottom: 17px double #9fbaf5;border-top: 6px solid #9fbaf5;width:30px;height:5px;}
    .menu-left{float:left;margin-right:1.5em;}
    .sidebar{margin:0;padding-top:1em;color:#000;background-color:#4285f4;}
    .sidebar h6{margin:2px .8em;padding:3px;font-weight:300;}
    .sidebar li{margin:.5em;padding:1px 1px 1px 2px;line-height:0.8rem;border-bottom: 1px solid #e5e5e5;text-align: center;color: #000;font-size: 0.33rem;}
    .sidebar li:hover{background-color:#e84e40;}
    .sidebar hr{margin:0.1em auto;border:0;padding:0;width:100%;height:1px;background-color:#000;}
    /*主要样式*/
    .subNav{
        font-size: 0.45rem;
        cursor:pointer;
        background-color: #4285f4;
        text-align: center;
        line-height: 1rem;
        color: #fff;
    }
    .subNav_color{background-color:#4285f4;}
    .navContent{display:none;color:#999;}
    .subNav1 h5 {float: left;}
    .subNav1 a {float: right;font-size: 18px;}
    /*  公用样式 */
    html,
    body {
        background: #F6F6F6;
    }

    .font-bold {
        font-weight: bold;
    }

    /*  复选框自定义样式 */
    input[type="checkbox"] {
        width: 12px;
        height: 12px;
        display: inline-block;
        text-align: center;
        vertical-align: middle;
        line-height: 12px;
        position: relative;
        border-radius: 50%;
    }
    input[type="checkbox"]::before {
        content: "";
        position: absolute;
        top: -4px;
        left: -4px;
        background: #fff;
        width: 20px;
        height: 20px;
        border: 1px solid #CACDCF;
        border-radius: 50%;
    }
    input[type="checkbox"]:checked::before {
        content: "\2713";
        background-color: #ff5000;
        color: #fff;
        position: absolute;
        top: -4px;
        left: -4px;
        width: 20px;
        height: 20px;
        border: 1px solid #ff5000;
        font-size: 14px;
        font-weight: bold;
        line-height: 20px;
    }

    .header-cls {
        position: fixed;
        left: 0;
        top: 0;
        right: 0;
        height: 1.2rem;
        font-size: 0.37rem;
        line-height: 1.2rem;
        background: #4285f4;
        color: #fff;
        z-index: 999;
    }

    .header-cls .left-cls {
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        height: 1.2rem;
        line-height: 1.2rem;
        padding: 0 0.2rem;
        z-index: 9;
        font-size: 0.4rem;
    }

    .header-cls .right-cls {
        position: absolute;
        right: 0;
        top: 0;
        bottom: 0;
        height: 1.2rem;
        line-height: 1.2rem;
        padding: 0 0.2rem;
        z-index: 9;
        font-size: 0.32rem;
    }

    .header-cls .right-cls.iconfont {
        font-size: 0.4rem;
    }

    .header-cls .header-title {
        text-align: center;
    }

    .disNone{
        display: none !important;
    }

    .fiex-bottom{
        position: fixed;
        left: 0;
        bottom: 0;
        right: 0;
        height: 0.98rem;
        line-height: 0.98rem;
        font-size: 0.3rem;
        display: flex;
        background: #fff;
        padding-left: 0.3rem;
    }
    .fiex-bottom label{
        margin-left: 0.2rem;
        vertical-align: middle;
    }
    .fiex-bottom .del-cls {
        width: 2.1rem;
        background: #ff5b01;
        color: #fff;
        text-align: center;
    }

    /* prduce */
    .content-cls {
        padding-top: 1.2rem;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        /* Firefox */
        -webkit-box-sizing: border-box;
        /* Safari */
    }

    .content-cls .input-box {
        margin: 0.2rem 0.3rem;
        display: flex;
    }

    .input-box input {
        border: 1px solid #e5e5e5;
        border-radius: 4px;
        line-height: 0.94rem;
        font-size: 0.36rem;
        padding: 0 0.2rem;
        width: 100%;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        /* Firefox */
        -webkit-box-sizing: border-box;
        /* Safari */
    }

    .flex-1 {
        flex: 1;
    }

    .list-cls {
        font-size: 0.28rem;
    }
    .list-cls.isDetele{
        margin-bottom: 1.14rem;
    }

    .list-cls li {
        padding: 0.3rem;
        background: #fff;
        color: #303233;
        margin-bottom: 0.18rem;
    }

    .list-cls li>div {
        display: flex;
        margin-bottom: 0.2rem;
    }

    .list-cls li .img-box img {
        height: 1.55rem;
        width: 1.55rem;
        margin-right: 0.5rem;
    }

    .list-cls li .img-box .flex-1 {
        padding: 0.25rem 0;
    }

    .list-cls li .img-box p {
        color: #db4437;
        line-height: 0.5rem;
        font-weight: bold;
    }

    .list-cls li .img-box p span {
        color: #303233;
    }

    .list-btn button {
        border: 1px solid #ffb100;
        border-radius: 20px;
        font-size: 0.26rem;
        margin-left: 0.28rem;
        line-height: 0.54rem;
        padding: 0 0.2rem;
        color: #ffb100;
        background: #fff;
    }

    .list-btn button.full-btn {
        color: #fff;
        background: #ffb100;
    }

    /* order_MGT */
    .ul-list{
        font-size: 0.34rem;
        background: #fff;
    }
    .ul-list li{
        line-height: 1.44rem;
        text-align: center;
        border-bottom: 1px solid #e5e5e5;
    }
    .ul-list li:last-child{
        border-bottom: 0;
    }

    /* order_assign */
    .assign-cls{
        color: #303233;
    }
    .assign-cls .ul-head{
        text-align: center;
        font-size: 0.4rem;
        position: relative;
        height: 1.44rem;
        line-height: 1.44rem;
        color: #fff;
    }
    .assign-cls .ul-head .msg-cls{
        padding-top: 0.28rem;
        line-height: 0.48rem;
    }
    .assign-cls .ul-head p{
        font-size: 0.3rem;
        width: 70%;
        display: inline-block;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }
    .assign-cls .ul-head .iconfont{
        position: absolute;
        right: 0.2rem;
        top: 0;
        font-size: 0.4rem;
    }
    .assign-cls .ul-head.select-cls .iconjiahao:before{
        content: '\e729';
    }
    .assign-cls li{
        font-size: 0.32rem;
        line-height: 1.14rem;
    }
    .assign-cls li.active{
        background: #eeeeee;
    }
    .assign-cls .ul-list:first-child .ul-head{
        background: #9ecff0;
    }
    .assign-cls .ul-list:nth-child(2) .ul-head{
        background: #8daef6;
    }
    .assign-cls .ul-head~ul{
        display: none;
    }
    .assign-cls .ul-head.select-cls~ul{
        display: block;
    }
    .assign-cls .fiex-bottom{
        text-align: center;
        background: #ff5b01;
        color: #fff;
    }
</style>
</head>
<body>
    <div class="toolbar" id="toolbar" style="z-index: 1000">
        <div id="open-sb" class="menu-button menu-left"></div>
    </div>
    <section class="sidebar">
        <div class="subNav1" style="background-color: #4285f4;">

                <img src="<?php echo ADMIN_STYLE_URL; ?>images/logo.png" />

            <a href="<?php echo url('/admin/login/logout'); ?>" style="color: #000; margin: 11px;">退出</a>
        </div>
        <!--<hr />-->
        <!--<div class="subNav"><h6>订单</h6></div>
        <ul class="navContent">
            <li><a href="#"></a></li>
        </ul>-->
        <?php if(is_array($menu) || $menu instanceof \think\Collection || $menu instanceof \think\Paginator): $mkey = 0; $__LIST__ = $menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$value): $mod = ($mkey % 2 );++$mkey;$menuKey = $key; if($menuKey=='menu1'): ?>
        <div class="subNav <?php if($currentMenu['menu']==$key): endif; ?>"><h6><?php echo $value['title']; ?></h6></div>
        <?php if(!empty($value['nav'])): ?>
        <ul class="navContent">
            <?php if(is_array($value['nav']) || $value['nav'] instanceof \think\Collection || $value['nav'] instanceof \think\Paginator): $navKey = 0; $__LIST__ = $value['nav'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$li): $mod = ($navKey % 2 );++$navKey;$nKey = $key; if($nKey == 'nav3' || $nKey == 'nav6' || $nKey == 'nav0'): ?>
            <a href="<?php echo $li['url']; ?>"><li><?php echo $li['title']; ?></li></a>
            <?php endif; endforeach; endif; else: echo "" ;endif; ?>
        </ul>
        <?php endif; endif; if($menuKey=='menu13'): ?>
        <div class="subNav <?php if($currentMenu['menu']==$key): endif; ?>"><h6><?php echo $value['title']; ?></h6></div>
        <?php if(!empty($value['nav'])): ?>
        <ul class="navContent">
            <?php if(is_array($value['nav']) || $value['nav'] instanceof \think\Collection || $value['nav'] instanceof \think\Paginator): $navKey = 0; $__LIST__ = $value['nav'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$li): $mod = ($navKey % 2 );++$navKey;$nKey = $key; if($nKey == 'nav8' || $nKey == 'nav9'): ?>
            <a href="<?php echo $li['url']; ?>"><li><?php echo $li['title']; ?></li></a>
            <?php endif; endforeach; endif; else: echo "" ;endif; ?>
        </ul>
        <?php endif; endif; if($menuKey=='menu8'): ?>
        <div class="subNav <?php if($currentMenu['menu']==$key): endif; ?>"><h6><?php echo $value['title']; ?></h6></div>
        <?php if(!empty($value['nav'])): ?>
        <ul class="navContent">
            <?php if(is_array($value['nav']) || $value['nav'] instanceof \think\Collection || $value['nav'] instanceof \think\Paginator): $navKey = 0; $__LIST__ = $value['nav'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$li): $mod = ($navKey % 2 );++$navKey;$nKey = $key; if($nKey == 'nav8' || $nKey == 'nav9'): ?>
            <a href="<?php echo $li['url']; ?>"><li><?php echo $li['title']; ?></li></a>
            <?php endif; endforeach; endif; else: echo "" ;endif; ?>
        </ul>
        <?php endif; endif; endforeach; endif; else: echo "" ;endif; ?>
    </section>
    <div class="canvas">

        <div class="canvas_title do-clear">    <ul class="tab_btn tab_btn_fl fl">                <li><a href="<?php echo url('/admin/epwarehouse/warehouseAdd'); ?>" >添加</a></li>        <li>            <select id="factory_pid" name="factory_pid">                <option value="5" <?php if($factory_pid == 5): ?>selected<?php endif; ?>>全部仓库</option>                <option value="0" <?php if($factory_pid==0): ?>selected<?php endif; ?>>成品区</option>                <option value="3" <?php if($factory_pid==3): ?>selected<?php endif; ?>>FBA</option>                <option value="1" <?php if($factory_pid==1): ?>selected<?php endif; ?>>半成品区域</option>                <option value="2" <?php if($factory_pid==2): ?>selected<?php endif; ?>>瑕疵品区域</option>            </select>        </li>        <li>            <select id="factory_sid" name="factory_sid">                <option value="1" <?php if($factory_sid==1): ?>selected<?php endif; ?>>免费区</option>                <option value="2" <?php if($factory_sid==2): ?>selected<?php endif; ?>>付费区</option>            </select>        </li>        <li class="input"><input type="text" id="search" value='<?php echo $search; ?>' name="search"       onkeydown="keyFun.key(event,13,search.search,[$('#search'),'<?php echo url('/admin/epwarehouse/lists'); ?>'])"       placeholder="搜索：名称/id" /></li>      <li><a href="javaScript:search.searchArray(['<?php echo url('/admin/epwarehouse/lists'); ?>','search','factory_pid','factory_sid'])">搜索</a></li>    </ul>   </div><div class="canvas_intro">    <table class="productli">        <thead>            <tr>                <th width="10" class="center">#</th>                <th width="10" class="center"><input name="" type="checkbox" value="" id="select" /></th>                <th width="200" >仓库名称</th>                <th width="200" >区域</th>                <th width="120" >垂直（层）</th>                <th width="120" >水平（列）</th>                                <th width="120" class="center">创建时间</th>                                <th class="center">备注</th>                                <th width="80" class="center">状态</th>                <th class="center" width="500">操作</th>            </tr>        </thead>        <tbody>            <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $k = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$value): $mod = ($k % 2 );++$k;?>            <tr><!--                <td><?php echo $k; ?></td>-->                <td><?php echo $value['id']; ?></td>                <td><input name="select" type="checkbox" value="<?php echo $value['id']; ?>" /></td>                <td><?php echo $value['name']; ?></td>                <td><?php echo $value['factorypid_text']; if($value['factorypid_text']=='成品区域'): ?> <?php echo $value['factorysid_text']; endif; ?></td>                <td><?php echo $value['x']; ?></td>                <td><?php echo $value['y']; ?></td>                <td><?php echo $value['create_time']; ?></td>                                <td ><?php echo $value['memo']; ?></td>                                <td class="center radio"><?php if($value['status']=='1'): ?><span>开</span><?php else: ?><span class="close">关</span><?php endif; ?></td>                <td class="center operation">                    <a href="<?php echo url('/admin/epwarehouse/viewin','id='.$value['id']); ?>" >查看仓库</a><!--                    <a href="<?php echo url('/admin/epwarehouse/viewplan','id='.$value['id']); ?>" >仓库规划</a>-->                    <a href="<?php echo url('/admin/epwarehouse/warehouseEdit','id='.$value['id']); ?>">编辑</a>                    <a href="javaScript:ware_open_win.openWindow('<?php echo url('/admin/epwarehouse/viewqrcode'); ?>', <?php echo $value['id']; ?>)">查看货架二维码</a>                    <a href="javaScript:warehousetable.wpdel('all','<?php echo $value['id']; ?>')">删除</a>                </td>            </tr>            <?php endforeach; endif; else: echo "" ;endif; ?>        </tbody>    </table></div><div class="canvas_title do-clear">    <ul class="tab_btn tab_btn_fl fl">    </ul>    <?php echo $pageDiv; ?></div><div class="canvas_title do-clear">    <ul class="tab_btn tab_btn_fl fl">        <li><a href="<?php echo url('/admin/epwarehouse/warehouseAdd'); ?>" >添加</a></li>    </ul>   </div><script>    warehousetable.start();    var ware_open_win = {        windowurl:"",        openWindow:function(url,id){            $("#swfbox").show();            //if(edit_factory.windowurl!=url){            ware_open_win.windowurl=url;            $("#swfbox").html("");            ware_open_win.loadURL({id:id});            //}        },        close: function () {            $("#swfbox").hide();        },        loadURL:function(obj){            $.post(ware_open_win.windowurl,obj,function(data){                $("#swfbox").html(data);            })        },        save_data:function() {            var capacity = $("#capacity").val();            var sort = $("#sort").val();            var id = $("#id").val();            if (capacity.length > 1) {                var first_c = capacity.substring(0,1);                if (first_c == 0) {                    alert('产能请不要以0开头！');                    return;                }            }            if (sort.length > 1) {                var first_s = sort.substring(0,1);                if (first_s == 0) {                    alert('排序请不要以0开头！');                    return;                }            }            $.post("<?php echo url('/admin/product/saveFactory'); ?>",{capacity:capacity, sort:sort, id:id},function(data){                if (data.code == 1000) {                    alert(data.msg);                    window.location.reload();                } else {                    alert(data.msg);                    window.location.reload();                }            })        }    }</script>

    </div>
    <script type="text/javascript">
        $( document ).ready(function() {
            $( '.sidebar' ).simpleSidebar({
                settings: {
                    opener: '#open-sb',
                    wrapper: '.wrapper',
                    animation: {
                        duration: 500,
                        easing: 'easeOutQuint'
                    }
                },
                sidebar: {
                    align: 'left',
                    width: 200,
                    closingLinks: 'a',
                }
            });
        });
    </script>

    <script type="text/javascript">
        $(function(){
            $("#open-sb").click(function () {
                $(".navContent").show();
            });
            $(".subNav").click(function(){
                // 修改数字控制速度， slideUp(500)控制卷起速度
                //$(".navContent").slideToggle(500);
                //$(".navContent").hide();
                $(this).next(".navContent").slideToggle(500);
            });
            $(".header-title").on('click', function () {
                $(window).scrollTop(0);
                $(window).scrollLeft(0);
            });
        });

    </script>

</body>
</html>