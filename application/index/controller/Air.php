<?php
namespace app\index\controller;

use app\index\model\User;
use \app\index\model\Menu;
use \app\index\model\Product;
use \app\index\model\Userinfo;
use \app\index\model\Orderfactory;
use \app\index\model\Order as OrderModel;
use app\index\model\Batchcode;
use \think\Db;
use think\Log;

class Air extends Base
{
    public function __construct() {
        parent::__construct();
    }
    //系统数据有误，用来更新数据库内容
    /*
    public function log(){
        exit;
        $abc=new OrderModel();
        $mysql="( SELECT `b`.`id` FROM `ink_new_order` `a` INNER JOIN `ink_order_factory` `b` ON `b`.`order_id`=`a`.`id` WHERE `status` = 0 AND `b`.`endboo` = '1' )";
        $allCount = Db::table($mysql . ' a')->chunk(100, function ($list) {
            $itemarr = array();
            foreach ($list as $data) {
                $itemarr[]=array('id'=>$data['id'],'endboo'=>'0');
            }
            $orderfactory=new Orderfactory();
            $orderfactory->saveAll($itemarr);
        });
    }*/
    //配置文件
    public function config(){
        header('content-type: text/xml; charset=utf-8');
        return $this->fetch();
    }
    //工厂信息
    public function info(){
        header('content-type: text/xml; charset=utf-8');
        return $this->fetch();
    }
    //登录判断
    public function login(){
        $User=input('post.user','','trim');
        $Pwd=base64_encode(input('post.pwd','','trim').'factory');
        $userModle = new User();
        $user=  $userModle->where(['User' => $User,'Pwd'=>$Pwd,'reviewed'=>'1','Type'=>'2'])->find();
        if ($user != null) {
            session('factory_id_air',$user['id']);
        }

        $this->assign('success',$user?1:0);
        $this->assign('data',$user);
        return $this->fetch();
    }
    public function loginuser(){
        $User=input('post.user','','trim');
        $Pwd=base64_encode(input('post.pwd','','trim').'admin');
        $user=  User::get(['User' => $User,'Pwd'=>$Pwd,'reviewed'=>'1','Type'=>'1']);
        $this->assign('success',$user?1:0);
        $this->assign('data',$user);
        return $this->fetch('air/login');
    }
    //分类栏目
    public function nav(){
        $user_id=input('post.key');
        //$user_id=16;
        $menuModel=new Menu();
        $product=new Product();
        $data=$menuModel->where(['user_id'=>$user_id,'display'=>'1'])->order('sort desc,id desc')->select();
        $this->assign('data',$data);
        return $this->fetch();
    }
    //修改密码
    public function editpwd(){
        $oldPwd = base64_encode(input('post.oldPwd') . 'factory');
        $newPwd = base64_encode(input('post.newPwd') . 'factory');
        $key = input('post.key');

        $data=User::get(['id' => $key,'Pwd'=>$oldPwd,'reviewed'=>'1','Type'=>'2']);
        $success=false;
        if($data){
            $data->Pwd=$newPwd;
            $success=($data->save()!==false);
        }
        $this->assign('success',$success?1:0);
        return $this->fetch();
    }
    //修改工厂信息
    public function editinfo(){
        $id=input('post.key');
        $data=  Userinfo::get(['user_id'=>$id]);
        $data->Name=input('post.name');
        $data->Tel=input('post.tel');
        $success=($data->save()!==false);
        $this->assign('success',$success?1:0);
        return $this->fetch('air/editpwd');
    }
    //订单信息
    public function order(){
        $list_rows=input('post.list_rows', 100);
        $orderNum=input('post.orderNum', "");  //按照数量单件和多件检索
        $setCode=input('post.setCode', "");   //检索是否有批次码
        $code=input('post.code', "");
        $isFba = input('post.isFba', "");//是否fba，1fba，0非fba

        $sign = input('post.sigin');
        $product_id = input('post.productClass');
        $current_page = input('post.page');
        $user_id = input('post.key');
        $search = input('post.search');
        $startDate = input('post.startDate').' 00:00:00';
        $endDate = input('post.endDate').' 23:59:59';

        /*$sign = "1";
        $list_rows='15';
        $product_id = "";
        $current_page = 1;
        $user_id = "29";
        $startDate="2020-3-19 00:00:00";
        $endDate="2020-4-19 23:59:59";
        $search="";*/

        $wherearr=$this->getwhere($sign,$user_id,$product_id,$search,$startDate,$endDate,$orderNum,$setCode,$code,$isFba);
        $where=$wherearr['where'];
        $searchwhere=$wherearr['searchwhere'];
        $order=$wherearr['order'];
        $newData=$this->get_new_product_num($user_id);
        $sqlbuild=$this->getDb($where,$searchwhere)->group('a.id')->buildSql();
        $allCount=  Db::table($sqlbuild.' a')->sum('GdsNum');
        //$productModel = new Product();
        $db=Db::table($sqlbuild.' a')->order($order)->paginate(['page'=>$current_page,'list_rows'=>$list_rows])->each(function($item, $key){
            $product_id = $item["product_id"]; //获取数据集中的product_id
            $productModel = new Product();
            $orderModel = new OrderModel();
            //$batchcodeModel = new Batchcode();
            $product = $productModel->field('print_subscript')->where('id', $product_id)->find();
            if (!empty($product['print_subscript'])) {
                $print_subscript = $product['print_subscript'];
            } else {
                $print_subscript = '0';
            }
            $itemwhere = array(
                'OrdNum' => $item['OrdNum'],
                'OdrId' => $item['OdrId'],
            );
            $ordsum = $orderModel->where($itemwhere)->sum('GdsNum');
            $item['print_subscript'] = $print_subscript; //给数据集追加字段并赋值
            //$item['OrdSum'] = $ordsum;
            $item['OrdSum'] = $ordsum;//该订单总数量
            //$batch = $batchcodeModel->where('id', $item['batch_code_id'])->find();
            //$item['BatchCode'] = '无';
            //if ($batch != '') $item['BatchCode'] = $batch['batch_code'];//该订单总数量
            return $item;
        });

        /*$res_arr = array();
        foreach ($db as $key => &$value) {

            if (!empty($product['print_subscript'])) {
                $value['print_subscript'] = $product['print_subscript'];
            } else {
                $value['print_subscript'] = '0,1';
            }
            $db->items()[$key]['print_subscript'] = $value['print_subscript'];
        }*/
        $this->assign('list',$db);
        $this->assign('user_id',$user_id);
        $this->assign('allCount',$allCount);
        $this->assign('newnav',$newData);
        return $this->fetch();
    }
    //获取搜索条件
    private function getwhere($sign,$user_id,$product_id,$search,$startDate,$endDate,$orderNum,$setCode,$code,$isFba){
        $timeArr=array('GetTimer','GetTimer','pro_time','SignTimer','SignTimer','library_time');
        $time_name=$timeArr[$sign];
        $where=array(
            'factory_id'=>['=',$user_id],
            $time_name=>['between',[$startDate,$endDate]]
        );
        if(!empty($orderNum)){
            if($orderNum=="1"){
                $where['GdsNum']=1;
            }else{
                $where['GdsNum']=array('neq',1);
            }
        }
        if(!empty($code)){
            $where['BatchCode']=$code;
        }else{
            if(!empty($setCode)){
                if($setCode=="1"){
                    $where['BatchCode']=array('neq','not null');
                }else{
                    $where['BatchCode']=null;
                }
            }
        }


        $order="GetTimer desc,id desc";
        if($sign=="1"||$sign=="2"||$sign=="5"){
            $where['status']='0';
            $where['endboo']='0';
            $value="0"; //新订单
            $order="Urgent desc,AmzTimer asc,GetTimer asc,id desc";
            if($sign=="5"){
                $value="2";//已出库
                $order="library_time desc";
            }else if($sign=="2"){
                $order="pro_time desc";
                $value="1";//生产中
            }
            $where['sign']=$value;
        }else if($sign=="3"||$sign=="4"){
            $where['status']=$sign-1;
            $where['endboo']='1';
            $order="SignTimer desc";
        }
        if(!empty($product_id)){
            $where['product_id']=['in',$product_id];
        }
        if($isFba != ""){
            $where['IsFBA'] = $isFba;
        }
        $searchwhere=null;
        if(!empty($search)){

            $searchwhere=array(
                'GdsSku'=>$search,
                'OdrId'=>$search,
                'OrdNum'=>$search,
                'TrnNo'=>$search,
                'BatchCode'=>$search,//搜索条件批次号增加
            );
        }
        return array('where'=>$where,'searchwhere'=>$searchwhere,'order'=>$order);
    }
    //获取订单内容
    private function getDb($where,$searchwhere=null){
        $db=Db::view('new_order a','*')
            ->view('order_factory b','factory_id,sign,endboo,pro_time,library_time','b.order_id=a.id')
            ->where($where);
        if(isset($searchwhere)){
            $db->where(function($query)use($searchwhere){
                $query->whereor($searchwhere);
            });
        }
        return $db;
    }
    //获取新订单数量
    private function get_new_product_num($factory_id){
        $where = array(
            'factory_id' => $factory_id,
            'status'=>'0',
            'sign'=>'0',
            'endboo'=>'0'
        );
        $sqlbuild=$this->getDb($where,null)->group('a.id')->buildSql();
        return Db::table($sqlbuild.' a')->field('count(product_id) count,product_id')->group('product_id')->select();
    }

