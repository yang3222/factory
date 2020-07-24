<?php
namespace app\admin\controller;

use \app\admin\controller;
use app\admin\model\Order;
use \think\Db;//ink_product_materialforecast
//use app\admin\model\Productmaterialforecast;
//use app\admin\model\Materialforecasttime;
use app\admin\model\Product;

class Materialforecast extends Excel {
    protected $pageTotalItem=40;
    public function __construct() {
        parent::__construct();
        $this->assign('currentMenu',array('menu'=>'menu10','nav'=>'nav0'));
    }

    public function index() {
        $search=input('request.search','','trim');
        $productmodel = new Product();
        $modeldata = $productmodel->order('id', 'desc');
        $yestady = date('Y-m-d', strtotime('-1 days', time()));
        $three_days_ago = date('Y-m-d', strtotime('-30 days', time()));

        if(!empty($search)){
            $where=array(
                'name' => ['like',"%{$search}%"],
                'product_id'=>"{$search}",
            );
            $modeldata->whereor($where);
        }
        $data = $modeldata->relation('productsize,productfactroyHas')->paginate($this->pageTotalItem,false,['query' =>request()->param()])->each(
            function($items, $key){
                $orderModel = new Order();
                $order_pro = '';
                $product_id = $items['product_id'];
                $yestady = date('Y-m-d', strtotime('-1 days', time()));
                $three_days_ago = date('Y-m-d', strtotime('-30 days', time()));
                $interval_days = getDatesBetweenTwoDays($three_days_ago, $yestady);
                $sess_key = 'admin_predict_' . $product_id . '_' . strtotime($yestady) . '_' . strtotime($three_days_ago);

                $ca_data = cache($sess_key);
                if ($ca_data == false) {
                    $where = array(
                        'product_id' => $product_id,
                        'GetTimer' => ['between', [$three_days_ago . ' 00:00:00', $yestady . ' 23:59:59']],
                        'status' => ['in', [0, 1, 2, 3, 4, 5]]
                    );
                    $order_pro = $orderModel->where($where)->select();
                    if (count($order_pro) > 0) {
                        $date_order = array();//循环以日期为键，数量为值
                        foreach ($order_pro as $k => $v) {
                            $time = date('Y-m-d', strtotime($v['GetTimer']));
                            if (isset($date_order[$time])) {
                                $date_order[$time] += $v['GdsNum'];
                            } else {
                                $date_order[$time] = $v['GdsNum'];
                            }
                        }
                        $res = array();
                        foreach ($interval_days as $dk => $dv) {
                            if (isset($date_order[$dv])) {
                                $res[$dv] = $date_order[$dv];
                            } else {
                                $res[$dv] = 0;
                            }
                        }
                        $threeday = 0;
                        $sevenday = 0;
                        $fourteenday = 0;
                        $thirtyday = 0;
                        $index = 0;
                        $num = 0;
                        foreach ($res as $rk => $rv) {
                            $index += 1;
                            $num += $rv;
                            if ($index == 3) {
                                $threeday = bcdiv($num, 3, 2);
                            }
                            if ($index == 7) {
                                $sevenday = bcdiv($num, 7, 2);
                            }
                            if ($index == 14) {
                                $fourteenday = bcdiv($num, 14, 2);
                            }
                            if ($index == 30) {
                                $thirtyday = bcdiv($num, 30, 2);
                            }
                        }
                        $add_1 = bcadd($threeday, $sevenday, 2);
                        $add_2 = bcadd($fourteenday, $thirtyday, 2);
                        $add = bcadd($add_1, $add_2, 2);
                        $predict = bcdiv($add, 4, 2);
                        $options = array(
                            'expire' => 3600 * 4
                        );
                        $ca_data = array(
                            'predict' => $predict,
                            'date_num' => $res
                        );
                        cache($sess_key, $ca_data, $options);
                    } else {
                        $res = array();
                        foreach ($interval_days as $dk => $dv) {
                            $res[$dv] = 0;
                        }
                        $options = array(
                            'expire' => 3600 * 4
                        );
                        $predict = 0;
                        $ca_data = array(
                            'predict' => $predict,
                            'date_num' => $res
                        );
                        cache($sess_key, $ca_data, $options);
                    }
                    unset($sess_key, $options, $predict);
                }
                $items['predict'] = $ca_data['predict'];
            });//each给每一项数据加入自定义字段
        $today = date('Y-m-d', time());
        $this->assign('eventJS','product');
        $this->assign('today',$today);
        $this->assign('list',$data);
        $this->assign('pageDiv', $data->render());
        return $this->fetch();
    }

