<?php
namespace app\user\model;
use \think\Model;

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
class Orderfactory extends Model{
    //put your code here
    protected $name='order_factory';
    protected $type = [
        //'pro_time'    => 'datetime:Y-m-d',
        //'library_time'    => 'datetime:Y-m-d',
    ];
    public function userinfo(){
        return $this->hasOne('userinfo','user_id','factory_id');
    }
}
