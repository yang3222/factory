<?php
namespace app\index\controller;
use \app\index\model\Weight as WeightModel;
use \app\index\model\Order;
use \app\index\model\Orderfactory;
class Weight extends Base
{
    public function __construct() {
        parent::__construct();
    }
    public function index(){
        
    }
    public function config(){
        return $this->fetch();
    }
    //重量列表
    public function listdata(){
        $list_rows = 100;
        $startDate = (!empty(input('post.startDate'))?input('post.startDate'):  date('Y-m-d')).' 00:00:00';
        //$startDate='2017-01-01 00:00:00';
        $endDate =(!empty(input('post.endDate'))?input('post.endDate'):date('Y-m-d')).' 23:59:59';
        $search = input('post.search');
        $currentPage= !empty(input('post.page'))? input('post.page'): 1;
        $model=new WeightModel();
        $model->where([
            'StartTimer'=>['between',[$startDate,$endDate]]
        ]);
        if(!empty($search))$model->where('trn',$search);
        $data=$model->order('id desc')->paginate(['page'=>$currentPage,'list_rows'=>$list_rows]);
        $this->assign('listdata',$data);
        return $this->fetch();
    }
    //修改重量
    public function editweight(){
        $id=input('post.id');
        if(!empty($id)){
            $data=WeightModel::get($id);
        }else{
            $data=new WeightModel();
        }
        $boo=false;
        if(!empty(input('post.type'))){
            if($data->delete()!==false){
                $boo=true;
                $tip="删除成功!";
            }else{
                $tip="删除失败，请重新删除!";
            }
        }else{
            $trn = input('post.trn');
            if (WeightModel::get(['trn'=>$trn])) {
                $tip=$trn." 订单重复，请查看是否出错";
            } else {
                $data->trn = $trn;
                $data->weigth = input('post.weight');
                $data->StartTimer = date('y-m-d H:i:s');
                $data->country = input('post.country');
                $OrderID = input('post.OrderID');
                $data->orderID=$OrderID;
                $data->ems_weight =$data->weigth;
                $boo = $data->save() !== false;
                if (!empty($OrderID) && $boo) {
                    $orderModel = new Order();
                    $orderModel->where([
                                'OdrId' => ['=', $OrderID],
                                'TrnNo' => $trn,
                                'status' => '0'])
                            ->update([
                                'SignTimer' => date('y-m-d H:i:s'),
                                'status' => '2'
                    ]);
                    $factory = new Orderfactory();
                    $factoryboo = $factory->save(['endboo' => '1'], ['order_id' => ['=', $OrderID], 'endboo' => '0']);
                }
                if(!empty($id)){
                    $tip=$boo?"修改成功!":"修改失败，请重新保存!";
                }else{
                    $tip=$boo?"添加成功!":"添加失败，请重新添加!";
                }
                $id=$data->id;
            }
        }
        $data=array('boo'=>$boo,'id'=>$id,'tip'=>$tip);
        $this->assign('data',$data);
        return $this->fetch();
    }
    //导出数据
    public function updown(){
        $header = array("订单号", "国家", "重量");
        $index = array('trn', 'country', 'weigth');
        $filename = "飞飞鱼电子商务有限公司" . date('YmdHis');
        $startDate = (!empty(input('post.startDate'))? input('post.startDate'): date('y-m-d')).'  00:00:00';
        $endDate = (!empty(input('post.entDate'))? input('post.entDate') : date('y-m-d')).' 23:59:59';
        
        $model=new WeightModel();
        $model->where([
            'StartTimer'=>['between',[$startDate,$endDate]]
        ]);
        $data=$model->order('id desc')->select();
        $this->createtable($data, $filename, $header, $index);
    }
    //导出报关数据
    public function custom($arg = '') {
        $startDate = (!empty(input('post.startDate'))? input('post.startDate'): date('y-m-d')).'  00:00:00';
        $endDate = (!empty(input('post.entDate'))? input('post.entDate') : date('y-m-d')).' 23:59:59';
        $startDate ='2019-01-01 00:00:00';
        $endDate ='2019-01-11 23:59:59';
        $model=new WeightModel();
        $model->where([
            'StartTimer'=>['between',[$startDate,$endDate]]
        ]);
        $data = $model->order('id desc')->field('orderID')->select();
        foreach ($data as $value) {
            if ($value['orderID'] != "")
                $orders[] = $value['orderID'];
        }
        echo implode(",", $orders);
    }

    //创建表格
    private function createtable($list,$filename,$header=array(),$index = array()){
        header("Content-type:application/vnd.ms-excel charset=utf-8");
        header('Content-Disposition: attachment; filename='.$filename.'.xls');
        header("Pragma: no-cache"); 
        header("Expires: 0");
		
        $teble_header = implode("\t",$header);  
        $strexport = $teble_header."\r"; 
        foreach ($list as $row){
           foreach($index as $val){
               $strexport.=$row[$val]."\t";  
           }  
           $strexport.="\n";
        }
        $strexport=iconv('utf-8',"utf-8",$strexport);
        exit($strexport);       
    }
}
