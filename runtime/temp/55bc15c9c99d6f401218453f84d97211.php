<?php if (!defined('THINK_PATH')) exit(); /*a:6:{s:91:"D:\project\factory\public/../application/admin\view\productionstatusset\product_status.html";i:1584407224;s:60:"D:\project\factory\application\admin\view\common\layout.html";i:1584407225;s:60:"D:\project\factory\application\admin\view\common\member.html";i:1584407225;s:61:"D:\project\factory\application\admin\view\common\topline.html";i:1584407225;s:58:"D:\project\factory\application\admin\view\common\menu.html";i:1584407225;s:61:"D:\project\factory\application\admin\view\common\mapline.html";i:1584407225;}*/ ?>
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
<style>
    .canvas_intro {margin:30px;}
    .pro_status_name {
        padding: 0 15px;
        line-height: 28px;
    }
    input[type='checkbox']{
        display: none;
    }
    input[type='checkbox'] +label{
        -moz-border-radius: 4px;
        -webkit-border-radius: 4px;
        border-radius:4px;
        display:inline-block;
        cursor: pointer;
        height: 385px;
        margin-bottom: 12px;
        margin-right: 12px;
        line-height: 36px;
        padding: 0 24px;
        background: #fff;
        border: 1px solid #ccc;
        text-align:center;
        /*font-weight: bold;*/
        font-size: 18px;
    }
    input[type='checkbox'] +label:hover{
        border:1px solid #5eb1e5;
    }
    input[type='checkbox']:checked +label{
        border:1px solid #fff;
        background:#5eb1e5;
        color: #000;
    }
    .listIMG {
        height: 200px;
        width: 200px;
    }
    .gdsnums {
        font-size: 18px;
        color: #990000;
        font-weight: bold;
    }
    .gdsdays {
        font-size: 18px;
        color: #5bb75b;
        font-weight: bold;
    }
    .ugrens {
        color: #990000;
    }
    .hpfac {
        border: 1px solid #5eb1e5;
        height: 28px;
        background: #fff;
        margin-right: 2px;
        -moz-border-radius: 4px;
        -webkit-border-radius: 4px;
        border-radius: 4px;
        padding: 5px 8px 5px 8px;
        font-size: 13px;
    }
    .hmfac {
        border:1px solid #5eb1e5;
        height: 28px;
        background: #fff;
        margin-right: 2px;
        -moz-border-radius: 4px;
        -webkit-border-radius: 4px;
        border-radius: 4px;
        padding: 5px 8px 5px 8px;
        font-size: 13px;
    }
    .confirm_button {
        background: #287fdc;
        vertical-align: middle;
        text-align: center;
        color: #fff;
        border-radius: 4px;
        margin: 0 15px;
        padding: 5px 20px 5px 20px;
    }
    .productstatus {
        border:1px solid #000;
        height: 28px;
        background: #fff;
        margin-right: 2px;
        -moz-border-radius: 4px;
        -webkit-border-radius: 4px;
        border-radius: 4px;
        padding: 5px 8px 5px 8px;
        font-size: 11px;
    }


</style>

<script type="text/javascript">


</script>
<div class="canvas_title do-clear">

    <ul class="tab_btn tab_btn_fl fl">

        <li class="input">
            <input type="text" id="search_order" name="search_order" value="" style="width: 150px;" placeholder="订单号/状态名称" onkeydown="keys_press()" />
        </li>

        <li><a href="javaScript:pro_status.search()">搜索</a></li>
        <span class="pro_status_name" id="pro_status_name">生产状态：<?php if(empty('')): else: endif; ?></span>
        <input type="hidden" id="pro_status_barcode" name="pro_status_barcode" value=""/>
    </ul>

</div>

<div class="canvas_intro" id="order" style="overflow: hidden;">

</div>
<div class="canvas_title do-clear">
    <ul class="tab_btn tab_btn_fl">
        <li><a href="javaScript:pro_status.appointPro()" id="batch_set_id">批量确认状态</a></li>
        <li><a href="javaScript:pro_status.cancelallPro()" id="cancel_set_id" style="background-color: #ff0000;" >批量取消状态</a></li>
    </ul>
</div>

