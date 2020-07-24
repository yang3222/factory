<?php

namespace app\admin\controller;

use app\admin\model\EpDislist;
use app\admin\model\EpMaterial;
use app\admin\model\EpMaterialdetail;
use app\admin\model\EpOutInDetails;
use app\admin\model\EpWtableMaterial;
use app\admin\model\Fbadelivery;
use app\admin\model\Fbaorder;
use \app\admin\model\Material;
use \app\admin\model\Materialupplier;
use app\admin\model\Orderfactory;
use \app\admin\model\Upplier;
use app\admin\model\Materialdetail;
use app\admin\model\Warehousetable;
use app\admin\model\Wtablematerial;
use app\admin\model\EpWarehouseTable;
use app\admin\model\EpWarehouse as WH;
use app\admin\model\EpWarehouseMaterialdetail as WHMD;
use app\admin\logic\Warehouse as logicWH ;
use think\Db;
use think\Loader;
use think\migration\db\Column;
use app\admin\model\OutInDetails;
use fpdf182\fpdf;
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

class Epwarehouse extends Excel{

    protected $pageTotalItem=100;

    public function __construct() {

        parent::__construct();

    }
    public function qrtest()
    {
        return $this->fetch('qrtest');
    }
    //材料管理

    public function material(){

        $this->assign('currentMenu',array('menu'=>'menu13','nav'=>'nav0'));

        //$head= strtoupper(substr(md5("350216EEVCEB303Message"),8,16).$this->getRandom(20));

        //$head= strtoupper(substr(md5("350216EEV"),8,16).$this->getRandom(20));

        $search=input('request.search','','trim');

        $material=new Material();

        $modeldata=$material->order('id', 'desc');

        if(!empty($search)){

            $where=array(

                'name' => ['like', "%{$search}%"],

                'finance_num' => ['like', "%{$search}%"],

                'warehouse_num' => ['like', "%{$search}%"],

                'warehouse_num_prev' => ['like', "%{$search}%"],

                'type' => ['like', "%{$search}%"],

            );

            $modeldata=$modeldata->whereor($where);

        }

        $data=$modeldata->paginate($this->pageTotalItem,false,['query' =>request()->param()]);

        $this->assign('eventJS','warehouse');

        $this->assign('list',$data);

        $this->assign('pageDiv', $data->render());

        return $this->fetch('material');

    }

    //通过Excel导入数据

    public function importexcel(){

        parent::excelStart();

        $material = new Material();

        $fileName = isset($_FILES["file"]) ? $_FILES["file"]["name"] : "";

        $file = isset($_FILES["file"]) ? $_FILES["file"]["tmp_name"] : "";

        $ext = \file::get_ext($fileName);

        if ($ext == "xls") {

            $reader = \PHPExcel_IOFactory::createReader('Excel5');

        } else {

            $reader = \PHPExcel_IOFactory::createReader('Excel2007');

        }

        $PHPExcel = $reader->load($file);

        $sheet = $PHPExcel->getSheet(0); // 读取第一個工作表

        $highestRow = $sheet->getHighestRow(); // 取得总行数

        $highestColumm = $sheet->getHighestColumn(1); // 取得总列数

        $highestColumm = \PHPExcel_Cell::columnIndexFromString($highestColumm); //字母列转换为数字列 如:AA变为27

        $filedArray = array();
        //价格
        $field=array('物料编码(*)'=>'finance_num','物料名称(*)'=>'name','规格型号'=>'size','物料属性(*)'=>'att','计量单位类别名称(*)'=>'company','材料类型'=>'type','仓库物料编码'=>'warehouse_num','单价'=>'money');

        for ($col = 0; $col <= $highestColumm; $col++) {

            $value = $sheet->getCellByColumnAndRow($col, 1)->getValue();

            if(isset($field[$value]))$filedArray[$col]=$field[$value];

        }
        $newdata=array();
        for ($row = 2; $row <= $highestRow; $row++) { //列数是以第0列开始
            $data=array();
            foreach ($filedArray as $key=>$value){
                $data[$value]=$sheet->getCellByColumnAndRow($key, $row)->getValue();
            }

            $data['create_timer']=date('Y-m-d H:i:s');
            if (empty($data['warehouse_num']))$data['warehouse_num']='';
            if (empty($data['money'])) return "有材料价格为空，请重新导入数据" . $row . '-' . $data['money'];
            $finddata = $material->where('finance_num', $data['finance_num'])->find();
            if(isset($finddata->id)) $data['id']=$finddata->id;
            $newdata[] = $data;
            unset($data);
        }
        $saves = array();

        foreach ($newdata as $key => $val) {
            if (isset($val['id'])) {
                $material->update($val);
            } else {
                $saves[] = $val;
            }
        }
        /*$old_m = $material->select();
        $old_key = array_column($old_m, 'finance_num');
        $new_key = array_column($newdata, 'finance_num');*/
        /*foreach ($newdata as $k => $v) {
            $save = array();
            foreach ($v as $kk => $vv) {
                if (!empty($vv)) {
                    $save[$kk] = $vv;
                }
            }
            $m = $material->where('finance_num', $v['finance_num'])->find();
            if (!empty($m)) {
                $material->update($save, ['id' => $m['id']]);
            }
            else {
                $saves[] = $v;
            }
        }*/

        /*$id_arr = array();
        foreach ($old_m as $key => $val) {
            $exit = array_keys($new_key, $val['finance_num']);
            if (empty($exit)) $id_arr[] = $val['id'];
        }*/

        /*$id_arr = array_column($old_m, 'id');*/

        /*$material->destroy(function($query)use($id_arr){

            $query->where('id','in',$id_arr);

        });*/
        //$boo = $material->update($updates, ['finance_num' => $v['finance_num']]);
        $boo = $material->saveAll($saves,true);
        if($boo!==false){
            echo true;
        }else{
            echo "导入失败，请重新导入数据";
        }
    }

    //导出数据

    public function exportexcel(){

        parent::excelStart();

        $material=new Material();

        $data=$material->select();

        $PHPExcel=new \PHPExcel();

        $PHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A1","物料编码(*)")
            ->setCellValue("B1","物料名称(*)")
            ->setCellValue("C1","规格型号")
            ->setCellValue("D1","物料属性(*)")
            ->setCellValue("E1","计量单位类别名称(*)")
            ->setCellValue("F1","材料类型")
            ->setCellValue("G1","价格");
        //warehouse_num 仓库物料编码

        $count=count($data);

        for($i=0;$i<$count;$i++){
            $index=$i+2;
            $PHPExcel->getActiveSheet()
                ->setCellValue("A" . $index, $data[$i]->finance_num)
                ->setCellValue("B" . $index, $data[$i]->name)
                ->setCellValue("C" . $index, $data[$i]->size)
                ->setCellValue("D" . $index, $data[$i]->att)
                ->setCellValue("E" . $index, $data[$i]->company)
                ->setCellValue("F" . $index, $data[$i]->type)
                ->setCellValue("G" . $index, $data[$i]->money);
        }
        $PHPExcel->getActiveSheet()->setTitle('物料');      //设置sheet的名称
        $PHPExcel->setActiveSheetIndex(0);                   //设置sheet的起始位置
        //$objWriter = \PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel5');   //Excel2003通过PHPExcel_IOFactory的写函数将上面数据写出来
        $PHPWriter = \PHPExcel_IOFactory::createWriter( $PHPExcel,"Excel2007"); //Excel2007
        //header('Content-Disposition: attachment;filename="用户信息.xlsx"');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $PHPWriter->save("php://output"); //表示在$path路径下面生成demo.xlsx文件
        exit;
    }

    //添加材料

