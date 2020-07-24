<?php

namespace app\admin\controller;



use app\admin\controller;
use app\admin\model\Product as ProductModel;
use app\admin\model\Productsize;
use app\admin\model\User;
use app\admin\model\Productfactory;
use app\admin\model\Manufacture;
use app\admin\model\Userinfo;
use app\admin\model\Orderfactory;
use app\admin\model\Order;
use app\admin\model\Productionstatus;
use app\admin\model\Capacity;

class Productionmanage extends Base {

    protected $pageTotalItem = 40;

    public function __construct() {

        parent::__construct();

        $this->assign('currentMenu', array('menu' => 'menu1', 'nav' => 'nav3'));

    }

    //首页
    public function index() {
        $factoryModle = new User();
        $factory = $factoryModle->scope('showfactory')->relation('userinfo')->order('id desc')->select();
        $fac = array();//加工
        $pri = array();//印花
        foreach ($factory as $fack => $facv) {
            if (strpos($facv['fac_attribute'], '1') !== false) {
                $pri[] = $facv;//印花
            }
            if (strpos($facv['fac_attribute'], '2') !== false) {
                $fac[] = $facv;//加工
            }
        }
        $this->assign('factory', $factory);
        $this->assign('fac', $fac);//加工
        $this->assign('pri', $pri);//印花
        if ($this->isMobile == 1) {
            return $this->fetch('productionmanage/mobile_index');
        } else {
            return $this->fetch();
        }
    }

    //订单
    public function orderFac() {
        $data = input('get.');
        $reload = false;
        //$OrdNum = '';
        $userinfo = new Userinfo();
        if (!empty($data['pFactory'])) {
            $pFactory = $userinfo->where('user_id',$data['pFactory'])->find();
        } else {
            $pFactory = array('user_id' => '', 'Name' => '');
        }
        if (!empty($data['mFactory'])) {
            $mFactory = $userinfo->where('user_id',$data['mFactory'])->find();
        } else {
            $mFactory = array('user_id' => '', 'Name' => '');
        }

        $list = array();
        if ($this->request->isPost()) {
            $postData = input('post.');
            if (empty($postData['OrdNum'])) {
                return json(['code' => 1001, 'msg' => '订单号为空']);
            }
            //$OrdNum = $postData['OrdNum'];
            $where['OrdNum|OdrId'] = $postData['OrdNum'];
            $where['status'] = ['in','0,4'];//新订单状态0，跟未设置工厂4
            $orderModel = new Order();
            $res = $orderModel->where($where)->select();
            if (empty($res)) {
                $BeginTime = date("Y-m-d", time() - 3600 * 24 * 10);
                $EndTime = date("Y-m-d", time());
                $url = 'http://webapi.38420.com/api/Order/QueryOrderByRecNo';
                $post_data = array(
                    'RecNo' => $postData['OrdNum'],
                    'BeginTime' => $BeginTime,
                    'EndTime' => $EndTime
                );

                $entsku_order = send_post($url, $post_data);
                $entsku = json_decode($entsku_order, true);
                if (count($entsku) == 1 && isset($entsku[0])) {
                    $where2 = array(
                        'OdrId' => $entsku[0]['OdrId'],
                        'status' => ['in', '0,4']//新订单状态0，跟未设置工厂4
                    );
                    unset($res);
                    $res = $orderModel->where($where2)->select();
                }
            }
            $OrderFacModel = new Orderfactory();
            $ProductionstatusModel = new Productionstatus();
            foreach ($res as $k => $v) {
                $order_id = $v['id'];
                $v['orderFactory'] = $OrderFacModel->where('order_id',$order_id)->relation('userinfo')->select();//关联查找
                $v['productstatus'] = $ProductionstatusModel->field('id,status_name,abbreviation,bar_code')->where('id', $v['ProductionStatusId'])->find();

                if (empty($v['orderFactory'])) {
                    $v['orderFactory'] = array();
                    $date1 = date_create($v['AmzTimer']);
                    $date2 = date_create(date('Y-m-d H:m:s', time()));
                    $interval = date_diff($date1, $date2);
                    $diff = str_replace('-','', $interval->format('%R%a'));
                    $diff = str_replace('+','', $diff);
                    $v['days_diff'] = $diff;
                    $list[$k] = $v;
                } else {
                    /*$is_sign = false;//是否已经在生产
                    foreach ($v['orderFactory'] as $kk => $vv) {
                        if ($vv['sign'] == 1 || $vv['sign'] == 2) {
                            $is_sign = true;
                        }
                    }*/
                    //生产中也可以显示
                    if (true) {
                        $date1 = date_create($v['AmzTimer']);
                        $date2 = date_create(date('Y-m-d H:m:s', time()));
                        $interval = date_diff($date1, $date2);
                        $diff = str_replace('-','', $interval->format('%R%a'));
                        $diff = str_replace('+','', $diff);
                        $v['days_diff'] = $diff;
                        $list[$k] = $v;
                    }
                }

            }
            $autoConfirm = false;
            if (count($list) == 1) {
                $autoConfirm = true;
            }
            $jsondata['autoConfirm'] = $autoConfirm;
            $jsondata['list'] = $list;
            $jsondata['code'] = 1000;
            if (count($list) == 0) {
                $jsondata['code'] = 1001;
            }
            /*if (count($list) > 0) {
                $capacityModel = new Capacity();
                $capacity_data = array();
                foreach ($list as $n_k => $n_v) {
                    $capacity_data[] = array(
                        'user_id' => session('admin_id'),
                        'user_name' => session('admin_name'),
                        'action' => '生产派单',
                        'status' => 1,
                        'order_data' => $n_v['OrdNum'],//['OrdNum']
                        'creat_time' => date('Y-m-d H:i:s', time())
                    );
                }
                $capacityModel->savedata($capacity_data);
            }*/
            return json($jsondata);
        }
        if (empty($data['pFactory']) && empty($data['mFactory'])) {
            $reload = true;
        }
        $this->assign('autoConfirm', false);
        $this->assign('mFactory', $mFactory);
        $this->assign('pFactory', $pFactory);
        $this->assign('list', $list);
        //$this->assign('OrdNum', $OrdNum);
        $this->assign('reload', $reload);
        if ($this->isMobile == 1) {
            return $this->fetch('productionmanage/mobile_order_fac');
        }
        return $this->fetch();
    }

