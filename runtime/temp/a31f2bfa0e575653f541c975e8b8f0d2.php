<?php if (!defined('THINK_PATH')) exit(); /*a:6:{s:76:"D:\project\factory\public/../application/admin\view\ordermaterial\index.html";i:1584407225;s:60:"D:\project\factory\application\admin\view\common\layout.html";i:1594716292;s:60:"D:\project\factory\application\admin\view\common\member.html";i:1584407225;s:61:"D:\project\factory\application\admin\view\common\topline.html";i:1594716292;s:58:"D:\project\factory\application\admin\view\common\menu.html";i:1584407225;s:61:"D:\project\factory\application\admin\view\common\mapline.html";i:1584407225;}*/ ?>
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
</ul>      <div class="rightbox">         <div class="menumap">    <a href="<?php echo url('/user/index'); ?>" class="home">首页</a>    <?php if(isset($menu[$currentMenu['menu']])): ?><a href="javaScript:;"><?php echo $menu[$currentMenu['menu']]['title']; ?></a><?php endif; if(isset($menu[$currentMenu['menu']]['nav'][$currentMenu['nav']])): ?><span><?php echo $menu[$currentMenu['menu']]['nav'][$currentMenu['nav']]['title']; ?></span><?php endif; ?></div>         <div class="canvas">            <link rel="stylesheet" href="<?php echo ADMIN_STYLE_URL; ?>css/date.css" type="text/css" media="screen" />
<script type="text/javascript" src="<?php echo ADMIN_STYLE_URL; ?>js/date.js"></script>
<script>

    $(function(){
        $('.custom-date').dateRangePicker($("input[name='start_date']"),$("input[name='end_date']"));
        ordermaterial.productsearch = "<?php echo $searchproduct; ?>".split(",");
        //order.factorysearch="$searcfactory".split(",");
        select.select();
    })

</script>

<div class="canvas_title do-clear"><!--height_title增加高度-->
    <ul class="tab_nav ordertab fl">
        <!--<li ><a href="<?php echo url('/admin/ordermaterial/index'); ?>">全部</a></li>
        <li ><a href="<?php echo url('/admin/ordermaterial/index','type=2'); ?>">生产中</a></li>
        <li ><a href="<?php echo url('/admin/ordermaterial/index','type=4'); ?>">暂停</a></li>
        <li ><a href="<?php echo url('/admin/ordermaterial/index','type=8'); ?>">有库存</a></li>-->

        <label><input type="checkbox" name="order_status" id="sc" value="2" <?php if(in_array(2, $sign)): ?>checked<?php endif; ?> />生产中&nbsp;&nbsp;</label>
        <label><input type="checkbox" name="order_status" id="zt" value="4" <?php if(in_array(4, $sign)): ?>checked<?php endif; ?> />暂停&nbsp;&nbsp;</label>
        <label><input type="checkbox" name="order_status" id="kc" value="8" <?php if(in_array(8, $sign)): ?>checked<?php endif; ?> />有库存&nbsp;&nbsp;</label>
        <label><input type="checkbox" name="order_status" id="qs" value="5" <?php if(in_array(5, $sign)): ?>checked<?php endif; ?> />已签收&nbsp;&nbsp;</label>
    </ul>
    <ul class="tab_btn tab_btn_fl fr">

        <li><a href="javaScript:ordermaterial.mateial_repost();">批量查看材料使用</a></li>
        <li><a href="javaScript:ordermaterial.openWindow('<?php echo url('/admin/ordermaterial/openwindows'); ?>')">选择产品</a></li>
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
        <li class="input"></li>
        <li><a href="javaScript:ordermaterial.search([$('#search'),'<?php echo url('/admin/ordermaterial/index'); ?>'])">按订单</a></li>
        <li><a href="javaScript:ordermaterial.product_material([$('#search'),'<?php echo url('/admin/ordermaterial/productmaterial'); ?>'])">按产品</a></li>
    </ul>
</div>

