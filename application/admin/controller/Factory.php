<?php
namespace app\admin\controller;

use \app\admin\controller;
use \app\admin\model\User;
use app\admin\model\Userinfo;
use \app\admin\model\Productfactory;
use \app\admin\model\Product;
use app\admin\model\Authgroup;
use app\admin\model\Authgroupaccess;
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
    protected $fac_att = array(
        1 => '印花',
        2 => '加工'
    );
    public function __construct() {
        parent::__construct();
        $this->assign('currentMenu',array('menu'=>'menu3','nav'=>'nav1'));
    }
    //工厂列表
    public function index(){
        $member=User::relation('userinfo,authGroup')->where('Type','=','2')->order('id', 'desc')->select();
        foreach ($member as $k => &$v) {
            $pwd = substr(base64_decode($v['Pwd']), 0, -7);
            $user = $v['User'];
            //$rand1 = rand(1000, 9999);
            //$rand2 = rand(1000, 9999);
            $v['user_code'] = base64_encode($pwd . 'p&r' . $user);
            if ($v['fac_attribute'] != null) {
                $v['fac_attribute_arr'] = explode('-', $v['fac_attribute']);
            } else {
                $v['fac_attribute_arr'] = array();
            }
        }

        $this->assign('fac_att', $this->fac_att);//工厂属性
        $this->assign('member',$member);
        return $this->fetch();
    }
    //编辑会员信息
    public function edit($id){
        $userModel = new User();
        $authGroupModel = new Authgroup();
        $data = $userModel->relation('userinfo,authGroupMany,authGroup')->where('id', $id)->find();
        $ingroup = array();
        foreach ($data['authGroup'] as $k => $v) {
            $ingroup[$v['id']] = $v['id'];
        }
        $data['fac_attribute'] = explode('-', $data['fac_attribute']);

        $authGroup = $authGroupModel->where('types', 2)->select();
        $this->assign('auth_group', $authGroup);
        $this->assign('ingroup', $ingroup);
        $this->assign('fac_att', $this->fac_att);//工厂属性
        $this->assign('eventJS','facuser');
        $this->assign('canvasTitle','修改管理人员');
        $this->assign('data',$data);
        return $this->fetch();
    }
    //编辑会员信息
    public function add(){
        $authGroupModel = new Authgroup();
        $data=array('id'=>'','User'=>'','Pwd'=>'','reviewed'=>'0','Type'=>'1','Timer'=>'','userinfo'=>array('Name'=>'','Tel'=>'','Memo'=>''),'fac_attribute'=>array());
        $authGroup = $authGroupModel->where('status', 1)->select();
        $this->assign('auth_group', $authGroup);
        $this->assign('eventJS','facuser');
        $this->assign('fac_att', $this->fac_att);//工厂属性
        $this->assign('canvasTitle','新增管理人员');
        $this->assign('ingroup', array());
        $this->assign('data',$data);
        return $this->fetch('factory/edit');
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
        $user->Pwd=base64_encode(input('post.Pwd','','trim').'factory');
        $user->reviewed=input('post.reviewed');
        $fac_att = input('post.fac_attribute/a');
        //print_r($fac_att);exit;
        $user->fac_attribute = implode('-', $fac_att);
        if($user->save()!==false){
            if(empty($id)){
                $userinfo=new Userinfo();
            }else{
                $userinfo=$user->userinfo;
            }
            $userinfo->Name=input('post.Name','','trim');
            $userinfo->Tel=input('post.Tel','','trim');
            $autharr = input('post.authgroup/a');
            $authGroAccessModel = new Authgroupaccess();
            if(empty($id)){
                $value=$user->userinfo()->save($userinfo);
                $aga_data = array();
                foreach ($autharr as $gk => $gv) {
                    $aga_data[] = array(
                        'uid' => $user['id'],
                        'group_id' => $gv
                    );
                }
                $saveaga = $authGroAccessModel->insertAll($aga_data);
            }else{
                $value=$user->userinfo->save();
                $authGroAccessModel->where('uid', $id)->delete();
                $aga_data = array();
                foreach ($autharr as $gk => $gv) {
                    $aga_data[] = array(
                        'uid' => $id,
                        'group_id' => $gv
                    );
                }
                $saveaga = $authGroAccessModel->insertAll($aga_data);
            }
            if($value!==false && $saveaga){
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
        $user=  User::scope('showfactory')->relation('userinfo')->order('id desc')->select();
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
