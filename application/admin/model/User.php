<?php
namespace app\admin\model;
use \think\Model;
/*dfgdfg
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Login
 *
 * @author 27532
 */
class User extends Model{
    //put your code here
    protected $name='user';
    protected $type = [
        //'Timer'    => 'datetime:Y-m-d',
    ];
    public function userinfo(){
        return $this->hasOne('userinfo','user_id','id');
    }
    public function scopeShowfactory($query)
    {
        $query->where('reviewed', 1)->where('Type', 2);
        //reviewed是否通过审核1通过0未通过2审核屏蔽
        //Type:1管理员用户，2工厂用户
    }
    //auth_group
    public function authGroupMany() {
        return $this->hasMany('authgroupaccess', 'uid', 'id');
    }

    public function authGroup(){
        //(要查询的表，中间表，中间表里面对应要查询表的id字段名,中间表里面要对应现在模型表的id名称)
        return $this->belongsToMany('authgroup', 'auth_group_access','group_id', 'uid');
    }
}
