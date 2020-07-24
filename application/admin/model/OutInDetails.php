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
class OutInDetails extends Model{
    //put your code here
    protected $name='out_in_details';

    public function addDetails($data) {
        $res = $this->save($data);
        return $res;
    }

    public function editDetails($data) {
        $res = $this->isUpdate($data);
        return $res;
    }
    
    
}
