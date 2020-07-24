<?php

namespace app\admin\controller;

use \app\admin\controller;
use app\admin\model\Order;
use app\admin\model\Product;
use app\admin\model\Material;
use app\admin\model\Orderfactory;
use app\admin\model\Manufacture;//production_list
use app\admin\model\Manufacturelist;//list_manufacture
use app\admin\model\Productionstatus;
use \think\Db;
use app\admin\controller\Excel;

class Ordermaterial extends Excel{
    protected $pageTotalItem=40;
    public function __construct() {
        parent::__construct();
        $this->assign('currentMenu',array('menu'=>'menu7','nav'=>'nav2'));
    }
    public function index() {
        $signtype = array(2, 4, 5, 8);
        $status = input('request.signtype');
        if (!empty($status)) $signtype = explode(',', $status);
        $searchproduct = '';
        $rep_id = input('request.product_id');
        if (!empty($rep_id)) $searchproduct = input('request.product_id');
        $startData=session('manu_start_time')==""?date('Y-m-d',strtotime('-6 day')):session('manu_start_time');
        $endData=session('manu_end_time')==""?date('Y-m-d'):session('manu_end_time');
        $start_time = input('request.start_time');
        $end_time = input('request.end_time');
        if(!empty($start_time))$startData=input('request.start_time');
        if(!empty($end_time))$endData=input('request.end_time');
        //设置时间类型保存到session
        session('time_type',input('request.sdate') != 'custom'? '1':'2');
        if(session('time_type') == '1'){
            $times = strtotime(date('Y-m-d'))-strtotime($endData);
            $startData = date('Y-m-d',strtotime($startData)+$times);
            $endData =date('Y-m-d',strtotime($endData)+$times);
        }

        session('manu_start_time',$startData);
        session('manu_end_time',$endData);
        $orderModel = new Order();
        $timeArr = array('GetTimer','GetTimer','pro_time','library_time','SignTimer','SignTimer','SignTimer','GetTimer','GetTimer','GetTimer');//7,8状态的增加
        //0,1新订单,2生产中,3已出库,4暂停,5签收,6取消,7未设置工厂,8有库存
        $time_name = 'AmzTimer';
        //$searchproduct = input('request.product_id');
        $order = 'id desc';
        $db = Db::view('new_order a','*');
        //$factorysearchboo=false;
        $type_status = array();//order,状态
        $sign_status = array();//工厂sign状态
        foreach ($signtype as $type_key => $type_val) {
            if ($type_val == 4 || $type_val == 5 || $type_val == 6 || $type_val == 7 || $type_val == 8) {
                $type_status[] = $type_val - 3;
            } else {
                $type_status[] = 0;
                $sign_status[] = $type_val - 1;
            }
        }
        $type_status = array_unique($type_status);
        $where = array(
            'status'=>['in',$type_status],
            'sign'=>['in',[0,1,2]],
        );
        //已签收的判断
        if (in_array(5, $signtype)) {
            $where['endboo'] = ['in',[0,1]];
        } else {
            $where['endboo'] = ['in',[0]];
        }

        if (count($sign_status) > 0) {
            $arr = array_merge([0,1], $sign_status);
            $arr = array_unique($arr);
            $where['sign'] = ['in', $arr];
            unset($arr);
        }

        $db->view('order_factory b','order_id','b.order_id=a.id')->where($where)->group('a.id');
        //$db->view('ink_product pr','product_id,product_num,name,smallimg,Catalog,id as pr_id', 'pr.product_id = a.product_id');
        //$db->view('ink_production_list prl', 'name as prl_name', 'prl.product_id = pr.pr_id');
        //根据时间进行搜索
        if(!empty($time_name)){
            $db->where([$time_name=>['between',[$startData." 00:00:00",$endData." 23:59:59"]]]);
        }
        //根据产品进行搜索
        if(!empty($searchproduct)){
            $db->where('a.product_id','in',explode(",",$searchproduct));
        }
        $db->order($order);
        $res = array();
        $data = $db->paginate($this->pageTotalItem,false,['query' =>request()->param()]);
        $manufacturelistModel = new Manufacturelist();//list_manufacture
        $productModel = new Product();
        foreach ($data as $key => $value) {
            $factory_arr = $productModel->relation('productfactroyHas,manufacture')->where(['product_id' => $value['product_id']])->find();
            if (!empty($factory_arr['manufacture'])) {
                $list_id = $factory_arr['manufacture']['id'];
                $manumaterial = $manufacturelistModel->relation('material')->where('list_id', $list_id)->select();
                foreach ($manumaterial as $mkey => &$mvalue) {
                    $dosages = bcmul(floatval($mvalue['dosage']), $value['GdsNum'], 6);
                    $mvalue['dosage'] = bcmul($dosages, $mvalue['unit_conversion'], 6);
                }
                $value['manumaterial'] = $manumaterial;
            } else {
                $list_id = '';
                $value['manumaterial'] = array();
            }
            $value['product_name'] = $factory_arr['name'];
            $value['product_num'] = $factory_arr['product_num'];
            unset($manumaterial,$list_id,$factory_arr);
            $res[] = $value;
        }

        $this->assign('list', $res);
        $this->assign('date',array('start_time' => $startData, 'end_time' => $endData));
        $this->assign('pageDiv', $data->render());
        $this->assign('lastpage', $data->lastPage());
        $this->assign('sign',$signtype);
        $this->assign('searchproduct',$searchproduct);
        $this->assign('currentpage', $data->currentPage());
        return $this->fetch();

    }
    //按产品分类计算
    public function productmaterial() {
        $signtype = array(2, 4, 5, 8);
        $status = input('request.signtype');
        if (!empty($status)) $signtype = explode(',', $status);
        $searchproduct = input('request.product_id');
        $startData=session('manu_start_time')==""?date('Y-m-d',strtotime('-6 day')):session('manu_start_time');
        $endData=session('manu_end_time')==""?date('Y-m-d'):session('manu_end_time');
        $start_time = input('request.start_time');
        $end_time = input('request.end_time');
        if(!empty($start_time))$startData=input('request.start_time');
        if(!empty($end_time))$endData=input('request.end_time');
        //设置时间类型保存到session
        session('time_type',input('request.sdate') != 'custom'? '1':'2');
        if(session('time_type') == '1'){
            $times = strtotime(date('Y-m-d'))-strtotime($endData);
            $startData = date('Y-m-d',strtotime($startData)+$times);
            $endData =date('Y-m-d',strtotime($endData)+$times);
        }

        session('manu_start_time',$startData);
        session('manu_end_time',$endData);
        $timeArr = array('GetTimer','GetTimer','pro_time','library_time','SignTimer','SignTimer','SignTimer','GetTimer','GetTimer','GetTimer');//7,8状态的增加
        //0,1新订单,2生产中,3已出库,4暂停,5签收,6取消,7未设置工厂,8有库存
        $time_name = $timeArr[0];
        $order = 'id desc';
        $data_cache_key = implode('-', $signtype) . $searchproduct . $startData . $endData . $time_name;
        $datacache = cache($data_cache_key);
        $db = Db::view('new_order a','*');
        $type_status = array();//order,状态
        $sign_status = array();//工厂sign状态
        foreach ($signtype as $type_key => $type_val) {
            if ($type_val == 4 || $type_val == 5 || $type_val == 6 || $type_val == 7 || $type_val == 8) {
                $type_status[] = $type_val - 3;
            } else {
                $type_status[] = 0;
                $sign_status[] = $type_val - 1;
            }
        }
        $type_status = array_unique($type_status);
        $where = array(
            'status'=>['in',$type_status],
            'sign'=>['in',[0,1,2]],
        );
        //已签收的判断
        if (in_array(5, $signtype)) {
            $where['endboo'] = ['in',[0,1]];
        } else {
            $where['endboo'] = ['in',[0]];
        }

        if (count($sign_status) > 0) {
            $arr = array_merge([0,1], $sign_status);
            $arr = array_unique($arr);
            $where['sign'] = ['in', $arr];
            unset($arr);
        }

        $db->view('order_factory b','order_id','b.order_id=a.id')->where($where)->group('a.id');
        //根据时间进行搜索
        if (!empty($time_name)) {
            $db->where([$time_name=>['between',[$startData." 00:00:00",$endData." 23:59:59"]]]);
        }
        //根据产品进行搜索
        if (!empty($searchproduct)) {
            $db->where('a.product_id', 'in', explode(",", $searchproduct));
        }
        $db->order($order);
        $res = array();
        $data = $db->select();

        $options = array(
            //'prefix' => '',//前缀
            'expire' => 86400,//缓存一天
        );
        cache($data_cache_key, $data, $options);

        $manufacturelistModel = new Manufacturelist();//list_manufacture
        $productModel = new Product();
        $productData = '';//cache('productdata-admin');//产品的缓存
        //cache('productdata-admin');
        if (empty($productData)) {
            $productm = $productModel->relation('productfactroyHas,manufacture')->select();
            $product_arr = array();
            foreach ($productm as $pdk => $pdv) {
                $product_arr[$pdv['product_id']] = $pdv;
            }
            $productData = $product_arr;
            cache('productdata-admin',$product_arr);
        }

        $manumaterialData = '';//cache('manumaterial-admin');//材料的缓存
        if (empty($manumaterialData)) {
            $manumaterialm = $manufacturelistModel->relation('material')->select();
            $manumaterial_arr = array();
            foreach ($manumaterialm as $mtdk => $mtdv) {
                $manumaterial_arr[$mtdv['list_id']][] = $mtdv;
            }
            $manumaterialData = $manumaterial_arr;
            cache('manumaterial-admin', $manumaterial_arr);
        }

        foreach ($data as $key => $value) {
            if (isset($productData[$value['product_id']])) {
                //判断产品id是否存在
                $factory_arr = $productData[$value['product_id']];//$productModel->relation('productfactroyHas,manufacture')->where(['product_id' => $value['product_id']])->find();
            } else {
                continue;
            }
            if (!empty($factory_arr['manufacture'])) {
                $list_id = $factory_arr['manufacture']['id'];
                if (isset($manumaterialData[$list_id])) {
                    //判断生产单
                    $manumaterial = $manumaterialData[$list_id];//$manufacturelistModel->relation('material')->where('list_id', $list_id)->select();
                } else {
                    continue;
                }
                $value['manumaterial'] = $manumaterial;
                $value['product_name'] = $factory_arr['name'];
                $value['product_num'] = $factory_arr['product_num'];

                if (isset($res[$value['product_id'] . '_' . $value['SpecName']])) {
                    $res[$value['product_id'] . '_' . $value['SpecName']]['GdsNum'] = bcadd($res[$value['product_id'] . '_' . $value['SpecName']]['GdsNum'], $value['GdsNum']);
                } else {
                    $res[$value['product_id'] . '_' . $value['SpecName']]['product_id'] = $value['product_id'];
                    $res[$value['product_id'] . '_' . $value['SpecName']]['GdsNum'] = $value['GdsNum'];
                    $res[$value['product_id'] . '_' . $value['SpecName']]['product_name'] = $value['product_name'];
                    $res[$value['product_id'] . '_' . $value['SpecName']]['SpecName'] = $value['SpecName'];
                    $res[$value['product_id'] . '_' . $value['SpecName']]['product_num'] = $value['product_num'];
                    $res[$value['product_id'] . '_' . $value['SpecName']]['ImgURL'] = $value['ImgURL'];
                    //$res[$value['product_id'] . '_' . $value['SpecName']]['manumaterial'] = $value['manumaterial'];
                    foreach ($value['manumaterial'] as $mak => $mav) {
                        $res[$value['product_id'] . '_' . $value['SpecName']]['manumaterial'][$mak] = array(
                            'id' => $mav['id'],
                            'list_id' => $mav['list_id'],
                            'material_id' => $mav['material_id'],
                            'dosage' => $mav['dosage'],
                            'print_size' => $mav['print_size'],
                            'loss' => $mav['loss'],
                            'type' => $mav['type'],
                            'unit_conversion' => $mav['unit_conversion'],
                            'create_time' => $mav['create_time'],
                            'delete_time' => $mav['delete_time'],
                            'material' => $mav['material']
                        );
                    }
                    $res[$value['product_id'] . '_' . $value['SpecName']]['sku_code'] = $factory_arr['manufacture']['sku_code'];
                }
                unset($manumaterial,$list_id,$factory_arr);
            }
        }

        //$lists = array();
        foreach ($res as $resk => $resv) {
            foreach ($resv['manumaterial'] as $resvk => $resvv) {
                //计算每个产品对应材料的用量，用量*产品数量*单位换算
                $dosages = bcmul(floatval($resvv['dosage']), $resv['GdsNum'], 6);
                $res[$resk]['manumaterial'][$resvk]['dosages'] = bcmul(floatval($dosages), $resvv['unit_conversion'], 4);
                unset($resvv);
            }
        }
        cache('admin_productmaterial_cache', null);
        cache('admin_productmaterial_cache', $res);
        $this->assign('list', $res);
        $this->assign('date',array('start_time' => $startData, 'end_time' => $endData));
        $this->assign('sign',$signtype);
        $this->assign('searchproduct',$searchproduct);
        //print_r($res);
        return $this->fetch('ordermaterial/product_material');
    }

