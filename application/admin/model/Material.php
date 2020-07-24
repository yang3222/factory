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
class Material extends Model{
    //put your code here
    use SoftDelete;
    protected $autoWriteTimestamp = 'datetime';
    protected $deleteTime = 'delete_time';//必须
    protected $updateTime = false;//必须
    protected $field = true;
    protected $name='material';
    protected $type = [
        'create_timer'    => 'datetime:Y-m-d',
    ];
    protected $insert = ['display' => '1','warehouse_num_prev'=>'','warehouse_num'=>''];
   
    public function materialupplierHas(){
        return $this->hasMany('material_upplier','material_id','id');
    }
    public function materialupplier(){
        //(要查询的表，中间表，中间表里面对应要查询表的id字段名,中间表里面要对应现在模型表的id名称)
        return $this->belongsToMany('Upplier', 'material_upplier','upplier_id','material_id');
    }
    
}
