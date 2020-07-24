<?php
namespace app\index\controller;
use \app\index\model\Product;
class Fba extends Excel
{
    private $orderdata=array();
    public function __construct() {
        parent::__construct();
    }
    //业务报表
    public function index(){
        $now = date("Y-m-d");
        $lastday = date('Y-m-d', strtotime("$now -1 month -1 day"));
        $timer = array("start" => $lastday, "end" => $now);
        $this->assign('time', $timer);
        return $this->fetch();
    }
    public function postexcel(){
        $this->excelStart();
        $this->savebigExcel();
        $startTime = input('post.start_time');
        $endTime = input('post.end_time');
        if (!empty($_FILES ['file_stu'] ['name'])&&!empty($_FILES ['file_fba'] ['name'])){
            $file_types = explode ( ".", $_FILES ['file_stu'] ['name'] );
            $this->getAmazonOrder($this->getfile('file_stu'));
            $this->getAmazonSellable($this->getfile('file_fba'));
            
            $timer=array("start"=>$startTime,"end"=>$endTime);
            $this->assign('time', $timer);
            $this->assign('excelname', $file_types[0]);
            $this->assign('sku', $this->orderdata);
            return $this->fetch();
        }
    }
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
    private function getAmazonOrder($PHPExcel){
        $sheet = $PHPExcel->getSheet(0); // 读取第一個工作表
        $highestRow = $sheet->getHighestRow(); // 取得总行数
        $highestColumm = $sheet->getHighestColumn(1); // 取得总列数    
        $highestColumm = \PHPExcel_Cell::columnIndexFromString($highestColumm); //字母列转换为数字列 如:AA变为27
        $field=array("sku"=>"","quantity"=>"","fulfillment"=>"","product sales"=>"","total"=>"");
        //获取第一排的数据用于匹配
        for ($col = 0; $col <= $highestColumm; $col++) {
            $value = $sheet->getCellByColumnAndRow($col, 1)->getValue();
            if(isset($field[$value])){
                $field[$value]=$col;
            }
        }
        for ($row = 2; $row <= $highestRow; $row++) { //列数是以第0列开始
            $data=array();
            foreach ($field as $key=>$value){
                $data[$key]=$sheet->getCellByColumnAndRow($value, $row)->getValue();
            }
            if($data['fulfillment']=='Amazon'){
                $this->getorderData($data);
            }
        }
    }
    private function getorderData($data){
        $sku=$data['sku'];
        if (is_array($sku))return;
        if (strlen($sku) != 16) {
            $sku = str_replace("_par", "", $sku);
            $sku = preg_replace("/^[a-zA-Z]+/", "g", $sku);
        }
        if(!isset($this->orderdata[$sku]))$this->orderdata[$sku]=array('sku'=>$sku,'quantity'=>0,'sales'=>0,'total'=>0,'selltable'=>0);
        $this->orderdata[$sku]['quantity']+=floatval($data['quantity']);
        $this->orderdata[$sku]['sales']+=floatval($data['product sales']);
        $this->orderdata[$sku]['total']+=floatval($data['total']);
    }
    private function getAmazonSellable($PHPExcel){
        $sheet = $PHPExcel->getSheet(0); // 读取第一個工作表
        $highestRow = $sheet->getHighestRow(); // 取得总行数
        $highestColumm = $sheet->getHighestColumn(1); // 取得总列数    
        $highestColumm = \PHPExcel_Cell::columnIndexFromString($highestColumm); //字母列转换为数字列 如:AA变为27
        $field=array("seller-sku"=>"","Quantity Available"=>"");
        //获取第一排的数据用于匹配d
        for ($col = 0; $col <= $highestColumm; $col++) {
            $value = $sheet->getCellByColumnAndRow($col, 1)->getValue();
            if(isset($field[$value])){
                $field[$value]=$col;
            }
        }
        for ($row = 2; $row <= $highestRow; $row++) { //列数是以第0列开始
            $data=array();
            foreach ($field as $key=>$value){
                $data[$key]=$sheet->getCellByColumnAndRow($value, $row)->getValue();
            }
            if(isset($data['seller-sku'])){
                $this->settable($data);
            }
        }
    }
    private function settable($data){
        $sku=$data['seller-sku'];
        if (is_array($sku))return;
        if (strlen($sku) != 16) {
            $sku = str_replace("_par", "", $sku);
            $sku = preg_replace("/^[a-zA-Z]+/", "g", $sku);
        }
        if(!isset($this->orderdata[$sku]))$this->orderdata[$sku]=array('sku'=>$sku,'quantity'=>0,'sales'=>0,'total'=>0,'selltable'=>0);
        $this->orderdata[$sku]['selltable']+=floatval($data['Quantity Available']);
    }
}
