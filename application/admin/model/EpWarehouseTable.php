<?php

namespace app\admin\model;

use think\Model;
use traits\model\SoftDelete;

class EpWarehouseTable extends Model
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    public function wh()
    {
        return $this->hasOne('EpWarehouse','id','warehouse_id');
    }
}
