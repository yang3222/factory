<?php
namespace app\admin\controller;

use \app\admin\controller;
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
class Upload extends Base{
   public function postimg($type){
       if($this->request->isPost()&&isset($type)){
           $file_name=input("post.img");
           list($exttype, $data) = explode(',', $file_name);
           if(strstr($exttype,'image/jpeg')!=''){
               $ext = '.jpg';  
           }elseif(strstr($exttype,'image/gif')!=''){
               $ext = '.gif';  
           }elseif(strstr($exttype,'image/gif')!=''){
               $ext = '.png';
           }
           $html_path=$type.'/'.date("Y-m-d").'/';
           $file_path=ROOT_PRODUCT_IMG.$html_path;
           if(!is_dir($file_path)){
               mkdir($file_path, 0700,true);
           }
           $fileName=$this->randName().$ext;
           $photo=$file_path.'/'.$fileName;
           file_put_contents($photo, base64_decode($data), true);
           header('content-type:application/json;charset=utf-8');
           $ret = array('img'=>$html_path.$fileName);
           return json_encode($ret);
       }
   }
   private function randName() {
       return md5(uniqid(rand(), true));
   }
}