    //设置订单状态
    public function inproduct(){
        $api_log = new Log();
        $order_id=input('post.orderID');
        $factory_id=input('post.key');
        $openBoo=input('post.openBoo');
        $library=input('post.library');
        $api_log::record('置位生产中log');
        $api_log::record($order_id);
        $api_log::record($factory_id);
        $api_log::record($openBoo);
        $api_log::record($library);
        /*
        $factory_id="23";
        $order_id='407362';
        $openBoo="1";*/
        $where=array('order_id'=>['in',$order_id],'factory_id'=>$factory_id);
        $orderModel = new \app\admin\model\Order();
        $order_arr = $orderModel->where(['id' => ['in', $order_id]])->select();
        $cancel = false;
        foreach ($order_arr as $orderk => $orderv) {
            if ($orderv['status'] == 3) {
                $cancel = true;
                break;
            }
        }
        if ($cancel) {
            $data=array('id'=>$order_id,'success'=>"0",'tip'=>"有取消单，请刷新页面重新修改");
            $this->assign('data',$data);
            return $this->fetch();
        }
        $data=array();
        if(isset($openBoo)){
            $data['sign']=$openBoo;//0新订单，1生产中，2已出库
            $data['library_time']=null;//出库时间
            $data['pro_time']=$openBoo=="1"?date('Y-m-d H:i:s'):null;//生产时间
            $api_log::record($data);
        }
        if(isset($library)){
            if ($library == "0") {
                $data['sign'] = "1";//1生产中
                $data['library_time'] = null;
                $data['pro_time']=date('Y-m-d H:i:s');//生产时间
            } else {
                /*$order_list = $orderModel->where(['id' => ['in',$order_id]])->select();
                $order_res = array();
                foreach ($order_list as $k => $v) {
                    if ($v['status'] == 5) {
                        $order_res[] = $v;
                    }
                }*/
                $data['sign'] = "2";
                $data['library_time'] = date('Y-m-d H:i:s');
            }
            $api_log::record($data);
        }

        $boo=(Orderfactory::update($data,$where)!==false);
        if ($boo && !is_null($data['sign'])) {
            //ActFlag: "Order.Wrk",
            //OdrIds: setOrderID,
            //jsonp: "1000"
            //修改订单为生产状态
            if ($data['sign'] == '1' || $data['sign'] == '2') {
                /*if(is_array($order_id)) {*/
                $order_data = $orderModel->where(['id' => ['in',$order_id]])->select();
                $odrs_id = array();
                foreach ($order_data as $k => $v) {
                    $odrs_id[] = $v['OdrId'];
                }
                $stringOrderIds = implode(",", $odrs_id);
                /*} else {
                    $order_data = $orderModel->where('id' , $order_id)->find();
                    $stringOrderIds = $order_data['OdrId'];
                }*/

                $url = 'http://admin.entsku.com/__/_jc/agent/order.ashx?ActFlag=Order.Wrk&OdrIds=' . $stringOrderIds . '&jsonp=1000';
                $api_log::record($stringOrderIds);
                $api_log::record($url);
                //$html = file_get_contents($url);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
                $result = curl_exec($ch);
                $api_log::record($result.'result');
                curl_close($ch);
            }
        }
        $data=array('id'=>$order_id,'success'=>$boo?"1":"0",'tip'=>$boo?"修改成功！":"修改失败，请重新修改");
        $this->assign('data',$data);
        return $this->fetch();
    }
    //获取SKU内容
    public function getsku(){
        $id = input('post.id');
        $key = input('post.key');
        /*
        $key = "16";
        $id = "383706,382148";*/
        $order=new OrderModel();
        $data=$order->where('id','in',$id)->select();
        $sku = array();
        $PrintImgURL = array();
        $OrdNum = array();
        $urgent=array();
        foreach($data as $order){
            $menudata=GetMenu($key,$order->product_id);
            $menuName=isset($menudata)?$menudata['name']:"其他";
            //A(来源)_2016-11-8（订单日期）_雨伞（分类）_(型号)_110（pid）_331632(did)_157(mid)_79(terrid)_g331632p107c121s157(SKU)_3(订单ID)
            $sku[] = $order->Type . '_' .$order->GetTimer . '_' . $menuName . '_' . $order->SpecName . "_" . $menudata['Catalog'] . '_' . $order->UpData . '_' . $order->GdsSku . '_' . $order->id;
            $PrintImgURL[] = $order->PrintImgURL;
            $OrdNum[] = $order->OrdNum;
            $urgent[]=$order->Urgent;
        }
        $data = array('id' => $id, 'PrintImgURL' => implode(",", $PrintImgURL), 'sku' => implode("|", $sku), 'OrdNum' => implode(",", $OrdNum), 'Urgent' => implode(",", $urgent));
        $this->assign('data',$data);
        return $this->fetch();
    }
    //打印的面单
    public function printconfig(){
        return $this->fetch();
    }
    //保存SKU的图片地址
    public function saveskuimg(){
        $id=input('post.id');
        $url=str_replace('\\\\', '\\',input('post.url'));
        $data=  OrderModel::get($id);
        $data->PrintImgURL=$url;
        $boo=$data->save()!==false;
        $xmldata=array();
        $xmldata['success']=$boo?"1":"0";
        $xmldata['tip']=$boo?"保存成功！":"保存失败！";
        $xmldata['id']=$id;
        $this->assign('data',$xmldata);
        return $this->fetch('air/inproduct');
    }
    //获取excel文件
    public function createexcel(){
        $sign = input('post.sigin');
        $product_id = input('post.productClass');
        $user_id = input('post.key');
        $search = input('post.search');
        $startDate = input('post.startDate').' 00:00:00';
        $endDate = input('post.endDate').' 23:59:59';

        /*
        $sign="0";
        $product_id="";
        $user_id="47";
        $search="g11833910p242c277s443";
        $startDate="2019-4-1 00:00:00";
        $endDate="2019-4-31 23:59:59";*/

        $wherearr=$this->getwhere($sign,$user_id,$product_id,$search,$startDate,$endDate,"","","","");
        $where=$wherearr['where'];
        $searchwhere=$wherearr['searchwhere'];
        $order=$wherearr['order'];

        ini_set("memory_limit", "512M");
        $header = array('导航名称', 'SKU', '产品类别', '型号', '数量', '状态', '下单时间', '状态时间', '备注');
        $index = array('menuName', 'GdsSku', 'productname', 'SpecName', 'GdsNum', 'status', 'GetTimer', 'Time', 'Memo');

        $list=$this->getDb($where,$searchwhere)->group('a.id')->order($order)->select();
        $this->createtable($user_id,$list, "123", $header, $index);
    }
    //生成Excel
    protected function createtable($user_id,$list, $filename, $header = array(), $index = array()) {
        header("Content-type:application/vnd.ms-excel");
        header("Content-Disposition:filename=" . $filename . ".xls");
        $teble_header = implode("\t", $header);
        $strexport = $teble_header . "\r";
        $productmodel=new Product();
        $productData=$this->getProductObj($productmodel->field('product_id,name')->select());
        foreach ($list as $row) {
            $timeName = "SignTimer";
            $product_id=$row['product_id'];
            $product=GetMenu($user_id,$product_id);
            foreach ($index as $val) {
                if ($val == "status") {
                    if ($row['status'] == "0") {
                        $row[$val] = "新订单";
                        $timeName = "GetTimer";
                        if ($row['sign'] == "1") {
                            $row[$val] = "生产中";
                            $timeName = "pro_time";
                        }
                        if ($row['sign'] == "2") {
                            $row[$val] = "已出库";
                            $timeName = "library_time";
                        }
                    } else if ($row[$val] == "2") {
                        $row[$val] = "已签收";
                    } else {
                        $row[$val] = "已取消";
                        if($row['sign'] == '0'){
                            $row[$val].="(新订单取消";
                        }elseif($row['sign'] == '1'){
                            $row[$val].="(生产中取消)";
                        }else{
                            $row[$val].="(已出库取消)";
                        }
                    }
                }
                if ($val == "Time") {
                    $row[$val] = $row[$timeName];
                }
                if ($val == "Memo") {
                    $row[$val] = str_replace(array("\r\n", "\r", "\n"), " ", $row['OdrMemo'] . $row['FFYMemo'] . $row['SignMemo']) . "\t";
                }
                if($val=="menuName"){
                    $row[$val] =  $product['name'];
                }
                if($val=="productname"){
                    $row[$val] =isset($productData[$product_id])?$productData[$product_id]:"没有分类";
                }
                $strexport.=$row[$val] . "\t";
            }
            $strexport.="\r";
        }
        $strexport = iconv('utf-8', "utf-8", $strexport);
        exit($strexport);
    }
    private function getProductObj($obj){
        $arr=array();
        foreach($obj as $product){
            $arr[$product->product_id]=$product->name;
        }
        return $arr;
    }
    public function getCodeBar($size) {
        $lineSize = isset($size)?$size:4;
        $text = input('request.text'); //条形码将要数据的内容
        phpQrCode($text, $lineSize, 1);
    }
    //条形码
    public function code($codebar,$size=null){
        $lineSize = isset($size)?$size:4;
        $codebar = $codebar; //条形码将要数据的内容
        $text = input('request.text'); //条形码将要数据的内容
        $height = !empty(input('request.h')) ? input('request.h') : 40;
        $height = $height / $lineSize;
        require_once(EXTEND_PATH."barcodegen.1d-php5.v5.0.1/class/BCGColor.php");
        require_once(EXTEND_PATH."barcodegen.1d-php5.v5.0.1/class/BCGDrawing.php");
        require_once(EXTEND_PATH."barcodegen.1d-php5.v5.0.1/class/".$codebar.".barcode.php");

        $color_black = new \BCGColor(0, 0, 0);
        $color_white = new \BCGColor(255, 255, 255);
        $drawException = null;

        try {
            $code = new $codebar("B"); //实例化对应的编码格式
            $code->setScale($lineSize); // Resolution
            $code->setThickness($height); // Thickness
            $code->setForegroundColor($color_black); // Color of bars
            $code->setBackgroundColor($color_white); // Color of spaces
            $code->setFont(0); // Font (or 0)
            $code->parse($text);
        } catch (Exception $exception) {
            $drawException = $exception;
        }
        $drawing = new \BCGDrawing('', $color_white);
        if ($drawException) {
            $drawing->drawException($drawException);
        } else {
            $drawing->setBarcode($code);
            $drawing->draw();
        }
        // Header that says it is an image (remove it if you save the barcode to a file)
        header('Content-type:image/png');
        // Draw (or save) the image into PNG format.
        $drawing->finish(\BCGDrawing::IMG_FORMAT_PNG);
        exit;
    }

