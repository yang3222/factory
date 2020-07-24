<?php
namespace app\admin\model;
use \think\Model;
use traits\model\SoftDelete;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Outproorder extends Model{
    use SoftDelete;
    protected $autoWriteTimestamp = 'datetime';
    protected $deleteTime = 'delete_time';//必须
    protected $updateTime = false;//必须
    protected $field = true;
    //put your code here
    protected $name='out_pro_order';//委外加工单


    public function deletesub($id) {
        $idstr = implode(',', $id);
        $res = $this->where(['id' => ['in', $idstr]])->delete();
        if ($res) {
            return ['code' => 1000, 'msg' => '删除成功'];
        } else{
            return ['code' => 1001, 'msg' => '删除失败，请重试'];
        }
    }

    public function a123(){
        return $this->hasMany('fbaorder','fba_id','id');
    }

}
