<?php

namespace app\admin\controller;

use \app\admin\controller;
use app\admin\model\Order;
use app\admin\model\Product;
use app\admin\model\Orderfactory;
use app\admin\model\Manufacture;//production_list
use app\admin\model\Manufacturelist;//list_manufacture
use app\admin\model\Productionstatus;
use \think\Db;
use app\admin\controller\Excel;

class Checkbill extends Excel{
    protected $pageTotalItem=40;
    public function __construct() {
        parent::__construct();
        $this->assign('currentMenu',array('menu'=>'menu7','nav'=>'nav3'));
    }
    public function index() {

        return $this->fetch();

    }


    //按产品导出所需材料数据
    public function exportdata() {
        parent::excelStart();
        //$rate = '';
        //$rate = input('post.rate');
        //if (!is_numeric($rate) || empty($rate)) return false;
        $fileName = isset($_FILES["file"]) ? $_FILES["file"]["name"] : "";
        $file = isset($_FILES["file"]) ? $_FILES["file"]["tmp_name"] : "";
        $ext = \file::get_ext($fileName);
        /*$isCustoms = strstr($fileName, '运单号码');
        if ($isCustoms == false) {
            return '请导入正确的运单号码文件';
        }*/
        if ($ext == "xls") {
            $reader = \PHPExcel_IOFactory::createReader('Excel5');
        } else {
            $reader = \PHPExcel_IOFactory::createReader('Excel2007');
        }
        unset($fileName);
        unset($isCustoms);

        $PHPExcel = $reader->load($file);
        $sheet = $PHPExcel->getSheet(0); // 读取第一個工作表
        $highestRow = $sheet->getHighestRow(); // 取得总行数
        $highestColumm = $sheet->getHighestColumn(1); // 取得总列数
        $highestColumm = \PHPExcel_Cell::columnIndexFromString($highestColumm); //字母列转换为数字列 如:AA变为27
        $filedArray = array();
        //价格
        $field = array(
            '快递单号' => 'waybill_num',
            '订单号' => 'order_num',
            '实际重量(g)' => 'actual_weight',
            '资费' => 'postage',
            'GdsWeight' => 'GdsWeight',
            'GdsMny' => 'GdsMny'
        );
        for ($col = 0; $col <= $highestColumm; $col++) {
            $value = $sheet->getCellByColumnAndRow($col, 1)->getValue();
            $value = trim($value);
            if(isset($field[$value])) $filedArray[$col]=$field[$value];
        }

        $res = array();
        $last_total = array('waybill_num' => '', 'order_num' => '', 'actual_weight' => 0, 'GdsWeight' => 0, 'postage' => 0, 'GdsMny' => 0, 'weight_diff' => '', 'money_diff' => ''); 
        for ($row = 2; $row <= $highestRow; $row++) { //列数是以第0列开始
            $data=array();
            foreach ($filedArray as $key=>$value){
                $data[$value]=trim($sheet->getCellByColumnAndRow($key, $row)->getValue());
            }

            if (!empty($data['waybill_num'])) {
                if (!empty($data['actual_weight'])) $last_total['actual_weight'] = bcadd($last_total['actual_weight'], $data['actual_weight'], 2);
                if (!empty($data['GdsWeight'])) $last_total['GdsWeight'] = bcadd($last_total['GdsWeight'], $data['GdsWeight'], 2);
                if (!empty($data['postage'])) $last_total['postage'] = bcadd($last_total['postage'], $data['postage'], 2);
                if (!empty($data['GdsMny'])) $last_total['GdsMny'] = bcadd($last_total['GdsMny'], $data['GdsMny'], 2);

                if (!empty($data['actual_weight']) && !empty($data['GdsWeight'])) {
                    $data['weight_diff'] = bcsub($data['actual_weight'], $data['GdsWeight'], 2);
                } else {
                    $data['weight_diff'] = '无';
                }
                if (!empty($data['postage']) && !empty($data['GdsMny'])) {
                    $data['money_diff'] = bcsub($data['postage'], $data['GdsMny'], 2);
                } else {
                    $data['money_diff'] = '无';
                }
                $res[] = $data;
                if ($row == $highestRow) {
                    $last_total['weight_diff'] = bcsub($last_total['actual_weight'], $last_total['GdsWeight'], 2);
                    $last_total['money_diff'] = bcsub($last_total['postage'], $last_total['GdsMny'], 2);
                    $res[] = $last_total;
                }
            }
            unset($data);
        }

        //$this->set_checkbill($res);
        $PHPExcelout = new \PHPExcel();
        $PHPExcelout->setActiveSheetIndex(0)
            ->setCellValue("A1", "快递单号")
            ->setCellValue("B1", "订单号")
            ->setCellValue("C1", "实际重量(燕文/g)")
            ->setCellValue("D1", "GdsWeight(后台/g)")
            ->setCellValue("E1", "重量差值(g)")
            ->setCellValue("F1", "资费(燕文)")
            ->setCellValue("G1", "GdsMny(后台)")
            ->setCellValue("H1", "价格差值");

        $PHPExcelout->getActiveSheet()->getColumnDimension('A')->setWidth(25);
        $PHPExcelout->getActiveSheet()->getColumnDimension('B')->setWidth(35);
        $PHPExcelout->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $PHPExcelout->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $PHPExcelout->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $PHPExcelout->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $PHPExcelout->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $PHPExcelout->getActiveSheet()->getColumnDimension('H')->setWidth(20);

        $PHPExcelout->getActiveSheet()->getStyle('A')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcelout->getActiveSheet()->getStyle('B')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcelout->getActiveSheet()->getStyle('C')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcelout->getActiveSheet()->getStyle('D')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcelout->getActiveSheet()->getStyle('E')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcelout->getActiveSheet()->getStyle('F')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcelout->getActiveSheet()->getStyle('G')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcelout->getActiveSheet()->getStyle('H')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
       
        foreach ($res as $k => $v) {
            $ks = $k + 2;
            $PHPExcelout->setActiveSheetIndex(0)
                ->setCellValue("A" . $ks, $v['waybill_num'] . "\t")
                ->setCellValue("B" . $ks, $v['order_num'])
                ->setCellValue("C" . $ks, $v['actual_weight'])
                ->setCellValue("D" . $ks, $v['GdsWeight'])
                ->setCellValue("E" . $ks, $v['weight_diff'])
                ->setCellValue("F" . $ks, $v['postage'])
                ->setCellValue("G" . $ks, $v['GdsMny'])
                ->setCellValue("H" . $ks, $v['money_diff'] . "\t");
        }

        $PHPExcelout->setActiveSheetIndex(0);                   //设置sheet的起始位置
        //$objWriter = \PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel5');   //Excel2003通过PHPExcel_IOFactory的写函数将上面数据写出来
        $PHPWriter = \PHPExcel_IOFactory::createWriter($PHPExcelout,"Excel2007"); //Excel2007
        //header('Content-Disposition: attachment;filename="用户信息.xlsx"');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition:inline;filename=燕文物流单.xlsx");
        //$savefiles = ROOT_SAVE_FILES . '运单.xlsx';
        $PHPWriter->save('php://output'); //表示在$path路径下面生成demo.xlsx文件
        exit;
    }

