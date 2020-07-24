<?php
namespace app\admin\controller;

use \app\admin\controller;
use app\admin\model\EpOutInDetails;
use app\admin\model\FbaBoxLabel;
use app\admin\model\FbaCodePicking;
use app\admin\model\Fbaorder;
use \think\Db;
use app\admin\model\Fbadelivery;
use app\admin\model\Order;
use app\admin\model\EpWarehouseMaterialdetail;
use app\admin\model\EpWarehouse;
use app\admin\model\EpWarehouseTable;
use Mpdf\Mpdf;
use app\admin\model\FbaNews;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Index
 *
 * @author lkw
 */
class Fba extends Excel {
    protected $pageTotalItem=100;
    protected $plan_status_name = array(
        -1 => '已取消',
        0 => '新增',
        1 => '新计划',//已提交
        2 => '待装箱',//已接收
        3 => '已装箱',
        4 => '可发货',
        5 => '已完成',
    );
    protected $plan_status_color = array(
        -1 => 'yiquxiao',
        0 => 'xinzeng',
        1 => 'yitijiao',
        2 => 'yijieshou',
        3 => 'yizhuangxiang',
        4 => 'yifahuo',
        5 => 'yiwancheng',
    );
    public function __construct() {
        parent::__construct();
        $this->assign('currentMenu',array('menu'=>'menu14','nav'=>'nav0'));
    }

    //任务列表
    public function lists() {
        $url_style = input('request.url_style', 'lists', 'trim');

        $search = input('request.search', '', 'trim');
        $plan_status = input('request.plan_status', 100, 'trim');
        $payment_status = input('request.payment_status', '2', 'trim');
        $agent_id = input('request.agent_id', '', 'trim');
        $startData=session('fba_start_time')==""?date('Y-m-d',strtotime('-60 day')):session('fba_start_time');
        $endData=session('fba_end_time')==""?date('Y-m-d'):session('fba_end_time');
        $start_time = input('request.start_time');
        $end_time = input('request.end_time');
        $time_sort = input('request.time_sort', '0', 'trim');//0:全部，1期望发货时间，2下单时间
        $is_handle = input('request.is_handle', '', 'trim');//是否处理0未处理，1处理
        //$reload_mobile = input('request.reload_mobile');//1表示从pc过来，0表示其他
        if(!empty($start_time))$startData=input('request.start_time');
        if(!empty($end_time))$endData=input('request.end_time');

        //设置时间类型保存到session
        session('fba_time_type',input('request.sdate') != 'custom'? '1':'2');

        if(session('fba_time_type') == '1'){
            $times = strtotime(date('Y-m-d')) - strtotime($endData);
            $startData = date('Y-m-d',strtotime($startData) + $times);
            $endData =date('Y-m-d',strtotime($endData) + $times);
        }

        session('fba_start_time',$startData);
        session('fba_end_time',$endData);

        //fbaorders
        $db_view = Db::view('fbadelivery fba', "*");
        //$db_view->where(['fba.delete_time' => ['EQ', NULL]]);
        if ($search != '') {
            /*$where_search = array(
                'fba_nums' => "$search",
            );*/

            //$db_view->whereOr($where_search);
            $is_box_label = substr($search, 0, 1);
            if ($is_box_label == 'B') {
                $where_fba_order = array(
                    'id' => substr($search, 1),
                );
                $db_view->where($where_fba_order);
            } else {
                $where_fba_order = array(
                    'sku|fnsku|fba_nums|order_no|OdrId' => "$search",
                );
                $db_view->view('fba_order fo', 'fba_id', 'fo.fba_id=fba.id')->whereOr($where_fba_order)->group('fba.id');
            }
        }

        $where_time['fba.create_time'] = ['between', [$startData . ' 00:00:00', $endData . ' 23:59:59']];
        $db_view->where($where_time);

        if ($plan_status == 99) {
            //99为有业务新留言
            $where_new_msg = array(
                'new_msg' => 1,
                'plan_status' => ['in', '1,2,3,4,5']
            );
            $db_view->where($where_new_msg);
        } else {
            if ($plan_status != 100) {
                $where_pls['plan_status'] = $plan_status;
                $db_view->where($where_pls);
            } else {
                //判断是哪个栏目
                if ($url_style == 'picking') {
                    $where_pls['plan_status'] = ['in', '1,2,3,5'];
                } else if ($url_style == 'deliver_gds') {
                    $where_pls['plan_status'] = ['in', '3,4,5'];
                } else {
                    $where_pls['plan_status'] = ['in', '1,2,3,4,5'];
                }
                $db_view->where($where_pls);
            }
            if ($is_handle != '') {
                $where_is_handle['is_handle'] = $is_handle;
                $db_view->where($where_is_handle);
            }
            if ($agent_id != '') {
                $where_agent_id['agent_id'] = $agent_id;
                $db_view->where($where_agent_id);
            }
            if ($payment_status == 0 || $payment_status == 1) {
                $where_pas['payment_status'] = $payment_status;
                $db_view->where($where_pas);
            }
        }

        if ($plan_status >= 5 && $plan_status < 6) {
            $db_view->order('fba.success_time desc');
        } else if ($plan_status >= 2 && $plan_status < 3) {
            if ($is_handle == '') {
                $db_view->order('fba.topping desc, fba.is_handle desc');
            } else {
                $db_view->order('fba.topping desc, fba.id asc');
            }
        } else {
            if ($time_sort == 1) {
                $db_view->order('fba.expect_delivery_time asc, fba.id asc');
            } else if ($time_sort == 2) {
                $db_view->order('fba.create_time asc, fba.id asc');
            } else {
                $db_view->order('fba.id asc');
            }
        }


        $list = $db_view->paginate($this->pageTotalItem, false, ['query' =>request()->param()]);

        $fbaorderModel = new Fbaorder();
        $fbaboxlabelModel = new FbaBoxLabel();
        $list_each = array();
        $epmeatilModel = new EpWarehouseMaterialdetail();
        $fba_id_arr = array();
        foreach ($list as $k => $v) {
            $fba_id_arr[] = $v['id'];
            $v['sum_pick_number'] = $fbaorderModel->field('SUM(number),SUM(picking_num)')->where('fba_id', $v['id'])->select();
            $v['fba_box'] = $fbaboxlabelModel->relation('fbaCodePick')->where('fba_id', $v['id'])->select();
            $order = $fbaorderModel->where('fba_id', $v['id'])->select();
            $v['ware_enough'] = 0;
            $enough_num = 0;
            $stock_num = 0;
            $all_num = 0;
            if (count($order) > 0) {
                foreach ($order as $ek => $ev) {
                    $where_sku = array(
                        'sku' => $ev['sku'],//sku查询即可
                    );
                    $epwm = $epmeatilModel->field('SUM(count)')->where($where_sku)->select();
                    $now_gds = $epwm[0]['SUM(count)'] + $ev['picking_num'];//现在已拣货和仓库库存之和
                    if ($now_gds >= $ev['number']) {
                        $stock_num += $ev['number'];
                        $sub = $ev['number'] - $ev['picking_num'];
                        $enough_num += $epwm[0]['SUM(count)'];//$sub;
                        unset($sub);
                    } else {
                        $stock_num += $now_gds;
                        $enough_num += $epwm[0]['SUM(count)'];//$now_gds;
                    }
                    $all_num += $ev['number'];
                    unset($where_sku, $epwm, $now_gds);
                }
            }
            if ($stock_num >= $all_num) $v['ware_enough'] = 1;
            $v['ware_enough_num'] = $enough_num;
            $list_each[$k] = $v;
            unset($enough,$enough_num,$all_num,$stock_num);
        }

        if ($url_style == 'picking') {
            $this->assign('currentMenu',array('menu'=>'menu14','nav'=>'nav1'));
        } else if ($url_style == 'deliver_gds') {
            $this->assign('currentMenu',array('menu'=>'menu14','nav'=>'nav2'));
        } else {
            $this->assign('currentMenu',array('menu'=>'menu14','nav'=>'nav0'));
        }
        $this->assign('list',$list_each);
        $this->assign('payment_status',$payment_status);
        $this->assign('plan_status',$plan_status);
        $this->assign('time_sort',$time_sort);
        $this->assign('pageDiv', $list->render());
        $this->assign('lastpage', $list->lastPage());
        $this->assign('currentpage', $list->currentPage());
        $this->assign('date',array('start_time'=>$startData,'end_time'=>$endData));
        $this->assign('start_time', $startData);
        $this->assign('end_time', $endData);
        $this->assign('is_handle', $is_handle);
        $this->assign('url_style', $url_style);
        $this->assign('plan_status_name', $this->plan_status_name);
        $this->assign('plan_status_color', $this->plan_status_color);
        $this->assign('eventJS', 'fba');
        return $this->fetch();
    }

