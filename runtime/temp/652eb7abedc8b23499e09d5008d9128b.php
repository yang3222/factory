<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:87:"D:\project\factory\public/../application/admin\view\warehouse\get_materials_detail.html";i:1584407226;}*/ ?>
<link rel="stylesheet" href="<?php echo ADMIN_STYLE_URL; ?>css/warehouse.css" type="text/css" media="screen" /><style>.modelboxsmall{width:50%; background:#fff; -moz-border-radius: 4px; -webkit-border-radius: 4px; border-radius:4px; margin:0 auto; overflow:hidden; margin-top:100px;}  .span{display:inline-block; height:22px; line-height:22px; padding:0 10px; -moz-border-radius: 4px; -webkit-border-radius: 4px; border-radius:4px; background:#5bb75b; color:#fff; font-size:12px;}.spanclose{display:inline-block; height:22px; line-height:22px; padding:0 10px; -moz-border-radius: 4px; -webkit-border-radius: 4px; border-radius:4px; background:#da4f49; color:#fff; font-size:12px;}</style><div class="modelboxsmall">    <div class="canvas_title do-clear">        <ul class="tab_btn fr">            <li><a href="javaScript:openModel.close()">关闭</a></li>        </ul>    </div>    <div class="libox">        <ul class="model_li do-clear" id="model_li">                        <div class="canvas_intro">            <table class="productli">            <thead>            <tr>                <th width="10" class="center">#</th>                                <th  width="90" >名称</th>                <th width="80" class="center">仓位</th>                                <th width="80" class="center">数量</th>                <th width="50" class="center">供应商</th>                <th width="50" class="center">日期</th>                                <th width="50" class="center">状态</th>                           </tr>        </thead>        <tbody>            <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $k = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$value): $mod = ($k % 2 );++$k;?>                       <tr >                                 <td><?php echo $k; ?></td>                                <td class="materialname"  id="material_name_<?php echo $value['id']; ?>"><label  for="material<?php echo $k; ?>"><?php echo $value['name']; ?></label></td>                <td class="center"><?php echo $value['warehouse_num']; ?></td>                                 <td class="center"><?php echo $value['count']; ?><?php echo $value['company']; ?></td>                <td class="center"><?php echo $value['upplier']['company']; ?></td>                <td class="center" ><?php echo $value['create_time']; ?></td>                <td class="center"><?php if($value['status']=='1'): ?><span class="span">合格</span><?php else: ?><span class="spanclose">瑕疵品</span><?php endif; ?></td>            </tr>            <?php endforeach; endif; else: echo "" ;endif; ?>        </tbody>    </table>            </div>        </ul>    </div>    <div class="canvas_title do-clear">                <ul class="tab_btn tab_btn_fl fl">                       <ul style='width:100%'>&nbsp;&nbsp;<?php echo $count; ?>                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;                <label style='width:100%'><?php echo $warehouse_num; ?></label></ul>        </ul>        <ul class="tab_btn fr" style=''>            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label style='width:100%'><?php echo $nquestionData['cc']; ?>/</label><label style='width:100%;color: red'><?php echo $questionData['cc']; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>             &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;        </ul>    </div></div>