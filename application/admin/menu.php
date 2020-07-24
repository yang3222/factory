<?php

return [
    'admin_menu'=>array(
        'menu0'=>array(
            'title'=>'仪表盘',
            'class'=>'ybp',
            'url'=>url('/admin/ybp'),
        ),
        'menu10'=>array(
            'title'=>'产品订单数据与预测',
            'class'=>'',
            'nav'=>array(
                'nav0'=>array('title'=>'数据','url'=>url('/admin/materialforecast/index')),
                'nav1'=>array('title'=>'菜单栏管理','url'=>url('/admin/materialforecast/index11')),
                'nav2'=>array('title'=>'菜单栏管理','url'=>url('/admin/'))
            ),
        ),
        'menu1'=>array(
            'title'=>'订单管理',
            'class'=>'',
            'nav'=>array(
                'upload'=>array('title'=>'自动下单','url'=>url('/automaticorder'),'target'=>'_blank'),
                'nav0'=>array('title'=>'订单列表','url'=>url('/admin/order')),
                //'nav1'=>array('title'=>'订单数据更改','url'=>url('/admin/order/newdata')),
                'nav2'=>array('title'=>'同步签收','url'=>url('/admin/order/signorder')),
                'nav3'=>array('title'=>'生产派单管理','url'=>url('/admin/productionmanage')),
                'nav4'=>array('title'=>'快递设置','url'=>url('/admin/expsetting')),
                'nav5'=>array('title'=>'生产状态管理','url'=>url('/admin/productionstatusset')),
                'nav6'=>array('title'=>'生产状态绑定','url'=>url('/admin/productionstatusset/product_status')),
            ),
        ),
        'menu2'=>array(
            'title'=>'产品管理',
            'class'=>'',
            'nav'=>array(
                'nav0'=>array('title'=>'产品列表','url'=>url('/admin/product')),
                'nav1'=>array('title'=>'生产单','url'=>url('/admin/productlist')),
            ),
        ),
        'menu3'=>array(
            'title'=>'会员管理',
            'class'=>'',
            'nav'=>array(
                'nav0'=>array('title'=>'管理列表','url'=>url('/admin/member')),
                'nav1'=>array('title'=>'工厂列表','url'=>url('/admin/factory')),
                'nav2'=>array('title'=>'用户组列表','url'=>url('/admin/member/authgroup'))
            ),
        ),
        'menu4'=>array(
            'title'=>'分类管理',
            'class'=>'',
            'nav'=>array(
                'nav0'=>array('title'=>'发货分类','url'=>''),
                'nav1'=>array('title'=>'生产分类','url'=>''),
            ),
        ),
        'menu5'=>array(
            'title'=>'库存管理',
            'class'=>'',
            'nav'=>array(
                'nav0'=>array('title'=>'所有库存','url'=>''),
                'nav1'=>array('title'=>'工厂库存','url'=>''),
                'nav2'=>array('title'=>'寄放库存','url'=>''),
                'nav3'=>array('title'=>'瑕疵品','url'=>''),
                'nav4'=>array('title'=>'已发货','url'=>''),
            ),
        ),
        'menu6'=>array(
            'title'=>'软件管理',
            'class'=>'',
            'nav'=>array(
                'nav0'=>array('title'=>'雨伞排版','url'=>url('/admin/software/umbrella')),
            ),
        ),
        'menu7'=>array(
            'title'=>'财务管理',
            'class'=>'',
            'nav'=>array(
                'nav0'=>array('title'=>'财务报表','url'=>url('/admin/finance/index')),
                'nav1'=>array('title'=>'业务报表','url'=>url('/index/business/index','type=cw'),'target'=>'_blank'),
                'nav4'=>array('title'=>'日期范围报表','url'=>url('/index/business/index','type=treasurer'),'target'=>'_blank'),
                'nav2'=>array('title'=>'订单材料报表','url'=>url('/admin/Ordermaterial/index')),
                'nav3'=>array('title'=>'物流账单对比','url'=>url('/admin/Checkbill/index')),
            ),
        ),
        'menu11'=>array(
            'title'=>'9610',
            'class'=>'',
            'nav'=>array(
                'nav0'=>array('title'=>'导入数据','url'=>url('/admin/Orderexcel/index')),
                'nav1'=>array('title'=>'导出数据','url'=>url('/admin/Orderexcel/exportdata')),
            ),
        ),
        'menu8'=>array(
            'title'=>'仓库管理',
            'class'=>'',
            'nav'=>array(
                'nav0'=>array('title'=>'材料管理','url'=>url('/admin/warehouse/material')),
                'nav1'=>array('title'=>'库位管理','url'=>'/admin/warehouse/lists'),
                'nav2'=>array('title'=>'材料明细','url'=>'/admin/warehouse/materialDetailLists'),
                'nav3'=>array('title'=>'出入库明细','url'=>'/admin/warehouse/outInDetailLists'),
                'nav8'=>array('title'=>'入库','url'=>'/admin/warehouse/inWarehouse'),
                'nav9'=>array('title'=>'出库','url'=>'/admin/warehouse/outWarehouse'),
                'nav10'=>array('title'=>'委外加工单','url'=>url('/admin/subcontractingdoc/index')),
                'nav4'=>array('title'=>'今日采购','url'=>''),
                'nav5'=>array('title'=>'今日领料','url'=>''),
                'nav6'=>array('title'=>'外发领料','url'=>''),
                'nav7'=>array('title'=>'条码打印','url'=>url('/index/business/arehouse_bar_code'),'target'=>'_blank'),
            ),
        ),

        'menu13'=>array(
            'title'=>'成品库存管理',
            'class'=>'',
            'nav'=>array(
                'nav0'=>array('title'=>'仓库物资','url'=>url('/admin/epwarehouse/materialDetailLists')),
                'nav1'=>array('title'=>'库位管理','url'=>url('/admin/epwarehouse/lists')),
                'nav3'=>array('title'=>'出入库明细','url'=>'/admin/epwarehouse/outInDetailLists'),
                'nav8'=>array('title'=>'入库','url'=>url('/admin/epwarehouse/inWarehouse')),
                'nav9'=>array('title'=>'出库','url'=>url('/admin/epwarehouse/outWarehouse')),
            ),
        ),
        'menu9'=>array(
            'title'=>'供应商管理',
            'class'=>'',
            'nav'=>array(
                'nav0'=>array('title'=>'布料供应商','url'=>url('/admin/upplier/index','id=1')),
                'nav1'=>array('title'=>'辅料供应商','url'=>url('/admin/upplier/index','id=2')),
                'nav2'=>array('title'=>'材料外加工厂','url'=>url('/admin/upplier/index','id=3')),
                'nav3'=>array('title'=>'印花供应商','url'=>url('/admin/upplier/index','id=4')),
                'nav4'=>array('title'=>'查看所有供应商','url'=>url('/admin/upplier/allsupplier')),
                'nav5'=>array('title'=>'审核','url'=>'/admin/audit/index'),
                /*'nav6'=>array('title'=>'查看供应商','url'=>'/admin/upplier/index'),*/
            ),
        ),
        'menu12'=>array(
            'title'=>'产能管理',
            'class'=>'',
            'nav'=>array(
                'nav0'=>array('title'=>'生产/指派','url'=>url('/admin/capacity/index')),//production_send_order
                //'nav1'=>array('title'=>'订单指派','url'=>url('/admin/capacity/index', 'capacity_type=2')),//production_send_order
            )
        ),

        'menu14'=>array(
            'title'=>'FBA发货管理',
            'class'=>'',
            'nav'=>array(
                'nav0'=>array('title'=>'任务列表','url'=>url('/admin/fba/lists')),//所有状态
                'nav1'=>array('title'=>'拣货','url'=>url('/admin/fba/lists') . '?url_style=picking'),
                'nav2'=>array('title'=>'发货','url'=>url('/admin/fba/lists') . '?url_style=deliver_gds'),
                'nav3'=>array('title'=>'打印码拣货','url'=>url('/admin/fba/code_pick')),
            )
        ),
    ),
];