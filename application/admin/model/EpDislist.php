<?php

namespace app\admin\model;

use think\Model;

class EpDislist extends Model
{
    public function whmd()
    {
        return $this->hasOne('EpWarehouseMaterialdetail','sku','sku');
    }

    public function neworder()
    {
        return $this->hasOne('Order','id','neworder_id');
    }
}
