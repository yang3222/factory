<?php
namespace app\admin\model;
use \think\Model;
use traits\model\SoftDelete;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Audit extends Model{
    //软删除
    use SoftDelete;
    protected $autoWriteTimestamp = 'datetime';
    protected $deleteTime = 'delete_time';//必须
    protected $updateTime = false;//必须
    protected $field = true;
    //软删
    protected $name='audit_supplier';
    protected $type = [
        //'create_time' => 'datetime:Y-m-d',
        //'update_time' => '',
    ];
    public function addAudit($title,$types) {
        $create_time = date('Y-m-d H:i:s', time());
        $res = $this->insert(['title' => $title, 'create_time' => $create_time, 'status' => 1, 'types' => $types]);
        if ($res) {
            return $res;
        } else {
            return false;
        }
    }

    public function updateAudit($title,$id,$types) {
        $update_time = date('Y-m-d H:i:s', time());
        $res = $this->update(['title' => $title, 'update_time' => $update_time, 'types' => $types], ['id' => $id]);
        if ($res) {
            return $res;
        } else {
            return false;
        }
    }
    public function audituserinfo(){
        return $this->hasOne('userinfo','user_id','audit_user');
    }
    public function applyuserinfo(){
        return $this->hasOne('userinfo','user_id','apply_user');
    }
    public function supplier(){
        return $this->hasOne('upplier','id','supplier_id');
    }

}