    //打印码拣货
    public function code_pick() {
        $search = input('request.search', '', 'trim');
        $sku_arr = explode('_', $search);
        if (count($sku_arr) >= 3) {
            $sku = $sku_arr[0];
        } else {
            $sku = $search;
        }
        $real_sku = '';

        if ($search == '') {
            $list_each = array();
        } else {
            $db_view = Db::view('fbadelivery fba', "*");
            $where_fba_order = array(
                'sku|fnsku|OdrId' => "$sku",
            );

            $db_view->view('fba_order fo', 'fba_id', 'fo.fba_id=fba.id')->whereOr($where_fba_order)->group('fba.id');
            $where_plan_status['plan_status'] = ['in', '1,2'];
            $db_view->where($where_plan_status);

            $db_view->order('fba.id asc');
            $list = $db_view->paginate(200, false, ['query' => request()->param()]);
            $list_each = array();
            $fbaorderModel = new Fbaorder();
            $fbaboxlabelModel = new FbaBoxLabel();

            foreach ($list as $k => $v) {
                $v['order'] = $fbaorderModel->where(array('fba_id' => $v['id'], 'sku|fnsku|OdrId' => "$sku"))->find();
                $list_each[] = $v;
                $real_sku = $v['order']['sku'];
            }
        }
        if ($real_sku != '') {
            $sku_id = getNeedBetween($real_sku, 'g' , 'p' );
            $real_sku = $real_sku . '_' . $sku_id . '_' . time();
        }
        $this->assign('currentMenu',array('menu'=>'menu14','nav'=>'nav3'));
        $this->assign('list',$list_each);
        $this->assign('plan_status_name', $this->plan_status_name);
        $this->assign('plan_status_color', $this->plan_status_color);
        $this->assign('search_sku', $real_sku);
        $this->assign('eventJS', 'fba');
        return $this->fetch();
    }
    //通过sku_id获取,下载图片
    public function print_sku_id_size() {
        $sku = input('request.sku');
        $size = input('request.size');
        $sku_id = getNeedBetween($sku, 'g' , 'p' );
        if ($sku_id == 0) return '';
        $url = skuIdSizeQrCode($sku, $sku_id, $size);
        header('Content-Disposition:attachment;filename=' . $sku . '.png');
        //header('Content-Length:' . filesize($filename));
        readfile($url);
    }

    //对新留言已经处理功能
    public function move_new_msg() {
        $id = input('post.id');
        $fbaModel = new Fbadelivery();

        $update_res = $fbaModel->where('id', $id)->update(['new_msg' => 0]);
        return json(['code' => $update_res ? 1000 : 1001, 'msg' => $update_res ? '操作成功' : '操作失败，请刷新重试']);
    }

    //提醒消息,装箱
    public function get_fba_zx() {
        //fba计划的状态-1已取消，0新增，1已提交，2已接收，3已封箱，4可发货，5已完成
        //$FbaModel = new Fbadelivery();
        $FbaNewsModel = new FbaNews();
        $where_news = array(
            'views' => 0
        );
        $news = $FbaNewsModel->where($where_news)->select();
        $res_3 = 0;
        $res_4 = 0;
        foreach ($news as $k => $v) {
            if ($v['plan_status'] == 3) {
                $res_3 += 1;
            } else if ($v['plan_status'] == 4) {
                $res_4 += 1;
            }
        }
        /*$where_3 = array(
            'plan_status' => 3,
            //expect_delivery_time
        );
        $res_3 = $FbaModel->where($where_3)->select();
        $where_4 = array(
            'plan_status' => 4,
            //expect_delivery_time
        );
        $res_4 = $FbaModel->where($where_4)->select();*/
        if ($res_3 > 0 || $res_4 > 0) {
            $data = array(
                'code' => 1000,
                'msg' => '有新消息',
                'count_3' => $res_3,
                'count_4' => $res_4,
            );
            return json($data);
        }
        return json(['code' => 1001]);
    }

    //查看消息，则修改已经查看,不在显示
    public function check_news() {
        $plan_status = input('request.plan_status');
        $FbaNewsModel = new FbaNews();
        $where = array(
            'plan_status' => $plan_status,
            'views' => 0,
        );
        $update_res = $FbaNewsModel->where($where)->update(['views' => 1, 'view_user' => session('admin_id'), 'view_time' => time()]);
        return json(['data' => $update_res]);
    }

    //提醒消息，发货
    public function get_fba_fh() {
        //fba计划的状态-1已取消，0新增，1已提交，2已接收，3已封箱，4可发货，5已完成
        $FbaModel = new Fbadelivery();
        $where = array(
            'plan_status' => 3,
            //expect_delivery_time
        );
        $res = $FbaModel->where($where)->select();
        if (count($res) > 0) {
            $data = array(
                'code' => 1000,
                'msg' => '有新消息',
                'count' => count($res),
            );
            return json($data);
        }
        return json(['code' => 1001]);
    }

    //箱子列表
    public function box_lists() {
        $fba_id = input('request.fba_id', '', 'trim');
        $box_id = input('request.box_id', '', 'trim');
        //$FbaModel = new Fbadelivery();
        $FbaOrderModel = new Fbaorder();
        $FbaBoxModel = new FbaBoxLabel();
        $FbaCodePickingModel = new FbaCodePicking();

        $res = $FbaBoxModel->relation('fbaCodePick')->where('fba_id', $fba_id)->select();
        $code_pick = $FbaCodePickingModel->where('fba_id', $fba_id)->select();

        if (count($code_pick) == 0) {
            $cp['img'] = '';
            $this->assign('cp', $cp);
        }
        $code_pick_nums = array();//已经装箱的数量，数组键order_id对值数量
        foreach ($code_pick as $cpk => $cpv) {
            if (isset($code_pick_nums[$cpv['fba_order_id']])) {
                $code_pick_nums[$cpv['fba_order_id']] += $cpv['num'];
            } else {
                $code_pick_nums[$cpv['fba_order_id']] = $cpv['num'];
            }
        }
        $order = $FbaOrderModel->where('fba_id', $fba_id)->select();
        $order_res = array();
        foreach ($order as $ok => $ov) {
            if (isset($code_pick_nums[$ov['id']])) {
                if ($ov['picking_num'] <= $code_pick_nums[$ov['id']]) {
                    continue;
                } else {
                    //大于则减去
                    $ov['picking_num'] = $ov['picking_num'] - $code_pick_nums[$ov['id']];
                    $order_res[] = $ov;
                }
            } else {
                $order_res[] = $ov;
            }
        }
        if (count($order_res) == 0) {
            $oval['img'] = '';
            $this->assign('oval', $oval);
        }
        $this->assign('currentMenu',array('menu'=>'menu14','nav'=>'nav0'));
        $this->assign('list', $res);
        $this->assign('order', $order_res);
        $this->assign('code_pick', $code_pick);
        $this->assign('fba_id', $fba_id);
        $this->assign('box_id', $box_id);
        $this->assign('eventJS', 'fba');
        return $this->fetch();
    }

