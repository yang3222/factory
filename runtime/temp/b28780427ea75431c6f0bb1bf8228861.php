<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:69:"D:\project\factory\public/../application/index\view\fbanew\index.html";i:1584407223;}*/ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>导出FBA数据</title>
<script type="text/javascript" src="<?php echo ADMIN_STYLE_URL; ?>js/jquery.min.js"></script>
<script>

function importent(){
	if($("#file_stu").val()==""){
		alert("请导入 日期范围报告 Date Range Reports：");
		return false;
	}
        if($("#file_Reconciliation").val()==""){
		alert("请导入 库存调整 Inventory Reconciliation：");
		return false;
	}
        if($("#file_fba_old").val()==""){
		alert("请导入 上个月的 每月库存历史记录 Monthly Inventory History：");
		return false;
	}
        if($("#file_fba").val()==""){
		alert("请导入 上年度12月份 每月库存历史记录 Monthly Inventory History：");
		return false;
	}
        if($("#file_fba_user").val()==""){
		alert("请导入 本年度配货量（业务员）：");
		return false;
	}
	return true;
}

</script>
<style>
        select,input[type='text']{width:200px; height: 25px; border: 1px solid #ccc; padding: 0 10px; border-radius: 4px;}
        input[type='checkbox']{width:20px; height:20px;}
        span{margin-left:20px;}
</style>
</head>

<body>
  <form method="post" action="<?php echo url('/index/fbanew/postExcel'); ?>" enctype="multipart/form-data"  onsubmit="return importent();" >
      选择站点
      <select class="selectinput" name="country">
          <option value="US">美国</option>
          <option value="CA">加拿大</option>
          <option value="MX">墨西哥</option>
          <option value="GB">英国</option>
          <option value="DE">德国</option>
          <option value="FR">法国</option>
          <option value="IT">意大利</option>
          <option value="ES">西班牙</option>
          <option value="JP">日本</option>
          <option value="AU">澳大利亚</option>
      </select>
      <span>选择币种</span>
      <select class="selectinput" name="money">
          <option value="USD">美元</option>
          <option value="CAD">加元</option>
          <option value="MXN">墨西哥比索</option>
          <option value="GBP">英镑</option>
          <option value="EUR">欧元</option>
          <option value="JPY">日元</option>
          <option value="AUD">澳大利亚元</option>
      </select>
      <br/>
      <br/>
      下单开始时间：<input type="text" name="start_time" id="start_time" value="<?php echo $time['start']; ?>" />
      下单结束时间：<input type="text" name="end_time" id="end_time" value="<?php echo $time['end']; ?>" />
      <br/><br/>
      输出业务名称：<input type="checkbox" name="user" id="user" />
      <h3>导入 本年度配货量（业务员）：</h3><input  type="file" name="file_fba_user" id="file_fba_user" />
      <h3>导入 库存调整 Inventory Reconciliation：</h3><input  type="file" name="file_Reconciliation" id="file_Reconciliation" />
      <h3>导入 上年度12月份 每月库存历史记录 Monthly Inventory History：</h3><input  type="file" name="file_fba" id="file_fba" />
      <h3>导入 上月度 每月库存历史记录 Monthly Inventory History：</h3><input  type="file" name="file_fba_old" id="file_fba_old" />
      <h3>导入 日期范围报告 Date Range Reports：</h3><input  type="file" name="file_stu" id="file_stu" />
      <br/><br/>
      <input type="submit"  value="导入" style="width:100px;" />
      <div class="tip">
          <h3>注意事项：</h3>
          <p>1、请选择站点和正确的日期时间</p>
          <p>2、请导入对应的5份文件，缺一不可</p>
          <p>3、SKU为飞飞鱼后台下单用的SKU，如：g123456p12c34s56</p>
          <p>4、文件命名格式为：店铺号-店名-FBA库存表-日期-姓名，如：28-ALAZA-FBA库存明细-201904-王小明</p>
          <p>5、输出业务名称：若为多人共管账户，可勾选</p>
          <p>6、<a href="https://mubu.com/doc/1AYM_tX_cU" target="_blank">点击查看教程</a></p>
      </div>
  </form>
</body>
</html>
