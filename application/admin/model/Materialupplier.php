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
class Materialupplier extends Model{
    //put your code here
    protected $name='material_upplier';
    public function material(){
        return $this->hasOne('material','id','material_id');
    }
    
   
}
