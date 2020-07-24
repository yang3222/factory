<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:76:"D:\project\factory\public/../application/admin\view\product\openwindows.html";i:1584407225;}*/ ?>
<div class="modelbox">
    <div class="canvas_title do-clear">
        <ul class="tab_btn tab_btn_fl fl">
            <li class="input">
                <input type="text" id="window_search" name="window_search" value="" onkeydown="keyFun.key(event, 13, openModel.search)" placeholder="搜索：名称,产品ID">
            </li>
            <li><a href="javaScript:openModel.search()">搜索</a></li>
        </ul>
        <ul class="tab_btn fr">
            <li><a href="javaScript:order.selectproduct()">确定</a></li>
            <li><a href="javaScript:openModel.close()">关闭</a></li>
        </ul>
    </div>
    <div class="libox">
        <ul class="model_li do-clear" id="model_li">
            <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $k = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$value): $mod = ($k % 2 );++$k;?>
            <li>
                <input type="checkbox" name="produc"  id="product<?php echo $k; ?>" value="<?php echo $value['product_id']; ?>">
                <label for="product<?php echo $k; ?>">
                    <?php if(strstr($value['smallimg'],'http')): ?><img src='<?php echo $value['smallimg']; ?>@0e_0o_1l_500h_500w.src' /><?php else: ?><img src='<?php echo PRODUCT_IMG; ?><?php echo $value['smallimg']; ?>' /><?php endif; ?>
                    <h3><?php echo $value['name']; ?></h3>
                </label>
            </li>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
    </div>
    <div class="canvas_title do-clear">
        <ul class="tab_btn fr">
            <li><a href="javaScript:order.selectproduct()">确定</a></li>
        </ul>
    </div>
</div>
<script>order.selectStart();</script>