<?php
namespace app\user\model;
use \think\Model;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Login
 *
 * @author 27532
 */
class Order extends Model{
    //put your code here
    protected $name='new_order';
    protected $type = [
       // 'AmzTimer'    => 'datetime:Y-m-d',
       // 'SignTimer'   =>'datetime:Y-m-d',
    ];
    public function orderfactroy(){
        return $this->hasMany('orderfactory','order_id','id')->order('working_type asc');
    }
    public function orderproduct(){
        return $this->hasOne('product','product_id','product_id');
    }
}
