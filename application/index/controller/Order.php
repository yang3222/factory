<?php

namespace app\index\controller;

use app\admin\model\Lgstcode;
use app\index\model\Productfactory;
use app\admin\model\Productfactorycapacity;
use app\index\model\User;
use \app\index\model\Product;
use \app\index\model\Order as OrderModel;
use \app\index\model\Orderfactory;
use app\index\model\Userinfo;
use think\Log;

class Order extends Base {

    public function __construct() {
        parent::__construct();
    }
    public function index() {
        $data['order'] = input('get.order');
        $data['type'] = isset($webtype) ? $webtype : "A";
        $data['username'] = isset($user_name) ? $user_name : "";
        $data['key'] = md5(uniqid(rand(), true));
        $factorydata = new User();
        $factorydata = $factorydata->where(['Type' => '2', 'reviewed' => '1'])->order('id asc')->select();
        $this->assign('data', $data);
        $this->assign('factorydata', $factorydata);
        return $this->fetch();
    }
    //自动下单
    public function automaticorder($webtype = null, $user_name = null) {
        exit;
        $data['type'] = isset($webtype) ? $webtype : "A";
        $data['username'] = isset($user_name) ? $user_name : "";
        $data['key'] = md5(uniqid(rand(), true));
        $userModel = new User();
        $factorydata = $userModel->relation('userinfo')->where(['Type' => '2', 'reviewed' => '1'])->order('id asc')->select();//relation关联
        //$factorydata = $userModel->where(['Type' => '2', 'reviewed' => '1'])->order('id asc')->select();
        $this->assign('data', $data);
        $this->assign('factorydata', $factorydata);
        return $this->fetch();
    }
    /*
    //删除其他多余的内容
    public function removeother(){
        $order=new OrderModel();
        $orderdata = $order->where(['GetTimer' => [ 'between',["2019-01-29 00:00:00","2019-01-29 11:59:59"]]])->select();
        $this->assign('data',$orderdata);
        return $this->fetch();
    }
    //获取数据的数量
    public function getcount(){
        $neworder = new OrderModel();
        $count = $neworder->where([
                    'OrdNum' => input('post.OrdNum'),
                    'GdsSku' => input('post.GdsSku'),
                    'GdsNum' => input('post.GdsNum'),
                ])->count();
        if($count>1){
            $data = OrderModel::get(input('post.id'));
            echo input('post.id');
            if ($data->delete()) {
                $data->orderfactroy()->delete();
            }
        }
    }*/

    //配置文件
    public function config() {
        return $this->fetch();
    }

    //提交数据
    public function postorder() {
        //订单号
        $OrdNum = input('post.OdrNo');
        if (empty($OrdNum)) return;
        $GdsSku = input('post.GdsSku'); //SKU
        $GdsNum = input('post.GdsNum'); //数量
        $PrintF = input('post.PrintFactory');
        $MacF = input('post.MacFactory');
        $PrintFactory = $this->getPrintType(empty($PrintF) ? [] : explode(',', $PrintF), '1');  //印花厂
        $MacFactory = $this->getPrintType(empty($MacF) ? [] : explode(',', $MacF), '2');  //加工厂

        //根据订单号，SKU，数量，判断是否有重复下单,偶尔会有差距0.1毫米的时间获取到的是无数据
        $order = OrderModel::get(['OrdNum' => $OrdNum, 'GdsSku' => $GdsSku, 'GdsNum' => $GdsNum]);
        if (!$order) {
            //如果订单不存在，创建新订单
            $order = new OrderModel();
            $order->OrdNum = $OrdNum;  //订单号
            $order->OdrId = input('post.OdrId');  //订单ID
            $order->AgntName = input('post.AgntName');  //分销商
            $order->GdsSku = $GdsSku;  //SKU编码
            $order->GdsNum = $GdsNum;  //数量
            $order->UpData = input('post.UpData');  //125_476029_190_100  (pid_did_mid_terrid)
            $order->ImgURL = input('post.ImgURL');    //图片路径
            $order->Type = input('post.Type');    //图片来源类型
            $order->product_id = input('post.ProdId');  //产品id
        } else {
            //修改订单状态
            $order->status = '0';
            $order->SignTimer = null;
        }
        $order->TrnNo = input('post.TrnNo'); //运单号
        $order->SpecName = input('post.SpecName');   //型号
        $order->OdrMemo = input('post.OdrMemo');    //备注
        $order->AmzTimer = input('post.TimePay');    //下单时间
        $urg = input('post.Urgent');
        if (!empty($urg))
            $order->Urgent = input('post.Urgent');    //加急
            $order->GetTimer = date('y-m-d h:i:s'); //提交时间
            $productModel = Product::get(['product_id' => $order->product_id]);
        if ($productModel->manufacture)
            $order->list_id = $productModel->manufacture->id;
        foreach ($productModel->productfactroyHas as $data) {
            if ($data->working_type == 1 && count($PrintFactory) == 0) {
                //工厂型号等于1，且$PrintFactory为空数组
                $PrintFactory[] = $data->factory_id . '_1';
            }
            if ($data->working_type == 2 && count($MacFactory) == 0) {
                $MacFactory[] = $data->factory_id . '_2';
            }
        }
        $factorydata = array_merge($PrintFactory, $MacFactory);
        $factorytip = explode(',',str_replace("_2","",str_replace("_1","",implode(',',$factorydata))));
        $factoryid = implode(",",array_unique($factorytip));
        $boo = false;
        if ($order->save() !== false) {
            $boo = true;
            $orderfactory = new Orderfactory();
            $orderfactorydata = $orderfactory->where('order_id', '=', $order->id)->select();
            foreach ($orderfactorydata as $data) {
                $value = $data['factory_id'] . '_' . $data['working_type'];
                $key = array_search($value, $factorydata);
                if ($key !== false) {
                    //存在值就删除添加的内容
                    unset($factorydata[$key]);
                } else {
                    //不存在就需要删除
                    $data->delete();
                }
            }
            $boo = $orderfactory->saveAll($this->getNewData($factorydata, $order->id)) !== false;
            
            $neworder = new OrderModel();
            $count = $neworder->where([
                        'OrdNum' => $OrdNum,
                        'GdsSku' => $GdsSku,
                        'GdsNum' => $GdsNum,
                    ])->count();
            if ($count > 1) {
                if ($order->delete()) {
                    //存在多条数据就删除
                    $order->orderfactroy()->delete();
                }
            }
        }
        $data = array('success' => $boo?"1":"0", 'id' => $order->id, 'GdsSku' => $order->GdsSku, 'tip' => ($boo ? "下单成功":("下单失败，请重新下单/".$order->OdrId)), 'factory' => $factoryid);
        $this->assign('data', $data);
        return $this->fetch();
    }