    //批次号接口
    public function addbatchcode() {
        header('Content-Type: text/xml');
        $ids = input('post.orderID');
        $batchcodeModel = new Batchcode();
        $orderModel = new OrderModel();
        $batchcode =  chr(rand(65,90)) . date('ymdHms', time());
        $fac_id = input('post.key');
        $batch_data = array(
            'batch_code' => $batchcode,
            'factory_id' => $fac_id,
            'create_time' => date('Y-m-d H:m:s', time()),
        );
        $res = $batchcodeModel->insertGetId($batch_data);//获得batch_code_id

        if ($res !== false) {
            $orderwhere = array(
                'id' => ['in', $ids],
                'BatchCode' => null
            );
            $batch_data['id'] = $res;
            $data = $orderModel->where($orderwhere)->update(['BatchCode' => $batchcode]);
            if ($data !== false) {
                $batch_data['success'] = 1;
                $batch_data['tip'] = '添加成功';
                //$success = 1;
                //$tip = '更新成功';
                //return $xml;//array('success' => $data?"1":"0", 'tip' => $data?"更新成功":"更新失败");
            } else {
                $batch_data['success'] = 0;
                $batch_data['tip'] = '添加失败';
                //return $xml;
                //return array('success' => $data?"1":"0", 'tip' => $data?"更新成功":"更新失败");
            }
        } else {
            $batch_data['success'] = 0;
            $batch_data['tip'] = '添加失败';
            //return $xml;
            //return array('success' => $res?"1":"0", 'tip' => $res?"更新成功":"更新失败");
        }
        $this->assign('data', $batch_data);

        return $this->fetch();

    }

