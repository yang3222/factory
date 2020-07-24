<?php
namespace app\user\controller;

use app\user\model\User;
use app\user\model\Userinfo;
use \app\user\model\Productfactory;
use \app\user\model\Product;
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
class Factory extends Base{
    public function __construct() {
        parent::__construct();
        $this->assign('currentMenu',array('menu'=>'menu3','nav'=>'nav1'));
    }
    //工厂列表
    public function index(){
        $member=User::where('Type','=','2')->order('id', 'desc')->select();
        $this->assign('member',$member);
        return $this->fetch();
    }
    //编辑会员信息
    public function edit($id){
        $data=  User::get(['id'=>$id],'userinfo');
        $this->assign('eventJS','member');
        $this->assign('canvasTitle','修改管理人员');
        $this->assign('data',$data);
        return $this->fetch();
    }
    //编辑会员信息
    public function add(){
        $data=array('id'=>'','User'=>'','Pwd'=>'','reviewed'=>'0','Type'=>'2','Timer'=>'','userinfo'=>array('Name'=>'','Tel'=>'','Memo'=>''));
        $this->assign('eventJS','member');
        $this->assign('canvasTitle','新增工厂');
        $this->assign('data',$data);
        return $this->fetch('factory/edit');
    }
    //保存信息
    public function post(){
        $id=input('post.id');
        if(empty($id)){
            //新增
            $user=new User();
            $user->Type='1';
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
    //产品列表
    public function product($id,$type){
       
        $productfactory=new Productfactory();
        $productdata=$productfactory
                ->where([
                    'factory_id'=>['=',$id],
                    'working_type'=>$type
                        ])->select();
        $this->assign('data',$productdata);
        $this->assign('eventJS','factory');
        $this->assign('id',$id);
        $this->assign('type',$type);
        return $this->fetch();
    }
    //删除产品
    public function deleteProduct(){
        $ids=input('post.ids/a');
        $productfactory=new Productfactory();
        if($productfactory->where('id','in',$ids)->delete()){
            echo true;
        }else{
            echo "删除失败，请重新删除";
        }
    }
    public function openwindows(){
        $this->view->engine->layout(false);
        $user=  User::scope('showfactory')->order('id desc')->select();
        $this->assign('factorys',$user);
        return $this->fetch();
    }
    //添加产品
    public function addProduct($factory_id,$type){
        $product=new Product();
        $data=$product->where('display','=','1')->order("sort desc,id desc")->select();
        $factory=  User::get(['id'=>$factory_id],'userinfo');
        $this->assign('data',$data);
        $this->assign('eventJS','factory');
        $this->assign('factoryName',$factory->userinfo->Name);
        $this->assign('id',$factory_id);
        $this->assign('type',$type);
        return $this->fetch();
    }
    public function postProduct($factory_id,$type){
        $saveid=input('post.save/a');
        $deleteid=input('post.delete/a');
        $productfactory=new Productfactory();
        if(count($deleteid)>0)$productfactory->where(['product_id'=>['in',$deleteid],'factory_id'=>['=',$factory_id],'working_type'=>['=',$type]])->delete();
        if(count($saveid)==0){
            echo true;
            exit;
        }
        $saveData=array();
        foreach($saveid as $value){
            $saveData[]=['product_id'=>$value,'factory_id'=>$factory_id,'working_type'=>$type];
        }
        if($productfactory->saveAll($saveData)){
            echo true;
        }else{
            echo "保存失败，请重新保存！";
        }
    }
}
