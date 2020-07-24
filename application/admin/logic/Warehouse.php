<?php
namespace app\admin\logic;

use app\admin\model\EpWarehouseMaterialdetail;
use app\admin\model\Materialdetail;
use \app\admin\model\WarehouseMaterialdetail;
use app\admin\model\Warehousetable;
use app\admin\model\Wtablematerial;
use \app\admin\model\Material;


/**
 * 仓库的逻辑层
 *
 * @author wujiabin 
 */
class Warehouse {
   //移库前检查
    public function moveCheck($wmd_idA, $wmd_idB){
        
        $res = ['res'=>'success','id'=>[]];
        
        $sql = "SELECT md.material_id,whmd.id,whmd.wtm_id,whmd.wt_name,whmd.wtm_name,whmd.md_id,
            whmd.`count` AS whmd_count,md.warehouse_num,md.name,md.wt_id,md.`count`
            from  ink_warehouse_materialdetail whmd  inner join ink_material_detail md on whmd.md_id = md.id
            where whmd.status=2 and whmd.delete_time is null and md.delete_time is null 
            and whmd.id in (".$wmd_idA.",".$wmd_idB.")";
        
        $wtData = \think\Db::query($sql); 
        
        if(count($wtData) != 2 || $wtData[0]['material_id'] != $wtData[1]['material_id'] ){
            $res['res'] = "错误";
            return $res;
        }
       
        $res['id'] = $wtData;
        
        return $res;
    }
    //移库
    public function move($params){
        
        $wmdModel = new WarehouseMaterialdetail();
        $mdModel = new Materialdetail();
        
        $res0 = $this->getWtName($params[0]['md_id']);
        $res1 = $this->getWtName($params[1]['md_id']);
      
        // 启动事务
        \think\Db::startTrans();
        try {
            $wmdModel->where('id','=',$params[0]['id'])->update(['wtm_id'=>$params[1]['wtm_id'],'wt_name'=>$res1['name']]);
            $wmdModel->where('id','=',$params[1]['id'])->update(['wtm_id'=>$params[0]['wtm_id'],'wt_name'=>$res0['name']]);
            
            $mdModel->where('id','=',$params[0]['md_id'])->update(['warehouse_num'=>$res1['name'],'wt_id'=>$res1['id']]);
            $mdModel->where('id','=',$params[1]['md_id'])->update(['warehouse_num'=>$res0['name'],'wt_id'=>$res0['id']]);
            
            \think\Db::commit();
            return $this->jsonArr("success","do_success");
        } catch (\Exception $ex) {
            //回滚事务
            \think\Db::rollback();
            return $this->jsonArr("error","do_error");
        }
        
    }
    public function getWtName($md_id){
        
        $sql = "select wt.name,wt.id from ink_material_detail md inner join 
            ink_warehouse_materialdetail whmd on md.id = whmd.md_id inner join
            ink_wtable_material wtm on whmd.wtm_id = wtm.id inner join 
            ink_warehouse_table wt on wtm.wt_id = wt.id  where md.delete_time is null 
            and whmd.delete_time is null and wtm.delete_time is null and wt.delete_time is null
            and md.id= ".$md_id;
        $wtData = \think\Db::query($sql); 
        return $wtData[0];
    }
    // 整形转字符串 eg: 整形 9 转化 成 09 字符串
    static function int2twostr($int){
        return strlen(strval($int))==2 ? $int : '0'.strval($int);
    }
    public function getMaterialBelongWarehouse($id){
       $sqlStr = "SELECT wtm.id as wtm_id ,wt.name,wt.id from ink_wtable_material wtm  "
               . "inner join ink_warehouse_table wt on wtm.wt_id = wt.id  where wtm.m_id = ".$id;
       return \think\Db::query($sqlStr);
    }
    //自动填充
    public function autoComplete($search){
        $autoquery = session('autoquery')==""? array():session('autoquery');
        if(!empty($search)){
            $autoquery[$search] = $search;
            session('autoquery',$autoquery);
        }
        return $autoquery;
    }
    //计划入库
    public function planIn($data){
        
            $houseModel     = new Warehousetable();    
            $wtmModel       = new Wtablematerial(); 
            $materialModel  = new Material(); 
            
            $house = $houseModel->get($data['wt_id']);
            if(empty($house)){
                return $this->jsonArr("error","stock_no_exist");
            }
            
            $wtmData = $wtmModel->get(['wt_id'=>$data['wt_id']]);
            if(!empty($wtmData)){
                return $this->jsonArr("error","exist");
            }
            
            $detail = $materialModel->get($data['m_id']);
            if(empty($detail)){
                return $this->jsonArr("error","material_no_exist");
            }
            
            $data['create_time']    = date('y-m-d h:i: s');
            $data['wt_name']        = $house['name'];
            
            // 启动事务
            \think\Db::startTrans();
            try{
                
                $wtmModel->save($data);
                //更新库位
                $this->updateWarehousenum($detail['id']);
                \think\Db::commit();
                
             } catch (\Exception $ex) {
                //回滚事务
                \think\Db::rollback();
                return $this->jsonArr("error","do_error");
            }
            
           return $this->jsonArr("success","do_success");
            
    }
    //移除计划入库
    public function delPlan($id){
        
        $wtableModel = new Wtablematerial();
        $wtmdModel = new WarehouseMaterialdetail();
        
        $material = $wtableModel::get(['id'=>$id]);
        if(empty($material)){ 
            return $this->jsonArr("error","no_material");
        }
        //是否有材料
        $wtmdData = $wtmdModel->where(['wtm_id'=>$id])->select();
        if(!empty($wtmdData)){
            return $this->jsonArr("error","have_materials");
        }
        
        // 启动事务
        \think\Db::startTrans();
        try{
            $material->delete();
            $this->updateWarehousenum($material['m_id']);
            \think\Db::commit();
        } catch (Exception $ex) {
            //回滚事务
            \think\Db::rollback();
            return $this->jsonArr("error","do_error");
        }
        
        return $this->jsonArr("success","do_success");
    }

