<?php
namespace app\admin\model;
use \think\Model;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Product
 *
 * @author wujiabin 
 */
class Warehousetable extends Model{
    //put your code here
    protected $name='warehouse_table';
    
    public function materialDetails(){
        return $this->hasMany('material_detail','material_id','m_id');
    }

    public function wtablematerial(){
        return $this->hasOne('wtable_material','wt_id','id');
    }

    /**
     * 获取单条信息
     * @param $where array() 条件
     * @param $fields sting 字段
     * @return $reinfo 返回数组
     * */
    public function getOne($where, $fields = '')
    {
        if (!$where) {
            return false;
        }
        if ($fields) {
            $this->field($fields);
        }
        $reinfo = $this->where($where)->find();
        return $reinfo;
    }
    /**
     * 增加一条信息
     * @param $data array() 数据
     * @return integer|string 返回自增id
     * */
    public function addOne($data)
    {
        return $this->insertGetId($data);
    }

    /**
     * 根据条件获取数据总数
     * @param $where array()
     * @return int|string 返回总量
     */
    public function getCount($where)
    {
        return $this->where($where)->count();
    }

    /**
     * 编辑角色信息
     * @param $param
     * @return array()
     */
    public function edit($param)
    {
        try{
            $result = $this->save($param, ['id' => $param['id']]);
            if(false === $result){
                return ['code' => 0, 'data' => '', 'msg' => $this->getError()];
            }else{
                return ['code' => 1000, 'data' => '', 'msg' => '编辑成功'];
            }
        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    /**
     * 根据id删除数据
     * @param $id
     * @return array()
     */
    public function delOne($id)
    {
        try{
            $this->where('id', $id)->delete();
            return ['code' => 1000, 'data' => '', 'msg' => '删除成功'];
        }catch( PDOException $e){
            return ['code' => 1001, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    /**
     * 根据字段、表达式删除数据
     * @param $field string
     * @param $op string
     * @param $arr array()
     * @return array()
     */
    public function delWHTable($field, $op, $arr)
    {
        try{
            $this->where($field, $op, $arr)->delete();
            //return ['code' => 1000, 'data' => '', 'msg' => '删除成功'];
            exit('1');
        }catch( PDOException $e){
            //return ['code' => 1001, 'data' => '', 'msg' => $e->getMessage()];
            exit('2');
        }
    }
    
}