    //复制箱子
    public function copy_save() {
        $fba_id = input('request.fba_id', '', 'trim');
        $box_id = input('request.box_id', '', 'trim');
        $copy_nums = input('request.copy_nums', '', 'trim');
        $content_arr = input('request.content_arr/a', array());
        $fbaBoxModel = new FbaBoxLabel();
        $fbaCodePickingModel = new FbaCodePicking();//箱子内容表
        //$fbaModel = new Fbadelivery();

        //$fba = $fbaModel->where('id', $fba_id)->find();
        $box = $fbaBoxModel->where('id', $box_id)->find();
        $content = array();//箱子内容
        $str_case_no = trim($box['case_no']);
        $case_no = '';
        for($cni = 0; $cni < strlen($str_case_no); $cni++){
            if(is_numeric($str_case_no[$cni])){
                $case_no .= $str_case_no[$cni];
            }
        }
        $str_letter = str_replace($case_no, '', $str_case_no);
        if (count($content_arr) > 0) {
            foreach ($content_arr as $ck => $cv) {
                $content[$cv] = $fbaCodePickingModel->relation('fbaOrders')->where('id', $cv)->find();
                $fba_order_id = $content[$cv]['fba_order_id'];
                $where_cont = array(
                    'fba_id' => $fba_id,
                    'fba_order_id' => $fba_order_id,
                );
                $count_num = $fbaCodePickingModel->field('SUM(num)')->where($where_cont)->select();//该订单已经装箱的数量
                $picking_num = $content[$cv]['fbaOrders']['picking_num'];//该订单已经拣货的数量
                $content[$cv]['surplus'] = (int)$picking_num - (int)$count_num[0]['SUM(num)'];
            }

            $box_save = array();

            for ($i = 1; $i <= $copy_nums; $i++) {
                $case_no = $case_no + 1;
                $new_case_no = $str_letter . $case_no;

                $box_save[$i]['box'] = array(//箱子表
                    'fba_id' => $fba_id,
                    'case_no' => $new_case_no,
                    'case_location' => $box['case_location'],
                    'case_weight' => $box['case_weight'],
                    'price' => $box['price'],
                    'wt_id' => $box['wt_id'],
                    'case_length' => $box['case_length'],
                    'case_width' => $box['case_width'],
                    'case_height' => $box['case_height'],
                    'create_time' => date('Y-m-d H:i:s', time()),
                );
                foreach ($content as $bck => &$bcv) {
                    $box_save[$i]['pick'][$bck] = array(//装箱表
                        'fba_id' => $fba_id,
                        'fba_order_id' => $bcv['fbaOrders']['id'],
                        'img' => $bcv['fbaOrders']['img'],
                        'sku' => $bcv['fbaOrders']['sku'],
                        'size' => $bcv['fbaOrders']['size'],
                        'fnsku' => $bcv['fbaOrders']['fnsku'],
                        'case_no' => $new_case_no,
                        'create_time' => date('Y-m-d H:i:s', time()),
                    );
                    if ($bcv['surplus'] > 0) {
                        $surplus = (int)$bcv['surplus'] - (int)$bcv['num'];
                        if ($surplus >= 0) {
                            $box_save[$i]['pick'][$bck]['num'] = $bcv['num'];
                            $bcv['surplus'] = $surplus;
                        } else {
                            $box_save[$i]['pick'][$bck]['num'] = $bcv['surplus'];
                            $bcv['surplus'] = 0;
                        }
                    } else {
                        unset($box_save[$i]['pick'][$bck]);
                        //$box_save[$i]['pick'][$bck]['num'] = 0;
                    }
                }
                unset($bcv);
            }
            $save_pick = array();
            foreach ($box_save as $bsk => $bsv) {
                $box_res = $fbaBoxModel->insertGetId($bsv['box']);

                foreach ($bsv['pick'] as $bspv) {
                    $bspv['fba_box_id'] = $box_res;
                    $save_pick[] = $bspv;
                }
                unset($box_res);
            }
            if (count($save_pick) > 0) {
                $res = $fbaCodePickingModel->saveAll($save_pick);
            } else {
                $res = 1;
            }
            return json(['code' => $res ? 1000 : 1001, 'msg' => $res ? '复制成功' : '复制失败']);
        }

        $box_save = array();
        for ($i = 1; $i <= $copy_nums; $i++) {
            $case_no = $case_no + 1;
            $new_case_no = $str_letter . $case_no;
            $box_save[] = array(
                'fba_id' => $fba_id,
                'case_no' => $new_case_no,
                'case_location' => $box['case_location'],
                'case_weight' => $box['case_weight'],
                'price' => $box['price'],
                'wt_id' => $box['wt_id'],
                'case_length' => $box['case_length'],
                'case_width' => $box['case_width'],
                'case_height' => $box['case_height'],
                'create_time' => date('Y-m-d H:i:s', time()),
            );
        }

        $res = $fbaBoxModel->saveAll($box_save);
        return json(['code' => $res ? 1000 : 1001, 'msg' => $res ? '复制成功' : '复制失败']);
    }

    //复制箱子,获取箱子信息
    public function getBoxContent() {
        $this->view->engine->layout(false);
        $fba_id = input('request.fba_id', '', 'trim');
        $box_id = input('request.box_id', '', 'trim');
        //$fbaBoxModel = new FbaBoxLabel();
        $fbaCodePickingModel = new FbaCodePicking();//箱子内容表格

        $where = array(
            'fba_id' => $fba_id,
            'fba_box_id' => $box_id
        );
        $box_cont = $fbaCodePickingModel->where($where)->select();
        $this->assign('list', $box_cont);
        $this->assign('fba_id', $fba_id);
        $this->assign('box_id', $box_id);
        return $this->fetch();
    }

    //确认封箱，装箱，增加消息news
    public function confirm_closure() {
        $fba_id = input('request.id', '', 'trim');
        $fbaModel = new Fbadelivery();
        $res = $fbaModel->relation('fbaOrders,fbaCodePick,fbaBoxLabel')->where('id', $fba_id)->find();
        if ($res['plan_status'] >= 3) {
            return json(['code' => 1001, 'msg' => '该计划已经封箱']);
        }
        if ($res['plan_status'] < 2) {
            return json(['code' => 1001, 'msg' => '计划未接收']);
        }
        if (count($res['fbaBoxLabel']) < 1) {
            return json(['code' => 1001, 'msg' => '未添加箱子，不可确认封箱']);
        }

        $is_closure = 1;
        $nums = 0;//判断数据拣货量和总数据量是否一样，
        $pick_nums = 0;
        foreach ($res['fbaOrders'] as $fok => $fov) {
            $nums = bcadd($fov['number'], $nums, 0);
            $pick_nums = bcadd($fov['picking_num'], $pick_nums, 0);
        }
        $FbaNewsModel = new FbaNews();
        if ($nums != $pick_nums) {
            //提前发货，修改发货状态
            $update_res = $fbaModel->where('id', $fba_id)->update(['early_delivery' => 1, 'plan_status' => 3]);
            //return json(['code' => $update_res ? 1000 : 1001, 'msg' => $update_res ? '操作成功' : '操作失败，请刷新重试']);
        } else {
            $update_res = $fbaModel->where('id', $fba_id)->update(['plan_status' => 3]);
        }
        if ($update_res) {
            $news_add = array(
                'fba_id' => $fba_id,
                'agent_id' => $res['agent_id'],
                'plan_status' => 3,
                //'view_user' => session('admin_id'),//查看人id
                'creat_time' => time(),
            );
            $save_news = $FbaNewsModel->save($news_add);
        }

        return json(['code' => $update_res ? 1000 : 1001, 'msg' => $update_res ? '操作成功' : '操作失败，请刷新重试']);
    }

    //获取箱子内容
    public function get_box_contents() {
        $box_id = input('request.box_id', '', 'trim');
        $FbaCodePickingModel = new FbaCodePicking();
        $FbaBoxLableModel = new FbaBoxLabel();
        $case_status = $FbaBoxLableModel->field('case_status')->where('id', $box_id)->find();
        $code_pick = $FbaCodePickingModel->where('fba_box_id', $box_id)->select();
        return json(['data' => $code_pick, 'case_status' => $case_status['case_status']]);
    }

    //拣货
    public function picking_gds() {
        $this->view->engine->layout(false);
        $fba_id = input('request.fba_id', '', 'trim');
        $FbaModel = new Fbadelivery();

        $res = $FbaModel->relation('fbaOrders,fbaBoxLabel,fbaCodePick')->where('id', $fba_id)->find();

        $this->assign('list', $res);
        return $this->fetch();
    }

