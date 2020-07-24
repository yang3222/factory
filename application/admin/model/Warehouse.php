<?php
namespace app\admin\model;
use \think\Model;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Product
 *
 * @author wujiabin 
 */
class Warehouse extends Model{
    //put your code here
    protected $name='warehouse';
    protected $type = [
        'create_time'    => 'datetime:Y-m-d h:i:s',
    ];
   public function warehouseTables(){
        return $this->hasMany('warehousetable','warehouse_id','id');
    }
}
