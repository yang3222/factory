<?php

namespace app\index\controller;

use app\admin\model\FbaBoxLabel;
use app\admin\model\FbaCodePicking;
use think\Log;
use app\admin\model\Fbadelivery;
use app\admin\model\Order;
use app\admin\model\Fbaorder;
use app\admin\model\EpWarehouseMaterialdetail;
use think\Db;
use app\admin\model\FbaNews;


class Fbaapi extends Base {

    public function __construct() {
        parent::__construct();
    }
    //获取主表
    public function ApiGetFbaLists() {
        header('Content-Type:application/json');
        header("Access-Control-Allow-Origin:*");
        /*if (!$this->request->isPost()) {
            return json(['code' => 1002, 'msg' => '', 'data' => '']);
        }*/
        $search = input('get.search', '', 'trim');
        $start_time = input('get.start_time', '', 'trim');
        $end_time = input('get.end_time', '', 'trim');
        $plan_status = input('get.plan_status', '', 'trim');
        $payment_status = input('get.payment_status', '', 'trim');
        $agent_id = input('get.agent_id', '', 'trim');
        $page = input('get.page', '', 'trim');
        $rows = input('get.rows', '', 'trim');

        //fbaorders
        $db_view = Db::view('fbadelivery fba', "*");

        if ($search != '') {
            $where_search = array(
                'fba_nums' => "$search",
            );

            $db_view->whereOr($where_search);
            $where_fba_order = array(
                'order_no' => "$search",
                'sku' => "$search",
            );
            $group = 'fba.id desc';
            $db_view->view('fba_order fo', 'fba_id', 'fo.fba_id=fba.id')->whereOr($where_fba_order)->group($group);
        }
        if ($start_time != '' && $end_time == '') {
            $where_time['fba.create_time'] = ['>=', $start_time . ' 00:00:00'];
            $db_view->where($where_time);
        } elseif ($start_time == '' && $end_time != '') {
            $where_time['fba.create_time'] = ['<=', $end_time . ' 23:59:59'];
            $db_view->where($where_time);
        } elseif ($start_time != '' && $end_time != '') {
            $where_time['fba.create_time'] = ['between', [$start_time . ' 00:00:00', $end_time . ' 23:59:59']];
            $db_view->where($where_time);
        }

        if ($start_time != '' && $end_time != '') {
            $where_time['fba.create_time'] = ['between', [$start_time . ' 00:00:00', $end_time . ' 23:59:59']];
            $db_view->where($where_time);
        }
        /*else {
            $start_time = date('Y-m-d', strtotime('-6 day'));
            $end_time = date('Y-m-d', time());
            $where_time['fba.create_time'] = ['between', [$start_time . ' 00:00:00', $end_time . ' 23:59:59']];
        }*/

        if ($plan_status != '') {
            $where_pls['plan_status'] = $plan_status;
            $db_view->where($where_pls);
        }
        if ($agent_id != '') {
            $where_agent_id['agent_id'] = $agent_id;
            $db_view->where($where_agent_id);
        }
        if ($payment_status != '') {
            $where_pas['payment_status'] = $payment_status;
            $db_view->where($where_pas);
        }

        $page = $page == '' ? 1 : $page;
        $rows = $rows == '' ? 50 : $rows;
        $db_view->order('fba.id desc');
        $list = $db_view->paginate($rows, false, ['page'=>$page]);

        $fbaOrderModel = new Fbaorder();
        $epmeatilModel = new EpWarehouseMaterialdetail();
        //$fbaboxlabelModel = new FbaBoxLabel();
        $list_each = array();
        foreach ($list as $k => $v) {
            $sum_pick_number = $fbaOrderModel->field('SUM(number),SUM(picking_num)')->where('fba_id', $v['id'])->select();
            $order = $fbaOrderModel->where('fba_id', $v['id'])->select();
            $stock_num = 0;
            foreach ($order as $ek => $ev) {
                $where_sku = array(
                    'sku' => $ev['sku'],//sku查询即可
                );
                $epwm = $epmeatilModel->field('SUM(count)')->where($where_sku)->select();
                $now_gds = $epwm[0]['SUM(count)'] + $ev['picking_num'];//现在已拣货和仓库库存之和
                if ($now_gds >= $ev['number']) {
                    $sub = $ev['number'] - $ev['picking_num'];
                    $stock_num += $epwm[0]['SUM(count)'];//$sub;
                    unset($sub);
                } else {
                    $stock_num += $epwm[0]['SUM(count)'];//$now_gds;
                }
                unset($now_gds);
            }
            $v['case_status'] =  $stock_num . '/' . $sum_pick_number[0]['SUM(picking_num)'] . '/' . $sum_pick_number[0]['SUM(number)'];
            $v['expect_delivery_time'] = mb_strcut($v['expect_delivery_time'], 0, 10);
            $v['success_time'] = mb_strcut($v['success_time'], 0, 10);
            $list_each[$k] = $v;
            unset($sum_pick_number,$stock_num);
        }

        $res = array(
            'start_time' => $start_time,
            'end_time' => $end_time,
            'total' => $list->total(),
            'current_page' => $list->currentPage(),
            'rows' => $list_each,
        );
        return json($res);
    }