    //拣货信息,获取拣货唯一值，查询是否有库存，有出库，添加，拣货功能
    public function picking_msg() {
        $sku_id = input('request.unique_id', '', 'trim');
        $fba_id = input('request.fba_id', '', 'trim');
        $picking_nums = input('request.picking_nums', '', 'trim');//要拣货的数量
        $sku_arr = explode('_', $sku_id);
        if (count($sku_arr) > 3) {
            unset($sku_arr[2]);
            $sku_id = implode('_', $sku_arr);
        }

        $fbaOrderModel = new Fbaorder();
        $where = array(
            'sku' => $sku_arr[0],
            'fba_id' => $fba_id
        );
        $res = $fbaOrderModel->where($where)->find();
        if (empty($res)) {
            return json(['code' => 1001, 'msg' => '该订单不存在此产品']);
        }
        $pick = bcsub($res['number'], $res['picking_num'], 0);//剩余需要拣货的数量
        if ($picking_nums > $pick) {
            //大于的话即超过，
            return json(['code' => 1001, 'msg' => '拣货量大于该拣货数量']);
        }
        $picking_num_save = bcadd($res['picking_num'], $picking_nums, 0);
        $epWMDModel = new EpWarehouseMaterialdetail();
        $emwmd_res = $epWMDModel->where('unique_id', $sku_id)->find();
        if (!empty($emwmd_res)) {
            if ($picking_nums >= $emwmd_res['count']) {
                //大于等于则库存直接为0，并做，软删
                $update_epwmd = $epWMDModel->where('id', $emwmd_res['id'])->update(['count' => 0]);
                $our_nums = $emwmd_res['count'];
                $delete_epw = $epWMDModel->destroy($emwmd_res['id']);
            } else {
                //小于的减去拣货的数量，更新
                $update_count = bcsub($emwmd_res['count'], $picking_nums, 0);
                $update_epwmds = $epWMDModel->where('id', $emwmd_res['id'])->update(['count' => $update_count]);
                $our_nums = $picking_nums;
            }
            $out_in = array(
                'whmd_id' => $emwmd_res['id'],
                'wt_name' => $emwmd_res['wt_name'],
                'm_id' => 0,
                'sku' => $emwmd_res['sku'],
                'create_time' => time(),
                'unit' => $emwmd_res['unit'],
                'type' => 1,
                'count' => $our_nums,
                'operator' => session('admin_name'),
                'store_keeper' => '',
                'epimg' => $emwmd_res['ep_img'],
                //'in_type' => '',
            );

            $outInModel = new EpOutInDetails();
            $out_save = $outInModel->save($out_in);
        }
        $is_here = 0;//物品到齐提示0未到齐，1已到齐则提醒
        if ($res['number'] == $picking_num_save) $is_here = 1;

        $order_res = $fbaOrderModel->where('id', $res['id'])->update(['picking_num' => $picking_num_save]);
        $handle = $fbaOrderModel->field('SUM(picking_num),SUM(number)')->where('fba_id', $fba_id)->select();
        if ($handle[0]['SUM(picking_num)'] == $handle[0]['SUM(number)']) {
            $fbaModel = new Fbadelivery();
            $fbaModel->where('id', $fba_id)->update(['is_handle' => 1]);
        }
        return json(['code' => 1000, 'data' => ['num' => $picking_num_save, 'id' => $res['id'], 'is_here' => $is_here], 'msg' => '操作成功']);

    }

    //拣货删除
    public function picking_msg_del() {
        $sku_id = input('request.unique_id', '', 'trim');
        $fba_id = input('request.fba_id', '', 'trim');
        $picking_nums = input('request.picking_nums', '', 'trim');//要拣货的数量
        $sku_arr = explode('_', $sku_id);
        if (count($sku_arr) > 3) {
            unset($sku_arr[2]);
            $sku_id = implode('_', $sku_arr);
        }

        $fbaOrderModel = new Fbaorder();
        $where = array(
            'sku' => $sku_arr[0],
            'fba_id' => $fba_id
        );
        $res = $fbaOrderModel->where($where)->find();
        if (empty($res)) {
            return json(['code' => 1001, 'msg' => '该订单不存在此产品']);
        }
        if ($picking_nums > $res['picking_num']) {
            return json(['code' => 1001, 'msg' => '删除的产品数量大于拣货数量']);
        } else {
            $picking_num_save = bcsub($res['picking_num'], $picking_nums, 0);
        }

        $order_res = $fbaOrderModel->where('id', $res['id'])->update(['picking_num' => $picking_num_save]);
        $handle = $fbaOrderModel->field('SUM(picking_num),SUM(number)')->where('id', $fba_id)->select();
        $sub_p_h = $handle[0]['SUM(picking_num)'] - $picking_num_save;
        if ($handle[0]['SUM(picking_num)'] < $handle[0]['SUM(number)']) {
            $fbaModel = new Fbadelivery();
            $fbaModel->where('id', $fba_id)->update(['is_handle' => 0]);
        }
        return json(['code' => 1000, 'data' => ['num' => $picking_num_save, 'id' => $res['id']], 'msg' => '操作成功']);
    }

    //往箱子添加产品
    public function add_box_contents() {
        $box_id = input('request.box_id', '', 'trim');
        $fba_id = input('request.fba_id', '', 'trim');
        $fnsku = input('request.fnsku', '', 'trim');
        $nums = input('request.nums', '', 'trim');//产品数量
        $FbaOrderModel = new Fbaorder();
        $fbaCodePickModle = new FbaCodePicking();
        $where = array(
            'fba_id' => $fba_id,
            'fnsku' => $fnsku
        );

        $order = $FbaOrderModel->where($where)->find();
        if ($order == null) {
            return json(['code' => 1001, 'msg' => 'fnsku码查询订单数据为空']);
        }
        $where_code = array(
            'fba_id' => $fba_id,
            'fba_order_id' => $order['id']
        );
        $code_res = $fbaCodePickModle->where($where_code)->select();
        $code_pick = 0;
        foreach ($code_res as $code_k => $code_v) {
            $code_pick += $code_v['num'];
        }
        $code_pick = $code_pick + $nums;
        if ($code_pick > $order['picking_num']) {
            return json(['code' => 1004, 'msg' => '产品数量已达订单最大数量']);
        }
        $sub_pick_order = $order['picking_num'] - $code_pick;//拣货数量-已经装进箱子的数量=剩余未装箱数量
        $FbaBoxModel = new FbaBoxLabel();
        $where_box = array(
            'id' => $box_id
        );
        $box_label = $FbaBoxModel->where($where_box)->find();
        if ($box_label['case_status'] == 2) {
            return json(['code' => 1002, 'msg' => '箱子状态已经封箱']);
        }
        $FbaCodePickModel = new FbaCodePicking();
        $where_code = array(
            'fnsku' => $fnsku,
            'fba_box_id' => $box_id,
            'fba_id' => $fba_id,
        );
        $code_pick = $FbaCodePickModel->where($where_code)->find();
        if ($code_pick == null) {
            $save_code = array(
                'fba_id' => $fba_id,
                'fba_box_id' => $box_id,
                'fba_order_id' => $order['id'],
                'img' => $order['img'],
                'sku' => $order['sku'],
                'num' => $nums,
                'fnsku' => $fnsku,
                'size' => $order['size'],
                'case_no' => $box_label['case_no'],
                'create_time' => date('Y-m-d H:i:s')
            );
            $code_res = $FbaCodePickModel->insertGetId($save_code);
            $save_code['id'] = $code_res;
            $save_code['sub_pick_order'] = $sub_pick_order;
            $save_code['order_id'] = $order['id'];
            //$picking = $order['picking_num'] + 1;
            //$box_res = $FbaBoxModel->where('id', $box_id)->update(['case_status' => 1]);
            //$order_res = $FbaOrderModel->where('id', $order['id'])->update(['picking_num' => $picking]);
            return json(['code' => $code_res ? 1000 : 1003, 'msg' => $code_res ? '操作成功' : '操作失败', 'data' => $save_code, 'save' => 'save']);
        } else {
            $code_num = $code_pick['num'] + $nums;
            $code_res = $FbaCodePickModel->where('id', $code_pick['id'])->update(['num' => $code_num]);
            //$picking = $order['picking_num'] + 1;
            //$order_res = $FbaOrderModel->where('id', $order['id'])->update(['picking_num' => $picking]);
            $data = array(
                'id' => $code_pick['id'],
                'num' => $code_num,
                'sub_pick_order' => $sub_pick_order,
                'order_id' => $order['id'],
            );
            return json(['code' => $code_res ? 1000 : 1003, 'msg' => $code_res ? '操作成功' : '操作失败', 'data' => $data, 'save' => 'update']);
        }

    }

    //删除箱子物品
    public function delete_box_contents() {
        $box_id = input('request.box_id', '', 'trim');
        $fba_id = input('request.fba_id', '', 'trim');
        $fnsku = input('request.fnsku', '', 'trim');
        $nums = input('request.nums', '', 'trim');
        $FbaOrderModel = new Fbaorder();
        $where = array(
            'fba_id' => $fba_id,
            'fnsku' => $fnsku
        );
        $order = $FbaOrderModel->where($where)->find();
        if ($order['picking_num'] <= 0) {
            return json(['code' => 1001, 'msg' => '该产品未开始拣货']);
        }

        $FbaCodePickModel = new FbaCodePicking();
        $where_code = array(
            'fnsku' => $fnsku,
            'fba_box_id' => $box_id,
            'fba_id' => $fba_id,
        );
        $code_pick = $FbaCodePickModel->where($where_code)->find();

        if ($code_pick == null) {
            return json(['code' => 1001, 'msg' => '该产品未开始拣货']);
        }
        if ($code_pick['num'] < $nums) {
            return json(['code' => 1001, 'msg' => '移除的产品数量超过该箱子拥有的数量']);
        }
        $pick_num = $code_pick['num'] - $nums;
        $order_tr = array(
            'order_id' => $order['id'],
            'id' => $order['id'],
            'img' => $order['img'],
            'sku' => $order['sku'],
            'picking_num' => $order['picking_num'],
            'size' => $order['size'],
            'fnsku' => $order['fnsku'],
            'code_id' => $code_pick['id'],
        );
        if ($pick_num <= 0) {

            //$order_res = $FbaOrderModel->where('id', $order['id'])->update(['picking_num' => $order_num]);
            $code_res = $FbaCodePickModel->destroy($code_pick['id'], true);
            return json(['id' => $order_tr, 'code' => $code_res ? 1000 : 1001, 'msg' => $code_res ? '操作成功' : '操作失败', 'data' => $pick_num, 'op' => 'del']);
        } else {

            //$order_res = $FbaOrderModel->where('id', $order['id'])->update(['picking_num' => $order_num]);
            $code_res = $FbaCodePickModel->where('id', $code_pick['id'])->update(['num' => $pick_num]);
            return json(['id' => $order_tr, 'code' => $code_res ? 1000 : 1001, 'msg' => $code_res ? '操作成功' : '操作失败', 'data' => $pick_num, 'op' => 'sub']);
        }
    }

