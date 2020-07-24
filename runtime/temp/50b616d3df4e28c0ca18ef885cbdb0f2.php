<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:80:"D:\project\factory\public/../application/admin\view\software\umbrellaconfig.html";i:1584407225;}*/ ?>
<?php echo '<?'; ?>
xml version="1.0" encoding="utf-8"?>
<!--版本信息以及配置-->
<config>
   <!--雨伞列表-->
   <Umbrella><?php echo url("/admin/Software/umbrellalist"); ?></Umbrella>
   <!--工厂列表-->
   <Factory><?php echo url("/admin/Software/factory"); ?></Factory>
   <!--雨伞模板数据-->
   <UmbrellaTmp><?php echo url("/admin/Software/umbrellatmp"); ?></UmbrellaTmp>
   <!--上次预览图-->
   <UploadBrowse><?php echo url("/admin/Software/uploadbrowse"); ?></UploadBrowse>
   <!--上传模板-->
   <UploadTmp><?php echo url("/admin/Software/uploadtmp"); ?></UploadTmp>
   <!--提交数据-->
   <PostData><?php echo url("/admin/Software/umbrellasave"); ?></PostData>
</config>