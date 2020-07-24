<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:76:"D:\project\factory\public/../application/admin\view\factory\openwindows.html";i:1584407225;}*/ ?>
<div class="modelbox">
    <div class="canvas_title do-clear">
        <ul class="tab_btn fr">
            <li><a href="javaScript:order.selectfactory()">确定</a></li>
            <li><a href="javaScript:openModel.close()">关闭</a></li>
        </ul>
    </div>
    <div class="libox">
        <div class="factory" id="factorys">
            <?php if(is_array($factorys) || $factorys instanceof \think\Collection || $factorys instanceof \think\Paginator): $k = 0; $__LIST__ = $factorys;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$value): $mod = ($k % 2 );++$k;?>
            <input type="checkbox" name="factory"  id="factory<?php echo $k; ?>" value="<?php echo $value['id']; ?>"><label for="factory<?php echo $k; ?>"><?php echo $value['userinfo']['Name']; ?></label>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </div>
    </div>
    <div class="canvas_title do-clear">
        <ul class="tab_btn fr">
            <li><a href="javaScript:order.selectfactory()">确定</a></li>
        </ul>
    </div>
</div>
<script>order.selectFactory();</script>