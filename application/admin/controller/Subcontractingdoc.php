<?php
namespace app\admin\controller;

use \think\Db;
use app\admin\model\Outproorder;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description
 *
 * @author
 */
class Subcontractingdoc extends Excel {
    protected $pageTotalItem=40;
    public function __construct() {
        parent::__construct();
        $this->assign('currentMenu',array('menu'=>'menu8','nav'=>'nav10'));
    }
    //委外加工单
    public function index(){

        $search = input('post.search', '', 'trim');
        $where = array();
        if ($search != '') {
            $where = array(
                'product_id' => $search,
                'product_name' => ['like', "%{$search}%"],
                'product_sku' => ['like', "%{$search}%"]
            );
        }
        $outproorderModel = new Outproorder();
        $res = $outproorderModel->whereOr($where)->paginate($this->pageTotalItem,false,['query' =>request()->param()]);
        $this->assign('pageDiv', $res->render());
        $this->assign('list',$res);
        return $this->fetch();
    }

    //添加数据
    public function addSub(){
        if ($this->request->isPost()) {
            $data = input('post.');
            $outproorderModel = new Outproorder();
            $where = array(
                'product_id' => $data['product_id'],
                'product_sku' => $data['product_sku'],
            );
            $res = $outproorderModel->where($where)->find();
            if (!empty($res)) {
                return json(['msg'=>'数据已经存在', 'code'=>1001]);
            } else {
                $data['create_time'] = date('Y-m-d H:m:s', time());
                $add_res = $outproorderModel->insert($data);
                return json(['code' => $add_res ? 1000 : 1001, 'msg' => $add_res ? '添加成功' : '添加失败']);
            }
        }

        return $this->fetch();
    }

    //编辑数据
    public function editSub($id = ''){

        if ($this->request->isPost()) {
            $data = input('post.');
            $outproorderModel = new Outproorder();
            $res = $outproorderModel->update($data);

            return json(['code' => $res ? 1000 : 1001, 'msg' => $res ? '修改成功' : '修改失败']);
        }

        $outproorderModel = new Outproorder();
        $res = $outproorderModel->where('id', $id)->find();
        $this->assign('data',$res);
        return $this->fetch();
    }

    //删除数据
    public function deletesub() {
        if (!$this->request->isPost()) {
            return json(['code' => 1001, 'msg' => '此为post接口']);
        }
        $ids = input('post.id/a');
        if (count($ids) <= 0) {
            return json(['code' => 1001, 'msg' => '参数为空']);
        }
        $mod = new Outproorder();
        $res = $mod->destroy($ids);
        return json(['code' => $res ? 1000 : 1001, 'msg' => $res ? '删除成功' : '删除失败']);
    }

    //导入数据
    public function importsub() {
        parent::excelStart();

        $fileName = isset($_FILES["file"]) ? $_FILES["file"]["name"] : "";
        $file = isset($_FILES["file"]) ? $_FILES["file"]["tmp_name"] : "";
        $ext = \file::get_ext($fileName);

        if ($ext == "xls") {
            $reader = \PHPExcel_IOFactory::createReader('Excel5');
        } else {
            $reader = \PHPExcel_IOFactory::createReader('Excel2007');
        }
        unset($fileName);
        $outproorderModel = new Outproorder();
        $PHPExcel = $reader->load($file);
        $sheet = $PHPExcel->getSheet(0); // 读取第一個工作表
        $highestRow = $sheet->getHighestRow(); // 取得总行数
        $highestColumm = $sheet->getHighestColumn(1); // 取得总列数
        $highestColumm = \PHPExcel_Cell::columnIndexFromString($highestColumm); //字母列转换为数字列 如:AA变为27
        $filedArray = array();
        //价格
        $field = array(
            '产品ID' => 'product_id',
            '产品名称' => 'product_name',
            '产品SKU' => 'product_sku',
            '产品型号' => 'product_size',
            '裁片物料编码' => 'cutting_material_code',
            '裁片名称' => 'slice_name',
            '原料物料编码' => 'material_code',
            '原料名称' => 'name_of_raw_material',
            '片数' => 'number_of_slices',
            '用量/码' => 'dosage',
            '片/套' => 'slice',
            '裁片单位' => 'cutting_unit',
            '总用量（片/套*用量）' => 'total_dosage',
        );
        for ($col = 0; $col <= $highestColumm; $col++) {
            $value = $sheet->getCellByColumnAndRow($col, 1)->getValue();
            $value = trim($value);
            if(isset($field[$value])) {
                $filedArray[$col]=$field[$value];
            }
        }

        $newdata=array();

        for ($row = 2; $row <= $highestRow; $row++) { //列数是以第0列开始
            $data=array();

            foreach ($filedArray as $key=>$value){
                $data[$value]=trim($sheet->getCellByColumnAndRow($key, $row)->getValue());
            }

            $data['create_time']=date('Y-m-d h:i:s');
            $newdata[]=$data;
        }
        unset($PHPExcel);
        $saves = array();
        foreach ($newdata as $v) {
            if($v['product_id'] == '' || $v['product_sku'] == '') {
                continue;
            }
            $have_data = $outproorderModel->where(['product_sku' => $v['product_sku'], 'product_id' => $v['product_id']])->find();
            if (!empty($have_data)) {
                $have_data->product_name = $v['product_name'];
                $have_data->product_size = $v['product_size'];
                $have_data->cutting_material_code = $v['cutting_material_code'];
                $have_data->slice_name = $v['slice_name'];
                $have_data->material_code = $v['material_code'];
                $have_data->name_of_raw_material = $v['name_of_raw_material'];
                $have_data->number_of_slices = $v['number_of_slices'];
                $have_data->dosage = $v['dosage'];
                $have_data->slice = $v['slice'];
                $have_data->cutting_unit = $v['cutting_unit'];
                $have_data->total_dosage = bcmul($v['dosage'], $v['slice'], 2);
                $have_data->isUpdate()->save();
            } else {
                $saves[] = $v;
            }
        }
        if (count($saves) > 0) {
            $boo = $outproorderModel->insertAll($saves, true);
        } else {
            $boo = true;
        }
        unset($saves);
        unset($sheet);
        if($boo!==false){
            return true;
        }else{
            return "导入失败，请重新导入数据";
        }
    }

