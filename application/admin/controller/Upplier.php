<?php

namespace app\admin\controller;



use \app\admin\controller;

use \app\admin\model\Upplier as UpplierModel;
use \app\admin\model\Audit;
use app\admin\controller\Excel;

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

class Upplier extends Excel{

    protected $pageTotalItem=100;

    public function __construct() {

        parent::__construct();

    }

    public function index($id){

        $this->assign('currentMenu',array('menu'=>'menu9','nav'=>'nav'.($id-1)));
        $search=input('request.search','','trim');
        $upplier=new UpplierModel();
        $modeldata=$upplier->order('id', 'desc')->where('type', $id);
        if(!empty($search)){
            $where=array(
                'company' => ['like', "%{$search}%"],
                'contacts' => ['like', "%{$search}%"],
                'adress' => ['like', "%{$search}%"],
            );
            $modeldata=$modeldata->where(function($query)use($where){
                $query->whereOr($where);
            });
        }
        $data=$modeldata->relation('auditsupplier')->paginate($this->pageTotalItem,false,['query' =>request()->param()]);

        $this->assign('type',$id);
        $this->assign('eventJS','upplier');
        $this->assign('list',$data);
        $this->assign('pageDiv', $data->render());
        return $this->fetch();
    }

    //查看所有供应商
    public function allsupplier(){
        $this->assign('currentMenu',array('menu'=>'menu9','nav'=>'nav4'));
        $search=input('request.search','','trim');
        $upplier=new UpplierModel();
        $modeldata=$upplier->order('id', 'desc');
        if(!empty($search)){
            $where=array(
                'company' => ['like', "%{$search}%"],
                'contacts' => ['like', "%{$search}%"],
                'adress' => ['like', "%{$search}%"],
            );
            $modeldata=$modeldata->where(function($query)use($where){
                $query->whereOr($where);
            });
        }
        $data=$modeldata->relation('auditsupplier')->paginate($this->pageTotalItem,false,['query' =>request()->param()]);

        /*$this->assign('type',$id);*/
        $this->assign('eventJS','upplier');
        $this->assign('list',$data);
        $this->assign('pageDiv', $data->render());
        return $this->fetch();
    }

    //添加供应商
    public function add($id) {

        $this->assign('currentMenu',array('menu'=>'menu9','nav'=>'nav'.($id-1)));
        $data=array('id'=>'','type'=>$id,'company'=>'','adress'=>'','contacts'=>'','tel'=>'','display'=>'1','attribute'=>'','company_for_short'=>'','payment_days'=>'','contacts_position'=>'','remittance_num_company_name'=>'','remittance_num_company_bank'=>'','remittance_num_company'=>'','remittance_num_private_name'=>'','remittance_num_private_bank'=>'','remittance_num_private'=>'','main_purchased_materials'=>'','email'=>'','note'=>'',);
        $this->assign('data',$data);
        $this->assign('eventJS','upplier');
        $this->assign('is_edit',0);
        return $this->fetch('upplier/edit');
    }


    //修改供应商资料
    public function edit($id) {

        $supplierModel = new UpplierModel();
        $data =  $supplierModel->relation('auditsupplier')->where('id', $id)->find();
        if ($data['auditsupplier'] != null) {
            $res = array(
                'id' => $data['auditsupplier']['supplier_id'],
                'attribute' => $data['auditsupplier']['attribute'],
                'type' => $data['auditsupplier']['type'],
                'company' => $data['auditsupplier']['company'],
                'company_for_short' => $data['auditsupplier']['company_for_short'],
                'payment_days' => $data['auditsupplier']['payment_days'],
                'contacts' => $data['auditsupplier']['contacts'],
                'contacts_position' => $data['auditsupplier']['contacts_position'],
                'tel' => $data['auditsupplier']['tel'],
                'email' => $data['auditsupplier']['email'],
                'remittance_num_company_name' => $data['auditsupplier']['remittance_num_company_name'],
                'remittance_num_company_bank' => $data['auditsupplier']['remittance_num_company_bank'],
                'remittance_num_company' => $data['auditsupplier']['remittance_num_company'],
                'remittance_num_private_name' => $data['auditsupplier']['remittance_num_private_name'],
                'remittance_num_private_bank' => $data['auditsupplier']['remittance_num_private_bank'],
                'remittance_num_private' => $data['auditsupplier']['remittance_num_private'],
                'adress' => $data['auditsupplier']['adress'],
                'main_purchased_materials' => $data['auditsupplier']['main_purchased_materials'],
                'note' => $data['auditsupplier']['note'],
                'display' => $data['auditsupplier']['display'],
                'audit_status' => $data['auditsupplier']['audit_status'],
                'create_timer' => $data['auditsupplier']['create_timer'],
            );
        } else {
            $res = $data;
        }
        $this->assign('currentMenu',array('menu'=>'menu9','nav'=>'nav'.($data['type']-1)));
        $this->assign('data',$res);
        $this->assign('eventJS','upplier');
        $this->assign('is_edit',1);
        return $this->fetch();
    }

