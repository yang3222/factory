<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:80:"D:\project\factory\public/../application/admin\view\software\umbrellaupload.html";i:1584407225;}*/ ?>
<?php echo '<?'; ?>
xml version="1.0" encoding="utf-8"?>
<data>
   <error><?php if($success): ?>1<?php else: ?>0<?php endif; ?></error>
   <?php if(isset($tip)): ?><tip><?php echo $tip; ?></tip><?php endif; if(isset($url)): ?><url><?php echo $url; ?></url><?php endif; ?>
</data>