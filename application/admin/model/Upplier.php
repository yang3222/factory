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
class Upplier extends Model{
    //put your code here
    use SoftDelete;
    protected $autoWriteTimestamp = 'datetime';
    protected $deleteTime = 'delete_time';//必须
    protected $updateTime = false;//必须
    protected $field = true;
    protected $name='upplier';
    protected $type = [
        //'create_timer'    => 'datetime:Y-m-d',
    ];
    protected $insert = ['display' => '1'];

    public function auditsupplier(){
        return $this->hasOne('audit','supplier_id','id');
    }
    
}