    //添加供应商
    public function add_supplier(){
        $this->assign('currentMenu',array('menu'=>'menu9','nav'=>'nav4'));
        $data=array('id'=>'','type'=>'','company'=>'','adress'=>'','contacts'=>'','tel'=>'','display'=>'1','attribute'=>'','company_for_short'=>'','payment_days'=>'','contacts_position'=>'','remittance_num_company_name'=>'','remittance_num_company_bank'=>'','remittance_num_company'=>'','remittance_num_private_name'=>'','remittance_num_private_bank'=>'','remittance_num_private'=>'','main_purchased_materials'=>'','email'=>'','note'=>'',);
        $this->assign('data',$data);
        $this->assign('eventJS','upplier');
        $this->assign('is_edit',0);
        return $this->fetch();
    }

    //修改供应商资料
    public function edit_supplier($id){
        $supplierModel = new UpplierModel();
        $data =  $supplierModel->relation('auditsupplier')->where('id', $id)->find();
        if ($data['auditsupplier'] != null) {
            $res = array(
                'id' => $data['auditsupplier']['supplier_id'],
                'attribute' => $data['auditsupplier']['attribute'],
                'type' => $data['auditsupplier']['type'],
                'company' => $data['auditsupplier']['company'],
                'company_for_short' => $data['auditsupplier']['company_for_short'],
                'payment_days' => $data['auditsupplier']['payment_days'],
                'contacts' => $data['auditsupplier']['contacts'],
                'contacts_position' => $data['auditsupplier']['contacts_position'],
                'tel' => $data['auditsupplier']['tel'],
                'email' => $data['auditsupplier']['email'],
                'remittance_num_company_name' => $data['auditsupplier']['remittance_num_company_name'],
                'remittance_num_company_bank' => $data['auditsupplier']['remittance_num_company_bank'],
                'remittance_num_company' => $data['auditsupplier']['remittance_num_company'],
                'remittance_num_private_name' => $data['auditsupplier']['remittance_num_private_name'],
                'remittance_num_private_bank' => $data['auditsupplier']['remittance_num_private_bank'],
                'remittance_num_private' => $data['auditsupplier']['remittance_num_private'],
                'adress' => $data['auditsupplier']['adress'],
                'main_purchased_materials' => $data['auditsupplier']['main_purchased_materials'],
                'note' => $data['auditsupplier']['note'],
                'display' => $data['auditsupplier']['display'],
                'audit_status' => $data['auditsupplier']['audit_status'],
                'create_timer' => $data['auditsupplier']['create_timer'],
            );
        } else {
            $res = $data;
        }
        $this->assign('currentMenu',array('menu'=>'menu9','nav'=>'nav4'));
        $this->assign('data',$res);
        $this->assign('eventJS','upplier');
        $this->assign('is_edit',1);
        return $this->fetch();
    }

