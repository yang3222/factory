<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:73:"D:\project\factory\public/../application/index\view\business\listing.html";i:1595570552;}*/ ?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Amazon Product Listing Builder 亚马逊产品文案编辑器
        ver. 1.0.20200724</title>
    <script type="text/javascript" src="<?php echo ADMIN_STYLE_URL; ?>js/jquery.min.js"></script>
</head>
<style>
    body{
        align-content: center;
        margin: 50px;
    }
    input{
        height: 30px;
        border-radius: 5px;
        margin-left:10px;
    }
    .qian{
        float: left;
        margin: 10px;
    }


    .textcolor{
        margin-left: 10px;
    }

    textarea{
        float: left;font-size: 2rem;margin: 10px;
    }

    .test3{
        border:1px solid #a0b3d6;/*设置边框1px，实线，边框线色为#a0b3d6 */
        width: 800px;
        min-height: 10px;
        font-size: 10px;
        padding: 4px;
        color: #333;
        outline:0; /* 当元素获得焦点的时候，焦点框为0.去掉虚线框（或高亮框）*/
        user-modify: read-write-plaintext-only;
        /*overflow-x: scroll;*/
        /*overflow-x:auto;*/
        /*white-space:nowrap;*/
        /*overflow:auto*/
    }
    .test4{
        border:1px solid #a0b3d6;/*设置边框1px，实线，边框线色为#a0b3d6 */
        width: 190px;
        min-height: 190px;
        font-size: 14px;
        padding: 4px;
        color: #333;
        outline:0; /* 当元素获得焦点的时候，焦点框为0.去掉虚线框（或高亮框）*/
        margin-left: 10px;
    }

    /* 当输入框为空时，显示默认文字 */
    .test3:empty::before{
        content:attr(placeholder); /*获取属性的值 attr（value）,可以获得属性值 */
        color: #999;
    }
</style>
<body>
<br>
<h2 style="margin: 10px">Amazon Product Listing Builder 亚马逊产品文案编辑器

ver. 1.0.20200724</h2>
<br><hr>
<form>
    <div class="qian">
        <label>品牌名</label><br>
        <input type="text" placeholder="输入内容 可留空" id="brand">
    </div>
    <div class="qian">
        <label>标题 推荐字符数（移动端）</label><br>
        <input type="text" id="title_recommend_num" placeholder="输入数字 默认为80" maxlength="4" oninput = "value=value.replace(/[^\d]/g,'')">
    </div>
    <div class="qian">
        <label>标题 常规字符数（PC端）</label><br>
        <input type="text" id="title_routine_num" placeholder="输入数字 默认为130">
    </div>
    <div class="qian">
        <label>标题 最大字符数</label><br>
        <input type="text" id="title_max_num" placeholder="输入数字 默认为200">
    </div>
    <div style="clear:both">
    </div>
    <div class="qian">
        <label>卖点 最大字符数</label><br>
        <input type="text" id="sell_max_num" placeholder="输入数字 默认为500">
    </div>
    <div class="qian">
        <label>产品描述 最大字符数</label><br>
        <input type="text" id="desc_max_num" placeholder="输入数字 默认为2000">
    </div>
    <div class="qian">
        &nbsp;&nbsp;<label>关键词 最大字节数</label><br>
        <input type="text" id="keyword_max_num" placeholder="输入数字 默认为249">
    </div>
        <div style="clear:both">
        </div>
<!--                        <br><br>-->
     
     标题Title<br><textarea id="titlecontent" name="info" cols="70" rows="10" wrap=off  onblur="this.placeholder='输入标题，一行一个标题\n'+
'\n'+
'若填写“品牌名”，则自动在每行标题前自动插入\n'+
'\n'+
'≤“标题 推荐字符数”的部分，字体显示绿色\n'+
'＞“标题 推荐字符数”且≤“标题 常规字符数”的部分，字体显示蓝色\n'+
'＞“标题 常规字符数”且≤“标题 最大字符数”的部分，字体显示黑色\n'+
'＞“标题 最大字符数”的部分，字体显示红色'" onfocus="this.placeholder=''"  placeholder="输入标题，一行一个标题

