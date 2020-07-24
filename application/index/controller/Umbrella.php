<?php
namespace app\index\controller;
use \app\index\model\Umbrella as UmbrellaModel;

class Umbrella extends Base
{
    public function __construct() {
        parent::__construct();
    }
    public function index(){
        
    }
    public function config(){
        return $this->fetch();
    }
    public function listdata()
    {
        $user_id=input('post.key');
        $data=new UmbrellaModel();
        $datalist=$data->where('find_in_set('.$user_id.',factory)')->select();
        $this->assign('data',$datalist);
        return $this->fetch();
    }
    public function tmp(){
        $umbrella_id=input('post.id');
        $data=UmbrellaModel::get($umbrella_id);
        $this->assign('data',$data);
        return $this->fetch();
    }
}
