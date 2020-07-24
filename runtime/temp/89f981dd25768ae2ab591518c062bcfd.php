<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:79:"D:\project\factory\public/../application/admin\view\order\prostatus_detail.html";i:1584407225;}*/ ?>
<div class="modelbox">

    <div class="canvas_title do-clear">

        <h3>生产状态详情</h3>

    </div>

    <ul class="infobox do-clear">

        <li><label>生产状态：</label>

            <ul class="factory">

                <?php if(is_array($orderstatus) || $orderstatus instanceof \think\Collection || $orderstatus instanceof \think\Paginator): $i = 0; $__LIST__ = $orderstatus;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$status): $mod = ($i % 2 );++$i;?>

                <li>
                    <?php echo $status['status']; ?>
                    <i><?php echo $status['add_time']; ?></i>
                </li>
                <?php endforeach; endif; else: echo "" ;endif; ?>

            </ul>

        </li>




    </ul>

    <div class="canvas_title do-clear">

        <ul class="tab_btn fr">

            <li><a href="javaScript:openModel.close()">确定</a></li>

        </ul>

    </div>

</div>