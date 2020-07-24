<?php
namespace app\index\controller;
use \app\index\model\Product;
class Business extends Excel
{
    public function __construct() {
        parent::__construct();
    }
    private $typeText = array(
        ''=>'(空白)',
        'Refund'=>'退款',
        'Erstattung'=>'退款',
        'Service Fee'=>'服务费',
        'Servicegebühr'=>'服务费',
        'Order'=>'订单付款',
        'Bestellung'=>'订单付款',
        'Adjustment'=>'盘点',
        'FBA Inventory Fee'=>'FBA库存费用',
        'Versand durch Amazon Lagergebühr'=>'FBA库存费用',
        'Transfer'=>'转账',
        'FBA Customer Return Fee'=>'亚马逊买家退货费用',
        'A-to-z Guarantee Claim' => '亚马逊商城交易保障索赔',
        'Deal Fee'=>'秒杀费用',
        '注文'=>'订单付款',
        '返金'=>'退款',
        '調整'=>'盘点',
        '振込み'=>'转账',
        '注文外料金'=>'服务费',
        'マイナス残高'=>'扣款失败的回款',
        'FBA 在庫関連の手数料'=>'FBA库存费用',
        'Anpassung'=>'盘点',
        'Commande' => '订单付款',
        "Remboursement" => '退款',
        "Réclamation dans le cadre de la Garantie A à z" => "亚马逊商城交易保障索赔",
        "Frais de retour client FBA" =>"FBA买家退货费用",
        "Frais de stock Expédié par Amazon" =>"FBA库存费用",
        "Frais de service" =>"服务费",
        "Modifica" =>"盘点",
        "Ordine" =>"订单付款",
        "Rimborso" =>"退款",
        "Commissione di servizio" =>"服务费",
        "Trasferimento"=> '转账',
        "Saldo negativo"=> '扣款失败的回款',
        "Tarifas de inventario de Logística de Amazon"=> 'FBA库存费用',
        "Ajuste" =>"盘点",
        "Pedido" =>"订单付款",
        "Reembolso" =>"退款",
        "Tarifa de prestación de servicio" =>"服务费",
        "Transferir" =>"转账",
        "Transfert" =>"转账",
        "Chargeback Refund" =>"退款",



    );
    private $descriptionText = array(
        'Buyer Recharge' => '买家重新付款',
        'FBA Inventory Reimbursement - Customer Return'=>'亚马逊库存退款 - 买家退货：',
        'FBA Inventory Reimbursement - Customer Service Issue'=>'亚马逊库存退款 - 买家服务问题',
        'FBA Inventory Reimbursement - Lost:Warehouse'=> '亚马逊库存赔偿 - 丢失：仓库：',
        'FBA Customer Return Per Order Fee'=> '亚马逊物流客户退货单费',
        'FBA Customer Return Per Unit Fee'=>'亚马逊物流客户退货单费',
        'FBA Customer Return Weight Based Fee'=> '亚马逊物流客户退货单费',
//            'To your account ending in: 833, Bank Transfer ID: 091000012560340'=>'向您尾号为 833 的账户转账, 银行转账编号：091000012519034',
        'Cost of Advertising'=>'广告费',
        'Werbekosten'=>'广告费',
        ' Manual Processing Fee'=>'人工处理费',
        'FBA Inventory Placement Service Fee'=>'亚马逊物流库存放置服务费',
        'SellerPayments_Report_Fee_Subscription'=>'订阅',
        'FBA Removal Order: Disposal Fee'=>'亚马逊物流弃置费用',
        'FBA Inventory Reimbursement - General Adjustment'=>'亚马逊物流库存报销-一般调整',
        'FBA Inventory Reimbursement - Damaged:Warehouse'=>'亚马逊库存赔偿 - 残损：仓库：',
        'FBA Inventory Storage Fee'=>'亚马逊物流库存放置服务费',
        'FBA Long-Term Storage Fee'=> '亚马逊长期存储服务费',
        'FBA Inventory Reimbursement - Damaged:Warehouse'=>'FBA库存赔偿-在库房中残损',
        'FBA Inventory Reimbursement - Fee Correction'=>'FBA库存赔偿-费用更正',
        'FBA Inventory Reimbursement - General Adjustment'=>'FBA库存赔偿-一般盘点',
        'Non-subscription Fee Adjustment'=>'非订阅费调整',
        'FBA Amazon-Partnered Carrier Shipment Fee' => 'FBA亚马逊合作承运人运输费',
        'FBA Removal Order: Disposal Fee'=>'FBA移除订单:弃置费',
        'Versand durch Amazon Gebühr für Entsorgung'=>'FBA移除订单:弃置费',
        'FBA Removal Order: Return Fee'=>'FBA移除订单:退货费',
        'FBA保管手数料：' => 'FBA库存仓储费',
        'Versand durch Amazon Lagergebühr' => 'FBA库存仓储费',
        'FBA長期在庫保管手数料'=>'FBA长期仓储费',
        'Versand durch Amazon Langzeitlagergebühr'=>'FBA长期仓储费',
        'FBA在庫の廃棄手数料' => 'FBA移除订单:弃置费',
        '購入者返品配送手数料'=>'FBA买家退货费 按每订单',
        '購入者返品作業手数料'=>'FBA买家退货费 按每件',
        '購入者の再課金：'=>'买家重新付款',
        'FBA在庫の返金 - 購入者による返品:'=>'FBA库存赔偿-买家退货',
        'Versand durch Amazon Erstattung für Lagerbestand - Kundenrücksendung'=>'FBA库存赔偿-买家退货',
        "Frais de retour client par unité FBA" => 'FBA买家退货费 按每件',
        "Frais de stock Expédié par Amazon" =>"FBA库存仓储费",
        "Prix de la publicité" =>"广告费",
        "Rettifica commissione non di abbonamento" =>"非订阅费调整",
        "Ajuste ajeno a la cuota de suscripción" =>"非订阅费调整",
        "Costo della pubblicità" =>"广告费",
        "Tarifa de almacenamiento de Logística de Amazon" =>"FBA库存仓储费",
        "Gastos de publicidad" =>"广告费",
    );
    //业务报表
    public function index($type=null){
        $this->assign('type',$type);
        return $this->fetch();
    }

