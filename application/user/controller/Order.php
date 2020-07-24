<?php
namespace app\user\controller;

use \think\Db;
use app\admin\model\Productionstatus;
use app\user\model\Orderfactory;
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
class Order extends Base{
    protected $pageTotalItem=40;
    public function __construct() {
        parent::__construct();
        $this->assign('title','订单列表');
        $this->assign('currentMenu',array('menu'=>'order','nav'=>'order_list'));
    }
    //订单列表
    public function index($signtype=''){
        //如果session时间空 就最近7天
        $startData=session('order_start_time')==""?date('Y-m-d',strtotime('-6 day')):session('order_start_time');
        $endData=session('order_end_time')==""?date('Y-m-d'):session('order_end_time');
        if(!empty(input('request.start_time')))$startData=input('request.start_time');
        if(!empty(input('request.end_time')))$endData=input('request.end_time');
        
        //设置时间类型保存到session
        session('time_type',input('request.sdate') != 'custom'? '1':'2');
        
        if(session('time_type') == '1'){
           $times = strtotime(date('Y-m-d'))-strtotime($endData);
           $startData = date('Y-m-d',strtotime($startData)+$times);
           $endData =date('Y-m-d',strtotime($endData)+$times);
        }
        
        session('order_start_time',$startData);
        session('order_end_time',$endData);
        if ($this->isMobile == 1) {
            $startData = date('Y-m-d',strtotime('-365 day'));
            $endData = date('Y-m-d');
        }
        $search=input('request.search');
        $searchproduct=input('request.product_id');


        $timeArr=array('GetTimer','GetTimer','pro_time','library_time','SignTimer','SignTimer','SignTimer','GetTimer','GetTimer','GetTimer');//7,8状态的增加
        $time_name=$timeArr[0];
        $order='id desc';
        $db=Db::view('new_order a','*');
      
       
        //查询自己的数据
        $where['factory_id']= $this->user_id;
       
        if(!empty($signtype)){
            $time_name=$timeArr[$signtype];
            if($signtype==4||$signtype==5||$signtype==6||$signtype==7||$signtype==8){
                //暂停，签收，取消
                if($signtype == 8) {
                    $where=array(
                        'status'=>['=','5'],
                        'sign'=>['in',[0,1]],
                        'endboo'=>'0',
                    );

                } else {
                    //暂停，签收，取消
                    $where['status']=['=',($signtype-3)];
                }
            }else{
                $type=$signtype-1;
                if($type==0)$order='Urgent desc,AmzTimer asc,id desc';
                //新订单，生产中，已出库
                $where['status']=['=','0'];
                $where['sign']=$type;
                $where['endboo']='0';
                $where[$time_name]=['between',[$startData." 00:00:00",$endData." 23:59:59"]];
            }
        }
       $db->view('order_factory b','order_id','b.order_id=a.id')->where($where)->group('a.id');
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
        $data=$db->paginate($this->pageTotalItem,false,['query' =>request()->param()]);
        $res = array();
        $OrderFacModel = new Orderfactory();
        $proStatusModel = new Productionstatus();
        foreach ($data as $key => $v) {
            $order_id = $v['id'];
            $v['orderFactory'] = $OrderFacModel->where('order_id',$order_id)->relation('userinfo')->select();//关联查找
            if (empty($v['orderFactory'])) {
                $v['orderFactory'] = array();
            }
            /*$date1 = date_create($v['AmzTimer']);
            $date2 = date_create(date('Y-m-d H:m:s', time()));
            $interval = date_diff($date1, $date2);
            $diff = str_replace('-','', $interval->format('%R%a'));
            $diff = str_replace('+','', $diff);
            $v['days_diff'] = $diff;*/
            if ($v['ProductionStatusId'] != 0) {
                //生产状态id不为0，有绑定
                $v['production_status'] = $proStatusModel->field('id,status_name,abbreviation,bar_code')->where('id', $v['ProductionStatusId'])->find();
            }

            $res[$key] = $v;
        }
        if ($this->request->isPost() && $this->isMobile == 1) {
            $res_arr = array(
                'list' => $res,
                'sign' => $signtype,
                'lastpage' => $data->lastPage(),
                'currentpage' => $data->currentPage(),
            );
            return json($res_arr);
        }

        $this->assign('eventJS','order');
        $this->assign('date',array('start_time'=>$startData,'end_time'=>$endData));
        $this->assign('list',$res);
        $this->assign('searchproduct',$searchproduct);
       
        $this->assign('sign',$signtype);
        $this->assign('lastpage', $data->lastPage());
        $this->assign('currentpage', $data->currentPage());
        $this->assign('pageDiv', $data->render());
        if ($this->isMobile == 1) {
            return $this->fetch('order/mobile_index');
        }
        return $this->fetch();
    }
}
