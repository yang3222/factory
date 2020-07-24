<?php
namespace app\index\controller;
use \app\index\model\Product;
class Fbanew extends Excel
{
    private $orderdata=array();
    private $country="";
    private $money="";
    private $Txt2NewTxt=array(
        'Bestellung'=>'Order',
        'Commande'=>'Order',
        'Ordine'=>'Order',
        'Pedido'=>'Order',
        '注文'=>'Order',
        'Erstattung'=>'Refund',
        'Remboursement'=>'Refund',
        'Rimborso'=>'Refund',
        'Reembolso'=>'Refund',
        '返金'=>'Refund',
        'SKU'=>'sku',
        'Tipo'=>'type',
        'tipo'=>'type',
        'トランザクションの種類'=>'type',
        'Menge'=>'quantity',
        'quantité'=>'quantity',
        'Quantità'=>'quantity',
        'cantidad'=>'quantity',
        '数量'=>'quantity',
        'fulfilment'=>'fulfillment',
        'Versand'=>'fulfillment',
        'traitement'=>'fulfillment',
        'Gestione'=>'fulfillment',
        'gestión logística'=>'fulfillment',
        'cumplimiento'=>'fulfillment',
        'フルフィルメント'=>'fulfillment',
        'Umsätze'=>'product sales',
        'ventes de produits'=>'product sales',
        'Vendite'=>'product sales',
        'ventas de productos'=>'product sales',
        '商品売上'=>'product sales',
        'Gesamt'=>'total',
        'totale'=>'total',
        '合計'=>'total',
        'Merchant SKU'=>'sku',
        'End Qty'=>'end-quantity',
        'Received'=>'received',
        'FBA customer returns'=>'returned',
        'Inventory disposed of'=>'disposed',
        'Other'=>'other',
    );
    private $OrderTable=array("sku"=>"","type"=>"","quantity"=>"","fulfillment"=>"","product sales"=>"","total"=>"");
    private $MihTable=array("sku"=>"","end-quantity"=>"");
    private $ReconTable=array("sku"=>"","beginning-quantity"=>"","ending-quantity"=>"","received"=>"","returned"=>"","found"=>"","sold"=>"","removed"=>"","lost"=>"","disposed"=>"","other"=>"","discrepant-quantity"=>"");
    private $UserFbaTable=array("sku"=>"","quantity"=>"","country-code"=>"");
    