    //获取子表数据
    public function ApiGetFbaSubtable() {
        header('Content-Type:application/json');
        header("Access-Control-Allow-Origin:*");
        /*if (!$this->request->isPost()) {
            return json(['code' => 1002, 'msg' => '', 'data' => '']);
        }*/
        $fba_id = input('get.fba_id', '', 'trim');
        if ($fba_id == '') {
            return json(['code' => 1001, 'msg' => '父表id为空']);
        }

        //fbaorders
        $fbaOrderModel = new Fbaorder();

        $fba_order = $fbaOrderModel->where('fba_id', $fba_id)->select();
        $order_res = array();
        foreach ($fba_order as $k => $v) {
            $where = array(
                'sku' => $v['sku'],
                //'spec' => $size
            );

            $epmeatilModel = new EpWarehouseMaterialdetail();

            $stock_num = $epmeatilModel->field('SUM(count)')->where($where)->select();
            if (!isset($stock_num[0]['SUM(count)'])) $stock_num[0]['SUM(count)'] = 0;
            $v['stock_num'] = $stock_num[0]['SUM(count)'];
            $order_res[] = $v;
            unset($stock_num);
        }
        $res = array(
            'total' => count($fba_order),
            'rows' => $order_res,
        );
        return json($res);
    }

    //获取已封箱数量，使用，消息提醒
    public function ApiGetFbaStatusNews() {
        header('Content-Type:application/json');
        $agent_id = input('post.');
        if (empty($agent_id['agent_id'])) {
            return json(['code' => 1001, 'msg' => '业务员id为空']);
        }
        $FbaModel = new Fbadelivery();
        $where_3 = array(
            'plan_status' => 3,
            'agent_id' => $agent_id['agent_id']
            //expect_delivery_time
        );
        $res = $FbaModel->where($where_3)->select();

        $data = array(
            'code' => 1000,
            'count' => count($res),
        );
        return json($data);
    }

    //save/edit fba修改增加主表,现在使用
    public function ApiSaveEditFba() {
        header('Content-Type:application/json');
        $data = input('post.');
        if (empty($data) || !is_array($data)) {
            return json(['code' => 1001, 'msg' => '传参为空']);
        }

        //新增或更新fba主表数据
        if (empty($data['agent_id'])) {
            return json(['code' => 1001, 'msg' => '业务员id为空']);
        }
        if ($data['id'] != '') {
            //update
            //OrdNum+OdrId区分
            $fba_id = $data['id'];
            $FbaModel = new Fbadelivery();
            $where_nums = array(
                'fba_nums' => $data['fba_nums'],
                'id' => ['NEQ', $fba_id],
            );
            $fba_nums = $FbaModel->where($where_nums)->find();
            $fba_old = $FbaModel->where('id', $fba_id)->find();
            if (!empty($fba_nums)) {
                return json(['code' => 1001, 'msg' => '改订单已经存在，请重试！']);
            }
            if ($data['note'] != $fba_old['note']) $data['new_msg'] = 1;
            if (isset($data['expect_delivery_time'])) $data['expect_delivery_time'] = date('Y-m-d H:i:s', strtotime($data['expect_delivery_time']));
            $fba = $FbaModel->updateFba($data);
        } else {
            //add
            //OrdNum+OdrId区分
            $data['create_time'] = date('Y-m-d H:m:s', time());
            unset($data['id']);
            $FbaModel = new Fbadelivery();
            $where_va = array(
                'fba_nums' => $data['fba_nums'],
            );
            $veri_data = $FbaModel->where($where_va)->find();
            if (!empty($veri_data)) {
                return json(['code' => 1001, 'msg' => '改订单已经存在，请重试！']);
            }
            if ($data['note'] != '') $data['new_msg'] = 1;
            if (isset($data['expect_delivery_time'])) $data['expect_delivery_time'] = date('Y-m-d H:i:s', strtotime($data['expect_delivery_time']));
            $fba = $FbaModel->saveFba($data);
            $fba_id = $fba['id'];
        }

        return json(['code' => $fba['code'], 'msg' => $fba['msg'], 'fba_id' => $fba_id]);
    }

