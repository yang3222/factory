<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:74:"D:\project\factory\public/../application/admin\view\member\edit_group.html";i:1584407225;}*/ ?>
<style>
    .add_from>li label.label {
        position: absolute;
        left: 0;
        top: 0;
        text-align: right;
        width: 210px;
        height: 52px;
        line-height: 52px;
        font-size: 14px;
    }
    .add_from>li {
        border-bottom: 1px solid #f5f5f5;
        min-height: 22px;
        padding: 10px 0;
        position: relative;
        padding-left: 217px;
    }
    .add_from li>input[type='text'] {
        border: 1px solid #cccccc;
        -moz-border-radius: 4px;
        -webkit-border-radius: 4px;
        border-radius: 4px;
        height: 30px;
        width: 400px;
        padding: 0 5px;
        color: #666;
    }
    .add_from li label.label i {
        color: #ff0000;
        text-decoration: none;
        font-size: 14px;
        font-weight: bold;
    }
</style>
<div class="modelbox">
    <div class="canvas_title do-clear">
        <ul class="tab_btn fr">
            <li><a href="javaScript:openeditgroup.close()">关闭</a></li>
        </ul>
    </div>
    <div class="canvas_intro">
        <input name="id" type="hidden" value="<?php if(!empty($editgroup)): ?><?php echo $editgroup['id']; endif; ?>" id="id" />
        <ul class="add_from">

            <li>
                <label class="label">用户组名称<i>*</i>：</label>

                <input name="title_name" type="text" value="<?php if(!empty($editgroup['title'])): ?><?php echo $editgroup['title']; endif; ?>" id="title_name" />

            </li>
            <li>
                <label class="label">管理组类型<i>*</i>：</label>

                <select name="types" id="types">
                    <option value="1" <?php if($editgroup['types']=='1'): ?>selected="selected"<?php endif; ?>>管理员</option>
                    <option value="2" <?php if($editgroup['types']=='2'): ?>selected="selected"<?php endif; ?>>工厂</option>
                </select>

            </li>

        </ul>

    </div>
    <div class="canvas_title do-clear">
        <ul class="tab_btn fr">
            <?php if(!empty($editgroup)): ?>
            <li><a href="javaScript:openeditgroup.update_name()">保存</a></li>
            <?php else: ?>
            <li><a href="javaScript:openeditgroup.save_name()">保存</a></li>
            <?php endif; ?>
        </ul>
    </div>
</div>