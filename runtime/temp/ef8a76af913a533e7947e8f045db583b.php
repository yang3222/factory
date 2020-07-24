<?php if (!defined('THINK_PATH')) exit(); /*a:6:{s:70:"D:\project\factory\public/../application/admin\view\fba\code_pick.html";i:1595235587;s:60:"D:\project\factory\application\admin\view\common\layout.html";i:1594716292;s:60:"D:\project\factory\application\admin\view\common\member.html";i:1584407225;s:61:"D:\project\factory\application\admin\view\common\topline.html";i:1594716292;s:58:"D:\project\factory\application\admin\view\common\menu.html";i:1584407225;s:61:"D:\project\factory\application\admin\view\common\mapline.html";i:1584407225;}*/ ?>
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
</ul>      <div class="rightbox">         <div class="menumap">    <a href="<?php echo url('/user/index'); ?>" class="home">首页</a>    <?php if(isset($menu[$currentMenu['menu']])): ?><a href="javaScript:;"><?php echo $menu[$currentMenu['menu']]['title']; ?></a><?php endif; if(isset($menu[$currentMenu['menu']]['nav'][$currentMenu['nav']])): ?><span><?php echo $menu[$currentMenu['menu']]['nav'][$currentMenu['nav']]['title']; ?></span><?php endif; ?></div>         <div class="canvas">            
<link rel="stylesheet" href="<?php echo ADMIN_STYLE_URL; ?>css/date.css" type="text/css" media="screen" />
<script type="text/javascript" src="<?php echo ADMIN_STYLE_URL; ?>js/date.js"></script>
<style>
    .fbali th {
        border-width: 1px;
        border-style: solid;
        border-color: #e6e6e6;
    }
    .fbali td {
        border-width: 1px;
        border-style: solid;
        border-color: #e6e6e6;
    }
    .openyes {
        display: inline-block;
        height: 22px;
        line-height: 22px;
        padding: 0 10px;
        -moz-border-radius: 4px;
        -webkit-border-radius: 4px;
        border-radius: 4px;
        background: #5bb75b;
        color: #fff;
        font-size: 12px;
    }
    td.info{
        vertical-align: top;
    }
    td.timer{text-align: center;}
    td.timer span{
        display:inline-block;
        padding:5px 10px;
        border-radius: 4px;
        margin-top:5px;
    }
    td.alldate strong{
        font-size:24px;
        font-weight:bold;
    }
    .closeno {
        display: inline-block;
        height: 22px;
        line-height: 22px;
        padding: 0 10px;
        -moz-border-radius: 4px;
        -webkit-border-radius: 4px;
        border-radius: 4px;
        background: #e80c0c;
        color: #fff;
        font-size: 12px;
    }
    .trBox {
        padding: 10px;
        height: 60px;
        animation: fade 1000ms infinite;
        -webkit-animation: fade 1000ms infinite;
    }
    @keyframes fade {
        from {
            opacity: 1.0;
        }
        50% {
            opacity: 0.4;
        }
        to {
            opacity: 1.0;
        }
    }

    @-webkit-keyframes fade {
        from {
            opacity: 1.0;
        }
        50% {
            opacity: 0.4;
        }
        to {
            opacity: 1.0;
        }
    }
    .yitijiao {
        color: #1db100;
    }
    .yijieshou {
        color: #9e19dc;
    }
    .yizhuangxiang {
        color: #e73737;
    }
    .yifahuo {
        color: #374ce7;
    }
    .yiwancheng {
        color: #dc9a19;
    }
    .yiquxiao {
        color: #bdbdbd;
    }

</style>
<script>

    $(function(){

        //jeDate("#purchases_date",{
        //format: "YYYY-MM-DD"
        //});
        //jeDate("#purchases_date",{
        //multiPane:false,
        //theme:{bgcolor:"#00A1CB",color:"#ffffff", pnColor:"#00CCFF"},
        //format: "YYYY-MM-DD hh:mm:ss"
        //});
    })


</script>
<input type="hidden" id="search_sku" value="<?php echo $search_sku; ?>"/>
<div class="canvas_title do-clear">
    <ul class="tab_btn tab_btn_fl fl">
        <li class="input"><input type="text" id="search" name="search" value=""

                                 onkeydown="keys_press();"

                                 placeholder="箱标" /></li>

        <li><a href="javaScript:search();">搜索</a></li>
    </ul>

    <ul class="tab_btn tab_btn_fl fr">

    </ul>

</div>

