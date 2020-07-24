<?php if (!defined('THINK_PATH')) exit(); /*a:6:{s:70:"D:\project\factory\public/../application/admin\view\finance\index.html";i:1584407226;s:60:"D:\project\factory\application\admin\view\common\layout.html";i:1594716292;s:60:"D:\project\factory\application\admin\view\common\member.html";i:1584407225;s:61:"D:\project\factory\application\admin\view\common\topline.html";i:1594716292;s:58:"D:\project\factory\application\admin\view\common\menu.html";i:1584407225;s:61:"D:\project\factory\application\admin\view\common\mapline.html";i:1584407225;}*/ ?>
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
        order.productsearch="<?php echo $searchproduct; ?>".split(",");
        order.factorysearch="<?php echo $searcfactory; ?>".split(",");
    })
    function excelFun(url){
        $.StandardPost(url,{
            search:$("#search").val(),
            start_time:$("#start_date").val(),
            end_time:$("#end_date").val(),
            product_id:order.productsearch,
            factorysearch:order.factorysearch
        });
    }
</script>
<div class="canvas_title do-clear">
    <ul class="tab_btn tab_btn_fl fl">
        <li><a href="javaScript:openModel.openWindow('<?php echo url('/admin/product/openwindows'); ?>')">按产品</a></li>
        <li><a href="javaScript:openModel.openWindow('<?php echo url('/admin/factory/openWindows'); ?>')">按工厂</a></li>
        <li><a href="javaScript:excelFun('<?php echo url('/admin/finance/excel'); ?>')">导出订单Excel</a></li>
        <li><a href="javaScript:alert('还未开始做')">导出材料Excel</a></li>
    </ul>
    <ul class="tab_btn tab_btn_fl fr">
        <li class="date">
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
        <li class="input"><input type="text" id="search" name="search" value="" style="width: 150px;"
      onkeydown="keyFun.key(event,13,order.search,[$('#search'),'<?php echo url('/admin/finance/index'); ?>'])" 
      placeholder="搜索：产品名称/产品ID" /></li>
      <li><a href="javaScript:order.search([$('#search'),'<?php echo url('/admin/finance/index'); ?>'])">搜索</a></li>
    </ul>
</div>
<div class="canvas_intro">
    <table class="productli orderli">
        <thead>
            <tr>
                <th width="10" class="center">#</th>
                <th width="180">订单信息</th>
                <th width="130" class="center">缩略图</th>
                <th width="80" class="center">型号/数量</th>
                <th class="center">备注</th>
                <th class="center" width="200">加工厂/状态</th>
                <th class="center" width="120">时间</th>
            </tr>
        </thead>
        <tbody>
            <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $k = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$value): $mod = ($k % 2 );++$k;?>
            <tr>
                <td class="center"><?php echo $k; ?></td>
                <td class="info">
                    <label>id：</label><?php echo $value['id']; ?><br/>
                    <label>订单号：</label><?php echo $value['OrdNum']; ?><br/>
                    <label>SKU：</label><?php echo $value['GdsSku']; ?><br/>
                    <label>运单号：</label><?php echo $value['TrnNo']; ?><br/>
                    <label>分销商：</label><?php echo $value['AgntName']; ?><br/>
                    <label>来源：</label><?php echo $value['Type']; ?><br/>
                    <label>产品ID：</label><?php echo $value['product_id']; ?><br/>
                </td>
                <td  class="center"><img src='http://kjds-img.img-cn-shanghai.aliyuncs.com/<?php echo explode(',',$value['ImgURL'])[0]; ?>@0e_0o_1l_500h_500w.src' /></td>
                <td class="center num <?php if($value['Urgent']==1): ?>urgent<?php endif; ?>">
                    <span><?php echo $value['SpecName']; ?></span><br/><strong><?php echo $value['GdsNum']; ?>件</strong>
                    <?php if($value['Urgent']==1): ?><br/><strong>!加急</strong><?php endif; ?>
                </td>
                <td>
                    <?php if(!empty($value['OdrMemo'])): ?><span>客户：</span><?php echo $value['OdrMemo']; ?><br/><?php endif; if(!empty($value['FFYMemo'])): ?><span>平台：</span><?php echo $value['FFYMemo']; ?><br/><?php endif; if(!empty($value['SignMemo'])): ?><span>签收：</span><?php echo $value['SignMemo']; endif; ?>
                </td>
                <td class="center">
                    <ul class="factory">
                    <?php $factorys = GetOrderFactory($value['id']); if(is_array($value['orderFactory']) || $value['orderFactory'] instanceof \think\Collection || $value['orderFactory'] instanceof \think\Paginator): $i = 0; $__LIST__ = $value['orderFactory'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$factory): $mod = ($i % 2 );++$i;?>
                    <li>
                        <?php if($factory['sign']==0): $sign_type = '新订单'; $signtime = $value['GetTimer']; elseif($factory['sign']==1): $sign_type = '生产中'; $signtime = $factory['pro_time']; elseif($factory['sign']==2): $sign_type = '已出库'; $signtime = $factory['library_time']; endif; ?>
                        <h4><b><?php if($factory['working_type']=='1'): ?>印花：<?php else: ?>加工：<?php endif; ?></b><?php echo $factory['userinfo']['Name']; ?></h4>
                        <?php if($value['status']==1): ?><span class="stop">暂停</span><i><?php echo date('Y-m-d',strtotime($value['SignTimer'])); ?></i>
                        <?php elseif($value['status']==2): ?><span class="sign">签收</span><i><?php echo date('Y-m-d',strtotime($value['SignTimer'])); ?></i>
                        <?php elseif($value['status']==3): ?><span class="cancel">(<?php echo $sign_type; ?>) 取消</span><i><?php echo date('Y-m-d',strtotime($value['SignTimer'])); ?></i>
                        <?php else: ?>
                        <span><?php echo $sign_type; ?></span><i><?php echo date('Y-m-d',strtotime($signtime)); ?></i>
                        <?php endif; ?>
                    </li>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                    </ul>
                </td>
                <td class="info center" >
                    <label>亚马逊：</label><?php echo date('Y-m-d',strtotime($value['AmzTimer'])); ?><br/>
                    <label>提交：</label><?php echo date('Y-m-d',strtotime($value['GetTimer'])); ?><br/>
                    <?php if($value['status']<2): $endtime = date('Y-m-d h:i:s'); else: $endtime = $value['SignTimer']; endif; ?>
                    <label>时效：</label><strong><?php $alldata = floor((strtotime($endtime)-strtotime($value['GetTimer']))/(3600*24)); if($alldata>0): ?><?php echo $alldata; else: ?>0<?php endif; ?>天</strong>
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
        <h3>修改备注<a href="javaScript:windowoperation.hide($('#tip_windows'))" class="close"><img src="<?php echo ADMIN_STYLE_URL; ?>images/closecut.png" /></a></h3>
        <textarea name="" cols="" rows="" id="message"></textarea>
        <div class="btn"><a href="javaScript:orderoperation.yesclick($('#message').val());">确认</a><a href="javaScript:windowoperation.hide($('#tip_windows'))">取消</a></div>
    </div>
