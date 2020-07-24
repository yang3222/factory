<?php if (!defined('THINK_PATH')) exit(); /*a:6:{s:74:"D:\project\factory\public/../application/admin\view\member\auth_group.html";i:1594969025;s:60:"D:\project\factory\application\admin\view\common\layout.html";i:1594716292;s:60:"D:\project\factory\application\admin\view\common\member.html";i:1584407225;s:61:"D:\project\factory\application\admin\view\common\topline.html";i:1594716292;s:58:"D:\project\factory\application\admin\view\common\menu.html";i:1584407225;s:61:"D:\project\factory\application\admin\view\common\mapline.html";i:1584407225;}*/ ?>
<!doctype html><html><head><meta charset="utf-8"><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"><meta name="viewport" content="width=device-width, initial-scale=1.0"><meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0,  user-scalable=0" name="viewport" /><title>管理后台</title><link rel="stylesheet" href="<?php echo ADMIN_STYLE_URL; ?>css/r.css" type="text/css" media="screen" /><link rel="stylesheet" href="<?php echo ADMIN_STYLE_URL; ?>css/style.css?v=1.0" type="text/css" media="screen" /><script type="text/javascript" src="<?php echo ADMIN_STYLE_URL; ?>js/jquery.min.js"></script><script type="text/javascript" src="<?php echo LAYER_JS_URL; ?>layer.js"></script><?php if(isset($eventJS)): ?><script type="text/javascript" src="<?php echo ADMIN_STYLE_URL; ?>js/<?php echo $eventJS; ?>.js"></script><?php endif; ?><script type="text/javascript" src="<?php echo ADMIN_STYLE_URL; ?>js/move.js"></script></head><body class="content">   <div class="topline">
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
   <div class="contentbox do-clear">      <ul class="menubox">
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
</ul>      <div class="rightbox">         <div class="menumap">    <a href="<?php echo url('/user/index'); ?>" class="home">首页</a>    <?php if(isset($menu[$currentMenu['menu']])): ?><a href="javaScript:;"><?php echo $menu[$currentMenu['menu']]['title']; ?></a><?php endif; if(isset($menu[$currentMenu['menu']]['nav'][$currentMenu['nav']])): ?><span><?php echo $menu[$currentMenu['menu']]['nav'][$currentMenu['nav']]['title']; ?></span><?php endif; ?></div>         <div class="canvas">            <style>
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
            $('#' + type).jstree('select_node', [rules], false); //选中指定节点
            // $('#' + type).jstree('select_all'); //选中指定节点
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
                'three_state': true

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
         </div>      </div>   </div>   <div class="swf_edit_box" id="swfbox"></div></body><script type="text/javascript">   $(document).ready(function(){      $("#fba_auto_msg_li").mouseover(function(){         $("#fba_auto_msg_ul").show();         $("#fba_auto_msg_li").mouseout(function(){            $("#fba_auto_msg_ul").hide();         });      });   })   var is_fba_auth = "<?php echo $is_fba_auth; ?>";   if (is_fba_auth == 1) {      $('#fba_btn_mn').click();      var speaks_auth = 1;      get_fba_new_msg();      var start_for = setInterval(function () {         get_fba_new_msg();      }, 150000);   }   //语音播报   function speak_cn(ttsText) {      //var mess = document.getElementById('ttsText').value;      var msg = new SpeechSynthesisUtterance(ttsText);      msg.volume = 100;      msg.rate = 1;      msg.pitch = 1.5;      console.log(msg);      window.speechSynthesis.speak(msg);   }   //获取fba数据   function get_fba_new_msg() {      $.post("<?php echo url('/admin/Fba/get_fba_zx'); ?>", {}, function (sres) {         if (sres['code'] == 1000) {            $('#fba_auto_msg_li').show();            var html = '';            var html_3 = '';            var html_4 = '';            var vol_txt_3 = '';            var vol_txt_4 = '';            if (sres['count_3'] > 0) {               vol_txt_3 = '已封箱' + sres['count_3'] + '件';               html_3 = "<li style='z-index: 999;'><a href=\"javaScript:check_fba_news(3);\">已封箱（" + sres['count_3'] + "件）</a></li>";            }            if (sres['count_4'] > 0) {               vol_txt_4 = '已发货' + sres['count_4'] + '件';               html_4 = "<li style='z-index: 999;'><a href=\"javaScript:check_fba_news(4);\">可发货（" + sres['count_4'] + "件）</a></li>";            }            $("#fba_auto_msg_ul").html(html_3 + html_4);            if (vol_txt_3 != '') {               speak_cn(vol_txt_3);            }            if (vol_txt_4 != '') {               speak_cn(vol_txt_4);            }            //alert(sres['msg']);            //playPause();         }         return;      });   }   //播放   function playPause() {      var music = document.getElementById('fba_music');      if (music.paused) {         music.play();         console.log('play');      } else {         music.pause();         console.log('pause');      }   }   //查看状态   function check_fba_news(plan_status) {      $.post("<?php echo url('/admin/Fba/check_news'); ?>", {plan_status:plan_status}, function (res) {         window.location.href = "<?php echo url('/admin/fba/lists'); ?>" + "?plan_status=" + plan_status;      });   }</script></html>