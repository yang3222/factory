<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:80:"D:\project\factory\public/../application/index\view\business\newimportexcel.html";i:1592459839;}*/ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>导出业务报表</title>
    <script type="text/javascript" src="<?php echo ADMIN_STYLE_URL; ?>js/jquery.min.js"></script>
    <link href="<?php echo ADMIN_STYLE_URL; ?>css/r.css" rel="stylesheet" type="text/css" />
    <script>
        $(function() {
            //loadOrd.start(ordarr);
            //psku.load();
            $(".tabbox li").click(function() {
                showtable($(this).index());
            })
            showtable(0);
            addpsku.create();
            psku.loading();
            logistics.loadorder();
        })
        function showtable(index) {
            $(".tabbox li").removeClass("current");
            $(".content>table").hide();
            $("#table" + index).show();
            $(".tabbox li").eq(index).addClass("current");
        }
        var loadnum=20;
        var logistics={
            // url:"http://admin.entsku.com/__/_jc/agent/order.ashx?ActFlag=Order.Sch&OdrStatus=0&Keyword=",
            url:"http://webapi.38420.com/api/Order/OrderAll",
            orderarr:[],
            money:0,
            index:0,
            addorder:function(ordernum){
                logistics.orderarr.push(ordernum);
                if(logistics.orderarr.length==loadnum)logistics.loadorder();
            },
            loadorder:function(){
                if(logistics.orderarr.length==0)return;
                logistics.index++;
                var orders=logistics.orderarr.join(" ");
                $.post(logistics.url,{Keyword:orders},function(data){
                    var rows=data;
                    for(var i=0;i<rows.length;i++){
                        logistics.money+=Math.round(Number(rows[i].TranMny)*100)/100;
                    }
                    logistics.index--;
                    if(logistics.index==0){
                        $("#TranMny").html(Math.round(logistics.money*100)/100);
                    }
                })
                logistics.orderarr=[];
            }
        };
        var userorder={}
        var psku = {
            url: "http://admin.entsku.com/__/_jc/design/product.ashx?ActFlag=Product.Sku.Grd&rows=1000000",
            imgurl: "http://kjds-img.img-cn-shanghai.aliyuncs.com/",
            loading: function() {
                $.get(psku.url, null, function(data) {
                    for (var i = 0; i < data.rows.length; i++){
                        var product = data.rows[i];
                        $(".img" + product.ProdSku).attr("src", psku.imgurl + product.ImgPath + "@0e_0o_1l_50h_50w.src");
                        $(".gg" + product.ProdSku).html(product.SpecName);
                        $(".zl" + product.ProdSku).html(product.ProdWeight + "g");
                        <?php if(isset($type)): ?>
                        $(".vip" + product.ProdSku).html(product.VipPrice);
                        $(".cb" + product.ProdSku).html(product.CostPrice);
                        var allnum= $(".allvip" + product.ProdSku).html();
                        if(allnum){
                            $(".allvip" + product.ProdSku).html(Math.round(allnum*product.VipPrice*10)/10);
                            $(".allcb" + product.ProdSku).html(Math.round(allnum*product.CostPrice*10)/10);
                        }
                        <?php endif; ?>
                            $(".name" + product.ProdSku).html($("#product"+product.ProdId).val()+"("+product.ProdId+")");
                        }
                    }, 'jsonp')
            }
        }
        var addpsku = {
            skuarr: {},
            ordersku:[],
            url:"http://admin.entsku.com/__/_jc/agent/goods.ashx?ActFlag=Goods.All&Keywords=",
            add: function(psku,sku, allnum, fbanum, cancelnum,allmoney) {
                addpsku.ordersku.push(sku);
                if(addpsku.ordersku.length==loadnum)addpsku.loadsku(false);
                if (!addpsku.skuarr[psku])
                    addpsku.skuarr[psku] = {all: 0, fba: 0, cancel: 0,money:{}};
                addpsku.skuarr[psku].all += allnum;
                addpsku.skuarr[psku].fba += fbanum;
                addpsku.skuarr[psku].cancel += cancelnum;
                for(var name in allmoney){
                    if(!addpsku.skuarr[psku].money[name])addpsku.skuarr[psku].money[name]=0;
                    addpsku.skuarr[psku].money[name]+=Number(allmoney[name]);
                }
            },

            //产品数据
            create: function() {
                addpsku.loadsku(true);
                var index = 0;
                var tbody = "";
                for (var name in addpsku.skuarr) {
                    index++;
                    tbody += addpsku.addtr(index, name, addpsku.skuarr[name]);
                }
                $("#allproduct").html(tbody);
            },

            //卖家信息





            adduserinfo:function(obj){
                var index=0;
                var tbody="";
                for(var name in obj){
                    index++;
                    var tr="<tr>";
                    tr+="<td>"+index+"</td>";
                    tr+="<td>"+obj[name].name+"</td>";
                    tr+="<td>"+obj[name].num+"</td>";
                    tr+="<td>"+obj[name].fba+"</td>";
                    tr+="<td>"+obj[name].cancel+"</td>";
                    var txt="";
                    for(var money in obj[name].money){
                        var moneyvalue=Math.round(Number(obj[name].money[money])*100)/100;
                        var moneytitle=money=="US"?"":"<i>"+money+"</i>"
                        txt+="<span>"+moneytitle+moneyvalue+"</span>";
                    }
                    tr+="<td>"+txt+"</td>";
                    tr+="</tr>";
                    tbody+=tr;
                }
                $("#allName").html(tbody);
            },
            addtr: function(index, psku, numobj) {
                var tr = "<tr>";
                tr += "<td>" + index + "</td>";
                tr += "<td class='name" + psku + "'></td>";
                tr += "<td class='imgtd'><img  class='img" + psku + " src='' width='50' /></td>";
                tr += "<td>" + psku + "</td>";
                tr += "<td class='gg" + psku + "'></td>";
                tr += "<td>" + numobj.all + "</td>";
                tr += "<td>" + numobj.fba + "</td>";
                tr += "<td>" + numobj.cancel + "</td>";
                <?php if($type == 'treasurer'): ?>
                tr += "<td class='allvip"+psku+"'>"+numobj.all+"</td>";
                tr += "<td class='allcb"+psku+"'></td>";
                <?php endif; ?>
                    var money="";
                    for(var name in addpsku.skuarr[psku].money){
                        var type=name!="US"?"("+name+")":"";
                        money+=type+Math.round(addpsku.skuarr[psku].money[name]*100)/100;
                    }
                    tr += "<td>" + money + "</td>";
                    tr +="</tr>";
                    return tr;
                },
                loadsku:function(boo){
                    var search=addpsku.ordersku.join('+');
                    console.log(addpsku.ordersku.length)
                    addpsku.ordersku=[];
                    if(search==""){
                        if(boo)addpsku.adduserinfo(userorder);
                        return;
                    }
                    $.get(addpsku.url+search,"",function (data) {
                        var rows=data.rows;
                        for(var i=0;i<rows.length;i++){
                            var sku=rows[i]['GdsSku'];
                            var name=rows[i]['AgntName'];
                            var img=psku.imgurl +rows[i]['ImgPath']+ "@0e_0o_1l_50h_50w.src";
                            $(".img" + sku).attr("src", img);
                            $(".name" + sku).html(name);
                            var username=rows[i].AgntUser;
                            if(!userorder[username])userorder[username]={name:rows[i]['AgntName'],num:0,fba:0,cancel:0,money:{}};
                            userorder[username].num+=Number($('.num'+sku).text());
                            userorder[username].fba+=Number($('.fbanum'+sku).text());
                            userorder[username].cancel+=Number($('.cancelnum'+sku).text());
                            $('.money'+sku).each(function(){
                                $bz=$(this).find("i").html();
                                if(!$bz)$bz="US";
                                var money=$(this).find("b").html();
                                if(!userorder[username].money[$bz])userorder[username].money[$bz]=0;
                                userorder[username].money[$bz]+=Number(money);
                            })
                        }
                        if(boo)addpsku.adduserinfo(userorder);
                    },"jsonp");
                }
            }
            function method1(tableid) {
            // 使用outerHTML属性获取整个table元素的HTML代码（包括<table>标签），然后包装成一个完整的HTML文档，设置charset为urf-8以防止中文乱码
            var table=$("#"+tableid).parent().html();
            var table=$('table').parent().clone();
            $(table).find('td.imgtd').remove();
            table=$(table).html();
            var html = "<html><head><meta charset='utf-8' /></head><body>" + table + "</body></html>";
            // 实例化一个Blob对象，其构造函数的第一个参数是包含文件内容的数组，第二个参数是包含文件类型属性的对象
            var blob = new Blob([html], {type: "application/vnd.ms-excel"});
            var objectUrl = URL.createObjectURL(blob);
            var aForExcel = $("<a><span class='forExcel'>下载excel</span></a>").attr("href", objectUrl);
            $("body").append(aForExcel);
            $(".forExcel").click();
            aForExcel.remove();
        }
    </script>
    <style>
        table{border-right:1px solid #ccc; border-bottom:1px solid #ccc; width:100%; display:none;}
        td{text-align:center; vertical-align:middle; border:1px solid #ccc; border-right:0; border-bottom:0; height:30px;}
        tr:hover{background:#efefef;}
        td span{padding:0 10px; display: inline-block; margin: 0 5px;}
        td span i{margin-right:5px;}
        td span i,td span b{font-style:normal; font-weight: normal;}
        td.money span{padding: 0;}
        .content{ padding:10px; }
        .dc{position:fixed; right:10px; top:10px; border:1px solid #ccc; background:#efefef; text-decoration:none; color:#666; padding:0 20px; display:block; height:40px; line-height:40px;}
        .tabbox{height:41px; border-bottom:1px solid #ccc; padding-left:10px; margin-top:10px;}
        .tabbox li{float:left; margin-right:5px; height:40px; line-height:40px; border:1px solid #ccc; background:#efefef;}
        .tabbox li a{text-decoration:none; color:#666; padding:0 20px; display:block; height:40px; line-height:40px;}
        .tabbox li.current{border-bottom:1px solid #fff; background:#fff;}
    </style>
</head>

<body>
<a href="javaScript:method1('table0')" id="dcsj" class="dc">导出数据</a>
<ul class="tabbox">
    <li class="current"><a href="javaScript:void(0)">SKU数据</a></li>
    <li><a href="javaScript:void(0)">产品数据</a></li>
    <li><a href="javaScript:void(0)">卖家数据</a></li>
</ul>
<div class="content">
    <table id="table0">
        <thead>
        <tr>
            <td width="60">#</td>
            <td>SKU</td>
            <td class='imgtd'>SKU缩略图</td>
            <td>产品编码</td>
            <td class='imgtd'>产品缩略图</td>
            <td>规格</td>
            <td>重量</td>
            <?php if($type=='treasurer'): ?>
            <td>vip(￥)</td>
            <td>成本(￥)</td>
            <?php endif; ?>
            <td>销售总数</td>
            <td>FBA件数</td>
            <td>退货件数</td>
            <td>卖家</td>
            <td>商品销售额(订单价格)</td>
            <td>商品销售税</td>
            <td>运费</td>
            <td>运费税</td>
            <td>礼品包装费</td>
            <td>礼品包装税</td>
            <td>亚马逊积分成本</td>
            <td>促销返点</td>
            <td>促销返点税</td>
            <td>商城预扣税/AU:低价值商品</td>
            <td>销售佣金</td>
            <td>亚马逊物流基础服务费</td>
            <td>其他交易费用</td>
            <td>其他</td>
            <td>退款</td>
            <td>销售额</td>
        </tr>
        </thead>
        <tbody>
        <?php $allnum = '0'; $allfbanum = '0'; $allcancelnum = '0'; $allproductsales = '0'; $productsalestax = '0'; $shippingcredits = '0'; $shippingcreditstax = '0'; $giftwrapcredits = '0'; $giftwrapcreditstax = '0'; $amazon_points = '0'; $promotionalrebates = '0'; $promotionalrebatestax = '0'; $marketplacewithheldtax = '0'; $sellingfees = '0'; $fbafees = '0'; $othertransactionfees = '0'; $allother = '0'; $refund_total = '0'; $allmoney=array(); if(is_array($skudata) || $skudata instanceof \think\Collection || $skudata instanceof \think\Paginator): $ak = 0; $__LIST__ = $skudata;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sku): $mod = ($ak % 2 );++$ak;$skuval = $key; $allnum = $allnum+$sku['Num']; $allfbanum = $allfbanum+$sku['fbaNum']; $allproductsales = $allproductsales+$sku['product_sales']; $productsalestax = $productsalestax+$sku['product_sales_tax']; $shippingcredits = $shippingcredits+$sku['shipping_credits']; $shippingcreditstax = $shippingcreditstax+$sku['shipping_credits_tax']; $giftwrapcredits = $giftwrapcredits+$sku['gift_wrap_credits']; $giftwrapcreditstax = $giftwrapcreditstax+$sku['giftwrap_credits_tax']; $amazon_points = $amazon_points+$sku['amazon_points']; $promotionalrebates = $promotionalrebates+$sku['promotional_rebates']; $promotionalrebatestax = $promotionalrebatestax+$sku['promotional_rebates_tax']; $marketplacewithheldtax = $marketplacewithheldtax+$sku['marketplace_withheld_tax']; $sellingfees = $sellingfees+$sku['selling_fees']; $fbafees = $fbafees+$sku['fba_fees']; $othertransactionfees = $othertransactionfees+$sku['other_transaction_fees']; $allother = $allother+$sku['other']; $refund_total = $refund_total+$sku['sku_refund_price']; $allcancelnum = $allcancelnum+$sku['cancelNum']; $keyname = $key; $productsku = preg_replace("/g.*p/","p",$key); ?>
        <tr>
            <td width="60"><?php echo $ak; ?></td>
            <td><?php echo $key; ?></td>
            <td class='imgtd'><img  class="img<?php echo $key; ?>" src="" width="50" /></td>
            <td><?php echo $productsku; ?></td>
            <td class='imgtd'><img  class="img<?php echo $productsku; ?>" src="" width="50" /></td>

            <td class='gg<?php echo $productsku; ?>'></td>
            <td class='zl<?php echo $productsku; ?>'></td>
            <?php if($type == 'treasurer'): ?>
            <td class='vip<?php echo $productsku; ?>'></td>
            <td class='cb<?php echo $productsku; ?>'></td>
            <?php endif; ?>
            <td class="num<?php echo $key; ?>"><?php echo $sku['Num']; ?></td>
            <td class="fbanum<?php echo $key; ?>"><?php echo $sku['fbaNum']; ?></td>
            <td class="cancelnum<?php echo $key; ?>"><?php echo $sku['cancelNum']; ?></td>
            <td class="name<?php echo $key; ?>"></td>
            <td><?php echo $sku['product_sales']; ?></td>
            <td><?php echo $sku['product_sales_tax']; ?></td>
            <td><?php echo $sku['shipping_credits']; ?></td>
            <td><?php echo $sku['shipping_credits_tax']; ?></td>
            <td><?php echo $sku['gift_wrap_credits']; ?></td>
            <td><?php echo $sku['giftwrap_credits_tax']; ?></td>
            <td><?php echo $sku['amazon_points']; ?></td>
            <td><?php echo $sku['promotional_rebates']; ?></td>
            <td><?php echo $sku['promotional_rebates_tax']; ?></td>
            <td><?php echo $sku['marketplace_withheld_tax']; ?></td>
            <td><?php echo $sku['selling_fees']; ?></td>
            <td><?php echo $sku['fba_fees']; ?></td>
            <td><?php echo $sku['other_transaction_fees']; ?></td>
            <td><?php echo $sku['other']; ?></td>
            <td><?php echo $sku['sku_refund_price']; ?></td>
            <td class="money">
                <script>var currentobj={}</script>
                <?php if(is_array($sku['Currency']) || $sku['Currency'] instanceof \think\Collection || $sku['Currency'] instanceof \think\Paginator): $k = 0; $__LIST__ = $sku['Currency'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$currency): $mod = ($k % 2 );++$k;if($k>1): ?>,<?php endif; ?>
                <span class="money<?php echo $keyname; ?>">
                                <?php if($currency!='US'): ?><i><?php echo $currency; ?></i><?php endif; ?>
                                <b><?php echo $sku[$currency.'Currency']; ?></b>
                    <?php
                                if(!isset($allmoney[$currency]))$allmoney[$currency]=0;
                                $allmoney[$currency]+=$sku[$currency.'Currency'];
                                ?>
                            </span>
                <script>currentobj["<?php echo $currency; ?>"]="<?php echo $sku[$currency.'Currency']; ?>"</script>
                <?php endforeach; endif; else: echo "" ;endif; ?>
                <script>addpsku.add("<?php echo $productsku; ?>","<?php echo $skuval; ?>", <?php echo $sku['Num']; ?>, <?php echo $sku['fbaNum']; ?>, <?php echo $sku['cancelNum']; ?>,currentobj)</script>
            </td>
        </tr>
        <?php endforeach; endif; else: echo "" ;endif; ?>
        <tr>
            <td>#</td>
            <td>单项总和</td>
            <td class='imgtd'></td>
            <td></td>
            <td class='imgtd'></td>
            <td></td>
            <td></td>
            <?php if($type == 'treasurer'): ?>
            <td></td>
            <td></td>
            <?php endif; ?>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td><?php echo $allproductsales; ?></td>
            <td><?php echo $productsalestax; ?></td>
            <td><?php echo $shippingcredits; ?></td>
            <td><?php echo $shippingcreditstax; ?></td>

            <td><?php echo $giftwrapcredits; ?></td>
            <td><?php echo $giftwrapcreditstax; ?></td>
            <td><?php echo $amazon_points; ?></td>

            <td><?php echo $promotionalrebates; ?></td>
            <td><?php echo $promotionalrebatestax; ?></td>
            <td><?php echo $marketplacewithheldtax; ?></td>
            <td><?php echo $sellingfees; ?></td>
            <td><?php echo $fbafees; ?></td>
            <td><?php echo $othertransactionfees; ?></td>
            <td><?php echo $allother; ?></td>
            <td><?php echo $refund_total; ?></td>
            <td><?php echo $allmoney['US']; ?></td>
        </tr>

        <?php $advermoney=array(); if(is_array($other) || $other instanceof \think\Collection || $other instanceof \think\Paginator): $k = 0; $__LIST__ = $other;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$value): $mod = ($k % 2 );++$k;?>
        <tr>
            <td>#</td>
            <td><?php echo $key; ?></td>
            <td width="60" colspan='<?php if(isset($type)): ?>12<?php else: ?>10<?php endif; ?>' style=" text-align: left;">
                <?php if(is_array($value) || $value instanceof \think\Collection || $value instanceof \think\Paginator): $i = 0; $__LIST__ = $value;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$value0): $mod = ($i % 2 );++$i;$outkey = $key; if(is_array($value0) || $value0 instanceof \think\Collection || $value0 instanceof \think\Paginator): $i = 0; $__LIST__ = $value0;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$value1): $mod = ($i % 2 );++$i;?>
                <span>
                               <?php if(!empty($key)): ?><?php echo $key; else: ?><?php echo $outkey; endif; if(is_array($value1) || $value1 instanceof \think\Collection || $value1 instanceof \think\Paginator): $i = 0; $__LIST__ = $value1;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$value2): $mod = ($i % 2 );++$i;
                               if(!isset($advermoney[$key]))$advermoney[$key]=0;
                               $advermoney[$key]+=$value2;
                               $allmoney[$key]+=$value2;
                               ?>
                    (<?php echo $value2; ?>)</span>
                <?php endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <?php if($type == 'treasurer'): ?>
            <td></td>
            <td></td>
            <?php endif; ?>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <?php endforeach; endif; else: echo "" ;endif; ?>
        <tr>
            <td width="60">#</td>
            <td>总费用</td>
            <td>
                <?php if(is_array($advermoney) || $advermoney instanceof \think\Collection || $advermoney instanceof \think\Paginator): $k = 0; $__LIST__ = $advermoney;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$adver): $mod = ($k % 2 );++$k;?>
                <?php echo $adver; endforeach; endif; else: echo "" ;endif; ?>
            </td>
            <td></td>
            <td class='imgtd'></td>
            <td class='imgtd'></td>
            <td>总数</td>
            <?php if($type == 'treasurer'): ?>
            <td></td>
            <td></td>
            <?php endif; ?>
            <td><?php echo $allnum; ?></td>
            <td><?php echo $allfbanum; ?></td>
            <td><?php echo $allcancelnum; ?></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>
                <?php if(is_array($allmoney) || $allmoney instanceof \think\Collection || $allmoney instanceof \think\Paginator): $i = 0; $__LIST__ = $allmoney;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$value): $mod = ($i % 2 );++$i;?>
                <?php echo $value; endforeach; endif; else: echo "" ;endif; ?>
            </td>
        </tr>
        <tr>
            <td width="60"></td>
            <td></td>
            <td>
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <?php if($type == 'treasurer'): ?>
            <td></td>
            <td></td>
            <?php endif; ?>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        </tbody>
    </table>
    <table id="table1">
        <thead>
        <tr>
            <td width="60">#</td>
            <td width="200">产品</td>
            <td class='imgtd' width="50">缩略图</td>
            <td width="200">sku</td>
            <td width="200">型号</td>
            <td>销售总数</td>
            <td>FBA</td>
            <td>退货件数</td>
            <?php if($type == 'treasurer'): ?>
            <td>VIP总成本</td>
            <td>工厂总成本</td>
            <?php endif; ?>
            <td>销售额</td>
        </tr>
        </thead>
        <tbody id="allproduct">

        </tbody>
        <tr>
            <td>#</td>
            <td>物流费用(不含FBA)(RMB)</td>
            <td class='imgtd'></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <?php if($type == 'treasurer'): ?>
            <td></td>
            <td></td>
            <?php endif; ?>
            <td id="TranMny"></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td class='imgtd'></td>
            <td></td>
            <Td></Td>
            <Td></Td>
            <td></td>
            <td></td>
            <?php if($type == 'treasurer'): ?>
            <td></td>
            <td></td>
            <?php endif; ?>
            <td></td>
        </tr>
    </table>
    <table id="table2">
        <thead>
        <tr>
            <td width="60">#</td>
            <td>卖家</td>
            <td>销售总数</td>
            <td>FBA件数</td>
            <td>退货件数</td>
            <td>销售额</td>
        </tr>
        </thead>
        <tbody id="allName">

        </tbody>
    </table>
</div>
<div style="display: none;">
    <?php if(is_array($products) || $products instanceof \think\Collection || $products instanceof \think\Paginator): $i = 0; $__LIST__ = $products;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$product): $mod = ($i % 2 );++$i;?>
    <input type="hideen" id="product<?php echo $product['product_id']; ?>" value="<?php echo $product['name']; ?>" />
    <?php endforeach; endif; else: echo "" ;endif; ?>
    <script>
        <?php if(is_array($ordData) || $ordData instanceof \think\Collection || $ordData instanceof \think\Paginator): $i = 0; $__LIST__ = $ordData;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ord): $mod = ($i % 2 );++$i;?>
        logistics.addorder("<?php echo $ord; ?>");
        <?php endforeach; endif; else: echo "" ;endif; ?>
    </script>
