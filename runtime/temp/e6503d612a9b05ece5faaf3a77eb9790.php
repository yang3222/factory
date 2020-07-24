<?php if (!defined('THINK_PATH')) exit(); /*a:6:{s:69:"D:\project\factory\public/../application/admin\view\factory\edit.html";i:1585097840;s:60:"D:\project\factory\application\admin\view\common\layout.html";i:1594716292;s:60:"D:\project\factory\application\admin\view\common\member.html";i:1584407225;s:61:"D:\project\factory\application\admin\view\common\topline.html";i:1594716292;s:58:"D:\project\factory\application\admin\view\common\menu.html";i:1584407225;s:61:"D:\project\factory\application\admin\view\common\mapline.html";i:1584407225;}*/ ?>
<!doctype html><html><head><meta charset="utf-8"><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"><meta name="viewport" content="width=device-width, initial-scale=1.0"><meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0,  user-scalable=0" name="viewport" /><title>管理后台</title><link rel="stylesheet" href="<?php echo ADMIN_STYLE_URL; ?>css/r.css" type="text/css" media="screen" /><link rel="stylesheet" href="<?php echo ADMIN_STYLE_URL; ?>css/style.css?v=1.0" type="text/css" media="screen" /><script type="text/javascript" src="<?php echo ADMIN_STYLE_URL; ?>js/jquery.min.js"></script><script type="text/javascript" src="<?php echo LAYER_JS_URL; ?>layer.js"></script><?php if(isset($eventJS)): ?><script type="text/javascript" src="<?php echo ADMIN_STYLE_URL; ?>js/<?php echo $eventJS; ?>.js"></script><?php endif; ?><script type="text/javascript" src="<?php echo ADMIN_STYLE_URL; ?>js/move.js"></script></head><body class="content">   <div class="topline">
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
   <div class="contentbox do-clear">      <ul class="menubox">
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
</ul>      <div class="rightbox">         <div class="menumap">    <a href="<?php echo url('/user/index'); ?>" class="home">首页</a>    <?php if(isset($menu[$currentMenu['menu']])): ?><a href="javaScript:;"><?php echo $menu[$currentMenu['menu']]['title']; ?></a><?php endif; if(isset($menu[$currentMenu['menu']]['nav'][$currentMenu['nav']])): ?><span><?php echo $menu[$currentMenu['menu']]['nav'][$currentMenu['nav']]['title']; ?></span><?php endif; ?></div>         <div class="canvas">            <style>
   .fs-wrap {
      position: relative;
      display: inline-block;
      width: 412px;
      height: 120px;
      font-size: 14px;
      line-height: 1;
   }

   .fs-label-wrap {
      position: relative;
      border: 1px solid #ccc;
      cursor: default;
   }

   .fs-label-wrap,
   .fs-dropdown {
      -webkit-user-select: none;
      -moz-user-select: none;
      -ms-user-select: none;
      user-select: none;
   }

   .fs-label-wrap .fs-label {
      padding: 4px 22px 4px 8px;
      text-overflow: ellipsis;
      white-space: nowrap;
      height: 22px;
      overflow: hidden;
   }

   .fs-arrow {
      width: 0;
      height: 0;
      border-left: 6px solid transparent;
      border-right: 6px solid transparent;
      border-top: 6px solid #000;
      position: absolute;
      top: 0;
      right: 5px;
      bottom: 0;
      margin: auto;
   }

   .fs-dropdown {
      position: absolute;
      background-color: #fff;
      border: 1px solid #ccc;
      margin-top: 5px;
      width: 100%;
      z-index: 1000;
   }

   .fs-dropdown .fs-options {
      max-height: 200px;
      overflow: auto;
   }

   .fs-search input {
      width: 100%;
      padding: 2px 4px;
      border: 0;
   }

   .fs-option,
   .fs-search,
   .fs-optgroup-label {
      padding: 6px 8px;
      border-bottom: 1px solid #eee;
      cursor: default;
   }

   .fs-option {
      cursor: pointer;
   }

   .fs-option.hl {
      background-color: #f5f5f5;
   }

   .fs-wrap.multiple .fs-option {
      position: relative;
      padding-left: 30px;
   }

   .fs-wrap.multiple .fs-checkbox {
      position: absolute;
      display: block;
      width: 30px;
      top: 0;
      left: 0;
      bottom: 0;
   }

   .fs-wrap.multiple .fs-option .fs-checkbox i {
      position: absolute;
      margin: auto;
      left: 0;
      right: 0;
      top: 0;
      bottom: 0;
      width: 14px;
      height: 14px;
      border: 1px solid #aeaeae;
      border-radius: 2px;
      background-color: #fff;
   }

   .fs-wrap.multiple .fs-option.selected .fs-checkbox i {
      background-color: rgb(17, 169, 17);
      border-color: transparent;
      background-repeat: no-repeat;
      background-position: center;
   }

   .fs-wrap .fs-option:hover {
      background-color: #f5f5f5;
   }

   .fs-optgroup-label {
      font-weight: bold;
   }

   .hidden {
      display: none;
   }
