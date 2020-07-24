<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:83:"D:\project\factory\public/../application/admin\view\warehouse\showmaterialedit.html";i:1584407226;}*/ ?>
<style>
  .label {
    
  }
  .add_from li {
    line-height: 28px;
    font-size: 14px;
    min-height: 28px;
    position: relative;
    padding-left: 105px;
    float: left;
    min-width: 230px;
    margin-bottom: 5px;
  }
  .add_from li label {
    display: inline-block;
    width: 100px;
    font-weight: bold;
    text-align: right;
    position: absolute;
    left: 0;
    top: 0;
    color: #F90;
  }
</style>
<div class="modelbox">

    <div class="canvas_title do-clear">

        <h3>材料信息</h3>

    </div>

<!--<div class="canvas_title do-clear">

     <ul class="tab_nav fl">

        <li class="current"><a href="javaScript:void(0)">材料信息</a></li>

        <li ><a href="">供应商</a></li> 

    </ul>

</div>-->



    <ul class="infobox do-clear" style=""><!-- margin: 10px 20px 30px 60px;font-size: 18px;text-align: left; -->

        <li>

           <label class="label">财务编码：</label>

           <input type="hidden" id="id" name="id" value="<?php echo $data['id']; ?>" />

          <span><?php echo $data['finance_num']; ?></span>

        </li>

        <li>

           <label class="label">物料名称：</label>

          <span><?php echo $data['name']; ?></span>

       </li>

       <li>

           <label class="label">材料类型：</label>

           <span><?php echo $data['type']; ?></span>

       </li>

       <li>

           <label class="label">型号/规格：</label>

           <span><?php echo $data['size']; ?></span>

       </li>

       <li>

           <label class="label">物料属性：</label>

           <span><?php echo $data['att']; ?></span>

       </li>

       <li>

           <label class="label">单位：</label>

           <span><?php echo $data['company']; ?></span>

       </li>

       <li>

           <label class="label">大致价格：</label>

           <span><?php echo sprintf('%.2f',$data['money']); ?></span>

       </li>

       <li>

           <label class="label">备货周期：</label>

           <span><?php echo $data['choice']; ?>天</span>

       </li>

       <li>

           <label class="label">状态：</label>
           <?php if($data['display']=='1'): ?>
               <span>上架</span>
               <?php else: ?>
               <span>下架</span>
          <?php endif; ?>

       </li>
       <li>
         <label class="label">供应商：</label>
         <ul class="factory">

         <?php if(is_array($data['materialupplier']) || $data['materialupplier'] instanceof \think\Collection || $data['materialupplier'] instanceof \think\Paginator): $k = 0; $__LIST__ = $data['materialupplier'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$upplier): $mod = ($k % 2 );++$k;?>
         <li>
          <h4><b><?php echo $upplier['company']; ?></b></h4>
          <i>Tel:<?php echo $upplier['tel']; ?></i>
          <i>类型:<?php if($upplier['type']=='1'): ?>布料供应<?php elseif($upplier['type']=='2'): ?>辅料供应<?php else: ?>材料加工<?php endif; ?></i>
        </li>
        <?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
       </li>

           <!-- <a href="javaScript:material.save('<?php echo url('/admin/warehouse/save'); ?>')">保存</a>
 -->
       

    </ul>




<div class="canvas_title do-clear">

        <ul class="tab_btn fr">

            <li><a href="javaScript:openModel.close()">确定</a></li>

        </ul>

    </div>
</div>