<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:71:"D:\project\factory\public/../application/index\view\business\index.html";i:1591151060;}*/ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>导出业务报表</title>
    <script type="text/javascript" src="<?php echo ADMIN_STYLE_URL; ?>js/jquery.min.js"></script>
    <script>
        function importent(){
            if($("#file_stu").val()==""){
                alert("请选择要计算的文件");
                return false;
            }
            return true;
        }
        function importent2() {
            if($("#file_stu2").val()==""){
                alert("请选择要计算的文件");
                return false;
            }
            return true;
        }
    </script>
</head>

<body>
<?php if($type=='treasurer' || $type=='operate'): ?>
<form method="post" action="<?php echo url('/index/business/newimportexcel','type='.$type); ?>" enctype="multipart/form-data"  onsubmit="return importent2();" >
    <h3>日期范围报表：</h3><input  type="file" name="file" id="file_stu2" />
    <input type="submit"  value="导入" />
</form>
<?php else: ?>
<form method="post" action="<?php echo url('/index/business/importexcel','type='.$type); ?>" enctype="multipart/form-data"  onsubmit="return importent();" >
    <h3>导入Excel表：</h3><input  type="file" name="file" id="file_stu" />
    <input type="submit"  value="导入" />
</form>
<?php endif; ?>
</body>
</html>