    public function add(){

        $this->assign('currentMenu',array('menu'=>'menu8','nav'=>'nav0'));

        $data=array('id'=>'','type'=>'','finance_num'=>'','choice'=>'','name'=>'','size'=>'','att'=>'','company'=>'','display'=>'1','money'=>'','create_timer'=>'');

        $this->assign('data',$data);

        $this->assign('eventJS','warehouse');

        return $this->fetch("warehouse/edit");

    }

    //修改材料

    public function edit($id){

        $this->assign('currentMenu',array('menu'=>'menu8','nav'=>'nav0'));

        $data=  Material::get($id);

        $this->assign('data',$data);

        $this->assign('eventJS','warehouse');

        return $this->fetch();

    }


    //查看材料

    public function showmaterialedit($id){
        $this->view->engine->layout(false);
        $this->assign('currentMenu',array('menu'=>'menu8','nav'=>'nav0'));

        $MaterialModel = new Material();
        $material = $MaterialModel->relation('materialupplier')->where('id', $id)->find();

        $this->assign('data',$material);
        $this->assign('eventJS','warehouse');
        return $this->fetch();
    }

    //材料供应商列表

    public function upplier($material_id){
        $this->assign('currentMenu',array('menu'=>'menu8','nav'=>'nav0'));
        $MaterialModel = new Material();
        $material = $MaterialModel->relation('materialupplier')->where('id', $material_id)->find();

        $this->assign('data',$material);

        $this->assign('eventJS','warehouse');

        return $this->fetch();

    }

    public function addupplier($material_id){
        $this->assign('currentMenu',array('menu'=>'menu8','nav'=>'nav0'));
        $search=input('request.search','','trim');

        $type=input('request.select','','trim');

        $upplier=new Upplier();

        $data=$upplier->where('display','=','1');

        if(!empty($type)){

            $data=$data->where('type','=',$type);

        }

        if(!empty($search)){

            $where=array(

                'company' => ['like', "%{$search}%"],

                'contacts' => ['like', "%{$search}%"],

                'adress' => ['like', "%{$search}%"],

            );

            $data=$data->where(function($query)use($where){

                $query->whereOr($where);

            });

        }

        $data=$data->paginate($this->pageTotalItem,false,['query' =>request() ->param()]);

        $this->assign('material_id',$material_id);

        $this->assign('data',$data);

        $this->assign('eventJS','warehouse');

        $this->assign('pageDiv', $data->render());

        return $this->fetch();

    }

    //添加供应商

    public function addupplierpost($material_id){

        $add=input('post.ids/a');

        $materialupplier=new Materialupplier();

        $data=array();

        foreach($add as $value){

            $count=$materialupplier->where([

                'material_id'=>['=',$material_id],

                'upplier_id'=>['=',$value]

            ])->count();

            if($count==0){

                $data[]=array('material_id'=>$material_id,'upplier_id'=>$value);

            }

        }

        $boo=$materialupplier->saveAll($data);

        if($boo!==false){

            echo true;

        }else{

            echo "添加失败，请重新添加！";

        }

    }

    //删除供应商

    public function deleteupplier(){

        $ids=input('post.ids/a');

        $material=new Materialupplier();

        $boo=$material->destroy(function($query)use($ids){

            $query->where('id','in',$ids);

        });

        if($boo){

            echo true;

        }else{

            echo "删除失败，请重新删除！";

        }

    }

    public function save(){

        $id=input('post.id');

        $warehouse_num=input('post.warehouse_num','','trim');

        if(!empty($id)){

            $material=  Material::get($id);

            if($material->warehouse_num!=$warehouse_num)$material->warehouse_num_prev=$material->warehouse_num;

        }else{

            $material=new Material();

            $material->create_timer=date('Y-m-d H:i:s');

        }

        $material->finance_num=input('post.finance_num','','trim');

        $material->warehouse_num=$warehouse_num;

        $material->name=input('post.name','','trim');

        $material->size=input('post.size','','trim');

        $material->att=input('post.att','','trim');

        $material->company=input('post.company','','trim');

        $material->money=input('post.money','','trim');

        $material->display=input('post.display');

        $material->type=input('post.type');

        $material->choice=input('post.choice');



        if($material->save()!==false){

            echo true;

        }else{

            echo "保存失败，请重新保存";

        }

    }

    public function delete(){

        $ids=input('post.ids/a');

        $material=new Material();

        $boo=$material->destroy(function($query)use($ids){

            $query->where('id','in',$ids);

        });

        if($boo){

            echo true;

        }else{

            echo "删除失败，请重新删除！";

        }

    }

    //随机名字
    private function getRandom($param){

        $str="0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";

        $key = "";

        for($i=0;$i<$param;$i++){

            $key .= $str{mt_rand(0,32)};    //生成php随机数

        }

        return $key;

    }
    //仓库列表
    public function lists(){

        $search=input('request.search','','trim');
        $factory_pid=input('request.factory_pid',5,'trim');
        $factory_sid=input('request.factory_sid','','trim');
        $warehouseModel = new WH();

        $modeldata = $warehouseModel->order('id', 'desc');

        if($factory_pid != 5){
            $modeldata=$modeldata->where(['factory_pid'=>$factory_pid]);
        }
        if($factory_pid == $warehouseModel::WHTYPE_EP){
            $modeldata=$modeldata->where(['factory_sid'=>$factory_sid]);
        }

        if(!empty($search)){

            $where=array(

                'name'=>['like',"%{$search}%"],

                'id'=>"{$search}",

            );
            $modeldata->whereor($where);
        }


        $data=$modeldata->paginate($this->pageTotalItem,false,['query' =>request()->param()]);
        $this->assign('list',$data);
        $this->assign('pageDiv', $data->render());

        $this->assign('search',$search);
        $this->assign('factory_pid',$factory_pid);
        $this->assign('factory_sid',$factory_sid);

        $this->assign('eventJS','warehousetable');

        $this->assign('currentMenu',array('menu'=>'menu13','nav'=>'nav1'));

        return  $this->fetch('warehouse_list');

    }
    //仓库新增
    public function warehouseAdd(){

        $warehouseModel = new WH();

        $warehouse_one  = $warehouseModel->limit(1)->order('id','desc')->select();

        $warehouse_name = empty($warehouse_one) ? 'A' : chr(ord($warehouse_one[0]['name'])+1);

        $this->assign('warehouse_name', $warehouse_name);
        $this->assign('eventJS' ,       'warehousetable');
        $this->assign('currentMenu', array('menu'=>'menu13','nav'=>'nav1'));
        return  $this->fetch();

    }
    //仓库编辑
    public function warehouseEdit($id){
        $warehouseModel = new WH();
        $data = $warehouseModel->get($id);
        $warehouseTable = new EpWarehouseTable();
        $wt = $warehouseTable->where('parents_id=0 AND warehouse_id=:w_id')->bind(['w_id' => $id])->select();
        $data['warehousetables'] = $wt;

        $this->assign('list',$data);
        $this->assign('status','2');
        $this->assign('eventJS','warehousetable');
        $this->assign('currentMenu', array('menu'=>'menu13','nav'=>'nav1'));
        return  $this->fetch('warehouse_edit');

    }
    //查看规划入库
    public function viewplan($id){

        $warehouseModel  = new WH();

        $data = $warehouseModel->get(['id'=>$id]);
        $warehouseTable = new EpWarehousetable();
        $wt = $warehouseTable->where('parents_id=0 AND warehouse_id=:w_id')->bind(['w_id' => $id])->select();
        $data['warehousetables'] = $wt;

        $this->assign('list', $data);

        $this->assign('eventJS', 'warehousetable');
        $this->assign('currentMenu', array('menu'=>'menu13','nav'=>'nav1'));

        return  $this->fetch('warehouse_plan');

    }
    //查看仓库
    public function viewin($id){

        $warehouseModel  = new WH();

        $data = $warehouseModel->get(['id'=>$id]);
        $warehouseTable = new EpWarehouseTable();
        $wt = $warehouseTable->where('parents_id=0 AND warehouse_id=:w_id')->bind(['w_id' => $id])->select();
        //$wttt = $warehouseTable->relation('sfgsdfg')->where('')->select();
        $data['warehousetables'] = $wt;

        $this->assign('list', $data);

        $this->assign('eventJS', 'warehousetable');

        $this->assign('currentMenu', array('menu'=>'menu13','nav'=>'nav1'));

        return  $this->fetch('warehouse_in');

    }

