<?php

namespace app\user\controller;



use app\user\controller;
use app\user\model\User;
use app\user\model\Userinfo;
use app\user\model\Orderfactory;
use app\user\model\Order;

class Productionmanage extends Base {

    protected $pageTotalItem = 40;

    public function __construct() {

        parent::__construct();

        $this->assign('currentMenu', array('menu' => 'order', 'nav' => 'nav1'));

    }

    //首页生产派单
    public function index() {
        $factoryModle = new User();
        $factory = $factoryModle->scope('showfactory')->relation('userinfo')->order('id desc')->select();
        $this->assign('factory', $factory);
        if ($this->isMobile == 1) {
            return $this->fetch('productionmanage/mobile_index');
        } else {
            return $this->fetch();
        }
    }

    //订单
    public function orderFac() {
        /*$data = input('get.');
        $reload = false;
        //$OrdNum = '';
        $userinfo = new Userinfo();
        if (!empty($data['pFactory'])) {
            $pFactory = $userinfo->where('user_id',$data['pFactory'])->find();
        } else {
            $pFactory = array('user_id' => '', 'Name' => '');
        }*/
        $userinfo = new Userinfo();
        $mFactory = $userinfo->where('user_id',$this->user_id)->find();

        $list = array();
        if ($this->request->isPost()) {
            $postData = input('post.');
            if (empty($postData['OrdNum'])) {
                return json(['code' => 1001, 'msg' => '订单号为空']);
            }
            //$OrdNum = $postData['OrdNum'];
            $where['OrdNum|OdrId'] = $postData['OrdNum'];
            $where['status'] = ['in',[0,4]];//新订单状态0，跟未设置工厂4
            $orderModel = new Order();
            $res = $orderModel->where($where)->select();
            $OrderFacModel = new Orderfactory();
            foreach ($res as $k => $v) {
                $order_id = $v['id'];
                $v['orderFactory'] = $OrderFacModel->where('order_id',$order_id)->relation('userinfo')->select();//关联查找
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
                    $is_sign = false;//是否已经在生产
                    foreach ($v['orderFactory'] as $kk => $vv) {
                        if ($vv['sign'] == 1 || $vv['sign'] == 2) {
                            $is_sign = true;
                        }
                    }
                    if (!$is_sign) {
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
            return json($jsondata);
        }
        /*if (empty($data['pFactory']) && empty($data['mFactory'])) {
            $reload = true;
        }*/
        $this->assign('autoConfirm', false);
        $this->assign('mFactory', $mFactory);
        //$this->assign('pFactory', $pFactory);
        $this->assign('list', $list);
        //$this->assign('OrdNum', $OrdNum);
        //$this->assign('reload', $reload);
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
        $FacArray = array();
        if (!empty($pFactory)) {
            //印花,working_type:1
            //$pFactory = explode(',', $data['pFactory']);
            //$mFactory = explode(',', $data['mFactory']);
            foreach ($order_id as $k => $v) {

                $pWhere['order_id'] = $v;
                $pWhere['working_type'] = 1;
                $pWhere['sign'] = 0;
                $findOrderFac = $orderFacModel->where($pWhere)->find();
                if (!empty($findOrderFac)) {
                    $orderFacModel->update(['factory_id' => $pFactory], ['id' => $findOrderFac['id']]);
                } else {
                    $FacArray[] = ['factory_id' => $pFactory, 'working_type' => 1, 'order_id' => $v, 'sign' => 0];
                }
            }

        }
        if (!empty($mFactory)) {
            //加工,working_type:2
            //$pFactory = explode(',', $data['pFactory']);
            //$mFactory = explode(',', $data['mFactory']);
            foreach ($order_id as $k => $v) {
                $pWhere['order_id'] = $v;
                $pWhere['working_type'] = 2;
                $pWhere['sign'] = 0;
                $findOrderFac = $orderFacModel->where($pWhere)->find();
                if (!empty($findOrderFac)) {
                    $orderFacModel->update(['factory_id' => $mFactory], ['id' => $findOrderFac['id']]);
                } else {
                    $FacArray[] = ['factory_id' => $mFactory, 'working_type' => 2, 'order_id' => $v, 'sign' => 0];
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
            return json(['code' => 1000, 'msg' => '指派成功']);
        } else {
            return json(['code' => 1001, 'msg' => '指派失败，请重试']);
        }

    }

}

