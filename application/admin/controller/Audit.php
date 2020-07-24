<?php

namespace app\admin\controller;



use \app\admin\controller;

use \app\admin\model\Upplier as UpplierModel;
use app\admin\controller\Excel;
use \app\admin\model\Audit as AuditModel;

/**

 * Description of Index

 *

 * @author 27532

 */

class Audit extends Excel{

    protected $pageTotalItem=100;

    public function __construct() {

        parent::__construct();

    }

    public function index(){
        $this->assign('currentMenu',array('menu'=>'menu9','nav'=>'nav5'));
        $search = input('request.search','','trim');
        $auditModel = new AuditModel();
        $modeldata = $auditModel::withTrashed()->order('id', 'desc');
        if(!empty($search)){
            $where=array(
                'company' => ['like', "%{$search}%"],
                'contacts' => ['like', "%{$search}%"],
                'adress' => ['like', "%{$search}%"]
            );
            $modeldata=$modeldata->where(function($query)use($where){
                $query->whereOr($where);
            });
        }
        $data=$modeldata->relation('supplier,audituserinfo,applyuserinfo')->paginate($this->pageTotalItem,false,['query' =>request()->param()]);
        $this->assign('eventJS','audit');
        $this->assign('list',$data);
        $this->assign('pageDiv', $data->render());
        return $this->fetch();
    }

    //审核通过
    public function approved(){
        $ids = input('post.ids/a');
        $supplierModel = new UpplierModel();
        $auditModel = new AuditModel();
        $data = $auditModel->where(['id' => ['in', $ids]])->select();
        $audit_save = array();
        foreach ($data as $k => $v) {
            $audit_save[$k] = array(
                'id' => $v['supplier_id'],
                'attribute' => $v['attribute'],
                'type' => $v['type'],
                'company' => $v['company'],
                'company_for_short' => $v['company_for_short'],
                'payment_days' => $v['payment_days'],
                'contacts' => $v['contacts'],
                'contacts_position' => $v['contacts_position'],
                'tel' => $v['tel'],
                'email' => $v['email'],
                'remittance_num_company_name' => $v['remittance_num_company_name'],
                'remittance_num_company_bank' => $v['remittance_num_company_bank'],
                'remittance_num_company' => $v['remittance_num_company'],
                'remittance_num_private_name' => $v['remittance_num_private_name'],
                'remittance_num_private_bank' => $v['remittance_num_private_bank'],
                'remittance_num_private' => $v['remittance_num_private'],
                'adress' => $v['adress'],
                'main_purchased_materials' => $v['main_purchased_materials'],
                'note' => $v['note'],
                'display' => $v['display'],
            );
        }
        $boo = $supplierModel->isUpdate()->saveAll($audit_save);
        if($boo!==false){
            $auditModel->where(['id'=>['in', $ids]])->update(['audit_user' => session('user_id'), 'audit_status' => 1, 'audit_time' => date('Y-m-d H:i:s', time())]);
            $auditModel->destroy(function($query)use($ids){
                $query->where('id','in',$ids);
            });
            return json(['msg' => '保存成功', 'code' => 1000]);
        }else{
            return json(['msg' => '保存失败，请重新保存', 'code' => 1001]);
        }
    }
    //审核不通过
    public function delete(){
        $ids=input('post.ids/a');
        $auditModel=new AuditModel();
        $boo = $auditModel->where(['id'=>['in', $ids]])->update(['audit_user' => session('user_id'), 'audit_status' => 2, 'audit_time' => date('Y-m-d H:i:s', time())]);
        $auditModel->destroy(function($query)use($ids){
            $query->where('id','in',$ids);
        });
        if($boo){
            echo true;
        }else{
            echo "删除失败，请重新删除！";
        }
    }

    //导入数据
    public function export_supplier() {
        parent::excelStart();
        $fileName = isset($_FILES["file"]) ? $_FILES["file"]["name"] : "";
        $file = isset($_FILES["file"]) ? $_FILES["file"]["tmp_name"] : "";
        $ext = \file::get_ext($fileName);
        $isCustoms = strstr($fileName, '后台整理');
        if ($isCustoms == false) {
            return '请导入正确的后台整理文件';
        }
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
            '商品货号SKU' => 'sku',
            '商品编号' => 'commodity_code',
            '商品名称' => 'name',
            '商品要素' => 'element',
            '法定计量单位' => 'unit',
            '法定第二计量单位' => 'second_unit',
            '规格型号' => 'specification_model',
            '法定计量单位面积(可为空)' => 'unit_area',
            '法定第二计量单位面积(可为空)' => 'sec_unit_area',
            '数量计量单位' => 'num_unit'
        );
        for ($col = 0; $col <= $highestColumm; $col++) {
            $value = $sheet->getCellByColumnAndRow($col, 1)->getValue();
            $value = trim($value);
            if(isset($field[$value]))$filedArray[$col]=$field[$value];
        }

        $newdata=array();

        for ($row = 2; $row <= $highestRow; $row++) { //列数是以第0列开始
            $data=array();

            foreach ($filedArray as $key=>$value){
                $data[$value]=trim($sheet->getCellByColumnAndRow($key, $row)->getValue());
            }
            $newdata[]=$data;
        }
        unset($PHPExcel);
        $saves = array();
        /*$updates = array();*/

        unset($saves);
        unset($sheet);
        if(1!==false){
            return true;
        }else{
            return "导入失败，请重新导入数据";
        }
    }

}