    //批量计算材料
    public function materialReport($signtype='') {
        $orderModel = new Order();
        $ids = input('request.ids');
        $startData = session('manu_start_time') == "" ? date('Y-m-d', strtotime('-6 day')) : session('manu_start_time');
        $endData = session('manu_end_time') == "" ? date('Y-m-d') : session('manu_end_time');
        $start_time = input('request.start_time');
        $end_time = input('request.end_time');
        if (!empty($start_time)) $startData = input('request.start_time');
        if (!empty($end_time)) $endData = input('request.end_time');
        if (session('time_type') == '1') {
            $times = strtotime(date('Y-m-d')) - strtotime($endData);
            $startData = date('Y-m-d', strtotime($startData) + $times);
            $endData = date('Y-m-d', strtotime($endData) + $times);
        }

        session('manu_start_time', $startData);
        session('manu_end_time', $endData);
        if (!empty($ids)) {
            $ids_where = array(
                'id' => ['in', $ids]
            );
            $data = $orderModel->where($ids_where)->select();
        } else {
            $timeArr = array('GetTimer', 'GetTimer', 'pro_time', 'library_time', 'SignTimer', 'SignTimer', 'SignTimer', 'GetTimer', 'GetTimer', 'GetTimer');//7,8状态的增加
            $time_name = $timeArr[0];
            $order = 'id desc';
            $db = Db::view('new_order a', '*');
            //$factorysearchboo=false;

            if (!empty($signtype)) {
                $time_name = $timeArr[$signtype];
                if ($signtype == 4 || $signtype == 5 || $signtype == 6 || $signtype == 7 || $signtype == 8) {
                    if ($signtype == 8) {
                        $where = array(
                            'status' => ['=', '5'],
                            'sign' => ['in', [0, 1]],
                            'endboo' => '0',
                        );
                        $db->view('order_factory b', 'order_id', 'b.order_id=a.id')->where($where)->group('a.id');
                    } else {
                        //暂停，签收，取消
                        $db->view('order_factory b', 'order_id', 'b.order_id=a.id')->where('status', '=', $signtype - 3);
                    }
                } else {
                    $type = $signtype - 1;
                    if ($type == 0) $order = 'Urgent desc,id desc';
                    //sign: 新订单0，生产中1，已出库2
                    $where = array(
                        'status' => ['=', '0'],
                        'sign' => $type,
                        'endboo' => '0',
                        $time_name => ['between', [$startData . " 00:00:00", $endData . " 23:59:59"]]
                    );
                    $db->view('order_factory b', 'order_id', 'b.order_id=a.id')->where($where)->group('a.id');
                }
            }
            if (!empty($time_name)) {
                $db->where([$time_name => ['between', [$startData . " 00:00:00", $endData . " 23:59:59"]]]);
            }
            $db->order($order);
            //$OrderFacModel = new Orderfactory();
            //$proStatusModel = new Productionstatus();
            $data = $db->select();
        }
        $res = array();
        $manufacturelistModel = new Manufacturelist();//list_manufacture
        //$materialModel = new Material();
        $productModel = new Product();
        $product_arr = $productModel->relation('productfactroyHas,manufacture')->select();
        $manufacturelist = $manufacturelistModel->relation('material')->select();
        $pro_arr = array();
        $manufac_arr = array();
        foreach ($product_arr as $it => $va) {
            $pro_arr[$va['product_id']] = $va;
        }
        foreach ($manufacturelist as $mfk => $mfv) {
            $manufac_arr[$mfv['list_id']][] = $mfv;
        }
        //session('promanufac', $facarr);
        //$manumaterial = array();
        $order_id_num = array();
        foreach ($data as $key => $value) {
            //$factory_arr = array();
            if (isset($pro_arr[$value['product_id']])) $factory_arr = $pro_arr[$value['product_id']];//$productModel->relation('productfactroyHas,manufacture')->where(['product_id' => $value['product_id']])->select();
            if (!empty($factory_arr['manufacture'])) {
                $list_id = $factory_arr['manufacture']['id'];
                $manumaterial = $manufacturelistModel->relation('material')->where('list_id', $list_id)->select();
                foreach ($manumaterial as $mkey => $mvalue) {
                    $mvalue['dosage'] = bcmul(floatval($mvalue['dosage']), $value['GdsNum'], 4);

                    if (isset($res[$mvalue['id']])) {
                        $res[$mvalue['id']]['dosage'] = bcadd($res[$mvalue['id']]['dosage'], $mvalue['dosage'], 4);
                    } else {
                        $res[$mvalue['id']] = $mvalue;
                    }
                    $res[$mvalue['id']]['order_id'] = $value['id'];
                }
            } else {
                $order_id_num[$value['id']] = $value['OrdNum'];//order_id=>OrdNum
            }
            unset($manumaterial,$list_id,$factory_arr,$value);
        }

        $this->assign('list', $res);
        $this->assign('date',array('start_time' => $startData, 'end_time' => $endData));
        return $this->fetch();
    }