    //保存子表现在使用,order保存订单
    public function ApiSaveFbaSubtable() {
        header('Content-Type:application/json');
        $data = input('post.');
        if (empty($data['fba_id']) || empty($data['fba_order'])) {
            return json(['code' => 1001, 'msg' => '父表id或子表数据为空']);
        }

        $epmeatilModel = new EpWarehouseMaterialdetail();//成品仓库，需要修改fba物资是否为计划状态
        foreach ($data['fba_order'] as $fov) {
            if ($fov['id'] != '') {
                //存在id更新数据
                $res_up = $this->updateFbaOrder($fov);
                if ($res_up == false) {
                    return json(['code' => 1001, 'msg' => '保存失败']);
                }
                $where_epwm = array(
                    'sku' => $fov['sku'],
                    'status' => 0
                );
                $epm_res = $epmeatilModel->where($where_epwm)->find();
                if (!empty($epm_res)) {
                    $epmeatilModel->where($where_epwm)->update(['status' => 1]);
                }
            } else {
                //不存在id新增数据
                $fov['fba_id'] = $data['fba_id'];
                $fov['create_time'] = date('Y-m-d H:m:s', time());
                unset($fov['id']);
                $res_up = $this->saveFbaOrder($fov);
                if ($res_up == false) {
                    return json(['code' => 1001, 'msg' => '保存失败']);
                }
                $where_epwm = array(
                    'sku' => $fov['sku'],
                    'status' => 0
                );
                $epm_res = $epmeatilModel->where($where_epwm)->find();
                if (!empty($epm_res)) {
                    $epmeatilModel->where($where_epwm)->update(['status' => 1]);
                }
            }
            unset($res_up,$epm_res,$where_epwm);
        }

        return json(['code' => 1000, 'msg' => '保存成功']);
    }


    //新增
    public function save_fba($data) {

        $FbaModel = new Fbadelivery();
        $fba_res = $FbaModel->saveFba($data);//返回添加结果包含fbaid
        if ($fba_res['code'] == 1001) {
            return json($fba_res);
        }
        return $fba_res;
    }
    //更新
    public function update_fba($data) {

        $FbaModel = new Fbadelivery();
        $fba_res = $FbaModel->updateFba($data);//返回添加结果包含fbaid
        if ($fba_res['code'] == 1001) {
            return json($fba_res);
        }
        return $fba_res;
    }

