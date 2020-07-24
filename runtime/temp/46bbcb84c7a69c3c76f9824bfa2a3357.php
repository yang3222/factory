<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:75:"D:\project\factory\public/../application/admin\view\order\mobile_index.html";i:1584586560;s:67:"D:\project\factory\application\admin\view\common\mobile_layout.html";i:1587535506;}*/ ?>
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
    .button-box {margin: 0.2rem 0.3rem;display: flex;}
    .button-box a{
        border-radius: 10px;
        line-height: 0.94rem;
        font-size: 0.36rem;
        padding: 0;
        color: #000000;
        margin: 2px;
        text-align: center;
    }
    .new_order {
        width: 25%;
        background-color: #fab703;
    }
    .pro_order {
        width: 25%;
        background-color: #60c2f4;
    }
    .out_order {
        width: 25%;
        background-color: #fa4903;
    }
    .all_order {
        width: 25%;
        background-color: #fa4903;
    }

</style>
<header class="header-cls">
    <a href="javaScript:history.go(-1);" style="color: #fff;"><span class="iconfont iconleft left-cls"></span></a>
    <div class="header-title"><?php echo $menu[$currentMenu['menu']]['nav'][$currentMenu['nav']]['title']; ?></div>
    <!--<span class="iconfont icondelete right-cls"></span>-->
    <!--<span class="right-cls completeBtn disNone">完成</span>-->
</header>

<div class="content-cls">
    <div class="button-box">
        <a class="all_order" href="<?php echo url('/admin/order/index'); ?>">全部订单</a>
        <a class="new_order" href="<?php echo url('/admin/order/index','type=1'); ?>">新订单</a>
        <a class="pro_order" href="<?php echo url('/admin/order/index','type=2'); ?>">生产中</a>
        <a class="out_order" href="<?php echo url('/admin/order/index','type=3'); ?>">已出库</a>
    </div>
    <div class="input-box">
        <input type="text" placeholder="输入订单号搜索" id="search" onkeydown="keyspress()" value="" />
        <input type="hidden" id="type" value="<?php echo $sign; ?>"/>
        <!--<input type="hidden" id="date" value="365"/>
        <input type="hidden" name="start_date" id="start_date" value="$date.start_time">
        <input type="hidden" name="end_date" id="end_date" value="$date.end_time">-->
    </div>
    <ul class="list-cls" id="order">
        <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $k = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$value): $mod = ($k % 2 );++$k;?>
        <li>
            <?php if($value['status']<2  || $value['status']==4): $endtime = date('Y-m-d h:i:s'); else: $endtime = $value['SignTimer']; endif; ?>

            <div class="font-bold">订单号：<span class="flex-1"><?php echo $value['OrdNum']; if($value['Urgent']==1): ?><strong>(急)</strong><?php endif; ?></span><span><?php echo $value['GdsNum']; ?>件/<?php if($value['status'] == 5): ?>0<?php else: $alldata = floor((strtotime($endtime)-strtotime($value['AmzTimer']))/(3600*24)); if($alldata>0): ?><?php echo $alldata; else: ?>0<?php endif; endif; ?>天</span></div>
            <div>SKU：<span class="flex-1"><?php echo $value['GdsSku']; ?></span>
                <!--<input type="checkbox" name="orderData" value="id" class="checkBtn disNone">-->
            </div>
            <div class="img-box">
                <img src="http://kjds-img.img-cn-shanghai.aliyuncs.com/<?php echo explode(',',$value['ImgURL'])[0]; ?>@0e_0o_1l_500h_500w.src" alt="">
                <div class="flex-1" style="font-size: 0.24rem">
                <?php if(is_array($value['production_status']) || $value['production_status'] instanceof \think\Collection || $value['production_status'] instanceof \think\Paginator): $osikey = 0; $__LIST__ = $value['production_status'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$order_status_id): $mod = ($osikey % 2 );++$osikey;if($osikey <= 4): ?>
                    <span class="prostatus"><?php echo $order_status_id['status']; ?></br>
                    <?php echo mb_substr($order_status_id['add_time'],5,11,'utf-8'); ?></span></br>
                    <?php else: endif; endforeach; endif; else: echo "" ;endif; ?>
                    </div>
                <div class="flex-1" style="font-size: 0.24rem">
                    <?php $factorys = GetOrderFactory($value['id']); if(is_array($value['orderFactory']) || $value['orderFactory'] instanceof \think\Collection || $value['orderFactory'] instanceof \think\Paginator): $i = 0; $__LIST__ = $value['orderFactory'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$factory): $mod = ($i % 2 );++$i;if($factory['sign']==0): $sign_type = '新订单'; $signtime = $value['GetTimer']; elseif($factory['sign']==1): $sign_type = '生产中'; $signtime = $factory['pro_time']; elseif($factory['sign']==2): $sign_type = '已出库'; $signtime = $factory['library_time']; endif; ?>
                    <p><?php if($factory['working_type']=='1'): ?>印花：<?php else: ?>加工：<?php endif; ?><span><?php echo $factory['userinfo']['Name']; ?></span></br><span><?php echo date('m-d H:i',strtotime($signtime)); ?></span></p>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </div>
            </div>
        </li>
        <?php endforeach; endif; else: echo "" ;endif; ?>
    </ul>

    <p style="text-align: center; display: none;" id="loadmore">加载更多数据中</p>
    <p style="text-align: center; display: none;" id="buttom_p">没有更多数据了</p>
    <!--<div class="fiex-bottom disNone">
        <div class="flex-1">
            <input type="checkbox" id="checkAll">
            <label for="checkAll">全选</label>
        </div>
        <div class="del-cls"><a href="javaScript:" style="color: #fff;">删除</a></div>
    </div>-->
    <input type="hidden" id="search_data" value="" />
