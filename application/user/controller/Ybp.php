<?php
namespace app\user\controller;

use \think\Db;
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
   
    public function __construct() {
        parent::__construct();
        $this->assign('title','仪表盘');
    }
    //订单列表
    public function index($signtype=''){
       
        //如果session时间空 就最近7天
        $startData=session('ybp_start_time')==""?date('Y-m-d',strtotime('-6 day')):session('ybp_start_time');
        $endData=session('ybp_end_time')==""?date('Y-m-d'):session('ybp_end_time');
        if(!empty(input('request.start_time')))$startData=input('request.start_time');
        if(!empty(input('request.end_time')))$endData=input('request.end_time');
        
        //设置时间类型保存到session
        session('time_type',input('request.sdate') != 'custom'? '1':'2');
        
        if(session('time_type') == '1'){
           $times = strtotime(date('Y-m-d'))-strtotime($endData);
           $startData = date('Y-m-d',strtotime($startData)+$times);
           $endData =date('Y-m-d',strtotime($endData)+$times);
        }
        
        session('ybp_start_time',$startData);
        session('ybp_end_time',$endData);
        
        //两个时间共几天 echart 参数
        $Days = round((strtotime($endData)-strtotime($startData))/3600/24);
        
        $productStr = "";
        $searchproduct=input('request.product_id');
        
         //根据产品进行搜索
        if(!empty($searchproduct)){
           $productStr = " and `product_id` IN (".$searchproduct.") ";
        }
        $sqlStr = "SELECT DATE(t.`GetTimer`) createtime,SUM(t.GdsNum) order_number from (SELECT  DISTINCT a.* FROM `ink_new_order` `a` left JOIN `ink_order_factory` `b`". 
            "ON `a`.`id` = `b`.`order_id` WHERE `factory_id` = ".session('user_id'). $productStr." and `GetTimer` BETWEEN '".$startData."' AND '".
            date('Y-m-d',strtotime($endData)+86400)."')t GROUP BY createtime order by createtime asc;";
        
        $data = Db::query($sqlStr);
        $dataCount = count($data);
      
        $orderCount = "";
        $dataStr = "";
        $dataI = 0;
        
        for($i=0; $i<=$Days; $i++ ){
                
           $currentDate =  date("Y-m-d", strtotime($startData)+86400*$i);
           
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
        
        $this->assign('date',array('start_time'=>$startData,'end_time'=>$endData));
       
        $this->assign('eventJS','ybp');
        $this->assign('day', $Days);
        $this->assign('list',$data);
        $this->assign('sign',$signtype);
        return $this->fetch();
    }
}