    //viewqrcode
    public function viewqrcode() {
        $this->view->engine->layout(false);
        $id = input('post.id');
        $epwarehouseModel  = new \app\admin\model\EpWarehouse();

        $data = $epwarehouseModel->get(['id'=>$id]);
        $epwarehouseTable = new epWarehousetable();
        $wt = $epwarehouseTable->where('parents_id = 0 and warehouse_id=:w_id')->bind(['w_id' => $id])->select();
        foreach ($wt as $k => $v) {
            $wt[$k]['child'] = $epwarehouseTable->where('parents_id','=',$v['id'])->select();
        }

        $data['warehousetables'] = $wt;

        $this->assign('list', $data);
        $this->assign('eventJS', 'warehousetable');
        //$this->assign('currentMenu', array('menu'=>'menu8','nav'=>'nav1'));
        return  $this->fetch();

    }



    //查看原材料
    public function viewMaterial($id){

        $warehouseModel  = new WH();
        $warehouseTable = new Warehousetable();
        $materialModel  = new Wtablematerial();
        $where['m_id'] = $id;
        $materiallist = $materialModel->where($where)->select();
        $wt_id_arr = array();
        foreach ($materiallist as $key => $value) {
            $wt_id_arr[$key] = $value['wt_id'];
        }
        $data_wt = $warehouseTable->where('id', 'in', $wt_id_arr)->column('warehouse_id');//field('warehouse_id,parents_id,id,name,p_x,p_y,status,volume')->select();

        $data = array();
        $wt = array();
        if (count($data_wt) > 0) {
            $data_wt = array_unique($data_wt);
            foreach ($data_wt as $key => $value) {
                $wt[$value] = $warehouseTable->where('parents_id=0 AND warehouse_id=:w_id')->bind(['w_id' => $value])->select();
            }
            $data = $warehouseModel->where('id', 'in', $data_wt)->select();
        }

        $this->assign('list', $data);
        $this->assign('wlist', $wt);
        $this->assign('mlist', $materiallist);
        $this->assign('eventJS', 'warehousetable');
        $this->assign('currentMenu', array('menu'=>'menu8','nav'=>'nav2'));
        return  $this->fetch('view_material');

    }
    //移除物资
    public function delPlan(){

        $id = input('post.id');
        $logic = new logicWH();

        $delRes = $logic->epdelPlan($id);

        echo json_encode($delRes);
    }
    //添加提交仓库
    public function postWarehouse(){

        $data = input('post.');

        $warehouseModel = new WH();
        $tableModel = new EpWarehouseTable();

        // 启动事务
        \think\Db::startTrans();

        $warehouseArr = ["factory_pid"=>$data['factory_pid'], "factory_sid"=>$data['factory_sid'], 'name'=>$data['name'],'x'=>$data['x'],'y'=>$data['y'],'status'=>$data['status'],'create_time'=> date('Y-m-d h:i:s',time())];
        $warehouseModel->save($warehouseArr);
        $insertWarehouseId = $warehouseModel->getLastInsID();

        $warehouseTableFatherList = [];

        try {

            $parentids = [];
            foreach ($data['fatherId'] as $v){

                $famxy          = explode('_',$v[0]);
                $famx           = logicWH::int2twostr($famxy[0]);
                $famy           = $famxy[1];
                $parentsname    = $data['name'].$famy.$famx;

                $warehouseTableFatherList = [
                    'parents_id' => '0',
                    'warehouse_id' => $insertWarehouseId,
                    'name' => $parentsname,
                    //'volume' => $v[1],
                    'p_x' => $famxy[0],
                    'p_y' => $famxy[1],
                    'create_time' => date('Y-m-d h:i:s',time()),
                    'status' => 1
                ];

                $tableModel->isUpdate(false)->data($warehouseTableFatherList, true)->save();
                $parentids[$famxy[0].'_'.$famxy[1]] = $tableModel->getLastInsID();

            }

            if(!empty($data['childId'])){

                $warehouseTableList = [];

                foreach ($data['childId'] as $v){

                    $fam        = explode('__',$v[0]);
                    $famxy      = explode('_',$fam[0]);
                    $famx       = logicWH::int2twostr($famxy[0]);
                    $famy       = $famxy[1];
                    $child      = $fam[1];
                    $childxy    = explode("_", $child);
                    $childy     = $childxy[1];
                    $parents_id = $parentids[$fam[0]];
                    $childname  = $data['name'].$famy.$famx."-".$childy;
                    $warehouseTableList[] = [
                        'parents_id' => $parents_id,
                        'warehouse_id' => $insertWarehouseId,
                        'status' => 1,
                        'name' => $childname,
                        'create_time'=> date('Y-m-d H:i:s',time()),
                        //'volume' => $v[1],
                        'p_x' => $childxy[0],
                        'p_y' => $childxy[1]
                    ];
                }

                $tableModel->isUpdate(false)->saveAll($warehouseTableList);

            }

            \think\Db::commit();
            exit('1');

        } catch (\Exception $ex) {
            echo $ex->getTraceAsString();
            //回滚事务
            \think\Db::rollback();
            echo "3";
            exit;

        }

    }
    //删除仓库
    public function delWarehouse(){

        $id = input('post.id');
        $wid_slibing = input('post.wid_slibing');
        $type = input('post.type');
        $tableModel = new EpWarehousetable();
        $whmdModel = new WHMD;
        if(empty($id) || !is_numeric($id)) exit('4');
        if ($type == 'two') {
            if(!is_numeric($wid_slibing)) exit('4');
            $id_arr[0] = $id;
            $id_arr[1] = $wid_slibing;
            $whmd_1 = $whmdModel::get(['wt_id'=>$id]);
            $whmd_2 = $whmdModel::get(['wt_id'=>$wid_slibing]);
            if(empty($whmd_1) && empty($whmd_2)) {
                $tableModel::destroy($id);
                $tableModel::destroy($wid_slibing);
//                $tableModel->delWHTable('id','in',$id_arr);
                //return json($res);//2 有库存 1 没库存删除成功
                exit('1');
            } else {
                exit('2');
            }
        }
        if($type == 'one'){

            $whmd = $whmdModel::get(['wt_id'=>$id]);
            if(empty($whmd)){
                $data = $tableModel::get($id);
                $data->delete();
                exit("1");//2 有库存 1 没库存删除成功
            }
            exit("2");
        }

        if ($type == 'all') {
//            $wtableMaterialmodel = new EpWtableMaterial();

            $wt_id = $tableModel->where('warehouse_id', $id)->column('id');
            $wtm_res = $whmdModel->where('wt_id', 'in', $wt_id)->select();
            /*$sqlStr = "select c.*  from ink_warehouse_table wt inner join  ink_wtable_material wm on wt.id=wm.wt_id   "
                    . "inner join (select * from ink_warehouse_table where parents_id = 0 and warehouse_id=".$id." ) c on wt.id = c.id";*/
            $sqlStr = "select c.*  from ink_warehouse_table wt inner join  ink_wtable_material wm on wt.id=wm.wt_id   "
                . "inner join (select * from ink_warehouse_table where warehouse_id=".$id." ) c on wt.id = c.id";//去掉and条件parents_id = 0，也需要查询是否有规划材料
            $data = \think\Db::query($sqlStr);

            if(empty($wtm_res)){
                $sqlStr = "select group_concat( if (small.id is null ,big.id,concat(big.id, ',',small.id) )  separator ',' )  res"
                    . " from (select * from ink_warehouse_table where parents_id = 0 and warehouse_id=".$id." ) big   left  join  "
                    . "(select * from ink_warehouse_table where warehouse_id=0) small  on big.id = small.parents_id";

                //$res = \think\Db::query($sqlStr);

                // 启动事务
                \think\Db::startTrans();
                try {
                    \app\admin\model\EpWarehouse::destroy($id);
//                    \think\Db::query("delete from   ink_ep_warehouse  where id =".$id );
                    /*if(!empty($res[0]['res'])){
                        \think\Db::query("delete from ink_warehouse_table  where id in(".$res[0]['res'].")");
                    }    */
                    //修改直接删除对应的warehouse_id
                   $tbm = $tableModel::get(['warehouse_id'=>$id]);
                   $tbm->delete();
//                    \think\Db::query("delete from ink_ep_warehouse_table  where warehouse_id =".$id);

                    \think\Db::commit();
                    exit("1");

                } catch (\Exception $ex) {
                    //回滚事务
                    \think\Db::rollback();
                    exit("3");
                }
            }
            exit("2");
        }

    }
    //提交修改仓库
    public function updateWarehouse(){

        if(!request()->isPost()) exit(3);

        $data = input('post.');
        $warehouseTableList=[];

        $tableModel     = new EpWarehousetable();
        $warehouseModel = new WH();
        $whData = $warehouseModel->get($data['id']);

        \think\Db::startTrans();

        try {
            $whData->status = $data['status'];
            $whData->factory_pid = $data['factory_pid'];
            $whData->factory_sid = $data['factory_sid'];
            $whData->save();
            if(!empty($data['childId'])){

                foreach ($data['childId'] as $v){

                    $fam        = explode('__',$v[0]);
                    $child      = explode('_',$fam[1]);

                    $res        = $tableModel->get($fam[0]);

                    $childy     = $child[1];
                    $partwox    = logicWH::int2twostr($res['p_x']);
                    $partwoy    = $res['p_y'];
                    $childname  = $whData['name'].$partwoy.$partwox."-".logicWH::int2twostr($childy);

                    $warehouseTableList[] = [
                        'parents_id' => $fam[0],
                        //'volume' => $v[1],
                        'status' => 1,
                        'warehouse_id' => $data['id'],//增加子仓库对应顶级仓库位id
                        'create_time' => date('Y-m-d h:i:s',time()),
                        'name' => $childname,
                        'p_x' => $child[0],
                        'p_y' => $child[1]
                    ];
                }

                $tableModel->isUpdate(false)->saveAll($warehouseTableList);
            }
            //设置容量
            if(!empty($data['volume'])){
                foreach ($data['volume'] as $v){
                    $tableModel->where('id','=',$v[0])->update(['volume'=>$v[1]]);
                }
            }
            $warehouseModel->allowField(true)->save($data,$data['id']);
            \think\Db::commit();
            exit("1");

        } catch (\Exception $exc) {
            //回滚事务
            \think\Db::rollback();
            exit("3");
        }
    }
    //显示原材料
    public function showMaterial(){

        $this->view->engine->layout(false);

        $search = input('request.search','','trim');

        $material = new Material();

        $modeldata=$material->order('id', 'desc');

        if(!empty($search)){

            $where=array(

                'name' => ['like', "%{$search}%"],

                'finance_num' => ['like', "%{$search}%"],

                'warehouse_num' => ['like', "%{$search}%"],

                'type' => ['like', "%{$search}%"],

            );
            $modeldata=$modeldata->whereor($where);
        }

        //Autocomplete
        $logic = new logicWH();
        $autoquery = $logic->autoComplete($search);

        $this->assign('autoquery',$autoquery);

        $data=$modeldata->paginate($this->pageTotalItem,false,['query' =>request()->param()]);

        $this->assign('eventJS','warehouse');

        $this->assign('list',$data);

        $this->assign('pageDiv', $data->render());

        return $this->fetch();

    }
    //计划入库
    public function setMaterial(){

        if(!request()->isPost()){
            exit("error");
        }

        $logic  = new logicWH();

        $pres = $logic->planIn(input('post.'));

        echo json_encode($pres);
    }
    //添加子仓库确认 该子仓库是否有 东西
    public function addWarehouseCheck(){

        $id = input('post.id');
        $Model = new WHMD;
        $data = $Model->get(['wt_id'=>$id]);

        if(empty($data)){
            exit('no_material');
        }
        exit('have_material');
    }

