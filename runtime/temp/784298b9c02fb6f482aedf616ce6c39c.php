<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:80:"D:\project\factory\public/../application/admin\view\warehouse\get_materials.html";i:1584407226;}*/ ?>
<style>.modelboxsmall{width:30%; background:#fff; -moz-border-radius: 4px; -webkit-border-radius: 4px; border-radius:4px; margin:0 auto; overflow:hidden; margin-top:100px;}  </style><div class="modelboxsmall">    <div class="canvas_title do-clear">        <ul class="tab_btn tab_btn_fl fl">            <li class="input">                    &nbsp;&nbsp<?php if($_REQUEST['status'] == 1): ?>已规划入库<?php else: ?>已入库<?php endif; ?>            </li>        </ul>        <ul class="tab_btn fr">        </ul>    </div>    <div class="libox">        <ul class="model_li do-clear" id="model_li">                        <div class="canvas_intro">             <table class="productli">            <thead>            <tr>                <th width="10" class="center">#<input type='hidden' id='wt_id' value="<?php if(!empty($_REQUEST['wt_id'])): ?><?php echo $_REQUEST['wt_id']; endif; ?>"></th>                               <th width="80" class="center">名称</th>                <th width="80" class="center">规格</th>                <th width="80" class="center">属性</th>                <th width="50" class="center" style='white-space:nowrap'>数量/单位</th>                <th width="50" class="center">价格</th>                           </tr>        </thead>        <tbody>            <?php if($type=='bath'): if(empty($data) && $type=='bath'): ?> <tr > <td align='center' colspan="6">空 </td></tr><?php endif; if(is_array($data) || $data instanceof \think\Collection || $data instanceof \think\Paginator): $k = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$value): $mod = ($k % 2 );++$k;?>                       <tr >                <td><?php echo $k; ?><input type="hidden" value="<?php echo $value['id']; ?>"></td>                                                     <td class="materialname" ><label><?php echo $value['wtm_name']; ?> </label></td>                                          <td class="center" ><?php echo $value['size']; ?></td>                     <td class="center"> <?php echo $value['att']; ?></td>                                          <td class="center" ><?php echo $value['count']; ?><?php echo $value['company']; ?></td>                     <td class="center" ><?php echo $value['money']; ?></td>                 </tr>            <?php endforeach; endif; else: echo "" ;endif; endif; if($type=='one'): ?>            <tr >                                <td class="materialname" width='190px' id="addmaterial_name_<?php echo $data['0']['id']; ?>"><label><?php echo $data['0']['name']; ?></label>(有<?php echo $data['0']['count']; ?><?php echo $data['0']['company']; ?>可计划入库)</td>                <td class="center" id="addcompany_<?php echo $data['0']['id']; ?>"><input type="button" class="oper" id='addbtnasc_<?php echo $data['0']['id']; ?>' value="-"><input type="hidden" id='addc_<?php echo $data['0']['id']; ?>' value='<?php echo $data['0']['count']; ?>'>                    <input type="text" size="1px" class='addcountid' id='addcount_<?php echo $data['0']['id']; ?>' readonly="readonly" value='<?php echo $data['0']['count']; ?>'><input class="oper" id='addbtndesc_<?php echo $data['0']['id']; ?>' type="button" value="+"></td>                <td class="center" id="addcompany_<?php echo $data['0']['id']; ?>"><?php echo $data['0']['company']; ?></td>            </tr>            <?php endif; ?>            </tbody>    </table>                  </div>        </ul>    </div>    <div class="canvas_title do-clear">        <ul class="tab_btn fr">            <?php if($type=='one'): $method = 'postCount'; else: $method = 'bathPostCount'; endif; ?>                            <li style="display: none"><a href="javaScript:warehousetable.<?php echo $method; ?>(<?php echo $m_id; ?>,<?php echo $wtm_id; ?>)">确定</a></li>            <li><a href="javaScript:openModel.close()">关闭</a></li>        </ul>    </div></div><script>    $(function(){                $(".oper").click(function(){        	                      var arr = $(this).attr('id').split('_');                      var pre = (arr[0] == 'btndesc' || arr[0] == 'btnasc' )?'':'add';                      var res = arr[0] == pre+"btndesc"? 1:-1;                      var obj = $('#'+pre+'count_'+arr[1]);                          if(parseInt(obj.val())+res <= 0 ){               obj.val('0');               return ;           }                      if(parseInt(obj.val())+res >= $('#'+pre+'c_'+arr[1]).val()){        	   obj.val($('#'+pre+'c_'+arr[1]).val());               return ;           }                                 obj.val(parseInt(obj.val() == ''? 0:obj.val())+res);                                         })    })</script>