    /**
     * 下单api
     * */
    public function ApiSaveOrder() {
        //header('Access-Control-Allow-Origin:*');
        header('Content-Type:application/json');
        /*$lgsModel = new Lgstcode();
        $lgsdata = $lgsModel->where('status', '=', 1)->field('lgstcode')->select();
        $lgs = array_column($lgsdata, 'lgstcode');
        if (!in_array('YW', $lgs)) {
            $aa = 45;
        }*/
        $api_log = new Log();
        $api_log::record('api开始');//开启log
        if (!$this->request->isPost()) return json(['code' => 1002, 'msg' => '此为post接口', 'data' => '']);
        $data = input('post.');
        $api_log::record($data);
        //print_r($data);exit;
        //$data = json_decode($data, true);
        if (empty($data) || !is_array($data)) {
            $api_log::record(['code' => 1001, 'msg' => '参数为空', 'data' => '']);
            return json(['code' => 1001, 'msg' => '参数为空', 'data' => '']);
        }
        $haveStock = array();//有库存
        $noStock = array();//无库存有工厂
        $noStockFac = array();//无库存无工厂
        $noStockHaveFac = array();//无库存有工厂，这个主要做后续处理

        foreach ($data as $DKey => $DValue) {
            //$a = $data[0]['OdrNo'];
            if (is_null($DValue['OdrNo']) || is_null($DValue['GdsSku']) || is_null($DValue['GdsNum']) || $DValue['GdsNum'] <= 0) return json(['code' => 1003, 'msg' => 'OdrNo|GdsSku|GdsNum不能为空']);
            $stock = $DValue['Stock']; //库存
            if (is_null($stock)) {
                $api_log::record(['code' => 1001, 'msg' => 'stock参数不能为空', 'data' => '']);
                return json(['code' => 1001, 'msg' => 'stock参数不能为空', 'data' => '']);
            }
            $order['GetTimer'] = date('y-m-d h:i:s'); //提交时间
            $order['AmzTimer'] = $DValue['TimePay']; //下单时间
            $order['OrdNum'] = $DValue['OdrNo']; //订单号
            $order['GdsSku'] = $DValue['GdsSku']; //SKU编码
            $order['GdsNum'] = $DValue['GdsNum']; //数量
            $order['TrnNo'] = $DValue['TrnNo']; //运单号
            $order['SpecName'] = $DValue['SpecName']; //型号
            $order['OdrMemo'] = $DValue['OdrMemo'];//备注
            $order['OdrId'] = $DValue['OdrId'];  //订单ID
            $order['AgntName'] = $DValue['AgntName'];  //分销商
            $order['UpData'] = $DValue['UpData'];  //125_476029_190_100  (pid_did_mid_terrid)
            $order['ImgURL'] = $DValue['ImgURL'];    //图片路径
            $order['Type'] = $DValue['Type'];    //图片来源类型
            $order['product_id'] = $DValue['ProdId'];  //产品id
            $order['LgstCode'] = $DValue['LgstCode'];  //快递code
            $order['IsFBA'] = $DValue['IsFBA'];  //是否FBA

            $productModel = new Product();
            $inkOrder = OrderModel::get(['OrdNum' => $order['OrdNum'], 'GdsSku' => $order['GdsSku'], 'GdsNum' => $order['GdsNum'], 'OdrId' => $order['OdrId']]);
            if ($inkOrder) {//有取消单的订单号会复用，所以如果是取消单可以继续加入订单
                if ($inkOrder['status'] == 3) {
                    unset($inkOrder);
                    $inkOrder = false;
                }
            }
            $factory_arr = $productModel->relation('productfactroyHas,manufacture')->where(['product_id' => $order['product_id']])->select();
            $list_id = '';
            if (!empty($factory_arr[0]['manufacture'])) $list_id = $factory_arr[0]['manufacture']['id'];
            $order['list_id'] = $list_id;
            if (!$inkOrder) {
                if ($stock >= $order['GdsNum']) {
                    //大于等于，库存够用
                    $order['status'] = 5;//有库存，设置订单状态为5
                    $haveStock[] = $order;
                    continue;
                } else if ($stock > 0 && $stock < $order['GdsNum']) {
                    //库存大于0小于所需量，库存不够
                    $GdsNum = $order['GdsNum'] - $stock;//有部分库存进行拆单，此为需生产数量
                    $order['status'] = 5;//有库存，设置订单状态为5
                    $order['GdsNum'] = $stock;
                    $haveStock[] = $order;
                }
                //无库存
                $order['status'] = 0;//无库存，设置订单状态为0
                if (isset($GdsNum)) $order['GdsNum'] = $GdsNum;//去掉库存的剩余需要生产
                //$factory_arr = $productModel->relation('productfactroyHas,manufacture')->where(['product_id' => $order['product_id']])->select();
                //if (!empty($factory_arr[0]['manufacture'])) $order['list_id'] = $factory_arr[0]['manufacture']['id'];
                if (empty($factory_arr[0]['productfactroyHas'])) {
                    $order['status'] = 4;//无工厂，需手动设置
                    $noStockFac[] = $order;
                } else {
                    $noStock[] = $order;
                    $noStockHaveFac[$DKey] = $order;//无库存有工厂，这个主要做后续处理
                    $noStockHaveFac[$DKey]['productfactroyHas'] = $factory_arr[0]['productfactroyHas'];
                }
                unset($order,$GdsNum);
            } else {
                unset($order);
                $api_log::record(['code' => 1000, 'msg' => '添加成功，该订单已经存在', 'data' => []]);
                return json(['code' => 1000, 'msg' => '添加成功，该订单已经存在', 'data' => []]);
            }
        }
        $orderFactoryModel = new Orderfactory();
        $saveDada = array_merge($haveStock, $noStockFac, $noStock);
        $orderModel = new OrderModel();
        $api_log::record(['code' => 1000, 'msg' => '保存订单信息', 'data' => $saveDada]);
        $reshs = $orderModel->saveOrderHSFor($saveDada);
        $lgsModel = new Lgstcode();
        //$userinfoModel = new Userinfo();
        //没库存且有工厂的保存工厂
        if ($reshs['code'] == 1000 && !empty($noStock)) {
            $order_id = array();
            $orderfac = array();
            $update_profaccapa = array();//产品工厂产能表需要更新的数据
            $save_profaccapa = array();//产品工厂产能表需要保存的数据

            $lgs = cache('apiSaveOrder-lgstcode-status-1');
            if (empty($lgs)) {
                $lgsdata = $lgsModel->where('status', '=', 1)->field('lgstcode')->select();
                $lgs = array();
                foreach ($lgsdata as $klgs => $vlgs) {
                    $lgs[] = $vlgs['lgstcode'];
                }
                $options = [
                    'expire' => 864000,
                ];//这里可以增加缓存
                cache('apiSaveOrder-lgstcode-status-1', $lgs, $options);
            }
            $profaccapaModel = new Productfactorycapacity();//产品工厂产能表
            /*noStockHaveFac循环开始*/
            $api_log::record($noStockHaveFac);
            foreach ($noStockHaveFac as $k => $v) {

                $order = array();//$orderModel->where(['OrdNum' => $v['OrdNum'], 'GdsSku' => $v['GdsSku'], 'GdsNum' => $v['GdsNum'], 'status' => 0])->find();
                foreach ($reshs['order'] as $order_v) {
                    if ($order_v['OrdNum'] == $v['OrdNum'] && $order_v['GdsSku'] == $v['GdsSku'] && $order_v['GdsNum'] == $v['GdsNum'] && $order_v['status'] == 0) {
                        $order = $order_v;
                        break;
                    }
                }
                if (!in_array($v['LgstCode'], $lgs)) {
                    $orderModel->where('id', '=', $order['id'])->update(['status' => 4]);
                    continue;
                }
                //$order = $orderModel->where(['OrdNum' => $v['OrdNum'], 'GdsSku' => $v['GdsSku'], 'GdsNum' => $v['GdsNum'], 'status' => 0])->find();
                $order_id[] = $order['id'];

                $productfac_sort = array_sorts($v['productfactroyHas'], 'sort', 'asc', 2);//加工
                $productpri_sort = array_sorts($v['productfactroyHas'], 'sort', 'asc', 1);//印花
                $set_fac = array();
                $set_pri = array();
                //工厂产能自动派单循环
                foreach ($productfac_sort as $pfsk => $pfsv) {
                    if ($pfsv['urgent'] == 0) {
                        //pro_fac_id,产品工厂表id对应$pfsv['id']
                        $where['pro_fac_id'] = $pfsv['id'];
                        $where['date'] = date('Ymd',time());
                        $profaccapaData = $profaccapaModel->where($where)->find();
                        if (!empty($profaccapaData)) {
                            if ($profaccapaData['residue'] >= $v['GdsNum']) {
                                $set_fac = array(
                                    'factory_id' => $pfsv['factory_id'],
                                    'working_type' => $pfsv['working_type'],
                                    'order_id' => $order['id']
                                );
                                $update_profaccapa[] = array(
                                    'id' => $profaccapaData['id'],
                                    'residue' => bcsub($profaccapaData['residue'], $v['GdsNum'], 0),
                                );
                                //return json($update_profaccapa);
                                break;
                            }else {
                                /*if (count($productfac_sort) > 1) {*/
                                    continue;
                                /*} else {
                                    $set_fac = array(
                                        'factory_id' => $pfsv['factory_id'],
                                        'working_type' => $pfsv['working_type'],
                                        'order_id' => $order['id']
                                    );
                                    $update_profaccapa[] = array(
                                        'residue' => 0,
                                        'id' => $profaccapaData['id']
                                    );
                                }*/
                            }
                        } else {
                            if ($pfsv['capacity'] != 0 && $pfsv['capacity'] >= $v['GdsNum']) {
                                $set_fac = array(
                                    'factory_id' => $pfsv['factory_id'],
                                    'working_type' => $pfsv['working_type'],
                                    'order_id' => $order['id']
                                );
                                $save_profaccapa[] = array(
                                    'residue' => bcsub($pfsv['capacity'], $v['GdsNum'], 0),
                                    'all_capacity' => $pfsv['capacity'],
                                    'date' => date('Ymd',time()),
                                    'pro_fac_id' => $pfsv['id']
                                );
                                break;
                            } else if($pfsv['capacity'] == 0) {
                                $set_fac = array(
                                    'factory_id' => $pfsv['factory_id'],
                                    'working_type' => $pfsv['working_type'],
                                    'order_id' => $order['id']
                                );
                                break;
                            } else {
                                continue;
                            }

                        }
                    }
                }
                //工厂产能自动派单循环结束
                //印花产能自动派单循环开始
                foreach ($productpri_sort as $pfpk => $pfpv) {
                    if ($pfpv['urgent'] == 0) {
                        //pro_fac_id,产品工厂表id对应$pfsv['id']
                        $where['pro_fac_id'] = $pfpv['id'];
                        $where['date'] = date('Ymd',time());
                        $profaccapaData = $profaccapaModel->where($where)->find();
                        if (!empty($profaccapaData)) {
                            if ($profaccapaData['residue'] >= $v['GdsNum']) {
                                $set_pri = array(
                                    'factory_id' => $pfpv['factory_id'],
                                    'working_type' => $pfpv['working_type'],
                                    'order_id' => $order['id']
                                );
                                $update_profaccapa[] = array(
                                    'id' => $profaccapaData['id'],
                                    'residue' => bcsub($profaccapaData['residue'], $v['GdsNum'], 0),
                                );
                                break;
                            }else {
                                /*if (count($productfac_sort) > 1) {*/
                                continue;
                                /*} else {
                                    $set_fac = array(
                                        'factory_id' => $pfsv['factory_id'],
                                        'working_type' => $pfsv['working_type'],
                                        'order_id' => $order['id']
                                    );
                                    $update_profaccapa[] = array(
                                        'residue' => 0,
                                        'id' => $profaccapaData['id']
                                    );
                                }*/
                            }
                        } else {
                            if ($pfpv['capacity'] != 0 && $pfpv['capacity'] >= $v['GdsNum']) {
                                $set_pri = array(
                                    'factory_id' => $pfpv['factory_id'],
                                    'working_type' => $pfpv['working_type'],
                                    'order_id' => $order['id']
                                );
                                $save_profaccapa[] = array(
                                    'residue' => bcsub($pfpv['capacity'], $v['GdsNum'], 0),
                                    'all_capacity' => $pfpv['capacity'],
                                    'date' => date('Ymd',time()),
                                    'pro_fac_id' => $pfpv['id']
                                );
                                break;
                            } else if($pfpv['capacity'] == 0) {
                                $set_pri = array(
                                    'factory_id' => $pfpv['factory_id'],
                                    'working_type' => $pfpv['working_type'],
                                    'order_id' => $order['id']
                                );
                                break;
                            } else {
                                continue;
                            }

                        }
                    }
                }
                //印花产能自动派单循环结束

                $orderfac[] = $set_fac;
                $orderfac[] = $set_pri;

                /*foreach ($v['productfactroyHas'] as $pk => $pv) {
                    //按不加急工厂分配
                    if ($pv['urgent'] == 0) {
                        $profacModel = new Productfactory();
                        $orderfac[] = ['factory_id' => $pv['factory_id'], 'working_type' => $pv['working_type'], 'order_id' => $order['id']];
                    }
                    return json($pv);
                }*/

            }
            /*noStockHaveFac循环结束*/
            //return json($update_profaccapa);
            $resfac = $orderFactoryModel->saveOrderFactoryAll($orderfac);
            if ($resfac['code'] != 1000) {
                $orderModel->where('id', 'in', $order_id)->update(['status' => 4]);
            }

            $profaccapasModel = new Productfactorycapacity();//产品工厂产能表
            if (count($update_profaccapa) > 0) {
                $profaccapasModel->isUpdate()->saveAll($update_profaccapa);
            }
            if (count($save_profaccapa) > 0) {
                $profaccapasModel->saveAll($save_profaccapa);
            }

            unset($order,$save_profaccapa,$update_profaccapa);
        }
        //有库存的单指定一个固定工厂
        if ($reshs['code'] == 1000 && !empty($haveStock)) {
            $have_stock_order_id = array();
            //$have_stock_orderfac = array();
            foreach ($haveStock as $k => $v) {
                $order = $orderModel->where(['OrdNum' => $v['OrdNum'], 'GdsSku' => $v['GdsSku'], 'GdsNum' => $v['GdsNum'], 'status' => 5])->find();
                $api_log::record(['orderhaveStock' => $order]);
                //$fac_id = $userinfoModel->field('user_id')->where('Name', '=', 'havestock')->find();
                //$order = $orderModel->where(['OrdNum' => $v['OrdNum'], 'GdsSku' => $v['GdsSku'], 'GdsNum' => $v['GdsNum'], 'status' => 0])->find();
                $have_stock_order_id[] = $order['id'];
                //if (!empty($fac_id)) {
                    $have_stock_orderfac[] = ['factory_id' => 50, 'working_type' => 1, 'order_id' => $order['id']];
                    $have_stock_orderfac[] = ['factory_id' => 50, 'working_type' => 2, 'order_id' => $order['id']];
                //}
                $api_log::record(['have_stock_orderfac' => $have_stock_orderfac]);
                $rfac = $orderFactoryModel->saveOrderFactoryAll($have_stock_orderfac);
                $api_log::record(['codehfac' => $rfac]);
                unset($order,$have_stock_orderfac);
            }
            //后期再有问题先查询在保存一次
        }

        if ($reshs['code'] == 1000) {
            $api_log::record(['code' => 1000, 'msg' => '添加成功', 'data' => []]);
            return json(['code' => 1000, 'data' => '', 'msg' => '添加成功']);
        } else {
            $api_log::record(['code' => 1001, 'msg' => '添加失败', 'data' => []]);
            return json(['code' => 1001, 'data' => '', 'msg' => '添加失败']);
        }
    }