若填写“品牌名”，则自动在每行标题前自动插入

≤“标题 推荐字符数”的部分，字体显示绿色
＞“标题 推荐字符数”且≤“标题 常规字符数”的部分，字体显示蓝色
＞“标题 常规字符数”且≤“标题 最大字符数”的部分，字体显示黑色
＞“标题 最大字符数”的部分，字体显示红色"></textarea>
    <div id="text1" class="textcolor" style="float: left"></div>
    <div style="clear:both">
    </div>
        <input type="button" onclick="copyText('titlecontent')" value="复制">
    <input type="button" onclick="deltext('titlecontent')" value="清空">
    <br><br>
      &nbsp;&nbsp;卖点Bullet Point<br><textarea id="bulletcontent" name="info" cols="70" rows="10" wrap=off onblur="this.placeholder='输入卖点，一行一个卖点\n'+
'\n'+
'＞“卖点 最大字符数”的部分，字体显示红色\n'+
'\n'+
' 注意：不支持Type 1 High ASCII字符（®, ©, ™, etc.等）或其他特殊字符。'" onfocus="this.placeholder=''"  placeholder="输入卖点，一行一个卖点

＞“卖点 最大字符数”的部分，字体显示红色

 注意：不支持Type 1 High ASCII字符（®, ©, ™, etc.等）或其他特殊字符。"></textarea>
       
    <div id="text2" class="textcolor" style="float: left"></div>
    <div style="clear:both">
    </div>
        <input type="button" onclick="copyText('bulletcontent')" value="复制">
        <input type="button" onclick="deltext('bulletcontent')" value="清空">
    <br><br>
    &nbsp;&nbsp;产品描述 Product Description<br><textarea name="info" id="Productcontent" cols="70" rows="10" wrap=off  onblur="this.placeholder='输入产品描述，若描述中包含HTML代码，请连同HTML代码一起复制并粘贴\n'+
'\n'+
'＞“描述 最大字符数”的部分，字体显示红色\n'+
'\n'+
'注意：不支持Type 1 High ASCII字符（®, ©, ™, etc.等）或其他特殊字符。'" onfocus="this.placeholder=''"  placeholder="输入产品描述，若描述中包含HTML代码，请连同HTML代码一起复制并粘贴

＞“描述 最大字符数”的部分，字体显示红色

注意：不支持Type 1 High ASCII字符（®, ©, ™, etc.等）或其他特殊字符。"></textarea>
       
    <div id="text3" class="textcolor" style="float: left"></div>
    <div style="clear:both">
    </div>
        <input type="button" onclick="copyText('Productcontent')" value="复制">
        <input type="button" onclick="deltext('Productcontent')" value="清空">
    <br><br>
    &nbsp;&nbsp;关键词 Search Terms<br><textarea name="info" id="keywordcontent" cols="70" rows="20" wrap=off onblur="this.placeholder='输入关键词 \n'+
'\n'+
'＞“关键词 最大字符数”的部分，字体显示红色\n'+
'\n'+
'注意：\n'+
'1.字节和字符有何区别？\n'+
'在卖家平台的【Search Terms】字段中，有一个内置的字节计算器，它会停止接\n'+'受超过字节（而不是字符）长度限制的输入，因为一些搜索关键字中包含多字节\n'+'字符。\n'+
'\n'+
'字母数字字符的字符数（例如 a – z、A - Z、0 - 9）和【字节数】比是 1:1。\n'+'当处理更复杂的字符时，例如德语中的变音符号（如 ä），每个字符有 2 个字\n'+'节，长度就会发生变化。其他复杂字符（如日语和中文字符）可能有 3 个或\n'+' 4 个字节。在一些情况下，搜索关键词中可能同时包含单字节字符和多字节字符，这\n'+'使得对字符计数的预测变得复杂。\n'+
'\n'+
'2.是否计算空格和标点符号？\n'+
'不计算。计算搜索关键字的长度时，Amazon Search 不会将空格或标点符号计算\n'+'在内。为便于阅读，应使用空格分隔搜索关键字。可以使用标点符号，但不作强\n'+'制性要求。'" onfocus="this.placeholder=''"  placeholder="输入关键词