</div>

<script type="text/javascript">
    var lastpages = "<?php echo $lastpage; ?>";
    var currentpages = "<?php echo $currentpage; ?>";

    function keyspress(frm,event) {
        var events = window.event ? window.event : event;
        if (events.keyCode == 13) {
            search();
        }
    }

    var downs = true;
    $(window).scroll(function() {
        //
        var scrollTop = $(this).scrollTop();
        var scrollHeight = $(document).height();
        var windowHeight = $(this).height();

        if (scrollTop + windowHeight >= scrollHeight) {
            //alert("<?php echo $sign; ?>");
            //触发后执行的方法
            if (!downs) {
                return;
            }
            $("#loadmore").show();
            downs = false;
            if (lastpages == 0) {
                $("#loadmore").hide();
                $("#buttom_p").show();
                setTimeout(function() {
                    downs = true;
                }, 2000);
                return;
            }
            if (currentpages < lastpages) {
                currentpages++;
                var searchs = $("#search_data").val();

                $.post("<?php echo url('/admin/order/index','type='.$sign); ?>" + "?page=" + currentpages, {search: searchs}, function(data) {
                    /*if($("#loadmore").hasClass('disNone')){
                        $("#loadmore").removeClass('disNone');
                    }*/
                    var order_html = document.getElementById('order');
                    //order_html.innerHTML = '';
                    if (data.list.length > 0) {
                        var htmls = order_html.innerHTML;
                        for (var i = 0; i < data.list.length; i++) {
                            var imgArr = data.list[i].ImgURL.split(',');
                            var imgUrl = '';
                            if (imgArr.length > 0) {imgUrl = imgArr[0];}
                            var pFac = '';//印花
                            var mFac = '';//加工
                            var urgen = '';
                            if (data.list[i].Urgent == 1) {
                                urgen = '<span class="">(急)</span>';
                            }
                            for (var j = 0; j < data.list[i].orderFactory.length; j++) {
                                if (data.list[i].orderFactory[j].working_type == 1) {pFac += data.list[i].orderFactory[j].userinfo.Name;}
                                if (data.list[i].orderFactory[j].working_type == 2) {mFac += data.list[i].orderFactory[j].userinfo.Name;}

                            }
                            var prostatushtml = '';//状态html
                            for (var ji = 0; ji < data.list[i].production_status.length; ji++) {
                                var addtimestr = data.list[i].production_status[ji].add_time.substr(5,5);
                                if (ji <= 4) {prostatushtml += '<span class="prostatus">' + data.list[i].production_status[ji].status + addtimestr +'</span></br>';}
                            }
                            var hpfac = '';
                            var hmfac = '';
                            if (pFac != '') {hpfac = pFac;}
                            if (mFac != '') {hmfac = mFac;}

                            htmls += '<li>' +
                                '<div class="font-bold">订单号：<span class="flex-1">' + data.list[i].OrdNum + urgen +'</span><span>' + data.list[i].GdsNum + '件/天</span></div>' +
                                '<div>SKU：<span class="flex-1">' + data.list[i].GdsSku + '</span>' +
                                '</div>' +
                                '<div class="img-box">' +
                                '<img src="http://kjds-img.img-cn-shanghai.aliyuncs.com/' + imgUrl +'@0e_0o_1l_500h_500w.src" alt="">' +
                                '<div class="flex-1" style="font-size: 0.24rem">' + prostatushtml +
                                '</div>' +
                                '<div class="flex-1" style="font-size: 0.24rem">' +
                                '<p>印花：<span>' + hpfac + '</span></p>' +
                                '<p>加工：<span>' + hmfac + '</span></p>' +
                                '</div>' + '</div></li>';
                        }
                        order_html.innerHTML = htmls;
                        $("#loadmore").hide();
                    } else {
                        $("#loadmore").hide();
                        $("#buttom_p").show();
                    }
                    lastpages = data.lastpage;
                    currentpages = data.currentpage;
                    document.getElementById('search').focus();
                });
            } else {
                $("#loadmore").hide();
                $("#buttom_p").show();
            }
            setTimeout(function() {
                downs = true;
                return;
            }, 3000)
        }
        //$("#loadmore").hide();
    });

    //搜索
    function search() {
        var search = $('#search').val();
        document.getElementById('search').value = '';
        document.getElementById('search_data').value = search;
        if (search == '') {
            alert('请输入搜索内容');
            return;
        }
        /*if(!$("#buttom_p").hasClass('disNone')){
            $("#buttom_p").addClass('disNone');
        }*/
        //window.location.href = "<?php echo url('/admin/order/index','type='.$sign); ?>" + '?search=' + search;
        $.post("<?php echo url('/admin/order/index','type='.$sign); ?>", {search:search}, function(data) {
            var order_html = document.getElementById('order');
            order_html.innerHTML = '';
            if (data.list.length > 0) {
                var htmls = order_html.innerHTML;
                for (var i = 0; i < data.list.length; i++) {
                    var imgArr = data.list[i].ImgURL.split(',');
                    var imgUrl = '';
                    if (imgArr.length > 0) {imgUrl = imgArr[0];}
                    var pFac = '';//印花
                    var mFac = '';//加工
                    var urgen = '';
                    if (data.list[i].Urgent == 1) {
                        urgen = '<span class="">(急)</span>';
                    }
                    console.log(data.list[i]);
                    for (var j = 0; j < data.list[i].orderFactory.length; j++) {
                        var sign_type = '';
                        var sign_time = data.list[i].GetTimer.substr(5,11);
                        if (data.list[i].orderFactory[j]['sign'] == 0) {
                            sign_type = '新订单';
                            sign_time = data.list[i].GetTimer.substr(5,11);
                        } else if (data.list[i].orderFactory[j]['sign'] == 1) {
                            sign_type = '生产中';
                            sign_time = data.list[i].orderFactory[j].pro_time.substr(5,11);
                        } else if (data.list[i].orderFactory[j]['sign'] == 2) {
                            sign_type = '已出库';
                            sign_time = data.list[i].orderFactory[j].library_time.substr(5,11);
                        }
                        if (data.list[i].orderFactory[j].working_type == 1) {pFac += data.list[i].orderFactory[j].userinfo.Name + '</br>' + sign_time;}
                        if (data.list[i].orderFactory[j].working_type == 2) {mFac += data.list[i].orderFactory[j].userinfo.Name + '</br>' + sign_time;}

                    }
                    var prostatushtml = '';//状态html
                    for (var ji = 0; ji < data.list[i].production_status.length; ji++) {
                        var addtimestr = data.list[i].production_status[ji].add_time.substr(5,11);
                        if (ji <= 4) {prostatushtml += '<span class="prostatus">' + data.list[i].production_status[ji].status + addtimestr +'</span></br>';}
                    }
                    var hpfac = '';
                    var hmfac = '';
                    if (pFac != '') {hpfac = pFac;}
                    if (mFac != '') {hmfac = mFac;}

                    htmls += '<li>' +
                        '<div class="font-bold">订单号：<span class="flex-1">' + data.list[i].OrdNum + urgen +'</span><span>' + data.list[i].GdsNum + '件/天</span></div>' +
                        '<div>SKU：<span class="flex-1">' + data.list[i].GdsSku + '</span>' +
                        '</div>' +
                        '<div class="img-box">' +
                        '<img src="http://kjds-img.img-cn-shanghai.aliyuncs.com/' + imgUrl +'@0e_0o_1l_500h_500w.src" alt="">' +
                        '<div class="flex-1" style="font-size: 0.24rem">' + prostatushtml +
                                '</div>' +
                        '<div class="flex-1" style="font-size: 0.24rem">' +
                        '<p>印花：<span>' + hpfac + '</span></p>' +
                        '<p>加工：<span>' + hmfac + '</span></p>' +
                        '</div>' + '</div></li>';
                }
                order_html.innerHTML = htmls;
            } else {
                order_html.innerHTML = '';
                $("#buttom_p").show();
            }
            lastpages = data.lastpage;
            currentpages = data.currentpage;
            document.getElementById('search').focus();
            return;
        });
    }
    document.getElementById('search').focus();
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