    //发货签收
    public function ShipOrder() {
        header('Content-Type:application/json');
        if (!$this->request->isPost()) {
            return json(['code' => 1001, 'msg' => '此为post接口']);
        }

        $data = input('post.OdrIds');//OdrIds=533098,533098,533098

        $orderModel = new OrderModel();
        //$orderfacModel = new Orderfactory();
        $order = $orderModel->where(['OdrId' => ['in', $data],'status' => ['in','0,2']])->select();

        if (empty($order)) return json(['code' => 1002, 'msg' => '订单不存在', 'data' => $data]);
        //$OdrStatus = intval($data['OdrStatus']);
        foreach($order as $v){
            $status = 2;
            $signTimer = date('Y-m-d H:i:s', time());
            $v->status = $status;
            $v->SignTimer = $signTimer;
            //if ($OdrStatus != 3) continue;
            if($v->save()!==false){
                $v->orderfactroy()->update(['endboo'=>'1']);
            }
        }
        return json(['code' => 1000, 'msg' => '更新成功']);

    }

    //修改fba状态
    public function changeIsFba() {
        header('Content-Type:application/json');
        $orderModel = new OrderModel();
        if (!$this->request->isPost()) {
            return json(['code' => 1001, 'msg' => '此为post接口']);
        }
        $data = input('post.');
        $where = array(
            'OdrId' => ['in', $data['orderids']],
        );
        $ores = $orderModel->where($where)->update(['IsFBA' => $data['isfba']]);
        return json(['code' => 1000, 'msg' => '操作成功']);
    }