    //打印库位码至PDF
    public function getShelvesPdf()
    {
        $shelf_code=explode(",",input('post.shelf_code'));
        $whtbModel = new EpWarehouseTable();
        header('Content-type: application/pdf');
        $pdf=new fpdf('p', 'mm', 'A5'); //创建新的FPDF 对象，竖向放纸，单位为毫米，纸张大小A4
        $pdf->SetFont('Courier','I',20); //设置字体样式
        foreach ($shelf_code as $k => $v){
            $whtbid = $whtbModel->where(['name'=>$v])->value('id');
            if (!$whtbid)continue;
//        $pdf->Open(); //开始创建PDF

//        $pdf->Image(ROOT_PATHNEWORDER_IMG.'114-3605952-7075412.png',20,20,0,0); //增加一张图片，文件名为sight.jpg
                $pdf->AddPage(); //增加一页
                if (!file_exists(ROOT_EP_SHELVESCODE_IMG . $whtbid . 'shelves.png')){
                    allshelvesQrCode($whtbid,10,3,$v,'',1);
                }
                $pdf->Image(ROOT_EP_SHELVESCODE_IMG . $whtbid . 'shelves.png',3,3,85,100);
             //输出PDF 到浏览器
        }
        $rand = rand(1,1000000);
        $pdf->Output(ROOT_EP_SHELVESCODE_IMG.$rand.'.pdf','F');

        $url =ROOT_EP_NEW_SHELVESCODE_IMG.$rand.'.pdf';
        return $url;
    }

    //仓库物资列表
    public function materialDetailLists(){

        $search=input('request.search','','trim');
        $status=input('request.status',5,'trim');
        $factory_pid=input('request.factory_pid',5,'trim');
        $factory_sid=input('request.factory_sid',0,'trim');
        $neworder=input('request.neworder',5,'trim');

        if ($factory_pid!=5) {
            $where['wh.factory_pid'] = $factory_pid;
            if ($factory_pid == 0){
                $where['wh.factory_sid'] = $factory_sid;
            }
        }else{
            $where['wh.factory_pid'] = ['in',[0,1,2,3]];
        }

        $material = Db::table('ink_ep_warehouse_materialdetail')
            ->alias('whmd')
            ->join('ink_ep_warehouse_table whtb','whmd.wt_id = whtb.id','left')
            ->join('ink_ep_warehouse wh','whtb.warehouse_id = wh.id','left')
            ->field('wh.factory_pid,whmd.factory_sid,whmd.*')
            ->where($where)
            ->where('whmd.delete_time is null');

        if($status == '5' || $status == ''){
            $modeldata=$material->order('whmd.id', 'desc');
        }else{
            $modeldata=$material->where(['whmd.status'=>$status])->order('whmd.id', 'desc');
        }
        if(!empty($search)){

            $where=array( 'whmd.sku' => ['like', "%{$search}%"]);

            $modeldata=$modeldata->where($where);

        }
//        echo $modeldata->buildSql();die;
        $data=$modeldata->paginate($this->pageTotalItem,false,['query' =>request()->param()]);

            $OrderFacModel = new Orderfactory();
            foreach ($data as $key => $v) {
                $order_id = $v['skuid'];
                $v['orderFactory'] = $OrderFacModel->where(['order_id'=>$order_id,'sign'=>0])->relation('userinfo')->select();//关联查找
                if ($neworder == 0 && $v['orderFactory']){
                    unset($data[$key]);
                }elseif($neworder == 1 && empty($v['orderFactory'])){
                    unset($data[$key]);
                }else{
                    $data[$key] = $v;
                }

            }
        $this->assign('factory_pid',$factory_pid);
        $this->assign('factory_sid',$factory_sid);
        $this->assign('eventJS','warehouse');
        $this->assign('list',$data);
        $this->assign('status',$status);
        $this->assign('neworder',$neworder);
        $this->assign('pageDiv', $data->render());

        $this->assign('currentMenu', array('menu'=>'menu13','nav'=>'nav0'));

        return $this->fetch();
    }