    //导出数据
    public function exportsubdoc() {
        parent::excelStart();
        $ids = input('get.ids');
        if ($ids == '') {
            return false;
        }
        $PHPExcel = new \PHPExcel();
        $PHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A1", "产品ID")
            ->setCellValue("B1", "产品名称")
            ->setCellValue("C1", "产品SKU")
            ->setCellValue("D1", "产品型号")
            ->setCellValue("E1", "裁片物料编码")
            ->setCellValue("F1", "裁片名称")
            ->setCellValue("G1", "原料物料编码")
            ->setCellValue("H1", "原料名称")
            ->setCellValue("I1", "片数")
            ->setCellValue("J1", "用量/码")
            ->setCellValue("K1", "片/套")
            ->setCellValue("L1", "裁片单位")
            ->setCellValue("M1", "总用量（片/套*用量）")
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
        $PHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(12);
        $PHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(12);
        $PHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(12);

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
        $PHPExcel->getActiveSheet()->getStyle('K')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('L')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('M')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $outproModel = new Outproorder();

        if ($ids == 'all') {
            $res_data = $outproModel->select();
        } else {
            $res_data = $outproModel->where('id', 'in', $ids)->select();
        }

        foreach ($res_data as $k => $v) {
            $ks = $k + 2;
            $totald = bcmul($v['dosage'], $v['slice'], 2);
            $PHPExcel->setActiveSheetIndex(0)
                ->setCellValue("A" . $ks, $v['product_id'] . "\t")
                ->setCellValue("B" . $ks, $v['product_name'])
                ->setCellValue("C" . $ks, $v['product_sku'])
                ->setCellValue("D" . $ks, $v['product_size'])
                ->setCellValue("E" . $ks, $v['cutting_material_code'])
                ->setCellValue("F" . $ks, $v['slice_name'])
                ->setCellValue("G" . $ks, $v['material_code'])
                ->setCellValue("H" . $ks, $v['name_of_raw_material'])
                ->setCellValue("I" . $ks, $v['number_of_slices'])
                ->setCellValue("J" . $ks, (float)$v['dosage'])
                ->setCellValue("K" . $ks, $v['slice'])
                ->setCellValue("L" . $ks, $v['cutting_unit'])
                ->setCellValue("M" . $ks, (float)$totald);
        }

        $PHPExcel->setActiveSheetIndex(0);                   //设置sheet的起始位置
        //$objWriter = \PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel5');   //Excel2003通过PHPExcel_IOFactory的写函数将上面数据写出来
        $PHPWriter = \PHPExcel_IOFactory::createWriter($PHPExcel,"Excel2007"); //Excel2007

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition:inline;filename=委外工单.xlsx");

        $PHPWriter->save('php://output'); //表示在$path路径下面生成demo.xlsx文件
        exit;
    }
}