    //循环fba订单更改fba状态
    public function cgFbaStatus() {
        exit;
        $orderModel = new OrderModel();

        $where = array(
            'OrdNum' => ['like', 'fba%'],
            'GetTimer' => ['between', ['2020-05-06 00:00:00', '2020-06-18 00:00:00']]
        );
        $ores = $orderModel->where($where)->update(['IsFBA' => 1]);
        print_r($ores);exit;
    }

    //加急修改工厂
    public function EditUrgent() {
        header('Content-Type:application/json');
        if (!$this->request->isPost()) {
            return json(['code' => 1001, 'msg' => '此为post接口']);
        }
        //$api_log = new Log();
        //$api_log::record('加急修改工厂');//开启log
        $data = input('post.data');//['OdrId' => 654887, 'isUrgent' => 1]

        $data = json_decode($data, true);
        if (empty($data['OdrId'])) return json(['code' => 1001, 'msg' => 'OdrId参数为空']);
        $OdrId = $data['OdrId'];
        $order_urgent = $data['isUrgent'];
        $orderModel = new OrderModel();
        $productModel = new Product();
        $orderfacModel = new Orderfactory();
        $order = $orderModel->relation('orderfactroy')->where(['OdrId' => $OdrId,'status' => ['in','0']])->select();
        if (empty($order)) return json(['code' => 1002, 'msg' => '订单不存在']);
        $set_order_urgent = $orderModel->update(['Urgent' => 1], ['OdrId' => $OdrId]);//修改订单加急
        $order_fac_can = array();
        $update_fac = array();
        $old_fac_id = array();
        foreach ($order as $k => $v) {
            //$product = $productModel->where(['id' => $v['product_id']])->select();
            $order_factory = $v['orderfactroy'];
            //$product_factory = $v['productfacHas'];//产品对应的工厂
            $isSign = false;
            if (count($order_factory) > 0) {
                //有设置工厂，
                foreach ($order_factory as $key => $value) {
                    if ($value['sign'] != 0) {
                        $isSign = true;
                    }
                    if ($value['endboo'] == 1) {
                        $isSign = true;
                    }
                }
                if ($isSign == true) {
                    continue;
                } else {
                    $order_fac_can[$k] = $v;
                }
            } else {
                $order_fac_can[$k] = $v;
            }
            //break
        }
        $del_old_fac = array();

        foreach ($order_fac_can as $ck => $cv) {
            //$old_fac_list = array();
            foreach ($cv['orderfactroy'] as $cck => $ccv) {
                $del_old_fac[] = $ccv['id'];
                /*$old_fac_list[$cck]['id'] = $ccv['id'];
                $old_fac_list[$cck]['factory_id'] = $ccv['factory_id'];
                $old_fac_list[$cck]['working_type'] = $ccv['working_type'];*/
            }
            $profac = $productModel->relation('productfactroyHas')->where('product_id', $cv['product_id'])->find();
            //return json($old_fac_list);
            $iufac1 = array();
            $iufac2 = array();
            $fac1 = array();
            $fac2 = array();
            foreach ($profac['productfactroyHas'] as $pck => $pcv) {
                if ($pcv['working_type'] == 1 && $pcv['urgent'] == 1) {
                    $iufac1 = ['factory_id' => $pcv['factory_id'], 'working_type' => 1, 'order_id' => $cv['id'], 'sign' => 0];
                }
                if ($pcv['working_type'] == 2 && $pcv['urgent'] == 1) {
                    $iufac2 = ['factory_id' => $pcv['factory_id'], 'working_type' => 2, 'order_id' => $cv['id'], 'sign' => 0];
                }
                if ($pcv['working_type'] == 1 && $pcv['urgent'] == 0 && $pcv['infinite'] == 0) {//$pcv['infinite'] == 0,无限制产能
                    $fac1 = ['factory_id' => $pcv['factory_id'], 'working_type' => 1, 'order_id' => $cv['id'], 'sign' => 0];
                }
                if ($pcv['working_type'] == 2 && $pcv['urgent'] == 0 && $pcv['infinite'] == 0) {
                    $fac2 = ['factory_id' => $pcv['factory_id'], 'working_type' => 2, 'order_id' => $cv['id'], 'sign' => 0];
                }
            }
            if (!empty($iufac1)) {
                $update_fac[] = $iufac1;
            } else {
                $update_fac[] = $fac1;
            }
            if (!empty($iufac2)) {
                $update_fac[] = $iufac2;
            } else {
                $update_fac[] = $fac2;
            }
            unset($iufac2,$iufac1,$fac2,$fac1);
        }
        if (count($del_old_fac) > 0) {
            $delold = $orderfacModel->where(['id' => ['in',$del_old_fac]])->delete();
        } else {
            $delold = true;
        }
        if ($delold) {
            $newfac = $orderfacModel->saveAll($update_fac);
            if ($newfac) {
                return json(['code' => 1000, 'msg' => '修改成功']);
            } else {
                return json(['code' => 1003, 'msg' => '修改失败']);
            }
        } else {
            return json(['code' => 1003, 'msg' => '修改失败']);
        }
    }

