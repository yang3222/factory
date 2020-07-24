<?php
namespace app\admin\model;
use \think\Model;
use traits\model\SoftDelete;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Fbaorder extends Model{
    use SoftDelete;
    protected $autoWriteTimestamp = 'datetime';
    protected $deleteTime = 'delete_time';//必须
    protected $updateTime = false;//必须
    protected $field = true;
    //put your code here
    protected $name='fba_order';

    /*public function fba_box_label(){
        return $this->hasMany('fba_box_label','fba_order_id','id');
    }*/

    public function saveFbaorder($data) {

        $res = $this->insertAll($data);
        if ($res) {
            return ['code' => 1000, 'msg' => '添加成功'];
        } else{
            return ['code' => 1001, 'msg' => '添加失败，请重试'];
        }
    }

    public function updateFba($data, $id) {

        $res = $this->update($data, ['id' => $id]);
        if ($res) {
            return ['code' => 1000, 'msg' => '编辑成功'];
        } else{
            return ['code' => 1001, 'msg' => '编辑失败，请重试'];
        }
    }

    public function fbas(){
        return $this->hasOne('fbadelivery','id','fba_id');
    }

}
