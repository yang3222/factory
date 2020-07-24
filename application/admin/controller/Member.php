<?php
namespace app\admin\controller;

use \app\admin\controller;
use \app\admin\model\User;
use app\admin\model\Userinfo;
use app\admin\model\Authgroup;
use app\admin\model\Authgroupaccess;
use \think\Config;
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
class Member extends Base{
    public function __construct() {
        parent::__construct();
        $this->assign('currentMenu',array('menu'=>'menu3','nav'=>'nav0'));
    }
    //管理员列表
    public function index(){
        $member=User::relation('userinfo,authGroup')->where('Type','=','1')->order('id', 'desc')->select();
        foreach ($member as $k => &$v) {
            $pwd = substr(base64_decode($v['Pwd']), 0, -5);
            $user = $v['User'];
            //$rand1 = rand(1000, 9999);
            //$rand2 = rand(1000, 9999);
            $v['user_code'] = base64_encode($pwd . 'p&r' . $user);
        }
        //print_r($member);exit;
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
        $authGroup = $authGroupModel->where('types', 1)->select();
        $this->assign('auth_group', $authGroup);
        $this->assign('ingroup', $ingroup);
        $this->assign('eventJS','member');
        $this->assign('canvasTitle','修改管理人员');
        $this->assign('data',$data);
        return $this->fetch();
    }
    //编辑会员信息
    public function add(){
        $authGroupModel = new Authgroup();
        $data=array('id'=>'','User'=>'','Pwd'=>'','reviewed'=>'0','Type'=>'1','Timer'=>'','operation_auth'=>1,'userinfo'=>array('Name'=>'','Tel'=>'','Memo'=>''));
        $authGroup = $authGroupModel->where('status', 1)->select();
        $this->assign('auth_group', $authGroup);
        $this->assign('eventJS','member');
        $this->assign('canvasTitle','新增管理人员');
        $this->assign('ingroup', array());
        $this->assign('data',$data);
        return $this->fetch('member/edit');
    }
    //保存信息
    public function post(){
        $id=input('post.id');
        if(empty($id)){
            //新增
            $user = new User();
            $user->Type=input('post.usertype');
            $user->Timer=date('y-m-d h:i:s',time());
        }else{
            //修改
            $user=  User::get($id,'userinfo');
        }
        if (strstr(input('post.User'), '&') == true || strstr(input('post.Pwd'), '&') == true) exit('账户名或密码包含非法字符：&');
        $user->User=input('post.User','','trim');
        $user->Pwd=base64_encode(input('post.Pwd','','trim').'admin');
        $user->reviewed=input('post.reviewed');
        $user->operation_auth=input('post.operation_auth');
        /*if(empty($id)){
            $user_id = $user->insertGetId($user);
        }else{
            $user_id = $user->save();
        }*/
        if($user->save()!==false){
            if(empty($id)){
                $userinfo=new Userinfo();

            }else{
                $userinfo=$user->userinfo;
            }
            $userinfo->Name=input('post.Name','','trim');
            $userinfo->Tel=input('post.Tel','','trim');
            $userinfo->Memo=input('post.Memo','','trim');
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

    //用户管理组
    public function authGroup() {
        $groupModel = new Authgroup();
        $authgroup = $groupModel->where('status', 1)->select();
        $this->assign('currentMenu',array('menu'=>'menu3','nav'=>'nav2'));
        $this->assign('authgroup', $authgroup);
        return $this->fetch();
    }

    //编辑用户组
    public function editGroup() {
        $this->view->engine->layout(false);
        $data = input('post.');
        $group = array(
            'title' => '',
            'types' => 0
        );
        if (isset($data['id'])) {
            $id = $data['id'];
            $authgroupModel = new Authgroup();
            $group = $authgroupModel->where('id', $id)->find();
        }
        $this->assign('currentMenu',array('menu'=>'menu3','nav'=>'nav2'));
        $this->assign('editgroup', $group);
        return $this->fetch();
    }

    //保存
    public function saveGroup() {
        if ($this->request->isPost()) {
            $data = input('post.');
            if (empty($data['title'])) return json(['code' => 1001, 'msg' => '用户名称不能为空']);
            $title = $data['title'];
            $types = $data['types'];
            $groupModel = new Authgroup();
            $add = $groupModel->addGroup($title, $types);
            if ($add) {
                return json(['code' => 1000, 'msg' => '保存成功']);
            } else {
                return json(['code' => 1001, 'msg' => '保存失败']);
            }
        } else {
            return json(['code' => 1001, 'msg' => '此为post接口']);
        }
    }

    //更新
    public function updateGroup() {
        if ($this->request->isPost()) {
            $data = input('post.');
            if (empty($data['title'])) return json(['code' => 1001, 'msg' => '用户名称不能为空']);
            if (empty($data['id'])) return json(['code' => 1001, 'msg' => '用户id为空,请重试']);
            if (empty($data['types'])) return json(['code' => 1001, 'msg' => '参数为空,请重试']);
            $title = $data['title'];
            $id = $data['id'];
            $types = $data['types'];
            $groupModel = new Authgroup();
            $update = $groupModel->updateGroup($title,$id,$types);
            if ($update) {
                return json(['code' => 1000, 'msg' => '编辑成功']);
            } else {
                return json(['code' => 1001, 'msg' => '编辑失败']);
            }
        } else {
            return json(['code' => 1001, 'msg' => '此为post接口']);
        }
    }
    //获取菜单树tree：get=>tree.js; post=>通过id获取规则
    public function accControl() {
        if ($this->request->isGet()) {

            //$group_id = input('get.gro_id');
            //$groupModel = new Authgroup();
            $types = input('get.types');
            if ($types == 'tree') {
                $menu = Config::get('admin_menu');
            } else if($types == 'treeuser') {
                $menu=  Config::get('user_menu');
            }
            $res = menu_to_tree($menu, 'id', 'pid', 'children');
            return json($res);
        }
        //$jstree = array();
        //$this->assign($jstree);
        if ($this->request->isPost()) {
            $groupModel = new Authgroup();
            $data = input('post.');
            $gro_id = $data['id'];
            $auth_group = $groupModel->where('id', $gro_id)->find();
            return json(['rules' => $auth_group['rules']]);
        }

        $this->view->engine->layout(false);
        $this->assign('currentMenu',array('menu'=>'menu3','nav'=>'nav2'));
        return $this->fetch();
    }

    //修改rules
    public function editRules() {
        if ($this->request->isPost()) {
            $groupModel = new Authgroup();
            $data = input('post.');
            $gro_id = $data['id'];
            $rules = '';
            if (isset($data['rules'])) $rules = implode(',', $data['rules']);
            $auth_group = $groupModel->update(['rules' => $rules, 'update_time' => date('Y-m-d H:i:s', time())], ['id' => $gro_id]);
            if ($auth_group) {
                return json(['code' => 1000, 'msg' => '修改成功']);
            } else {
                return json(['code' => 1001, 'msg' => '修改失败']);
            }
        } else {
            return json(['code' => 1001, 'msg' => '此为post接口']);
        }
    }

    //获取条形码
    public function getUserCodeBar($text) {
        //barcode_user_code('BCGcode128', $text, 40, 4);
        phpQrCode($text, 2, 1);
    }


}