<script>
    function keys_press(frm,event) {
        var events = window.event ? window.event : event;
        if (events.keyCode == 13) {
            pro_status.search();
        }
    }

    var pro_status = {
        order:[],
        pro_status_name:[],
        pro_status_barcode:'',
        appointOrd:[],
        //获得状态条形码
        getPro_status:function() {
            pro_status.pro_status_barcode = $('#pro_status_barcode').val();
            if (pro_status.pro_status_barcode == '') {
                alert('请先扫描生产状态条形码');
                document.getElementById('search_order').focus();
                return
            }
        },
        //获得订单
        getOrd:function(){
            pro_status.appointOrd=[];
            $("[name=orderData]:checked").each(function(){
                pro_status.appointOrd.push($(this).val());
            });
        },
        //绑定状态
        search:function() {

            var search_order = $('#search_order').val();
            document.getElementById('search_order').value = '';
            if (search_order == '') {
                alert('订单号/条形码不能为空');
                document.getElementById('search_order').focus();
                return;
            }
            if (search_order.search('-prostatus') != -1) {
                var search_arr = search_order.split('-');
                var pro_status_id = search_arr[0];
                console.log(search_arr);
                //document.getElementById('pro_status_barcode').value = search_order;
                $.post("<?php echo url('/admin/productionstatusset/product_status'); ?>", {id:pro_status_id}, function(res) {
                    if (res['code'] != 1000) {
                        alert(res['msg']);
                        document.getElementById('pro_status_barcode').value = '';
                        document.getElementById('search_order').focus();
                        return;
                    }console.log(res);
                    //alert(res['msg']);
                    document.getElementById('pro_status_barcode').value = res.data.id + '-prostatus';
                    document.getElementById('pro_status_name').innerHTML = '生产状态：' + res.data.status_name;
                    document.getElementById('search_order').focus();
                });

            } else {
                //pro_status.getPro_status();
                pro_status.pro_status_barcode = $('#pro_status_barcode').val();
                if (pro_status.pro_status_barcode == '') {
                    alert('请先扫描生产状态条形码');
                    document.getElementById('search_order').focus();
                    return;
                }

                var pro_status_arr = pro_status.pro_status_barcode.split('-');
                var pro_status_id = pro_status_arr[0];
                $.post("<?php echo url('/admin/productionstatusset/bind_order_pro'); ?>", {OrdNum:search_order, pro_status_id:pro_status_id}, function(res) {
                    var orderHtml = document.getElementById('order');
                    var html = orderHtml.innerHTML;
                    if (res.list.length == 0) {
                        document.getElementById('search_order').focus();
                        return;
                    }
                    for(var i = 0; i < res.list.length; i++) {
                        var check = '';
                        if (res.autoConfirm == true) {
                            check = 'checked';
                        }
                        var days = 0;
                        var startTime = new Date(res.list[i].AmzTimer); // 开始时间
                        var endTime = new Date(); // 结束时间
                        days = Math.floor((endTime - startTime) / (24 * 3600 * 1000));
                        if (days < 1) {days = 0;}
                        var imgArr = res.list[i].ImgURL.split(',');
                        var imgUrl = '';
                        if (imgArr.length > 0) {imgUrl = imgArr[0];}
                        var pFac = '';//印花
                        var mFac = '';//加工
                        var urgen = '';console.log(res.list[i].Urgent);
                        if (res.list[i].Urgent == 1) {
                            urgen = '<span class="ugrens">(急)</span>';
                        }
                        for (var j = 0; j < res.list[i].orderFactory.length; j++) {
                            if (res.list[i].orderFactory[j].working_type == 1) {pFac += res.list[i].orderFactory[j].userinfo.Name;}
                            if (res.list[i].orderFactory[j].working_type == 2) {mFac += res.list[i].orderFactory[j].userinfo.Name;}
                        }
                        var hpfac = '';
                        var hmfac = '';
                        if (pFac != '') {hpfac = '<span class="hpfac">' + pFac + '</span>';}
                        if (mFac != '') {hmfac = '<span class="hmfac">' + mFac + '</span>';}
                        var orderidpro = res.list[i].id + '-' + res.list[i].bind_pro_status_id;
                        var hprodstatu = '';
                        //if (res.list[i]['productstatus'] != null) {hprodstatu = '<span class="productstatus">' + res.list[i]['productstatus']['status_name'] + '</span>';}
                        html = '<div style="float: left;text-align: center;padding: 20px;" id="del' + res.list[i].id + '-' + res.list[i].bind_pro_status_id + '"><input type="checkbox" name="orderData"  id="' + res.list[i].OrdNum + i + '" value="' + res.list[i].id + '-' + res.list[i].bind_pro_status_id + '"'  + check + ' />' +
                            '<label for="' + res.list[i].OrdNum + i + '">' +
                            res.list[i].OrdNum + urgen + '</br>' +
                            '<span class="gdsnums">' + res.list[i].GdsNum + '件/</span>' +
                            '<span class="gdsdays">' + days + '天</span></br>' +
                            '<img class="listIMG" src="http://kjds-img.img-cn-shanghai.aliyuncs.com/' + imgUrl + '@0e_0o_1l_500h_500w.src" /></br>' +
                            hprodstatu
                            +  '</br>' +
                            hpfac +
                            '&nbsp;' + hmfac +
                            '</label></br>' +
                            '<a href="javascript:pro_status.autoappointPro(' + res.list[i].id + ',' + res.list[i].bind_pro_status_id + ')" style="color: #fff;" class="confirm_button" id="confirm' + res.list[i].id + '">确认状态</a>' +
                            '<a href="javascript:pro_status.cancelPro(' + res.list[i].id + ',' + res.list[i].bind_pro_status_id + ')" style="color: #fff;background-color: #ff0000;" class="confirm_button" id="cancel' + res.list[i].id + '">取消指派</a>' +
                            '</div>' + html;
                    }
                    orderHtml.innerHTML = html;
                    //document.getElementById('searchOrdNum').value = '';
                    document.getElementById('search_order').focus();
                    if (res.list.length == 1) {
                        //setTimeout(function () {order.autoappointFac(res.list[0].id)}, 5000);
                        var count = 3;
                        //$("#cancel" + res.list[0].id).addClass('disable');
                        document.getElementById('cancel' + res.list[0].id).remove();
                        var star = window.setInterval(function(){
                            document.getElementById("confirm" + res.list[0].id).innerHTML = "自动指派状态(" + (count--) + 's)';
                            if(count == 0){
                                window.clearInterval(star);
                                //document.getElementById("setFac").innerHTML = "指派工厂";
                                pro_status.autoappointPro(res.list[0].id, res.list[0].bind_pro_status_id);
                            }
                        }, 1000);
                    }
                });
            }
        },
        //批量取消自动指派状态
        cancelallPro:function() {
            pro_status.getOrd();
            if (pro_status.appointOrd.length == 0) {
                alert('请选择需要取消的订单');
                document.getElementById('search_order').focus();
                return;
            }
            document.getElementById('search_order').focus();
            for (var dd = 0; dd < pro_status.appointOrd.length; dd++) {
                document.getElementById('del' + pro_status.appointOrd[dd]).remove();
                //document.getElementById('brdel' + order.appointOrd[dd]).remove();
            }
        },
        //取消自动指派状态
        cancelPro:function(ord_id,pro_id) {
            var orderpro = ord_id + '-' + pro_id;
            document.getElementById('search_order').focus();
            document.getElementById('del' + orderpro).remove();
        },
        //自动指派状态
        autoappointPro:function(ord_id,pro_id) {
            //order.getOrd();
            //var data_arr = ord_id.split('-');
            var orderpro = ord_id + '-' + pro_id;
            var ord = [orderpro];
            console.log(orderpro);

            if (ord.length == 0) {
                alert('请选择需要指派订单');
                document.getElementById('search_order').focus();
                return;
            }
            console.log(ord);

            $.post("<?php echo url('/admin/productionstatusset/appointPro'); ?>", {order_id:ord}, function(res) {
                if (res['code'] != 1000) {
                    alert(res['msg']);
                    document.getElementById('search_order').focus();
                    return;
                }
                //alert(res['msg']);
                document.getElementById('search_order').focus();
                document.getElementById('del' + orderpro).remove();
                //document.getElementById('del' + ord_id).remove();
                //document.getElementById('order').innerHTML = '';
                //window.location = "<?php echo url('/admin/productionmanage/orderFac'); ?>"+'?pFactory=' + order.printFactory + '&mFactory=' + order.macFactory;
            });
        },

        appointPro:function() {
            pro_status.getOrd();

            if (pro_status.appointOrd.length == 0) {
                alert('请选择需要指派订单');
                document.getElementById('search_order').focus();
                return;
            }

            $.post("<?php echo url('/admin/productionstatusset/appointPro'); ?>", {order_id:pro_status.appointOrd}, function(res) {
                if (res['code'] != 1000) {
                    alert(res['msg']);
                    document.getElementById('search_order').focus();
                    return;
                }
                alert(res['msg']);
                document.getElementById('search_order').focus();
                for (var dd = 0; dd < pro_status.appointOrd.length; dd++) {
                    document.getElementById('del' + pro_status.appointOrd[dd]).remove();
                    //document.getElementById('brdel' + order.appointOrd[dd]).remove();
                }
                //document.getElementById('order').innerHTML = '';
                //window.location = "<?php echo url('/admin/productionmanage/orderFac'); ?>"+'?pFactory=' + order.printFactory + '&mFactory=' + order.macFactory;
            });
        }
    };
    document.getElementById('search_order').focus();//将输入光标定位到搜索框
</script>
         </div>      </div>   </div>   <div class="swf_edit_box" id="swfbox"></div></body></html>