    //保存
    public function ApiSaveFba() {
        header('Content-Type:application/json');

        if (!$this->request->isPost()) {
            return json(['code' => 1002, 'msg' => '', 'data' => '']);
        }
        $data = input('post.');

        if (empty($data) || !is_array($data)) {
            return json(['code' => 1001, 'msg' => '传参为空', 'data' => '']);
        }
        $fba_save = array();
        //OrdNum+OdrId区分
        $fba_save['fba_nums'] = $data['fba_nums'];
        $fba_save['contact'] = $data['contact'];
        $fba_save['shop_name'] = $data['shop_name'];
        $fba_save['expect_delivery_time'] = $data['expect_delivery_time'];
        $fba_save['express_way'] = $data['express_way'];
        $fba_save['EORI'] = $data['EORI'];
        $fba_save['note'] = $data['note'];
        $fba_save['agent_id'] = $data['agent_id'];
        $fba_save['addr'] = $data['addr'];
        $fba_save['fba_name'] = $data['fba_name'];
        $fba_save['plan_id'] = $data['plan_id'];
        $fba_save['create_time'] = date('Y-m-d H:m:s', time());
        $order_arr = array();
        $FbaModel = new Fbadelivery();
        $where_va = array(
            'fba_nums' => $data['fba_nums'],
            'skus' => $data['skus']
        );
        $veri_data = $FbaModel->where($where_va)->find();
        if (!empty($veri_data)) {
            return json(['code' => 1001, 'msg' => '改订单已经存在，请重试！']);
        }
        $fba_res = $FbaModel->saveFba($fba_save);//返回添加结果包含fbaid
        if ($fba_res['code'] == 1001) {
            return json($fba_res);
        }
        foreach($data['order'] as $order_key => $order_value) {
            $order_arr[$order_key] = array(
                'fba_id' => $fba_res['id'],
                'order_id' => $order_value['order_id'],
                'sku' => $order_value['sku'],
                'img' => $order_value['img'],
                'number' => $order_value['number'],
                'size' => $order_value['size'],
                'fnsku' => $order_value['fnsku'],
                'name' => $order_value['name'],
                'gdsnum' => $order_value['gdsnum'],
                'title' => $order_value['title'],
                'asin' => $order_value['asin'],
                'condition' => $order_value['condition'],
                'who_will_prep' => $order_value['who_will_prep'],
                'prep_type' => $order_value['prep_type'],
                'who_will_label' => $order_value['who_will_label'],
                'fbasku' => $order_value['fbasku'],
                'create_time' => $fba_save['create_time'],
            );
        }

        $fbaorderModel = new Fbaorder();
        $fbaorder = $fbaorderModel->saveFbaorder($order_arr);
        if ($fbaorder['code'] == 1001) {
            return json($fbaorder);
        }

        return json(['code' => 1000, 'msg' => '保存成功']);
    }

    //修改fba_order子表
    public function updateFbaOrder($data, $id = '') {
        $fbaorderModel = new Fbaorder();

        $res = $fbaorderModel->update($data);

        return $res;
    }

    //新增fba_order子表
    public function saveFbaOrder($data) {
        $fbaorderModel = new Fbaorder();

        $res = $fbaorderModel->save($data);

        return $res;
    }

    //单个字段修改数据
    public function ApiEditOneField() {
        header('Content-Type:application/json');
        if (!$this->request->isPost()) {
            return json(['code' => 1002, 'msg' => '此为post接口。', 'data' => '']);
        }

        $field = input('post.field', '', 'trim');
        $data = input('post.data', '', 'trim');
        $id = input('post.id', '', 'trim');

        if ($field == '' || $data == '' || $id == '') {
            return json(['code' => 1001, 'msg' => '要修改的字段或数据为空', 'data' => '']);
        }

        $fbadeliveryModel = new Fbadelivery();
        $fba = $fbadeliveryModel->where('id', $id)->update([$field => $data]);
        return json(['code' => $fba ? 1000 : 1001, 'msg' => $fba ? '更新成功' : '更新失败']);
    }

    //修改留言字段-在用
    public function ApiEditMsg() {
        header('Content-Type:application/json');
        if (!$this->request->isPost()) {
            return json(['code' => 1002, 'msg' => '此为post接口。', 'data' => '']);
        }

        $data = input('post.data', '', 'trim');
        $id = input('post.id', '', 'trim');

        $fbadeliveryModel = new Fbadelivery();
        $fba = $fbadeliveryModel->where('id', $id)->update(['note' => $data, 'new_msg' => 1]);//new_msg:新留言就修改状态
        return json(['code' => $fba ? 1000 : 1001, 'msg' => $fba ? '更新成功' : '更新失败']);
    }