＞“关键词 最大字符数”的部分，字体显示红色

注意：
1.字节和字符有何区别？
在卖家平台的【Search Terms】字段中，有一个内置的字节计算器，它会停止接
受超过字节（而不是字符）长度限制的输入，因为一些搜索关键字中包含多字节
字符。

字母数字字符的字符数（例如 a – z、A - Z、0 - 9）和【字节数】比是 1:1。
当处理更复杂的字符时，例如德语中的变音符号（如 ä），每个字符有 2 个字
节，长度就会发生变化。其他复杂字符（如日语和中文字符）可能有 3 个或
4 个字节。在一些情况下，搜索关键词中可能同时包含单字节字符和多字节字符，这
使得对字符计数的预测变得复杂。

2.是否计算空格和标点符号？
不计算。计算搜索关键字的长度时，Amazon Search 不会将空格或标点符号计算
在内。为便于阅读，应使用空格分隔搜索关键字。可以使用标点符号，但不作强
制性要求。"></textarea>
    <div id="text4" class="textcolor" style="float: left"></div>
    <div style="clear:both">
    </div>
    <input type="button" onclick="copyText('keywordcontent')" value="复制">
    <input type="button" onclick="deltext('keywordcontent')" value="清空">
</form>
</body>
<script>

    function copyText(getid) {
        var text = document.getElementById(getid).innerText;
        var input = document.getElementById(getid);
        // input.value = text; // 修改文本框的内容
        input.select(); // 选中文本
        document.execCommand("copy"); // 执行浏览器复制命令
        // alert("复制成功");
    }

    function deltext(getid) {

        $("#"+getid).next().html("");
        $("#"+getid).val("");

    }

    var autoTextarea = function(elem, extra, maxHeight) {
        extra = extra || 0;
        var isFirefox = !!document.getBoxObjectFor || 'mozInnerScreenX' in window,
            isOpera = !!window.opera && !!window.opera.toString().indexOf('Opera'),
            addEvent = function(type, callback) {
                elem.addEventListener ?
                    elem.addEventListener(type, callback, false) :
                    elem.attachEvent('on' + type, callback);
            },
            getStyle = elem.currentStyle ? function(name) {
                var val = elem.currentStyle[name];
                if (name === 'height' && val.search(/px/i) !== 1) {
                    var rect = elem.getBoundingClientRect();
                    return rect.bottom - rect.top -
                        parseFloat(getStyle('paddingTop')) -
                        parseFloat(getStyle('paddingBottom')) + 'px';
                };
                return val;
            } : function(name) {
                return getComputedStyle(elem, null)[name];
            },
            minHeight = parseFloat(getStyle('height'));
        elem.style.resize = 'none';
        var change = function() {
            var scrollTop, height,
                padding = 0,
                style = elem.style;
            if (elem._length === elem.value.length) return;
            elem._length = elem.value.length;
            if (!isFirefox && !isOpera) {
                padding = parseInt(getStyle('paddingTop')) + parseInt(getStyle('paddingBottom'));
            };
            scrollTop = document.body.scrollTop || document.documentElement.scrollTop;
            elem.style.height = minHeight + 'px';
            if (elem.scrollHeight > minHeight) {
                if (maxHeight && elem.scrollHeight > maxHeight) {
                    height = maxHeight - padding;
                    style.overflowY = 'auto';
                } else {
                    height = elem.scrollHeight - padding;
                    style.overflowY = 'hidden';
                };
                style.height = height + extra + 'px';
                scrollTop += parseInt(style.height) - elem.currHeight;
                document.body.scrollTop = scrollTop;
                document.documentElement.scrollTop = scrollTop;
                elem.currHeight = parseInt(style.height);
            };
        };
        addEvent('propertychange', change);
        addEvent('input', change);
        addEvent('focus', change);
        change();
    };

    var titlecontent = document.getElementById("titlecontent");
    var bulletcontent = document.getElementById("bulletcontent");
    var Productcontent = document.getElementById("Productcontent");
    var keywordcontent = document.getElementById("keywordcontent");
    autoTextarea(titlecontent); // 调用
    autoTextarea(bulletcontent); // 调用
    autoTextarea(Productcontent); // 调用
    autoTextarea(keywordcontent); // 调用

    $("#brand").change(function () {
        $("#titlecontent").text($(this).val())
    })

    function textreplace(str){
        str=str.replace(/[\ |\~|\`|\!|\@|\#|\$|\%|\^|\&|\*|\(|\)|\-|\_|\+|\=|\||\\|\[|\]|\{|\}|\;|\:|\"|\'|\,|\<|\.|\>|\/|\?|\，|\。|\、|\‘|\，|\’|\？|\；]/g,"");
        return str;
    }

    $("#titlecontent").bind('input propertychange',function () {
        str = $(this).val();
        str = textreplace(str)
        let code = str.split(/[(\r\n)\r\n]+/);

        code.forEach((item, index) => { // 删除空项
            if (!item) {
                code.splice(index, 1);
            }
        })
        var trn = $("#title_recommend_num").val();
        var tron = $("#title_routine_num").val();
        var tmn = $("#title_max_num").val();
        if (!trn){
            trn = 80;
        }
        if (!tron){
            tron = 130;
        }
        if (!tmn){
            tmn = 200;
        }
        var text = "";

        // var wordnumber = "";
        $.each(code,function(index,value) {
            text += "<div class='test3' contenteditable='true' placeholder='请输入内容...' style='float: left'>";
            // wordnumber += "该行字符数:";
            valleng = value.length;
            if (valleng <= trn){
                text+= "<span style='color: green'>"+value+"</span>"
            }else if (valleng > trn && valleng <= tron){
                greentext = value.substring(0,trn);
                bluetext = value.substring(trn,tron);
                text += "<span style='color: green'>"+greentext+"</span><span style='color: blue'>"+bluetext+"</span>";
            }else if (valleng > tron && valleng <= tmn){
                greentext = value.substring(0,trn);
                bluetext = value.substring(trn,tron);
                blacktext = value.substring(tron,tmn);
                text += "<span style='color: green'>"+greentext+"</span><span style='color: blue'>"+bluetext+"</span><span style='color:black'>"+blacktext+"</span>";
            }else if (valleng > tmn){
                greentext = value.substring(0,trn);
                bluetext = value.substring(trn,tron);
                blacktext = value.substring(tron,tmn);
                redtext  = value.substring(tmn);
                text += "<span style='color: green'>"+greentext+"</span><span style='color: blue'>"+bluetext+"</span><span style='color:black'>"+blacktext+"</span><span style='color: red'>"+redtext+"</span>";
            }
            // wordnumber += "<span>"+valleng+"</span><br>";
            text+= "</div><span style='float: left'>"+valleng+"</span><br>"
        })
        $("#text1").html('')
        $("#text1").html(text)
    })

    $("#bulletcontent").bind('input propertychange',function () {
        str = $(this).val();
        str = textreplace(str)
        let code = str.split(/[(\r\n)\r\n]+/);

        code.forEach((item, index) => { // 删除空项
            if (!item) {
                code.splice(index, 1);
            }
        })
        var smn = $("#sell_max_num").val();

        console.log(smn)
        if (!smn){
            smn = 500;
        }

        var text = "";
        $.each(code,function(index,value) {
            text += "<div class='test3' contenteditable='true' placeholder='请输入内容...' style='float: left'>";
            // wordnumber += "该行字符数:";
            valleng = value.length;
            if (valleng <= smn){
                text+= "<span style='color: black'>"+value+"</span>"
            }else if (valleng > smn){
                blacktext = value.substring(0,smn);
                redtext  = value.substring(smn);
                text += "<span style='color: black'>"+blacktext+"</span><span style='color: red'>"+redtext+"</span>";
            }
            // wordnumber += "<span>"+valleng+"</span><br>";
            text+= "</div><span style='float: left'>"+valleng+"</span><br>"
        })
        $("#text2").html('')
        $("#text2").html(text)
    })

    $("#Productcontent").bind('input propertychange',function () {
        str = $(this).val();
        str = textreplace(str)
        let code = str.split(/[(\r\n)\r\n]+/);

        code.forEach((item, index) => { // 删除空项
            if (!item) {
                code.splice(index, 1);
            }
        })
        var dmn = $("#desc_max_num").val();

        if (!dmn){
            dmn = 2000;
        }

        var text = "";
        $.each(code,function(index,value) {
            text += "<div class='test3' contenteditable='true' placeholder='请输入内容...' style='float: left'>";
            // wordnumber += "该行字符数:";
            valleng = value.length;
            if (valleng <= dmn){
                text+= "<span style='color: black'>"+value+"</span>"
            }else if (valleng > dmn){
                blacktext = value.substring(0,dmn);
                redtext  = value.substring(dmn);
                text += "<span style='color: black'>"+blacktext+"</span><span style='color: red'>"+redtext+"</span>";
            }
            // wordnumber += "<span>"+valleng+"</span><br>";
            text+= "</div><span style='float: left'>"+valleng+"</span><br>"
        })
        $("#text3").html('')
        $("#text3").html(text)
    })

    $("#keywordcontent").bind('input propertychange',function () {
        str = $(this).val();
        str = textreplace(str)
        let code = str.split(/[(\r\n)\r\n]+/);

        code.forEach((item, index) => { // 删除空项
            if (!item) {
                code.splice(index, 1);
            }
        })
        var kmn = $("#keyword_max_num").val();

        if (!kmn){
            kmn = 249;
        }

        var text = "";
        $.each(code,function(index,value) {
            var b = 0;
            l = value.length;
            if (value){
                for (var i =0;i < l; i++){
                    if (value.charCodeAt(i) > 255){
                        b+=2;
                    }else{
                        b++;
                    }
                }
            }

            text += "<div class='test3' contenteditable='true' placeholder='请输入内容...' style='float: left'>";
            // wordnumber += "该行字符数:";
            if (b <= kmn){
                text+= "<span style='color: black'>"+value+"</span>"
            }else if (b > kmn){
                blacktext = value.substring(0,(kmn / 2));
                redtext  = value.substring((kmn/2));
                text += "<span style='color: black'>"+blacktext+"</span><span style='color: red'>"+redtext+"</span>";
            }
            // wordnumber += "<span>"+valleng+"</span><br>";
            text+= "</div><span style='float: left'>"+b+"字节</span><br>"
        })

        $("#text4").html('')
        $("#text4").html(text)
    })

    //判断浏览器
    function browserType () {
        var userAgent = navigator.userAgent; //取得浏览器的userAgent字符串
        var isOpera = false;
        if (userAgent.indexOf('Edge') > -1) {
            return "Edge";
        }
        if (userAgent.indexOf('.NET') > -1) {
            return "IE";
        }
        if (userAgent.indexOf("Opera") > -1 || userAgent.indexOf("OPR") > -1) {
            isOpera = true;
            return "Opera"
        }; //判断是否Opera浏览器
        if (userAgent.indexOf("Firefox") > -1) {
            return "FF";
        } //判断是否Firefox浏览器
        if (userAgent.indexOf("Chrome") > -1) {
            return "Chrome";
        }
        if (userAgent.indexOf("Safari") > -1) {
            return "Safari";
        } //判断是否Safari浏览器
        if (userAgent.indexOf("compatible") > -1 && userAgent.indexOf("MSIE") > -1 && !isOpera) {
            return "IE";
        }; //判断是否IE浏览器
    }
        function moveEnd(obj){
            obj.focus();

            var len = obj.innerText.length;

            if (document.selection) {

                var sel = document.selection.createRange();

                sel.moveStart('character',len);

                sel.collapse();

                sel.select();

            }

            else{                                                 /* IE11 特殊处理 */

                var sel = window.getSelection();

                var range = document.createRange();

                range.selectNodeContents(obj);

                range.collapse(false);

                sel.removeAllRanges();

                sel.addRange(range);

            }

        }
</script>
</html>
