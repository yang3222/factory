<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:90:"D:\project\factory\public/../application/admin\view\productionmanage\mobile_order_fac.html";i:1584407225;s:67:"D:\project\factory\application\admin\view\common\mobile_layout.html";i:1584407225;}*/ ?>
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
        <?php endif; endif; endforeach; endif; else: echo "" ;endif; ?>
    </section>
    <div class="canvas">

        <script type="text/javascript">
    $(function() {
        if ("<?php echo $reload; ?>") {
            alert('请选择打印/加工工厂');
            window.location = "<?php echo url('/admin/productionmanage/index'); ?>";
        }
    });

</script>
<header class="header-cls">
    <a href="javaScript:history.go(-1);" style="color: #fff;"><span class="iconfont iconleft left-cls"></span></a>
    <div class="header-title"><?php echo $menu[$currentMenu['menu']]['nav'][$currentMenu['nav']]['title']; ?></div>
    <span style="position: absolute;
    left: 110px;
    top: 23px;
    bottom: 0;
    font-size: 10px;
    padding: 0 0.2rem;
    z-index: 9;">印花厂：<?php if(empty($pFactory['Name'])): ?>(无)<?php else: ?><?php echo $pFactory['Name']; endif; ?> 加工厂：<?php if(empty($mFactory['Name'])): ?>(无)<?php else: ?><?php echo $mFactory['Name']; endif; ?></span>
    <span class="iconfont icondelete right-cls"></span>
    <span class="right-cls completeBtn disNone">完成</span>
</header>

<div class="content-cls">

    <div class="input-box">

        <input type="text" placeholder="输入订单号/订单ID" id="searchOrdNum" onkeydown="keyspress()" />
        <input type="hidden" id="pro_status_barcode" name="pro_status_barcode" value="" />
        <input type="hidden" id="pFactory" name="pFactory" value="<?php echo $pFactory['user_id']; ?>" />
        <input type="hidden" id="mFactory" name="mFactory" value="<?php echo $mFactory['user_id']; ?>" />
        <!-- <span class="pro_status_name" id="pro_status_name">生产状态：<?php if(empty('')): else: endif; ?></span>-->
    </div>
    <ul class="list-cls" id="order">
        <!--<li>
            <div class="font-bold">订单号：<span class="flex-1">113-3780507-4081020</span><span>1件/15天</span></div>
            <div>SKU：<span class="flex-1">g23014123p262c299s505</span>
                <input type="checkbox" name="orderData" value="id" class="checkBtn disNone">
            </div>
            <div class="img-box">
                <img src="images/prduct_1.jpg" alt="">
                <div class="flex-1">
                    <p>印花：<span>欧锦数码</span></p>
                    <p>加工：<span>飞飞鱼工贸</span></p>
                </div>
            </div>
            <div class="list-btn">
                <div class="flex-1"></div>
                <button>取消指派</button>
                <button class="full-btn">确认状态</button>
            </div>
        </li>-->

    </ul>
    <div class="fiex-bottom disNone">
        <div class="flex-1">
            <input type="checkbox" id="checkAll">
            <label for="checkAll">全选</label>
        </div>
        <div class="del-cls"><a href="javaScript:order.cancelallPro()" style="color: #fff;">删除</a></div>
    </div>
</div>

