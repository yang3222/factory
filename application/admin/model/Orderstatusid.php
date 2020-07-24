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
class Orderstatusid extends Model{
    //put your code here
    protected $name='order_status_id';
    protected $type = [

    ];

    public function savestatus($data) {
        $res = $this->insertAll($data);
        if ($res) {
            return ['code' => 1000, 'msg' => '添加成功'];
        } else{
            return ['code' => 1001, 'msg' => '添加失败，请重试'];
        }
    }


}