    //编辑主表状态,在用 plan_status
    public function ApiChangeFbaStatus() {
        header('Content-Type:application/json');

        if (!$this->request->isPost()) {
            return json(['code' => 1002, 'msg' => '此为post接口', 'data' => '']);
        }
        //fba计划的状态-1已取消，0新增，1已提交，2已接收，3已封箱，4可发货，5已完成

        $id = input('post.id', '', 'trim');
        $plan_status = input('post.plan_status');
        if ($id == '' || !is_numeric($plan_status)) {
            return json(['code' => 1001, 'msg' => 'id或状态参数为空']);
        }
        $FbaModel = new Fbadelivery();
        $fba = $FbaModel->where('id', $id)->find();
        $fbaOrderModel = new Fbaorder();
        if ($plan_status == 4) {
            //修改状态为4的时候
            $sum_pick_number = $fbaOrderModel->field('SUM(number),SUM(picking_num)')->where('fba_id', $id)->select();
            if ($sum_pick_number[0]['SUM(picking_num)'] < $sum_pick_number[0]['SUM(number)']) {
                $FbaModel->where('id', $id)->update(['early_delivery' => 1]);//状态为4，标记是否未配齐，提前发货
            }
        }

        $res = $FbaModel->where('id', $id)->update(['plan_status' => $plan_status, 'submit_time' => date('Y-m-d H:m:s', time())]);
        $FbaNewsModel = new FbaNews();
        if ($res) {
             if ($plan_status == 3 || $plan_status == 4) {
                 $news_add = array(
                     'fba_id' => $id,
                     'agent_id' => $fba['agent_id'],
                     'plan_status' => $plan_status,
                     //'view_user' => $fba['agent_id'],//查看人id
                     'creat_time' => time(),
                 );
                 $save_news = $FbaNewsModel->save($news_add);
             }
        }
        return json(['code' => $res ? 1000 : 1001, 'msg' => $res ? '操作成功！' : '操作失败！']);
    }

    //添加fba中的订单
    public function FbaOrderAdd() {
        header('Content-Type:application/json');
        $fbaOrderModel = new Fbaorder();
        $data = input('post.data/a');
        //fba_id+sku+order_id区分

        $add_data = array();
        foreach ($data as $v) {
            $where = array(
                'fba_id' => $v['fba_id'],
                'order_id' => $v['order_id'],
                'sku' => $v['sku']
            );
            $old_order = $fbaOrderModel->where($where)->find();
            if (empty($old_order)) {
                $add_data[] = $v;
            }
            unset($where);
        }

        if (count($add_data) <= 0) {
            return json(['code' => 1001, 'msg' => '数据已存在！']);
        }
        $res = $fbaOrderModel->saveAll($add_data);
        return json(['code' => $res ? 1000 : 1001, 'msg' => $res ? '修改成功！' : '修改失败！']);
    }

    //删除fba
    public function ApiDeleteFba() {
        header('Content-Type:application/json');
        if (!$this->request->isPost()) {
            return json(['code' => 1002, 'msg' => '此为post接口', 'data' => '']);
        }
        $ids = input('post.ids/a');
        $FbaModel = new Fbadelivery();
        $FbaOrderModel = new Fbaorder();
        $is_delete_fba = true;
        foreach ($ids as $id) {
            $fba_data = $FbaModel->field('plan_status')->where('id', $id)->find();
            if ($fba_data['plan_status'] != 0) {
                $is_delete_fba = false;//如果状态不为0就不能删除
            }
        }

        if (!$is_delete_fba) {
            return json(['code' => 1001, 'msg' => '删除失败！（订单状态已提交）']);
        }

        $FbaModel->destroy($ids, true);

        foreach ($ids as $value) {
            $is_exists = file_exists(ROOT_SAVE_FBA_BOX_LABEL . $value . ".txt");
            if ($is_exists) unlink(ROOT_SAVE_FBA_BOX_LABEL . $value . ".txt");
        }
        /*
         * $FbaOrderModel->destroy(function($query)use($ids){$query->where('fba_id','in',$ids);});*/
        $fba_order = $FbaOrderModel->where(['fba_id' => ['in', $ids]]);
        $fba_order->delete(true);

        return json(['code' => 1000, 'msg' => '删除成功']);
    }

