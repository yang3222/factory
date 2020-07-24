<?php
namespace app\admin\model;
use \think\Model;
use traits\model\SoftDelete;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Product
 *
 * @author 27532
 */
class Materialdetail extends Model{
   
   use SoftDelete;
   
   protected $name='material_detail';
   protected $autoWriteTimestamp = 'datetime';
   protected $deleteTime = 'delete_time';//必须
   protected $updateTime = false;//必须
   protected $type = [
        'purchases_date'    => 'datetime:Y-m-d',
    ];
   //所属材料
   public function material(){
        return $this->hasOne('material','id','material_id');
    }
    //供应商
    public function upplier(){
        return $this->hasOne('upplier','id','upplier_id');
    }
    
    public function warehouses(){
        return $this->hasMany('wtable_material','m_id','material_id');
    }
    
    public function whmdetail(){
        return $this->hasOne('warehouse_materialdetail','md_id','id');
    }
    public function getWhmdetailByWhere($where,$haswhere){
        
    }
   
}
