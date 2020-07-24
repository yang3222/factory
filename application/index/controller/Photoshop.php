<?php
namespace app\index\controller;
use \app\index\model\Umbrella as UmbrellaModel;

class Photoshop extends Base
{
    public function __construct() {
        parent::__construct();
    }
    public function index(){
        
    }
    public function config(){
        return $this->fetch();
    }
}
