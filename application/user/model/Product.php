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
class Product extends Model{
    //put your code here
    protected $name='product';
    protected $type = [
        //'Timer'    => 'datetime:Y-m-d',
    ];
    protected $insert = ['Catalog' => '1','sort'=>'0','display'=>'1'];
    public function productsize(){
        return $this->hasMany('productsize','product_id','id');
    }
    public function productfactroyHas(){
        return $this->hasMany('productfactory','product_id','id');
    }
    public function productfactroy(){
        //(要查询的表，中间表，中间表里面对应要查询表的id字段名,中间表里面要对应现在模型表的id名称)
        return $this->belongsToMany('User', 'product_factory','factory_id','product_id')->order('working_type asc');
    }
}
