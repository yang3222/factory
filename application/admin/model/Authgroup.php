<?php
namespace app\admin\model;
use \think\Model;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Authgroup extends Model{
    //put your code here
    protected $name='auth_group';
    protected $type = [
        //'create_time' => 'datetime:Y-m-d',
        //'update_time' => '',
    ];
    public function addGroup($title,$types) {
        $create_time = date('Y-m-d H:i:s', time());
        $res = $this->insert(['title' => $title, 'create_time' => $create_time, 'status' => 1, 'types' => $types]);
        if ($res) {
            return $res;
        } else {
            return false;
        }
    }

    public function updateGroup($title,$id,$types) {
        $update_time = date('Y-m-d H:i:s', time());
        $res = $this->update(['title' => $title, 'update_time' => $update_time, 'types' => $types], ['id' => $id]);
        if ($res) {
            return $res;
        } else {
            return false;
        }
    }


}
