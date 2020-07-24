<?php

namespace app\admin\controller;

use \app\admin\controller;
use app\admin\model\Excel9610;
use app\admin\controller\Excel;
use app\admin\model\Order;

class Orderexcel extends Excel{
    protected $pageTotalItem=40;

    public function index(){
        $ex9610Model = new Excel9610();
        $search = input('request.search','','trim');
        $model_order = $ex9610Model->order('id', 'desc');
        if(!empty($search)){

            $where = array(
                'commodity_code' => ['like', '%' . $search . '%'],
                'sku' => ['like', '%' . $search . '%']
            );
            $model_order->whereor($where);
        }
        $data = $model_order->paginate($this->pageTotalItem,false,['query' =>request()->param()]);
        $this->assign('currentMenu',array('menu'=>'menu11','nav'=>'nav0'));

        $this->assign('list',$data);
        $this->assign('pageDiv', $data->render());
        return $this->fetch();
    }

    public function exportdata() {
        //页面
        $this->assign('currentMenu',array('menu'=>'menu11','nav'=>'nav1'));
        return $this->fetch();
    }
    //导出后台数据
    public function exports() {
        parent::excelStart();
        $excel9610Model = new Excel9610();
        $ids = input('get.ids');
        if ($ids == '') return false;
        if ($ids == 'all') {
            $data = $excel9610Model->select();
        } else {
            $data = $excel9610Model->where(['id' => ['in', $ids]])->select();
        }
        $PHPExcel = new \PHPExcel();
        $PHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A1", "商品货号SKU")
            ->setCellValue("B1", "商品编号")
            ->setCellValue("C1", "商品名称")
            ->setCellValue("D1", "商品要素")
            ->setCellValue("E1", "法定计量单位")
            ->setCellValue("F1", "法定第二计量单位")
            ->setCellValue("G1", "规格型号")
            ->setCellValue("H1", "法定计量单位面积(可为空)")
            ->setCellValue("I1", "法定第二计量单位面积(可为空)")
            ->setCellValue("J1", "数量计量单位")
        ;
        $PHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(16);
        $PHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(16);
        $PHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(16);
        $PHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(80);
        $PHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(16);
        $PHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $PHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $PHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);
        $PHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);
        $PHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(18);

        foreach ($data as $k => $v) {
            $ks = $k + 2;
            $PHPExcel->setActiveSheetIndex(0)
                ->setCellValue("A" . $ks, $v['sku'])
                ->setCellValue("B" . $ks, $v['commodity_code'])
                ->setCellValue("C" . $ks, $v['name'])
                ->setCellValue("D" . $ks, $v['element'])
                ->setCellValue("E" . $ks, $v['unit'])
                ->setCellValue("F" . $ks, $v['second_unit'])
                ->setCellValue("G" . $ks, $v['specification_model'])
                ->setCellValue("H" . $ks, $v['unit_area'])
                ->setCellValue("I" . $ks, $v['sec_unit_area'])
                ->setCellValue("J" . $ks, $v['num_unit'])
            ;
        }

        $PHPExcel->setActiveSheetIndex(0);                   //设置sheet的起始位置
        //$objWriter = \PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel5');   //Excel2003通过PHPExcel_IOFactory的写函数将上面数据写出来
        $PHPWriter = \PHPExcel_IOFactory::createWriter($PHPExcel,"Excel2007"); //Excel2007
        //header('Content-Disposition: attachment;filename="用户信息.xlsx"');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition:inline;filename=后台整理数据-9610.xlsx");
        //$savefiles = ROOT_SAVE_FILES . '运单.xlsx';
        $PHPWriter->save('php://output'); //表示在$path路径下面生成demo.xlsx文件
        exit;
    }
    //导入修改数据
    public function uploadall() {
        parent::excelStart();
        $fileName = isset($_FILES["file"]) ? $_FILES["file"]["name"] : "";
        $file = isset($_FILES["file"]) ? $_FILES["file"]["tmp_name"] : "";
        $ext = \file::get_ext($fileName);
        $isCustoms = strstr($fileName, '后台整理');
        if ($isCustoms == false) {
            return '请导入正确的后台整理文件';
        }
        if ($ext == "xls") {
            $reader = \PHPExcel_IOFactory::createReader('Excel5');
        } else {
            $reader = \PHPExcel_IOFactory::createReader('Excel2007');
        }
        unset($fileName);
        unset($isCustoms);
        $excel9610Model = new Excel9610();
        $PHPExcel = $reader->load($file);
        $sheet = $PHPExcel->getSheet(0); // 读取第一個工作表
        $highestRow = $sheet->getHighestRow(); // 取得总行数
        $highestColumm = $sheet->getHighestColumn(1); // 取得总列数
        $highestColumm = \PHPExcel_Cell::columnIndexFromString($highestColumm); //字母列转换为数字列 如:AA变为27
        $filedArray = array();
        //价格
        $field = array(
            '商品货号SKU' => 'sku',
            '商品编号' => 'commodity_code',
            '商品名称' => 'name',
            '商品要素' => 'element',
            '法定计量单位' => 'unit',
            '法定第二计量单位' => 'second_unit',
            '规格型号' => 'specification_model',
            '法定计量单位面积(可为空)' => 'unit_area',
            '法定第二计量单位面积(可为空)' => 'sec_unit_area',
            '数量计量单位' => 'num_unit'
        );
        for ($col = 0; $col <= $highestColumm; $col++) {
            $value = $sheet->getCellByColumnAndRow($col, 1)->getValue();
            $value = trim($value);
            if(isset($field[$value]))$filedArray[$col]=$field[$value];
        }

        $newdata=array();

        for ($row = 2; $row <= $highestRow; $row++) { //列数是以第0列开始
            $data=array();

            foreach ($filedArray as $key=>$value){
                $data[$value]=trim($sheet->getCellByColumnAndRow($key, $row)->getValue());
            }
            $newdata[]=$data;
        }
        unset($PHPExcel);
        $saves = array();
        /*$updates = array();*/
        foreach ($newdata as $k => $v) {
            if($v['sku'] == '' || $v['commodity_code'] == '') {
                continue;
            }
            $have_data = $excel9610Model->where(['sku' => $v['sku']])->find();
            if (!empty($have_data)) {
                $have_data->commodity_code = $v['commodity_code'];
                $have_data->name = $v['name'];
                $have_data->unit = $v['unit'];
                $have_data->second_unit = $v['second_unit'];
                $have_data->element = $v['element'];
                $have_data->specification_model = $v['specification_model'];
                $have_data->sec_unit_area = $v['sec_unit_area'];
                $have_data->unit_area = $v['unit_area'];
                $have_data->unit_area = $v['num_unit'];
                $have_data->isUpdate()->save();
                /*$updates[$k] = $v;
                $updates[$k]['id'] = $have_data['id'];*/
            } else {
                $saves[] = $v;
            }
        }
        if (count($saves) > 0) {
            $boo = $excel9610Model->insertAll($saves, true);
        } else {
            $boo = true;
        }
        unset($saves);
        unset($sheet);
        if($boo!==false){
            return true;
        }else{
            return "导入失败，请重新导入数据";
        }
    }

    //导出结果数据
    public function exportexcel(){
        parent::excelStart();
        //$rate = '';
        $rate = input('post.rate');
        if (!is_numeric($rate) || empty($rate)) return false;
        $fileName = isset($_FILES["file"]) ? $_FILES["file"]["name"] : "";
        $file = isset($_FILES["file"]) ? $_FILES["file"]["tmp_name"] : "";
        $ext = \file::get_ext($fileName);
        $isCustoms = strstr($fileName, '运单号码');
        if ($isCustoms == false) {
            return '请导入正确的运单号码文件';
        }
        if ($ext == "xls") {
            $reader = \PHPExcel_IOFactory::createReader('Excel5');
        } else {
            $reader = \PHPExcel_IOFactory::createReader('Excel2007');
        }
        unset($fileName);
        unset($isCustoms);

        $PHPExcel = $reader->load($file);
        $sheet = $PHPExcel->getSheet(0); // 读取第一個工作表
        $highestRow = $sheet->getHighestRow(); // 取得总行数
        $highestColumm = $sheet->getHighestColumn(1); // 取得总列数
        $highestColumm = \PHPExcel_Cell::columnIndexFromString($highestColumm); //字母列转换为数字列 如:AA变为27
        $filedArray = array();
        //价格
        $field = array(
            '运单号' => 'waybill_num',
            '重量' => 'waybill_weight',
            '运费' => 'freight',
            '国家' => 'countries'
        );
        $counts = array(
            '美国' => '502',
            '日本' => '116'
        );
        for ($col = 0; $col <= $highestColumm; $col++) {
            $value = $sheet->getCellByColumnAndRow($col, 1)->getValue();
            $value = trim($value);
            if(isset($field[$value])) $filedArray[$col]=$field[$value];
        }

        $waybill_nums = array();
        $waybill = array();
        $freight = array();
        $countries = array();
        for ($row = 2; $row <= $highestRow; $row++) { //列数是以第0列开始
            $data=array();

            foreach ($filedArray as $key=>$value){
                $data[$value]=trim($sheet->getCellByColumnAndRow($key, $row)->getValue());
            }

            if (!empty($data['waybill_num'])) $waybill_nums[] = $data['waybill_num'];
            if (!empty($data['waybill_num'])) $freight[$data['waybill_num']] = (float)$data['freight'];
            if (!empty($data['waybill_num'])) $waybill[$data['waybill_num']] = (float)bcmul($data['waybill_weight'], '1000', 2);//将千克转换为克
            if (!empty($data['waybill_num'])) {
                $countries[$data['waybill_num']] = array('nums' => $counts[$data['countries']], 'counts' => $data['countries']);
            }
        }
        //通过接口获取数据
        $waybill_num_str = implode(',', array_unique($waybill_nums));
        $post_data = array(
            'TrnNo' => $waybill_num_str
        );
        unset($waybill_nums);
        $post_url = 'http://webapi.38420.com/api/Order/Query9610';
        $post_res = send_post($post_url, $post_data);
        $post_res = json_decode($post_res, true);
        /*$bbbb = array_column($post_res, 'TrnNo');
        $aaa = array();
        foreach ($waybill_nums as $kke => $kkv) {

            if (!in_array($kkv, $bbbb)) {
                $aaa[] = $kkv;
            }
        }
        print_r($aaa);exit;*/
        $yundan = array();
        foreach ($post_res as $k1 => $v1) {
            $v1['GdsSku'] = strstr($v1['GdsSku'], 'p');
            $v1['OdrNo'] = (string)$v1['OdrNo'];
            $v1['SaleMny'] = round($v1['SaleMny']);//将售价四舍五入
            $yundan[$v1['TrnNo']][] = $v1;
        }

        $yd_arr = array();
        foreach ($yundan as $ks => $vs) {
            if (count($vs) > 1) {//一个运单号对应多个sku
                foreach ($vs as $key => $val) {
                    if(isset($yd_arr[$ks][$val['GdsSku']])) {
                        //数量
                        $yd_arr[$ks][$val['GdsSku']]['GdsNum'] = (int)bcadd($yd_arr[$ks][$val['GdsSku']]['GdsNum'], $val['GdsNum']);
                        //运费
                        $yd_arr[$ks][$val['GdsSku']]['GdsMny'] = (float)bcadd($yd_arr[$ks][$val['GdsSku']]['GdsMny'], $val['GdsMny']);
                        //重量
                        $yd_arr[$ks][$val['GdsSku']]['GdsWeight'] = (float)bcadd($yd_arr[$ks][$val['GdsSku']]['GdsWeight'], $val['GdsWeight']);
                        //售价
                        $yd_arr[$ks][$val['GdsSku']]['SaleMny'] = (float)bcadd($yd_arr[$ks][$val['GdsSku']]['SaleMny'], $val['SaleMny'], 2);
                    } else {
                        $yd_arr[$ks][$val['GdsSku']] = $val;
                    }
                }
            } else if(count($vs) == 1) {
                $yd_arr[$ks][$vs[0]['GdsSku']] = $vs[0];
            }
        }
        //unset();
        $excel9610Model = new Excel9610();

        foreach ($yd_arr as $yk => &$yv) {
            $allwei = 0;
            $yd_index = 0;
            foreach ($yv as $k => &$v) {
                $yd_index += 1;
                $mod9610 = $excel9610Model->where('sku', $k)->find();
                $v['TranMny'] = (float)bcdiv($freight[$yk], $rate, 4);
                $v['TranMny'] = round($v['TranMny'], 2);
                $v['SaleMny'] = (float)bcmul($v['SaleMny'], $v['GdsNum'], 2);
                $v['waybill_weight'] = $waybill[$yk];//运单重量
                $allwei += $v['GdsWeight'];//总重量
                if (!empty($countries[$yk])) {
                    $v['country'] = $countries[$yk];
                } else {
                    $v['country'] = array('nums' => '', 'counts' => '');
                }
                if (!empty($mod9610)) {
                    $v['name'] = $mod9610['name'];
                    $v['unit'] = $mod9610['unit'];
                    $v['second_unit'] = $mod9610['second_unit'];
                    $v['specification_model'] = $mod9610['specification_model'];
                    $v['commodity_code'] = $mod9610['commodity_code'];
                    $v['unit_area'] = $mod9610['unit_area'];
                    $v['sec_unit_area'] = $mod9610['sec_unit_area'];
                    $v['num_unit'] = $mod9610['num_unit'];
                } else {
                    $v['name'] = '';
                    $v['unit'] = '';
                    $v['second_unit'] = '';
                    $v['specification_model'] = '';
                    $v['commodity_code'] = '';
                    $v['unit_area'] = '';
                    $v['sec_unit_area'] = '';
                    $v['num_unit'] = '';
                }
            }
            unset($v);
            //计算重量
            if (!empty($waybill[$yk]) && $yd_index > 1) {
                if ($waybill[$yk] > $allwei) {
                    $wasub = (int)bcsub($waybill[$yk], $allwei, 1);//差值
                    $wmod = (int)bcmod($wasub, $yd_index);//求余数
                    $wmod_sub = bcsub($wasub, $wmod, 1);//差值与余数的差
                    $wave = bcdiv($wmod_sub, $yd_index, 1);//平均值
                    //$wallwei = bcmul($wave, $yd_index, 1);
                    foreach ($yv as $k1s => &$v1s) {
                        $v1s['GdsWeight'] = bcadd($v1s['GdsWeight'], $wave, 1);
                    }
                    unset($v1s);
                    foreach ($yv as $k1s => &$v1s) {
                        $v1s['GdsWeight'] = bcadd($v1s['GdsWeight'], $wmod, 1);
                        break;
                    }
                    unset($v1s);
                } else if ($waybill[$yk] < $allwei) {
                    $wasub = (int)bcsub($allwei, $waybill[$yk], 1);//差值
                    $wmod = (int)bcmod($wasub, $yd_index);//求余数
                    $wmod_sub = bcsub($wasub, $wmod, 1);//差值与余数的差
                    $wave = bcdiv($wmod_sub, $yd_index, 1);//平均值
                    //$wallwei = bcmul($wave, $yd_index, 1);
                    foreach ($yv as $k1s => &$v1s) {
                        $v1s['GdsWeight'] = bcsub($v1s['GdsWeight'], $wave, 1);
                    }
                    unset($v1s);
                    foreach ($yv as $k1s => &$v1s) {
                        $v1s['GdsWeight'] = bcsub($v1s['GdsWeight'], $wmod, 1);
                        break;
                    }
                    unset($v1s);
                }
            } else if (!empty($waybill[$yk]) && $yd_index == 1) {
                if ($waybill[$yk] != $allwei) {
                    //$wasub = bcsub($waybill[$yk], $allwei, 0);
                    foreach ($yv as $kks => &$vvs) {
                        $vvs['GdsWeight'] = $waybill[$yk];
                    }
                    unset($vvs);
                }
            }
            unset($allwei);
        }
        unset($yv);// & 引用赋值后必须将该值销毁，以便接下来继续使用 $value 这个变量

        $this->set_order_in($yd_arr);
        $this->set_invt_in($yd_arr);//清单
        $this->set_yundan($yd_arr);//运单
        $this->set_allyundan($yd_arr);//总运单
        $zipmodel = new \ZipArchive();
        if (file_exists(ROOT_SAVE_FILES . 'data.zip')) unlink(ROOT_SAVE_FILES . 'data.zip');
        if ($zipmodel->open(ROOT_SAVE_FILES . 'data.zip', \ZipArchive::CREATE) === true) {
            $zipmodel->addFile(ROOT_SAVE_FILES . '总分单.xls', '/总分单.xls');
            $zipmodel->addFile(ROOT_SAVE_FILES . 'order_in.xls', '/order_in.xls');
            $zipmodel->addFile(ROOT_SAVE_FILES . 'invt_in.xls', '/invt_in.xls');
            $zipmodel->addFile(ROOT_SAVE_FILES . '运单.xls', '/运单.xls');
        }
        $zipmodel->close();
        downloadzipFile(ROOT_SAVE_FILES . 'data.zip', 'data.zip');
        //$down_time = date('Ymd', time());
        //downloadzipFile(ROOT_SAVE_FILES . 'data.zip', 'data-' . $down_time . '.zip');
    }

    //运单
    public function set_yundan($data) {
        $PHPExcel = new \PHPExcel();
        $PHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A1","报送类型")
            ->setCellValue("B1","报送状态")
            ->setCellValue("C1","物流企业代码")
            ->setCellValue("D1","物流企业名称")
            ->setCellValue("E1","运单号")
            ->setCellValue("F1","运费")
            ->setCellValue("G1","保价费")
            ->setCellValue("H1","币制")
            ->setCellValue("I1","毛重")
            ->setCellValue("J1","件数")
            ->setCellValue("K1","主要货物信息")
            ->setCellValue("L1","电商企业代码")
            ->setCellValue("M1","电商企业名称")
            ->setCellValue("N1","电商企业电话")//SKU
            ->setCellValue("O1","备注")
            ->setCellValue("P1","传输企业代码")
            ->setCellValue("Q1","传输企业名称")
            ->setCellValue("R1","接收部门");
        $PHPExcel->getActiveSheet()->getStyle('A')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('B')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('C')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('D')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('E')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('F')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('G')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('H')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('I')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('J')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('K')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('L')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('M')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('N')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('O')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('P')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('Q')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('R')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);

        $index = 1;
        foreach ($data as $kk => $vv) {
            if (count($vv) > 1) {
                $wei = 0;
                $waybillwei = '';
                $name = array();
                foreach ($vv as $kkk => $vvv) {
                    $wei += $vvv['GdsWeight'];
                    $trnmony = $vvv['TranMny'];
                    $name[] = $vvv['name'];
                    $waybillwei = $vvv['waybill_weight'];
                }
                if (!empty($waybillwei)) {
                    $waybillwei = bcdiv($waybillwei, 1000, 3);
                } else {
                    $waybillwei = bcdiv($wei, 1000, 3);
                }
                $index += 1;
                $PHPExcel->getActiveSheet()
                    ->setCellValue("A" . $index, 1)
                    ->setCellValue("B" . $index, 2)
                    ->setCellValue("C" . $index, '3502180231')
                    ->setCellValue("D" . $index, '中国邮政速递物流股份有限公司厦门市分公司')
                    ->setCellValue("E" . $index, $kk)
                    ->setCellValue("F" . $index, $trnmony)
                    ->setCellValue("G" . $index, 0)
                    ->setCellValue("H" . $index, '502')
                    ->setCellValue("I" . $index, $waybillwei)
                    ->setCellValue("J" . $index, 1)
                    ->setCellValue("K" . $index, implode(',', $name))
                    ->setCellValue("L" . $index, '3502160EEV')
                    ->setCellValue("M" . $index, '厦门飞飞鱼供应链管理有限公司')
                    ->setCellValue("N" . $index, '+86 17350037897')
                    //->setCellValue("O" . $index, '')
                    ->setCellValue("P" . $index, '3502180231')
                    ->setCellValue("Q" . $index, '中国邮政速递物流股份有限公司厦门市分公司')
                    ->setCellValue("R" . $index, 'C');
                unset($waybillwei,$name,$wei);

            } else {
                $index += 1;
                foreach ($vv as $ks1 => $vs1) {
                    if (!empty($vs1['waybill_weight'])) {
                        $w = bcdiv($vs1['waybill_weight'], 1000, 3);
                    } else {
                        $w = bcdiv($vs1['GdsWeight'], 1000, 3);
                    }
                    $PHPExcel->getActiveSheet()
                        ->setCellValue("A" . $index, 1)
                        ->setCellValue("B" . $index, 2)
                        ->setCellValue("C" . $index, '3502180231')
                        ->setCellValue("D" . $index, '中国邮政速递物流股份有限公司厦门市分公司')
                        ->setCellValue("E" . $index, $kk)
                        ->setCellValue("F" . $index, $vs1['TranMny'])
                        ->setCellValue("G" . $index, 0)
                        ->setCellValue("H" . $index, '502')
                        ->setCellValue("I" . $index, $w)
                        ->setCellValue("J" . $index, 1)
                        ->setCellValue("K" . $index, $vs1['name'])
                        ->setCellValue("L" . $index, '3502160EEV')
                        ->setCellValue("M" . $index, '厦门飞飞鱼供应链管理有限公司')
                        ->setCellValue("N" . $index, '+86 17350037897')
                        //->setCellValue("O" . $index, '')
                        ->setCellValue("P" . $index, '3502180231')
                        ->setCellValue("Q" . $index, '中国邮政速递物流股份有限公司厦门市分公司')
                        ->setCellValue("R" . $index, 'C');
                    unset($w);
                }
            }
        }

        //$PHPExcel->getActiveSheet()->setTitle('物料');      //设置sheet的名称
        $PHPExcel->setActiveSheetIndex(0);                   //设置sheet的起始位置
        //$objWriter = \PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel5');   //Excel2003通过PHPExcel_IOFactory的写函数将上面数据写出来
        $PHPWriter = \PHPExcel_IOFactory::createWriter($PHPExcel,"Excel2007"); //Excel2007
        //header('Content-Disposition: attachment;filename="用户信息.xlsx"');
        //header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        //header("Content-Disposition:inline;filename=order_in.xlsx");
        $savefiles = ROOT_SAVE_FILES . '运单.xls';
        $PHPWriter->save($savefiles); //表示在$path路径下面生成demo.xlsx文件
    }

    //总运单
    public function set_allyundan($data) {
        $PHPExcel = new \PHPExcel();
        $PHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A1","报送类型")
            ->setCellValue("B1","报送状态")
            ->setCellValue("C1","申报地海关代码")
            ->setCellValue("D1","企业唯一编号")
            ->setCellValue("E1","电子口岸编号")
            ->setCellValue("F1","申报企业代码")
            ->setCellValue("G1","申报企业名称")
            ->setCellValue("H1","监管场所代码")
            ->setCellValue("I1","运输方式")
            ->setCellValue("J1","运输工具名称")
            ->setCellValue("K1","航班航次号")
            ->setCellValue("L1","总运单号")
            ->setCellValue("M1","境内运输工具编号")
            ->setCellValue("N1","毛重(公斤)")
            ->setCellValue("O1","物流企业代码")
            ->setCellValue("P1","物流企业名称")
            ->setCellValue("Q1","报文总数")
            ->setCellValue("R1","报文序号")
            ->setCellValue("S1","备注")
            ->setCellValue("T1","序号")
            ->setCellValue("U1","总包号")
            ->setCellValue("V1","物流运单编号")
            ->setCellValue("W1","出口清单编号")
            ->setCellValue("X1","备注")
            ->setCellValue("Y1","传输企业代码")
            ->setCellValue("Z1","报文传输的企业名称")
            ->setCellValue("AA1","接收部门");
        $PHPExcel->getActiveSheet()->getStyle('A')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('B')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('C')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('D')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('E')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('F')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('G')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('H')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('I')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('J')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('K')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('L')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('M')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('N')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('O')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('P')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('Q')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('R')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('S')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('T')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('U')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('V')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('W')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('X')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('Y')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('Z')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('AA')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);

        $index = 1;
        foreach ($data as $kk => $vv) {
            if (count($vv) > 1) {
                $wei = 0;
                $waybillwei = '';
                foreach ($vv as $kkk => $vvv) {
                    $wei += $vvv['GdsWeight'];
                    //$trnmony = $vvv['TranMny'];
                    //$name = $vvv['name'];
                    $waybillwei = $vvv['waybill_weight'];
                }
                if (!empty($waybillwei)) {
                    $waybillwei = bcdiv($waybillwei, 1000, 3);
                } else {
                    $waybillwei = bcdiv($wei, 1000, 3);
                }
                //$wei = bcdiv($wei, 1000, 3);
                $index += 1;
                $PHPExcel->getActiveSheet()
                    ->setCellValue("A" . $index, 1)
                    ->setCellValue("B" . $index, 2)
                    ->setCellValue("C" . $index, '3713')
                    //->setCellValue("D" . $index, '')
                    ->setCellValue("E" . $index, '0')
                    ->setCellValue("F" . $index, '3502180231')
                    ->setCellValue("G" . $index, '中国邮政速递物流股份有限公司厦门市分公司')
                    //->setCellValue("H" . $index, '')
                    ->setCellValue("I" . $index, 6)
                    ->setCellValue("J" . $index, '飞机')
                    ->setCellValue("K" . $index, 'MF871')
                    //->setCellValue("L" . $index, '')
                    ->setCellValue("M" . $index, '0')
                    ->setCellValue("N" . $index, $waybillwei)
                    ->setCellValue("O" . $index, '3502180231')
                    ->setCellValue("P" . $index, '中国邮政速递物流股份有限公司厦门市分公司')
                    ->setCellValue("Q" . $index, '1')
                    ->setCellValue("R" . $index, '1')
                    //->setCellValue("S" . $index, '')
                    //->setCellValue("T" . $index, '')
                    //->setCellValue("U" . $index, '')
                    ->setCellValue("V" . $index, $kk)
                    //->setCellValue("W" . $index, '')
                    //->setCellValue("X" . $index, '')
                    ->setCellValue("Y" . $index, '3502180231')
                    ->setCellValue("Z" . $index, '中国邮政速递物流股份有限公司厦门市分公司')
                    ->setCellValue("AA" . $index, 'C');
                unset($waybillwei,$wei);
            } else {
                $index += 1;
                foreach ($vv as $ks1 => $vs1) {
                    //$wei = bcdiv($vs1['GdsWeight'], 1000, 3);
                    if (!empty($vs1['waybill_weight'])) {
                        $wei = bcdiv($vs1['waybill_weight'], 1000, 3);
                    } else {
                        $wei = bcdiv($vs1['GdsWeight'], 1000, 3);
                    }
                    $PHPExcel->getActiveSheet()
                        ->setCellValue("A" . $index, 1)
                        ->setCellValue("B" . $index, 2)
                        ->setCellValue("C" . $index, '3713')
                        //->setCellValue("D" . $index, '')
                        ->setCellValue("E" . $index, '0')
                        ->setCellValue("F" . $index, '3502180231')
                        ->setCellValue("G" . $index, '中国邮政速递物流股份有限公司厦门市分公司')
                        //->setCellValue("H" . $index, '')
                        ->setCellValue("I" . $index, 6)
                        ->setCellValue("J" . $index, '飞机')
                        ->setCellValue("K" . $index, 'MF871')
                        //->setCellValue("L" . $index, '')
                        ->setCellValue("M" . $index, '0')
                        ->setCellValue("N" . $index, $wei)
                        ->setCellValue("O" . $index, '3502180231')
                        ->setCellValue("P" . $index, '中国邮政速递物流股份有限公司厦门市分公司')
                        ->setCellValue("Q" . $index, '1')
                        ->setCellValue("R" . $index, '1')
                        //->setCellValue("S" . $index, '')
                        //->setCellValue("T" . $index, '')
                        //->setCellValue("U" . $index, '')
                        ->setCellValue("V" . $index, $kk)
                        //->setCellValue("W" . $index, '')
                        //->setCellValue("X" . $index, '')
                        ->setCellValue("Y" . $index, '3502180231')
                        ->setCellValue("Z" . $index, '中国邮政速递物流股份有限公司厦门市分公司')
                        ->setCellValue("AA" . $index, 'C');
                }
            }
        }

        //$PHPExcel->getActiveSheet()->setTitle('物料');      //设置sheet的名称
        $PHPExcel->setActiveSheetIndex(0);                   //设置sheet的起始位置
        //$objWriter = \PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel5');   //Excel2003通过PHPExcel_IOFactory的写函数将上面数据写出来
        $PHPWriter = \PHPExcel_IOFactory::createWriter($PHPExcel,"Excel2007"); //Excel2007
        //header('Content-Disposition: attachment;filename="用户信息.xlsx"');
        //header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        //header("Content-Disposition:inline;filename=order_in.xlsx");
        $savefiles = ROOT_SAVE_FILES . '总分单.xls';
        $PHPWriter->save($savefiles); //表示在$path路径下面生成demo.xlsx文件
    }

    //invt_in,清单
    public function set_invt_in($data) {
        //$material=new Material();
        //$data = $yd_arr;
        $PHPExcel = new \PHPExcel();
        $PHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A1","业务批次号：")
            ->setCellValue("A2","贸易方式：")
            ->setCellValue("A3","电商平台/海外仓名称：")
            ->setCellValue("A4","运费币制：")
            //->setCellValue("B1","")
            ->setCellValue("B2",9610)
            ->setCellValue("B3","厦门飞飞鱼供应链管理有限公司")
            ->setCellValue("B4","美元")
            ->setCellValue("C1","出口日期：")
            ->setCellValue("C2","申报地海关代码：")
            ->setCellValue("C3","物流企业名称：")
            ->setCellValue("C4","运费标志：")
            //->setCellValue("D1","")
            ->setCellValue("D2",3713)
            ->setCellValue("D3","中国邮政速递物流股份有限公司厦门市分公司")
            ->setCellValue("D4",3)
            ->setCellValue("E1","运输方式：")
            ->setCellValue("E2","出口口岸海关代码：")
            ->setCellValue("E3","申报企业名称：")
            ->setCellValue("E4","保费币制：")
            ->setCellValue("F1","航空运输")
            ->setCellValue("F2",3715)
            ->setCellValue("F3","厦门飞飞鱼供应链管理有限公司")
            ->setCellValue("F4","美元")
            ->setCellValue("G1","运输工具编号：")
            ->setCellValue("G2","")
            ->setCellValue("G3","监管场所代码：")
            ->setCellValue("G4","保费标志：")
            //->setCellValue("H1","")
            //->setCellValue("H2","")
            ->setCellValue("H3",370002)
            ->setCellValue("H4",3)
            //->setCellValue("I1","")
            //->setCellValue("I2","")
            ->setCellValue("I3","申报业务类型：")
            //->setCellValue("I4","")
            //->setCellValue("J1","")
            //->setCellValue("J2","")
            ->setCellValue("J3","B")
            //->setCellValue("J4","")
            ->setCellValue("K1","提运单号：")
            //->setCellValue("K2","")
            //->setCellValue("K3","")
            //->setCellValue("K4","")
            ->setCellValue("A5","序号")
            ->setCellValue("B5","企业内部编号")
            ->setCellValue("C5","物流运单号")
            ->setCellValue("D5","电商订单/海外仓订仓单编号")
            ->setCellValue("E5","清单编号")
            ->setCellValue("F5","发货人代码")
            ->setCellValue("G5","生产企业代码")
            ->setCellValue("H5","生产销售企业名称")
            ->setCellValue("I5","毛重（公斤）")//合并
            ->setCellValue("J5","净重（公斤）")//合并
            ->setCellValue("K5","运抵国（地区）")
            ->setCellValue("L5","指运港代码")
            ->setCellValue("M5","件数")
            ->setCellValue("N5","运费")
            ->setCellValue("O5","保费")//SKU
            ->setCellValue("P5","包装种类")
            ->setCellValue("Q5","备注")
            ->setCellValue("R5","商品序号")
            ->setCellValue("S5","企业商品货号")
            ->setCellValue("T5","企业商品品名")
            ->setCellValue("U5","商品编码")
            ->setCellValue("V5","商品名称")
            ->setCellValue("W5","商品规格型号")
            ->setCellValue("X5","条码")
            ->setCellValue("Y5","目的国（地区）")
            ->setCellValue("Z5","数量")
            ->setCellValue("AA5","计量单位")
            ->setCellValue("AB5","法定数量")
            ->setCellValue("AC5","法定计量单位")
            ->setCellValue("AD5","第二数量")
            ->setCellValue("AE5","第二计量单位")
            ->setCellValue("AF5","单价")
            ->setCellValue("AG5","总价")
            ->setCellValue("AH5","币制");
        $PHPExcel->getActiveSheet()->getStyle('A')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('B')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('C')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('D')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('E')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('F')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('G')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('H')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('I')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('J')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('K')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('L')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('M')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('N')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('O')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('P')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('Q')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('R')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('S')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('T')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('U')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('V')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('W')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('X')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('Y')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('Z')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('AA')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('AB')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('AC')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('AD')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('AE')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('AF')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('AG')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('AH')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);

        $index = 5;
        $batch = $index;
        //多条数据与一条数据
        foreach ($data as $kk => $vv) {
            if (count($vv) > 1) {
                $xh = 0;
                $batch += 1;
                $wkarray = array();
                foreach ($vv as $wks => $wvs) {

                }

                foreach ($vv as $item => $vals) {
                    $index += 1;
                    $xh += 1;
                    /*$unit = '';
                    if (!empty($data[$kk][$item]['unit']) && mb_strwidth($data[$kk][$item]['unit']) == 2) {
                        $unit = $data[$kk][$item]['unit'];
                    } else if (!empty($data[$kk][$item]['unit']) && mb_strwidth($data[$kk][$item]['unit']) > 2) {
                        if (!empty($data[$kk][$item]['second_unit']) && mb_strwidth($data[$kk][$item]['second_unit']) == 2) {
                            $unit = $data[$kk][$item]['second_unit'];
                        }
                    }*/

                    $unit_num = '';
                    $sec_unit_num = '';
                    $unit_name = '';
                    $sec_unit_name = '';
                    //单位
                    if (!empty($data[$kk][$item]['unit']) && mb_strwidth($data[$kk][$item]['unit']) == 2) {
                        $unit_name = $data[$kk][$item]['unit'];
                        $unit_num = $data[$kk][$item]['GdsNum'];
                    } else if (!empty($data[$kk][$item]['unit']) && mb_strwidth($data[$kk][$item]['unit']) == 4) {
                        $unit_name = $data[$kk][$item]['unit'];
                        if ($data[$kk][$item]['unit'] == '千克') {
                            $unit_num = bcdiv($data[$kk][$item]['GdsWeight'], 1000, 3);
                            //$unit_num = bcdiv($vals['waybill_weight'], 1000, 3);
                            //$unit_num = bcsub($unit_num, 0.01, 3);
                        } else {
                            $unit_num = '';
                        }
                    } else if (!empty($data[$kk][$item]['unit']) && mb_strwidth($data[$kk][$item]['unit']) == 6) {
                        $unit_name = $data[$kk][$item]['unit'];
                        if ($data[$kk][$item]['unit'] == '平方米') {
                            $unit_num = bcmul($data[$kk][$item]['unit_area'], $data[$kk][$item]['GdsNum'], 3);
                        } else {
                            $unit_num = '';
                        }
                    }

                    if (!empty($data[$kk][$item]['second_unit']) && mb_strwidth($data[$kk][$item]['second_unit']) == 2) {
                        $sec_unit_name = $data[$kk][$item]['second_unit'];
                        if(strpos($data[$kk][$item]['num_unit'], '副') !== false && strpos($data[$kk][$item]['second_unit'], '个') !== false) {
                            $sec_unit_num = bcmul($data[$kk][$item]['GdsNum'], 2, 0);//第一单位为副的并且第二单位为个的数量需要乘以2
                        } else {
                            $sec_unit_num = $data[$kk][$item]['GdsNum'];
                        }
                    } else if (!empty($data[$kk][$item]['second_unit']) && mb_strwidth($data[$kk][$item]['second_unit']) == 4) {
                        $sec_unit_name = $data[$kk][$item]['second_unit'];
                        if ($data[$kk][$item]['second_unit'] == '千克') {
                            $sec_unit_num = bcdiv($data[$kk][$item]['GdsWeight'], 1000, 3);
                            //$sec_unit_num = bcdiv($vals['waybill_weight'], 1000, 3);
                            //$sec_unit_num = bcsub($sec_unit_num, 0.01, 3);
                        } else {
                            $sec_unit_num = '';
                        }
                    } else if (!empty($data[$kk][$item]['second_unit']) && mb_strwidth($data[$kk][$item]['second_unit']) == 6) {
                        $sec_unit_name = $data[$kk][$item]['second_unit'];
                        if ($data[$kk][$item]['second_unit'] == '平方米') {
                            $sec_unit_num = bcmul($data[$kk][$item]['sec_unit_area'], $data[$kk][$item]['GdsNum'], 3);
                        } else{
                            $sec_unit_num = '';
                        }
                    }

                    if ($data[$kk][$item]['GdsNum'] > 1) {
                        $sale_trn = bcadd($data[$kk][$item]['SaleMny'], $data[$kk][$item]['TranMny'], 2);
                        $danjia = bcdiv($sale_trn, $data[$kk][$item]['GdsNum'], 2);
                        $zongjia = bcmul($danjia, $data[$kk][$item]['GdsNum'], 2);
                        unset($sale_trn);
                    } else {
                        $danjia = bcadd($data[$kk][$item]['SaleMny'], $data[$kk][$item]['TranMny'], 2);
                        $zongjia = bcadd($data[$kk][$item]['SaleMny'], $data[$kk][$item]['TranMny'], 2);
                    }
                    //$weight = bcdiv($data[$kk][$item]['GdsWeight'], 1000, 3);
                    $weight = bcdiv($vals['waybill_weight'], 1000, 3);
                    $net_weight = bcsub($weight, 0.01, 3);
                    $tranmony = $data[$kk][$item]['TranMny'];
                    /*if ($item >= 1) {
                        $tranmony = '';//如果有多个sku那运费从第二个开始放空
                    }*/
                    $PHPExcel->getActiveSheet()
                        ->setCellValue("A" . $index, $batch - 5)
                        //->setCellValue("B" . $index, '')
                        ->setCellValue("C" . $index, $kk)
                        ->setCellValue("D" . $index, $data[$kk][$item]['OdrNo']."\t")
                        ->setCellValue("E" . $index, '')
                        ->setCellValue("F" . $index, '3502160EEV')
                        ->setCellValue("G" . $index, 'NO')
                        ->setCellValue("H" . $index, 'NO')
                        ->setCellValue("I" . $index, $weight)
                        ->setCellValue("J" . $index, $net_weight)
                        ->setCellValue("K" . $index, $data[$kk][$item]['country']['counts'])//抵达国
                        ->setCellValue("L" . $index, $data[$kk][$item]['country']['nums'])//指运代码
                        ->setCellValue("M" . $index, 1)
                        ->setCellValue("N" . $index, $tranmony)
                        ->setCellValue("O" . $index, 0)
                        ->setCellValue("P" . $index, '包')
                        //->setCellValue("Q" . $index, '')
                        ->setCellValue("R" . $index, $xh)
                        ->setCellValue("S" . $index, $data[$kk][$item]['GdsSku'])
                        ->setCellValue("T" . $index, $data[$kk][$item]['name'])
                        ->setCellValue("U" . $index, $data[$kk][$item]['commodity_code'])
                        ->setCellValue("V" . $index, $data[$kk][$item]['name'])
                        ->setCellValue("W" . $index, $data[$kk][$item]['SpecName'])
                        ->setCellValue("X" . $index, '无')
                        ->setCellValue("Y" . $index, $data[$kk][$item]['country']['counts'])
                        ->setCellValue("Z" . $index, $data[$kk][$item]['GdsNum'])
                        ->setCellValue("AA" . $index, $data[$kk][$item]['num_unit'])
                        ->setCellValue("AB" . $index, $unit_num)
                        ->setCellValue("AC" . $index, $unit_name)
                        ->setCellValue("AD" . $index, $sec_unit_num)
                        ->setCellValue("AE" . $index, $sec_unit_name)
                        ->setCellValue("AF" . $index, $danjia)
                        ->setCellValue("AG" . $index, $zongjia)
                        ->setCellValue("AH" . $index, '美元');
                    unset($danjia,$weight,$net_weight);
                    unset($zongjia);
                }
            } else {
                foreach ($vv as $item => $vals) {
                    $batch += 1;
                    $index += 1;
                    /*$unit = '';
                    if (!empty($data[$kk][$item]['unit']) && mb_strwidth($data[$kk][$item]['unit']) == 2) {
                        $unit = $data[$kk][$item]['unit'];
                    } else if (!empty($data[$kk][$item]['unit']) && mb_strwidth($data[$kk][$item]['unit']) > 2) {
                        if (!empty($data[$kk][$item]['second_unit']) && mb_strwidth($data[$kk][$item]['second_unit']) == 2) {
                            $unit = $data[$kk][$item]['second_unit'];
                        }
                    }*/

                    $unit_num = '';
                    $sec_unit_num = '';
                    $unit_name = '';
                    $sec_unit_name = '';
                    if (!empty($data[$kk][$item]['unit']) && mb_strwidth($data[$kk][$item]['unit']) == 2) {
                        $unit_name = $data[$kk][$item]['unit'];
                        $unit_num = $data[$kk][$item]['GdsNum'];
                    } else if (!empty($data[$kk][$item]['unit']) && mb_strwidth($data[$kk][$item]['unit']) == 4) {
                        $unit_name = $data[$kk][$item]['unit'];
                        if ($data[$kk][$item]['unit'] == '千克') {
                            //$unit_num = bcdiv($data[$kk][$item]['GdsWeight'], 1000, 3);
                            $unit_num = bcdiv($vals['waybill_weight'], 1000, 3);
                            $unit_num = bcsub($unit_num, 0.01, 3);
                        } else {
                            $unit_num = '';
                        }
                    } else if (!empty($data[$kk][$item]['unit']) && mb_strwidth($data[$kk][$item]['unit']) == 6) {
                        $unit_name = $data[$kk][$item]['unit'];
                        if ($data[$kk][$item]['unit'] == '平方米') {
                            $unit_num = bcmul($data[$kk][$item]['unit_area'], $data[$kk][$item]['GdsNum'], 3);
                        } else {
                            $unit_num = '';
                        }
                    }

                    if (!empty($data[$kk][$item]['second_unit']) && mb_strwidth($data[$kk][$item]['second_unit']) == 2) {
                        $sec_unit_name = $data[$kk][$item]['second_unit'];
                        if(strpos($data[$kk][$item]['num_unit'], '副') !== false && strpos($data[$kk][$item]['second_unit'], '个') !== false) {
                            $sec_unit_num = bcmul($data[$kk][$item]['GdsNum'], 2, 0);//第一单位为副的并且第二单位为个的数量需要乘以2
                        } else {
                            $sec_unit_num = $data[$kk][$item]['GdsNum'];
                        }
                        //$sec_unit_num = $data[$kk][$item]['GdsNum'];
                    } else if (!empty($data[$kk][$item]['second_unit']) && mb_strwidth($data[$kk][$item]['second_unit']) == 4) {
                        $sec_unit_name = $data[$kk][$item]['second_unit'];
                        if ($data[$kk][$item]['second_unit'] == '千克') {
                            //$sec_unit_num = bcdiv($data[$kk][$item]['GdsWeight'], 1000, 3);
                            $sec_unit_num = bcdiv($vals['waybill_weight'], 1000, 3);
                            $sec_unit_num = bcsub($sec_unit_num, 0.01, 3);
                        } else {
                            $sec_unit_num = '';
                        }
                    } else if (!empty($data[$kk][$item]['second_unit']) && mb_strwidth($data[$kk][$item]['second_unit']) == 6) {
                        $sec_unit_name = $data[$kk][$item]['second_unit'];
                        if ($data[$kk][$item]['second_unit'] == '平方米') {
                            $sec_unit_num = bcmul($data[$kk][$item]['sec_unit_area'], $data[$kk][$item]['GdsNum'], 3);
                        } else{
                            $sec_unit_num = '';
                        }
                    }

                    if ($data[$kk][$item]['GdsNum'] > 1) {
                        $sale_trn = bcadd($data[$kk][$item]['SaleMny'], $data[$kk][$item]['TranMny'], 2);
                        $danjia = bcdiv($sale_trn, $data[$kk][$item]['GdsNum'], 2);
                        $zongjia = bcmul($danjia, $data[$kk][$item]['GdsNum'], 2);
                        unset($sale_trn);
                    } else {
                        $danjia = bcadd($data[$kk][$item]['SaleMny'], $data[$kk][$item]['TranMny'], 2);
                        $zongjia = bcadd($data[$kk][$item]['SaleMny'], $data[$kk][$item]['TranMny'], 2);
                    }
                    //$weight = bcdiv($data[$kk][$item]['GdsWeight'], 1000, 3);
                    $weight = bcdiv($vals['waybill_weight'], 1000, 3);
                    $net_weight = bcsub($weight, 0.01, 3);
                    $PHPExcel->getActiveSheet()
                        ->setCellValue("A" . $index, $batch - 5)
                        //->setCellValue("B" . $index, '')
                        ->setCellValue("C" . $index, $kk)
                        ->setCellValue("D" . $index, $data[$kk][$item]['OdrNo']."\t")
                        ->setCellValue("E" . $index, '')
                        ->setCellValue("F" . $index, '3502160EEV')
                        ->setCellValue("G" . $index, 'NO')
                        ->setCellValue("H" . $index, 'NO')
                        ->setCellValue("I" . $index, $weight)
                        ->setCellValue("J" . $index, $net_weight)
                        ->setCellValue("K" . $index, $data[$kk][$item]['country']['counts'])
                        ->setCellValue("L" . $index, $data[$kk][$item]['country']['nums'])
                        ->setCellValue("M" . $index, 1)
                        ->setCellValue("N" . $index, $data[$kk][$item]['TranMny'])
                        ->setCellValue("O" . $index, 0)
                        ->setCellValue("P" . $index, '包')
                        //->setCellValue("Q" . $index, '')
                        ->setCellValue("R" . $index, 1)
                        ->setCellValue("S" . $index, $data[$kk][$item]['GdsSku'])
                        ->setCellValue("T" . $index, $data[$kk][$item]['name'])
                        ->setCellValue("U" . $index, $data[$kk][$item]['commodity_code'])
                        ->setCellValue("V" . $index, $data[$kk][$item]['name'])
                        ->setCellValue("W" . $index, $data[$kk][$item]['SpecName'])
                        ->setCellValue("X" . $index, '无')
                        ->setCellValue("Y" . $index, $data[$kk][$item]['country']['counts'])
                        ->setCellValue("Z" . $index, $data[$kk][$item]['GdsNum'])
                        ->setCellValue("AA" . $index, $data[$kk][$item]['num_unit'])
                        ->setCellValue("AB" . $index, $unit_num)
                        ->setCellValue("AC" . $index, $unit_name)
                        ->setCellValue("AD" . $index, $sec_unit_num)
                        ->setCellValue("AE" . $index, $sec_unit_name)
                        ->setCellValue("AF" . $index, $danjia)
                        ->setCellValue("AG" . $index, $zongjia)
                        ->setCellValue("AH" . $index, '美元');
                    unset($danjia,$weight,$net_weight);
                    unset($zongjia);
                }
            }
        }

        //$PHPExcel->getActiveSheet()->setTitle('物料');      //设置sheet的名称
        $PHPExcel->setActiveSheetIndex(0);                   //设置sheet的起始位置
        //$objWriter = \PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel5');   //Excel2003通过PHPExcel_IOFactory的写函数将上面数据写出来
        $PHPWriter = \PHPExcel_IOFactory::createWriter($PHPExcel,"Excel2007"); //Excel2007
        //header('Content-Disposition: attachment;filename="用户信息.xlsx"');
        //header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        //header("Content-Disposition:inline;filename=order_in.xlsx");
        $savefiles = ROOT_SAVE_FILES . 'invt_in.xls';
        $PHPWriter->save($savefiles); //表示在$path路径下面生成demo.xlsx文件
    }

    //order_in
    public function set_order_in($data) {
        //$material=new Material();
        //$data = $yd_arr;
        $PHPExcel = new \PHPExcel();
        $PHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A1", "业务批次号：")
            ->setCellValue("C1", "电商平台/海外仓名称：")
            ->setCellValue("D1", "厦门飞飞鱼供应链管理有限公司")
            ->setCellValue("E1", "订单类型：")
            ->setCellValue("A2","序号")
            ->setCellValue("B2","电商订单/海外仓订仓单编号")
            ->setCellValue("C2","电商企业名称")
            ->setCellValue("D2","商品金额")//金额合并
            ->setCellValue("E2","收款金额")//金额合并
            ->setCellValue("F2","收款时间")
            ->setCellValue("G2","支付企业代码")
            ->setCellValue("H2","支付企业名称")
            ->setCellValue("I2","支付交易编号")
            ->setCellValue("J2","运杂费")
            ->setCellValue("K2","订单币制")
            ->setCellValue("L2","订单备注")
            ->setCellValue("M2","商品序号")
            ->setCellValue("N2","企业商品货号")//SKU
            ->setCellValue("O2","企业商品名称")
            ->setCellValue("P2","企业商品描述")
            ->setCellValue("Q2","条形码")
            ->setCellValue("R2","币制")
            ->setCellValue("S2","计量单位")
            ->setCellValue("T2","数量")
            ->setCellValue("U2","单价")//金额分开
            ->setCellValue("V2","总价")//金额分开
            ->setCellValue("W2","商品备注");
        
        $PHPExcel->getActiveSheet()->getStyle('A')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('B')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('C')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('D')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('E')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('F')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('G')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('H')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('I')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('J')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('K')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('L')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('M')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('N')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('O')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('P')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('Q')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('R')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('S')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('T')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('U')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('V')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('W')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $index = 2;
        $batch = $index;
        foreach ($data as $kk => $vv) {
            if (count($vv) > 1) {
                $xh = 0;
                $batch += 1;
                $all_pro_money = 0;
                foreach ($vv as $kdj => $vdj) {
                    if ($data[$kk][$kdj]['GdsNum'] > 1) {
                        $sale_trn = bcadd($data[$kk][$kdj]['SaleMny'], $data[$kk][$kdj]['TranMny'], 2);
                        $danjia = bcdiv($sale_trn, $data[$kk][$kdj]['GdsNum'], 2);
                        $zongjia = bcmul($danjia, $data[$kk][$kdj]['GdsNum'], 2);
                        unset($sale_trn);
                    } else {
                        $danjia = bcadd($data[$kk][$kdj]['SaleMny'], $data[$kk][$kdj]['TranMny'], 2);
                        $zongjia = bcadd($data[$kk][$kdj]['SaleMny'], $data[$kk][$kdj]['TranMny'], 2);
                    }
                    $all_pro_money += $zongjia;
                    //$tranmony = $data[$kk][$kdj]['TranMny'];
                }
                unset($zongjia,$danjia);
                foreach ($vv as $item => $vals) {
                    $index += 1;
                    $xh += 1;//商品序号
                    /*$unit = '';
                    if (!empty($data[$kk][$item]['unit']) && mb_strwidth($data[$kk][$item]['unit']) == 2) {
                        $unit = $data[$kk][$item]['unit'];
                    } else if (!empty($data[$kk][$item]['unit']) && mb_strwidth($data[$kk][$item]['unit']) > 2) {
                        if (!empty($data[$kk][$item]['second_unit']) && mb_strwidth($data[$kk][$item]['second_unit']) == 2) {
                            $unit = $data[$kk][$item]['second_unit'];
                        }
                    }*/
                    if ($data[$kk][$item]['GdsNum'] > 1) {
                        $sale_trn = bcadd($data[$kk][$item]['SaleMny'], $data[$kk][$item]['TranMny'], 2);
                        $danjia = bcdiv($sale_trn, $data[$kk][$item]['GdsNum'], 2);
                        $zongjia = bcmul($danjia, $data[$kk][$item]['GdsNum'], 2);
                        unset($sale_trn);
                    } else {
                        $danjia = bcadd($data[$kk][$item]['SaleMny'], $data[$kk][$item]['TranMny'], 2);
                        $zongjia = bcadd($data[$kk][$item]['SaleMny'], $data[$kk][$item]['TranMny'], 2);
                    }
                    $tranmony = $data[$kk][$item]['TranMny'];
                    /*if ($item >= 1) {
                        $tranmony = '';//如果有多个sku那运费从第二个开始放空
                    }*/
                    $PHPExcel->getActiveSheet()
                        ->setCellValue("A" . $index, $batch - 2)
                        ->setCellValue("B" . $index, $data[$kk][$item]['OdrNo']."\t")
                        ->setCellValue("C" . $index, '厦门飞飞鱼供应链管理有限公司')
                        ->setCellValue("D" . $index, $all_pro_money)//该订单所有产品的总价
                        ->setCellValue("E" . $index, $all_pro_money)//该订单所有产品的总价
                        //->setCellValue("F" . $index, '')
                        //->setCellValue("G" . $index, '')
                        //->setCellValue("H" . $index, '')
                        //->setCellValue("I" . $index, '')
                        ->setCellValue("J" . $index, $tranmony)
                        ->setCellValue("K" . $index, '美元')
                        //->setCellValue("L" . $index, '')
                        ->setCellValue("M" . $index, $xh)
                        ->setCellValue("N" . $index, $data[$kk][$item]['GdsSku'])
                        ->setCellValue("O" . $index, $data[$kk][$item]['name'])
                        //->setCellValue("P" . $index, '')
                        //->setCellValue("Q" . $index, '')
                        ->setCellValue("R" . $index, '美元')
                        ->setCellValue("S" . $index, $data[$kk][$item]['num_unit'])
                        ->setCellValue("T" . $index, $data[$kk][$item]['GdsNum'])
                        ->setCellValue("U" . $index, $danjia)
                        ->setCellValue("V" . $index, $zongjia);
                        //->setCellValue("W" . $index, '');
                    unset($danjia);
                    unset($zongjia);
                }
            } else {
                foreach ($vv as $item => $vals) {
                    $index += 1;
                    $batch += 1;
                    /*$unit = '';
                    if (!empty($data[$kk][$item]['unit']) && mb_strwidth($data[$kk][$item]['unit']) == 2) {
                        $unit = $data[$kk][$item]['unit'];
                    } else if (!empty($data[$kk][$item]['unit']) && mb_strwidth($data[$kk][$item]['unit']) > 2) {
                        if (!empty($data[$kk][$item]['second_unit']) && mb_strwidth($data[$kk][$item]['second_unit']) == 2) {
                            $unit = $data[$kk][$item]['second_unit'];
                        }
                    }*/
                    if ($data[$kk][$item]['GdsNum'] > 1) {
                        $sale_trn = bcadd($data[$kk][$item]['SaleMny'], $data[$kk][$item]['TranMny'], 2);
                        $danjia = bcdiv($sale_trn, $data[$kk][$item]['GdsNum'], 2);
                        $zongjia = bcmul($danjia, $data[$kk][$item]['GdsNum'], 2);
                        unset($sale_trn);
                    } else {
                        $danjia = bcadd($data[$kk][$item]['SaleMny'], $data[$kk][$item]['TranMny'], 2);
                        $zongjia = bcadd($data[$kk][$item]['SaleMny'], $data[$kk][$item]['TranMny'], 2);
                    }
                    $PHPExcel->getActiveSheet()
                        ->setCellValue("A" . $index, $batch - 2)
                        ->setCellValue("B" . $index, $data[$kk][$item]['OdrNo'] . "\t")//
                        ->setCellValue("C" . $index, '厦门飞飞鱼供应链管理有限公司')
                        ->setCellValue("D" . $index, $zongjia)
                        ->setCellValue("E" . $index, $zongjia)
                        //->setCellValue("F" . $index, '')
                        //->setCellValue("G" . $index, '')
                        //->setCellValue("H" . $index, '')
                        //->setCellValue("I" . $index, '')
                        ->setCellValue("J" . $index, $data[$kk][$item]['TranMny'])
                        ->setCellValue("K" . $index, '美元')
                        //->setCellValue("L" . $index, '')
                        ->setCellValue("M" . $index, 1)
                        ->setCellValue("N" . $index, $data[$kk][$item]['GdsSku'])
                        ->setCellValue("O" . $index, $data[$kk][$item]['name'])
                        //->setCellValue("P" . $index, '')
                        //->setCellValue("Q" . $index, '')
                        ->setCellValue("R" . $index, '美元')
                        ->setCellValue("S" . $index, $data[$kk][$item]['num_unit'])
                        ->setCellValue("T" . $index, $data[$kk][$item]['GdsNum'])
                        ->setCellValue("U" . $index, $danjia)
                        ->setCellValue("V" . $index, $zongjia);
                        //->setCellValue("W" . $index, '');
                    unset($danjia);
                    unset($zongjia);
                }
            }
        }

        //$PHPExcel->getActiveSheet()->setTitle('物料');      //设置sheet的名称
        $PHPExcel->setActiveSheetIndex(0);                   //设置sheet的起始位置
        //$objWriter = \PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel5');   //Excel2003通过PHPExcel_IOFactory的写函数将上面数据写出来
        $PHPWriter = \PHPExcel_IOFactory::createWriter($PHPExcel,"Excel2007"); //Excel2007
        //header('Content-Disposition: attachment;filename="用户信息.xlsx"');
        //header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        //header("Content-Disposition:inline;filename=order_in.xlsx");
        $savefiles = ROOT_SAVE_FILES . 'order_in.xls';
        $PHPWriter->save($savefiles); //表示在$path路径下面生成demo.xlsx文件
    }

    //上传文件uploadexcel
    public function uploadexcel() {
        parent::excelStart();

        $fileName = isset($_FILES["file"]) ? $_FILES["file"]["name"] : "";
        $file = isset($_FILES["file"]) ? $_FILES["file"]["tmp_name"] : "";
        $ext = \file::get_ext($fileName);
        $isCustoms = strstr($fileName, '海关编码');
        if ($isCustoms == false) {
            return '请导入正确的海关编码文件';
        }
        if ($ext == "xls") {
            $reader = \PHPExcel_IOFactory::createReader('Excel5');
        } else {
            $reader = \PHPExcel_IOFactory::createReader('Excel2007');
        }
        unset($fileName);
        unset($isCustoms);
        $excel9610Model = new Excel9610();
        $PHPExcel = $reader->load($file);
        $sheet = $PHPExcel->getSheet(0); // 读取第一個工作表
        $highestRow = $sheet->getHighestRow(); // 取得总行数
        $highestColumm = $sheet->getHighestColumn(1); // 取得总列数
        $highestColumm = \PHPExcel_Cell::columnIndexFromString($highestColumm); //字母列转换为数字列 如:AA变为27
        $filedArray = array();
        //价格
        $field = array(
            '商品货号SKU' => 'sku',
            '商品名称' => 'name',
            '计量单位' => 'num_unit',
            'HS' => 'commodity_code',
            '要素' => 'element',
            '规格型号' => 'specification_model'
        );
        for ($col = 0; $col <= $highestColumm; $col++) {
            $value = $sheet->getCellByColumnAndRow($col, 1)->getValue();
            $value = trim($value);
            if(isset($field[$value]))$filedArray[$col]=$field[$value];
        }

        $newdata=array();

        for ($row = 2; $row <= $highestRow; $row++) { //列数是以第0列开始
            $data=array();

            foreach ($filedArray as $key=>$value){
                $data[$value]=trim($sheet->getCellByColumnAndRow($key, $row)->getValue());
            }

            $data['create_time']=date('Y-m-d h:i:s');
            $newdata[]=$data;
        }
        unset($PHPExcel);
        $saves = array();
        /*$updates = array();*/
        foreach ($newdata as $k => $v) {
            if($v['sku'] == '' || $v['commodity_code'] == '') {
                continue;
            }
            $have_data = $excel9610Model->where(['sku' => $v['sku']])->find();
            if (!empty($have_data)) {
                $have_data->commodity_code = $v['commodity_code'];
                $have_data->name = $v['name'];
                $have_data->element = $v['element'];
                $have_data->num_unit = $v['num_unit'];
                $have_data->specification_model = $v['specification_model'];
                $have_data->isUpdate()->save();
                /*$updates[$k] = $v;
                $updates[$k]['id'] = $have_data['id'];*/
            } else {
                $saves[] = $v;
            }
        }
        if (count($saves) > 0) {
            $boo = $excel9610Model->insertAll($saves, true);
        } else {
            $boo = true;
        }
        unset($saves);
        unset($sheet);
        if($boo!==false){
            return true;
        }else{
            return "导入失败，请重新导入数据";
        }
    }
    //上传文件uploadTaxexcel
    public function uploadtaxexcel() {
        set_time_limit(0);
        ini_set("memory_limit", "2048M");
        parent::excelStart();

        $fileName = isset($_FILES["file"]) ? $_FILES["file"]["name"] : "";
        $file = isset($_FILES["file"]) ? $_FILES["file"]["tmp_name"] : "";
        $ext = \file::get_ext($fileName);
        $newFileName = '9610'.time().rand(1000000, 9999999);
        //文件存储路径
        $pathName = ROOT_EXCEL_FILE . $newFileName . '.' . $ext;
        $res = move_uploaded_file($file,$pathName);
        if(!$res){
            return '上传失败';
        }
        /*$isCustoms = strstr($fileName, '税则');
        if ($isCustoms == false) {
            return '请导入正确的税则文件';
        }*/
        if ($ext == "xls") {
            $reader = \PHPExcel_IOFactory::createReader('Excel5');
        } else {
            $reader = \PHPExcel_IOFactory::createReader('Excel2007');
        }
        //$re = new \PHPExcelReadFilter();
        $excel9610Model = new Excel9610();
        $have_data = $excel9610Model->select();
        if (count($have_data) <= 0) {
            return '请先导入海关编码文件';
        }
        foreach ($have_data as $k => $v) {
            $code_arr[$k] = $v['commodity_code'];
        }
        //$code_arr = array_column($have_data, 'commodity_code');
        $code_arr = array_unique($code_arr);
        unset($fileName);
        unset($isCustoms);
        //cache('admin_orderexcel_taxexcel', null);
        $newdata = array();
        if (empty($newdata)) {
            try {
                $PHPExcel = $reader->load($pathName);
            } catch (Expection $e) {
                print $e->getMessage();
                exit();
            }
            //$PHPExcel = $reader->load($file);
            $sheet = $PHPExcel->getSheet(0); // 读取第一個工作表
            $highestRow = $sheet->getHighestRow(); // 取得总行数
            $highestColumm = $sheet->getHighestColumn(1); // 取得总列数
            $highestColumm = \PHPExcel_Cell::columnIndexFromString($highestColumm); //字母列转换为数字列 如:AA变为27
            $filedArray = array();
            //unset($sheet);
            //价格
            //旧的表头
            /*$field = array(
                '商品编码' => 'commodity_code',
                //'商品名称' => 'name',
                //'申报要素' => '',
                '法定计量单位' => 'unit',
                '法定第二计量单位' => 'second_unit'
            );*/
            $field = array(
                '海关编码' => 'commodity_code',
                //'商品名称' => 'name',
                //'申报要素' => '',
                '法一单位' => 'unit',
                '法二单位' => 'second_unit'
            );
            for ($col = 0; $col <= $highestColumm; $col++) {
                $value = $sheet->getCellByColumnAndRow($col, 1)->getValue();
                $value = trim($value);
                if (isset($field[$value])) $filedArray[$col] = $field[$value];
            }

            $newdata = array();

            for ($row = 2; $row <= $highestRow; $row++) { //列数是以第0列开始
                $data = array();

                foreach ($filedArray as $key => $value) {
                    $data[$value] = trim($sheet->getCellByColumnAndRow($key, $row)->getValue());
                }
                //$data['create_time']=date('Y-m-d h:i:s');
                if ($data['commodity_code'] != '') $newdata[] = $data;
            }
            unset($sheet);
            $cacorderex = cache('admin_orderexcel_taxexcel');
            if (!empty($cacorderex)) {
                $newdata = array_merge($cacorderex, $newdata);
            }
            cache('admin_orderexcel_taxexcel', $newdata);//方法一缓存,二增加表
        }

        /*$updates = array();*/
        $boo = false;
        foreach ($newdata as $k => $v) {
            if($v['commodity_code'] == '') {
                continue;
            }
            $is_in = in_array($v['commodity_code'], $code_arr);
            if ($is_in) {
                $save_data = array(
                    'unit' => $v['unit'],
                    'second_unit' => $v['second_unit']
                );
                $boo = $excel9610Model->where('commodity_code', $v['commodity_code'])->update($save_data);
                //$boo = true;
            }
        }

        if($boo!==false){
            return true;
        }else{
            return "导入失败，请重新导入数据";
        }
    }


    //上传文件uploadexcel
    public function uploadareaexcel() {
        parent::excelStart();

        $fileName = isset($_FILES["file"]) ? $_FILES["file"]["name"] : "";
        $file = isset($_FILES["file"]) ? $_FILES["file"]["tmp_name"] : "";
        $ext = \file::get_ext($fileName);
        $isCustoms = strstr($fileName, '印花面积');
        if ($isCustoms == false) {
            return '请导入正确的印花面积文件';
        }
        if ($ext == "xls") {
            $reader = \PHPExcel_IOFactory::createReader('Excel5');
        } else {
            $reader = \PHPExcel_IOFactory::createReader('Excel2007');
        }
        unset($fileName);
        unset($isCustoms);
        $excel9610Model = new Excel9610();
        $PHPExcel = $reader->load($file);
        $sheet = $PHPExcel->getSheet(0); // 读取第一個工作表
        $highestRow = $sheet->getHighestRow(); // 取得总行数
        $highestColumm = $sheet->getHighestColumn(1); // 取得总列数
        $highestColumm = \PHPExcel_Cell::columnIndexFromString($highestColumm); //字母列转换为数字列 如:AA变为27
        $filedArray = array();
        //价格
        $field = array(
            'SKU' => 'sku',
            /*'商品名称' => 'name',
            'HS' => 'commodity_code',
            '要素' => 'element',*/
            '面积' => 'area'
        );
        for ($col = 0; $col <= $highestColumm; $col++) {
            $value = $sheet->getCellByColumnAndRow($col, 1)->getValue();
            $value = trim($value);
            if(isset($field[$value]))$filedArray[$col]=$field[$value];
        }

        $newdata=array();

        for ($row = 2; $row <= $highestRow; $row++) { //列数是以第0列开始
            $data=array();

            foreach ($filedArray as $key=>$value){
                $data[$value]=trim($sheet->getCellByColumnAndRow($key, $row)->getValue());
            }
            $newdata[]=$data;
        }
        unset($PHPExcel);

        /*$updates = array();*/
        foreach ($newdata as $k => $v) {
            if($v['sku'] == '') {
                continue;
            }
            $have_data = $excel9610Model->where(['sku' => $v['sku']])->find();
            if (!empty($have_data)) {

                if ($have_data['unit'] == '平方米') {
                    $have_data->unit_area = round($v['area'], 2);
                }
                if ($have_data['second_unit'] == '平方米') {
                    $have_data->sec_unit_area = round($v['area'], 2);
                }
                $have_data->isUpdate()->save();
                /*$updates[$k] = $v;
                $updates[$k]['id'] = $have_data['id'];*/
            }
        }
        $boo = true;

        unset($sheet);
        if($boo!==false){
            return true;
        }else{
            return "导入失败，请重新导入数据";
        }
    }

    public function editdata($id = '') {
        $mod = new Excel9610();
        if ($this->request->isPost()) {
            $datas = input('post.');
            if (empty($datas['id'])) return json(['code' => 1001, 'msg' => '编辑出错，请重试']);
            $update = array(
                'element' => $datas['element'],
                'name' => $datas['name'],
                'unit' => $datas['unit'],
                'second_unit' => $datas['second_unit'],
                'specification_model' => $datas['specification_model'],
                'unit_area' => $datas['unit_area'],
                'sec_unit_area' => $datas['sec_unit_area'],
                'num_unit' => $datas['num_unit']
            );
            $res = $mod->updatedata($update, $datas['id']);
            return json($res);
        }
        //if ($id == '')
        $data = $mod->where('id', $id)->find();
        $this->assign('data', $data);
        $this->assign('id', $id);
        $this->assign('currentMenu',array('menu'=>'menu11','nav'=>'nav0'));
        return $this->fetch();
    }

    public function adddata() {
        $mod = new Excel9610();
        if ($this->request->isPost()) {
            $datas = input('post.');
            //if (empty($datas['id'])) return json(['code' => 1001, 'msg' => '编辑出错，请重试']);
            $update = array(
                'sku' => $datas['sku'],
                'commodity_code' => $datas['commodity_code'],
                'element' => $datas['element'],
                'name' => $datas['name'],
                'unit' => $datas['unit'],
                'second_unit' => $datas['second_unit'],
                'specification_model' => $datas['specification_model'],
                'unit_area' => $datas['unit_area'],
                'sec_unit_area' => $datas['sec_unit_area'],
                'num_unit' => $datas['num_unit']
            );
            $res = $mod->savedata($update);
            return json($res);
        }
        //if ($id == '')
        $this->assign('currentMenu',array('menu'=>'menu11','nav'=>'nav0'));
        return $this->fetch();
    }

    public function delete() {
        if (!$this->request->isPost()) {
            return json(['code' => 1001, 'msg' => '此为post接口']);
        }
        $ids = input('post.id/a');
        if (count($ids) <= 0) {
            return json(['code' => 1001, 'msg' => '参数为空']);
        }
        $mod = new Excel9610();
        $res = $mod->deletedata($ids);
        return json($res);
    }



    public function setget($id) {
        //$material=new Material();
        //$data = $yd_arr;
        parent::excelStart();
        $PHPExcel = new \PHPExcel();
        $PHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A1", "时间")
            ->setCellValue("B1", "数量");

        $PHPExcel->getActiveSheet()->getStyle('A')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $PHPExcel->getActiveSheet()->getStyle('B')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $index = 2;
        $ordrmo = new Order();
        $list = $ordrmo->field('GetTimer,GdsNum')->where('product_id', $id)->select();
        //
        $res = array();
        foreach ($list as $r => $rv) {
            $res[$r]['GetTimer'] = date('Y-m-d', strtotime($rv['GetTimer']));
            $res[$r]['GdsNum'] = $rv['GdsNum'];
        }
        //print_r($res);
        $data = array();
        foreach ($res as $k => $v) {

            if (isset($data[$v['GetTimer']])) {
                $data[$v['GetTimer']] = bcadd($v['GdsNum'], $data[$v['GetTimer']], 1);
                continue;
            }

            $data[$v['GetTimer']] = $v['GdsNum'];
        }
        //print_r($data);exit;
        foreach ($data as $i => $y) {
            $index += 1;
            $PHPExcel->getActiveSheet()
                ->setCellValue("A" . $index, $i)
                ->setCellValue("B" . $index, $y);
        }

        //$PHPExcel->getActiveSheet()->setTitle('物料');      //设置sheet的名称
        $PHPExcel->setActiveSheetIndex(0);                   //设置sheet的起始位置
        //$objWriter = \PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel5');   //Excel2003通过PHPExcel_IOFactory的写函数将上面数据写出来
        $PHPWriter = \PHPExcel_IOFactory::createWriter($PHPExcel,"Excel2007"); //Excel2007
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition:inline;filename= " . $id . ".xls");

        //$savefiles = ROOT_SAVE_FILES . 'order_in.xls';
        $PHPWriter->save('php://output'); //表示在$path路径下面生成demo.xlsx文件
        exit;
    }

}