    //详情页
    public function predict_details($product_id) {

        //$product_id = input('request.product_id','','trim');
        $productModel = new Product();
        $product = $productModel->relation('productsize')->where('product_id', $product_id)->find();
        $end_time = date('Y-m-d', strtotime('+6 days', time()));
        $start_time = date('Y-m-d', time());
        $first_year = 2016;//数据的起始年份
        $this_year = (int)date('Y', time());//今年
        $year_arr = array();
        do {
            $mid_year = $first_year;
            $year_arr[] = $mid_year;
            $first_year +=1;
        } while ($first_year < $this_year);
        unset($first_year, $this_year, $mid_year);
        $today = date('Y-m-d', time());//今天
        //$after_thirty_days = date('Y-m-d', strtotime('+29 days', time()));//三十天之后
        $yesterday = date('Y-m-d', strtotime('-1 days', time()));//昨天
        $before_thirty_start_time = date('Y-m-d', strtotime('-30 days', strtotime($today)));//开始时间的前三十天
        $start_end_date = getDatesBetweenTwoDays($start_time, $end_time);
        $orderModel = new Order();
        $where = array(
            'product_id' => $product_id,
            'GetTimer' => ['between', [$before_thirty_start_time . ' 00:00:00', $yesterday . ' 23:59:59']],//获取开始时间前三十天到昨天的数据
            'status' => ['in', [0, 1, 2, 3, 4, 5]]
        );
        $search_date = getDatesBetweenTwoDays($before_thirty_start_time, $yesterday);
        $order_pro = $orderModel->field('GetTimer,product_id,GdsNum,status,AmzTimer')->where($where)->select();
        $date_order = array();//循环以日期为键，数量为值
        foreach ($order_pro as $k => $v) {
            $time = date('Y-m-d', strtotime($v['GetTimer']));
            if (isset($date_order[$time])) {
                $date_order[$time] += $v['GdsNum'];
            } else {
                $date_order[$time] = $v['GdsNum'];
            }
        }
        $res = array();//所有日期对应当天产品数量
        foreach ($search_date as $dk => $dv) {
            if (isset($date_order[$dv])) {
                $res[$dv] = $date_order[$dv];
            } else {
                $res[$dv] = 0;
            }
        }
        $date_res = array();
        foreach ($start_end_date as $sed_k => $sed_v) {
            if ($sed_v >= $start_time) {
                $date_res[$sed_v] = $this->everyday_data($product_id, $sed_v, $res, $date_res);
                $before_date = array();
                foreach ($year_arr as $item5 => $value5) {
                    $mid_year = explode('-', $sed_v);
                    $mid_year[0] = $value5;
                    $before_date[] = implode('-', $mid_year);
                }
                foreach ($before_date as $item6 => $value6) {
                    $date_res[$sed_v]['befores'][] = $this->before_data($product_id, $value6);
                }
            }
        }
        $product['date_num'] = $date_res;
        $product['predict'] = $date_res[$start_time]['predict'];

        $this->assign('data', $product);
        //$this->assign('day_difference', count());
        $this->assign('date', ['start_date' => $start_time, 'end_date' => $end_time]);

        return $this->fetch();
    }

