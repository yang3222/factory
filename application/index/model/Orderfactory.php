<?php
namespace app\index\model;
use \think\Model;
use think\exception\PDOException;

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
class Orderfactory extends Model{
    //put your code here
    protected $name='order_factory';
    protected $type = [
        //'pro_time'    => 'datetime:Y-m-d',
        //'library_time'    => 'datetime:Y-m-d',
    ];
    protected $insert = [
        'sign' => '0',
        'endboo' => '0',
        'pro_time' => null,
        'library_time' => null
    ];
    public function userinfo(){
        return $this->hasOne('userinfo','user_id','factory_id');
    }

    public function saveOrderFactory($data) {
        try {
            $res = $this->saveAll($data);
            if(false === $res){
                return ['code' => 1002, 'data' => $res, 'msg' => $this->getError()];//1002：工厂添加失败，但是订单添加成功
            }else{
                return ['code' => 1000, 'data' => $res, 'msg' => '添加成功'];
            }
        } catch( PDOException $e){
            return ['code' => 1002, 'data' => '', 'msg' => $e->getMessage()];
        }

    }

    public function saveOrderFactoryAll($data) {
        try {
            $res = $this->saveAll($data);
            if(false === $res){
                return ['code' => 1002, 'data' => $res, 'msg' => $this->getError()];//1002：工厂添加失败，但是订单添加成功
            }else{
                return ['code' => 1000, 'data' => $res, 'msg' => '添加成功'];
            }
        } catch( PDOException $e){
            return ['code' => 1002, 'data' => '', 'msg' => $e->getMessage()];
        }

    }
}