</style>
<script>
    $(function(){
        Member.UserInput=$("#User");
        Member.PwdInput=$("#Pwd");
        Member.IdInput=$("#id");
        Member.NameInput=$("#Name");
        Member.TelInput=$("#Tel");
        Member.MemoArea=$("#Memo");
        Member.TypeInput=$("#Type");
        Member.AuthGroup=$("#auth_group");
        Member.Fac_Attribute=$("#fac_attribute");
        Member.UserType='2';
        
        Member.NoUser="请输入账号！";
        Member.NoPwd="请输入密码！";
        Member.SuccessURL="<?php echo url('/admin/factory'); ?>";
       $('#auth_group').fSelect();
       $('#fac_attribute').fSelect();
    });

    (function($) {

       $.fn.fSelect = function(options,astr) {
//console.log(options);
          if (typeof options == 'string' ) {
             var settings = options;
          }
          else {
             var settings = $.extend({
                placeholder: '请选择',
                numDisplayed: 3,
                overflowText: '{n} selected',
                searchText: '搜索',
                showSearch: true
             }, options);
          }


          /**
           * Constructor
           */
          function fSelect(select, settings) {
             console.log(select);
             this.$select = $(select);
             this.settings = settings;
             this.create();
          }


          /**
           * Prototype class
           */
          fSelect.prototype = {
             create: function() {
                var multiple = this.$select.is('[multiple]') ? ' multiple' : '';
                this.$select.wrap('<div class="fs-wrap' + multiple + '"></div>');
                this.$select.before('<div class="fs-label-wrap"><div class="fs-label">' + this.settings.placeholder + '</div><span class="fs-arrow"></span></div>');
                this.$select.before('<div class="fs-dropdown hidden"><div class="fs-options"></div></div>');
                this.$select.addClass('hidden');
                this.$wrap = this.$select.closest('.fs-wrap');
                this.reload();
             },

             reload: function() {
                if (this.settings.showSearch) {
                   var search = '<div class="fs-search"><input type="search" placeholder="' + this.settings.searchText + '" /></div>';
                   this.$wrap.find('.fs-dropdown').prepend(search);
                }
                var choices = this.buildOptions(this.$select);
                this.$wrap.find('.fs-options').html(choices);
                this.reloadDropdownLabel();
             },

             destroy: function() {
                this.$wrap.find('.fs-label-wrap').remove();
                this.$wrap.find('.fs-dropdown').remove();
                this.$select.unwrap().removeClass('hidden');
             },

             buildOptions: function($element) {
                var $this = this;

                var choices = '';
                $element.children().each(function(i, el) {
                   var $el = $(el);

                   if ('optgroup' == $el.prop('nodeName').toLowerCase()) {
                      choices += '<div class="fs-optgroup">';
                      choices += '<div class="fs-optgroup-label">' + $el.prop('label') + '</div>';
                      choices += $this.buildOptions($el);
                      choices += '</div>';
                   }
                   else {
                      var selected = $el.is('[selected]') ? ' selected' : '';
                      choices += '<div class="fs-option' + selected + '" data-value="' + $el.prop('value') + '"><span class="fs-checkbox"><i></i></span><div class="fs-option-label">' + $el.html() + '</div></div>';
                   }
                });

                return choices;
             },

             reloadDropdownLabel: function() {
                var settings = this.settings;
                var labelText = [];

                this.$wrap.find('.fs-option.selected').each(function(i, el) {
                   labelText.push($(el).find('.fs-option-label').text());
                });
                console.log(labelText);
                if (labelText.length < 1) {
                   labelText = settings.placeholder;
                }
                else if (labelText.length > settings.numDisplayed) {
                   labelText = settings.overflowText.replace('{n}', labelText.length);
                }
                else {
                   labelText = labelText.join(', ');
                }

                this.$wrap.find('.fs-label').html(labelText);
                this.$select.change();
             }
          }


          /**
           * Loop through each matching element
           */
          return this.each(function() {
             var data = $(this).data('fSelect');

             if (!data) {
                data = new fSelect(this, settings);
                $(this).data('fSelect', data);
             }

             if (typeof settings == 'string') {
                data[settings]();
             }
          });
       }


       /**
        * Events
        */
       window.fSelect = {
          'active': null,
          'idx': -1
       };

       function setIndexes($wrap) {
          $wrap.find('.fs-option:not(.hidden)').each(function(i, el) {
             $(el).attr('data-index', i);
             $wrap.find('.fs-option').removeClass('hl');
          });
          $wrap.find('.fs-search input').focus();
          window.fSelect.idx = -1;
       }

       function setScroll($wrap) {
          var $container = $wrap.find('.fs-options');
          var $selected = $wrap.find('.fs-option.hl');

          var itemMin = $selected.offset().top + $container.scrollTop();
          var itemMax = itemMin + $selected.outerHeight();
          var containerMin = $container.offset().top + $container.scrollTop();
          var containerMax = containerMin + $container.outerHeight();

          if (itemMax > containerMax) { // scroll down
             var to = $container.scrollTop() + itemMax - containerMax;
             $container.scrollTop(to);
          }
          else if (itemMin < containerMin) { // scroll up
             var to = $container.scrollTop() - containerMin - itemMin;
             $container.scrollTop(to);
          }
       }

       $(document).on('click', '.fs-option', function() {
          var $wrap = $(this).closest('.fs-wrap');

          if ($wrap.hasClass('multiple')) {
             var selected = [];

             $(this).toggleClass('selected');
             $wrap.find('.fs-option.selected').each(function(i, el) {
                selected.push($(el).attr('data-value'));
             });
          }
          else {
             var selected = $(this).attr('data-value');
             $wrap.find('.fs-option').removeClass('selected');
             $(this).addClass('selected');
             $wrap.find('.fs-dropdown').hide();
          }

          $wrap.find('select').val(selected);
          $wrap.find('select').fSelect('reloadDropdownLabel');
       });

       $(document).on('keyup', '.fs-search input', function(e) {
          if (40 == e.which) {
             $(this).blur();
             return;
          }

          var $wrap = $(this).closest('.fs-wrap');
          var keywords = $(this).val();

          $wrap.find('.fs-option, .fs-optgroup-label').removeClass('hidden');

          if ('' != keywords) {
             $wrap.find('.fs-option').each(function() {
                var regex = new RegExp(keywords, 'gi');
                if (null === $(this).find('.fs-option-label').text().match(regex)) {
                   $(this).addClass('hidden');
                }
             });

             $wrap.find('.fs-optgroup-label').each(function() {
                var num_visible = $(this).closest('.fs-optgroup').find('.fs-option:not(.hidden)').length;
                if (num_visible < 1) {
                   $(this).addClass('hidden');
                }
             });
          }

          setIndexes($wrap);
       });

       $(document).on('click', function(e) {
          var $el = $(e.target);
          var $wrap = $el.closest('.fs-wrap');

          if (0 < $wrap.length) {
             if ($el.hasClass('fs-label')) {
                window.fSelect.active = $wrap;
                var is_hidden = $wrap.find('.fs-dropdown').hasClass('hidden');
                $('.fs-dropdown').addClass('hidden');

                if (is_hidden) {
                   $wrap.find('.fs-dropdown').removeClass('hidden');
                }
                else {
                   $wrap.find('.fs-dropdown').addClass('hidden');
                }

                setIndexes($wrap);
             }
          }
          else {
             $('.fs-dropdown').addClass('hidden');
             window.fSelect.active = null;
          }
       });

       $(document).on('keydown', function(e) {
          var $wrap = window.fSelect.active;

          if (null === $wrap) {
             return;
          }
          else if (38 == e.which) { // up
             e.preventDefault();

             $wrap.find('.fs-option').removeClass('hl');

             if (window.fSelect.idx > 0) {
                window.fSelect.idx--;
                $wrap.find('.fs-option[data-index=' + window.fSelect.idx + ']').addClass('hl');
                setScroll($wrap);
             }
             else {
                window.fSelect.idx = -1;
                $wrap.find('.fs-search input').focus();
             }
          }
          else if (40 == e.which) { // down
             e.preventDefault();

             var last_index = $wrap.find('.fs-option:last').attr('data-index');
             if (window.fSelect.idx < parseInt(last_index)) {
                window.fSelect.idx++;
                $wrap.find('.fs-option').removeClass('hl');
                $wrap.find('.fs-option[data-index=' + window.fSelect.idx + ']').addClass('hl');
                setScroll($wrap);
             }
          }
          else if (32 == e.which || 13 == e.which) { // space, enter
             $wrap.find('.fs-option.hl').click();
          }
          else if (27 == e.which) { // esc
             $('.fs-dropdown').addClass('hidden');
             window.fSelect.active = null;
          }
       });

    })(jQuery);
