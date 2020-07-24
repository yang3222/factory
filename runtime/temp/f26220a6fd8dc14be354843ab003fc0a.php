<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:77:"D:\project\factory\public/../application/admin\view\warehouse\viewqrcode.html";i:1592878804;}*/ ?>
<style>
    .name {
        line-height: 28px;
        font-size: 14px;
        min-height: 28px;
        position: relative;
        padding-left: 105px;
        float: left;
        margin-bottom: 5px;
    }
    .canvas_intro table.product {
        margin-top: 30px;
        margin-bottom: 80px;
        margin-right: 50px;
        margin-left: 10px;
    }
    .canvas_intro table.product {
        width: 80%;
        border: solid #000000;
        border-width: 1px 0px 0px 1px;
        padding: 2px;
        margin: 8px 0;
        margin-bottom: 12px;
    }
    table {
        border-collapse: collapse;
        border-spacing: 0;
        display: table;
    }
    .canvas_intro table.product td {
        padding: 20px 8px;
        height: 42px;
        vertical-align: middle;
        text-align: center;
        border: solid #000000;
        border-width: 0px 1px 1px 0px;
        display: table-cell;
    }
    .canvas_intro table.product table.productchild {
        width: 100%;
        height: 100%;
        border: solid #000000;
        border-width: 1px 0px 0px 1px;
        margin: 8px 0;
        margin-bottom: 1px;
    }
    .canvas_intro {
        overflow-y:auto;
        overflow-x:auto;
        height:635px;
    }
    .modelboxv {
        width: 1105px;
        background: #fff;
        -moz-border-radius: 4px;
        -webkit-border-radius: 4px;
        border-radius: 4px;
        margin: 0 auto;
        margin-top: 100px;
    }
    input[type='checkbox'] {
        display: none;
    }

    input[type='checkbox'] + label {
        border-radius: 4px;
        display: inline-block;
        cursor: pointer;
        /*height: 155px;*/
        width: 204px;
        margin-bottom: 2px;
        margin-right: 2px;
        line-height: 36px;
        background: #fff;
        border: 1px solid #ccc;
        text-align: center;
        font-weight: bold;
        font-size: 18px;
    }
    input[type='checkbox']:checked +label {
        border: 1px solid #5eb1e5;
        background: #5eb1e5;
        color: #fff;
    }
    .qrcodeimg {
        margin: 1px;
        width: 200px;
        /*height: 151.8px;*/
        /*transform:scale(0.8)*/
    }
    .printdiv {

    }
    div.printcent {

        left: -10000px;
    }

