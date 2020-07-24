<?php

namespace app\admin\controller;

use app\admin\controller;
use app\admin\model\Lgstcode;

class Expsetting extends Base {

    protected $pageTotalItem = 40;

    public function __construct() {

        parent::__construct();

        $this->assign('currentMenu', array('menu' => 'menu1', 'nav' => 'nav4'));

    }

    //首页
    public function index() {
        $LgstcodeModle = new Lgstcode();

        $lgscode = $LgstcodeModle->paginate($this->pageTotalItem,false,['query' =>request()->param()]);
        $this->assign('lgscode', $lgscode);
        $this->assign('pageDiv', $lgscode->render());
        return $this->fetch();
    }

    //添加快递
    public function add_exp() {
        if($this->request->isPost()) {
            $data = input('post.');
            $lgsModel = new Lgstcode();
            if ($data['status'] == '' || empty($data['lgstcode']) || empty($data['lgstcode_name'])) return json(['code' => 1001, 'msg' => '添加失败，请重试']);
            $data['create_time'] = time();
            $res = $lgsModel->savelgs($data);
            cache('apiSaveOrder-lgstcode-status-1', null);
            return json($res);
        }
        return $this->fetch();
    }

    //编辑快递edit_exp.html
    public function edit_exp($id = '') {
        $lgsModel = new Lgstcode();
        if($this->request->isPost()) {
            $data = input('post.');
            $res = $lgsModel->updatelgs($data);
            cache('apiSaveOrder-lgstcode-status-1', null);
            return json($res);
        }
        $res = $lgsModel->where('id', '=', $id)->find();
        $this->assign('lgscode', $res);
        $this->assign('lgscode_id', $id);
        return $this->fetch();
    }


}

