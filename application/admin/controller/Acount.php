<?php
namespace app\admin\controller;

use \app\admin\controller;
use \app\admin\model\User;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Index
 *
 * @author 27532
 */
class Acount extends Base{
    //修改密码
    public function editpwd(){
        return $this->fetch();
    }
    //修改密码
    public function edit(){
        $oldpwd=base64_encode(input('post.old','','trim').'admin');
        if($oldpwd==session('admin_pwd')){
            $newpwd=base64_encode(input('post.new','','trim').'admin');
            $user=  User::get(session('admin_id'));
            $user->Pwd=$newpwd;
            if($user->save()!==false){
                session('admin_pwd',$newpwd);
                echo "密码设置成功";
            }else{
                echo "密码设置失败，请重新设置";
            }
        }else{
            echo "密码错误，请重新输入";
        }
    }
}