    //获取二维码
    //$text二维码内容,$ab简称
    public function getProStaCodeBar($text, $ab) {
        //barcode_user_code('BCGcode128', $text, 40, 4);
        phpQrCode($text, 4, 1, 1, $ab);
    }
    
    
    
    
    
    //入库保存
    public function inWarehouse(){

        if(!request()->isPost()){
            $this->assign('eventJS','outOrInWarehouse');
            $this->assign('currentMenu', array('menu'=>'menu13','nav'=>'nav8'));
            $wt_name = input('get.wt_name','');
            $wt_id = input('get.wt_id','');

            if ($this->isMobile == 1)
            {
                if ($wt_name && $wt_id){
                    $this->assign('wt_name',$wt_name);
                    $this->assign('wt_id',$wt_id);
                    return $this->fetch('epwarehouse/in_warehouse_mobile_next');
                }
                return $this->fetch('epwarehouse/in_warehouse_mobile');
            }
            if ($wt_name && $wt_id){
                $this->assign('wt_name',$wt_name);
                $this->assign('wt_id',$wt_id);
                return $this->fetch('epwarehouse/in_warehouse_next');
            }
            return  $this->fetch();
        }

        $data = input('post.');
        $unidsku = input('post.sku','');
        $pattern = '/(?=.*[a-z])(?=.*[\d])(?=.*_)/';
        $verify = preg_match($pattern,$unidsku);
        if (!$verify){
            exit('sku格式错误');
        }
        $arraysku = explode('_',$unidsku);
        $arrlen = count($arraysku);
        if ($arrlen > 10) exit('参数错误！');
        if ($arrlen > 3){
            $arraysku[2] = $arraysku[$arrlen - 1];
            $arraysku = array_slice($arraysku,0,3);
            $unidsku = implode('_',$arraysku);
        }
        $data['sku'] = $arraysku[0];
        $data['skuid'] = $arraysku[1];
        $data['unique_id'] = $unidsku;
        $data['count'] = $data['count']?$data['count']:1;
        $houseModel     = new EpWarehousetable();
        $whmdModel       = new WHMD;
        $neworderModel = new \app\admin\model\Order();
        $skuinfo = '';
        $skuinfo = $neworderModel->where(['GdsSku'=>$data['sku']])->find();
        $data['create_time'] = date('Y-m-d H:i:s');
        $data['update_time'] = date('Y-m-d H:i:s');
        if ($skuinfo){
            $data['produce_time'] = isset($skuinfo['SignTimer']);
            $data['ep_img'] = $skuinfo['ImgURL'];
            $data['spec'] = $skuinfo['SpecName'];
        }else{
            $url = 'http://webapi.38420.com/api/Goods/GetGoodsByids';
            $idsarr = 'ids='.$data['skuid'];
            $skuinfo = json_decode(curlPost($url,$idsarr),true);
            if (isset($skuinfo[0])){
                $data['ep_img'] = $skuinfo[0]['ImgPath'];
                $data['spec'] = $skuinfo[0]['SpecName'];
            }
        }

        if(empty($skuinfo)){
            exit('sku不存在！');
        }
        $whm = $whmdModel->get(['wt_id'=>$data['wt_id'],'unique_id'=>$data['unique_id']]);
        $house = $houseModel->get($data['wt_id']);

        if(empty($house)){
            exit('仓库不存在！');
        }
        $fbadeliveryModel = new Fbadelivery();
        $fnaOrderModel = new Fbaorder();
        $fbaorderid = $fnaOrderModel->where(['sku'=>$data['sku']])->value('fba_id');
        $delivery='';
        if ($fbaorderid>0){
            $delivery = $fbadeliveryModel->where('plan_status','in',[0,1,2])->where(['id'=>$fbaorderid])->find();
            if (isset($delivery))$data['status'] = 1;
        }

        // 启动事务
        \think\Db::startTrans();
        try{
            if ($whm){
                if ($delivery){
                    $whm->status = $data['status'];
                    $whm->save();
                }

                $whm->setInc('count',$data['count']);
                $whmd_id = $whm->id;

            }else {
                $wh = new WH();
                $data['factory_sid'] = $wh->where(['id'=>$house->warehouse_id])->value('factory_sid');
                $whmdModel->allowField(['wt_id','wt_name', 'sku', 'ep_img','count','md_id','unique_id','order_id','spec','skuid','create_time','produce_time','status','factory_sid'])->save($data);
                $whmd_id = $whmdModel->id;
            }
            $whmddata = WHMD::get($whmd_id);
            $outInDetails   = new \app\admin\model\EpOutInDetails();

            $outInDetail_data['whmd_id'] =  $whmddata['id'];
            $outInDetail_data['wt_name'] =  $whmddata['wt_name'];
            $outInDetail_data['sku'] =  $whmddata['sku'];
            $outInDetail_data['create_time'] =  time();
            $outInDetail_data['type'] =  '2';//入库
            $outInDetail_data['operator'] =  session('admin_name');
            $outInDetail_data['count'] =  $data['count'];
            $outInDetail_data['epimg'] = $data['ep_img'];

            $outInDetails->save($outInDetail_data);
            \think\Db::commit();
            return $this->jsonArr($whmddata,"入库成功！");
        } catch (\Exception $ex) {
            //回滚事务
            \think\Db::rollback();
            exit($ex);
            return $this->jsonArr("error","操作失败");
        }
        exit('操作成功！');
//        return $this->jsonArr("success","do_success");
    }

    //入库二维码
    public function print_inqrcode(){
        $unique_id = input('request.unique_id');
        $size = input('request.size');
        $arraysku = explode('_',$unique_id);
        $sku = $arraysku[0];
        $sku_id = $arraysku[1];
        if (!$sku_id) return '';
        $url = InWhQrCode($sku, $sku_id, $size,$unique_id);
        header('Content-Disposition:attachment;filename=' . $sku . '.png');
        //header('Content-Length:' . filesize($filename));
        readfile($url);
    }

    //json数组
    public function jsonArr($r, $m){
        return ["result"=>$r,"message"=>$m];
    }