<div class="canvas_intro">

    <table class="productli fbali">

        <thead>

        <tr>

            <th width="10" class="center">#</th>
            <th style="width:180px;">订单信息</th>
            <th class="center">订单状态</th>
            <th class="center">时效</th>
            <th class="center">备注</th>
            <th class="center" width="120">SKU<br/>(已拣/需拣数量）</th>
            <th class="center">图片</th>
            <th class="center" width="200">操作</th>

        </tr>

        </thead>

        <tbody>

        <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $k = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$value): $mod = ($k % 2 );++$k;?>

        <tr id="fab_list" class="fba_list <?php if($value['remind_status'] == 1): ?>trBox<?php else: endif; ?>">

        <td><?php echo $k; ?></td>

        <td class="info">
            <label>箱标：</label>B<?php echo $value['id']; ?><br/>
            <label>FBA货件号：</label><?php echo $value['fba_nums']; ?><br/>
            <label>业务员：</label><?php echo $value['contact']; ?><br/>
            <label>店铺名：</label><?php echo $value['shop_name']; ?><br/>
        </td>
        <td class="center <?php echo $plan_status_color[$value['plan_status']]; ?>" style="font-weight: bold;font-size: 16px;"><?php echo $plan_status_name[$value['plan_status']]; ?></td>
        <td class="center alldate">
            <?php if($value['plan_status']<5): $endtime = date('Y-m-d h:i:s'); else: $endtime = $value['success_time']; endif; ?>
            <strong><?php $alldata = floor((strtotime($endtime)-strtotime($value['create_time']))/(3600*24)); if($alldata>0): ?><?php echo $alldata; else: ?>0<?php endif; ?></strong>天
        </td>
        <td  style="max-width:200px; word-break:break-all; vertical-align: top; padding-top: 0;"><?php echo $value['note']; ?></td>
        <td style="font-size: 16px;">
            <?php echo $value['order']['sku']; ?><br/>
            <span id="order_picking_num_<?php echo $value['id']; ?>_<?php echo $value['order']['id']; ?>"><?php echo $value['order']['picking_num']; ?></span>/<?php echo $value['order']['number']; ?>
        </td>

        <td class="center" width="130">
            <img src='http://kjds-img.img-cn-shanghai.aliyuncs.com/<?php echo explode(',',$value['order']['img'])[0]; ?>@0e_0o_1l_500h_500w.src' />
        </td>

        <td class="center operation">
            <ul class="do-clear operation">
                <?php if($value['plan_status'] >= 1): ?>
                <li><a href="javaScript:checkFba(<?php echo $value['id']; ?>);">查看详情</a></li>
                <?php endif; if($value['plan_status'] == 1): ?>
                <li><a href="javaScript:startPlan(<?php echo $value['id']; ?>);">开始计划</a></li>
                <?php endif; if($value['plan_status'] == 2): ?>
                <li><a href="javaScript:code_pick_nums('<?php echo $search_sku; ?>', <?php echo $value['id']; ?>, <?php echo $value['order']['id']; ?>);">拣货</a></li>
                <?php endif; ?>
            </ul>
        </td>
        </tr>

        <?php endforeach; endif; else: echo "" ;endif; ?>

        </tbody>

    </table>

</div>

<div class="canvas_title do-clear">

    <ul class="tab_btn tab_btn_fl fl">
        <!--<li><a href="javaScript:audit.approvedall('<?php echo url('/admin/audit/approved'); ?>')">批量通过</a></li>
        <li><a class="close" href="javaScript:audit.deleteall('<?php echo url('/admin/audit/delete'); ?>')">批量不通过</a></li>-->
    </ul>


</div>