    //删除订单
    public function ApiDeleteFbaOrder() {
        header('Content-Type:application/json');
        if (!$this->request->isPost()) {
            return json(['code' => 1002, 'msg' => '此为post接口', 'data' => '']);
        }
        $ids = input('post.ids/a');
        $FbaOrderModel = new Fbaorder();
        $FbaModel = new Fbadelivery();
        $is_delete_fba = true;//是否能删除
        $fba_id = $FbaOrderModel->distinct(true)->field('fba_id')->where(['id' => ['in', $ids]])->select();
        //做个排重
        foreach ($fba_id as $id) {
            $fba_data = $FbaModel->field('plan_status')->where('id', $id['fba_id'])->find();
            if ($fba_data['plan_status'] != 0) {
                $is_delete_fba = false;//如果状态不为0就不能删除
            }
        }

        if (!$is_delete_fba) {
            return json(['code' => 1001, 'msg' => '删除失败！（订单状态已提交）']);
        }
        $FbaOrderModel->destroy($ids, true);

        return json(['code' => 1000, 'msg' => '删除成功']);
    }

    //删除箱标
    public function ApiDeleteFbaBoxLabel() {
        header('Content-Type:application/json');
        if (!$this->request->isPost()) {
            return json(['code' => 1002, 'msg' => '此为post接口', 'data' => '']);
        }
        $ids = input('post.ids/a');
        $fbaModel = new Fbadelivery();
        foreach ($ids as $value) {
            $fbaModel->where('id', $value)->update(['file_format' => '']);
            $is_exists = file_exists(ROOT_SAVE_FBA_BOX_LABEL . $value . ".txt");
            if ($is_exists) unlink(ROOT_SAVE_FBA_BOX_LABEL . $value . ".txt");
        }

        return json(['code' => 1000, 'msg' => '删除成功']);
    }

    //下载箱标
    public function ApiDownloadLabel() {
        header('Content-Type:application/json');
        if (!$this->request->isPost()) {
            return json(['code' => 1002, 'msg' => '此为post接口']);
        }
        $fba_id = input("post.fba_id", "", "trim");
        if (empty($fba_id)) {
            return json(['code' => 1001, 'msg' => 'id不可为空']);
        }
        $fbaModel = new Fbadelivery();
        $fba_data = $fbaModel->where('id', $fba_id)->find();
        $file_exi = file_exists(ROOT_SAVE_FBA_BOX_LABEL . $fba_id . '.txt');
        if ($fba_data != '' && $file_exi) {
            $file_res = file_get_contents(ROOT_SAVE_FBA_BOX_LABEL . $fba_id . '.txt');//读,返回字符数，失败false
        } else {
            $file_res = false;
            $fba_data['file_format'] = '';
        }

        return json(['code' => $file_res !== false ? 1000 : 1003, 'msg' => $file_res !== false ? '文件获取成功' : '文件获取失败', 'base64_code' => $file_res, 'file_format' => $fba_data['file_format']]);
    }

    //上传箱标文件接口
    public function ApiUploadBoxLabel() {
        header('Content-Type:application/json');
        header("Access-Control-Allow-Origin:*");
        if (!$this->request->isPost()) {
            return json(['code' => 1002, 'msg' => '此为post接口']);
        }
        $base64_code = input("post.base64_code", "", "trim");
        $fba_id = input("post.fba_id", "", "trim");
        $file_format = input("post.file_format", "", "trim");
        if (empty($fba_id) || empty($base64_code)) {
            return json(['code' => 1001, 'msg' => 'id或文件不可为空']);
        }

        $FbaModel = new Fbadelivery();
        $FbaModel->where('id', $fba_id)->update(['file_format' => $file_format]);//下载文件后缀

        $file_res = file_put_contents(ROOT_SAVE_FBA_BOX_LABEL . $fba_id . '.txt', $base64_code);
        //写,返回字符数，失败false
        return json(['code' => $file_res !== false ? 1000 : 1001, 'msg' => $file_res !== false ? '文件保存成功':'文件保存失败']);
        //$file = file_get_contents($formPdf);//读
        //$data = base64_encode($file);//转换
        //file_put_contents(ROOT_SAVE_FBA_BOX_LABEL . '10201', $base64_code);//写,返回字符数，失败false
    }

    //下载明细
    public function ApiDownloadDetailed() {
        header('Content-Type:application/json');
        header("Access-Control-Allow-Origin:*");
        if (!$this->request->isPost()) {
            return json(['code' => 1002, 'msg' => '此为post接口']);
        }
        $fba_id = input('post.id');
        $FbaModel = new Fbadelivery();
        $res = $FbaModel->where('id', $fba_id)->relation('fbaOrders,fbaBoxLabel')->find();
        return json($res);
    }