    public function checkWarehouseName(){

        $name =input('post.material_name');

        if(empty($name)) exit("error");

        $model = new EpWarehousetable();

        $data = $model->get(['name'=>$name]);

        if(empty($data)){
            exit("error");
        }

        echo $data['id'];
        
    }
    //出库保存
    public function outWarehouse(){
        if(!request()->isPost()){

            $this->assign('int_type','0');
            $this->assign('eventJS','outOrInWarehouse');
            $this->assign('currentMenu', array('menu'=>'menu13','nav'=>'nav9'));
            if ($this->isMobile == true){
                return  $this->fetch('out_warehouse_mobile');
            }
            return  $this->fetch('out_warehouse');
        }

        $post =input('post.');
        $unique_id = $post['sku'];
        $pattern = '/(?=.*[a-z])(?=.*[\d])(?=.*_)/';
        $verify = preg_match($pattern,$unique_id);
        if (!$verify){
            exit('sku格式错误');
        }
        $arraysku = explode('_',$unique_id);
        $arrlen = count($arraysku);
        if ($arrlen > 10) exit('参数错误！');
        if ($arrlen > 3){
            $arraysku[2] = $arraysku[$arrlen - 1];
            $arraysku = array_slice($arraysku,0,3);
            $unique_id = implode('_',$arraysku);
        }
        $wtmaterialModel = new WHMD;
        $wtmaterialData = $wtmaterialModel->get(['unique_id'=>$unique_id]);

        if(!$wtmaterialData) exit('此sku未入过库或库存用完！');
        if($wtmaterialData['count'] < $post['count']) exit('库存不足，请重新选择出库数量！');

        $new_count = $wtmaterialData['count']-$post['count'];

        //如果出库数量为0 那么改状态 库里面的数据 否则 扣掉相应的出库数量

        \think\Db::startTrans();

        try {
            //备注
            if ($post['memo']!='') {
                $wtmaterialData->memo = $post['memo'];
                $wtmaterialData->save();
            }
            $wtmaterialData->setDec('count',$post['count']);
            if ($new_count==0){
                $wtmaterialData->delete();
            }
            $outInDetails   = new \app\admin\model\EpOutInDetails();

            $outInDetail_data['whmd_id'] =  $wtmaterialData['id'];
            $outInDetail_data['wt_name'] =  $wtmaterialData['wt_name'];
            $outInDetail_data['sku'] =  $wtmaterialData['sku'];
            $outInDetail_data['create_time'] =  time();
            $outInDetail_data['type'] =  '1';//1出库2入库
            $outInDetail_data['operator'] =  session('admin_name');
            $outInDetail_data['count'] =  $post['count'];
            $outInDetail_data['epimg'] = $wtmaterialData['ep_img'];
            $outInDetail_data['memo'] = $wtmaterialData['memo'];

            $outInDetails->save($outInDetail_data);
            \think\Db::commit();
            $res= WHMD::withTrashed()->find(['id'=>$wtmaterialData['id']]);
            return $this->jsonArr($res,'出库成功！！！');

        } catch (\Exception $exc) {
            \think\Db::rollback();
            exit('出库失败');
        }
        exit('出库成功！');
    }
    //出入库记录
    public function outInDetailLists(){

        $dataArr = logicWH::sessionQueryTime('outin',input('request.'));

        $search = input('request.search','','trim');
        $type = input('request.type','','trim');
        $operator = input('request.operator','','trim');

        $detailModel = new EpOutInDetails();
        $modeldata = $detailModel->order('id', 'desc');

        if(!empty($type)){
            $where['type'] = $type;
        }

        if(!empty($operator)){
            $where['operator'] = ['like', "%{$operator}%"];
        }

        $where['create_time'] = ['between',[strtotime($dataArr['start_date']),strtotime($dataArr['end_date'])+86400]];

        $modeldata=$modeldata->where($where);

        if(!empty($search)){

            $where=array(

                'sku' => ['like', "%{$search}%"],

                'wt_name' => ['like', "%{$search}%"],

            );

            $modeldata=$modeldata->where(function($query)use($where){

                $query->whereOr($where);

            });

        }

        $data=$modeldata->paginate($this->pageTotalItem,false,['query' =>request()->param()]);
        $this->assign('eventJS','warehouse');
        $this->assign('search',$search);

        $this->assign('type',$type);

        $this->assign('operator',$operator);

        $this->assign('date',$dataArr);

        $this->assign('data',$data);

        $this->assign('pageDiv', $data->render());

        $this->assign('currentMenu',array('menu'=>'menu13','nav'=>'nav3'));

        return $this->fetch();

    }

    public function printLists($id){

        $wtmdModel = new WHMD;

        $wtmdData = $wtmdModel->where(['status'=>1])->select();

        $this->assign('wtmDatas',$wtmdData);

        return $this->fetch();

    }

    /**
     * @return mixed
     * 配货单列表
     *
     */
    public function disLists()
    {
//        $whmd = new WHMD;
//
//        $search=input('request.search','','trim');
//
//        $modeldata = $whmd->where(['in_dislist'=>WHMD::IN_DISLIST_YES]);
//
//        if(!empty($search)){
//
//            $where=array( 'sku' => ['like', "%{$search}%"]);
//
//            $modeldata=$modeldata->whereor($where);
//
//        }
//
//        $disdata = $modeldata->field('id,sku, skuid,spec,unique_id, ep_img,in_dislist')->order('update_time desc')->group('sku')
//            ->paginate($this->pageTotalItem,false,['query'=>request()->param()]);

        $dislist = new EpDislist();
        $search=input('request.search','','trim');

        if(!empty($search)){

            $where=array( 'sku' => ['like', "%{$search}%"]);

            $dislist=$dislist->whereor($where);

        }

        $disdata = $dislist->with('whmd,neworder')->order('id desc')->paginate($this->pageTotalItem,false,['query'=>request()->param()]);
        foreach ($disdata as $K =>$v){
            if (isset($v->neworder->OrdNum)){
                if (!file_exists(ROOT_PATHNEWORDER_IMG.$v->neworder->OrdNum.'.png')){
                    orderQrcode($v->neworder->OrdNum,2,1);
                }
            }
        }
        $this->assign('eventJS','warehouse');

        $this->assign('data',$disdata);

        $this->assign('pageDiv', $disdata->render());

        $this->assign('currentMenu',array('menu'=>'menu13','nav'=>'nav3'));
        return $this->fetch();
    }

    public function getOrdCodeBar($text) {
        //barcode_user_code('BCGcode128', $text, 40, 4);
        orderQrcode($text, 2, 1);
    }


    //修改付费区域
    public function editpay(){

        $ids=explode(",",input('post.id'));
        $whmdModel = new WHMD();
        Db::startTrans();
        try{
            $whmdres = $whmdModel->where('id','in',$ids)->select();
            foreach ($whmdres as $k => $v){
                $updatenum = 1;
                if ($v->factory_sid == 1){
                    $updatenum = 2;
                }
                $v->factory_sid = $updatenum;
                $v->save();
            }
            \think\Db::commit();
            echo true;exit;
        }catch (\Exception $ex){
            Db::rollback();
            exit('操作失败！');
        }
    }
    
    /**
     * 加入配货单
     */
    public function adddisLists()
    {
        if(!request()->isPost()) exit("error");
        $addtype = input('post.addtype','');
        $ids=explode(",",input('post.id'));

        \think\Db::startTrans();
        try {
            if ($addtype == 'orderlist'){
                $newOrderSku = \app\admin\model\Order::where('id','in',$ids)->column('id','GdsSku');
                foreach ($newOrderSku as $sku => $neworderid){
                    $disModel = new EpDislist();
                    $dis = $disModel::where(['neworder_id'=>$neworderid])->find();
                    if (!$dis){
                        $disModel->neworder_id = $neworderid;
                        $disModel->sku = $sku;
                        $disModel->save();
                    }
                }
            }else{
                $whmdSku = WHMD::where('id','in',$ids)->column('sku');
                foreach ($whmdSku as $k => $sku){
                    $disModel = new EpDislist();
                    $dis = $disModel::where(['sku'=>$sku])->find();
                    if (!$dis){
                        $disModel->neworder_id = 0;
                        $disModel->sku = $sku;
                        $disModel->save();
                    }
                }
            }
//            foreach ($ids as $k=>$v){
//
//
//                $wmd = WHMD::where(['id'=>$v])->update(['in_dislist'=>WHMD::IN_DISLIST_YES,'update_time'=>date('Y-m-d H:i:s')]);
////                if (empty($wmd)){
////                    exit( "增加配货单成功！");
////                }
//            }

            \think\Db::commit();
            echo true;exit;

        } catch (\Exception $ex) {
            \think\Db::rollback();
            exit( "加入配货单失败，请重新选中！");
        }
    }

