<?php
namespace app\admin\model;
use \think\Model;
use traits\model\SoftDelete;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Fbadelivery extends Model{
    use SoftDelete;
    protected $autoWriteTimestamp = 'datetime';
    protected $deleteTime = 'delete_time';//必须
    protected $updateTime = false;//必须
    protected $field = true;
    //put your code here
    protected $name='fbadelivery';

    public function saveFba($data) {

        $res = $this->insertGetId($data);
        if ($res) {
            return ['code' => 1000, 'msg' => '保存成功', 'id' => $res];
        } else{
            return ['code' => 1001, 'msg' => '保存失败，请重试', 'id' => $res];
        }
    }

    public function updateFba($data) {

        $res = $this->update($data);
        if ($res) {
            return ['code' => 1000, 'msg' => '保存成功'];
        } else{
            return ['code' => 1001, 'msg' => '保存失败，请重试'];
        }
    }

    public function fbaOrders(){
        return $this->hasMany('fbaorder','fba_id','id');
    }

    public function fbaBoxLabel(){
        return $this->hasMany('fba_box_label','fba_id','id');
    }

    public function fbaCodePick(){
        return $this->hasMany('fba_code_picking','fba_id','id');
    }

}