    //判断是否闰年
    public function is_leap_year($year)
    {
        $year = 2008;//可以像上例一样用mt_rand随机取一个年，也可以随便赋值。
        $time = mktime(20, 20, 20, 4, 20, $year);//取得一个日期的 Unix 时间戳;
        if (date("L", $time) == 1) { //格式化时间，并且判断是不是闰年，后面的等于一也可以省略;
            echo $year . "是闰年";
        } else {
            echo $year . "不是闰年";
        }
    }
    //获取选择日期中的预估数据
    public function get_predict() {
        if ($this->request->isPost()) {
            $first_year = 2016;//数据的起始年份
            $this_year = (int)date('Y', time());//今年
            $year_arr = array();
            do {
                $mid_year = $first_year;
                $year_arr[] = $mid_year;
                $first_year +=1;
            } while ($first_year < $this_year);
            unset($first_year, $this_year, $mid_year);
            $today = date('Y-m-d', time());//今天
            $after_thirty_days = date('Y-m-d', strtotime('+29 days', time()));//三十天之后
            $yesterday = date('Y-m-d', strtotime('-1 days', time()));//昨天
            $start_time = date('Y-m-d', strtotime(input('post.start_time')));//从页面获取的开始时间
            $end_time = date('Y-m-d', strtotime(input('post.end_time')));//从页面获取的结束时间
            //$sub_start_end = (strtotime($end_time) - strtotime($start_time))/86400;
            $product_id = input('post.product_id');

            /*if ($end_time < $today || $start_time < $today) {
                return json(['code' => 1001, 'msg' => '请选择' . $today . '到' . $after_thirty_days . '之间的日期']);
            }
            if ($end_time > $after_thirty_days || $start_time > $after_thirty_days) {
                return json(['code' => 1001, 'msg' => '请选择' . $today . '到' . $after_thirty_days . '之间的日期']);
            }*/

            //$sub_start_end = count($start_end_date);

            if ($start_time > $today) {
                $start_end_date = getDatesBetweenTwoDays($today, $end_time);
                $before_thirty_start_time = date('Y-m-d', strtotime('-30 days', strtotime($today)));//开始时间的前三十天
            } else {
                $before_thirty_start_time = date('Y-m-d', strtotime('-30 days', strtotime($start_time)));//开始时间的前三十天
                $start_end_date = getDatesBetweenTwoDays($start_time, $end_time);
            }

            if ($end_time >= $today) {
                $where_endtime = $yesterday;
            } else {
                $where_endtime = date('Y-m-d', strtotime($end_time));
            }

            $orderModel = new Order();
            $where = array(
                'product_id' => $product_id,
                'GetTimer' => ['between', [$before_thirty_start_time . ' 00:00:00', $where_endtime . ' 23:59:59']],//获取开始时间前三十天到昨天的数据
                'status' => ['in', [0, 1, 2, 3, 4, 5]]
            );

            $search_date = getDatesBetweenTwoDays($before_thirty_start_time, $where_endtime);

            $order_pro = $orderModel->field('GetTimer,product_id,GdsNum,status,AmzTimer')->where($where)->select();
            $date_order = array();//循环以日期为键，数量为值
            foreach ($order_pro as $k => $v) {
                $time = date('Y-m-d', strtotime($v['GetTimer']));
                if (isset($date_order[$time])) {
                    $date_order[$time] += $v['GdsNum'];
                } else {
                    $date_order[$time] = $v['GdsNum'];
                }
            }
            $res = array();//所有日期对应当天产品数量
            $date_num = array();
            foreach ($search_date as $dk => $dv) {
                if (isset($date_order[$dv])) {
                    $res[$dv] = $date_order[$dv];
                    $date_num[] = array('date' => $dv, 'num' => $date_order[$dv]);
                } else {
                    $res[$dv] = 0;
                    $date_num[] = array('date' => $dv, 'num' => 0);
                }
            }
            $date_res = array();
            foreach ($start_end_date as $sed_k => $sed_v) {
                if ($sed_v >= $start_time) {
                    $date_res[$sed_v] = $this->everyday_data($product_id, $sed_v, $res, $date_res);
                    $before_date = array();
                    foreach ($year_arr as $item5 => $value5) {
                        $mid_year = explode('-', $sed_v);
                        if ($mid_year[0] == $value5) continue;
                        $mid_year[0] = $value5;
                        $before_date[] = implode('-', $mid_year);
                    }

                    foreach ($before_date as $item6 => $value6) {
                        $date_res[$sed_v]['befores'][] = $this->before_data($product_id, $value6);
                    }
                }
            }

            return json(['code' => 1000, 'data' => $date_res]);
        } else {
            return json(['code' => 1001, 'msg' => '此为post接口']);
        }
    }

