<?php if (!defined('THINK_PATH')) exit(); /*a:6:{s:79:"D:\project\factory\public/../application/admin\view\productionmanage\index.html";i:1585097840;s:60:"D:\project\factory\application\admin\view\common\layout.html";i:1594716292;s:60:"D:\project\factory\application\admin\view\common\member.html";i:1584407225;s:61:"D:\project\factory\application\admin\view\common\topline.html";i:1594716292;s:58:"D:\project\factory\application\admin\view\common\menu.html";i:1584407225;s:61:"D:\project\factory\application\admin\view\common\mapline.html";i:1584407225;}*/ ?>
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
    .modelbox .libox .factory input[type='radio']{
        display: none;
    }
    .modelbox .libox .factory input[type='radio'] +label{
        -moz-border-radius: 4px;
        -webkit-border-radius: 4px;
        border-radius:4px;
        display:inline-block;
        cursor: pointer;
        height: 39px;
        margin-bottom: 12px;
        margin-right: 12px;
        line-height: 36px;
        padding: 0 24px;
        background: #fff;
        border: 1px solid #ccc;
    }
    .modelbox .libox .factory input[type='radio'] +label:hover{
        border:1px solid #5eb1e5;
    }
    .modelbox .libox .factory input[type='radio']:checked +label{
        border:1px solid #5eb1e5;
        background:#5eb1e5;
        color: #fff;
    }
</style>
<script type="text/javascript" src="<?php echo ADMIN_STYLE_URL; ?>js/jquery.min.js"></script>

<div class="canvas_title do-clear">
    <ul class="tab_btn fr">

        <li><a href="javaScript:factory.searchOrder();">下一步</a></li>

        <!--<li><a href="javaScript:openModel.close()">关闭</a></li>-->

    </ul>
</div>
<div class="modelbox">
<!--<div class="canvas_title do-clear">-->



<!--</div>-->

<div class="libox">

    <div class="factory" id="Printfactorys">
        <span >印花厂：</span>
        <?php if(is_array($pri) || $pri instanceof \think\Collection || $pri instanceof \think\Paginator): $k = 0; $__LIST__ = $pri;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$value): $mod = ($k % 2 );++$k;?>

        <input type="radio" name="Printfactory"  id="Printfactory<?php echo $k; ?>" value="<?php echo $value['id']; ?>"><label for="Printfactory<?php echo $k; ?>"><?php echo $value['userinfo']['Name']; ?></label>

        <?php endforeach; endif; else: echo "" ;endif; ?>

    </div>

    <div class="factory" id="Macfactorys">
        <span>加工厂：</span>
        <?php if(is_array($fac) || $fac instanceof \think\Collection || $fac instanceof \think\Paginator): $k = 0; $__LIST__ = $fac;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$value): $mod = ($k % 2 );++$k;?>

        <input type="radio" name="Macfactory"  id="Macfactory<?php echo $k; ?>" value="<?php echo $value['id']; ?>"><label for="Macfactory<?php echo $k; ?>"><?php echo $value['userinfo']['Name']; ?></label>

        <?php endforeach; endif; else: echo "" ;endif; ?>

    </div>

</div>

<input type='hidden' id='orderid' value=''>
</div>

<script>
    //单选框取消功能
    $("input[type='radio']").on('click', function() {
        if ($(this).data('ischecked') == true) {
            $(this).prop('checked', false);
            $(this).data('ischecked', false);
        } else {
            $(this).prop('checked', true);
            $(this).data('ischecked', true);
        }
        $(this).parents('.factory').siblings('.factory').find("input[type='radio']").data('ischecked', false);
    });
    var factory={

        printFactory:[],
        macFactory:[],
        orderid:'',

        getOrderId:function(){
            factory.orderid=$('#orderid').val();
        },
        getPrintFactory:function(){
            factory.printFactory=[];
            $("[name=Printfactory]:checked").each(function(){
                factory.printFactory.push($(this).val());
            });
        },

        getMacFactory:function(){
            factory.macFactory=[];
            $("[name=Macfactory]:checked").each(function(){
                factory.macFactory.push($(this).val());
            });
        },
        searchOrder:function(){

            //factory.getOrderId();
            factory.getPrintFactory();//打印
            factory.getMacFactory();//加工
            if (factory.printFactory.length == 0 && factory.macFactory.length == 0) {
                alert('请选择印花/加工工厂');
                return;
            }
            var url = "<?php echo url('/admin/productionmanage/orderFac'); ?>"+'?pFactory=' + factory.printFactory.join(',') + '&mFactory=' + factory.macFactory.join(',');

            window.location = url;
            //$.post("/admin/productionmanage/OrderFac",{pFactory:factory.printFactory,mFactory:factory.macFactory},function(result){
                //alert(result);
                //window.location = result;
            //});
        }


    }
</script>