<script type="text/javascript">

    function keyspress(frm,event) {
        var events = window.event ? window.event : event;
        if (events.keyCode == 13) {
            order.search();
        }
    }
    //计算手机端宽度和设计稿750px结合 rem  1rem = 100px 以iphone6 为准
    var deviceWidth = document.documentElement.clientWidth;
    if (deviceWidth > 750) {  //设备最大值750px iphone6 375px@2
        deviceWidth = 750;
    }
    if (deviceWidth < 320) {  //设备最小320px  iphone4
        deviceWidth = 320;
    }
    document.documentElement.style.fontSize = deviceWidth / 7.5 + 'px';
    let checkLen = ''; // 复选框数量
    let nowCheck = ''; // 当前选中个数
    $(function(){
        checkLen = $('.list-cls li').length;
    });
    // 切换删除事件
    $('.icondelete').click(function(){
        $('.list-cls').addClass('isDetele');
        $('.completeBtn, .checkBtn, .fiex-bottom').removeClass('disNone');
        $('.icondelete').addClass('disNone');
        // 删除项全部改为未选中
        $('#checkAll').prop('checked', false);
        $("input[name='orderData']").prop("checked", false);
        $("#searchOrdNum").hide();
        $("#toolbar").hide();
    });
    // 完成事件
    $('.completeBtn').click(function(){
        $("#searchOrdNum").show();
        $("#toolbar").show();
        $('.list-cls').removeClass('isDetele');
        $('.icondelete').removeClass('disNone');
        $('.completeBtn, .checkBtn, .fiex-bottom').addClass('disNone');
        document.getElementById('searchOrdNum').focus();
    });

    function check_btn() {
        // 单选事件
        //$('.checkBtn').click(function () {
        checkboxFn();
        //});
    }

    // 全选事件
    $('#checkAll').click(function(){
        checkboxFn();
        if ($('#checkAll').is(':checked')) {
            $('#checkAll').prop('checked', false);
            $("input[name='orderData']").prop("checked", false);
        } else {
            /*var arr = document.getElementsByName('orderData');
            var ck = document.getElementById('checkAll');
            for(i in arr){
                arr[i].checked=ck.checked; // 全选
                //arr[i].checked=!arr[i].checked; 反向全选
            }*/
            $('#checkAll').prop('checked', true);
            $("input[name='orderData']").prop("checked", true);
        }
    });

    // 检测复选框数量
    function checkboxFn() {
        checkLen = $('.list-cls li').length;
        nowCheck =  $("input[name='orderData']:checkbox:checked").length;
        if (checkLen === nowCheck) {
            $('#checkAll').prop('checked', true);
        } else {
            $('#checkAll').prop('checked', false);
        }
    }

    var factory = {
        printFactory:[],
        macFactory:[],

        getPrintFactory:function(){

            factory.printFactory=[];
            $("[name=Printfactory]:checked").each(function(){
                factory.printFactory.push($(this).val());
            });
        },

        getMacFactory:function(){
            factory.macFactory=[];
            $("[name=Macfactory]:checked").each(function(){
                factory.macFactory.push($(this).val());
            });
        },
    };


    var order = {

        productsearch:[],
        factorysearch:[],
        printFactory:'',
        macFactory:'',
        appointOrd:[],

        getFac:function(){
            order.printFactory = $('#pFactory').val();
            order.macFactory = $('#mFactory').val();
        },

        getOrd:function(){

            order.appointOrd=[];
            $("[name=orderData]:checked").each(function(){
                order.appointOrd.push($(this).val());
            });
        },

        search:function(){
            order.getFac();
            var searchOrdNum = $('#searchOrdNum').val();
            document.getElementById('searchOrdNum').value = '';
            if (searchOrdNum == '') {
                alert('订单号不能为空');
                document.getElementById('searchOrdNum').focus();
                return;
            }
            if (order.printFactory == '' && order.macFactory == '') {
                alert('请选择打印/加工工厂');
                window.location = "<?php echo url('/admin/productionmanage/index'); ?>";
            }
            //document.getElementById('order').innerHTML = '搜索中...';
            $.post("<?php echo url('/admin/productionmanage/orderFac'); ?>", {pFactory:order.printFactory, mFactory:order.macFactory, OrdNum:searchOrdNum}, function (res) {

                var orderHtml = document.getElementById('order');
                var html = orderHtml.innerHTML;
                if (res.list.length == 0) {
                    //orderHtml.innerHTML = '无数据';
                    //document.getElementById('searchOrdNum').value = '';
                    //document.getElementById('no_data').innerHTML += searchOrdNum + ',';
                    document.getElementById('searchOrdNum').focus();
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
                    var hprodstatu = '';
                    if (res.list[i]['productstatus'] != null) {hprodstatu = '' + res.list[i]['productstatus']['status_name'] + '';}
                    html = '<li id="del' + res.list[i].id + '"> ' +
                        '<div class="font-bold">订单号：<span class="flex-1">' + res.list[i].OrdNum + urgen + '</span><span>' + res.list[i].GdsNum + '件/' + res.list[i].days_diff + '天</span></div> ' +
                        '<div>SKU：<span class="flex-1">' + res.list[i].GdsSku + '</span>' +
                        '<input onchange="check_btn()" type="checkbox" name="orderData" id="' + res.list[i].OrdNum + i + '" class="checkBtn disNone" value="' + res.list[i].id + '"'  + check + ' />' +
                        '</div>' +
                        '<div class="img-box">' +
                        '<img src="http://kjds-img.img-cn-shanghai.aliyuncs.com/' + imgUrl + '@0e_0o_1l_500h_500w.src" alt="">' +
                        '<div class="flex-1">' +
                        '<p>印花：<span>' + hpfac + '</span></p>' +
                        '<p>加工：<span>' + hmfac + '</span></p>' +
                        '<p style="color: #045862;">状态：<span>' + hprodstatu + '</span></p>' +
                        '</div>' +
                        '</div>' +
                        '<div class="list-btn"> ' +
                        '<div class="flex-1"></div>' +
                        '<button id="cancel' + res.list[i].id + '">' + '<a href="javascript:order.cancelPro(' + res.list[i].id + ')" style="color: #ffb100;" class="confirm_button">取消指派</a></button>' +
                        '<button class="full-btn">' +
                        '<a href="javascript:order.autoappointFac(' + res.list[i].id + ')" style="color: #fff;" class="confirm_button" id="confirm' + res.list[i].id + '">确认指派</a></button>' +
                        '</div>' +
                        '</li>'  + html;
                }
                orderHtml.innerHTML = html;
                //document.getElementById('searchOrdNum').value = '';
                document.getElementById('searchOrdNum').focus();
                if (res.list.length == 1) {
                    //setTimeout(function () {order.autoappointFac(res.list[0].id)}, 5000);
                    var count = 3;
                    document.getElementById('cancel' + res.list[0].id).remove();
                    var star = window.setInterval(function(){
                        document.getElementById("confirm" + res.list[0].id).innerHTML = "自动指派(" + (count--) + 's)';
                        if(count == 0){
                            window.clearInterval(star);
                            //document.getElementById("setFac").innerHTML = "指派工厂";
                            order.autoappointFac(res.list[0].id);
                        }
                    }, 1000);
                }
            });
            //window.location = "<?php echo url('/admin/productionmanage/orderFac'); ?>"+'?pFactory=' + order.printFactory + '&mFactory=' + order.macFactory + '&OrdNum=' + searchOrdNum;
        },

        //批量取消自动指派状态
        cancelallPro:function() {
            order.getOrd();
            if (order.appointOrd.length == 0) {
                alert('请选择需要取消的订单');
                document.getElementById('searchOrdNum').focus();
                return;
            }
            document.getElementById('searchOrdNum').focus();
            for (var dd = 0; dd < order.appointOrd.length; dd++) {
                document.getElementById('del' + order.appointOrd[dd]).remove();
                //document.getElementById('brdel' + order.appointOrd[dd]).remove();
            }
        },
        //取消自动指派状态
        cancelPro:function(ord_id) {
            var orderpro = ord_id;
            document.getElementById('searchOrdNum').focus();
            document.getElementById('del' + orderpro).remove();
        },

        autoappointFac:function(ord_id) {
            order.getFac();
            //order.getOrd();
            var ord = [ord_id];
            if (order.printFactory == '' && order.macFactory == '') {
                alert('请选择打印/加工工厂');
                window.location = "<?php echo url('/admin/productionmanage/index'); ?>";
            }

            if (ord.length == 0) {
                alert('请选择需要指派订单');
                document.getElementById('searchOrdNum').focus();
                return;
            }

            $.post("<?php echo url('/admin/productionmanage/appointFac'); ?>", {order_id:ord,pFactory:order.printFactory,mFactory:order.macFactory}, function(res) {
                if (res['code'] != 1000) {
                    alert(res['msg']);
                    document.getElementById('searchOrdNum').focus();
                    return;
                }
                //alert(res['msg']);
                document.getElementById('searchOrdNum').focus();
                document.getElementById('del' + ord_id).remove();
                //document.getElementById('del' + ord_id).remove();
                //document.getElementById('order').innerHTML = '';
                //window.location = "<?php echo url('/admin/productionmanage/orderFac'); ?>"+'?pFactory=' + order.printFactory + '&mFactory=' + order.macFactory;
            });
        },

        appointFac:function() {
            order.getFac();
            order.getOrd();
            //var searchOrdNum = $('#searchOrdNum').val();
            if (order.printFactory == '' && order.macFactory == '') {
                alert('请选择打印/加工工厂');
                window.location = "<?php echo url('/admin/productionmanage/index'); ?>";
            }

            if (order.appointOrd.length == 0) {
                alert('请选择需要指派订单');
                document.getElementById('searchOrdNum').focus();
                return;
            }

            $.post("<?php echo url('/admin/productionmanage/appointFac'); ?>", {order_id:order.appointOrd,pFactory:order.printFactory,mFactory:order.macFactory}, function(res) {
                if (res['code'] != 1000) {
                    alert(res['msg']);
                    document.getElementById('searchOrdNum').focus();
                    return;
                }
                alert(res['msg']);
                document.getElementById('searchOrdNum').focus();
                for (var dd = 0; dd < order.appointOrd.length; dd++) {
                    document.getElementById('del' + order.appointOrd[dd]).remove();
                    //document.getElementById('brdel' + order.appointOrd[dd]).remove();
                }
                //document.getElementById('order').innerHTML = '';
                //window.location = "<?php echo url('/admin/productionmanage/orderFac'); ?>"+'?pFactory=' + order.printFactory + '&mFactory=' + order.macFactory;
            });
        }

    };
    document.getElementById('searchOrdNum').focus();//将输入光标定位到搜索框
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