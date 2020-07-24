<?php
namespace app\admin\model;
use \think\Model;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Lgstcode extends Model{
    //put your code here
    protected $name='lgstcode';
    protected $type = [
        //'Timer'    => 'datetime:Y-m-d',
    ];
    public function savelgs($data) {
        $lgs = $this->where('lgstcode', $data['lgstcode'])->find();
        if (!empty($lgs)) {
            return ['code' => 1002, 'msg' => '该快递code已经存在，请更换'];
        }
        $res = $this->insert($data);
        if ($res) {
            return ['code' => 1000, 'msg' => '添加成功'];
        } else{
            return ['code' => 1001, 'msg' => '添加失败，请重试'];
        }
    }

    public function updatelgs($data) {
        /*$lgs = $this->where('lgstcode', $data['lgstcode'])->find();
        if (!empty($lgs)) {
            return ['code' => 1002, 'msg' => '该快递code已经存在，请更换'];
        }*/
        $res = $this->update(['lgstcode' => $data['lgstcode'], 'lgstcode_name' => $data['lgstcode_name'], 'status' => $data['status']], ['id' => $data['id']]);
        if ($res) {
            return ['code' => 1000, 'msg' => '编辑成功'];
        } else{
            return ['code' => 1001, 'msg' => '编辑失败，请重试'];
        }
    }

}
