<?php if (!defined('THINK_PATH')) exit(); /*a:6:{s:66:"D:\project\factory\public/../application/admin\view\ybp\index.html";i:1584407225;s:60:"D:\project\factory\application\admin\view\common\layout.html";i:1594716292;s:60:"D:\project\factory\application\admin\view\common\member.html";i:1584407225;s:61:"D:\project\factory\application\admin\view\common\topline.html";i:1594716292;s:58:"D:\project\factory\application\admin\view\common\menu.html";i:1584407225;s:61:"D:\project\factory\application\admin\view\common\mapline.html";i:1584407225;}*/ ?>
<!doctype html>
      <h1><img src="<?php echo ADMIN_STYLE_URL; ?>images/logo.png" /></h1>
      <ul class="rightbox">
          <audio id="fba_music">
              <source src="<?php echo ROOT_FBA_MUSIC_SRC; ?>555982.mp3" >
              Your browser does not support the audio element.
          </audio>
          <li class="fba_msg" id="fba_auto_msg_li" style="display: none;">
              <a href="javaScript:;" class="fba_msg"><span>新消息</span></a>
              <ul class="nav" id="fba_auto_msg_ul">

              </ul>
              <!--<audio id="fba_music" src="<?php echo ROOT_FBA_MUSIC_SRC; ?>555982.mp3" ></audio>--><!--autoplay="autoplay" loop="loop"-->
          </li>
         <li>
            <a href="javaScript:;" class="fa move_over"><span><?php echo session('admin_name'); ?></span></a>
            <ul class="nav">
               <li><a href="<?php echo url('/admin/login/logout'); ?>">退出登录</a></li>
               <li><a href="<?php echo url('/admin/acount/editpwd'); ?>">修改密码</a></li>
            </ul>
         </li>
         <!--<li class="delete"><a href="#">删除缓存</a></li>-->
      </ul>
   </div>
<button id="fba_btn_mn" style="display: none;"></button>

    <?php if(is_array($menu) || $menu instanceof \think\Collection || $menu instanceof \think\Paginator): $mkey = 0; $__LIST__ = $menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$value): $mod = ($mkey % 2 );++$mkey;?>
    <li <?php if($currentMenu['menu']==$key): ?>class="open"<?php endif; ?>>
        <?php $menuKey = $key; ?>
        <a href="<?php if(isset($value['url'])): ?><?php echo $value['url']; else: ?>javaScript:;<?php endif; ?>"><i class="<?php echo $value['class']; ?>"></i><?php echo $value['title']; ?></a>
        <?php if(!empty($value['nav'])): ?>
        <ul>
        <?php if(is_array($value['nav']) || $value['nav'] instanceof \think\Collection || $value['nav'] instanceof \think\Paginator): $navKey = 0; $__LIST__ = $value['nav'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$li): $mod = ($navKey % 2 );++$navKey;?>
            <li <?php if($currentMenu['nav']==$key&&$currentMenu['menu']==$menuKey): ?>class="current"<?php endif; ?>><a href="<?php echo $li['url']; ?>" <?php if(isset($li['target'])): ?>target="<?php echo $li['target']; ?>"<?php endif; ?> ><?php echo $li['title']; ?></a></li>
        <?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
        <?php endif; ?>
    </li>
    <?php endforeach; endif; else: echo "" ;endif; ?>
</ul>

<script type="text/javascript" src="<?php echo USER_STYLE_URL; ?>js/date.js"></script>
<script src="<?php echo USER_STYLE_URL; ?>js/echarts.js"></script>

<script type="text/javascript">
 $(function(){
        $('.custom-date').dateRangePicker($("input[name='start_date']"),$("input[name='end_date']"));
        order.productsearch="<?php echo $searchproduct; ?>".split(",");
        select.select();
    })
  </script>


<div class="canvas_title do-clear">

    

    <ul class="tab_btn tab_btn_fl fr">

        <li><a href="javaScript:openModel.openWindow('<?php echo url('/admin/product/openwindows'); ?>')">按产品</a></li>

        <li class="date">

            <select class='custom-date' name='custom_date' id="date">

                <option value='7'  selected='selected'>7天</option>

                <option value='30' >30 天</option>

                <option value='90' >90 天</option>

                <option value='180' >180 天</option>

                <option value='365' >365 天</option>

                <option value='custom' >自定义</option>

            </select>

            <input type="hidden" name="start_date" id="start_date" value="<?php echo $date['start_date']; ?>">

            <input type="hidden" name="end_date" id="end_date" value="<?php echo $date['end_date']; ?>">

        </li>
        
        

      <li><a href="javaScript:order.search([$('#search'),'<?php echo url('/admin/ybp/index','type='.$sign); ?>'])">搜索</a></li>

    </ul>

</div>
<div id="chartmain" style="height:400px"></div>
<script type="text/javascript">
     
 //指定图标的配置和数据
        var option = {
            title:{
                text:''
            },
            tooltip:{
                 trigger: "item",
                 formatter: " {b}<br/>{a} : {c}件"
            },
            legend:{
                data:['用户来源']
            },
            xAxis:{
                data:[<?php echo $dataStr; ?>]
            },
            yAxis:{

            },
            series:[{
                name:'订单量',
                type:'line',
                data:[<?php echo $orderCount; ?>]
            }]
        };
        //初始化echarts实例
        var myChart = echarts.init(document.getElementById('chartmain'));

        //使用制定的配置项和数据显示图表
        myChart.setOption(option);

</script>