    //箱子状态
    public function edit_case_status() {
        $op = input('request.op');
        $box_id = input('request.box_id');
        $fbaBoxModel = new FbaBoxLabel();
        if ($op == 'seal') {
            $box_res = $fbaBoxModel->where('id', $box_id)->update(['case_status' => 2]);
        } else if ($op == 'open') {
            $box_res = $fbaBoxModel->where('id', $box_id)->update(['case_status' => 1]);
        }
        return json(['code' => $box_res ? 1000 : 1001, 'msg' => $box_res ? '操作成功' : '操作失败']);
    }

    //置顶
    public function set_topping() {
        $topping = input('request.topping');
        $id = input('request.id');
        $fbaModel = new Fbadelivery();

        $res = $fbaModel->where('id', $id)->update(['topping' => $topping]);

        return json(['code' => $res ? 1000 : 1001, 'msg' => $res ? '操作成功' : '操作失败']);
    }

    //二维码
    public function get_qrcode($text, $id) {
        fnskuQrCode($text, $id, 4, 1);
    }

    //打印标签
    public function print_tap() {
        $this->view->engine->layout(false);
        $id = input('request.id', '', 'trim');
        $number = input('request.number', '', 'trim');
        /*$size = input('request.size', '', 'trim');
        $name = input('request.name', '', 'trim');
        $num = input('request.num', '', 'trim');
        $sku = input('request.sku', '', 'trim');
        $title = input('request.title', '', 'trim');
        $condition = input('request.condition', '', 'trim');*/
        $list = array(
            'id' => $id,
            'number' => $number,
        );
        $this->assign('list', $list);
        return $this->fetch();
    }