    //弹出页面
    public function openwindows(){

        $this->view->engine->layout(false);

        $search=input('post.search','','trim');

        $model = new Product();

        $modeldata = $model->order('id', 'desc');

        if(!empty($search)){

            $where=array(

                'name'=>['like',"%{$search}%"],

                'product_id'=>"{$search}",

            );

            $modeldata->whereor($where);

        }

        $data = $modeldata->select();

        $this->assign('list',$data);

        return $this->fetch();

    }

    //按产品导出所需材料数据
    public function exportdata() {
        parent::excelStart();
        $data = cache('admin_productmaterial_cache');
        if (empty($data)) {
            exit;
        }
        $PHPExcel = new \PHPExcel();
        $PHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A1", "产品ID")
            ->setCellValue("B1", "产品名称")
            ->setCellValue("C1", "产品SKU")
            ->setCellValue("D1", "产品数量")
            ->setCellValue("E1", "产品型号")
            ->setCellValue("F1", "财务物料编码")
            ->setCellValue("G1", "材料名称")
            ->setCellValue("H1", "材料用量")
            ->setCellValue("I1", "单位")
            ->setCellValue("J1", "单个用量")
        ;
        $PHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
        $PHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(13);
        $PHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
        $PHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(11);
        $PHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $PHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(16);
        $PHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(35);
        $PHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(13);
        $PHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(12);
        $PHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(12);

        $PHPExcel->getActiveSheet()->getStyle('A')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('B')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('C')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('D')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('E')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('F')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('G')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('H')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('I')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('J')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        //$PHPExcel->getActiveSheet()->getStyle('')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE);
        $res_data = array();
        foreach ($data as $item => $value) {
            foreach ($value['manumaterial'] as $kk => $vv) {
                $res_data[] = array(
                    'product_id' => $value['product_id'],
                    'product_name' => $value['product_name'],
                    'sku_code' => $value['sku_code'],
                    'GdsNum' => $value['GdsNum'],
                    'SpecName' => $value['SpecName'],
                    'material_finance_num' => $vv['material']['finance_num'],
                    'material_name' => $vv['material']['name'],
                    'material_dosage' => $vv['dosages'],
                    'material_company' => $vv['material']['company']
                );
            }
        }
        foreach ($res_data as $k => $v) {
            $dgyl = bcdiv($v['material_dosage'], $v['GdsNum'], 4);
            //$manuat = '';
            $ks = $k + 2;
            //$manuat = $manuat . '[' . $val['material']['finance_num'] .']' .$val['material']['name'] . ': ' . $val['dosage'] . $val['material']['company'] . "\n";
            $PHPExcel->setActiveSheetIndex(0)
                ->setCellValue("A" . $ks, $v['product_id'] . "\t")
                ->setCellValue("B" . $ks, $v['product_name'])
                ->setCellValue("C" . $ks, $v['sku_code'])
                ->setCellValue("D" . $ks, (float)$v['GdsNum'])
                ->setCellValue("E" . $ks, $v['SpecName'])
                ->setCellValue("F" . $ks, $v['material_finance_num'])
                ->setCellValue("G" . $ks, $v['material_name'])
                ->setCellValue("H" . $ks, (float)$v['material_dosage'])
                ->setCellValue("I" . $ks, $v['material_company'])
                ->setCellValue("J" . $ks, (float)$dgyl);
            //$rowHeight = 15;
            //if (count($v['manumaterial']) > 0) $rowHeight = count($v['manumaterial']) * 15;
            //$PHPExcel->getActiveSheet()->getRowDimension($ks)->setRowHeight($rowHeight);
        }

        $PHPExcel->setActiveSheetIndex(0);                   //设置sheet的起始位置
        //$objWriter = \PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel5');   //Excel2003通过PHPExcel_IOFactory的写函数将上面数据写出来
        $PHPWriter = \PHPExcel_IOFactory::createWriter($PHPExcel,"Excel2007"); //Excel2007
        //header('Content-Disposition: attachment;filename="用户信息.xlsx"');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition:inline;filename=订单材料报表.xlsx");
        //$savefiles = ROOT_SAVE_FILES . '运单.xlsx';
        $PHPWriter->save('php://output'); //表示在$path路径下面生成demo.xlsx文件
        exit;
    }


}