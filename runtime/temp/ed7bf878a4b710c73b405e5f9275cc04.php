<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:95:"D:\project\factory\public/../application/admin\view\warehouse\show_material_detail_upplier.html";i:1584407226;}*/ ?>
<style>.modelboxsmall{width:50%; background:#fff; -moz-border-radius: 4px; -webkit-border-radius: 4px; border-radius:4px; margin:0 auto; overflow:hidden; margin-top:100px;}  .modelboxsmall .libox{height:450px; overflow:hidden; overflow-y:auto;}.modelboxsmall ul.model_li{padding:5px;}.modelboxsmall ul.model_li li{float:left;  margin:5px; }.modelboxsmall ul.model_li li input[type='checkbox'] +label{display: inline-block; overflow: hidden; background:#efefef; width:150px; padding:5px; cursor:pointer; -moz-border-radius: 4px; -webkit-border-radius: 4px; border-radius:4px;}.modelboxsmall ul.model_li li img{width:150px; height:150px;}.modelboxsmall ul.model_li li input[type='checkbox']{display: none;}.modelboxsmall ul.model_li li h3{font-weight:normal; text-align:center; line-height:24px; height:24px; color:#333; font-size:14px; }.modelboxsmall ul.model_li li input[type='checkbox'] +label:hover{background:#ccc;}.modelboxsmall ul.model_li li input[type='checkbox']:checked +label{background:#0CF; }</style><?php if(!isset($_REQUEST['material_id']) || empty($_REQUEST['material_id'])): ?>  <link rel="stylesheet" href="<?php echo ADMIN_STYLE_URL; ?>css/jquery-ui.min.css"><script type="text/javascript" src="<?php echo ADMIN_STYLE_URL; ?>js/jquery-ui.min.js"></script> <script>  $(function() {    var names = [<?php if(is_array($autoquery) || $autoquery instanceof \think\Collection || $autoquery instanceof \think\Paginator): $k = 0; $__LIST__ = $autoquery;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($k % 2 );++$k;?>"<?php echo $v; ?>",<?php endforeach; endif; else: echo "" ;endif; ?>];     var accentMap = {         };    var normalize = function( term ) {      var ret = "";      for ( var i = 0; i < term.length; i++ ) {        ret += accentMap[ term.charAt(i) ] || term.charAt(i);      }      return ret;    };     $( "#window_search" ).autocomplete({      source: function( request, response ) {        var matcher = new RegExp( $.ui.autocomplete.escapeRegex( request.term ), "i" );        response( $.grep( names, function( value ) {          value = value.label || value.value || value;          return matcher.test( value ) || matcher.test( normalize( value ) );        }) );      }    });  });  </script>     <?php endif; ?><div class="modelboxsmall">    <div class="canvas_title do-clear">        <ul class="tab_btn tab_btn_fl fl">                 <?php if(!isset($_REQUEST['material_id']) || empty($_REQUEST['material_id'])): ?>          <li class="input"><input type="text" id="window_search" name="window_search" value="" onkeydown="keyFun.key(event, 13, openModel.search)" placeholder="搜索：名称,产品ID">                    </li>     <li><a href="javaScript:openModel.search();">搜索</a></li>     <?php endif; ?>    </ul>        <ul class="tab_btn fr">            <li><a href="javaScript:material.config()">确定</a></li>            <li><a href="javaScript:openModel.close()">关闭</a></li>        </ul>    </div>    <div class="libox">        <ul class="model_li do-clear" id="model_li">                        <div class="canvas_intro">            <table class="productli">        <thead>            <tr>                <th width="10" class="center">#</th>                <th width="10" class="center"><input name="" type="radio" value="" id="select" /></th>                <th>供应商</th>                <th>地址</th>                <th>联系人</th>                <th>电话</th>                <th>商家类型</th>            </tr>        </thead>        <tbody>                        <?php if(is_array($data['materialupplier']) || $data['materialupplier'] instanceof \think\Collection || $data['materialupplier'] instanceof \think\Paginator): $k = 0; $__LIST__ = $data['materialupplier'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$upplier): $mod = ($k % 2 );++$k;?>             <tr>                <td class="center"><?php echo $k; ?></td>                <td class="center"><input name="select" type="radio" value="<?php echo $upplier['id']; ?>" id="upplierid_<?php echo $upplier['id']; ?>" /></td>                <td id='upplier_name_<?php echo $upplier['id']; ?>'><label for="upplierid_<?php echo $upplier['id']; ?>"><?php echo $upplier['company']; ?></label></td>                <td><?php echo $upplier['adress']; ?></td>                <td><?php echo $upplier['contacts']; ?></td>                <td><?php echo $upplier['tel']; ?></td>                <td><?php if($upplier['type']=='1'): ?>布料供应<?php elseif($upplier['type']=='2'): ?>辅料供应<?php else: ?>材料加工<?php endif; ?></td>            </tr>            <?php endforeach; endif; else: echo "" ;endif; ?>        </tbody>    </table>            </div>        </ul>    </div>    <div class="canvas_title do-clear">        <ul class="tab_btn fr" >                       <li><a href="javaScript:material.config()">确定</a></li>                       </ul>    </div></div>