    public function set_checkbill($res) {
        $PHPExcel = new \PHPExcel();
        $PHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A1", "快递单号")
            ->setCellValue("B1", "订单号")
            ->setCellValue("C1", "实际重量(燕文/g)")
            ->setCellValue("D1", "GdsWeight(后台/g)")
            ->setCellValue("E1", "重量差值(g)")
            ->setCellValue("F1", "资费(燕文)")
            ->setCellValue("G1", "GdsMny(后台)")
            ->setCellValue("H1", "价格差值");

        $PHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
        $PHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(35);
        $PHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $PHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $PHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $PHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $PHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $PHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);

        $PHPExcel->getActiveSheet()->getStyle('A')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('B')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('C')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('D')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('E')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('F')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('G')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('H')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
       
        foreach ($res as $k => $v) {
            $ks = $k + 2;
            $PHPExcel->setActiveSheetIndex(0)
                ->setCellValue("A" . $ks, $v['waybill_num'] . "\t")
                ->setCellValue("B" . $ks, $v['order_num'])
                ->setCellValue("C" . $ks, $v['actual_weight'])
                ->setCellValue("D" . $ks, $v['GdsWeight'])
                ->setCellValue("E" . $ks, $v['weight_diff'])
                ->setCellValue("F" . $ks, $v['postage'])
                ->setCellValue("G" . $ks, $v['GdsMny'])
                ->setCellValue("H" . $ks, $v['money_diff'] . "\t");
        }

        $PHPExcel->setActiveSheetIndex(0);                   //设置sheet的起始位置
        //$objWriter = \PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel5');   //Excel2003通过PHPExcel_IOFactory的写函数将上面数据写出来
        $PHPWriter = \PHPExcel_IOFactory::createWriter($PHPExcel,"Excel2007"); //Excel2007
        //header('Content-Disposition: attachment;filename="用户信息.xlsx"');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition:inline;filename=燕文物流单.xlsx");
        //$savefiles = ROOT_SAVE_FILES . '运单.xlsx';
        $PHPWriter->save('php://output'); //表示在$path路径下面生成demo.xlsx文件
        exit;
    }


}