    /*public function saveDataOrder($order) {
        $orderFactoryModel = new Orderfactory();
        $orderModel = new OrderModel();
        $productModel = new Product();
        $res = $orderModel->saveOrder($order);
        if ($res['code'] == 1001) return json($res);//订单存入失败
        $factory_arr = $productModel->relation('productfactroyHas,manufacture')->where(['product_id' => $order['product_id']])->select();

        if (!empty($factory_arr[0]['productfactroyHas'])) {
            $orderfac = array();
            foreach ($factory_arr[0]['productfactroyHas'] as $k => $v) {
                $orderfac[] = ['factory_id' => $v['factory_id'], 'working_type' => $v['working_type'], 'order_id' => $res['id']];
            }
            $saveOrderFac = $orderFactoryModel->saveOrderFactory($orderfac);
            if ($saveOrderFac['code'] == 1000) {
                return json(['code' => 1000, 'msg' => '添加成功', 'data' => '']);
            } else {
                $orderModel->where('id', $res['id'])->update(['status' => 4]);//工厂添加失败设置状态为没有工厂
                return json(['code' => 1000, 'msg' => '添加成功', 'data' => $saveOrderFac['data']]);//加工厂存入失败，但订单添加成功
            }
        }
        return json(['code' => $res['code'], 'msg' => $res['msg'], 'data' => '']);
    }*/