    //移除入库的成品物资
    public function epdelPlan($id){

        $wtmdModel = new EpWarehouseMaterialdetail();

        $res = $wtmdModel::destroy($id);

        if ($res==true){
            return $this->jsonArr("success","do_success");
        }
        return $this->jsonArr("error","do_error");
    }
    //json数组
    public function jsonArr($r, $m){
        return ["result"=>$r,"message"=>$m];  
    }
    static function sessionQueryTime($type,$param){
        
        //如果session时间空 就最近7天
        $startStr = 'admin_'.$type.'_start_time';
        $endStr = 'admin_'.$type.'_end_time';
        $timeTypeStr = $type.'_time_type';
        
        $startData = session($startStr)==""?date('Y-m-d',strtotime('-6 day')):session($startStr);
        $endData = session($endStr)==""?date('Y-m-d'):session($endStr);
       
        $startData = !empty($param['start_date'])?$param['start_date']:$startData; 
        $endData = !empty($param['end_date'])?$param['end_date']:$endData;
       
        //设置时间类型保存到session
        session($timeTypeStr,isset($param['sdate'])?$param['sdate']:'');
        
        //如果不是自定义,即 最近几天， 
        if(session($timeTypeStr) != 'custom'){
            
            //算出上次session时间减去现在时间 的差
           $times = strtotime(date('Y-m-d'))-strtotime($endData);
           //再赋值给
           $startData = date('Y-m-d',strtotime($startData)+$times);
           $endData =date('Y-m-d',strtotime($endData)+$times);
        }
        
        session($startStr,  $startData);
        session($endStr,    $endData);
       
        return ['start_date'=>$startData,'end_date'=>$endData];
        
    }
    //跟新库位
    public function updateWarehousenum($material_id){
        
        $warehouse_num = \think\Db::query("select group_concat( distinct wt.name separator '-')wt_name from ink_wtable_material wtm  "
                . "left join ink_warehouse_table wt  on wtm.wt_id = wt.id  where  delete_time is null and wtm.m_id =".$material_id);
        
        \think\Db::query("update ink_material set warehouse_num = '".$warehouse_num[0]['wt_name'] ."' where id = ".$material_id);
        
        
    }
            
    
}
