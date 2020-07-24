<?php
namespace app\user\controller;

use \app\user\controller;
use \app\user\model\User;
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
    //工厂信息
    public function index(){
        
        $data =  User::get(session('user_id'));
        $this->assign('eventJS','member');
        $this->assign('canvasTitle','工厂信息');
        $this->assign('data',$data);
        return $this->fetch();
    }
     //保存信息
    public function post(){
        $id=input('post.id');
        if(empty($id)){
            //新增
            $user=new User();
            $user->Type=input('post.usertype');
            $user->Timer=date('y-m-d h:i:s',time());
        }else{
            //修改
            $user=  User::get($id,'userinfo');
        }
        $user->User=input('post.User','','trim');
        $user->Pwd=base64_encode(input('post.Pwd','','trim').'admin');
        $user->reviewed=input('post.reviewed');
        if($user->save()!==false){
            if(empty($id)){
                $userinfo=new Userinfo();
            }else{
                $userinfo=$user->userinfo;
            }
            $userinfo->Name=input('post.Name','','trim');
            $userinfo->Tel=input('post.Tel','','trim');
            $userinfo->Memo=input('post.Memo','','trim');
            if(empty($id)){
                $value=$user->userinfo()->save($userinfo);
            }else{
                $value=$user->userinfo->save();
            }
            if($value!==false){
                echo true;
            }else{
                exit("保存失败，请重新保存");
            }
        }else{
             dump($user->getError());
        }
    }
    //修改密码
    public function editpwd(){
        return $this->fetch();
    }
    //修改密码
    public function edit(){
        $oldpwd=base64_encode(input('post.old','','trim').'factory');
        if($oldpwd==session('user_pwd')){
            $newpwd=base64_encode(input('post.new','','trim').'factory');
            $user=  User::get(session('user_id'));
            $user->Pwd=$newpwd;
            if($user->save()!==false){
                session('user_pwd',$newpwd);
                echo "密码设置成功";
            }else{
                echo "密码设置失败，请重新设置";
            }
        }else{
            echo "密码错误，请重新输入";
        }
    }
}