    public function newimportexcel($type=''){
        set_time_limit(0);
        $this->excelStart();
        $fileName = isset($_FILES["file"]) ? $_FILES["file"]["name"] : "";
        $file = isset($_FILES["file"]) ? $_FILES["file"]["tmp_name"] : "";
        $ext = \file::get_ext($fileName);

        if ($ext == "xls") {
            $reader = \PHPExcel_IOFactory::createReader('Excel5');
        } else {
            $reader = \PHPExcel_IOFactory::createReader('Excel2007');
        }
        $PHPExcel = $reader->load($file);
        $sheet = $PHPExcel->getSheet(0); // 读取第一個工作表
        $highestRow = $sheet->getHighestRow(); // 取得总行数

        $highestColumm = $sheet->getHighestColumn(1); // 取得总列数
        $highestColumm = \PHPExcel_Cell::columnIndexFromString($highestColumm); //字母列转换为数字列 如:AA变为27

        $filedArray = array('OdrNum','sku','intro','type','money','num','fulfillment','product_sales',
            'product_sales_tax','shipping_credits',
            'low_value_goods',
            'shipping_credits_tax',
            'gift_wrap_credits',
            'giftwrap_credits_tax',
            'promotional_rebates',
            'promotional_rebates_tax',
            'marketplace_withheld_tax','selling_fees', 'fba_fees',
            'other_transaction_fees','other',
            'amazon_points'
        );
        $field = filedArr();
        $searchStratarr = searchStrat();
        $index=1;
        $startrow = 1;
        for ($row = $index; $row <=$highestRow;$row++){
            $value = $sheet->getCellByColumnAndRow(0, $row)->getValue();
            if ($value && isset($searchStratarr[$value])){
                $startrow = $row;
                break;
            }
            continue;
        }

        //获取第一排的数据用于匹配
        $filedArrays=[];
        $highestColumm = $sheet->getHighestColumn($startrow); //开始行总列数
        $highestColumm = \PHPExcel_Cell::columnIndexFromString($highestColumm);
        for ($col = 0; $col <= $highestColumm; $col++) {
            $value = trim($sheet->getCellByColumnAndRow($col, $startrow)->getValue());
            if(isset($field[$value])){
                $filedArrays[$col]=$field[$value];
//                $index=2;
                $index=$startrow+1;
            }
        }
        $orderNumArr=array();  //记录订单号
        $orderInfoArr=array(); //记录订单号下的信息
        $other=array();
        $transfer = 0;
        for ($row = $index; $row <= $highestRow; $row++) { //列数是以第0列开始
            $data=array();
            foreach ($filedArrays as $key=>$value){
                $data[$value]=$sheet->getCellByColumnAndRow($key, $row)->getValue();
            }
            if (strstr($data['money'],',')){
                $data['money'] = str_replace('.',"",$data['money']);
                $data['money'] = str_replace(',',".",$data['money']);
                $data['product_sales'] = str_replace('.',"",$data['product_sales']);
                $data['product_sales'] = str_replace(',',".",$data['product_sales']);
                $data['shipping_credits'] = str_replace('.',"",$data['shipping_credits']);
                $data['shipping_credits'] = str_replace(',',".",$data['shipping_credits']);
                $data['promotional_rebates'] = str_replace('.',"",$data['promotional_rebates']);
                $data['promotional_rebates'] = str_replace(',',".",$data['promotional_rebates']);
                $data['selling_fees'] = str_replace('.',"",$data['selling_fees']);
                $data['selling_fees'] = str_replace(',',".",$data['selling_fees']);
                $data['fba_fees'] = str_replace('.',"",$data['fba_fees']);
                $data['fba_fees'] = str_replace(',',".",$data['fba_fees']);
                $data['other_transaction_fees'] = str_replace('.',"",$data['other_transaction_fees']);
                $data['other_transaction_fees'] = str_replace(',',".",$data['other_transaction_fees']);
                $data['other'] = str_replace('.',"",$data['other']);
                $data['other'] = str_replace(',',".",$data['other']);
            }

            if($data['money']=="")continue;
            $orderNum=$data['OdrNum'];
            $skuNum=$data['sku'];
            if(!in_array($orderNum, $orderNumArr)&&$orderNum)$orderNumArr[]=$orderNum;
            unset($data['OdrNum']);
            $fytypearr = $this->typeText;
            if (isset($fytypearr[$data['type']]) && $fytypearr[$data['type']] == '转账'){
                $transfer+=$data['money'];
                continue;//转账金额不包含在内
            }
//            if ($data['type'] == 'Transfer') continue; //转账金额不算入在内
            if (isset($fytypearr[$data['type']])) $data['type'] = $fytypearr[$data['type']];
            $ttype=$data['type'];



            if($orderNum&&$skuNum){
                if ($data['type'] == '盘点'){
                    $money=preg_replace("/^[a-zA-Z]+/", "", $data['money']);

                    $bz = str_replace($money, "", $data['money']);
                    if (empty($bz))$bz = "US";
                    $skutype=$data['type'];

                    $intro = '';
                    $fyarr = $this->descriptionText;
                    if (isset($fyarr[$data['intro']])) $data['intro'] = $fyarr[$data['intro']];
                    $intro = $data['intro'];
                    if(!isset($other[$ttype]))$other[$ttype]=array();
                    if(!isset($other[$ttype][$skutype]))$other[$ttype][$skutype]=array();
                    if(!isset($other[$ttype][$skutype][$intro]))$other[$ttype][$skutype][$intro]=array();
                    if(!isset($other[$ttype][$skutype][$intro][$bz]))$other[$ttype][$skutype][$intro][$bz]=0;
                    $other[$ttype][$skutype][$intro][$bz]+=$money;
                }else{
                    if(!isset($orderInfoArr[$orderNum]))$orderInfoArr[$orderNum]=array();
                    $orderInfoArr[$orderNum][]=$data;
                }
            }else{

                $money=preg_replace("/^[a-zA-Z]+/", "", $data['money']);
                $bz = str_replace($money, "", $data['money']);
                if (empty($bz))$bz = "US";
                $skutype=$data['type'];
                $intro = '';
                $fyarr = $this->descriptionText;
                if (isset($fyarr[$data['intro']])) $data['intro'] = $fyarr[$data['intro']];
                $intro = $data['intro'];
                if(!isset($other[$ttype]))$other[$ttype]=array();
                if(!isset($other[$ttype][$skutype]))$other[$ttype][$skutype]=array();
                if(!isset($other[$ttype][$skutype][$intro]))$other[$ttype][$skutype][$intro]=array();
                if(!isset($other[$ttype][$skutype][$intro][$bz]))$other[$ttype][$skutype][$intro][$bz]=0;
                $other[$ttype][$skutype][$intro][$bz]+=$money;
            }
        }
        $skuarr=$this->neworderData($orderInfoArr);
        $product=new Product();
        $product=$product->field('product_id,name,smallimg')->select();
        $this->assign('excelname',$fileName);
        $this->assign('type',$type);
        $this->assign('ordData',$orderNumArr);
        $this->assign('other',$other); //其他
        $this->assign('transfer',$transfer);
        $this->assign('advertisement',$skuarr['advertisement']);
        $this->assign('skudata',$skuarr['data']);
        $this->assign('products',$product);
        return $this->fetch();
    }



