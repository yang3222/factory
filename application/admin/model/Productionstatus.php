<?php
namespace app\admin\model;
use \think\Model;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Productionstatus extends Model{
    //put your code here
    protected $name='production_status';
    protected $type = [

    ];

    public function saveprostatus($data) {
        $pros = $this->where('abbreviation', $data['abbreviation'])->find();
        if (!empty($pros)) {
            return ['code' => 1002, 'msg' => '该简称已经存在，请更换'];
        }
        $res = $this->insertGetId($data);
        if ($res) {
            return ['code' => 1000, 'msg' => '添加成功', 'id' => $res];
        } else{
            return ['code' => 1001, 'msg' => '添加失败，请重试'];
        }
    }

    public function updateprostatus($data, $id) {

        $res = $this->update($data, ['id' => $id]);
        if ($res) {
            return ['code' => 1000, 'msg' => '编辑成功'];
        } else{
            return ['code' => 1001, 'msg' => '编辑失败，请重试'];
        }
    }

}