</script>
<div class="canvas_title do-clear">
   <h2><?php echo $canvasTitle; ?></h2>
</div>
<div class="canvas_intro">
   <ul class="add_from">
      <li>
         <label class="label">账号<i>*</i></label>
         <input name="user" type="text" id="User" value="<?php echo $data['User']; ?>" />
      </li>
      <li>
         <label class="label">密码<i>*</i></label>
         <?php $newPwd = base64_decode($data['Pwd']); ?>
         <input name="password" type="text" id="Pwd" value="<?php echo str_replace('factory','',$newPwd); ?>" />
      </li>
      <li>
         <label class="label">名称</label>
         <input name="Name" type="text" value="<?php echo $data['userinfo']['Name']; ?>" id="Name" />
      </li>
      <li>
         <label class="label">电话</label>
         <input name="Tel" type="text" value="<?php echo $data['userinfo']['Tel']; ?>" id="Tel" />
      </li>
      <li>
         <label class="label">备注</label>
         <textarea name="Memo" id="Memo" ><?php echo $data['userinfo']['Memo']; ?></textarea>
      </li>
      <li>
         <label class="label">状态：</label>
         <select name="Type" id="Type">
           <option value="0" <?php if($data['reviewed']=='2'): ?>selected="selected"<?php endif; ?>>未审核</option>
           <option value="1" <?php if($data['reviewed']=='1'): ?>selected="selected"<?php endif; ?> >审核通过</option>
           <option value="2" <?php if($data['reviewed']=='0'): ?>selected="selected"<?php endif; ?> >审核屏蔽</option>
         </select>
         <input name="id" value="<?php echo $data['id']; ?>" id="id" type="hidden" />
      </li>
      <li>
         <label class="label">工厂属性：</label>
         <select name="fac_attribute" id="fac_attribute" multiple="multiple">
            <optgroup label="">
               <?php if(is_array($fac_att) || $fac_att instanceof \think\Collection || $fac_att instanceof \think\Paginator): $ks = 0; $__LIST__ = $fac_att;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$fatt): $mod = ($ks % 2 );++$ks;?>
               <option value="<?php echo $ks; ?>" <?php if(in_array($ks,$data['fac_attribute'])): ?>selected='selected'<?php endif; ?> ><?php echo $fatt; ?></option><!--selected="selected"-->
               <?php endforeach; endif; else: echo "" ;endif; ?>
            </optgroup>

         </select>
      </li>
      <li>
         <label class="label">绑定用户组：</label>
         <select name="auth_group" id="auth_group" multiple="multiple">
            <optgroup label="">
               <?php if(is_array($auth_group) || $auth_group instanceof \think\Collection || $auth_group instanceof \think\Paginator): $k = 0; $__LIST__ = $auth_group;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$group): $mod = ($k % 2 );++$k;?>
               <option value="<?php echo $group['id']; ?>" <?php if(isset($ingroup[$group['id']])): ?>selected='selected'<?php endif; ?> ><?php echo $group['title']; ?></option><!--selected="selected"-->
               <?php endforeach; endif; else: echo "" ;endif; ?>
            </optgroup>

         </select>
         <input name="" value="" id="" type="hidden" />
      </li>
      <li>
         <a href="javaScript:Member.post('<?php echo url('/admin/factory/post'); ?>')">保存</a>
      </li>
   </ul>