<div class="canvas_intro">

    <table class="productli orderli">
        <thead>
        <tr>
            <th width="10" class="center">#</th>
            <th width="20" class="center"><input name="" type="checkbox" value="" id="select" /></th>
            <th width="130" class="">订单信息</th>
            <th width="130" class="">缩略图</th>
            <th width="150" class="">材料使用</th>
        </tr>
        </thead>
        <tbody>
        <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $k = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$value): $mod = ($k % 2 );++$k;?>
        <tr>
            <td class="center"><?php echo $k; ?></td>
            <td class="center">
                <input name="select" type="checkbox" value="<?php echo $value['id']; ?>" data-odrid="<?php echo $value['OdrId']; ?>" />

            </td>
            <td class="info">

                <label>id：</label><?php echo $value['id']; ?><br/>
                <label>订单号：</label><?php echo $value['OrdNum']; ?><br/>
                <label>SKU：</label><?php echo $value['GdsSku']; ?><br/>
                <label>数量：</label><?php echo $value['GdsNum']; ?><br/>
                <label>商品型号：</label><?php echo $value['SpecName']; ?><br/>
                <label>分销商：</label><?php echo $value['AgntName']; ?><br/>
                 <label>商品名称：</label><?php echo $value['product_name']; ?><br/>
                <label>产品ID：</label><?php echo $value['product_id']; ?><br/>

            </td>
            <td  class=""><img src='http://kjds-img.img-cn-shanghai.aliyuncs.com/<?php echo explode(',',$value['ImgURL'])[0]; ?>@0e_0o_1l_500h_500w.src' /></td>

            <td>
                <?php if(is_array($value['manumaterial']) || $value['manumaterial'] instanceof \think\Collection || $value['manumaterial'] instanceof \think\Paginator): $mkey = 0; $__LIST__ = $value['manumaterial'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$mvalue): $mod = ($mkey % 2 );++$mkey;?>

                <label>[<?php echo $mvalue['material']['finance_num']; ?>]<?php echo $mvalue['material']['name']; ?>:&nbsp;&nbsp;</label><span style="color: #990000;"><?php echo $mvalue['dosage']; ?></span><?php echo $mvalue['material']['company']; ?><!--（材料型号：$mvalue.material.size）--><br/>

                <?php endforeach; endif; else: echo "" ;endif; ?>
            </td>
        </tr>
        <?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
    </table>

</div>

<div class="canvas_title do-clear">
    <?php echo $pageDiv; ?>
</div>

<div class="message" id="tip_windows">

    <div class="box">

        <h3>修改备注<a href="javaScript:" class="close"><img src="" /></a></h3>

        <textarea name="" cols="" rows="" id="message"></textarea>

        <div class="btn"><a href="javaScript:">确认</a><a href="javaScript:">取消</a></div>

    </div>

</div>

<script>
    var ordermaterial = {
        productsearch:[],
        order_search:[],

        search: function (obj) {
            //var value = obj[0].val();
            $('input:checkbox[name=order_status]:checked').each(function(k){
                if($(this).val()!='') ordermaterial.order_search.push($(this).val());
            });
            if (ordermaterial.order_search.length <= 0) {
                alert('请选择订单状态');
                return;
            }
            $.StandardPost(obj[1], {
                sdate: $("#date").val(),
                start_time: $("#start_date").val(),
                end_time: $("#end_date").val(),
                product_id: ordermaterial.productsearch,
                signtype: ordermaterial.order_search,
            });
        },
        product_material: function (obj) {
            //var value = obj[0].val();
            $('input:checkbox[name=order_status]:checked').each(function(k){
                if($(this).val()!='') ordermaterial.order_search.push($(this).val());
            });
            if (ordermaterial.order_search.length <= 0) {
                alert('请选择订单状态');
                return;
            }
            $.StandardPost(obj[1], {
                sdate: $("#date").val(),
                start_time: $("#start_date").val(),
                end_time: $("#end_date").val(),
                product_id: ordermaterial.productsearch,
                signtype: ordermaterial.order_search,
            });
        },
        //计算材料
        mateial_repost: function() {
            var order_ids = [];

            $('input:checkbox[name=select]:checked').each(function(k){
                if($(this).val()!='') order_ids.push($(this).val());
            });
            if(order_ids.length==0){
                alert("请选择要计算的物品");
                return;
            }
            var ids = order_ids.join(',');
            $.StandardPost("<?php echo url('/admin/ordermaterial/materialReport'); ?>", {
                //sdate: $("#date").val(),
                //start_time: $("#start_date").val(),
                //end_time: $("#end_date").val(),
                ids:ids,
            });
        },

        windowurl:"",

        openWindow:function(url){
            $("#swfbox").show();
            if(ordermaterial.windowurl!=url){
                ordermaterial.windowurl=url;
                $("#swfbox").html("");
                ordermaterial.loadURL({});
            }
        },

        loadURL:function(obj){
            $.post(ordermaterial.windowurl,obj,function(data){
                $("#swfbox").html(data);
            })
        },

        selectproduct:function(){
            ordermaterial.productsearch=[];
            $("#model_li input[type='checkbox']:checked").each(function(){
                ordermaterial.productsearch.push($(this).val());
            });
            $('input:checkbox[name=order_status]:checked').each(function(k){
                if($(this).val()!='') ordermaterial.order_search.push($(this).val());
            });
            $("#swfbox").hide();
            //ordermaterial.search();
        },

        pro_search:function(){
            var value = $("#window_search").val();
            ordermaterial.loadURL({search:value});
        }
    };

    function pro_search(){
        var value = $("#window_search").val();
        ordermaterial.loadURL({search:value});
    }

    function selectproduct() {
        ordermaterial.productsearch=[];
        $("#model_li input[type='checkbox']:checked").each(function(){
            ordermaterial.productsearch.push($(this).val());
        });
        $('input:checkbox[name=order_status]:checked').each(function(k){
            if($(this).val()!='') ordermaterial.order_search.push($(this).val());
        });
        $("#swfbox").hide();
        //ordermaterial.search();
    }
