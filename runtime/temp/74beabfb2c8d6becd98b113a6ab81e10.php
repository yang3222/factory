<?php if (!defined('THINK_PATH')) exit(); /*a:6:{s:78:"D:\project\factory\public/../application/admin\view\epwarehouse\dis_lists.html";i:1592962849;s:60:"D:\project\factory\application\admin\view\common\layout.html";i:1594716292;s:60:"D:\project\factory\application\admin\view\common\member.html";i:1584407225;s:61:"D:\project\factory\application\admin\view\common\topline.html";i:1594716292;s:58:"D:\project\factory\application\admin\view\common\menu.html";i:1584407225;s:61:"D:\project\factory\application\admin\view\common\mapline.html";i:1584407225;}*/ ?>
<!doctype html>
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
</ul>

    $(function(){

        select.select();

    })

</script>
<style type="text/css" media=print>
    .noprint{display : none }
    .yinc td{
        border-left: 1px solid;border-top: 1px solid;
        width:200px;
    }

</style>
<div class="canvas_title do-clear">

    <ul class="tab_btn tab_btn_fl fl">
<!--        <li><a href="javaScript:materialdetail.deleteAll('<?php echo url('/admin/warehouse/materialDetailDel'); ?>')">删除</a></li>-->
<!--        <li><a href="<?php echo url('/admin/warehouse/exportExcel'); ?>" download='物料.xlsx' target="_blank">导出Excel数据</a></li>-->
        <li><a href="javaScript:;" onclick="preview(1,'all')">打印全部</a></li>
        <li><a href="javaScript:;" onclick="preview(1,'select')">打印选中</a></li>

        <li class="input"><input type="text" id="search" name="search" value=""

                                 onkeydown="keyFun.key(event,13,search.search,[$('#search'),'<?php echo url('/admin/epwarehouse/disLists'); ?>'])"

                                 placeholder="搜索：sku" /></li>

        <li><a href="javaScript:search.searchArray(['<?php echo url('/admin/epwarehouse/disLists'); ?>','search'])">搜索</a></li>
        <li><a href="javaScript:materialdetail.deleteAll('<?php echo url('/admin/epwarehouse/canceldisLists'); ?>','批量取消')">批量取消</a></li>

    </ul>

    <?php echo $pageDiv; ?>

</div>

<div class="canvas_intro">
    <!--startprint1-->
    <table class="productli" style="border-bottom:1px solid;text-align: center" border="1" cellspacing="0" cellpadding="0">

        <thead class="noprint">

        <tr>

            <th width="10" class="center">#</th>

            <th width="10" class="center"><input name="" type="checkbox" value="" id="select" /></th>

            <th width="140" class="center">图片</th>
            <th width="140" class="center">订单号二维码</th>
            <th width="140" class="center">sku</th>
            <th width="140" class="center">库位</th>
            <th class="center" width="200">操作</th>

        </tr>

        </thead>

        <tbody>

        <?php if(is_array($data) || $data instanceof \think\Collection || $data instanceof \think\Paginator): $k = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$value): $mod = ($k % 2 );++$k;?>
        <tr class="yinc" style="width: 50%;height: 105px">
            <td class="center noprint"><?php echo $k; ?></td>
            <td class="center noprint"><input name="select" type="checkbox" value="<?php echo $value['sku']; ?>" /></td>
            <td><img style="width: 100px;" src='http://kjds-img.img-cn-shanghai.aliyuncs.com/<?php echo explode(',',$value['whmd']['ep_img'])[0]; ?>@0e_0o_1l_500h_500w.src' /></td>
            <td>
                <?php if(!empty($value['neworder_id'])): ?>
                <img style="width: 100px;" src="<?php echo ROOT_NEWORDER_IMG; ?><?php echo $value['neworder']['OrdNum']; ?>.png" /><br>
                <?php echo $value['neworder']['OrdNum']; endif; ?>
            </td>
            <td><?php echo $value['sku']; ?><br><span>id:</span><?php echo $value['whmd']['skuid']; ?><br><?php echo $value['whmd']['spec']; ?></td>
            <?php $wt_data = getWtdata($value['whmd']['unique_id']); ?>
            <td>
                <?php if(!empty($wt_data)): if(is_array($wt_data) || $wt_data instanceof \think\Collection || $wt_data instanceof \think\Paginator): $kw = 0; $__LIST__ = $wt_data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vw): $mod = ($kw % 2 );++$kw;?>
                <label class='material' id="label_<?php echo $vw['id']; ?>" style='white-space:nowrap;'><?php echo $vw['wt_name']; ?>(<?php echo $vw['count']; ?>件)&nbsp;</label><br>
                <?php endforeach; endif; else: echo "" ;endif; endif; if(!empty($value['neworder_id'])): ?>
                <br><label class='material' style='white-space:nowrap;'><b style="color: green">需要数量:</b><?php echo $value['neworder']['GdsNum']; ?></label>
                <?php endif; ?>
            </td>

            <td class="center operation noprint">
                <a href="javaScript:materialdetail.delete('<?php echo $value['sku']; ?>','<?php echo url('/admin/epwarehouse/canceldisLists'); ?>','取消')">取消</a>
<!--                <a href="javaScript:;" class="printchecked">打印配货单</a>-->
            </td>

        </tr>

        <?php endforeach; endif; else: echo "" ;endif; ?>

        </tbody>
    </table>
    <!--endprint1-->



</div>

    <script language="javascript">

        function preview(oper,type='') {

            if(oper < 10) {
                // $(".yinc").css('float','left');


                var s=document.getElementsByName("select")
                console.log(s)
                // return false;
                for(var i=0;i<s.length;i++){
                    console.log(s[i].parentNode)
                    // return false;
                    if (type == 'select'){
                        if (s[i].checked == false){
                            s[i].parentNode.parentNode.style.display='none';//隐藏不需要打印部分
                        }
                    }

                }

                var o=document.getElementsByClassName("noprint")
                for(var i=0;i<o.length;i++){
                    o[i].style.display='none';//隐藏不需要打印部分
                }
                bdhtml =window.document.body.innerHTML;//获取当前页的html代码

                sprnstr = "<!--startprint" + oper + "-->";//设置打印开始区域

                eprnstr = "<!--endprint" + oper + "-->";//设置打印结束区域

                prnhtml =bdhtml.substring(bdhtml.indexOf(sprnstr) + 18); //从开始代码向后取html

                prnhtml = prnhtml.substring(0,prnhtml.indexOf(eprnstr));//从结束代码向前取html

                window.document.body.innerHTML= prnhtml;

                window.print();

                window.location.reload();
            } else{
                window.print();
            }

        }





    </script>
<div class="canvas_title do-clear">

    <ul class="tab_btn tab_btn_fl fl">

    </ul>

    <?php echo $pageDiv; ?>

</div>