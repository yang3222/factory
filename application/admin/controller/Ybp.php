<?php
namespace app\admin\controller;

use \think\Db;
use app\admin\logic\Warehouse as logicWH ;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Ybp
 *
 * @author 27532        
 */
class Ybp extends Base{
    protected $pageTotalItem=40;
    public function __construct() {
        parent::__construct();
        $this->assign('title','仪表盘');
    }
    //订单列表
    public function index($signtype=''){

        $dataArr = logicWH::sessionQueryTime('ybp',input('request.'));

        //两个时间共几天 echart 参数
        $Days = round((strtotime($dataArr['end_date'])-strtotime($dataArr['start_date']))/3600/24);
        
        $productStr = "";
        $searchproduct=input('request.product_id');
        
         //根据产品进行搜索
        if(!empty($searchproduct)){
           $productStr = " and `product_id` IN (".$searchproduct.")";
        }
        
        $sqlStr = "SELECT DATE(`GetTimer`) createtime,SUM(a.GdsNum) order_number  FROM `ink_new_order` `a`"
                . "WHERE      `GetTimer` BETWEEN '".$dataArr['start_date']."' AND '".date('Y-m-d',strtotime($dataArr['end_date'])+86400)."' ".$productStr
                . "GROUP BY createtime order by createtime asc;";
        $data = Db::query($sqlStr);
        $dataCount = count($data);
      
        $orderCount = "";
        $dataStr = "";
        $dataI = 0;
        
        for($i=0; $i<=$Days; $i++ ){
            
           $currentDate =  date("Y-m-d", strtotime($dataArr['start_date'])+86400*$i);
           
           $dataStr = $dataStr."'".$currentDate."'".",";
           
           if($dataI >= $dataCount){
               $orderCount=$orderCount."0,";
           }else{
                if ( $data[$dataI]['createtime'] == $currentDate ){
                     $orderCount=$orderCount.$data[$dataI]['order_number'].",";
                     $dataI++;
                }else{
                     $orderCount=$orderCount."0,"; 
                }
           }
        }
        $this->assign('orderCount',$orderCount);
        $this->assign('dataStr',$dataStr);
        $this->assign('searchproduct',$searchproduct);
        $this->assign('date',$dataArr);
        $this->assign('eventJS','ybp');
        $this->assign('day', $Days);
        $this->assign('list',$data);
         $this->assign('sign',$signtype);
        return $this->fetch();
    }
}
