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
 * @author 27532
 */
class Productmaterialforecast extends Model{
    //put your code here
    protected $name='product_materialforecast';
    protected $type = [
        // 'AmzTimer'    => 'datetime:Y-m-d',
        // 'SignTimer'   =>'datetime:Y-m-d',
    ];
    /*public function orderfactroy(){
        return $this->hasMany('orderfactory','order_id','id')->order('working_type asc');
    }
    public function orderproduct(){
        return $this->hasOne('product','product_id','product_id');
    }*/
}
