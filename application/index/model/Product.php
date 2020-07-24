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
class Product extends Model{
    //put your code here
    protected $name='product';
    public function manufacture(){
        return $this->hasOne('manufacture','product_id','id')->where('display','=','1');
    }
    public function productfactroyHas(){
        return $this->hasMany('productfactory','product_id','id');
    }
}