    //处理每日的订单预估
    public function everyday_data($product_id, $predict_time, $order_pro, $predict_data) {
        //$predict_time:需要预估的日期
        $start_time = date('Y-m-d', strtotime('-30 days', strtotime($predict_time)));
        $end_time = date('Y-m-d', strtotime('-1 days', strtotime($predict_time)));
        //$before_thirty_days = date('Y-m-d', strtotime('-30 days', time()));
        $yesterday = date('Y-m-d', strtotime('-1 days', time()));//昨天
        $s_e_time = getDatesBetweenTwoDays($start_time, $end_time);
        $sess_key = 'admin_everyday_data_' . $product_id . '_' . strtotime($end_time) . '_' . strtotime($start_time);

        $ca_data = cache($sess_key);
        if ($ca_data == false) {
            $thirty_days_data = array();
            foreach ($s_e_time as $item1 => $value1) {
                if ($value1 <= $yesterday) {
                    if (isset($predict_data[$value1])) {
                        $thirty_days_data[$value1]['predict_data'] = $predict_data[$value1]['predict'];
                    } else {
                        $thirty_days_data[$value1]['predict_data'] = 'no';
                    }

                    $thirty_days_data[$value1]['real_data'] = $order_pro[$value1];
                    $thirty_days_data[$value1]['cal_data'] = $order_pro[$value1];
                } else {
                    $thirty_days_data[$value1]['real_data'] = 'no';
                    if (isset($predict_data[$value1])) {
                        $thirty_days_data[$value1]['predict_data'] = $predict_data[$value1]['predict'];
                        $thirty_days_data[$value1]['cal_data'] = $predict_data[$value1]['predict'];
                    } else {
                        $thirty_days_data[$value1]['predict_data'] = 'no';
                        $thirty_days_data[$value1]['cal_data'] = 0;
                    }

                }
            }

            $index = 0;
            $num = 0;
            $ave_arr = array();//按3,7,14,30分开数据
            foreach ($thirty_days_data as $rrk => $rrv) {
                $index += 1;
                $num = bcadd($rrv['cal_data'], $num, 2);
                switch ($index) {
                    case 3:
                        $ave_arr[] = bcdiv($num, 3, 2);
                        break;
                    case 7:
                        $ave_arr[] = bcdiv($num, 7, 2);
                        break;
                    case 14:
                        $ave_arr[] = bcdiv($num, 14, 2);
                        break;
                    case 30:
                        $ave_arr[] = bcdiv($num, 30, 2);
                        break;
                }
                /*if (count($rv) == $index && count($rv) != 30 && count($rv) != 3 && count($rv) != 7 && count($rv) != 14) {
                    $ave_arr[] = bcdiv($num, $index, 2);
                }*/
            }

            $add_ave = 0;
            foreach ($ave_arr as $ave_k => $ave_v) {
                $add_ave = bcadd($add_ave, $ave_v, 2);
            }
            $ave_count = count($ave_arr);
            $predict = bcdiv($add_ave, $ave_count, 2);

            $orderModel = new Order();
            $where = array(
                'product_id' => $product_id,
                'GetTimer' => ['between', [$predict_time . ' 00:00:00', $predict_time . ' 23:59:59']],//获取开始时间前三十天到昨天的数据
                'status' => ['in', [0, 1, 2, 3, 4, 5]]
            );
            $sum_gdsnum = $orderModel->field('sum(GdsNum) as gdsnum')->where($where)->select();
            if ($sum_gdsnum[0]['gdsnum'] != '') {
                $real_data = $sum_gdsnum[0]['gdsnum'];
            } else {
                $real_data = 'no';
            }
            $resp = array(
                'predict' => $predict,
                //'before_data' => $before_data,
                'real_data' => $real_data
            );

            $options = array(
                'expire' => 3600 * 4
            );
            //$predict_key = 'admin_everyday_data_predict_' . $product_id . '_' . strtotime($predict_time);
            cache($sess_key, $resp, $options);
            $ca_data = $resp;
        }
        return $ca_data;
    }


