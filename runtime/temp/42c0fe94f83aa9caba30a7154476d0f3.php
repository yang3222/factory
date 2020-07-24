<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:74:"D:\project\factory\public/../application/admin\view\member\auth_group.html";i:1584407225;s:67:"D:\project\factory\application\admin\view\common\mobile_layout.html";i:1587535506;}*/ ?>
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
    .toolbar{position:fixed;left:10px;bottom:65px;background-color:transparent;line-height:68px;}
    .menu-button{position:relative;margin:5px;height:36px;width:36px;cursor:pointer;}
    .menu-button:before{content:"";position:absolute;top:5px;right:3px;border-bottom:17px double rgba(0,0,0,.7);border-top:6px solid rgba(0,0,0,.7);width:30px;height:5px;}
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

        <style>
    .edit_tree_box {
        position: fixed;
        left: 0;
        top: 0;
        display: none;
        height: 10000px;
        background: rgba(0,0,0,0.3);
        width: 100%;
        z-index: 100;
    }
</style>

<script>
    $(function() {
        //checkFun.init($("#selecttitle"), $("input[name='select']"));
    })
</script>
<link rel="stylesheet" href="/static/jstree/themes/default/style.min.css" type="text/css">
<div class="canvas_title do-clear">
    <ul class="tab_btn tab_btn_fl fl">
        <li><a href="javaScript:openeditgroup.openWindow('<?php echo url('/admin/member/editGroup'); ?>')">添加</a></li>
    </ul>
</div>
<div class="canvas_intro">
    <table class="productli">
        <thead>
        <tr>
            <th width="10" class="center">#</th>
            <th width="150" class="center">组名</th>
            <th width="350" class="center">规则id</th>
            <!--<th class="center" width="200">是否禁用</th>-->
            <th class="center" width="200">管理组类型</th>
            <th class="center" width="100">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php if(is_array($authgroup) || $authgroup instanceof \think\Collection || $authgroup instanceof \think\Paginator): $k = 0; $__LIST__ = $authgroup;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$value): $mod = ($k % 2 );++$k;?>
        <tr class="navline">
            <td class="center"><?php echo $k; ?></td>
            <td class="center"><?php echo $value['title']; ?></td>
            <td class="center" style=""><?php if(empty($value['rules']) || mb_strlen($value['rules']) <= 50): ?><?php echo $value['rules']; else: ?><?php echo msubstr($value['rules'],0,50,'utf-8',true); endif; ?></td>
            <td class="center"><?php if($value['types'] == 1): ?>管理员<?php else: ?>工厂<?php endif; ?></td>
            <td class="center operation">
                <a href="javaScript:openeditgroup.openWindow('<?php echo url('/admin/member/editGroup'); ?>',<?php echo $value['id']; ?>)">编辑</a>
                <a href="javaScript:openeditgroup.openView('<?php echo url('/admin/member/accControl'); ?>',<?php echo $value['id']; ?>,<?php echo $value['types']; ?>)">访问控制</a>
            </td>
        </tr>
        <?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
    </table>
</div>
<div class="canvas_title do-clear">
    <ul class="tab_btn tab_btn_fl fl">
        <li><a href="javaScript:openeditgroup.openWindow('<?php echo url('/admin/member/editGroup'); ?>')">添加</a></li>
    </ul>
</div>
<input type="hidden" id="gro_id" name="gro_id" value="">
<div id="hiddentree" class="edit_tree_box">
<div class="modelbox" style="width: 298px;">
    <div class="canvas_title do-clear">
        <ul class="tab_btn fr">
            <li><a href="javaScript:openeditgroup.closetree('hiddentree')">关闭</a></li>
        </ul>
    </div>
    <div class="canvas_intro">
        <div id="tree" class="tree_c"></div>

    </div>


    <div class="canvas_title do-clear">
        <ul class="tab_btn fr">

            <li><a href="javaScript:openeditgroup.editRules('tree')">保存</a></li>


        </ul>
    </div>
</div>
</div>

<div id="hiddentreeuser" class="edit_tree_box">
    <div class="modelbox" style="width: 298px;">
        <div class="canvas_title do-clear">
            <ul class="tab_btn fr">
                <li><a href="javaScript:openeditgroup.closetree('hiddentreeuser')">关闭</a></li>
            </ul>
        </div>
        <div class="canvas_intro">
            <div id="treeuser" class="tree_c"></div>

        </div>

        <div class="canvas_title do-clear">
            <ul class="tab_btn fr">

                <li><a href="javaScript:openeditgroup.editRules('treeuser')">保存</a></li>


            </ul>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<!--<script type="text/javascript" src="/static/admin/js/jquery.min.js"></script>-->
