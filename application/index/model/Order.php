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
class Order extends Model{
    //put your code here
    protected $name='new_order';
    protected $type = [
        //'AmzTimer'    => 'datetime:Y-m-d',
        //'SignTimer'   =>'datetime:Y-m-d',
        //'GetTimer'    =>'datetime:Y-m-d',
    ];
    protected $insert = [
        'stock_id' => '',
        'PrintImgURL' => null,
        'Urgent' => '0',
        'SignTimer' => null,
        'FFYMemo' => '',
        'SignMemo' => '',
        'Code' => '',
        'BlackNum' => '0',
        'BlackReason' => '',
        'Black_info'=>'',
        'status'=>'0',
    ];
    public function orderproduct(){
        return $this->hasOne('product','product_id','product_id');
    }
    public function orderfactroy(){
        return $this->hasMany('orderfactory','order_id','id')->order('working_type asc');
    }

    /*public function productfacHas(){
        return $this->hasMany('productfactory','product_id','product_id');
    }*/

    /*public function productfacHas(){
        //(要查询的表，中间表，中间表里面对应要查询表的id字段名,中间表里面要对应现在模型表的id名称)
        return $this->belongsToMany('productfactory', 'product','id', 'product_id')->order('working_type asc');
    }*/

    public function saveOrder($order) {
        try {
            $res = $this->insertGetId($order);
            if(false === $res){
                return ['code' => 1001, 'data' => '', 'msg' => $this->getError()];
            }else{
                return ['code' => 1000, 'data' => '', 'msg' => '添加成功','id' => $res];//id:返回新增数据的id
            }
        } catch( PDOException $e){
            return ['code' => 1001, 'data' => '', 'msg' => $e->getMessage()];
        }

    }
    //有库存
    public function saveOrderHS($order) {
        try {
            $res = $this->insertAll($order, true);
            if(false === $res){
                return ['code' => 1001, 'data' => '', 'msg' => $this->getError()];
            }else{
                return ['code' => 1000, 'data' => '', 'msg' => '添加成功'];//id:返回新增数据的id
            }
        } catch( PDOException $e){
            return ['code' => 1001, 'data' => '', 'msg' => $e->getMessage()];
        }

    }

    //循环插入订单数据
    public function saveOrderHSFor($order) {
        $res_return = array();
        foreach ($order as $k => $v) {
            try {
                $res = $this->insertGetId($v);//id:返回新增数据的id
                if (false === $res) {
                    return ['code' => 1001, 'data' => '', 'msg' => $this->getError()];
                } else {
                    $v['id'] = $res;
                    $res_return[] = $v;
                }
            } catch (PDOException $e) {
                return ['code' => 1001, 'data' => '', 'msg' => $e->getMessage()];
            }
        }
        return ['code' => 1000, 'order' => $res_return];
    }
}
