<?php
namespace app\admin\model;
use \think\Model;
use traits\model\SoftDelete;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class FbaCodePicking extends Model{
    //put your code here
    use SoftDelete;
    protected $autoWriteTimestamp = 'datetime';
    protected $deleteTime = 'delete_time';//必须
    protected $updateTime = false;//必须
    protected $field = true;
    protected $name='fba_code_picking';
    protected $type = [

    ];

    public function fbaOrders(){
        return $this->hasOne('fbaorder','id','fba_order_id');
    }

    public function fbaBoxLabel(){
        return $this->hasOne('fba_box_label','id','fba_box_id');
    }


}