    //提醒接口
    public function ApiReminderStart() {
        header('Content-Type:application/json');
        header("Access-Control-Allow-Origin:*");
        if (!$this->request->isPost()) {
            return json(['code' => 1002, 'msg' => '此为post接口']);
        }

        $start = input("post.remind_status", "", "trim");
        $fba_id = input("post.id", "", "trim");
        if (empty($fba_id) || $start == '') {
            return json(['code' => 1001, 'msg' => 'id或状态值不可为空']);
        }

        $fbaModel = new Fbadelivery();
        $rem_status = $fbaModel->where('id', $fba_id)->update(['remind_status' => $start]);
        return json(['code' => $rem_status !== false ? 1000 : 1001, 'msg' => $rem_status !== false ? '保存成功':'保存失败']);
    }

    //下载装箱列表
    public function ApiDownLoadBox() {
        header('Content-Type:application/json');
        header("Access-Control-Allow-Origin:*");
        if (!$this->request->isPost()) {
            return json(['code' => 1002, 'msg' => '此为post接口']);
        }
        $fba_id = input("post.id", "", "trim");

        $fbaModel = new Fbadelivery();
        $res = $fbaModel->relation('fbaOrders,fbaBoxLabel,fbaCodePick')->where('id', $fba_id)->find();
        /*if ($res['plan_status'] < 3) {
            return json(['code' => 1001, '未装箱']);
        }*/
        $data = array();
        $data['ShipmentID'] = $res['fba_nums'];
        $data['Name'] = $res['fba_name'];
        $data['PlanID'] = $res['plan_id'];
        $data['TotalSKUs'] = count($res['fbaOrders']);
        $total_units = 0;
        $order = array();
        foreach ($res['fbaOrders'] as $k => $v) {
            $total_units += $v['number'];
            $order[$v['id']] = array(
                //'order_no' => $v['order_no'],
                'MerchantSKU' => $v['fbasku'],
                'ASIN' => $v['asin'],
                'Title' => $v['title'],
                'FNSKU' => $v['fnsku'],
                'ExternalID' => "--",
                'Whowillprep' => $v['who_will_prep'],
                'PrepType' => $v['prep_type'],
                'Whowilllabel' => $v['who_will_label'],
                'ExpectedQTY' => $v['number'],
                'BoxedQTY' => $v['number'],
            );
            $order_box = array();
            foreach ($res['fbaBoxLabel'] as $bk => $bv) {
                //循环
                $bk_one = $bk + 1;
                foreach ($res['fbaCodePick'] as $ck => $cv) {
                    if ($bv['id'] == $cv['fba_box_id'] && $v['id'] == $cv['fba_order_id']) {
                        $order_box['Box'. $bk_one .'-QTY'] = $cv['num'];
                        break;
                    } else {
                        $order_box['Box'. $bk_one .'-QTY'] = 0;
                    }
                }

            }

            $order[$v['id']]['box'] = array_values($order_box);
            unset($bk_one,$order_box);
        }

        unset($bk_one,$order_box);
        $data['TotalUnits'] = $total_units;
        $data['order'] = array_values($order);
        foreach ($res['fbaBoxLabel'] as $bk2 => $bv2) {
            $bk_one = $bk2 + 1;
            //$data['box'][0]['Box' . $bk2] = 'Box'. $bk_one;
            $data['weight']['Box' . $bk_one] = $bv2['case_weight'];
            $data['length']['Box' . $bk_one] = $bv2['case_length'];
            $data['width']['Box' . $bk_one] = $bv2['case_width'];
            $data['height']['Box' . $bk_one] = $bv2['case_height'];
        }
        if (isset($data['weight'])) {
            $data['weight'] = array_values($data['weight']);
        } else {
            $data['weight'] = array();
        }
        if (isset($data['length'])) {
            $data['length'] = array_values($data['length']);
        } else {
            $data['length'] = array();
        }
        if (isset($data['width'])) {
            $data['width'] = array_values($data['width']);
        } else {
            $data['width'] = array();
        }
        if (isset($data['height'])) {
            $data['height'] = array_values($data['height']);
        } else {
            $data['height'] = array();
        }
        //$data = $box_arr;
        return json(['code' => 1000, 'data' => $data]);
    }

}