    //组合成数据类型
    private function getPrintType($factory, $type) {
        $data = array();
        foreach ($factory as $id) {
            $data[] = $id . '_' . $type;
        }
        return $data;
    }

    //转换格式
    private function getNewData($arr, $id) {
        $data = array();
        foreach ($arr as $value) {
            $valuearr = explode('_', $value);
            $data[] = array('factory_id' => $valuearr[0], 'working_type' => $valuearr[1], 'order_id' => $id);
        }
        return $data;
    }
    //给entsku返回订单状态的内容
    public function OrderData(){
        header('Content-type:text/json');
        $keyword=input('request.KeyWord');
        $order=new OrderModel();
        $userInfo = new Userinfo();
        $OrderfacModel = new \app\admin\model\Orderfactory();

        $data=$order->relation('orderfactroy')->field('id,OrdNum,GdsSku,OdrId,ImgURL,status,product_id')->whereor(['OrdNum' => ['in' ,$keyword],'OdrId' => ['in' ,$keyword]])->select();

        $typename=array("新订单","暂停生产","已入库","已取消",'未设置工厂','有库存');
        $status=array('新订单','生产中','已出库');

        foreach($data as $order){
            $fac_id = $OrderfacModel->field('factory_id,working_type')->where('order_id', '=', $order['id'])->select();
            $printery[0] = array(
                'Name' => '',
                'user_id' => ''
            );
            $refinery[0] = array(
                'Name' => '',
                'user_id' => ''
            );
            foreach ($fac_id as $k => $v) {
                if ($v['working_type'] == 1) $printery = $userInfo->field('user_id,Name')->where('user_id', '=', $v['factory_id'])->select();
                if ($v['working_type'] == 2) $refinery = $userInfo->field('user_id,Name')->where('user_id', '=', $v['factory_id'])->select();
            }
            $order['printery'] = $printery[0]['Name'];//印花
            $order['printeryId'] = $printery[0]['user_id'];//印花id
            $order['refinery'] = $refinery[0]['Name'];//加工
            $order['refineryId'] = $refinery[0]['user_id'];//加工id
            $order['progress']=$typename[$order->status];
            $order['progress_num'] = $order->status;
            $order['ImgURL']=explode(",",$order['ImgURL'])[0];
            if($order->status=="0"||$order->status=="3"){
                $statustype=$order->status;
                $order->status="0";
                $sign=0;
                foreach ($order['orderfactroy'] as $factory) {
                    if($factory->sign>$sign){
                        $sign=$factory->sign;
                        if($statustype=="0") {
                            $order['progress']=$status[$sign];
                            $order['progress_num'] = $statustype;
                        }
                        $order->status="1";
                    }
                }
            }else{
                if($order->status == '4' || $order->status == '5') {

                } else {
                    $order->status="1";
                }
            }
            unset($order['orderfactroy']);
            unset($order['product_id']);

        }
        return json($data);
        //$factroy = $userInfo->field('Name')->where('');
        /*echo json_encode($data);
        exit;*/
    }
    //entsku取消订单的时候设置订单状态
    /*public function Sign(){
        $keyword=input('request.KeyWord');
        $orderModel=new OrderModel();
        $orderdata=$orderModel->whereor(['OrdNum'=>$keyword,'OdrId'=>$keyword])->select();
        $boo=true;
        foreach($orderdata as $data){
            $data->status="3";
            $data->SignTimer=date('y-m-d h:i:s');
            $boo=$data->save()!==false;
            if($boo){
                $boo=$data->orderfactroy()->update(['endboo'=>'1'])!==false;
            }
        }
        echo $boo;
    }*/