    //处理以往每日的订单预估
    public function before_data($product_id, $predict_time) {
        //$predict_time:需要预估的日期
        $start_time = date('Y-m-d', strtotime('-30 days', strtotime($predict_time)));
        $end_time = date('Y-m-d', strtotime('-1 days', strtotime($predict_time)));
        //$before_thirty_days = date('Y-m-d', strtotime('-30 days', time()));
        $yesterday = date('Y-m-d', strtotime('-1 days', time()));//昨天
        $s_e_time = getDatesBetweenTwoDays($start_time, $end_time);
        $sess_key = 'admin_before_data_' . $product_id . '_' . strtotime($end_time) . '_' . strtotime($start_time);
        $ca_data = cache($sess_key);
        if ($ca_data == false) {
        $orderModel = new Order();
        $where1 = array(
            'product_id' => $product_id,
            'GetTimer' => ['between', [$start_time . ' 00:00:00', $end_time . ' 23:59:59']],//获取开始时间前三十天到昨天的数据
            'status' => ['in', [0, 1, 2, 3, 4, 5]]
        );

        $search_date = getDatesBetweenTwoDays($start_time, $end_time);

        $order_pro = $orderModel->field('GetTimer,product_id,GdsNum,status,AmzTimer')->where($where1)->select();
        $date_order = array();//循环以日期为键，数量为值
        foreach ($order_pro as $k => $v) {
            $time = date('Y-m-d', strtotime($v['GetTimer']));
            if (isset($date_order[$time])) {
                $date_order[$time] += $v['GdsNum'];
            } else {
                $date_order[$time] = $v['GdsNum'];
            }
        }
        $res = array();//所有日期对应当天产品数量
        $date_num = array();
        foreach ($search_date as $dk => $dv) {
            if (isset($date_order[$dv])) {
                $res[$dv] = $date_order[$dv];
                $date_num[] = array('date' => $dv, 'num' => $date_order[$dv]);
            } else {
                $res[$dv] = 0;
                $date_num[] = array('date' => $dv, 'num' => 0);
            }
        }



            $thirty_days_data = array();
            foreach ($s_e_time as $item1 => $value1) {
                $thirty_days_data[$value1]['real_data'] = $res[$value1];
                $thirty_days_data[$value1]['cal_data'] = $res[$value1];
                $thirty_days_data[$value1]['predict_data'] = 'no';

                /*if ($value1 <= $yesterday) {
                    if (isset($predict_data[$value1])) {
                        $thirty_days_data[$value1]['predict_data'] = $predict_data[$value1]['predict'];
                    } else {
                        $thirty_days_data[$value1]['predict_data'] = 'no';
                    }

                    $thirty_days_data[$value1]['real_data'] = $order_pro[$value1];
                    $thirty_days_data[$value1]['cal_data'] = $order_pro[$value1];
                } else {
                    $thirty_days_data[$value1]['real_data'] = 'no';
                    if (isset($predict_data[$value1])) {
                        $thirty_days_data[$value1]['predict_data'] = $predict_data[$value1]['predict'];
                        $thirty_days_data[$value1]['cal_data'] = $predict_data[$value1]['predict'];
                    } else {
                        $thirty_days_data[$value1]['predict_data'] = 'no';
                        $thirty_days_data[$value1]['cal_data'] = 0;
                    }

                }*/
            }

            $index = 0;
            $num = 0;
            $ave_arr = array();//按3,7,14,30分开数据
            foreach ($thirty_days_data as $rrk => $rrv) {
                $index += 1;
                $num = bcadd($rrv['cal_data'], $num, 2);
                switch ($index) {
                    case 3:
                        $ave_arr[] = bcdiv($num, 3, 2);
                        break;
                    case 7:
                        $ave_arr[] = bcdiv($num, 7, 2);
                        break;
                    case 14:
                        $ave_arr[] = bcdiv($num, 14, 2);
                        break;
                    case 30:
                        $ave_arr[] = bcdiv($num, 30, 2);
                        break;
                }
                /*if (count($rv) == $index && count($rv) != 30 && count($rv) != 3 && count($rv) != 7 && count($rv) != 14) {
                    $ave_arr[] = bcdiv($num, $index, 2);
                }*/
            }

            $add_ave = 0;
            foreach ($ave_arr as $ave_k => $ave_v) {
                $add_ave = bcadd($add_ave, $ave_v, 2);
            }
            $ave_count = count($ave_arr);
            $predict = bcdiv($add_ave, $ave_count, 2);

            $orderModel = new Order();
            $where = array(
                'product_id' => $product_id,
                'GetTimer' => ['between', [$predict_time . ' 00:00:00', $predict_time . ' 23:59:59']],//获取开始时间前三十天到昨天的数据
                'status' => ['in', [0, 1, 2, 3, 4, 5]]
            );
            $sum_gdsnum = $orderModel->field('sum(GdsNum) as gdsnum')->where($where)->select();
            if ($sum_gdsnum[0]['gdsnum'] != '') {
                $real_data = $sum_gdsnum[0]['gdsnum'];
            } else {
                $real_data = 'no';
            }
            $before_years = explode('-', $predict_time);
            $resp = array(
                'predict' => $predict,
                //'before_data' => $before_data,
                'real_data' => $real_data,
                'year' => $before_years[0]
            );

            $options = array(
                'expire' => 3600 * 4
            );
            //$predict_key = 'admin_everyday_data_predict_' . $product_id . '_' . strtotime($predict_time);
            cache($sess_key, $resp, $options);
            $ca_data = $resp;
        }
        return $ca_data;
    }

