<?php

namespace app\user\controller;

use app\user\controller;
use app\user\model\Productionstatus;
use app\admin\model\Order;
use app\user\model\Orderfactory;

class Productionstatusset extends Base {

    protected $pageTotalItem = 40;

    public function __construct() {

        parent::__construct();

        $this->assign('currentMenu', array('menu' => 'order', 'nav' => 'nav2'));

    }

    //首页生产状态管理
    public function index() {
        $ProStatusModel = new Productionstatus();

        $ProStatus = $ProStatusModel->where('uid', $this->user_id)->paginate($this->pageTotalItem,false,['query' =>request()->param()]);
        $this->assign('prostatus', $ProStatus);
        $this->assign('pageDiv', $ProStatus->render());
        return $this->fetch();
    }

    public function get_barcode($text,$ab) {

        //$barcodes = new barcode($text);
        //$barcodes->createBarCode('png', '');
        barcode_cn('BCGcode128', $text, $ab, 60, 2);
        //Imagepng($drawing,ROOT_BARCODE_IMG.$text.'.png');
        //return;
    }
    public function savepng($text) {
        //Imagepng($this->get_barcode($text),ROOT_BARCODE_IMG.$text.'.png');
    }

    //添加生产状态
    public function add_pro_status() {
        if($this->request->isPost()) {
            $data = input('post.');
            $ProStatus = new Productionstatus();
            if (empty($data['status_name']) || empty($data['abbreviation'])) return json(['code' => 1001, 'msg' => '添加失败，请重试']);
            $data['creat_time'] = date('Y-m-d H:i:s', time());
            $data['uid'] = $this->user_id;
            $res = $ProStatus->saveprostatus($data);
            if ($res['code'] == 1000) {
                $id = $res['id'];
                $updata = array(
                    'bar_code' => $id . '-prostatus'
                );
                $ProStatus->update($updata, ['id' => $id]);
            }
            return json($res);
        }
        return $this->fetch();
    }

    //编辑生产状态
    public function edit_pro_status($id = '') {
        $ProStatus = new Productionstatus();
        if($this->request->isPost()) {
            $data = input('post.');
            $pro_status_id = $data['id'];
            $updata = array(
                'status_name' => $data['status_name'],
                'abbreviation' => $data['abbreviation'],
                'update_time' => date('Y-m-d H:i:s', time()),
                'bar_code' => $pro_status_id . '-prostatus'
            );
            $res = $ProStatus->updateprostatus($updata, $pro_status_id);
            return json($res);
        }
        $res = $ProStatus->where('id', '=', $id)->find();
        $this->assign('prostatus', $res);
        $this->assign('pro_status_id', $id);
        return $this->fetch();
    }

    //订单追踪
    public function product_status() {
        $proStatusModel = new Productionstatus();
        if ($this->request->isPost()) {
            $pro_data = input('post.');
            $pro_status = $proStatusModel->field('id,status_name,abbreviation,bar_code')->where(['id' => $pro_data['id'], 'uid' => $this->user_id])->find();
            if (empty($pro_status)) {
                //生产状态为空
                return json(['code' => 1002, 'msg' => '没有这个生产状态,请重新扫描', 'data' => '']);
            }
            return json(['code' => 1000, 'msg' => '', 'data' => $pro_status]);
        }

        $this->assign('pro_status', '');
        $this->assign('currentMenu',array('menu'=>'order','nav'=>'nav3'));
        if ($this->isMobile == 1) {
            return $this->fetch('productionstatusset/mobile_pro_status');
        } else {
            return $this->fetch();
        }
    }

    //绑定订单与生产状态
    public function bind_order_pro() {
        //create_barcode_bcg128('1-prostatus');
        if ($this->request->isPost()) {
            $orderModel = new Order();
            $data = input('post.');
            $pro_status_id = $data['pro_status_id'];
            $where = array(
                'OrdNum' => $data['OrdNum'],
                'status' => ['in', [0,1,2,4,5]]
            );
            $order = $orderModel->where($where)->select();

            if (empty($order)) {
                //没找到订单
                return json(['code' => 1002, 'msg' => '该订单不存在', 'list' => array()]);
            }
            $order_arr = array();
            $OrderFacModel = new Orderfactory();
            foreach ($order as $k => $v) {
                $order_id = $v['id'];
                $v['orderFactory'] = $OrderFacModel->where('order_id',$order_id)->relation('userinfo')->select();//关联查找
                if (empty($v['orderFactory'])) {
                    $v['orderFactory'] = array();
                }
                $date1 = date_create($v['AmzTimer']);
                $date2 = date_create(date('Y-m-d H:m:s', time()));
                $interval = date_diff($date1, $date2);
                $diff = str_replace('-','', $interval->format('%R%a'));
                $diff = str_replace('+','', $diff);
                $v['days_diff'] = $diff;
                $order_arr[$k] = $v;
                $order_arr[$k]['bind_pro_status_id'] = $pro_status_id;
            }
            $autoConfirm = false;
            if (count($order_arr) == 1) {
                $autoConfirm = true;
            }
            $res_data = array(
                'autoConfirm' => $autoConfirm,
                'code' => 1000,
                'list' => $order_arr
            );
            return json($res_data);
        }
        return json(['code' => 1001, 'msg' => '此为post接口', 'data' => '']);
    }

    //appointPro，更新订单中生产状态id
    public function appointPro() {
        if (!$this->request->isPost()) {
            return json(['code' => 1001, 'msg' => '此为post接口', 'data' => '']);
        }
        $data = input('post.');
        if (count($data['order_id']) > 0) {
            $order = $data['order_id'];
        } else {
            return json(['code' => 1003, 'msg' => '请选择指派订单']);
        }
        //print_r($data);

        $orderModel = new Order();
        $update_order = array();
        foreach ($order as $k => $v) {
            $arr = explode('-', $v);
            $update_order[$k]['id'] = $arr[0];
            $update_order[$k]['ProductionStatusId'] = $arr[1];
            //$orderModel->update(['ProductionStatusId' => $update_order[1], ['id' => $update_order[0]]]);
            $update_order[$k]['addStatusIdTime'] = date('Y-m-d H:i:s', time());
        }
        $res = $orderModel->isUpdate()->saveAll($update_order);

        if ($res !== false) {
            return json(['code' => 1000, 'msg' => '更新成功']);
        } else {
            return json(['code' => 1001, 'msg' => '更新失败，请重试']);
        }
    }
    
}