</div>         </div>      </div>   </div>   <div class="swf_edit_box" id="swfbox"></div></body><script type="text/javascript">   $(document).ready(function(){      $("#fba_auto_msg_li").mouseover(function(){         $("#fba_auto_msg_ul").show();         $("#fba_auto_msg_li").mouseout(function(){            $("#fba_auto_msg_ul").hide();         });      });   })   var is_fba_auth = "<?php echo $is_fba_auth; ?>";   if (is_fba_auth == 1) {      $('#fba_btn_mn').click();      var speaks_auth = 1;      get_fba_new_msg();      var start_for = setInterval(function () {         get_fba_new_msg();      }, 150000);   }   //语音播报   function speak_cn(ttsText) {      //var mess = document.getElementById('ttsText').value;      var msg = new SpeechSynthesisUtterance(ttsText);      msg.volume = 100;      msg.rate = 1;      msg.pitch = 1.5;      console.log(msg);      window.speechSynthesis.speak(msg);   }   //获取fba数据   function get_fba_new_msg() {      $.post("<?php echo url('/admin/Fba/get_fba_zx'); ?>", {}, function (sres) {         if (sres['code'] == 1000) {            $('#fba_auto_msg_li').show();            var html = '';            var html_3 = '';            var html_4 = '';            var vol_txt_3 = '';            var vol_txt_4 = '';            if (sres['count_3'] > 0) {               vol_txt_3 = '已封箱' + sres['count_3'] + '件';               html_3 = "<li style='z-index: 999;'><a href=\"javaScript:check_fba_news(3);\">已封箱（" + sres['count_3'] + "件）</a></li>";            }            if (sres['count_4'] > 0) {               vol_txt_4 = '已发货' + sres['count_4'] + '件';               html_4 = "<li style='z-index: 999;'><a href=\"javaScript:check_fba_news(4);\">可发货（" + sres['count_4'] + "件）</a></li>";            }            $("#fba_auto_msg_ul").html(html_3 + html_4);            if (vol_txt_3 != '') {               speak_cn(vol_txt_3);            }            if (vol_txt_4 != '') {               speak_cn(vol_txt_4);            }            //alert(sres['msg']);            //playPause();         }         return;      });   }   //播放   function playPause() {      var music = document.getElementById('fba_music');      if (music.paused) {         music.play();         console.log('play');      } else {         music.pause();         console.log('pause');      }   }   //查看状态   function check_fba_news(plan_status) {      $.post("<?php echo url('/admin/Fba/check_news'); ?>", {plan_status:plan_status}, function (res) {         window.location.href = "<?php echo url('/admin/fba/lists'); ?>" + "?plan_status=" + plan_status;      });   }</script></html>