</style>
<script type="text/javascript" src="<?php echo ADMIN_STYLE_URL; ?>js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo ADMIN_STYLE_URL; ?>js/jQuery.jsprint.js"></script>
<!--<script type="text/javascript" src="<?php echo ADMIN_STYLE_URL; ?>js/jquery.jqprint-0.3.js"></script>-->
<!--<script type="text/javascript" src="<?php echo ADMIN_STYLE_URL; ?>js/jQuery.print.js"></script>-->
<!--<script type="text/javascript" src="<?php echo ADMIN_STYLE_URL; ?>js/Print.js"></script>-->
<!--<script src="<?php echo ADMIN_STYLE_URL; ?>js/jquery-1.10.2.js" type="text/JavaScript" language="javascript"></script>
<script src="<?php echo ADMIN_STYLE_URL; ?>js/jquery-ui-1.10.4.custom.js"></script>
<script src="<?php echo ADMIN_STYLE_URL; ?>js/jquery.PrintArea.js" type="text/JavaScript" language="javascript"></script>-->
<div class="modelboxv">

    <div class="canvas_title do-clear">

        <ul class="tab_btn fr">
            <li><a id="printsome" onclick="print_some()" href="javaScript:">打印选中</a></li>
            <li><a href="javaScript:view_qr.close_select();">清空选中</a></li>
            <li><a id="printall" onclick="print()" href="javaScript:">打印全部</a></li><!--href="javaScript:view_qr.select();"-->
            <li><a href="javaScript:openModel.close()">关闭</a></li>
        </ul>

    </div>

    <div class="canvas_intro" id="canvas_intro">
        <div class="printcent" >
            <?php $index = '0'; $__FOR_START_1846610628__=$list['x'];$__FOR_END_1846610628__=0;for($i=$__FOR_START_1846610628__;$i > $__FOR_END_1846610628__;$i+=-1){ if(is_array($list['warehousetables']) || $list['warehousetables'] instanceof \think\Collection || $list['warehousetables'] instanceof \think\Paginator): $kt = 0; $__LIST__ = $list['warehousetables'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vt): $mod = ($kt % 2 );++$kt;if($i==$vt['p_x']): $childs = GetWarehouseChild($vt['id']); if(!empty($childs)): if(is_array($childs) || $childs instanceof \think\Collection || $childs instanceof \think\Paginator): $kc = 0; $__LIST__ = $childs;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vc): $mod = ($kc % 2 );++$kc;$materials = GetTargets("select * from ink_wtable_material where wt_id=".$vc['id']); if(!empty($materials)): if(is_array($materials) || $materials instanceof \think\Collection || $materials instanceof \think\Paginator): $km = 0; $__LIST__ = $materials;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vm): $mod = ($km % 2 );++$km;?>

                        <input class="printinput no-print" type="checkbox" name="printqrcode"  id="labelqrcode<?php echo $index; ?>" value="<?php echo $vc['name']; ?>"><label for="labelqrcode<?php echo $index; ?>"><span style="display: none"><?php echo $index+=1; ?></span>
                            <?php echo $vc['name']; ?></label>
                        <?php if($index % 8 == 0): endif; endforeach; endif; else: echo "" ;endif; else: ?>

                        <input class="printinput no-print" type="checkbox" name="printqrcode"  id="labelqrcode<?php echo $index; ?>" value="<?php echo $vc['name']; ?>"><label for="labelqrcode<?php echo $index; ?>"> <span style="display: none"><?php echo $index+=1; ?></span>
                            <?php echo $vc['name']; ?></label>
                        <?php if($index % 8 == 0): endif; endif; endforeach; endif; else: echo "" ;endif; else: $materials = GetTargets("select * from ink_wtable_material where wt_id=".$vt['id']); if(!empty($materials)): if(is_array($materials) || $materials instanceof \think\Collection || $materials instanceof \think\Paginator): $km = 0; $__LIST__ = $materials;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vm): $mod = ($km % 2 );++$km;?>

                        <input class="printinput no-print" type="checkbox" name="printqrcode"  id="labelqrcode<?php echo $index; ?>" value="<?php echo $vt['name']; ?>"><label for="labelqrcode<?php echo $index; ?>"><span style="display: none"><?php echo $index+=1; ?></span>
                            <?php echo $vt['name']; ?></label>
                        <?php if($index % 8 == 0): endif; endforeach; endif; else: echo "" ;endif; else: ?>

                        <input class="printinput no-print" type="checkbox" name="printqrcode"  id="labelqrcode<?php echo $index; ?>" value="<?php echo $vt['name']; ?>"><label for="labelqrcode<?php echo $index; ?>"><span style="display: none"><?php echo $index+=1; ?></span>
                            <?php echo $vt['name']; ?></label>
                        <?php if($index % 8 == 0): endif; endif; endif; endif; endforeach; endif; else: echo "" ;endif; } ?>
            <br/> <br/>
        </div>
    </div>
