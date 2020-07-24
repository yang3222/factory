<?php

namespace app\admin\model;

use think\Model;
use traits\model\SoftDelete;
class EpWarehouse extends Model
{
    protected $type = [
        'create_time'    => 'datetime:Y-m-d h:i:s',
    ];
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    const WHTYPE_EP = 0;//成品区域
    const WHTYPE_SP = 1;//半成品区域
    const WHTYPE_FP = 2;//瑕疵区域
    const WHTYPE_FBA = 3;//FBA区域
    const EP_FREE = 1;//成品免费区
    const EP_PAY = 2;//成品付费区
    public function warehouseTables(){
        return $this->hasMany('epwarehousetable','warehouse_id','id');
    }

    public function getFactorypidTextAttr($value,$data)
    {
        $status = [0=>'成品区域',1=>'半成品区域',2=>'瑕疵区域',3=>'FBA区域'];
        return $status[$data['factory_pid']];
    }

    public function getFactorysidTextAttr($value,$data)
    {
        $status = [1=>'免费区',2=>'付费区'];
        return $status[$data['factory_sid']];
    }

    public function wTables(){
        return $this->hasMany('ep_warehouse_table','warehouse_id','id');
    }
}