    //提交数据
    public function importexcel($type=null){
        $this->excelStart();
        $fileName = isset($_FILES["file"]) ? $_FILES["file"]["name"] : "";
        $file = isset($_FILES["file"]) ? $_FILES["file"]["tmp_name"] : "";
        $ext = \file::get_ext($fileName);
        if ($ext == "xls") {
            $reader = \PHPExcel_IOFactory::createReader('Excel5');
        } else {
            $reader = \PHPExcel_IOFactory::createReader('Excel2007');
        }
        $PHPExcel = $reader->load($file);
        $sheet = $PHPExcel->getSheet(0); // 读取第一個工作表
        $highestRow = $sheet->getHighestRow(); // 取得总行数
        $highestColumm = $sheet->getHighestColumn(1); // 取得总列数
        $highestColumm = \PHPExcel_Cell::columnIndexFromString($highestColumm); //字母列转换为数字列 如:AA变为27
        $filedArray = array('OdrNum','sku','Ttype','type','intro','money','num');
        $field=array("订单编号"=>'OdrNum','SKU'=>'sku','交易类型'=>'Ttype','付款类型'=>'type','付款详情'=>'intro',"金额"=>'money','数量'=>'num');
        $index=1;
        //获取第一排的数据用于匹配
        for ($col = 0; $col <= $highestColumm; $col++) {
            $value = $sheet->getCellByColumnAndRow($col, 1)->getValue();
            if(isset($field[$value])){
                $filedArray[$col]=$field[$value];
                $index=2;
            }
        }
        $orderNumArr=array();  //记录订单号
        $orderInfoArr=array(); //记录订单号下的信息
        $other=array();
        for ($row = $index; $row <= $highestRow; $row++) { //列数是以第0列开始
            $data=array();
            foreach ($filedArray as $key=>$value){
                $data[$value]=$sheet->getCellByColumnAndRow($key, $row)->getValue();
            }
            if($data['money']=="")continue;

            $orderNum=$data['OdrNum'];
            $skuNum=$data['sku'];
            if(!in_array($orderNum, $orderNumArr)&&$orderNum)$orderNumArr[]=$orderNum;
            unset($data['OdrNum']);
            if($orderNum&&$skuNum){
                if(!isset($orderInfoArr[$orderNum]))$orderInfoArr[$orderNum]=array();
                $orderInfoArr[$orderNum][]=$data;
            }else{
                $money=preg_replace("/^[a-zA-Z]+/", "", $data['money']);
                $bz = str_replace($money, "", $data['money']);
                if (empty($bz))$bz = "US";
                $ttype=$data['Ttype'];
                $skutype=$data['type'];
                $intro=$data['intro'];
                if(!isset($other[$ttype]))$other[$ttype]=array();
                if(!isset($other[$ttype][$skutype]))$other[$ttype][$skutype]=array();
                if(!isset($other[$ttype][$skutype][$intro]))$other[$ttype][$skutype][$intro]=array();
                if(!isset($other[$ttype][$skutype][$intro][$bz]))$other[$ttype][$skutype][$intro][$bz]=0;
                $other[$ttype][$skutype][$intro][$bz]+=$money;
            }
        }
        $skuarr=$this->orderData($orderInfoArr);
        $product=new Product();
        $product=$product->field('product_id,name,smallimg')->select();
        $this->assign('excelname',$fileName);
        $this->assign('type',$type);
        $this->assign('ordData',$orderNumArr);
        $this->assign('other',$other);
        $this->assign('advertisement',$skuarr['advertisement']);
        $this->assign('skudata',$skuarr['data']);
        $this->assign('products',$product);
        return $this->fetch();
    }
    //获取SKU的订单信息
    private function orderData($arr){
        $advertisement=array();//记录广告费
        $data=array();
        foreach($arr as $key=>$order){
            $fbaType="";
            foreach($order as $sku){
//                if($sku['intro']=="亚马逊物流基础服务费：")$fbaType="fba";
                $skuNum=$this->newSkuName($sku['sku']);
                $newsku=$this->getskudata($sku);
                $newsku['OrderType']=$fbaType;

                if($sku['Ttype']=="退款")$newsku['Num']*=-1;
                if($newsku['Type']=='服务费'){
                    if(!isset($advertisement[$newsku['Currency']]))$advertisement[$newsku['Currency']]=0;
                    $advertisement[$newsku['Currency']]+=$newsku['Money'];
                }else{
                    if(!isset($data[$skuNum]))$data[$skuNum]=array('Num'=>0,'fbaNum'=>0,'cancelNum'=>0,'Currency'=>array());
                    if(!isset($data[$skuNum]['Currency'][$newsku['Currency']]))$data[$skuNum]['Currency'][$newsku['Currency']]=$newsku['Currency'];
                    if(!isset($data[$skuNum][$newsku['Currency'].'Currency']))$data[$skuNum][$newsku['Currency'].'Currency']=0;

                   if($sku['type']=='商品价格'){
                       if($newsku['Num']>0)$data[$skuNum]['Num']+=$newsku['Num'];
                       if($newsku['Num']<0)$data[$skuNum]['cancelNum']+=abs($newsku['Num']);
                       if($newsku['OrderType']=='fba')$data[$skuNum]['fbaNum']+=$newsku['Num'];
                   }
                    $data[$skuNum][$newsku['Currency'].'Currency']+=preg_replace("/[^0-9._-]/","",intval($newsku['Money']));
                }
            }
        }
//        var_dump($data);die;
        $Num = array_column($data,'Num');
        $fbaNum = array_column($data,'fbaNum');
        array_multisort($Num,SORT_DESC,$fbaNum,SORT_DESC,$data);
        return array('advertisement'=>$advertisement,'data'=>$data);
    }

