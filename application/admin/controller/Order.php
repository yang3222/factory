<?php
namespace app\admin\controller;

use \app\admin\controller;
use app\admin\model\Order as OrderModel;
use \app\admin\model\Orderfactory;
use \think\Db;
use app\admin\model\Productfactory;
use app\admin\model\Userinfo;
use app\admin\model\Productionstatus;
use app\admin\model\Product;
use app\admin\model\Orderstatusid;
use app\admin\model\Batchcode;
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
        $this->assign('currentMenu',array('menu'=>'menu1','nav'=>'nav0'));
    }
    public function index($signtype=''){
        //如果session时间空 就最近7天
        $startData=session('order_start_time')==""?date('Y-m-d',strtotime('-6 day')):session('order_start_time');
        $endData=session('order_end_time')==""?date('Y-m-d'):session('order_end_time');
        $start_time = input('request.start_time');
        $end_time = input('request.end_time');
        //$reload_mobile = input('request.reload_mobile');//1表示从pc过来，0表示其他
        if(!empty($start_time))$startData=input('request.start_time');
        if(!empty($end_time))$endData=input('request.end_time');
        
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
        $factorysearch=input('request.factorysearch');
        
        $timeArr=array('GetTimer','GetTimer','pro_time','library_time','SignTimer','SignTimer','SignTimer','GetTimer','GetTimer','GetTimer');//7,8状态的增加
        $time_name=$timeArr[0];
        $order='id desc';
        $all_db = Db::view('new_order a','GdsNum');
        $db=Db::view('new_order a','*');
        $factorysearchboo=false;
        if(!empty($signtype)){
            $time_name=$timeArr[$signtype];
            if($signtype==4||$signtype==5||$signtype==6||$signtype==7||$signtype==8){
                if($signtype == 8) {
                    $where=array(
                        'status'=>['=','5'],
                        'sign'=>['in',[0,1]],
                        'endboo'=>'0',
                    );
                    if (!empty($factorysearch)) {
                        $factorysearchboo=true;
                        $where['factory_id']=array('in',explode(",",$factorysearch));
                    }
                    $db->view('order_factory b','order_id','b.order_id=a.id')->where($where)->group('a.id');
                    $all_db->view('order_factory b','order_id','b.order_id=a.id')->where($where)->group('a.id');
                } else {
                    //暂停，签收，取消
                    /*if ($signtype == 7) {
                        $db->where('status', 'in', [4,5]);
                        $all_db->where('status', 'in', [4,5]);
                    } else {*/
                        $db->where('status', '=', $signtype - 3);
                        $all_db->where('status', '=', $signtype - 3);
                    /*}*/

                }
            } else{
                $type=$signtype-1;
                if($type==0)$order='Urgent desc,id desc';
                //sign: 新订单0，生产中1，已出库2
                $where=array(
                    'status'=>['=','0'],
                    'sign'=>$type,
                    'endboo'=>'0',
                    $time_name=>['between',[$startData." 00:00:00",$endData." 23:59:59"]]
                );
                //根据工厂进行搜索
                if (!empty($factorysearch)) {
                    $factorysearchboo=true;
                    $where['factory_id']=array('in',explode(",",$factorysearch));
                }
                $db->view('order_factory b','order_id','b.order_id=a.id')->where($where)->group('a.id');
                $all_db->view('order_factory b','order_id','b.order_id=a.id')->where($where)->group('a.id');
            }
        }
        //搜索工厂
        if (!empty($factorysearch)&&!$factorysearchboo) {
            $db->view('order_factory b','order_id','b.order_id=a.id')->where([
                'endboo'=>'1',
                'factory_id'=>['in',explode(",",$factorysearch)]
            ])->group('a.id');
            $all_db->view('order_factory b','order_id','b.order_id=a.id')->where([
                'endboo'=>'1',
                'factory_id'=>['in',explode(",",$factorysearch)]
            ])->group('a.id');
        }
        //有搜索内容
        if(!empty($search)){

            $orderModel = new OrderModel();
            $search_order = $orderModel->where(['AgntName' => ['like', "%{$search}%"],'OdrMemo' => ['like', "%{$search}%"],'OdrId' => "$search",'OrdNum' => "$search",'GdsSku' => "$search",'TrnNo' => "$search",])->select();
            $order_res = array();
            if (count($search_order) == 0) {
                $BeginTime = date("Y-m-d", time() - 3600 * 24 * 10);
                $EndTime = date("Y-m-d", time());
                $url = 'http://webapi.38420.com/api/Order/QueryOrderByRecNo';
                $post_data = array(
                    'RecNo' => $search,
                    'BeginTime' => $BeginTime,
                    'EndTime' => $EndTime
                );

                $entsku_order = send_post($url, $post_data);
                $entsku = json_decode($entsku_order, true);
                if (count($entsku) == 1 && isset($entsku[0])) {
                    $where2 = array(
                        'OdrId' => $entsku[0]['OdrId'],
                        //'status' => ['in', '0,4']//新订单状态0，跟未设置工厂4
                    );
                    $order_res = $orderModel->where($where2)->select();
                }
            }

            if (count($order_res) > 0 && isset($order_res[0]['OdrId'])) {
                $search = $order_res[0]['OdrId'];
            }

            $whereors = array(
                'AgntName' => ['like', "%{$search}%"],
                'OdrId' => "$search",
                'OrdNum' => "$search",
                'GdsSku' => "$search",
                'TrnNo' => "$search",
                'OdrMemo' => ['like', "%{$search}%"],
            );
            /*$db->where(function($query)use($whereors){
               $query->whereor($whereors);
            });*/
            $db->whereor($whereors);
            $all_db->whereor($whereors);
            /*$all_db->where(function($query)use($whereors){
               $query->whereor($whereors);
            });*/
        }

        //根据产品进行搜索
        if(!empty($searchproduct)){
            $db->where('product_id','in',explode(",",$searchproduct));
            $all_db->where('product_id','in',explode(",",$searchproduct));
        }
        //根据时间段进行搜索
        if(!empty($time_name)){
            $db->where([$time_name=>['between',[$startData." 00:00:00",$endData." 23:59:59"]]]);
            $all_db->where([$time_name=>['between',[$startData." 00:00:00",$endData." 23:59:59"]]]);
        }
        $db->order($order);
        $res = array();
        $data = $db->paginate($this->pageTotalItem,false,['query' =>request()->param()]);//在这里使用each()的方法返回的数据为空，因为data里为数组
        $all_data = $all_db->select();
        $total_gds = 0;
        cache('admin_order_all_data', $all_data, 9600);
        /*foreach ($all_data as $ad_val) {
            $total_gds = bcadd($total_gds, $ad_val['GdsNum']);
        }*/
        $OrderFacModel = new Orderfactory();

        $productionModel = new Product();
        $OrderstatusidModel = new Orderstatusid();

        foreach ($data as $key => $v) {
            $order_id = $v['id'];
            $v['orderFactory'] = $OrderFacModel->where(['order_id'=>$order_id])->relation('userinfo')->select();//关联查找
            if (empty($v['orderFactory'])) {
                $v['orderFactory'] = array();
            }

            $pro_id = $v['product_id'];
            $v['product_arr'] = $productionModel->where('product_id', $pro_id)->find();
            /*$date1 = date_create($v['AmzTimer']);
            $date2 = date_create(date('Y-m-d H:m:s', time()));
            $interval = date_diff($date1, $date2);
            $diff = str_replace('-','', $interval->format('%R%a'));
            $diff = str_replace('+','', $diff);
            $v['days_diff'] = $diff;*/
            /*if ($v['ProductionStatusId'] != 0) {
                //生产状态id不为0，有绑定
                $v['production_status'] = $proStatusModel->field('id,status_name,abbreviation,bar_code')->where('id', $v['ProductionStatusId'])->find();
            }*/
            $v['production_status'] = $OrderstatusidModel->where('order_id', $order_id)->order('add_time desc')->select();
            $res[$key] = $v;
        }
        //$aaa = $db->select();
        //print_r($res);
        if ($this->request->isPost() && $this->isMobile == 1) {
            $res_arr = array(
                'list' => $res,
                'sign' => $signtype,
                'lastpage' => $data->lastPage(),
                'currentpage' => $data->currentPage(),
            );
            return json($res_arr);//
        }
        /*$aa = implode(',', array_column($res, 'OdrId'));
        $this->assign('aa', $aa);*/
        $this->assign('eventJS','order');
        $this->assign('date',array('start_time'=>$startData,'end_time'=>$endData));
        $this->assign('list',$res);
        $this->assign('searchproduct',$searchproduct);
        $this->assign('searcfactory',$factorysearch);
        $this->assign('sign',$signtype);
        $this->assign('pageDiv', $data->render());
        $this->assign('lastpage', $data->lastPage());
        $this->assign('currentpage', $data->currentPage());
        $this->assign('operation_auth', session('admin_operation_auth'));
        $this->assign('isFba', ['' => '', 0 => '非FBA订单', 1 => 'FBA订单']);
        $this->assign('total_gds',$total_gds);
        if ($this->isMobile == 1) {
            return $this->fetch('order/mobile_index');
        }
        return $this->fetch();
    }

    public function allData() {
        $all = 0;
        $s_all_data = cache('admin_order_all_data');
        foreach ($s_all_data as $v) {
            $all = bcadd($all, $v['GdsNum']);
        }
        return json(['total_gds' => $all]);
    }
    //同步签收
    public function signorder($_count=0){
        $noSign=explode(',',input('post.nosign/a')[0]);
        //$noSign=array('132895','166282');
        $this->assign('currentMenu',array('menu'=>'menu1','nav'=>'nav2'));
        $dataModel=new OrderModel();
        $where=array('status'=>[['=','0'],['=','5'],'or']);
        if(isset($noSign)){
            $where['OdrId']=['not in',$noSign];
            $this->assign('noOdrId',$noSign);
        }
        $count=$dataModel->where($where)->group('OdrId')->count();
        $endboo=$count==$_count?true:false;
        
        if (!$endboo) {
            $dataModel=new OrderModel();
            $data = $dataModel->field('OdrId,OrdNum')->where($where)->order('id asc')->group('OdrId')->limit(400)->select();
            $this->assign('count', $count);
            $this->assign('data', $data);
        }
        $this->assign('endboo',$endboo);
        $this->assign('eventJS','signorder');
        
        return $this->fetch();
    }
    //同步签收的情况下进行批量设置
    public function postsignorder(){
        $signData=input('post.arr/a');
        //404877
        foreach($signData as $value){
            $signTimer=isset($value['SignTimer'])?$value['SignTimer']:date('y-m-d h:i:s');
            $orderModel=new OrderModel();
            $orderdata=$orderModel->where([
                        'OdrId' => $value['OdrId'],
                        'status' => '0'])->select();
            foreach($orderdata as $order){
                $status=isset($value['status'])?$value['status']:"2";
                $order->status=$status;
                $order->SignTimer=$signTimer;
                if($order->save()!==false){
                    $order->orderfactroy()->update(['endboo'=>'1']);
                }
            }
        }
        echo true;
    }
    //生产状态
    public function prostatus_detail($id){
        $this->view->engine->layout(false);
        $orderstatusidModel = new Orderstatusid();
        //status，状态简称，
        $orderstatus = $orderstatusidModel->where('order_id', $id)->order('add_time desc')->select();
       // $orderModel = new OrderModel();
        //$data = $orderModel->where('id', $id)->relation('orderfactroy,orderproduct')->find();
        //$facModel = new Orderfactory();
        //$fac = $facModel->where('order_id', $id)->relation('userinfo')->select();

        //$this->assign('data',$data);
        $this->assign('orderstatus',$orderstatus);
        //$this->assign('fac',$fac);
        return $this->fetch();
    }


    //订单详情
    public function intro($id){
        $this->view->engine->layout(false);
        $orderModel = new OrderModel();
        $data = $orderModel->where('id', $id)->relation('orderfactroy,orderproduct')->find();
        $facModel = new Orderfactory();
        $fac = $facModel->where('order_id', $id)->relation('userinfo')->select();

        $this->assign('data',$data);
        $this->assign('fac',$fac);
        return $this->fetch();
    }
    //设置加急
    public function seturgent(){
        $id=input('post.id');
        $orderModel=new OrderModel();
        if($orderModel->save(['Urgent'=>'1'],['id'=>$id])!==false){
            echo true;
        }else{
            echo "加急失败，请重新设置";
        }
    }
    //设置状态
    public function setsign($id){
        $data=  OrderModel::get($id);
        $data->status=input('post.status');
        $endboo="0";
        if($data->status==0){
            $data->SignTimer=null;
        }else{
            $endboo=1;
            $data->SignTimer=date('y-m-d h:i:s');
        }
        $data->SignMemo=input('post.memo');
        $boo=false;
        if($data->save()!==false){
            if( $data->orderfactroy()->update(['endboo'=>$endboo])!==false){
                $boo=true;
            }
        }
        if($boo){
            echo true;
        }else{
            echo "设置失败，请重新设置";
        }
    }
    //重新生产
    public function setgain($id){
        $orderfacModel = new Orderfactory();
        $data = OrderModel::get($id);
        if (!empty($data)) {
           if ($data['status'] == 5) {
               $data->status='4';
               $order_fac = $orderfacModel->where('order_id', '=', $id)->select();
                if(!empty($order_fac)) {
                    $orderfacModel->where(['order_id' => $id])->delete();
                }
           } else {
               $data->status='0';
           }
        }

        $data->SignTimer=null;
        $data->BlackNum=array('inc',1);
        $boo=false;
        if($data->save()!==false){
             if( $data->orderfactroy()->update(['endboo'=>'0','sign'=>'0'])!==false){
                $boo=true;
            }
        }
        if($boo){
            echo true;
        }else{
            echo "设置失败，请重新设置";
        }
    }
    //备注
    public function setmemo($id){
        $data=  OrderModel::get($id);
        $data->FFYMemo=input('post.memo');
        if($data->save()!==false){
            echo true;
        }else{
            echo "设置失败，请重新设置";
        }
    }
    //批量签收
    public function setsignAll(){
        $orderModel=new OrderModel();
        $ids=input('post.status/a');
        $memo=input('post.memo');
        $orderboo=$orderModel->save(['SignMemo'=>$memo,'status'=>2,'SignTimer'=>date('y-m-d h:i:s')],['id'=>['in',$ids],'status'=>'0']);
        $factory=new Orderfactory();
        $factoryboo=$factory->save(['endboo'=>'1'],['order_id'=>['in',$ids],'endboo'=>'0']);
        if($orderboo!==false&&$factoryboo!==false){
            echo true;
        }else{
            echo "签收失败，请重新签收";
        }
    }
    //订单状态更新
    public function newdata(){
        $idSection=array('406533','410000');//380000
        $oldordermodel=new \app\admin\model\Orderold();
        $oldordermodel->where('id','>=',$idSection[0])->where('id','<',$idSection[1])->chunk(100, function ($list){
            $orderfactory=new Orderfactory();
            $newdata=array();
            foreach ($list as $data) {
                $model=new OrderModel();
                $onedata=array();
                $onedata['id']=$data['id'];
                $onedata['OrdNum']=$data['OrdNum'];
                $onedata['OdrId']=$data['OdrId'];
                $onedata['AgntName']=$data['AgntName'];
                $onedata['GdsSku']=$data['GdsSku'];
                $onedata['GdsNum']=$data['GdsNum'];
                $onedata['UpData']=$data['UpData'];
                $onedata['TrnNo']=$data['TrnNo'];
                $onedata['SpecName']=$data['SpecName'];
                $onedata['ImgURL']=$data['ImgURL'];
                $onedata['Type']=$data['Type'];
                $onedata['OdrMemo']=$data['OdrMemo'];
                $onedata['FFYMemo']=$data['FFYMemo'];
                $onedata['SignMemo']=$data['SignMemo'];
                $onedata['Code']=$data['Code'];
                $onedata['BlackNum']=$data['BlackNum'];
                $onedata['BlackReason']=$data['BlackReason'];
                $onedata['status']=$data['Sign']>1?$data['Sign']:0;
                $onedata['AmzTimer']=$data['AmzTimer'];
                $onedata['GetTimer']=$data['GetTimer'];
                $onedata['SignTimer']=$data['SignTimer'];
                $onedata['Urgent']=$data['Urgent'];
                $onedata['product_id']=$data['ProductClass'];
                $onedata['PrintImgURL']=$data['PrintImgURL'];
                $onedata['stock_id']=$data['stock_id'];
                
                $onedata['Black_info']=null;
                $onedata['list_id']=null;
                
                $endboo=$onedata['status']==0?0:1;
                $sing=$data->library=="1"?"2":"1";
                if($data->Sign=="3"){
                    $sing=$data->Sign_cancel;
                    if($data->library=="1")$sing="2";
                }
                if($data->Sign=="1"){
                    $sing="0";
                    if($data->OpenBoo=="1")$sing="1";
                    if($data->library=="1")$sing="2";
                }
                if(!empty($data->PrintFactoryID)){
                    $printArr=explode(",",$data->PrintFactoryID);
                    foreach ($printArr as $factoryid) {
                        $newdata[] = array('order_id' => $data->id, 'factory_id' => $factoryid, 'working_type' => '1', 'sign' => $sing,'endboo'=>$endboo, 'pro_time' => $data->Protime, 'library_time' => $data->library_time);
                    }
                }
                if(!empty($data->MacFactoryID)){
                    $macArr=explode(",",$data->MacFactoryID);
                    foreach ($macArr as $factoryid) {
                        $newdata[] = array('order_id' => $data->id, 'factory_id' => $factoryid, 'working_type' => '2', 'sign' => $sing,'endboo'=>$endboo, 'pro_time' => $data->Protime, 'library_time' => $data->library_time);
                    }
                }
                $model->save($onedata);
            }
            if(count($newdata)>0)$orderfactory->saveAll($newdata);
        });
        echo "结束";
    }
    //编辑工厂
     public function showFactorys($id){
            $this->view->engine->layout(false);
            $orderFactory = new \app\index\model\Orderfactory();

            if (!is_numeric($id)){
                echo "参数错误！";
                exit;
            }
            $orderModel = new \app\admin\model\Order();
            $order = $orderModel->where('id', $id)->find();
            if ($order['status'] == 3) {

                $this->assign('orderid','cancel');
                $this->assign('info',array());
                $this->assign('factorys',array());
                return $this->fetch();
            }
            $data = $orderFactory->all(array('order_id'=>$id));
            
            $user=  \app\admin\model\User::scope('showfactory')->relation('userinfo')->order('id desc')->select();

            $factory_arr = array();
            foreach ($user as $fack => $facv) {
                if (strpos($facv['fac_attribute'], '1') !== false) {
                    $factory_arr['pri'][] = $facv;//印花
                }
                if (strpos($facv['fac_attribute'], '2') !== false) {
                    $factory_arr['fac'][] = $facv;//加工
                }
            }
            $this->assign('orderid',$id);
            $this->assign('info',$data);
            $this->assign('factorys',$factory_arr);
            //$this->assign('factorys',$user);
            return $this->fetch();
        }

    /**
     * 设置工厂
     */
    public function setFactory($id = '') {

        $this->view->engine->layout(false);
        $orderFactory = new \app\index\model\Orderfactory();
        if (empty($id)){
            echo "参数错误！";
            exit;
        }
        $orderModel = new \app\admin\model\Order();
        $order = $orderModel->where('id', $id)->find();
        if ($order['status'] == 3) {
            //$this->assign('cancelorder',1);
            $this->assign('orderid','cancel');
            $this->assign('info',array());
            $this->assign('factorys',array());
            return $this->fetch();
        }
        $data = $orderFactory->all(array('order_id'=>$id));
        $user=  \app\admin\model\User::scope('showfactory')->relation('userinfo')->order('id desc')->select();
        $this->assign('orderid',$id);
        if ($id == 'batchsefactory') {
            unset($data);
            $data = array();
        }
        $factory_arr = array();
        foreach ($user as $fack => $facv) {
            if (strpos($facv['fac_attribute'], '1') !== false) {
                $factory_arr['pri'][] = $facv;//印花
            }
            if (strpos($facv['fac_attribute'], '2') !== false) {
                $factory_arr['fac'][] = $facv;//加工
            }
        }
        $this->assign('info',$data);
        $this->assign('factorys',$factory_arr);
        return $this->fetch();
    }

    /**
     * 批量设置工厂
     */
    public function batchSetFactory($id = '') {

        $data = input('post.');
        if (!isset($data['pFactory'])) {
            $data['pFactory'] = array();
        }
        if (!isset($data['mFactory'])) {
            $data['mFactory'] = array();
        }
        // 启动事务
        Db::startTrans();
        try {
            $orderFactory = new \app\index\model\Orderfactory();
            $orderModel = new OrderModel();
            //印花工厂
            $factoryList = [];
            $orderModel = new \app\admin\model\Order();
            foreach ($data['id'] as $key => $value) {

                $order = $orderModel->where('id', $value)->find();
                if ($order['status'] == 3) {
                    continue;
                }
                foreach ($data['pFactory'] as $v) {
                    $factoryList[] = ['order_id' => $value, 'working_type' => 1, 'factory_id' => $v, 'sign' => 0, 'endboo' => 0];
                }
                //加工工厂
                foreach ($data['mFactory'] as $v) {
                    $factoryList[] = ['order_id' => $value, 'working_type' => 2, 'factory_id' => $v, 'sign' => 0, 'endboo' => 0];
                }
                $orderFactory->where(['order_id' => $value])->delete();
            }
            if(!empty($factoryList)){
                if(count($factoryList)>1){
                    $printMethod = "saveAll";
                }  else {
                    $printMethod = "save";
                    $factoryList = $factoryList[0];
                }
                if($orderFactory->$printMethod($factoryList) == false){
                    Db::rollback();
                    throw new \Exception("save data error!");
                }
            }
            //提交事务
            Db::commit();
            $order['status'] = 0;
            $orderModel->where('id', 'in', $data['id'])->update(['status' => 0]);
            exit('修改成功！');
        } catch (\Exception $e) {
            //回滚事务
            Db::rollback();
            echo "操作失败！";
            exit;
        }
    }

    /**
     * 保存工厂
     * */
    public function saveFactory() {
        $data = input('post.');
        if (!isset($data['pFactory'])) {
            $data['pFactory'] = array();
        }
        if (!isset($data['mFactory'])) {
            $data['mFactory'] = array();
        }
        // 启动事务
        Db::startTrans();
        try {
            $orderFactory = new \app\index\model\Orderfactory();
            $orderModel = new OrderModel();
            //印花工厂
            $factoryList = [];
            foreach ($data['pFactory'] as $v){
                $factoryList[]=['order_id'=>$data['id'],'working_type'=>1,'factory_id'=>$v,'sign'=>0,'endboo'=>0];
            }
            //加工工厂
            foreach ($data['mFactory'] as $v){
                $factoryList[]=['order_id'=>$data['id'],'working_type'=>2,'factory_id'=>$v,'sign'=>0,'endboo'=>0];
            }
            $orderFactory->where(['order_id'=>$data['id']])->delete();
            if(!empty($factoryList)){
                if(count($factoryList)>1){
                    $printMethod = "saveAll";
                }  else {
                    $printMethod = "save";
                    $factoryList = $factoryList[0];
                }
                if($orderFactory->$printMethod($factoryList) == false){
                    Db::rollback();
                    throw new \Exception("save data error!");
                }
            }
            //提交事务
            Db::commit();
            if (count($data['pFactory']) == 0 && count($data['mFactory']) == 0) {
                $order['status'] = 4;
            } else {
                $order['status'] = 0;
            }
            $orderModel->where('id', $data['id'])->update($order);
            exit('修改成功！');
        } catch (\Exception $e) {
            //回滚事务
            Db::rollback();
            echo "操作失败！";
            exit;
        }
    }


    //编辑工厂保存
    public function updateFactorys(){

        $data = input('post.');
        if (!isset($data['pFactory'])) {
            $data['pFactory'] = array();
        }
        if (!isset($data['mFactory'])) {
            $data['mFactory'] = array();
        }
        $orderModel = new OrderModel();
        // 启动事务
        Db::startTrans();
        try {
            $orderFactory = new \app\index\model\Orderfactory();

            //旧的已存在的，排除的id列表
            $oldList = [];
            //印花工厂
            $factoryList = [];
            foreach ($data['pFactory'] as $v){
               $oldPrintFactory = $orderFactory->where(['order_id'=>$data['id'],'working_type'=>1,'factory_id'=>$v])->select();
               if(empty($oldPrintFactory)){
                   $factoryList[] = ['order_id'=>$data['id'],'working_type'=>1,'factory_id'=>$v,'sign'=>0,'endboo'=>0];
               }  else {
                  $oldList[] =  $oldPrintFactory[0]['id'];
               }
            }

            //加工工厂           
            foreach ($data['mFactory'] as $v){
                 $oldMacFactory = $orderFactory->where(['order_id'=>$data['id'],'working_type'=>2,'factory_id'=>$v])->select();
                 if(empty($oldMacFactory)){
                    $factoryList[]=['order_id'=>$data['id'],'working_type'=>2,'factory_id'=>$v,'sign'=>0,'endboo'=>0];
                 }  else {
                    $oldList[] =  $oldMacFactory[0]['id'];
                 }
            }
          
            if(!empty($oldList)){
                $orderFactory->where([
                    'order_id'  =>  $data['id'],
                    'id'        =>  ['not in', implode(",", $oldList)]
                    ])->delete();
            }  else {
                $orderFactory->where(['order_id'=>$data['id']])->delete();
            }
          
            if(!empty($factoryList)){

                if(count($factoryList)>1){
                    $printMethod = "saveAll";
                }  else {
                    $printMethod = "save";
                    $factoryList = $factoryList[0];
                }
              
                if($orderFactory->$printMethod($factoryList) == false){
                    Db::rollback(); 
                    throw new \Exception("save data error!");
                }
            }

            //提交事务
            Db::commit();
            if (count($data['pFactory']) == 0 && count($data['mFactory']) == 0) {
                $order['status'] = 4;
            } else {
                $order['status'] = 0;
            }
            $orderModel->where('id', $data['id'])->update($order);
            exit('修改成功！');
        } catch (\Exception $e) {
            //回滚事务
            Db::rollback();
            echo "操作失败！";
            exit;
        }

    }

}
