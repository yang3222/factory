<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:77:"D:\project\factory\public/../application/admin\view\software\umbrellatmp.html";i:1584407225;}*/ ?>
<?php echo '<?'; ?>
xml version="1.0" encoding="utf-8"?>
<data>
   <Id><?php echo $data['id']; ?></Id>
   <Img><?php echo WEB; ?><?php echo PRODUCT_IMG; ?><?php echo $data['imgurl']; ?></Img>
   <Name><?php echo $data['name']; ?></Name>
   <ClothWidth><?php echo $data['cloth']; ?></ClothWidth>
   <Resolution><?php echo $data['resolution']; ?></Resolution>
   <Spacing><?php echo $data['spacing']; ?></Spacing>
   <UmbrellaNum><?php echo $data['umbrellanum']; ?></UmbrellaNum>
   <Factory><?php echo $data['factory']; ?></Factory>
   <Remove><?php echo $data['remove']; ?></Remove>
   <Umbrella><?php echo WEB; ?><?php echo PRODUCT_IMG; ?><?php echo $data['umbrellaurl']; ?></Umbrella>
   <?php echo $data['deformation']; ?>
</data>