<script type="text/javascript" src="/static/jstree/jstree.js"></script>

<script>
    triggerTree('tree');
    triggerTree('treeuser');
    //groTrees();
    function groTrees(gro_id,type) {
        $.post("<?php echo url('/admin/member/accControl'); ?>", {id:gro_id}, function (res) {
            var rules = res.rules.split(',');
            $('#' + type).jstree("uncheck_all");  //清除所有选中
            $('#' + type).jstree('select_node', [rules], true); //选中指定节点
            $('#' + type).jstree().close_all(); //关闭所有节点
        });

    }

    function triggerTree(css_id) {

        $('#' + css_id).jstree({
            'core': {
                'data': {
                    'method': "GET",
                    'url': "<?php echo url('/admin/member/accControl'); ?>" + '?types=' + css_id,
                },
            },
            'checkbox': {
                "visible": true,
                "keep_selected_style": false,
                'three_state': false

            },
            'plugins': ['checkbox']
        });
    }

    //打开模型选择界面

    var openeditgroup = {

        windowurl:"",

        //编辑规则
        editRules:function(css_id) {
            var rules = $("#" + css_id).jstree("get_checked");

            var gro_id = $('#gro_id').val();
            if (gro_id == '') {
                alert('参数缺失请重试！');
                return;
            }

            $.post("<?php echo url('/admin/member/editRules'); ?>", {id:gro_id, rules:rules}, function (res) {
                if(res.code == 1000) {
                    alert(res.msg);
                    //$("#hiddentree").hide();
                    window.location = "<?php echo url('/admin/member/authGroup'); ?>";
                } else {
                    alert(res.msg);
                    window.location = "<?php echo url('/admin/member/authGroup'); ?>";
                }

            });
        },

        //打开菜单树
        openView:function(u,id,types) {
            if (types == 1) {
                var type = 'tree';
                var typehidden = 'hiddentree';
            } else if(types == 2) {
                var type = 'treeuser';
                var typehidden = 'hiddentreeuser';
            }
            groTrees(id,type);
            //var htmls = $("#hiddentree").html();
            //console.log(htmls);
            $('#gro_id').val(id);
            setTimeout(function (){$("#" + typehidden).show();}, 1500);
            //$("#swfbox").html(htmls);

            //$("#swfbox").show();
        },

        openWindow:function(url,id = null){

            $("#swfbox").show();

            //if(openeditgroup.windowurl!=url){

                openeditgroup.windowurl=url;

                $("#swfbox").html("");

                openeditgroup.loadURL({id:id});

           // }

        },

        close: function () {

            $("#swfbox").hide();

        },

        closetree: function(css_id) {
            $('#gro_id').val('');
            $("#" + css_id).hide();
        },

        loadURL:function(obj){

            $.post(openeditgroup.windowurl,obj,function(data){

                $("#swfbox").html(data);

            })

        },

        search:function(){

            var value=$("#window_search").val();

            openeditgroup.loadURL({search:value});

        },

        save_name:function() {
            var title_name = $("#title_name").val().replace(/^\s*|\s*$/g,"");//去空格
            var types = $("#types").val();
            if (title_name == '') {
                alert('名称不可为空！');
                return;
            }

            $.post("<?php echo url('admin/member/saveGroup'); ?>",{title:title_name, types:types},function(data){

                if(data.code == 1000) {
                    alert(data.msg);
                    window.location = "<?php echo url('admin/member/authGroup'); ?>";
                } else {
                    alert(data.msg);
                    window.location = "<?php echo url('admin/member/authGroup'); ?>";
                }

            })
        },
        //编辑名称
        update_name:function() {
            var id = $("#id").val();
            var title_name = $("#title_name").val().replace(/^\s*|\s*$/g,"");//去空格
            var types = $("#types").val();

            if (title_name == '') {
                alert('名称不可为空！');
                return;
            }
            if (id == '' || isNaN(id)) {
                alert('id参数缺失，请重试！');
                window.location = "<?php echo url('admin/member/authGroup'); ?>";
                return;
            }
            $.post("<?php echo url('admin/member/updateGroup'); ?>",{title:title_name, id:id, types:types},function(data){

                if(data.code == 1000) {
                    alert(data.msg);
                    window.location = "<?php echo url('admin/member/authGroup'); ?>";
                } else {
                    alert(data.msg);
                    window.location = "<?php echo url('admin/member/authGroup'); ?>";
                }

            })
        }

    }
</script>


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