<script type="text/javascript">
    /*laydate.render({
        elem: '#inptime'
        ,type: 'datetime'
        ,range: '~'
        ,format: 'yyyy-MM-dd HH:mm:ss'
        ,theme: '#70afc4'
    });*/
    //全选
    document.getElementById('search').focus();
    $("#select").change(function(){

        $("input[name=select").prop('checked',$("#select").prop("checked"));
        var eids = [];
        $('input:checkbox[name=select]:checked').each(function(k){

            if($(this).val()!='') eids.push($(this).val());
        });
    });

    //clearInterval(start_for);
    //开始计划
    function startPlan(id) {
        $.post("<?php echo url('/admin/Fba/startPlan'); ?>", {id:id, status:2}, function(res) {
            if(res['code'] == 1000) {
                alert(res['msg']);
                window.location.reload();
            } else {
                alert(res['msg']);
                return;
            }
        });
    }
    //查看订单详情
    function checkFba (id) {
        //$.StandardPost("<?php echo url('/admin/Fba/fba_details'); ?>", {id:id});
        window.location.href = "<?php echo url('/admin/Fba/fba_details'); ?>" + '?id=' + id;
    }

    function keys_press(frm,event) {
        var events = window.event ? window.event : event;
        if (events.keyCode == 13) {
            search();
        }
    }

    function search() {
        var value=$('#search').val();
        var sku_id_time = value.split('_');
        //if (sku_id_time.length < 3 && sku_id_time[0][0] != 'g') {
            //alert('扫码有误');
            //return;
        //}

        //$.get("<?php echo url('/admin/fba/lists'); ?>", {is_handle:is_handle,time_sort:time_sort,search:value,start_time:start_time,end_time:end_time,sdate:sdate,plan_status:plan_status,payment_status:payment_status});
        window.location.href = "<?php echo url('/admin/fba/code_pick'); ?>" + "?search=" + value;
    }

    //箱子列表
    function get_box_list(id) {
        window.location.href = "<?php echo url('/admin/Fba/box_lists'); ?>" + '?fba_id=' + id;
    }

    //完成计划
    function successPlan(id) {
        $.post("<?php echo url('/admin/Fba/successPlan'); ?>", {id:id}, function(res) {
            if(res['code'] == 1000) {
                alert(res['msg']);
                window.location.reload();
            } else {
                alert(res['msg']);
                return;
            }
        });
    }

    //拣货的数量
    function code_pick_nums(unskuid = '', fba_id = '', order_id = '') {
        var unique_id = '';
        if (unskuid == '') {
            unique_id = $("#search").val();
        } else {
            unique_id = unskuid;
        }
        var unique_id_arr = unique_id.split('_');
        if (unique_id_arr.length < 3) {
            alert('打印码有错，请重新操作');
            $("#search").val('');
            return;
        }
        var nums = prompt('请输入拣货数量', 1);


        if (nums) {
            if (nums == '') {
                alert("拣货量不能为空");
                pick_nums();
            }
            var reg = /^[1-9]\d*$/;
            if (!reg.test(nums)) {
                alert('请输入正整数');
                pick_nums();
            }
            if (isNaN(nums)) {

                alert('请输入数字');
                pick_nums();
            }

            var caozuo = 'add'//$('input:radio[name=pg_radio]:checked').val();
            //var fba_id = $("#fba_id").val();
            if (caozuo == 'add') {
                $.post("<?php echo url('/admin/Fba/picking_msg'); ?>", {unique_id: unique_id, picking_nums:nums, fba_id:fba_id}, function (res) {
                    if (res['code'] == 1000) {
                        $("#search").val('');
                        $("#order_picking_num_" + fba_id + '_' + order_id).text(res['data']['num']);
                        if (res['is_here'] == 1) {
                            alert('该物品已到齐');
                            return;
                        }
                    } else {
                        $("#search").val('');
                        alert(res['msg']);
                        return;
                    }
                });
            } else if(caozuo == 'move') {
                $.post("<?php echo url('/admin/Fba/picking_msg_del'); ?>", {unique_id: unique_id, picking_nums:nums, fba_id:fba_id}, function (res) {
                    if (res['code'] == 1000) {
                        $("#search").val('');
                        $("#order_picking_num_" + fba_id + '_' + order_id).text(res['data']['num']);
                    } else {
                        $("#search").val('');
                        alert(res['msg']);
                        return;
                    }
                });
            }
        } else{
            $("#search").val('');
        }
        document.getElementById('search').focus();
    }
</script>
         </div>      </div>   </div>   <div class="swf_edit_box" id="swfbox"></div></body><script type="text/javascript">   $(document).ready(function(){      $("#fba_auto_msg_li").mouseover(function(){         $("#fba_auto_msg_ul").show();         $("#fba_auto_msg_li").mouseout(function(){            $("#fba_auto_msg_ul").hide();         });      });   })   var is_fba_auth = "<?php echo $is_fba_auth; ?>";   if (is_fba_auth == 1) {      $('#fba_btn_mn').click();      var speaks_auth = 1;      get_fba_new_msg();      var start_for = setInterval(function () {         get_fba_new_msg();      }, 150000);   }   //语音播报   function speak_cn(ttsText) {      //var mess = document.getElementById('ttsText').value;      var msg = new SpeechSynthesisUtterance(ttsText);      msg.volume = 100;      msg.rate = 1;      msg.pitch = 1.5;      console.log(msg);      window.speechSynthesis.speak(msg);   }   //获取fba数据   function get_fba_new_msg() {      $.post("<?php echo url('/admin/Fba/get_fba_zx'); ?>", {}, function (sres) {         if (sres['code'] == 1000) {            $('#fba_auto_msg_li').show();            var html = '';            var html_3 = '';            var html_4 = '';            var vol_txt_3 = '';            var vol_txt_4 = '';            if (sres['count_3'] > 0) {               vol_txt_3 = '已封箱' + sres['count_3'] + '件';               html_3 = "<li style='z-index: 999;'><a href=\"javaScript:check_fba_news(3);\">已封箱（" + sres['count_3'] + "件）</a></li>";            }            if (sres['count_4'] > 0) {               vol_txt_4 = '已发货' + sres['count_4'] + '件';               html_4 = "<li style='z-index: 999;'><a href=\"javaScript:check_fba_news(4);\">可发货（" + sres['count_4'] + "件）</a></li>";            }            $("#fba_auto_msg_ul").html(html_3 + html_4);            if (vol_txt_3 != '') {               speak_cn(vol_txt_3);            }            if (vol_txt_4 != '') {               speak_cn(vol_txt_4);            }            //alert(sres['msg']);            //playPause();         }         return;      });   }   //播放   function playPause() {      var music = document.getElementById('fba_music');      if (music.paused) {         music.play();         console.log('play');      } else {         music.pause();         console.log('pause');      }   }   //查看状态   function check_fba_news(plan_status) {      $.post("<?php echo url('/admin/Fba/check_news'); ?>", {plan_status:plan_status}, function (res) {         window.location.href = "<?php echo url('/admin/fba/lists'); ?>" + "?plan_status=" + plan_status;      });   }</script></html>