</script>         </div>      </div>   </div>   <div class="swf_edit_box" id="swfbox"></div></body><script type="text/javascript">   $(document).ready(function(){      $("#fba_auto_msg_li").mouseover(function(){         $("#fba_auto_msg_ul").show();         $("#fba_auto_msg_li").mouseout(function(){            $("#fba_auto_msg_ul").hide();         });      });   })   var is_fba_auth = "<?php echo $is_fba_auth; ?>";   if (is_fba_auth == 1) {      $('#fba_btn_mn').click();      var speaks_auth = 1;      get_fba_new_msg();      var start_for = setInterval(function () {         get_fba_new_msg();      }, 150000);   }   //语音播报   function speak_cn(ttsText) {      //var mess = document.getElementById('ttsText').value;      var msg = new SpeechSynthesisUtterance(ttsText);      msg.volume = 100;      msg.rate = 1;      msg.pitch = 1.5;      console.log(msg);      window.speechSynthesis.speak(msg);   }   //获取fba数据   function get_fba_new_msg() {      $.post("<?php echo url('/admin/Fba/get_fba_zx'); ?>", {}, function (sres) {         if (sres['code'] == 1000) {            $('#fba_auto_msg_li').show();            var html = '';            var html_3 = '';            var html_4 = '';            var vol_txt_3 = '';            var vol_txt_4 = '';            if (sres['count_3'] > 0) {               vol_txt_3 = '已封箱' + sres['count_3'] + '件';               html_3 = "<li style='z-index: 999;'><a href=\"javaScript:check_fba_news(3);\">已封箱（" + sres['count_3'] + "件）</a></li>";            }            if (sres['count_4'] > 0) {               vol_txt_4 = '已发货' + sres['count_4'] + '件';               html_4 = "<li style='z-index: 999;'><a href=\"javaScript:check_fba_news(4);\">可发货（" + sres['count_4'] + "件）</a></li>";            }            $("#fba_auto_msg_ul").html(html_3 + html_4);            if (vol_txt_3 != '') {               speak_cn(vol_txt_3);            }            if (vol_txt_4 != '') {               speak_cn(vol_txt_4);            }            //alert(sres['msg']);            //playPause();         }         return;      });   }   //播放   function playPause() {      var music = document.getElementById('fba_music');      if (music.paused) {         music.play();         console.log('play');      } else {         music.pause();         console.log('pause');      }   }   //查看状态   function check_fba_news(plan_status) {      $.post("<?php echo url('/admin/Fba/check_news'); ?>", {plan_status:plan_status}, function (res) {         window.location.href = "<?php echo url('/admin/fba/lists'); ?>" + "?plan_status=" + plan_status;      });   }</script></html>