    /**
     * 取消配货单
     *
     */
    public function canceldisLists()
    {
        if(!request()->isPost()) exit("error");

        $sku=explode(",",input('post.id'));
        \think\Db::startTrans();
        try {
            foreach ($sku as $k=>$v){
                $dislist = EpDislist::where(['sku'=>$v])->delete();
                if (empty($dislist)){
                    exit( "参数错误，请重新选中！");
                }
            }
            \think\Db::commit();
            echo true;exit;

        } catch (\Exception $ex) {
            \think\Db::rollback();
            exit( "取消配货单失败，请重新选中！");
        }
    }


    public function materialDetailDo($action){

        if(request()->isPost()){
            $id = input('post.id');
            $wtm_id = input('post.wtm_id');

            $wtmModel       = new Wtablematerial();
            $wmdModel       = new WHMD;
            $wtableModel    = new EpWarehousetable();
            $materialModel  = new Material();

            //edit
            if(!empty($id)){

                $material=  Materialdetail::get($id);

            }else{
                //add
                $material = new Materialdetail();
                $saveData['create_time']=date('Y-m-d H:i:s');
            }

            if(!empty(input('post.warehouse_num'))){
                $wtableData = $wtableModel->get(input('post.warehouse_num'));
                $saveData['warehouse_num'] = $wtableData['name'];
                $saveData['wt_id'] = input('post.warehouse_num');
            }else{

                $saveData['wt_id'] = '';
                $saveData['warehouse_num'] = '';
                $wtableData['name'] = '';
                $wtm_id = '';
            }

            $material_id = input('post.material_id');
            $count = input('post.count');
            $materialData = $materialModel->get($material_id);

            $saveData['finance_num'] = $materialData['finance_num'];
            $saveData['att'] = $materialData['att'];
            $saveData['company'] = $materialData['company'];
            $saveData['money'] = $materialData['money'];
            $saveData['type'] = $materialData['type'];
            $saveData['choice'] = $materialData['choice'];
            $saveData['size'] = $materialData['size'];
            $saveData['name'] = $materialData['name'];
            $saveData['display'] = 1;
            $saveData['count'] = $count;
            $saveData['material_id'] = input('post.material_id');
            $saveData['upplier_id'] = input('post.upplier_id');
            $saveData['purchases_date'] = input('post.purchases_date');
            $saveData['status'] = input('post.status');
            $whmd_status = input('post.whmd_status');
            $create_num = input('post.create_num');

            \think\Db::startTrans();

            try {
                //edit
                //编辑要修改 1.材料明细的库位名称跟库位ID;2.库位关联表（EpWarehouseMaterialdetail）的修改
                if(empty($create_num)){
                    //0 为规划 1 已规划 2 已入库 已入库的就不能改为其他状态了
                    if($whmd_status != 2){
                        // 如果库位为空 就 未规划 否则 已规划
                        $update_arr = ['status'=>empty($wtm_id)?0:1,'wt_name'=>$wtableData['name'],'wtm_id'=>$wtm_id,'count'=>$count];
                    }else{
                        $update_arr = ['wt_name'=>$wtableData['name'],'wtm_id'=>$wtm_id,'count'=>$count];
                    }
                    $wmdModel->where(['md_id'=>$id])->update($update_arr);
                    $material->where(['id'=>$id])->update($saveData);

                }else{
                    //add
                    $materialsaveList = [];
                    $wmdsaveList = [];

                    for($i=1; $i <= $create_num; $i++){
                        $materialsaveList[] = $saveData;
                    }

                    $insertids = $material->isUpdate(false)->saveAll($materialsaveList);

                    $wtmData = $wtmModel->get($wtm_id);
                    $wmdData['wtm_id'] = $wtm_id;
                    $wmdData['count'] = $count;
                    $wmdData['wtm_name'] = $materialData['name'];
                    $wmdData['unit'] = $materialData['company'];
                    $wmdData['create_time'] = date('Y-m-d H:i:s');
                    $wmdData['in_type'] = $count>1?1:2;
                    $wmdData['status'] = empty($wtm_id)?0:1 ;
                    $wmdData['wt_name'] = $wtmData['wt_name'];

                    foreach ($insertids as $k=>$v){
                        $wmdData['md_id'] = $v['id'];
                        $wmdsaveList[] = $wmdData;
                    }

                    $wmdModel->isUpdate(false)->saveAll($wmdsaveList);

                }

                \think\Db::commit();
                echo true;exit;


            } catch (\Exception $exc) {
                echo $exc->getTraceAsString();
                \think\Db::rollback();
                exit('操作失败');

            }

        }
        //view edit
        $params = request()->param();
        if($action == 'edit' && isset($params['id']) && is_numeric($params['id'])){

            $sqlStr = "select md.* ,m.name as mname ,u.company as upplier_name ,whmd.status as wh_status from "
                . "ink_material_detail md  "
                . "left join ink_material m on md.material_id=m.id "
                . "left join ink_warehouse_materialdetail whmd on md.id=whmd.md_id "
                . "left join ink_wtable_material  wtm on whmd.wtm_id = wtm.id  "
                . "left join ink_upplier  u on md.upplier_id = u.id  "
                . "left join ink_warehouse_table wt on wtm.wt_id = wt.id where md.id=".$params['id']." limit 1";

            $data = \think\Db::query($sqlStr);

            $this->assign('data',$data[0]);

        }  else {
            //view add
            $data=array('id'=>'',
                'type'=>'','finance_num'=>'','warehouse_num'=>'','choice'=>'',
                'name'=>'','size'=>'','att'=>'','company'=>'','display'=>'1',
                'money'=>'','create_timer'=>'','count'=>'','mname'=>'','material_id'=>'','upplier_id'=>'',
                'company'=>'','warehouse_num'=>'','purchases_date'=>'','wt_id'=>'','upplier_name'=>'','status'=>'1','wh_status'=>'');

            $this->assign('data',$data);
        }

        $this->assign('currentMenu', array('menu'=>'menu8','nav'=>'nav2'));
        $this->assign('eventJS','warehousetable');

        return $this->fetch("warehouse/material_detail_do");

    }

