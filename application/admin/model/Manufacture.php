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
class Manufacture extends Model{
    //put your code here
    use SoftDelete;
    protected $autoWriteTimestamp = 'datetime';
    protected $deleteTime = 'delete_time';//必须
    protected $updateTime = false;//必须
    protected $field = true;
    protected $name='production_list';
    protected $type = [
        'create_time'    => 'datetime:Y-m-d',
    ];
    protected $insert = ['display' => '1'];
    public function userinfo(){
        return $this->hasOne('userinfo','user_id','user_id');
    }
    public function productsize(){
        return $this->hasOne('productsize','id','size_id');
    }
    public function product(){
        return $this->hasOne('product','id','product_id');
    }
}
