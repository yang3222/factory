<?php
namespace app\index\model;
use \think\Model;
/*
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
    public function userinfo(){
        return $this->hasOne('userinfo','user_id','id');
    }
}