</div>
<script>
    $(document).ready(function () {
        //首先将#back-to-top隐藏
        $("#back-to-top").hide();
        //当滚动条的位置处于距顶部600像素以下时，跳转链接出现，否则消失
        $(function () {
            $(window).scroll(function () {
                if ($(window).scrollTop() > 1000) {
                    $("#back-to-top").fadeIn(500);
                } else {
                    $("#back-to-top").fadeOut(500);
                }
            });
            //当点击跳转链接后，回到页面顶部位置
            $("#back-to-top").click(function () {
                $('body,html').animate({
                    scrollTop: 0
                }, 500);
                return false;
            });
        });
    });
</script>
<style>
    body {
        height: 2000px;
    }

    p#back-to-top {
        position: fixed;
        bottom: 18px;
        right: 12px;
    }

    p#back-to-top a {
        text-align: center;
        text-decoration: none;
        color: #000;
        display: block;
        width: 30px;
        /*使用CSS3中的transition属性给跳转链接中的文字添加渐变效果*/
        -moz-transition: color1s;
        -webkit-transition: color1s;
        -o-transition: color1s;
    }

    p#back-to-top a:hover {
        color: #000011;
    }

    p#back-to-top a span {
        background-color: #d4bbbb;
        z-index: -100;
        border: 1px solid #cccccc;
        border-radius: 6px;
        display: block;
        height: 90px;
        width: 30px;
        margin-bottom: 60px;
        /*使用CSS3中的transition属性给<span>标签背景颜色添加渐变效果*/
        -moz-transition: background1s;
        -webkit-transition: background1s;
        -o-transition: background1s;
    }

    #back-to-top a:hover span {
        background-color: #f0f0f0;
    }

</style>
<p id="back-to-top">
    <a href="#top">
			<span>
				<i class="fa fa-chevron-up" aria-hidden="true">回到顶部</i>
			</span>
    </a>
</p>
</body>
</html>