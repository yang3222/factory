<?php
namespace app\admin\controller;

use \app\admin\controller;
use app\admin\model\Order as OrderModel;
use \app\admin\model\Orderfactory;
use \think\Db;
use \app\admin\model\Product;
use app\admin\model\Userinfo;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Index
 *
 * @author 27532
 */
class Finance extends Excel{
    protected $pageTotalItem=40;
    public function __construct() {
        parent::__construct();
        $this->assign('currentMenu',array('menu'=>'menu7','nav'=>'nav0'));
    }
    //财务订单
    public function index(){
        $db=$this->order();
        $data=$db->paginate($this->pageTotalItem,false,['query' =>request()->param()]);
        $OrderFacModel = new Orderfactory();
        $res = array();
        foreach ($data as $key => $v) {
            $order_id = $v['id'];
            $v['orderFactory'] = $OrderFacModel->where('order_id',$order_id)->relation('userinfo')->select();//关联查找
            if (empty($v['orderFactory'])) {
                $v['orderFactory'] = array();
            }
            $res[$key] = $v;
        }
        $this->assign('eventJS','order');
        $this->assign('list',$res);
        $this->assign('pageDiv', $data->render());
        return $this->fetch();
    }
    //导出Excel内容
    public function excel(){
        ini_set("memory_limit", "512M");
        $header = array('订单号', '分销商', 'SKU', '产品SKU', '运单号', '名称', '生产状态', '型号', '数量','返工次数','印花厂','印花状态','加工厂','加工状态','订单备注','平台备注','签收备注','下单时间','签收时间');
        $index = array('OrdNum', 'AgntName', 'GdsSku', 'ProSku', 'TrnNo', 'ProName', 'status', 'SpecName', 'GdsNum','BlackNum','Print','PrintType','Mac','MacType','OdrMemo','FFYMemo','SignMemo','GetTimer','SignTimer');
        $db=$this->order();
        $data=$db->select();
        $this->createtable($data, "123", $header, $index);
    }
    //生成Excel
    protected function createtable($list, $filename, $header = array(), $index = array()) {
        $productdata=$this->productdata();
        $factorydata=$this->userinfo();
        header("Content-type:application/vnd.ms-excel");
        header("Content-Disposition:filename=" . $filename . ".xls");
        $teble_header = implode("\t", $header);
        $strexport = $teble_header . "\r";
        foreach ($list as $row) {
            $timeName = "SignTimer";
            $factorys=GetOrderFactory($row['id']);
            $printArr=array();
            $macArr=array();
            $printTypeArr=array();
            $macArrTypeArr=array();
            foreach($factorys as $factory){
                $sign="新订单";
                if($factory->sign=="1"){
                    $sign="生产中";
                }elseif($factory->sign=="2"){
                    $sign="已出库";
                }
                $text=$factorydata[$factory->factory_id];
                if($factory->working_type=="1"){
                    $printArr[]=$text;
                    $printTypeArr[]=$sign;
                }else{
                    $macArr[]=$text;
                    $macArrTypeArr[]=$sign;
                }
            }
            foreach ($index as $val) {
                if($val=="status"){
                    //生产状态
                    if($row['status']=="0"||$row['status']=="1"){
                        $row[$val] ="未签收";
                    }elseif($row['status']=="2"){
                        $row[$val] ="已签收";
                    }elseif($row['status']=="3"){
                        $row[$val] ="已取消";
                    }
                }
                if($val=="ProName"){
                    //产品名称
                    $row[$val] =isset($productdata[$row['product_id']])?$productdata[$row['product_id']]:"未知名称";
                }
                if($val=="ProSku"){
                    //产品SKU
                    $row[$val]=preg_replace("/g(\S*)p/","p",$row['GdsSku']);
                }
                if($val=="Print"){
                    //印花厂
                    $row[$val] =implode(",", $printArr);
                }
                if($val=="PrintType"){
                    //印花厂
                    $row[$val] =implode(",", $printTypeArr);
                }
                if($val=="Mac"){
                    //加工厂
                    $row[$val] =implode(",", $macArr);
                }
                if($val=="MacType"){
                    //加工厂
                    $row[$val] =implode(",", $macArrTypeArr);
                }
                if ($val == "OdrMemo" || $val == "FFYMemo" || $val == "SignMemo") {
                    $row[$val] = $this->newMemo($row[$val]);
                }
                $strexport.=$row[$val] . "\t";
            }
            $strexport.="\r";
        }
        $strexport = iconv('utf-8', "utf-8", $strexport);
        exit($strexport);
    }
    //获取新的备注信息
    private function newMemo($memo) {
        return str_replace(array("\r\n", "\r", "\n"), " ", $memo);
    }
    //产品信息
    private function productdata(){
        $product= new Product();
        $productdata=$product->select();
        $data=array();
        foreach($productdata as $onep){
            $data[$onep['product_id']]=$onep['name'];
        }
        return $data;
    }
    private function userinfo(){
        $factoryuser=new Userinfo();
        $userdata=$factoryuser->select();
        $data=array();
        foreach($userdata as $onep){
            $data[$onep['user_id']]=$onep['Name'];
        }
        return $data;
    }
    //订单数据
    private function order(){
        $startData=session('order_start_time')==""?date('Y-m-d',strtotime('-6 day')):session('order_start_time');
        $endData=session('order_end_time')==""?date('Y-m-d'):session('order_end_time');
        if(!empty(input('request.start_time')))$startData=input('request.start_time');
        if(!empty(input('request.end_time')))$endData=input('request.end_time');
        session('order_start_time',$startData);
        session('order_end_time',$endData);
        $search=input('request.search');
        $searchproduct=input('request.product_id');
        $factorysearch=input('request.factorysearch');
        
        $timeArr=array('GetTimer','GetTimer','pro_time','library_time','SignTimer','SignTimer','SignTimer');
        $time_name=$timeArr[0];
        $order='id desc';
        $db=Db::view('new_order a','*');
        $factorysearchboo=false;
        //搜索工厂
        if (!empty($factorysearch)&&!$factorysearchboo) {
            $db->view('order_factory b','order_id','b.order_id=a.id')->where([
                'endboo'=>'1',
                'factory_id'=>['in',explode(",",$factorysearch)]
            ])->group('a.id');
        }
        //有搜索内容
        if(!empty($search)){
            $where = array(
                'AgntName' => ['like', "%{$search}%"],
                'OdrId' => "$search",
                'OrdNum' => "$search",
                'GdsSku' => "$search",
                'TrnNo' => "$search",
                'OdrMemo' => ['like', "%{$search}%"],
            );
            $db->where(function($query)use($where){
               $query->whereor($where); 
            });
        }
        //根据产品进行搜索
        if(!empty($searchproduct)){
            $db->where('product_id','in',explode(",",$searchproduct));
        }
        //根据时间段进行搜索
        if(!empty($time_name)){
            $db->where([$time_name=>['between',[$startData." 00:00:00",$endData." 23:59:59"]]]);
        }
        $db->order($order);
        $this->assign('date',array('start_time'=>$startData,'end_time'=>$endData));
        $this->assign('searchproduct',$searchproduct);
        $this->assign('searcfactory',$factorysearch);
        return $db;
    }
}
