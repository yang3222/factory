<?php

namespace app\admin\controller;

use \app\admin\controller;
use app\admin\model\Order;
use app\admin\model\Orderfactory;
use app\admin\model\Productionstatus;
use \think\Db;
use app\admin\controller\Excel;
use app\admin\model\Capacity as CapacityModel;

class Capacity extends Excel{
    protected $pageTotalItem=40;
    protected $action_arr = [1 => '生产状态', 2 => '订单指派'];
    public function __construct() {
        parent::__construct();
        $this->assign('currentMenu',array('menu'=>'menu12','nav'=>'nav0'));
    }
    public function index() {
        $capacitymodel = new CapacityModel();
        $search = input('request.search', '', 'trim');
        $capacity_type = input('request.capacity_type');
        if ($capacity_type == null) $capacity_type = 0;
        if ($capacity_type != 0) {
            $where = array(
                'action' => $capacity_type
            );
        }
        if ($search != '') {
            $where = array(
                'user_name' => ['like', "%{$search}%"]
            );
        }
        $startData=session('capa_start_time')==""?date('Y-m-d',strtotime('-6 day')):session('capa_start_time');
        $endData=session('capa_end_time')==""?date('Y-m-d'):session('capa_end_time');
        $start_time = input('request.start_time');
        $end_time = input('request.end_time');
        if(!empty($start_time))$startData=input('request.start_time');
        if(!empty($end_time))$endData=input('request.end_time');
        //设置时间类型保存到session
        session('capa_time_type',input('request.sdate') != 'custom'? '1':'2');

        if(session('capa_time_type') == '1'){
            $times = strtotime(date('Y-m-d'))-strtotime($endData);
            $startData = date('Y-m-d',strtotime($startData)+$times);
            $endData =date('Y-m-d',strtotime($endData)+$times);
        }
        session('capa_start_time',$startData);
        session('capa_end_time',$endData);
        $where['creat_time'] = array(
            'between', [$startData . ' 00:00:00', $endData . ' 23:59:59']
        );
        $capacitymodel->where($where);
        $res = $capacitymodel->order('creat_time desc')->paginate($this->pageTotalItem,false,['query' =>request()->param()]);

        $this->assign('pageDiv', $res->render());
        $this->assign('action_arr', $this->action_arr);
        $this->assign('date',array('start_time' => $startData, 'end_time' => $endData));
        $this->assign('list',$res);
        $this->assign('capacity_type',$capacity_type);

        return $this->fetch();
    }

    //导出excel
    public function exports() {
        parent::excelStart();
        $excelCapacityModel = new CapacityModel();
        $ids = input('get.ids');
        $start_time = input('get.start_time');
        $end_time = input('get.end_time');
        $search = input('get.search', '', 'trim');
        $action = input('get.capacity_type');
        //$capacity_type = input('get.capacity_type');
        if ($ids == 'all') {
            $data = $excelCapacityModel->select();
        } else {
            if ($ids != '') {
                $where['id'] = ['in', $ids];
            }
            if ($action != 0) {
                $where['action'] = $action;
            }
            $where['user_name'] = ['like', "%{$search}%"];
            //$where['capacity_type'] = $capacity_type;
            $where['creat_time'] = ['between', [$start_time . " 00:00:00", $end_time . " 23:59:59"]];
            $where['creat_time'] = ['between', [$start_time . " 00:00:00", $end_time . " 23:59:59"]];
            $data = $excelCapacityModel->where($where)->select();
        }
        $PHPExcel = new \PHPExcel();
        $PHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A1", "操作用户")
            ->setCellValue("B1", "订单号")
            ->setCellValue("C1", "SKU")
            ->setCellValue("D1", "数量")
            ->setCellValue("E1", "行为")
            ->setCellValue("F1", "操作")
            ->setCellValue("G1", "操作时间");
        $PHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
        $PHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(23);
        $PHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
        $PHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(8);
        $PHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(14);
        $PHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(50);
        $PHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(18);
        $actions = $this->action_arr;
        foreach ($data as $k => $v) {
            $ks = $k + 2;
            $PHPExcel->setActiveSheetIndex(0)
                ->setCellValue("A" . $ks, $v['user_name'])
                ->setCellValue("B" . $ks, $v['order_nums']."\t")
                ->setCellValue("C" . $ks, $v['order_sku'])
                ->setCellValue("D" . $ks, $v['order_amount'])
                ->setCellValue("E" . $ks, $actions[$v['action']])
                ->setCellValue("F" . $ks, $v['assing_fac'] . $v['status'])
                ->setCellValue("G" . $ks, $v['creat_time']);
        }

        $PHPExcel->setActiveSheetIndex(0);                   //设置sheet的起始位置
        //$objWriter = \PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel5');   //Excel2003通过PHPExcel_IOFactory的写函数将上面数据写出来
        $PHPWriter = \PHPExcel_IOFactory::createWriter($PHPExcel,"Excel2007"); //Excel2007
        //header('Content-Disposition: attachment;filename="用户信息.xlsx"');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition:inline;filename=生产-派单.xlsx");
        //$savefiles = ROOT_SAVE_FILES . '运单.xlsx';
        $PHPWriter->save('php://output'); //表示在$path路径下面生成demo.xlsx文件
        exit;
    }
}