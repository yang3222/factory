<?php
namespace app\index\model;
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
class Productfactory extends Model{
    //put your code here
    protected $name='product_factory';
    public function product(){
        return $this->hasOne('product','id','product_id');
    }
}