</div>
<br/> <br/><br/> <br/><br/> <br/><br/> <br/><br/> <br/><br/> <br/><br/> <br/><br/> <br/>
<!--<div class="printcent" id="printcent">-->
<!--    <?php $index = '0'; ?>-->
<!--    <?php $__FOR_START_185187214__=$list['x'];$__FOR_END_185187214__=0;for($i=$__FOR_START_185187214__;$i > $__FOR_END_185187214__;$i+=-1){ ?>-->


<!--    <?php if(is_array($list['warehousetables']) || $list['warehousetables'] instanceof \think\Collection || $list['warehousetables'] instanceof \think\Paginator): $kt = 0; $__LIST__ = $list['warehousetables'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vt): $mod = ($kt % 2 );++$kt;?>-->

<!--    <?php if($i==$vt['p_x']): ?>-->
<!--    <?php $childs = GetWarehouseChild($vt['id']); ?>-->
<!--    <?php if(!empty($childs)): ?>-->

<!--    <?php if(is_array($childs) || $childs instanceof \think\Collection || $childs instanceof \think\Paginator): $kc = 0; $__LIST__ = $childs;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vc): $mod = ($kc % 2 );++$kc;?>-->

<!--    <?php $materials = GetTargets("select * from ink_wtable_material where wt_id=".$vc['id']); ?>-->
<!--    <?php if(!empty($materials)): ?>-->

<!--    <?php if(is_array($materials) || $materials instanceof \think\Collection || $materials instanceof \think\Paginator): $km = 0; $__LIST__ = $materials;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vm): $mod = ($km % 2 );++$km;?>-->

<!--    <div id="print<?php echo $vc['id']; ?><?php echo $vm['id']; ?>" class="printdiv no-print"><input class="printinput no-print" type="checkbox" name="printqrcode"  id="printqrcode<?php echo $index; ?>" value="print<?php echo $index; ?>"><label for="printqrcode<?php echo $index; ?>"><span style="display: none"><?php echo $index+=1; ?></span>-->
<!--        <img class="qrcodeimg" style="height: 250px" src="<?php echo url('/admin/warehouse/getshelvesQrCode').'?text='.$vc['id'].'&name='.$vm['m_name'].'&shelf_code='.$vc['name']; ?>"/></label></div><div id="heiprint<?php echo $vc['id']; ?><?php echo $vm['id']; ?>" class="hei no-print"></div>-->
<!--    <?php if($index % 8 == 0): endif; ?>-->
<!--    <?php endforeach; endif; else: echo "" ;endif; ?>-->
<!--    <?php else: ?>-->

<!--    <div id="print<?php echo $vc['id']; ?>" class="printdiv no-print"><input class="printinput no-print" type="checkbox" name="printqrcode"  id="printqrcode<?php echo $index; ?>" value="print<?php echo $index; ?>"><label for="printqrcode<?php echo $index; ?>"> <span style="display: none"><?php echo $index+=1; ?></span>-->
<!--        <img class="qrcodeimg" style="height: 250px" src="<?php echo url('/admin/warehouse/getshelvesQrCode').'?text='.$vc['id'].'&name=&shelf_code='.$vc['name']; ?>"/></label></div><div id="heiprint<?php echo $vc['id']; ?>" class="hei no-print"></div>-->
<!--    <?php if($index % 8 == 0): endif; ?>-->
<!--    <?php endif; ?>-->

<!--    <?php endforeach; endif; else: echo "" ;endif; ?>-->

<!--    <?php else: ?>-->

<!--    <?php $materials = GetTargets("select * from ink_wtable_material where wt_id=".$vt['id']); ?>-->
<!--    <?php if(!empty($materials)): ?>-->

<!--    <?php if(is_array($materials) || $materials instanceof \think\Collection || $materials instanceof \think\Paginator): $km = 0; $__LIST__ = $materials;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vm): $mod = ($km % 2 );++$km;?>-->

