<?php

namespace app\admin\controller;

use app\admin\controller;
use app\admin\model\Lgstcode;
use app\admin\model\Productionstatus;
use app\admin\model\Order;
use app\admin\model\Orderfactory;
use app\admin\controller\barcode;
use app\admin\model\Orderstatusid;
use app\admin\model\Capacity;

class Productionstatusset extends Base {

    protected $pageTotalItem = 40;

    public function __construct() {

        parent::__construct();

        $this->assign('currentMenu', array('menu' => 'menu1', 'nav' => 'nav5'));

    }

    //首页
    public function index() {
        $ProStatusModel = new Productionstatus();

        $ProStatus = $ProStatusModel->paginate($this->pageTotalItem,false,['query' =>request()->param()]);
        $this->assign('prostatus', $ProStatus);
        $this->assign('pageDiv', $ProStatus->render());
        return $this->fetch();
    }
    //获取条形码
    public function get_barcode($text,$ab) {

        //$barcodes = new barcode($text);
        //$barcodes->createBarCode('png', '');
        barcode_cn('BCGcode128', $text, $ab, 60, 2);
        //Imagepng($drawing,ROOT_BARCODE_IMG.$text.'.png');
        //return;
    }
    //获取二维码
    public function getProStaCodeBar($text, $ab) {
        //barcode_user_code('BCGcode128', $text, 40, 4);
        phpQrCode($text, 4, 1, 1, $ab);
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
            $data['uid'] = session('admin_id');
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

    //订单追踪、生产状态获取
    public function product_status() {
        $proStatusModel = new Productionstatus();
        if ($this->request->isPost()) {
            $pro_data = input('post.');
            $pro_status = $proStatusModel->field('id,status_name,abbreviation,bar_code')->where('id', $pro_data['id'])->find();
            if (empty($pro_status)) {
                //生产状态为空
                return json(['code' => 1002, 'msg' => '没有这个生产状态,请重新扫描', 'data' => '']);
            }
            return json(['code' => 1000, 'msg' => '', 'data' => $pro_status]);
        }

        $this->assign('pro_status', '');
        $this->assign('currentMenu',array('menu'=>'menu1','nav'=>'nav6'));
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
                'OrdNum|OdrId' => $data['OrdNum'],
                'status' => ['in', '0,1,2,4,5']//新订单，生产中，暂停，无工厂，有库存
            );
            $order = $orderModel->where($where)->select();

            if (empty($order)) {
                $BeginTime = date("Y-m-d", time() - 3600 * 24 * 10);
                $EndTime = date("Y-m-d", time());
                $url = 'http://webapi.38420.com/api/Order/QueryOrderByRecNo';
                $post_data = array(
                    'RecNo' => $data['OrdNum'],
                    'BeginTime' => $BeginTime,
                    'EndTime' => $EndTime
                );

                $entsku_order = send_post($url, $post_data);
                $entsku = json_decode($entsku_order, true);
                if (count($entsku) == 1 && isset($entsku[0])) {
                    $where2 = array(
                        'OdrId' => $entsku[0]['OdrId'],
                        'status' => ['in', '0,1,2,4,5']
                    );
                    $order = $orderModel->where($where2)->select();
                    if (empty($order)) {
                        return json(['code' => 1002, 'msg' => '该订单不存在', 'list' => array()]);
                    }
                } else {
                    //没找到订单
                    return json(['code' => 1002, 'msg' => '该订单不存在', 'list' => array()]);
                }
            }
            $order_arr = array();
            $OrderFacModel = new Orderfactory();
            $ProductionstatusModel = new Productionstatus();
            $order_nums = array();
            foreach ($order as $k => $v) {
                $order_id = $v['id'];
                $v['orderFactory'] = $OrderFacModel->where('order_id',$order_id)->relation('userinfo')->select();//关联查找
                $v['productstatus'] = $ProductionstatusModel->field('id,status_name,abbreviation,bar_code')->where('id', $v['ProductionStatusId'])->find();
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
                if (!in_array($v['OrdNum'], $order_nums)) $order_nums[] = $v['OrdNum'];//产能管理
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
            /*if (count($order_nums) > 0) {
                $capacityModel = new Capacity();
                $capacity_data = array();
                foreach ($order_nums as $n_k => $n_v) {
                    $capacity_data[] = array(
                        'user_id' => session('admin_id'),
                        'user_name' => session('admin_name'),
                        'action' => '生产状态',
                        'status' => 1,
                        'order_data' => $n_v,//['OrdNum']
                        'creat_time' => date('Y-m-d H:i:s', time())
                    );
                }
                $capacityModel->savedata($capacity_data);
            }*/
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
        //$update_order = array();
        //$capacityModel = new Capacity();
        $add_order_status = array();
        $statusModel = new Productionstatus();
        $OrderstatusidModel = new Orderstatusid();
        $capacity_data = array();
        foreach ($order as $k => $v) {
            $arr = explode('-', $v);
            //$arr[0]:order_id,$arr[1]:status_id,
            $status_arr = $statusModel->where('id', $arr[1])->find();
            $add_order_status[$k]['order_id'] = $arr[0];//
            $add_order_status[$k]['status_id'] = $arr[1];
            $add_order_status[$k]['status'] = $status_arr['abbreviation'];
            $add_order_status[$k]['add_time'] = date('Y-m-d H:i:s', time());
            //$update_order[$k]['id'] = $arr[0];
            //$update_order[$k]['ProductionStatusId'] = $arr[1];
            //$orderModel->update(['ProductionStatusId' => $update_order[1], ['id' => $update_order[0]]]);
            //$update_order[$k]['addStatusIdTime'] = date('Y-m-d H:i:s', time());
            $order_data = $orderModel->where('id', $arr[0])->find();
            $img_url = explode(',', $order_data['ImgURL']);
            $capacity_data[] = array(
                'user_id' => session('admin_id'),
                'user_name' => session('admin_name'),
                'action' => 1,//1生产状态，2订单指派
                'status' => $status_arr['status_name'],//状态名称
                'assing_fac' => '',//指派工厂
                'order_id' => $arr[0],
                'order_img' => $img_url[0],
                'order_amount' => $order_data['GdsNum'],
                'order_sku' => $order_data['GdsSku'],
                'order_nums' => $order_data['OrdNum'],//['OrdNum']
                'creat_time' => $add_order_status[$k]['add_time']
            );
            unset($status_arr,$img_url,$order_data);
        }

        //$res = $orderModel->isUpdate()->saveAll($update_order);
        $res = $OrderstatusidModel->insertAll($add_order_status);
        //$capacity_res = $capacityModel->insertAll();
        if ($res !== false) {
            $capacityModel = new Capacity();
            $capacityModel->savedata($capacity_data);
            return json(['code' => 1000, 'msg' => '更新成功']);
        } else {
            return json(['code' => 1001, 'msg' => '更新失败，请重试']);
        }
    }
    
}

