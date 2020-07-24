<?php
namespace app\user\model;
use \think\Model;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Product
 *
 * @author 27532
 */
class Menu extends Model{
    //put your code here
    protected $name='factory_menu';
    protected $insert = ['sort'=>'0','display'=>'1'];
}