    public function __construct() {
        parent::__construct();
    }
    //业务报表
    public function index(){
        $now = date("Y-m-d");
        $lastday = date('Y-01-01', strtotime("$now -1 month -1 day"));
        $timer = array("start" => $lastday, "end" => $now);
        $this->assign('time', $timer);
        return $this->fetch();
    }
    public function postexcel(){
        $this->excelStart();
        $this->savebigExcel();
        $startTime = input('post.start_time');
        $endTime = input('post.end_time');
        $user=input('post.user');
        $this->country=input('post.country');
        $this->money=input('post.money');
        if (!empty($_FILES ['file_stu'] ['name'])&&!empty($_FILES ['file_fba'] ['name'])){
            $file_types = explode ( ".", $_FILES ['file_stu'] ['name'] );
            //日期范围报告
            $this->getPHPData($this->getfile('file_stu'), $this->OrderTable,"getAmazonOrder",array('fulfillment','Amazon'));
            //上个月的每月库存历史记录
            $this->getPHPData($this->getfile('file_fba_old'), $this->MihTable,"getAmazonSellableOld",'sku');
            //每月库存历史记录
            $this->getPHPData($this->getfile('file_fba'), $this->MihTable,"getAmazonSellable",'sku');
            //库存调整
            $this->getPHPData($this->getfile('file_Reconciliation'), $this->ReconTable,"getAmazonRecon",'sku');
            //本年度配货量（业务员）
            $this->getPHPData($this->getfile('file_fba_user'), $this->UserFbaTable,"getUserFba",'sku');
            
            $timer=array("start"=>$startTime,"end"=>$endTime);
            $this->assign('time', $timer);
            $this->assign('money',$this->money);
            $this->assign('excelname', $file_types[0]);
            $this->assign('sku', $this->orderdata);
            $this->assign('user', $user);
            return $this->fetch();
        }
    }
    //日期范围报告
    private function getAmazonOrder($data){
        ini_set("memory_limit","1024M");
        $sku=$this->getnewSku($data['sku']);
        if (is_array($sku))return;
        if(!isset($this->orderdata[$sku]))$this->orderdata[$sku]=$this->getOneData($sku);
        if($data['type']=='Order'){
            $this->orderdata[$sku]['quantity']+=floatval($data['quantity']);
        }
        if($data['type']=='Refund'){
            $this->orderdata[$sku]['refund']+=floatval($data['quantity']);
            $this->orderdata[$sku]['refundsales']+=floatval($data['product sales']);
            $this->orderdata[$sku]['refundtotal']+=floatval($data['total']);
        }
        $this->orderdata[$sku]['sales']+=floatval($this->getMoney($data['product sales']));
        $this->orderdata[$sku]['total']+=floatval($this->getMoney($data['total']));
    }
    //每月库存历史记录
    private function getAmazonSellableOld($data){
        $sku=$this->getnewSku($data['sku']);
        if (is_array($sku))return;
        if(!isset($this->orderdata[$sku]))$this->orderdata[$sku]=$this->getOneData($sku);
        $this->orderdata[$sku]['selltableold']+=floatval($data['end-quantity']);
    }
    //每月库存历史记录
    private function getAmazonSellable($data){
        $sku=$this->getnewSku($data['sku']);
        if (is_array($sku))return;
        if(!isset($this->orderdata[$sku]))$this->orderdata[$sku]=$this->getOneData($sku);
        $this->orderdata[$sku]['selltable']+=floatval($data['end-quantity']);
    }
    //库存调整
    private function getAmazonRecon($data){
        $sku=$this->getnewSku($data['sku']);
        if (is_array($sku))return;
        if(!isset($this->orderdata[$sku]))$this->orderdata[$sku]=$this->getOneData($sku);
        $this->orderdata[$sku]['beginningquantity']+=floatval($data['beginning-quantity']);
        $this->orderdata[$sku]['endingquantity']+=floatval($data['ending-quantity']);
        $this->orderdata[$sku]['received']+=floatval($data['received']);
        $this->orderdata[$sku]['returned']+=floatval($data['returned']);
        $this->orderdata[$sku]['found']+=floatval($data['found']);
        $this->orderdata[$sku]['sold']+=floatval($data['sold']);
        $this->orderdata[$sku]['removed']+=floatval($data['removed']);
        $this->orderdata[$sku]['lost']+=floatval($data['lost']);
        $this->orderdata[$sku]['disposed']+=floatval($data['disposed']);
        $this->orderdata[$sku]['other']+=floatval($data['other']);
        $this->orderdata[$sku]['discrepantquantity']+=floatval($data['discrepant-quantity']);
    }
    //本年度配货量（业务员）
    private function getUserFba($data){
        if($data['country-code']===$this->country){
            $sku=$this->getnewSku($data['sku']);
            if (is_array($sku))return;
            if(!isset($this->orderdata[$sku]))$this->orderdata[$sku]=$this->getOneData($sku);
            $this->orderdata[$sku]['userquantity']+=floatval($data['quantity']);
        }
    }
    
    
    //获取表格要对应的数据
    private function getPHPData($PHPExcel, $field, $fun, $type) {
        $sheet = $PHPExcel->getSheet(0); // 读取第一個工作表
        $highestRow = $sheet->getHighestRow(); // 取得总行数
        $highestColumm = $sheet->getHighestColumn(1); // 取得总列数    
        $highestColumm = \PHPExcel_Cell::columnIndexFromString($highestColumm); //字母列转换为数字列 如:AA变为27
        //获取第一排的数据用于匹配d
        for ($col = 0; $col <= $highestColumm; $col++) {
            $value = $sheet->getCellByColumnAndRow($col, 1)->getValue();
            if(isset($this->Txt2NewTxt[$value]))$value=$this->Txt2NewTxt[$value];
            if (isset($field[$value])) {
                $field[$value] = $col;
            }
        }
        for ($row = 2; $row <= $highestRow; $row++) { //列数是以第0列开始
            $data = array();
            foreach ($field as $key => $value) {
                $keyvalue=$sheet->getCellByColumnAndRow($value, $row)->getValue();
                $data[$key] = $sheet->getCellByColumnAndRow($value, $row)->getValue();
                if(isset($this->Txt2NewTxt[$keyvalue]))$data[$key]=$this->Txt2NewTxt[$keyvalue];
            }
            if (!is_array($type)) {
                if (isset($data[$type])) {
                    $this->$fun($data);
                }
            }elseif($data[$type[0]]==$type[1]){
                $this->$fun($data);
            }
        }
    }

    //执行文件获取
    private function getfile($filename){
        $fileName = isset($_FILES[$filename]) ? $_FILES[$filename]["name"] : "";
        $file = isset($_FILES[$filename]) ? $_FILES[$filename]["tmp_name"] : "";
        $ext = \file::get_ext($fileName);
        if ($ext == "xls") {
            $reader = \PHPExcel_IOFactory::createReader('Excel5');
        } else {
            $reader = \PHPExcel_IOFactory::createReader('Excel2007');
        }
        $PHPExcel = $reader->load($file);
        return $PHPExcel;
    }
    //获取对应的数据表头
    private function getOneData($sku){
        return array('sku'=>$sku,'userquantity'=>0,'quantity'=>0,'refund'=>0,'sales'=>0,'total'=>0,'refundsales'=>0,'refundtotal'=>0,'selltable'=>0,'selltableold'=>0,'beginningquantity'=>0,'endingquantity'=>0,'received'=>0,'returned'=>0,'found'=>0,'sold'=>0,'removed'=>0,"lost"=>0,"disposed"=>0,"other"=>0,"discrepantquantity"=>0);
    }
    //转化SKU
    private function getnewSku($sku){
        if (strlen($sku) != 16) {
            $sku = str_replace("_par", "", $sku);
            $sku = preg_replace("/^[a-zA-Z]+/", "g", $sku);
        }
        return $sku;
    }
    //对德国金钱转义方式
    private function getMoney($money){
        if(strpos($money,',') !== false){ 
            $money=str_replace( '.','',$money);
            $money=str_replace( ',','.',$money);
        }
        return $money;
    }
}