    //指派工厂
    public function appointFac() {
        if (!$this->request->isPost()) {
            return json(['code' => 1001, 'msg' => '此为post接口']);
        }
        $data = input('post.');
        $pFactory = '';
        $mFactory = '';
        $order_id = array();
        if (count($data['order_id']) > 0) $order_id = $data['order_id'];
        if (!empty($data['pFactory'])) $pFactory = $data['pFactory'];
        if (!empty($data['mFactory'])) $mFactory = $data['mFactory'];
        if (empty($pFactory) && empty($mFactory)) {
            return json(['code' => 1002, 'msg' => '请选择打印/加工工厂']);
        }
        if (count($order_id) == 0) {
            return json(['code' => 1003, 'msg' => '请选择指派订单']);
        }
        $orderFacModel = new Orderfactory();
        $orderModel = new Order();
        $FacArray = array();
        /**/
        $capacity_data = array();
        if (!empty($pFactory)) {
            //印花,working_type:1
            //$pFactory = explode(',', $data['pFactory']);
            //$mFactory = explode(',', $data['mFactory']);
            foreach ($order_id as $k => $v) {

                $pWhere['order_id'] = $v;
                $pWhere['working_type'] = 1;
                //$pWhere['sign'] = 0;
                $findOrderFac = $orderFacModel->relation('userinfo')->where($pWhere)->find();
                if (!empty($findOrderFac)) {
                    $orderFacModel->update(['factory_id' => $pFactory, 'sign' => 0], ['id' => $findOrderFac['id']]);
                } else {
                    $FacArray[] = ['factory_id' => $pFactory, 'working_type' => 1, 'order_id' => $v, 'sign' => 0];
                }
                $order_data = $orderModel->where('id', $v)->find();
                $img_url = explode(',', $order_data['ImgURL']);
                $capacity_data[$v] = array(
                    'user_id' => session('admin_id'),
                    'user_name' => session('admin_name'),
                    'action' => 2,//1生产状态，2订单指派
                    'status' => '',//状态名称
                    'assing_fac' => $findOrderFac['userinfo']['Name'],//指派工厂
                    'order_id' => $v,
                    'order_img' => $img_url[0],
                    'order_amount' => $order_data['GdsNum'],
                    'order_sku' => $order_data['GdsSku'],
                    'order_nums' => $order_data['OrdNum'],//['OrdNum']
                    'creat_time' => date('Y-m-d H:i:s', time())
                );
            }

        }
        if (!empty($mFactory)) {
            //加工,working_type:2
            //$pFactory = explode(',', $data['pFactory']);
            //$mFactory = explode(',', $data['mFactory']);
            foreach ($order_id as $k => $v) {
                $pWhere['order_id'] = $v;
                $pWhere['working_type'] = 2;
                //$pWhere['sign'] = 0;
                $findOrderFac = $orderFacModel->relation('userinfo')->where($pWhere)->find();
                if (!empty($findOrderFac)) {
                    $orderFacModel->update(['factory_id' => $mFactory, 'sign' => 0], ['id' => $findOrderFac['id']]);
                } else {
                    $FacArray[] = ['factory_id' => $mFactory, 'working_type' => 2, 'order_id' => $v, 'sign' => 0];
                }
                if (isset($capacity_data[$v])) {
                    $capacity_data[$v]['assing_fac'] = $capacity_data[$v]['assing_fac'] . ',' . $findOrderFac['userinfo']['Name'];
                } else {
                    $order_data = $orderModel->where('id', $v)->find();
                    $img_url = explode(',', $order_data['ImgURL']);
                    $capacity_data[$v] = array(
                        'user_id' => session('admin_id'),
                        'user_name' => session('admin_name'),
                        'action' => 2,//1生产状态，2订单指派
                        'status' => '',//状态名称
                        'assing_fac' => $findOrderFac['userinfo']['Name'],//指派工厂
                        'order_id' => $v,
                        'order_img' => $img_url[0],
                        'order_amount' => $order_data['GdsNum'],
                        'order_sku' => $order_data['GdsSku'],
                        'order_nums' => $order_data['OrdNum'],//['OrdNum']
                        'creat_time' => date('Y-m-d H:i:s', time())
                    );
                }
            }
        }
        if (count($FacArray) > 0) {
            foreach ($order_id as $k => $v) {
                $data = Order::get($v);
                if (!empty($data)) {
                    if ($data['status'] == 4) {
                        $data->status = 0;
                        $data->save();
                    }
                }

            }
        }
        $res = $orderFacModel->saveAll($FacArray);
        if ($res!==false) {
            $capacityModel = new Capacity();
            $capacityModel->savedata($capacity_data);
            return json(['code' => 1000, 'msg' => '指派成功']);
        } else {
            return json(['code' => 1001, 'msg' => '指派失败，请重试']);
        }

    }

}