    public function materialDetailDel(){

        if(!request()->isPost()) exit("error");

        $ids=explode(",",input('post.id'));
        \think\Db::startTrans();
        try {
            foreach ($ids as $k=>$v){

                $wmd = WHMD::get(['id'=>$v]);

                if($wmd['status'] == '2'){
                    \think\Db::rollback();
                    exit("删除失败，是计划中物资，请重新操作！" );
                }
                $wmd->delete();
            }

            \think\Db::commit();
            echo true;exit;

        } catch (\Exception $ex) {
            \think\Db::rollback();
            exit( "删除失败，请重新删除！");

        }

    }
    public function showWarehouse($m_id){

        if(empty($m_id) || !is_numeric($m_id)) exit('error');

        $materialDetailModel  = new Materialdetail();

        $materialDetailData = $materialDetailModel->get($m_id);

        if(empty($materialDetailData)) exit('error');

        $sqlStr = "  select group_concat(tall.warehouse_id) res  from 
             (select wt_big.* ,if( wt_small.name is null,wt_big.name,wt_small.name) as sname,if( wt_small.id is null,wt_big.id,wt_small.id) as sid from 
                        (select * from ink_warehouse_table where parents_id =0  ) wt_big 
             left  join (select * from ink_warehouse_table where warehouse_id =0 )wt_small  on wt_big.id=wt_small.parents_id)tall
             inner join ink_wtable_material wtm on tall.sid = wtm.wt_id where wtm.status =1 and m_id=".$materialDetailData['material_id'];

        $res = \think\Db::query($sqlStr);

        $warehouseModel  = new WH();

        $materialModel  = new Wtablematerial();

        $data = $warehouseModel->where('id','in',explode(",",$res[0]['res']))->select();

        $materiallist = $materialModel->where(['m_id'=>$materialDetailData['material_id'],'status'=>1])->select();

        $this->assign('name', $materialDetailData['name']);

        $this->assign('m_id', $m_id);

        $this->assign('list', $data);

        $this->assign('mlist', $materiallist);

        $this->assign('currentMenu', array('menu'=>'menu8','nav'=>'nav2'));

        $this->assign('eventJS','warehousetable');

        return  $this->fetch();

    }
    public function getMaterials($type){
        $params = request()->param();
        $sku = trim($params['sku']);
        $whmdModel = new WHMD();
        $data = $whmdModel->where(['sku'=>$sku])->select();

        $this->view->engine->layout(false);

        $this->assign('eventJS','warehouse');

        $this->assign('data',$data);

        $this->assign('type',$type);

        return $this->fetch();

    }
    public function putMaterialToWarehouse(){

        if(!request()->isPost()){
            exit('error');
        }

        $model      = new WHMD;
        $wtmModel   = new Wtablematerial();
        $materialDetailModel  = new Materialdetail();

        $post = input('post.');

        $count = $post['count'];
        $m_id = $post['m_id'];
        $wtm_id = $post['wtm_id'];

        \think\Db::startTrans();

        try {

            $materialDetailData = $materialDetailModel->get($m_id);
            $wtmData = $wtmModel->get($wtm_id);

            $model->wtm_id = $wtm_id;
            $model->md_id = $m_id;
            $model->count = $count;
            $model->wtm_name = $materialDetailData['name'];
            $model->unit = $materialDetailData['company'];
            $model->create_time = date('Y-m-d H:i:s');
            $model->in_type = $count>1?1:2;
            $model->status = 1;
            $model->wt_name = $wtmData['wt_name'];

            $model->save();

            $newCount = $materialDetailData['count'] - $count;

            $updateArr =  $newCount == '0'  ? ['count'=>$newCount]:['count'=>$newCount];

            $materialDetailData->where(['id'=>$m_id])->update($updateArr);

            \think\Db::commit();
            exit('操作成功');
        } catch (\Exception $ex) {
            echo $ex->getTraceAsString();
            \think\Db::rollback();
            exit('操作失败');
        }

    }
    public function bathMaterialToWarehouse(){

        $post = input('post.');

        $model  = new WHMD;

        $materialDetailModel  = new Materialdetail();

        \think\Db::startTrans();

        try {

            if(!empty($post['o'])){

                foreach ($post['o'] as $k=>$v){

                    $data = $model->get($v['id']);

                    $updateArr = ( $v['count'] == '0' ) ? ['count'=> $v['count'],'delete_time'=>date('Y-m-d H:i:s',time())]:['count'=> $v['count']];

                    $model->where(['id'=>$v['id']])->update($updateArr);

                    $mdata = $materialDetailModel->get($data['md_id']);

                    $newcount = $mdata['count'] + ($data['count'] - $v['count']);

                    $materialDetailModel->where(['id'=>$data['md_id']])->update(['count'=> $newcount]);

                }
            }
            \think\Db::commit();
            exit('操作成功');

        } catch (Exception $ex) {

            echo $ex->getTraceAsString();
            \think\Db::rollback();
            exit('操作失败');

        }

    }
    //显示原材料明细
    public function showMaterialDetail(){

        $params = request()->param();

        $this->view->engine->layout(false);

        if(!isset($params['wtm_id']) || empty($params['wtm_id']) || !is_numeric($params['wtm_id'])){
            exit('error');
        }

        $sqlStr = "select * from ink_wtable_material wtm inner join ink_material_detail md on wtm.m_id = md.material_id where md.delete_time is null and wtm.id = ".$params['wtm_id'];

        $res = \think\Db::query($sqlStr);

        $this->assign('eventJS','warehouse');

        $this->assign('list',$res);

        $this->assign('wtm_id',  !isset($params['wtm_id'])?'':$params['wtm_id']);

        return $this->fetch();

    }

    public function getMaterialsDetail($material_id){

        $this->view->engine->layout(false);

        $materiald = new Materialdetail();

        $material = new Material();

        $data=$materiald::with('upplier')->order('id', 'desc')->where(['material_id'=>$material_id])->select();

        $material_one = $material->get($material_id);

        $sqlStr = "SELECT   if(concat( sum(md.count),md.company) is null ,0,concat( sum(md.count),md.company)) as cc  "
            . "from ink_material_detail md where delete_time is null and status = 2 and material_id = ".$material_id;

        $sqlStrQ = "SELECT   if(concat( sum(md.count),md.company) is null,0,concat( sum(md.count),md.company)) as cc  "
            . "from ink_material_detail md where delete_time is null and status = 1 and material_id = ".$material_id;

        $questionData  = \think\Db::query($sqlStr);

        $nquestionData  = \think\Db::query($sqlStrQ);

        $this->assign('questionData',$questionData[0]);

        $this->assign('nquestionData',$nquestionData[0]);

        $this->assign('warehouse_num',$material_one['warehouse_num']);

        $this->assign('count',count($data));

        $this->assign('eventJS','warehouse');

        $this->assign('list',$data);

        return $this->fetch();

    }
    //材料明细供应商列表
    public function showMaterialDetailUpplier(){

        $params = request()->param();
        $this->view->engine->layout(false);
        $search=input('request.search','','trim');

        if(isset($params['material_id']) && !empty($params['material_id']) && is_numeric($params['material_id'])){
            $material_id = $params['material_id'];
            $material =  Material::get($material_id);
            $this->assign('data',$material);
        }else{
            $upplier = new Upplier();
            $uppliers = $upplier->where('display','=','1');
            if(!empty($search)){

                $where=array(
                    'company' => ['like', "%{$search}%"],
                    'contacts' => ['like', "%{$search}%"],
                    'adress' => ['like', "%{$search}%"],
                );
                $uppliers=$uppliers->where(function($query)use($where){
                    $query->whereOr($where);
                });
            }
            //autocomplete 自动填充
            $logic = new logicWH();
            $autoquery = $logic->autoComplete($search);

            $this->assign('autoquery',$autoquery);

            $uppliers = $uppliers->select();
            $upplierlist['materialupplier'] = $uppliers;
            $this->assign('data',$upplierlist);
        }
        $this->assign('eventJS','warehouse');
        return $this->fetch();
    }
    //根据原材料id 获取 该原材料的仓位
    public function getMaterialBelongWarehouse(){

        $id      = input('post.id');
        $logic   = new logicWH();
        $res     = $logic->getMaterialBelongWarehouse($id);
        echo json_encode($res);
    }
    //获取物资信息
    public function getmaterialDetail(){

        $sku = input('post.sku');
        $wtmModel    = new WHMD;
        $wtmData     = $wtmModel->get(['unique_id'=>$sku]);
        echo json_encode($wtmData);

    }
    //移库 没用到。。。
    public function move(){

        $wmd_idA = 137;
        $wmd_idB = 147;

        $whLogic = new \app\admin\logic\Warehouse();
        //检验数据
        $logicRes = $whLogic->moveCheck($wmd_idA,$wmd_idB);

        if($logicRes['res'] != "success"){
            exit($logicRes['res']);
        }

        $checkRes = $whLogic->move($logicRes['id']);
        echo json_encode($checkRes);

    }

}