<!--    <div id="print<?php echo $vt['id']; ?><?php echo $vm['id']; ?>" class="printdiv no-print"><input class="printinput no-print" type="checkbox" name="printqrcode"  id="printqrcode<?php echo $index; ?>" value="print<?php echo $index; ?>"><label for="printqrcode<?php echo $index; ?>"><span style="display: none"><?php echo $index+=1; ?></span>-->
<!--        <img class="qrcodeimg" style="height: 250px" src="<?php echo url('/admin/warehouse/getshelvesQrCode').'?text='.$vt['id'].'&name='.$vm['m_name'].'&shelf_code='.$vt['name']; ?>"/></label></div><div id="heiprint<?php echo $vt['id']; ?><?php echo $vm['id']; ?>" class="hei no-print"></div>-->
<!--    <?php if($index % 8 == 0): endif; ?>-->
<!--    <?php endforeach; endif; else: echo "" ;endif; ?>-->
<!--    <?php else: ?>-->

<!--    <div id="print<?php echo $vt['id']; ?>" class="printdiv no-print"><input class="printinput no-print" type="checkbox" name="printqrcode"  id="printqrcode<?php echo $index; ?>" value="print<?php echo $index; ?>"><label for="printqrcode<?php echo $index; ?>"><span style="display: none"><?php echo $index+=1; ?></span>-->
<!--        <img class="qrcodeimg" style="height: 250px" src="<?php echo url('/admin/warehouse/getshelvesQrCode').'?text='.$vt['id'].'&name=&shelf_code='.$vt['name']; ?>"/></label></div><div id="heiprint<?php echo $vt['id']; ?>" class="hei no-print"></div>-->
<!--    <?php if($index % 8 == 0): endif; ?>-->
<!--    <?php endif; ?>-->

<!--    <?php endif; ?>-->
<!--    <?php endif; ?>-->

<!--    <?php endforeach; endif; else: echo "" ;endif; ?>-->

<!--    <?php } ?>-->

<!--</div>-->


