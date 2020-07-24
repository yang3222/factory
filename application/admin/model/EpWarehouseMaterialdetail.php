<?php

namespace app\admin\model;

use think\Model;
use traits\model\SoftDelete;
class EpWarehouseMaterialdetail extends Model
{
    protected $type = [
        'create_time'    => 'datetime:Y-m-d H:i:s',
        'produce_time'    => 'datetime:Y-m-d H:i:s',
    ];
    protected $autoWriteTimestamp = 'datetime';
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    const STATUS_WH_PLAN_NO = 0;//无计划物资
    const STATUS_WH_PLAN_YES = 1;//计划物资
    const IN_DISLIST_NO = 0;//非配货单
    const IN_DISLIST_YES = 1;//配货单

    public function whtb()
    {
        return $this->hasOne('EpWarehouseTable','id','wt_id');
    }


}
