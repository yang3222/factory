<?php
namespace app\admin\controller;

use \app\admin\controller;
use \app\admin\model\Umbrella;
use app\user\model\User;
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
class Software extends Base{
    protected $pageTotalItem=40;
    public function __construct() {
        parent::__construct();
        $this->assign('currentMenu',array('menu'=>'menu6','nav'=>'nav0'));
    }
    public function umbrella(){
        $this->assign('eventJS','flash');
        return $this->fetch();
    }
    //雨伞的参数
    public function umbrellaconfig(){
        header('content-type: text/xml; charset=utf-8');
        $this->view->engine->layout(false);
        return $this->fetch();
    }
    //雨伞
    public function umbrellalist(){
        $umbrella=new Umbrella();
        $data=$umbrella->Order('id asc')->select();
        $this->view->engine->layout(false);
        $this->assign('data',$data);
        return $this->fetch();
    }
    //工厂
    public function factory(){
        $member=User::where('Type','=','2')->order('id', 'desc')->select();
        $this->view->engine->layout(false);
        $this->assign('data',$member);
        return $this->fetch();
    }
    //雨伞模板
    public function umbrellatmp(){
        $data=  Umbrella::get(input('post.id'));
        $this->view->engine->layout(false);
        $this->assign('data',$data);
        return $this->fetch();
    }
    //上传图片
    public function uploadbrowse(){
        $path="umbrella/images/".date("Y-m-d").'/';
        $this->savePath($path);
        $this->view->engine->layout(false);
        return $this->fetch("software/umbrellaupload");
    }
    //上传模型
    public function uploadtmp(){
        $path="umbrella/swf/".date("Y-m-d").'/';
        $this->savePath($path);
        $this->view->engine->layout(false);
        return $this->fetch("software/umbrellaupload");
    }
    public function umbrellasave(){
        $id=input('post.id');
        if(!empty($id)){
             $umbrella=  Umbrella::get($id);
        }else{
             $umbrella=new Umbrella();
             $umbrella->StartTimer=date('y-m-d h:i:s');
        }
        $umbrella->cloth=input('post.ClothWidth');
        $umbrella->factory=input('post.Factory');
        $umbrella->name=input('post.Name');
        $umbrella->resolution=input('post.Resolution');
        $umbrella->spacing=input('post.Spacing');
        $umbrella->umbrellanum=input('post.UmbrellaNum');
        $umbrella->remove=input('post.Remove');
        $umbrella->deformation=input('post.deformation');
        $umbrella->imgurl=str_replace(WEB.PRODUCT_IMG,"",input('post.Img'));
        $umbrella->umbrellaurl=str_replace(WEB.PRODUCT_IMG,"",input('post.Umbrella'));
        if($umbrella->save()!==false){
            $boo=true;
        }else{
            $boo=false;
        }
        $this->assign('success',$boo);
        $this->assign('tip',$boo?"保存成功":"保存失败，请重新保存");
        return $this->fetch("software/umbrellaupload");
    }
    //保存地址
    private function savePath($path){
        $filename=input('post.Filename');
        if(!isset($filename))return;
        $file =isset($_FILES["Filedata"])? $_FILES["Filedata"]["tmp_name"]:"";
        $file_path=ROOT_PRODUCT_IMG.$path;
        if(!is_dir($file_path)){
            mkdir($file_path, 0700);
        }
        $ext = explode(".", $filename); 
        $imgname= rand().rand().rand();
	$num=count($ext)>0?count($ext)-1:0;
	$saveName=$imgname.".".$ext[$num];
	$fileUrl=$file_path.$saveName;
	$dataurl=WEB.PRODUCT_IMG.$path.$saveName;
        if (move_uploaded_file($file, $fileUrl)){
            chmod ($fileUrl, 0755);
	}
        $this->assign("success",true);
        $this->assign("url",$dataurl);
    }
}