<script>
    var view_qr = {
        windowurl:"",

        openWindow:function(url){

            $("#swfbox").show();

            if(view_qr.windowurl!=url){

                view_qr.windowurl=url;

                $("#swfbox").html("");

                view_qr.loadURL({});

            }

        },

        select:function(){
            //$("#select").change(function(){
            var mode = 'iFrame';//$("input[name='mode']:checked").val();
            var close = false;//mode == "popup" && $("input#closePop").is(":checked");
            var extraCss = 30;//$("input[name='extraCss']").val();
            $("input[name=printqrcode]").prop('checked',1);//,$("#select").prop("checked")
            var print = "";
            $("input.printinput").each(function(){
                print += (print.length > 0 ? "," : "") + "div.printdiv#" + $(this).val();
            });
            console.log(print);
            var keepAttr = [];
            //$(".chkAttr").each(function(){
                //if ($(this).is(":checked") == false )
                    //return;

                //keepAttr.push( $(this).val() );
            //});

            var headElements = '<meta charset="utf-8" />,<meta http-equiv="X-UA-Compatible" content="IE=edge"/>';//$("input#addElements").is(":checked") ? '<meta charset="utf-8" />,<meta http-equiv="X-UA-Compatible" content="IE=edge"/>' : '';

            var options = { mode : mode, popClose : close, extraCss : extraCss, retainAttr : keepAttr, extraHead : headElements };

            $( print ).printArea( options );
            //})

        },
        close_select:function(){
            //$("#select").change(function(){

            $("input[name=printqrcode]").prop('checked',0);//,$("#select").prop("checked")
            $("div.printdiv").addClass('no-print');//,$("#select").prop("checked")
            $("div.hei").addClass('no-print');
            //})

        },

        close: function () {

            $("#swfbox").hide();

        },

        loadURL:function(obj){

            $.post(edit_factory.windowurl,obj,function(data){

                $("#swfbox").html(data);

            })

        },

        save_data:function() {
            var capacity = $("#capacity").val();
            var sort = $("#sort").val();
            var id = $("#id").val();
            if (capacity.length > 1) {
                var first_c = capacity.substring(0,1);
                if (first_c == 0) {
                    alert('产能请不要以0开头！');
                    return;
                }
            }
            if (sort.length > 1) {
                var first_s = sort.substring(0,1);
                if (first_s == 0) {
                    alert('排序请不要以0开头！');
                    return;
                }
            }
            $.post("<?php echo url('/admin/product/saveFactory'); ?>",{capacity:capacity, sort:sort, id:id},function(data){
                if (data.code == 1000) {
                    alert(data.msg);
                    window.location.reload();
                } else {
                    alert(data.msg);
                    window.location.reload();
                }
            })
        }
    };
    $(document).ready(function() {
        $("a.printall").click(function () {

            var mode = 'iFrame';//$("input[name='mode']:checked").val();
            var close = false;//mode == "popup" && $("input#closePop").is(":checked");
            var extraCss = 30;//$("input[name='extraCss']").val();
            $("input[name=printqrcode]").prop('checked', 1);//,$("#select").prop("checked")
            var print = "";
            $("input.printinput").each(function () {
                print += (print.length > 0 ? "," : "") + "div.printdiv#" + $(this).val();
            });
            console.log(print);
            var keepAttr = [];
            //$(".chkAttr").each(function(){
            //if ($(this).is(":checked") == false )
            //return;

            //keepAttr.push( $(this).val() );
            //});

            var headElements = '<meta charset="utf-8" />,<meta http-equiv="X-UA-Compatible" content="IE=edge"/>';//$("input#addElements").is(":checked") ? '<meta charset="utf-8" />,<meta http-equiv="X-UA-Compatible" content="IE=edge"/>' : '';

            var options = {
                mode: mode,
                popClose: close,
                extraCss: extraCss,
                retainAttr: keepAttr,
                extraHead: headElements
            };

            $(print).printArea(options);
        });
    });
    function print() {
        $("input[name=printqrcode]").prop('checked', 1);//,$("#select").prop("checked")
        $("div.printdiv").removeClass('no-print');//,$("#select").prop("checked")
        $("div.hei").removeClass('no-print');
        var idarr=[];
        $('input:checkbox[name=printqrcode]:checked').each(function(k){

            if($(this).val()!='')idarr.push($(this).val())

        });

        idarr = idarr.join(",")
        updatePost(idarr,'/admin/warehouse/getShelvesPdf');
        return;
    }
    function print_some() {
        $("div.printdiv").addClass('no-print');
        $("div.hei").addClass('no-print');
        var idarr=[];
        $('input:checkbox[name=printqrcode]:checked').each(function(k){

            if($(this).val()!='')idarr.push($(this).val())

        });


        if (idarr.length <= 0) {
            alert('请选择要打印的图片');
            return;
        }console.log(idarr);
        for (var i = 0; i < idarr.length; i++) {

            $("#"+idarr[i]).removeClass('no-print');
            $("#hei"+idarr[i]).removeClass('no-print');
        }
        //$("input[name=printqrcode]").prop('checked', 1);//,$("#select").prop("checked")
        //$("div.printdiv").removeClass('no-print');//,$("#select").prop("checked")

        idarr = idarr.join(",")
        updatePost(idarr,'/admin/warehouse/getShelvesPdf');
        return;
    }


    function updatePost(ids,url){
        $.post(url,{shelf_code:ids},function(data){

            if(data){
                window.open(data);

            }else{

                alert(data);

            }

        })
    }


    // function print() {
    //     $("input[name=printqrcode]").prop('checked', 1);//,$("#select").prop("checked")
    //     $("div.printdiv").removeClass('no-print');//,$("#select").prop("checked")
    //     $("div.hei").removeClass('no-print');
    //     $("#printcent").print({
    //         globalStyles: true,
    //         mediaPrint: true,
    //         stylesheet: null,
    //         noPrintSelector: ".no-print",
    //         iframe: true,
    //         append: null,
    //         prepend: null,
    //         manuallyCopyFormValues: true,
    //         deferred: $.Deferred(),
    //         timeout: 10000000,
    //         title: null,
    //         doctype: '<!doctype html>'
    //     });
    // }
    // function print_some() {
    //     $("div.printdiv").addClass('no-print');
    //     $("div.hei").addClass('no-print');
    //     var idarr=[];
    //     $('input:checkbox[name=printqrcode]:checked').each(function(k){
    //
    //         if($(this).val()!='')idarr.push($(this).val())
    //
    //     });
    //     if (idarr.length <= 0) {
    //         alert('请选择要打印的图片');
    //         return;
    //     }console.log(idarr);
    //     for (var i = 0; i < idarr.length; i++) {
    //
    //         $("#"+idarr[i]).removeClass('no-print');
    //         $("#hei"+idarr[i]).removeClass('no-print');
    //     }
    //     //$("input[name=printqrcode]").prop('checked', 1);//,$("#select").prop("checked")
    //     //$("div.printdiv").removeClass('no-print');//,$("#select").prop("checked")
    //     $("#printcent").print({
    //         globalStyles: true,
    //         mediaPrint: false,
    //         stylesheet: null,
    //         noPrintSelector: ".no-print",
    //         iframe: true,
    //         append: null,
    //         prepend: null,
    //         manuallyCopyFormValues: true,
    //         deferred: $.Deferred(),
    //         timeout: 90000000,
    //         title: null,
    //         doctype: '<!doctype html>'
    //     });
    // }
    /*document.getElementById('printall').onclick = function () {
        $("input[name=printqrcode]").prop('checked', 1);//,$("#select").prop("checked")
        $("div.printdiv").removeClass('no-print');//,$("#select").prop("checked")

        Print('#printcent', {
            onStart: function () {
                console.log('onStart', new Date())
            },
            onEnd: function () {
                console.log('onEnd', new Date())
            }
        })

    };
    document.getElementById('printsome').onclick = function () {
        $("input[name=printqrcode]").prop('checked', 1);//,$("#select").prop("checked")
        $("div.printdiv").removeClass('no-print');//,$("#select").prop("checked")

        Print('#printcent', {
            onStart: function () {
                console.log('onStart', new Date())
            },
            onEnd: function () {
                console.log('onEnd', new Date())
            }
        })

    };*/
    /*function print() {
        $("input[name=printqrcode]").prop('checked', 1);//,$("#select").prop("checked")
        //$("div.printdiv").removeClass('no-print');//,$("#select").prop("checked")
        $("#printcent").jqprint({
            debug: true,
            importCSS: true,
            printContainer: true,
            operaSupport: false
        });
    }

    function print_some() {
        //$("input[name=printqrcode]").prop('checked', 1);//,$("#select").prop("checked")
        //$("div.printdiv").removeClass('no-print');//,$("#select").prop("checked")
        $("#printcent").jqprint({
            debug: true,
            importCSS: true,
            printContainer: true,
            operaSupport: false
        });
    }*/

    /*jQuery(function($) { 'use strict';
        $("#ele2").find('.print-link').on('click', function() {
            //Print ele2 with default options
            $.print("#ele2");
        });
        document.getElementById('11').onclick = function() {
            $("input[name=printqrcode]").prop('checked', 1);//,$("#select").prop("checked")
            $("div.printdiv").removeClass('no-print');//,$("#select").prop("checked")
            //Print ele4 with custom options
            $("#printcent").print({
                //Use Global styles
                globalStyles : true,
                //Add link with attrbute media=print
                mediaPrint : true,
                //Print in a hidden iframe
                iframe : true,
                //Don't print this
                noPrintSelector : ".printinput",
                //Add this at top
                prepend : "Hello World!!!<br/>",
                //Add this on bottom
                append : "<br/>Buh Bye!",
                //Log to console when printing is done via a deffered callback
                deferred: $.Deferred().done(function() { console.log('Printing done', arguments); })
            });
        };
    });*/

</script>