</div>         </div>      </div>   </div>   <div class="swf_edit_box" id="swfbox"></div></body><script type="text/javascript">   $(document).ready(function(){      $("#fba_auto_msg_li").mouseover(function(){         $("#fba_auto_msg_ul").show();         $("#fba_auto_msg_li").mouseout(function(){            $("#fba_auto_msg_ul").hide();         });      });   })   var is_fba_auth = "<?php echo $is_fba_auth; ?>";   if (is_fba_auth == 1) {      $('#fba_btn_mn').click();      var speaks_auth = 1;      get_fba_new_msg();      var start_for = setInterval(function () {         get_fba_new_msg();      }, 150000);   }   //语音播报   function speak_cn(ttsText) {      //var mess = document.getElementById('ttsText').value;      var msg = new SpeechSynthesisUtterance(ttsText);      msg.volume = 100;      msg.rate = 1;      msg.pitch = 1.5;      console.log(msg);      window.speechSynthesis.speak(msg);   }   //获取fba数据   function get_fba_new_msg() {      $.post("<?php echo url('/admin/Fba/get_fba_zx'); ?>", {}, function (sres) {         if (sres['code'] == 1000) {            $('#fba_auto_msg_li').show();            var html = '';            var html_3 = '';            var html_4 = '';            var vol_txt_3 = '';            var vol_txt_4 = '';            if (sres['count_3'] > 0) {               vol_txt_3 = '已封箱' + sres['count_3'] + '件';               html_3 = "<li style='z-index: 999;'><a href=\"javaScript:check_fba_news(3);\">已封箱（" + sres['count_3'] + "件）</a></li>";            }            if (sres['count_4'] > 0) {               vol_txt_4 = '已发货' + sres['count_4'] + '件';               html_4 = "<li style='z-index: 999;'><a href=\"javaScript:check_fba_news(4);\">可发货（" + sres['count_4'] + "件）</a></li>";            }            $("#fba_auto_msg_ul").html(html_3 + html_4);            if (vol_txt_3 != '') {               speak_cn(vol_txt_3);            }            if (vol_txt_4 != '') {               speak_cn(vol_txt_4);            }            //alert(sres['msg']);            //playPause();         }         return;      });   }   //播放   function playPause() {      var music = document.getElementById('fba_music');      if (music.paused) {         music.play();         console.log('play');      } else {         music.pause();         console.log('pause');      }   }   //查看状态   function check_fba_news(plan_status) {      $.post("<?php echo url('/admin/Fba/check_news'); ?>", {plan_status:plan_status}, function (res) {         window.location.href = "<?php echo url('/admin/fba/lists'); ?>" + "?plan_status=" + plan_status;      });   }</script></html>