    //获取SKU的订单信息2
    private function neworderData($arr){
        $advertisement=array();//记录广告费
        $data=array();
        foreach($arr as $key=>$order){
            $fbaType="";
            foreach($order as $sku){
                if($sku['intro']=="亚马逊物流基础服务费：")$fbaType="fba";
                $skuNum=$this->newSkuName($sku['sku']);
                $newsku=$this->getskudata($sku);
                $newsku['OrderType']=$fbaType;

                if($sku['type']=="退款")$newsku['Num']*=-1;
                if($newsku['Type']=='服务费'){
                    if(!isset($advertisement[$newsku['Currency']]))$advertisement[$newsku['Currency']]=0;
                    $advertisement[$newsku['Currency']]+=$newsku['Money'];
                }else{
                    if(!isset($data[$skuNum]))$data[$skuNum]=array('Num'=>0,'fbaNum'=>0,'cancelNum'=>0,'Currency'=>array(),'product_sales'=>0,
                        'product_sales_tax'=>0,
                        'shipping_credits'=>0,'shipping_credits_tax'=>0,
                        'gift_wrap_credits'=>0,
                        'giftwrap_credits_tax'=>0,
                        'promotional_rebates'=>0,
                        'promotional_rebates_tax'=>0,
                        'marketplace_withheld_tax'=>0,
                        'selling_fees'=>0,
                        'fba_fees'=>0,
                        'other_transaction_fees'=>0,
                        'other'=>0,
                        'sku_refund_price'=>0,
                        'sku_order_price'=>0,
                        'amazon_points'=>0,
                    );
                    if(!isset($data[$skuNum]['Currency'][$newsku['Currency']]))$data[$skuNum]['Currency'][$newsku['Currency']]=$newsku['Currency'];
                    if(!isset($data[$skuNum][$newsku['Currency'].'Currency']))$data[$skuNum][$newsku['Currency'].'Currency']=0;
                    if ($sku['type'] == '订单付款'){
                        if($newsku['Num']>0)$data[$skuNum]['Num']+=$newsku['Num'];
                    }
                    if ($sku['type'] == '退款'){

                        if($newsku['Num']<0)$data[$skuNum]['cancelNum']+=abs($newsku['Num']);
                        if($newsku['Num']<0)$data[$skuNum]['sku_refund_price'] += $sku['product_sales'];
                    }
                    if ($sku['fulfillment'] == 'Amazon'){
                        $data[$skuNum]['fbaNum']+=$newsku['Num'];
                    }
                    $data[$skuNum][$newsku['Currency'].'Currency']+=preg_replace("/[^0-9._-]/","",$newsku['Money']);
                    if ($sku['type'] == '订单付款'){
                        $data[$skuNum]['product_sales'] += isset($sku['product_sales']) ? preg_replace("/[^0-9._-]/","",$sku['product_sales']):0;
                    }

                    $data[$skuNum]['product_sales_tax'] += isset($sku['product_sales_tax']) ? preg_replace("/[^0-9._-]/","",$sku['product_sales_tax']) : 0;
                    $data[$skuNum]['shipping_credits'] +=  isset($sku['shipping_credits']) ? preg_replace("/[^0-9._-]/","",$sku['shipping_credits']):0;
                    $data[$skuNum]['shipping_credits_tax'] += isset($sku['shipping_credits_tax']) ? preg_replace("/[^0-9._-]/","",$sku['shipping_credits_tax']) : 0;
                    $data[$skuNum]['gift_wrap_credits'] += isset($sku['gift_wrap_credits']) ? preg_replace("/[^0-9._-]/","",$sku['gift_wrap_credits']):0;
                    $data[$skuNum]['giftwrap_credits_tax'] += isset($sku['giftwrap_credits_tax']) ? preg_replace("/[^0-9._-]/","",$sku['giftwrap_credits_tax']) : 0;
                    $data[$skuNum]['promotional_rebates'] += preg_replace("/[^0-9._-]/","",$sku['promotional_rebates']);
                    $data[$skuNum]['promotional_rebates_tax'] +=  isset($sku['promotional_rebates_tax']) ? preg_replace("/[^0-9._-]/","",$sku['promotional_rebates_tax']) : 0;
                    $data[$skuNum]['marketplace_withheld_tax'] += isset($sku['marketplace_withheld_tax']) ? preg_replace("/[^0-9._-]/","",$sku['marketplace_withheld_tax']) :0;
                    $data[$skuNum]['amazon_points'] += isset($sku['amazon_points']) ? preg_replace("/[^0-9._-]/","",$sku['amazon_points']) :0;
                    $data[$skuNum]['selling_fees'] += preg_replace("/[^0-9._-]/","",$sku['selling_fees']);
                    $data[$skuNum]['fba_fees'] += preg_replace("/[^0-9._-]/","",$sku['fba_fees']);
                    $data[$skuNum]['other_transaction_fees'] += preg_replace("/[^0-9._-]/","",$sku['other_transaction_fees']);
                    $data[$skuNum]['other'] += preg_replace("/[^0-9._-]/","",$sku['other']);
                }
            }
        }
        $Num = array_column($data,'Num');
        $fbaNum = array_column($data,'fbaNum');
        array_multisort($Num,SORT_DESC,$fbaNum,SORT_DESC,$data);
        return array('advertisement'=>$advertisement,'data'=>$data);
    }

    //获取该sku数据
    private function getskudata($sku){
        $money=preg_replace("/^[a-zA-Z]+/","",$sku['money']);
        $bz = str_replace($money, "", $sku['money']);
//        if (empty($bz))$bz = "US";
        $bz = "US";
        $newsku=array('Num'=>empty($sku['num'])?0:$sku['num'],'Type'=>$sku['type'],'Currency'=>$bz,'Money'=>$money);
        return $newsku;
    }
    //SKU过滤其他无用字母
    private function newSkuName($sku){
        $skuNum=str_replace("_par","",$sku);	
        return preg_replace("/^[a-zA-Z]+/","g",$skuNum);
    }
    //仓库打印条码软件
    public function arehouse_bar_code(){
        return $this->fetch();
    }

    public function listing(){
        return $this->fetch();
    }

}