    //批次号list
    public function batchcode() {
        header('Content-Type: text/xml');
        $status = input('post.state');
        $fac_id = input('post.factory_id');
        $startDate = input('post.startDate').' 00:00:00';
        $endDate = input('post.endDate').' 23:59:59';
        /*
        $status="0";
        $fac_id="29";
        $startDate="2020-3-20".' 00:00:00';;
        $endDate="2020-4-20".' 23:59:59';*/

        //$times = explode('-', $time);
        $batchcodeModel = new Batchcode();
        $where = array(
            'status' => $status,
            'factory_id' => $fac_id,
            'create_time'=>['between',[$startDate,$endDate]],
        );
        $list = $batchcodeModel->where($where)->select();

        $this->assign('list', $list);
        return $this->fetch();
    }

    //edit
    public function setbatchcode() {
        $ids = input('post.id');
        $status = input('post.type');
    

        $batchcodeModel = new Batchcode();
        $res = $batchcodeModel->where(['id'=>$ids])->update(['status'=>$status]);

        if ($res !== false) {
            $success = 1;
            $tip = '更新成功';
            //return $xml;//array('success' => $data?"1":"0", 'tip' => $data?"更新成功":"更新失败");
        } else {
            $success = 0;
            $tip = '更新失败';
            //return array('success' => $data?"1":"0", 'tip' => $data?"更新成功":"更新失败");
        }
        $this->assign('success', $success);
        $this->assign('tip', $tip);
        return $this->fetch();
        //return array('success' => $res?"1":"0", 'tip' => $res?"更新成功":"更新失败");
        /*if ($res !== false) {
        } else {
            return array('success' => $res?"1":"0", 'tip' => '更新失败');
        }*/

    }
}