    //打印面单
    public function print_list() {
        $id = input('request.fba_id');
        //new mPDF($mode='',$format='A4',$default_font_size=0,$default_font='',$mgl=15,$mgr=15,$mgt=16,$mgb=16,$mgh=9,$mgf=9, $orientation='P');
        $FbaModel = new Fbadelivery();
        $fba = $FbaModel->relation('fbaOrders,fbaBoxLabel,fbaCodePick')->where('id', $id)->find();
        $total_gds = 0;
        $picking_num = 0;
        foreach ($fba['fbaOrders'] as $ov) {
            $total_gds = bcadd($total_gds, $ov['number']);
            $picking_num = bcadd($picking_num, $ov['picking_num']);
        }
        $mpdf = new mPDF(['utf-8', 'A4', 16, '', 10, 10, 15, 1]);
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont = true;
//$stylesheet = file_get_contents('mpdfstyletables.css');
//$mpdf->WriteHTML($stylesheet,1);    // The parameter 1 tells that this is css/style only and no body/html/text
        $style = '  .fbali{font-size:11px;}
                    .tdsolid{border-width: 1px;border-style: solid;border-color: #e6e6e6;}
                    .img{width: 80px;height: 80px}
                    .center{text-align: center;}
                    .fbali td{}
                    .fba_nums{width:100px;}
                    .contact{width:100px;}
                    .time{width:230px;}
                    .waybill{width:120px;}
                    .express_way{width:130px;}
                    .xuhao{width:20px;}
                    .dindan{width:120px;}
                    .sku{width:120px;}
                    .fnsku{width:110px;}
                    .shuliang{width:40px;}
                    .guige{width:90px;}
                    .tupian{width:90px;}
                    .xianghao{width:80px;}
                    .lab_box_size{font-size:16px;}';//样式
        $mpdf->WriteHTML($style,1);//写入样式

        $html = '<table class="fbali">
            <tr style="">
                <td class="fba_nums center tdsolid" >
                <img class="img" src="' . ROOT_QRCODE_FNSKU . $fba['fba_nums'] . '_' . $fba['id'] . 'fbanums.png' .'" /><br/><span class="fbanums">' . $fba['fba_nums'] . '</span></td>
                <td class="contact center tdsolid"><span class="lab_box_size">箱标:B' . $fba['id'] . '</span><br/>联系人:' . $fba['contact'] . '<br/>店名:' . $fba['shop_name'] . '</td>
                <td class="time center tdsolid">下单时间：' . date('Y-m-d', $fba['create_time']) . '<br/>期望发货时间：' . substr($fba['expect_delivery_time'], 0, 10) . '</td>
                <td class="waybill center tdsolid">运单号：' . $fba['waybill_no'] . '<br/>拣货数：' . $picking_num .'<br/>总件数：' . $total_gds .'</td>
                <td class="express_way center tdsolid" colspan="4">物流：' . $fba['express_way'] . '<br/>快递方式：' . $fba['type_of_shipping'] . '</td>
            </tr>
        </table>';

        $order_top = '<table class="fbali fba_order">
            <thead>
            <tr>
                <th class="center tdsolid xuhao">序号</th>
                <th class="center tdsolid dindan">系统订单号</th>
                <th class="center tdsolid sku">产品SKU</th>
                <th class="center tdsolid fnsku">FNSKU二维码</th>
                <th class="center tdsolid shuliang">数量</th>
                <th class="center tdsolid guige">规格</th>
                <th class="center tdsolid tupian">图片</th>
                <th class="center tdsolid xianghao">箱号</th>
            </tr>
            </thead>
            <tbody>';
        $order_body = '';
        foreach ($fba['fbaOrders'] as $k => $value) {
            $add_k = $k + 1;
            $order_body = $order_body . '<tr class="">
                <td class="center tdsolid" >'. $add_k . '</td>
                <td class="center tdsolid"> '. $value['order_no'] . '</td>
                <td class="center tdsolid">' . $value['sku'] . '</td>
                <td class="center tdsolid"><img class="img" src="' . ROOT_QRCODE_FNSKU . $value['fnsku'] . '_' . $value['id'] . '.png' .'" /><br/>' . $value['fnsku'] . '</td>
                <td class="center tdsolid">' . $value['number'] . '</td>
                <td class="center tdsolid">' . $value['name'] . '</br>(' . $value['size'] . ')</td>
                <td class="center tdsolid"><img class="img" src="http://kjds-img.img-cn-shanghai.aliyuncs.com/' . explode(',',$value['img'])[0] . '@0e_0o_1l_500h_500w.src" /></td>
                <td class="center tdsolid" >
                </td></tr>';
        }
        //$order_body = $order_body . $order_body . $order_body . $order_body . $order_body . $order_body . $order_body . $order_body . $order_body . $order_body . $order_body . $order_body;
        $mpdf->WriteHTML($html . $order_top . $order_body . '</tbody></table>', 2);
        //$mpdf->Image($file, 140, 200); //追加盖章图片，貌似可以获取远程的，非必要请使用服务器本地的，以前tcpdf就是无法获取远程的，mpdf未测试远程图片
        $mpdf->Output($fba['fba_nums'] . '_面单.pdf', 'D'); //D是下载
    }

    //图片转pdf
    public function png2pdf() {

        $id = input('request.id', '', 'trim');
        $num = input('request.print_num', '', 'trim');
        $fbaOrderModel = new Fbaorder();
        $order = $fbaOrderModel->where('id', $id)->find();
        //$this->get_barcode($id);
        $mpdf = new mPDF(['mode' => '',
            'format' => array(70, 33),
            'default_font_size' => 9,
            'default_font' => 'utf-8',
            'margin_left' => 2,
            'margin_right' => 2,
            'margin_top' => 1,
            'margin_bottom' => 1,
            'margin_header' => 1,
            'margin_footer' => 1,
            'orientation' => 'P',]);
        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont = true;
        $style = '.center{text-align: center;}
                  .condition{font-weight:bold;}';
        $mpdf->WriteHTML($style,1);//写入样式
        //$pdf = new fpdf('L', 'mm', 'Letter'); //创建新的FPDF 对象，竖向放纸，单位为毫米，纸张大小A4
        //$mpdf->SetFont('Courier','I',20); //设置字体样式
        $str_cut = 25;
        $title_1 = substr($order['title'], 0, $str_cut);
        $title_2 = substr($order['title'], -$str_cut);
        $title_all = $title_1 . '...' . $title_2;//标题
        $title_len = imagettfbbox(9, 0, EXTEND_PATH . 'file/ht.ttf', $title_all);
        $title_width = ($title_len[2] - $title_len[0]);//字体宽度（px）
        while ($title_width > 260) {
            $str_cut = $str_cut - 1;
            $title_1 = substr($order['title'], 0, $str_cut);
            $title_2 = substr($order['title'], -$str_cut);
            $title_all = $title_1 . '...' . $title_2;//标题
            $title_len = imagettfbbox(9, 0, EXTEND_PATH . 'file/ht.ttf', $title_all);//
            $title_width = ($title_len[2] - $title_len[0]);
        }


        $condition_sku_size = $order['sku'] . '(' . $order['size'] . ')';
        $css_str_len = imagettfbbox(9, 0, EXTEND_PATH . 'file/ht.ttf', $condition_sku_size);//
        $name_width = ($css_str_len[2] - $css_str_len[0]);//字体宽度（px）
        while ($name_width > 185) {
            $condition_sku_size = substr($condition_sku_size, 0, strlen($condition_sku_size) - 1);
            $css_str_len = imagettfbbox(9, 0, EXTEND_PATH . 'file/ht.ttf', $condition_sku_size);//
            $name_width = ($css_str_len[2] - $css_str_len[0]);
        }
        for ($i = 0; $i < $num; $i++) {
            $mpdf->AddPage(); //增加一页
            $this->get_barcode($id, $order['fnsku']);
            if (!file_exists(ROOT_FBAORDERLABEL_IMG . $id .'_' . $order['fnsku'] . '_label.png')) {
                $this->get_barcode($id, $order['fnsku']);
            }
            $html = '<div class="center">
            <img class="img" src="' . ROOT_FBAORDERLABEL_IMG . $id .'_' . $order['fnsku'] . '_label.png' . '" /><br/>
            <span class="fnsku">' . $order['fnsku'] . '</span></div>
            <span class="title">' . $title_all . '</span><br/>
            <span class="condition">' . $order['condition'] . '</span>&nbsp;&nbsp;<span class="sku">' . $condition_sku_size . '</span><br/>
            <span class="mdicn">MADE IN CHINA</span>
            ';
            $mpdf->WriteHTML($html, 2);
            //$mpdf->Image(ROOT_FBAORDERLABEL_IMG . $id . '_' . $order['fnsku'] . '_label.png',3,3);
        }

        $mpdf->Output('label_' . $id . '.pdf','D');
    }
    //批量图片转pdf
    public function png2pdf_all() {
        $fba_id = input('request.id', '', 'trim');

        $fbaOrderModel = new Fbaorder();
        $fbaModel = new Fbadelivery();
        $fba = $fbaModel->where('id', $fba_id)->find();
        $order = $fbaOrderModel->where('fba_id', $fba_id)->select();
        $mpdf = new mPDF(['mode' => '',
            'format' => array(70, 33),
            'default_font_size' => 9,
            'default_font' => 'utf-8',
            'margin_left' => 2,
            'margin_right' => 2,
            'margin_top' => 1,
            'margin_bottom' => 1,
            'margin_header' => 2,
            'margin_footer' => 2,
            'orientation' => 'P',]);
        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont = true;
        $style = '.center{text-align: center;}
                  .condition{font-weight:bold;}';
        $mpdf->WriteHTML($style,1);//写入样式
        foreach ($order as $k => $v) {
            //$this->get_barcode($id);
            //$pdf = new fpdf('L', 'mm', 'Letter'); //创建新的FPDF 对象，竖向放纸，单位为毫米，纸张大小A4
            //$mpdf->SetFont('Courier', 'I', 20); //设置字体样式
            $str_cut = 25;
            $title_1 = substr($v['title'], 0, $str_cut);
            $title_2 = substr($v['title'], -$str_cut);
            $title_all = $title_1 . '...' . $title_2;
            $title_len = imagettfbbox(9, 0, EXTEND_PATH . 'file/ht.ttf', $title_all);
            $title_width = ($title_len[2] - $title_len[0]);//字体宽度（px）
            while ($title_width > 260) {
                $str_cut = $str_cut - 1;
                $title_1 = substr($order['title'], 0, $str_cut);
                $title_2 = substr($order['title'], -$str_cut);
                $title_all = $title_1 . '...' . $title_2;//标题
                $title_len = imagettfbbox(9, 0, EXTEND_PATH . 'file/ht.ttf', $title_all);//
                $title_width = ($title_len[2] - $title_len[0]);
            }

            $condition_sku_size = $order['sku'] . '(' . $order['size'] . ')';
            $css_str_len = imagettfbbox(9, 0, EXTEND_PATH . 'file/ht.ttf', $condition_sku_size);//
            $name_width = ($css_str_len[2] - $css_str_len[0]);//字体宽度（px）
            while ($name_width > 185) {
                $condition_sku_size = substr($condition_sku_size, 0, strlen($condition_sku_size) - 1);
                $css_str_len = imagettfbbox(9, 0, EXTEND_PATH . 'file/ht.ttf', $condition_sku_size);//
                $name_width = ($css_str_len[2] - $css_str_len[0]);
            }
            for ($i = 0; $i < $v['number']; $i++) {
                $mpdf->AddPage(); //增加一页
                $this->get_barcode($v['id'], $v['fnsku']);
                if (!file_exists(ROOT_FBAORDERLABEL_IMG . $v['id'] .'_' . $v['fnsku'] .  '_label.png')) {
                    $this->get_barcode($v['id'], $v['fnsku']);
                }
                $html = '<div class="center">
                        <img class="img" src="' . ROOT_FBAORDERLABEL_IMG . $v['id'] .'_' . $v['fnsku'] . '_label.png' . '" /><br/>
                        <span class="fnsku">' . $v['fnsku'] . '</span></div>
                        <span class="title">' . $title_all . '</span><br/>
                        <span class="condition">' . $v['condition'] . '</span>&nbsp;&nbsp;<span class="sku">' . $condition_sku_size . '</span><br/>
                        <span class="mdicn">MADE IN CHINA</span>
                        ';
                $mpdf->WriteHTML($html, 2);
                //$mpdf->Image(ROOT_FBAORDERLABEL_IMG . $v['id'] .'_' . $v['fnsku'] .  '_label.png', 3, 3, 156, 45);
            }
            unset($title_1, $title_2, $title_all);
        }
        $mpdf->Output($fba['fba_nums'] . '_标签.pdf','D');
    }

    //条形码
    public function get_barcode($id, $fnsku) {
        //$fbaOrderModel = new Fbaorder();
        //$order = $fbaOrderModel->where('id', $id)->find();
        //barcode_fba($id, 'BCGcode128', $order['fnsku'], $order['title'], $order['condition'], $order['sku'] . '(' . $order['size'] . ')', 60, 3);
        barcode_fba($id, 'BCGcode128', $fnsku, 55, 2);
    }

    //打印面单
    public function plan_list() {
        $this->view->engine->layout(false);
        $fba_id = input('request.fba_id', '', 'trim');
        $fbaModel = new Fbadelivery();
        $res = $fbaModel->relation('fbaOrders,fbaBoxLabel')->where('id', $fba_id)->find();
        $total_gds = 0;
        $picking_num = 0;
        foreach ($res['fbaOrders'] as $ov) {
            $total_gds = bcadd($total_gds, $ov['number']);
            $picking_num = bcadd($picking_num, $ov['picking_num']);
        }
        $this->assign('list', $res);
        $this->assign('total_gds', $total_gds);
        $this->assign('picking_num', $picking_num);
        return $this->fetch();
    }

    //打印配货单
    public function print_ckbox() {
        $fba_id = input('request.fba_id', '', 'trim');
        $fbaModel = new Fbadelivery();
        $fba = $fbaModel->where('id', $fba_id)->find();
        $FbaOrderModel = new Fbaorder();
        $epmeatilModel = new EpWarehouseMaterialdetail();
        $res = $FbaOrderModel->where('fba_id', $fba_id)->select();
        $list = array();
        foreach ($res as $k => $v) {
            $where = array(
                'sku' => $v['sku'],
                //'spec' => $v['size']
            );
            $list[$k]['sku'] = $v['sku'];
            $list[$k]['size'] = $v['size'];
            $list[$k]['img'] = $v['img'];
            $list[$k]['pick'] = bcsub($v['number'], $v['picking_num'], 0);
            $meatil = $epmeatilModel->where($where)->select();

            if (count($meatil) > 0) {
                $list[$k]['meatil'] = $meatil;
            } else {
                $list[$k]['meatil'] = array();
            }
            unset($meatil);
        }

        $mpdf = new Mpdf(['utf-8', 'A4', 16, '', 10, 10, 15, 1]);
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont = true;
        $style = '  .fbali{font-size:11px;}
                    .tdsolid{border-width: 1px;border-style: solid;border-color: #e6e6e6;}
                    .img{width: 80px;height: 80px}
                    .center{text-align: center;}
                    .tupian{width:100px;}
                    .sku{width:200px;}
                    .cangku{width:200px;}
                    .pick{width:100px;}';//样式
        $mpdf->WriteHTML($style,1);//写入样式
        $html_top = '<table class="fbali" >
           <thead>
            <tr>
                <th class="center tdsolid tupian">图片</th>
                <th class="center tdsolid sku">SKU</th>
                <th class="center tdsolid cangku">仓库</th>
                <th class="center tdsolid pick">需拣货量</th>
            </tr>
            </thead>
            <tbody>';
        $html_body = '';
        foreach ($list as $item => $value) {
            $html_meatil = '';
            foreach ($value['meatil'] as $ime => $vme) {
                $html_meatil = $html_meatil . $vme['wt_name'] . '(' . $vme['count'] . '件)<br/>';
            }
            $html_body = $html_body . '<tr class="">
                <td class="center tdsolid"><img class="img" src="http://kjds-img.img-cn-shanghai.aliyuncs.com/' . explode(',',$value['img'])[0] . '@0e_0o_1l_500h_500w.src" /></td>
                <td class="center tdsolid">' . $value['sku'] . '<br>' . $value['size'] . '</td>
                <td class="center tdsolid"> ' .
                    $html_meatil . '
                </td>
                <td class="center tdsolid">' . $value['pick'] . '</td>

            </tr>';
        }
        $mpdf->WriteHTML($html_top . $html_body . '</tbody></table>', 2);
        $mpdf->Output($fba['fba_nums'] . '_配货单.pdf','D');
    }

    //配货单
    public function distribution_list() {
        $this->view->engine->layout(false);
        $fba_id = input('request.fba_id', '', 'trim');
        $FbaOrderModel = new Fbaorder();
        $epmeatilModel = new EpWarehouseMaterialdetail();
        $res = $FbaOrderModel->where('fba_id', $fba_id)->select();
        $list = array();
        foreach ($res as $k => $v) {
            $where = array(
                'sku' => $v['sku'],
                //'spec' => $v['size']
            );
            $list[$k]['sku'] = $v['sku'];
            $list[$k]['size'] = $v['size'];
            $list[$k]['img'] = $v['img'];
            $list[$k]['pick'] = bcsub($v['number'], $v['picking_num'], 0);
            $meatil = $epmeatilModel->where($where)->select();

            if (count($meatil) > 0) {
                $list[$k]['meatil'] = $meatil;
            } else {
                $list[$k]['meatil'] = array();
            }
            unset($meatil);
        }

        $this->assign('list',$list);
        $this->assign('fba_id',$fba_id);
        return $this->fetch();
    }

    //查看详情
    public function fba_details() {
        $id = input('request.id');
        $FbaModel = new Fbadelivery();
        //判断是否有上传箱标，没有上传不能更改运单信息
        $res = $FbaModel->relation('fbaOrders,fbaBoxLabel,fbaCodePick')->where('id', $id)->find()->toArray();
        if (!file_exists(ROOT_QRCODE_FNSKU . $res['fba_nums'] . '_' . $res['id'] . "fbanums.png")) {
            fnskuQrCode($res['fba_nums'], $res['id'], 6, 2, 1);
        }
        $epmeatilModel = new EpWarehouseMaterialdetail();
        foreach ($res['fbaOrders'] as $fok => $fov) {
            $where = array(
                'sku' => $fov['sku'],//sku查询即可
                //'spec' => $fov['size']
            );
            $epwm = $epmeatilModel->field('SUM(count)')->where($where)->select();
            if ($epwm[0]['SUM(count)'] != null) {
                $res['fbaOrders'][$fok]['stock_count'] = $epwm[0]['SUM(count)'];
            } else {
                $res['fbaOrders'][$fok]['stock_count'] = 0;
            }
        }
        $total_gds = 0;
        $picking_num = 0;
        foreach ($res['fbaOrders'] as $ov) {
            $total_gds = bcadd($total_gds, $ov['number'], 0);
            $picking_num = bcadd($picking_num, $ov['picking_num'], 0);
            if (!file_exists(ROOT_QRCODE_FNSKU . $ov['fnsku'] . '_' . $ov['id'] . ".png")) {
                fnskuQrCode($ov['fnsku'], $ov['id']);
            }
        }
        $file_res = file_exists(ROOT_SAVE_FBA_BOX_LABEL . $id . '.txt');//读,返回字符数，失败false
        if ($file_res == true) {
            $shipment = 1;
        } else {
            $shipment = 0;
        }

        $fCP = 0;//箱子里的总数量
        foreach ($res['fbaCodePick'] as $fcpk => $fcpv) {
            $fCP += $fcpv['num'];
        }
        $this->assign('list', $res);
        $this->assign('fCP', $fCP);
        $this->assign('shipment', $shipment);
        $this->assign('total_gds', $total_gds);
        $this->assign('picking_num', $picking_num);
        $this->assign('plan_status_name', $this->plan_status_name);
        $this->assign('plan_status_color', $this->plan_status_color);
        $this->assign('eventJS', 'fba');
        return $this->fetch();
    }

    //查看库存
    public function check_stock() {
        $this->view->engine->layout(false);
        $sku = input('post.sku');
        $size = input('post.size');
        $img = input('post.img');
        $where = array(
            'sku' => $sku,
            //'spec' => $size
        );

        $epmeatilModel = new EpWarehouseMaterialdetail();
        $res = $epmeatilModel->where($where)->select();

        $check = array(
            'sku' => $sku,
            'size' => $size,
            'img' => $img
        );
        $this->assign('check', $check);
        $this->assign('list',$res);

        return $this->fetch();
    }

    //获取fba仓库
    public function get_fba_warehouse() {
        $this->view->engine->layout(false);
        $where = array(
            'factory_pid' => 3,
            'status' => 1
        );
        $wareModel = new EpWarehouse();
        $warehouseTable = new EpWarehouseTable();

        $res = $wareModel->where($where)->relation('wTables')->select();
        $this->assign('list',$res);

        return $this->fetch();
    }

    //修改运单号，增加消息news
    public function saveWno(){
        $id = input('request.fba_id');
        $wno = input('request.wno');
        $file_exi = file_exists(ROOT_SAVE_FBA_BOX_LABEL . $id . '.txt');//判断箱标文件是否存在，
        if (!$file_exi) {
            return json(['code' => 1001, 'msg' => '箱标文件未上传']);
        }
        $FbaModel = new Fbadelivery();
        $fba = $FbaModel->where('id', $id)->find();
        if ($fba['plan_status'] >= 3 && $fba['plan_status'] < 5) {
            $res = $FbaModel->where('id', $id)->update(['waybill_no' => $wno, 'plan_status' => 4, 'delivery_time' => date('Y-m-d H:i:s')]);
        } else {
            return json(['code' => 1001, 'msg' => '此状态不可修改运单号']);
        }
        $FbaNewsModel = new FbaNews();
        if ($res) {
            $news_add = array(
                'fba_id' => $id,
                'agent_id' => $fba['agent_id'],
                'plan_status' => 4,
                //'view_user' => session('admin_id'),//查看人id
                'creat_time' => time(),
            );
            $save_news = $FbaNewsModel->save($news_add);
        }
        return json(['code' => $res ? 1000 : 1001, 'msg' => $res ? '保存成功' : '保存失败']);
    }
    //保存信息
    public function saveMsg(){
        $id = input('request.fba_id');
        $wmsg = input('request.wmsg');

        $FbaModel = new Fbadelivery();
        $old_note = $FbaModel->where('id', $id)->field('note')->find();
        $msg = $old_note['note'] . '<br>工厂：' . $wmsg;
        $res = $FbaModel->where('id', $id)->update(['note' => $msg]);
        return json(['code' => $res ? 1000 : 1001, 'msg' => $res ? '保存成功' : '保存失败', 'data' => $msg]);
    }
    //增加箱子
    public function saveBox(){
        $fba_id = input('request.fba_id');
        $case_no = input('request.case_no');
        $case_length = input('request.case_length');
        $case_width = input('request.case_width');
        $case_height = input('request.case_height');
        $case_weight = input('request.case_weight');
        $price = input('request.price');
        $case_location = input('request.case_location');
        $case_location_id = input('request.case_location_id');

        $data = array(
            'fba_id' => $fba_id,
            'case_no' => $case_no,
            'case_length' => $case_length,
            'case_width' => $case_width,
            'case_height' => $case_height,
            'case_weight' => $case_weight,
            'price' => $price,
            'case_location' => $case_location,
            'wt_id' => $case_location_id,
        );
        $FbaBoxModel = new FbaBoxLabel();

        $res = $FbaBoxModel->insertGetId($data);
        return json(['code' => $res ? 1000 : 1001, 'msg' => $res ? '保存成功' : '保存失败', 'id' => $res]);
    }

    //修改状态，开始任务
    public function startPlan() {
        $id = input('request.id');
        $status = input('request.status');
        $FbaModel = new Fbadelivery();

        $res = $FbaModel->where('id', $id)->update(['plan_status' => $status, 'remind_status' => 2]);//remind_status开始计划时将提醒状态修改为开始
        return json(['code' => $res ? 1000 : 1001, 'msg' => $res ? '操作成功' : '操作失败']);
    }

    //修改状态，返回已封箱，待装箱状态
    public function backStatus() {
        $id = input('request.id');
        $agent_id = input('request.agent_id');
        $update_status = input('request.update_status');
        $old_status = input('request.old_status');
        $FbaModel = new Fbadelivery();
        //$fba_data = $FbaModel->where('id', $id)->find();
        $FbaNewsModel = new FbaNews();
        $news_add = array(
            'fba_id' => $id,
            'agent_id' => $agent_id,
            'plan_status' => $old_status,
        );
        $save_news = $FbaNewsModel->where($news_add)->update(['views' => 1]);
        if ($old_status == 3) {
            $res = $FbaModel->where('id', $id)->update(['plan_status' => $update_status, 'early_delivery' => 0]);
        } else {
            $res = $FbaModel->where('id', $id)->update(['plan_status' => $update_status]);
        }
        return json(['code' => $res ? 1000 : 1001, 'msg' => $res ? '操作成功' : '操作失败']);
    }

    //修改状态已完成
    public function successPlan() {
        $id = input('request.id');
        $FbaModel = new Fbadelivery();

        $fba = $FbaModel->where('id', $id)->find();
        $box_label = file_exists(ROOT_SAVE_FBA_BOX_LABEL . $id . '.txt');//判断箱标文件是否上传
        if (!$box_label) {
            return json(['code' => 1001, 'msg' => '箱标文件未上传']);
        }
        if ($fba['waybill_no'] == '') {
            return json(['code' => 1001, 'msg' => '未填写运单号']);
        }
        if ($fba['plan_status'] == 4) {
            $res = $FbaModel->where('id', $id)->update(['plan_status' => 5, 'success_time' => date('Y-m-d H:i:s')]);
        } else {
            return json(['code' => 1001, 'msg' => '操作失败']);
        }
        return json(['code' => $res ? 1000 : 1001, 'msg' => $res ? '操作成功' : '操作失败']);
    }

    //删除箱子
    public function delBox() {
        $id = input('request.id');
        $FbaBoxModel = new FbaBoxLabel();
        $FbaCodePickModel = new FbaCodePicking();
        $code_res = $FbaCodePickModel->where('fba_box_id', $id)->select();
        if (count($code_res) > 0) {
            return json(['code' => 1001, 'msg' => '删除失败,箱子有入库商品']);
        }

        $res = $FbaBoxModel->destroy($id, true);
        return json(['code' => $res ? 1000 : 1001, 'msg' => $res ? '删除成功' : '删除失败']);
    }

    //更改箱子
    public function editBox() {
        $id = input('request.id');
        $length = input('request.length');
        $width = input('request.width');
        $height = input('request.height');
        $weight = input('request.weight');
        $price = input('request.price');

        $FbaBoxModel = new FbaBoxLabel();
        $FbaBoxModel->where('id', $id)->update(['case_length' => $length, 'case_width' => $width, 'case_height' => $height,'case_weight' => $weight,'price' => $price,]);
        return json(['code' => 1000, 'msg' => '编辑成功']);
    }

    //下载箱标
    public function download_label() {
        /*if (!$this->request->isPost()) {
            return json(['code' => 1002, 'msg' => '此为post接口']);
        }*/
        $fba_id = input("get.fba_id", "", "trim");
        if (empty($fba_id)) {
            return json(['code' => 1001, 'msg' => 'id不可为空']);
        }
        $fbaModel = new Fbadelivery();
        $fba_data = $fbaModel->where('id', $fba_id)->find();


        $file_res = file_get_contents(ROOT_SAVE_FBA_BOX_LABEL . $fba_id . '.txt');//读,返回字符数，失败false
        $data = base64_decode($file_res);//转换
        file_put_contents(ROOT_SAVE_FBA_BOX_LABEL . 'download_' . $fba_id . '.' . $fba_data['file_format'], $data);
        downloadzipFile(ROOT_SAVE_FBA_BOX_LABEL . 'download_' . $fba_id . '.' . $fba_data['file_format'], $fba_data['fba_nums'] . '_货件标签' . '.' . $fba_data['file_format']);
        unlink(ROOT_SAVE_FBA_BOX_LABEL . 'download_' . $fba_id . '.' . $fba_data['file_format']);
        exit;
        //return json(['code' => $file_res !== false ? 1000 : 1003, 'msg' => $file_res !== false ? '文件获取成功' : '文件获取失败', 'base64_code' => $file_res, 'file_format' => $fba_data['file_format']]);
    }

    //订单详情
    public function intro($id){
        $this->view->engine->layout(false);
        $orderModel = new OrderModel();
        $data = $orderModel->where('id', $id)->relation('orderfactroy,orderproduct')->find();
        $facModel = new Orderfactory();
        $fac = $facModel->where('order_id', $id)->relation('userinfo')->select();

        $this->assign('data',$data);
        $this->assign('fac',$fac);
        return $this->fetch();
    }

    //导出excel
    public function exportsFBA() {
        parent::excelStart();
        $FbaModel = new Fbadelivery();
        $id = input('get.id');

        //$capacity_type = input('get.capacity_type');

        $data = $FbaModel->relation('fbaOrders,fbaBoxLabel,fbaCodePick')->where('id', $id)->find();

        $PHPExcel = new \PHPExcel();
        $PHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A1", "货件号")
            ->setCellValue("A2", "发货地址信息")
            ->setCellValue("A3", "快递方式")
            ->setCellValue("A4", "选择的物流")
            ->setCellValue("A5", "发货时间")
            ->setCellValue("A6", "箱子数量")
            ->setCellValue("A7", "库位")
            ->setCellValue("B7", "箱号")
            ->setCellValue("C7", "规格")
            ->setCellValue("D7", "重量");
        /*$PHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
        $PHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(23);
        $PHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
        $PHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(8);
        $PHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(14);
        $PHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(50);
        $PHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(18);*/
        $box_count = count($data['fbaBoxLabel']);
        $PHPExcel->setActiveSheetIndex(0)
            ->setCellValue("B1", $data['fba_nums'])
            ->setCellValue("B2", $data['addr'])
            ->setCellValue("B3", $data['type_of_shipping'])
            ->setCellValue("B4", $data['express_way'])
            ->setCellValue("B5", $data['success_time'])
            ->setCellValue("B6", $box_count);
        foreach ($data['fbaBoxLabel'] as $k => $v) {
            $ks = $k + 8;
            $PHPExcel->setActiveSheetIndex(0)
                ->setCellValue("A" . $ks, $v['case_location'])
                ->setCellValue("B" . $ks, $v['case_no'])//."\t"
                ->setCellValue("C" . $ks, $v['case_length'] . '*' . $v['case_width'] . '*' . $v['case_height'])
                ->setCellValue("D" . $ks, $v['case_weight']);
        }

        $PHPExcel->setActiveSheetIndex(0);                   //设置sheet的起始位置
        //$objWriter = \PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel5');   //Excel2003通过PHPExcel_IOFactory的写函数将上面数据写出来
        $PHPWriter = \PHPExcel_IOFactory::createWriter($PHPExcel,"Excel2007"); //Excel2007
        //header('Content-Disposition: attachment;filename="用户信息.xlsx"');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition:inline;filename=" . $data['fba_nums'] . "_物流信息.xlsx");
        //$savefiles = ROOT_SAVE_FILES . '运单.xlsx';
        $PHPWriter->save('php://output'); //表示在$path路径下面生成demo.xlsx文件
        exit;
    }

}