    //保存
    public function save(){
        $id=input('post.id');
        if(!empty($id)){
            $upplier= UpplierModel::get($id);
        }else{
            $upplier=new UpplierModel();
            $upplier->create_timer=date('Y-m-d H:i:s', time());
        }

        $upplier->company=input('post.company','','trim');
        $upplier->adress=input('post.adress','','trim');
        $upplier->contacts=input('post.contacts','','trim');
        $upplier->tel=input('post.tel','','trim');
        $upplier->display=input('post.display');
        $upplier->type=input('post.type');
        $upplier->attribute=input('post.attribute','','trim');
        $upplier->company_for_short=input('post.company_for_short','','trim');
        $upplier->payment_days=input('post.payment_days','','trim');
        $upplier->contacts_position=input('post.contacts_position','','trim');
        $upplier->remittance_num_company_name=input('post.remittance_num_company_name','','trim');
        $upplier->remittance_num_company_bank=input('post.remittance_num_company_bank','','trim');
        $upplier->remittance_num_company=input('post.remittance_num_company','','trim');
        $upplier->remittance_num_private_name=input('post.remittance_num_private_name','','trim');
        $upplier->remittance_num_private_bank=input('post.remittance_num_private_bank','','trim');
        $upplier->remittance_num_private=input('post.remittance_num_private','','trim');
        $upplier->main_purchased_materials=input('post.main_purchased_materials','','trim');
        $upplier->email=input('post.email','','trim');
        $upplier->note=input('post.note','','trim');

        if($upplier->save()!==false){
            echo true;
        }else{
            echo "保存失败，请重新保存";
        }
    }
    //保存审核数据
    public function save_audit(){
        $upplierModel = new Audit();
        $supplier_id = input('post.id');
        $upplier = $upplierModel->where('supplier_id', $supplier_id)->find();
        if ($upplier != null) {

            $upplier->supplier_id = input('post.id');

            $upplier->company = input('post.company', '', 'trim');
            $upplier->adress = input('post.adress', '', 'trim');
            $upplier->contacts = input('post.contacts', '', 'trim');
            $upplier->tel = input('post.tel', '', 'trim');
            $upplier->display = input('post.display');
            $upplier->type = input('post.type');
            $upplier->attribute = input('post.attribute', '', 'trim');
            $upplier->company_for_short = input('post.company_for_short', '', 'trim');
            $upplier->payment_days = input('post.payment_days', '', 'trim');
            $upplier->contacts_position = input('post.contacts_position', '', 'trim');
            $upplier->remittance_num_company_name=input('post.remittance_num_company_name','','trim');
            $upplier->remittance_num_company_bank=input('post.remittance_num_company_bank','','trim');
            $upplier->remittance_num_company=input('post.remittance_num_company','','trim');
            $upplier->remittance_num_private_name=input('post.remittance_num_private_name','','trim');
            $upplier->remittance_num_private_bank=input('post.remittance_num_private_bank','','trim');
            $upplier->remittance_num_private=input('post.remittance_num_private','','trim');
            $upplier->main_purchased_materials = input('post.main_purchased_materials', '', 'trim');
            $upplier->email = input('post.email', '', 'trim');
            $upplier->note = input('post.note', '', 'trim');
            $upplier->apply_user = session('user_id');
            $upplier->audit_status = 0;
        } else {
            $upplier=new Audit();
            $upplier->create_timer=date('y-m-d h:i:s');
            $upplier->supplier_id = input('post.id');

            $upplier->company = input('post.company', '', 'trim');
            $upplier->adress = input('post.adress', '', 'trim');
            $upplier->contacts = input('post.contacts', '', 'trim');
            $upplier->tel = input('post.tel', '', 'trim');
            $upplier->display = input('post.display');
            $upplier->type = input('post.type');
            $upplier->attribute = input('post.attribute', '', 'trim');
            $upplier->company_for_short = input('post.company_for_short', '', 'trim');
            $upplier->payment_days = input('post.payment_days', '', 'trim');
            $upplier->contacts_position = input('post.contacts_position', '', 'trim');
            $upplier->remittance_num_company_name=input('post.remittance_num_company_name','','trim');
            $upplier->remittance_num_company_bank=input('post.remittance_num_company_bank','','trim');
            $upplier->remittance_num_company=input('post.remittance_num_company','','trim');
            $upplier->remittance_num_private_name=input('post.remittance_num_private_name','','trim');
            $upplier->remittance_num_private_bank=input('post.remittance_num_private_bank','','trim');
            $upplier->remittance_num_private=input('post.remittance_num_private','','trim');
            $upplier->main_purchased_materials = input('post.main_purchased_materials', '', 'trim');
            $upplier->email = input('post.email', '', 'trim');
            $upplier->note = input('post.note', '', 'trim');
            $upplier->apply_user = session('user_id');
            $upplier->audit_status = 0;
        }
        if($upplier->save()!==false){
            echo true;
        }else{
            echo "保存失败，请重新保存";
        }
    }

    //删除
    public function delete(){

        $ids=input('post.ids/a');
        $auditModel = new Audit();
        $material=new UpplierModel();

        $boo=$material->destroy(function($query)use($ids){
            $query->where('id','in',$ids);
        });

        if($boo){
            //$auditModel->where(['id'=>['in', $ids]])->update(['audit_user' => session('user_id'), 'audit_status' => 1, 'audit_time' => date('Y-m-d H:i:s', time())]);
            $auditModel = new Audit();
            $auditModel->destroy(function($query)use($ids){
                $query->where('supplier_id','in',$ids);
            });
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

