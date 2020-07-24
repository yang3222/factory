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
 * @author wujiabin 
 */
class Wtablematerial extends Model{
    //put your code here
    protected $name='wtable_material';
    
    protected $type = [
        'create_time'    => 'datetime:Y-m-d h:i:s',
    ];
    public function warehousetable(){
        return $this->hasOne('Warehousetable','id','wt_id');
    }
    
    
}