    //entsku取消订单的时候设置订单状态
    public function Sign(){
        $keyword = input('request.KeyWord');
        $orderModel = new OrderModel();
        $orderdata = $orderModel->whereor(['OrdNum'=>$keyword,'OdrId'=>$keyword])->select();
        if (empty($orderdata)) {
            $boo = false;
        } else {
            $boo = true;
        }
        /*$is_pro = false;//是否有生产单，false为无生产单
        foreach ($orderdata as $key => $val) {
            foreach ($val['orderfactroy'] as $kk => $vv) {
                if ($vv['sign'] == 1 || $vv['sign'] == 2) {
                    //sign为1和2表示工厂已经生产
                    $is_pro = true;
                }
            }
        }
        if ($is_pro) {
            return 0;
        }*/
        foreach($orderdata as $data){
            $data->status="3";
            $data->SignTimer=date('y-m-d h:i:s');
            //unset($data['orderfactroy']);
            $boo=$data->save()!==false;
            if($boo){
                $boo=$data->orderfactroy()->update(['endboo'=>'1'])!==false;
            }
            if (!$boo) {
                break;
            }
        }
        if ($boo) {
            return 1;//成功
        } else {
            return 0;//失败
        }
    }

    /**
     *查询是否漏单接口
     */
    public function ApiCheckOrders() {
        header('Access-Control-Allow-Origin:*');
        header('Content-Type:application/json');
        /*if (empty($_POST) && false !== strpos($this->contentType(), 'application/json')) {
            $content = file_get_contents('php://input');
            $data    = json_decode($content, true);
        } else {
            $data = $_POST;
        }*/
        $data = input('post.data');//data:json，data={"datetime":"2019-07-17" , "Ids":[{"OrderId":601767,"Num":1},{"OrderId":601765,"Num":1}]}
        $data = json_decode($data, true);//array()
        if (empty($data)) {
            return json(['msg' => '参数有误', 'data' => '', 'code' => 1001]);
        }
        $date = $data['datetime'];//要查询的时间
        $orderIds = $data['Ids'];//要比对的orderid数组
        $orderModel = new OrderModel();
        $start_time = $date . ' 00:00:00';
        $end_time = $date . ' 23:59:59';

        $new_order = $orderModel->field('OdrId,GdsNum')->whereBetween('AmzTimer', [$start_time, $end_time])->select();
        //$key_oIds = array_column($new_order, 'OdrId');
        $order = array();
        //因为订单有拆单，处理查询到的数据，让odrid为键，相同订单的gdsnum相加
        foreach ($new_order as $ork => $orv) {
            if (array_key_exists($orv['OdrId'], $order)) {
                $order[$orv['OdrId']]['GdsNum'] = $orv['GdsNum']+$order[$orv['OdrId']]['GdsNum'];
            } else{
                $order[$orv['OdrId']]['OdrId'] = $orv['OdrId'];
                $order[$orv['OdrId']]['GdsNum'] = $orv['GdsNum'];
            }
        }
        $res_arr = array();
        foreach ($orderIds as $key => $value) {
            if (array_key_exists($value['OrderId'], $order)) {
                if ($value['Num'] == $order[$value['OrderId']]['GdsNum']) {

                } else {
                    //GdsNum不同的订单
                    $res_arr[] = $value['OrderId'];
                }
            } else {
                //订单遗漏
                $res_arr[] = $value['OrderId'];
            }
        }
        if (count($res_arr) > 0) {
            return json(['msg' => '查询订单缺失', 'data' => $res_arr, 'code' => 1000]);
        } else {
            return json(['msg' => '查询订单无误', 'data' => '', 'code' => 1002]);
        }
    }

