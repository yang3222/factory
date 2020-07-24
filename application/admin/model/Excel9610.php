<?php
namespace app\admin\model;
use \think\Model;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Excel9610 extends Model{
    //put your code here
    protected $name='excel9610';
    protected $type = [

    ];

    public function savedata($data) {

        $res = $this->insert($data);
        if ($res) {
            return ['code' => 1000, 'msg' => '添加成功'];
        } else{
            return ['code' => 1001, 'msg' => '添加失败，请重试'];
        }
    }

    public function updatedata($data, $id) {

        $res = $this->update($data, ['id' => $id]);
        if ($res) {
            return ['code' => 1000, 'msg' => '编辑成功'];
        } else{
            return ['code' => 1001, 'msg' => '编辑失败，请重试'];
        }
    }

    public function deletedata($id) {
        $idstr = implode(',', $id);
        $res = $this->where(['id' => ['in', $idstr]])->delete();
        if ($res) {
            return ['code' => 1000, 'msg' => '删除成功'];
        } else{
            return ['code' => 1001, 'msg' => '删除失败，请重试'];
        }
    }


}
