<?phpnamespace app\user\controller;use \app\user\controller;/* * To change this license header, choose License Headers in Project Properties. * To change this template file, choose Tools | Templates * and open the template in the editor. *//** * Description of Index * * @author 27532 */class Index extends Base{    public function __construct() {        parent::__construct();        $this->assign('title','工厂管理系统');        $this->assign('currentMenu',array('menu'=>'menu0','nav'=>'nav0'));    }    public function index(){        return $this->fetch();    }    }