    public function isproduce() {
        //header('Access-Control-Allow-Origin:*');
        header('Content-Type:application/json');
        
        if (!$this->request->post()) return json(['code' => 1001, 'msg' => '此为post接口', 'data' => '']);
        $data = input('post.data');
        //$data = json_decode($data, true);
        $orderModel = new OrderModel();
        $res = $orderModel->relation('orderfactroy')->where('OdrId', $data)->select();
        //print_r($res);exit;
        $isproduce = array();
        //$index = 0;
        foreach ($res as $key => $value) {
            foreach($value['orderfactroy'] as $k => $v) {
                //$index = $index + 1;
                if ($v['sign'] != 0) {
                    $isproduce[] = ['sku' => $value['GdsSku'], 'num' => $value['GdsNum']];
                    break;
                }
            }
        }
        //$isproduce = array_unique($isproduce);
        return json($isproduce);
    }
    //更新运单号
    public function modify_waybill_no() {
        //header('Access-Control-Allow-Origin:*');
        header('Content-Type:application/json');
        //$api_log = new Log();
        //$api_log::record('api开始');//开启log
        if (!$this->request->isPost()) return json(['code' => 1002, 'msg' => '此为post接口', 'data' => '']);
        $data = input('post.');
        //$api_log::record($data);
        $orderModel = new OrderModel();
        $res = $orderModel->where('OdrId', $data['OdrId'])->update(['TrnNo' => $data['TrnNo']]);
        return json($res);
    }
    //工厂更新订单批次号
    public function batch_number() {
        //header('Access-Control-Allow-Origin:*');
        header('Content-Type:application/json');
        //$api_log = new Log();
        //$api_log::record('api开始');//开启log
        if (!$this->request->isPost()) return json(['code' => 1002, 'msg' => '此为post接口', 'data' => '']);
        $data = input('post.');
        //$api_log::record($data);
        $orderModel = new OrderModel();
        $res = $orderModel->where('OdrId', $data['OdrId'])->update(['batch_num' => $data['batch_num']]);
        return json($res);
    }

    public function savedataright() {
        exit(0);
        /*$orderModel = new OrderModel();
        $facmodel = new Productfactory();
        $orderFactoryModel = new Orderfactory();
        $where = array(
            'AmzTimer' => ['between', ['2020-04-21 00:00:00', '2020-04-24 18:00:00']],
            'status' => 0
        );
        $data = $orderModel->relation('orderfactroy,')->where($where)->select();
        $arr = array();
        $ave = array();
        $productModel = new Product();
        foreach ($data as $ky => $vss) {
            if (count($vss['orderfactroy']) == 0 && $vss['LgstCode'] != 'ZT') {
                $factory_arr = $productModel->relation('productfactroyHas')->where(['product_id' => $vss['product_id']])->find();
                $arr[] = $factory_arr;
                if (!isset($factory_arr['productfactroyHas'])) $factory_arr['productfactroyHas'] = array();
                foreach ($factory_arr['productfactroyHas'] as $fk => $fv) {
                    if ($fv['infinite'] == 0 && $fv['urgent'] == 0) {
                        $ave[] = array(
                            'factory_id' => $fv['factory_id'],
                            'working_type' => $fv['working_type'],
                            'order_id' => $vss['id']
                        );
                    }
                }

            }
        }

        //$profaccapaModel = new Productfactorycapacity();//产品工厂产能表
        /*noStockHaveFac循环开始

        //$resfac = $orderFactoryModel->saveOrderFactoryAll($ave);
        echo count($ave);
        print_r($ave);*/

    }

}
