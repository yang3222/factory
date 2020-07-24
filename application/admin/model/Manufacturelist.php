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
class Manufacturelist extends Model{
    //put your code here
    use SoftDelete;
    protected $autoWriteTimestamp = 'datetime';
    protected $deleteTime = 'delete_time';//必须
    protected $updateTime = false;//必须
    protected $field = true;
    protected $name='list_manufacture';
    protected $type = [
        'create_time'    => 'datetime:Y-m-d',
    ];
    protected $insert = ['loss' => '0','print_size'=>'0','dosage'=>'0'];
    public function material(){
        return $this->hasOne('material','id','material_id');
    }
    public function manufacture(){
        return $this->hasOne('manufacture','id','list_id');
    }
}