    public function index11() {
        //admin_materialforecast_date
        die('1');
        $OrderModel = new Order();
        $materforeModel = new Productmaterialforecast();
        $materforetimeModel = new Materialforecasttime();
        //时间

        $yestady = date('Y-m-d', strtotime('-1 days', time()));
        $forty_days_ago = date('Y-m-d', strtotime('-30 days', time()));
        //$lastdata = $materforeModel->order('id desc')->limit(1)->select();//最后一条数据
        $materforetime = $materforetimeModel->where('id', 1)->find();
        if (empty($materforetime['segmentation'])) {
            $segmentation = $forty_days_ago;
            $where = array(
                'GetTimer' => ['<=', $segmentation . ' 23:59:59'],
            );
            $thesameday = false;
        } else {
            $segmentation = $forty_days_ago;
            $where = array(
                'GetTimer' => ['between', [$materforetime['segmentation'] . ' 00:00:00', $segmentation . ' 23:59:59']],
            );
            $thesameday = false;
            if ($materforetime['segmentation'] == $segmentation) $thesameday = true;
        }
        $all_order = array();
        if (!$thesameday) $all_order = $OrderModel->relation('orderproduct')->field('product_id,status,GetTimer,AmzTimer,ImgURL,SpecName,GdsNum')->order('GetTimer desc')->where($where)->select();
        $time_order = array();//pro_size_status_order

        foreach ($all_order as $key1 => $val1) {
            $gettimer = date('Y-m-d', strtotime($val1['GetTimer']));
            if (isset($time_order[$gettimer . '_' . $val1['product_id'] . '_' . $val1['SpecName'] . '_' . $val1['status']])) {
                $time_order[$gettimer . '_' . $val1['product_id'] . '_' . $val1['SpecName'] . '_' . $val1['status']]['quantity'] += $val1['GdsNum'];
            } else {
                $name = '';
                if (isset($val1['orderproduct']['name'])) $name = $val1['orderproduct']['name'];
                $time_order[$gettimer . '_' . $val1['product_id'] . '_' . $val1['SpecName'] . '_' . $val1['status']] = array(
                    'product_id' => $val1['product_id'],
                    'size' => $val1['SpecName'],
                    'quantity' => $val1['GdsNum'],
                    'time' => $gettimer . ' 00:00:00',
                    'product_name' => $name,
                    'status' => $val1['status'],
                    'creat_time' => date('Y-m-d H:i:s', time())
                );
            }
            //$time_order[$gettimer][] = $val1;
        }

        $time_pro = array_values($time_order);
        /*foreach ($time_pro as $key4 => $val4) {
            $getdata = $materforeModel->where(['product_id' => $val4['product_id'], 'size' => $val4['size'], 'time' => $val4['time'], 'status' => $val4['status']])->find();
            if (empty($getdata)) {
                $materforeModel->save($val4);
            } else {
                $materforeModel->where(['product_id' => $val4['product_id'], 'size' => $val4['size'], 'time' => $val4['time'], 'status' => $val4['status']])->update($val4);
            }
        }*/
        if (count($time_pro) > 0) {
            $time_res = $materforeModel->saveAll($time_pro);
            $segmentation_res = $materforetimeModel->where('id', 1)->update(['segmentation' => $segmentation, 'update_time' => date('Y-m-d H:i:s', time())]);
        }
        return '1';
    }


}
