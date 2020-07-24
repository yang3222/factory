<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
define("ADMIN_STYLE_URL",'/static/admin/');
define("INDEX_STYLE_URL",'/static/index/');
define("USER_STYLE_URL",'/static/user/');
define("PRODUCT_IMG",'/upload/img/');
define("LAYER_JS_URL",'/static/layer/');//前端弹窗路径
define("ROOT_PRODUCT_IMG",ROOT_PATH.'public/upload/img/');
define("ROOT_BARCODE_IMG",ROOT_PATH.'public/upload/barcode/');//条形码二维码文件夹
define("ROOT_QRCODE_FNSKU",ROOT_PATH.'public/upload/fnskuqrcode/');//fnsku qrcode文件夹
define("ROOT_URL_QRCODE_FNSKU",'/upload/fnskuqrcode/');//fnsku qrcode相对路径文件夹

define("ROOT_QRCODE_INWH",ROOT_PATH.'public/upload/inwhqrcode/');//入库二维码 qrcode文件夹

define("ROOT_SHELVESCODE_IMG",ROOT_PATH.'public/upload/shelves/');//货架二维码图片文件夹
define("ROOT_NEW_SHELVESCODE_IMG",'/upload/shelves/');//相对货架二维码图片文件夹

define("ROOT_FBAORDERLABEL_IMG",ROOT_PATH.'public/upload/fbaorderlabel/');
define("ROOT_URL_FBAORDERLABEL_IMG",'/upload/fbaorderlabel/');

define("ROOT_EP_SHELVESCODE_IMG",ROOT_PATH.'public/upload/epshelves/');//货架二维码图片文件夹
define("ROOT_EP_NEW_SHELVESCODE_IMG",'/upload/epshelves/');//相对货架二维码图片文件夹

define("ROOT_NEWORDER_IMG",'/upload/order/');//订单二维码相对路径文件夹
define("ROOT_PATHNEWORDER_IMG",ROOT_PATH.'public/upload/order/');//订单二维码绝对路径图片文件夹

define("ROOT_EXCEL_FILE",ROOT_PATH.'public/upload/excel/');
define("ROOT_SAVE_FILES",ROOT_PATH.'public/upload/savefiles/');
define("ROOT_SAVE_FBA_BOX_LABEL",ROOT_PATH.'public/upload/fbaboxlabel/');
define("ROOT_FBA_MUSIC_SRC",'/upload/music/');
define("WEB",'http://inkdiy.cn');
defined('EXTEND_PATH') or define('EXTEND_PATH', ROOT_PATH . 'extend'.DS);

//获取订单的生产工厂
function GetOrderFactory($order_id){
    $orderfactory=new app\admin\model\Orderfactory();
    $data=$orderfactory->where('order_id','=',$order_id)->select();
    return $data;
}
//获取分类的产品
function GetMenuProduct($products){
    $product=new app\user\model\Product();
    $data=$product->where('id','in',$products)->order('id desc')->select();
    return $data;
}
//获取产品信息
function GetMenu($user_id,$product_id){
    $data=\think\Db::view('product a','name productname,Catalog')
                ->view('factory_menu b','name','FIND_IN_SET(a.id, b.product)')
                ->where(['user_id'=>$user_id,'product_id'=>$product_id])->find();
    return $data;
    /*
    $product=\app\user\model\Product::get(['product_id'=>$product_id]);
    $menu=app\user\model\Menu::where('user_id',$user_id)->where('FIND_IN_SET('.$product->id.', product)')->find();
    return array('Catalog'=>$product->Catalog,'name'=>$menu->name);*/
}
//用于air里面的栏目
function GetMenuProductIds($products){
    $product=new app\user\model\Product();
    $data=$product->where('id','in',$products)->order('id desc')->column('product_id');
    return implode(',',$data);
}
//获取仓库子表格
function GetWarehouseChild($parents_id){
    $warehouseTableModel = new app\admin\model\Warehousetable();
    $data=$warehouseTableModel->where('parents_id','=',$parents_id)->select();
    return $data;
}
//获取仓库子表格
function GetTargets($sql){
    $data = \think\Db::query($sql);
    return $data;
}
//获取成品仓库子表格
function GetEpWarehouseChild($parents_id){
    $warehouseTableModel = new app\admin\model\EpWarehousetable();
    $data=$warehouseTableModel->where('parents_id','=',$parents_id)->select();
    return $data;
}

//获取sku对应的库位和数量数据
function getWtdata($unique_id)
{
    $sql = "select count,id,wt_name from ink_ep_warehouse_materialdetail where unique_id ='".$unique_id."' and delete_time is null";
//    var_dump($sql);die;
    $data = \think\Db::query($sql);

    return $data;
}


function getWhmddelstatus($sql)
{
    $data = \think\Db::query($sql);
    return $data;
}


//post方法获取数据
if (!function_exists('send_post')) {
    function send_post($url, $post_data)
    {
        $postdata = http_build_query($post_data);
        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-type:application/x-www-form-urlencoded',
                'content' => $postdata,
                'timeout' => 15 * 60 // 超时时间（单位:s）
            )
        );
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        return $result;
    }
}
function create_barcode_bcg128($text, $size = 4, $height = 40) {
    $height = $height / $size;
    require_once(EXTEND_PATH."barcodegen.1d-php5.v5.0.1/class/BCGColor.php");
    require_once(EXTEND_PATH."barcodegen.1d-php5.v5.0.1/class/BCGDrawing.php");
    require_once(EXTEND_PATH."barcodegen.1d-php5.v5.0.1/class/BCGcode128.barcode.php");//BCGcode128
    require_once(EXTEND_PATH."barcodegen.1d-php5.v5.0.1/class/BCGFontFile.php");
    $font = new \BCGFontFile(EXTEND_PATH . 'barcodegen.1d-php5.v5.0.1/class/font/Arial.ttf', 18);
    $color_black = new \BCGColor(0, 0, 0);
    $color_white = new \BCGColor(255, 255, 255);
    $drawException = null;

    try {
        $code = new \BCGcode128(); //实例化对应的编码格式$code = new $codebar("B");
        $code->setScale(2); // Resolution，解析度
        $code->setThickness(30); // Thickness，厚度
        $code->setForegroundColor($color_black); // Color of bars
        $code->setBackgroundColor($color_white); // Color of spaces
        $code->setFont($font); // Font (or 0)
        $code->setStart(null);
        $code->setTilde(true);
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

/**
 * 将返回的数据集转换成树
 * @param array $list 数据集
 * @param string $pk 主键
 * @param string $pid 父节点名称
 * @param string $child 子节点名称
 * @param integer $root 根节点ID
 * @return array          转换后的树
 */
if (!function_exists('list_to_tree')) {
    function list_to_tree($list, $pk = 'id', $pid = 'pid', $child = 'child', $root = 0)
    {
        // 创建Tree
        $tree = array();
        if (is_array($list)) {
            // 创建基于主键的数组引用
            $refer = array();
            foreach ($list as $key => $data) {
                $refer[$data[$pk]] =& $list[$key];
            }
            foreach ($list as $key => $data) {
                // 判断是否存在parent
                $parentId = $data[$pid];
                if ($root == $parentId) {
                    $tree[] =& $list[$key];
                } else {
                    if (isset($refer[$parentId])) {
                        $parent =& $refer[$parentId];
                        $parent[$child][] =& $list[$key];
                    }
                }
            }
        }
        return $tree;
    }
}

if (!function_exists('menu_to_tree')) {
    function menu_to_tree($list, $pk = 'id', $pid = 'pid', $child = 'child', $root = 0)
    {
        // 创建Tree
        $tree = array();
        $menu = array();
        if (is_array($list)) {
            foreach ($list as $key => $val) {
                $menu[] = array(
                    $pk => $key,
                    'val' => $val
                );/*
                $tree[$key][$pid] = $root;
                $tree[$key]['text'] = $val['title'];*/

            }
            foreach ($menu as $k => $v) {
                $tree[$k][$pk] = $v[$pk];//$v[$pk];
                $tree[$k][$pid] = $root;
                $tree[$k]['text'] = $v['val']['title'];
                if (!isset($v['val']['nav'])) {
                    $tree[$k][$child] = array();
                    continue;
                }
                foreach ($v['val']['nav'] as $ks => $vs) {
                    $tree[$k][$child][] = array(
                        $pk => $v[$pk] . '-' . $ks,
                        $pid => $v[$pk],
                        'text' => $vs['title']
                    );
                    /*$tree[$k][$child][$ks][$pk] = $ks;
                    $tree[$k][$child][$ks][$pid] = $v[$pk];
                    $tree[$k][$child][$ks]['text'] = $vs['title'];*/
                }

            }
        }
        return $tree;
    }
}
//字符截取
function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=false){
    if(function_exists("mb_substr")){
        if($suffix)
            return mb_substr($str, $start, $length, $charset)."...";
        else
            return mb_substr($str, $start, $length, $charset);
    }elseif(function_exists('iconv_substr')) {
        if($suffix)
            return iconv_substr($str,$start,$length,$charset)."...";
        else
            return iconv_substr($str,$start,$length,$charset);
    }
    $re['utf-8'] = "/[x01-x7f]|[xc2-xdf][x80-xbf]|[xe0-xef][x80-xbf]{2}|[xf0-xff][x80-xbf]{3}/";
    $re['gb2312'] = "/[x01-x7f]|[xb0-xf7][xa0-xfe]/";
    $re['gbk'] = "/[x01-x7f]|[x81-xfe][x40-xfe]/";
    $re['big5'] = "/[x01-x7f]|[x81-xfe]([x40-x7e]|xa1-xfe])/";
    preg_match_all($re[$charset], $str, $match);
    $slice = join("",array_slice($match[0], $start, $length));
    if($suffix) return $slice."…";
    return $slice;
}
/*phpQrCode($text, $outfile = false, $level = QR_ECLEVEL_L, $size = 3, $margin = 4, $saveandprint=false)
它们的含义分别是：
$text,                                  // 生成的二维码 内容
$outfile = false,                 // 生成的二维码 文件名，false为 不保存
$level = QR_ECLEVEL_L,  //级别,也是容错率。下面会有介绍
$size = 3,                           //大小
$margin = 4,                     //外边距
$saveandprint=false       //是否 保存和打印。true为保存并打印
$pc=是否拼接：1是0否
$ab= 简称
*/
if (!function_exists('phpQrCode')) {
    function phpQrCode($text, $size = 6, $margin = 2, $pc = 0, $ab = '') {
        if ($pc == 0) {
            $QR = ROOT_NEWORDER_IMG.$text.'png';
            require_once(EXTEND_PATH . "phpqrcode/qrlib.php");
            header('Content-type:image/png');
            header("Content-Disposition:inline;filename=" . $text . ".png");
            QRcode::png($text, false, QR_ECLEVEL_L, $size, $margin, false);
            exit;
        } else if ($pc == 1) {
            require_once(EXTEND_PATH . "phpqrcode/qrlib.php");
            header('Content-Type: image/png');
            header("Content-Disposition:inline;filename=" . $text . ".png");
            ob_clean();
            \QRcode::png($text, ROOT_BARCODE_IMG.$text.'prostatus.png', QR_ECLEVEL_L, $size, $margin, true);
//            ImagePng(ROOT_BARCODE_IMG.$text.'prostatus.png');
            $imgs[0] = ROOT_BARCODE_IMG.$text.'prostatus.png';
            //$imgs[1] = ROOT_BARCODE_IMG.$text.'down.png';
            //$target  = 'emp.jpg'; //背景图片
            $imageInfo = getimagesize($imgs[0]);
            $target_img = ImageCreate($imageInfo[0] + 130, 120);
            $tbgcolor = imagecolorallocate($target_img,255,255,255);
            $bai = imagecolorallocate($target_img, 1, 1, 1);
            $source = array();

            foreach ($imgs as $k => $v) {
                $source[$k]['source'] = Imagecreatefrompng($v);
                $source[$k]['size'] = getimagesize($v);
            }

            imagecopy($target_img, $source[0]['source'], 60, 0, 0, 0, $source[0]['size'][0], $source[0]['size'][1]);
            imagettftext($target_img,16, 0, 2, 110, $bai,EXTEND_PATH . 'file/ht.ttf',$ab);
            Imagepng($target_img, null);
            exit;
        }
    }

}

/*fnskuQrCode($text, $size = 3, $margin = 4)
它们的含义分别是：
$text,                                  // 生成的二维码 内容
$outfile = false,                 // 生成的二维码 文件名，false为 不保存
$level = QR_ECLEVEL_L,  //级别,也是容错率。下面会有介绍
$size = 3,                           //大小
$margin = 4,                     //外边距
$saveandprint=false       //是否 保存和打印。true为保存并打印
$pc=是否拼接：1是0否
$ab= 简称
*/
if (!function_exists('fnskuQrCode')) {
    function fnskuQrCode($text, $id, $size = 6, $margin = 2, $fba_nums = '') {

        require_once(EXTEND_PATH . "phpqrcode/qrlib.php");
        header('Content-Type: image/png');
        header("Content-Disposition:inline;filename=" . $text . '_' . $id . ".png");
        ob_clean();
        if ($fba_nums != '') {
            \QRcode::png($text, ROOT_QRCODE_FNSKU . $text . '_' . $id . "fbanums.png", QR_ECLEVEL_L, $size, $margin, false);
        } else {
            \QRcode::png($text, ROOT_QRCODE_FNSKU . $text . '_' . $id . ".png", QR_ECLEVEL_L, $size, $margin, false);
        }
        /*//ImagePng(ROOT_BARCODE_IMG.$text.'prostatus.png');
        $imgs[0] = ROOT_BARCODE_IMG.$text.'fnsku.png';
        //$imgs[1] = ROOT_BARCODE_IMG.$text.'down.png';
        //$target  = 'emp.jpg'; //背景图片
        $imageInfo = getimagesize($imgs[0]);
        $target_img = ImageCreate($imageInfo[0] + 130, 120);
        $tbgcolor = imagecolorallocate($target_img,255,255,255);
        $bai = imagecolorallocate($target_img, 1, 1, 1);
        $source = array();

        foreach ($imgs as $k => $v) {
            $source[$k]['source'] = Imagecreatefrompng($v);
            $source[$k]['size'] = getimagesize($v);
        }

        imagecopy($target_img, $source[0]['source'], 60, 0, 0, 0, $source[0]['size'][0], $source[0]['size'][1]);
        imagettftext($target_img,16, 0, 2, 110, $bai,EXTEND_PATH . 'file/ht.ttf',$text);
        Imagepng($target_img, null);*/
    }
}
/*shelvesQrCode($text, $size = 10, $margin = 3, $shelf_code = '', $name = '')
它们的含义分别是：
$text,                                  // 生成的二维码 内容
$size = 3,                           //大小
$margin = 4,                     //外边距
$shelf_code=''       //货架码
$name= 名称
*/
if (!function_exists('shelvesQrCode')) {
    function shelvesQrCode($text, $size = 10, $margin = 3, $shelf_code = '', $name = '',$qrcodetype=0) {

        require_once(EXTEND_PATH . "phpqrcode/qrlib.php");
//        header('Content-Type: image/png');
        header("Content-Disposition:inline;filename=" . $text . ".png");
        ob_clean();
        if ($qrcodetype == 1){
            \QRcode::png($shelf_code, ROOT_EP_SHELVESCODE_IMG.$text.'shelves.png', QR_ECLEVEL_L, $size, $margin, true);
        }else{
            \QRcode::png($text . '~' . $shelf_code, ROOT_SHELVESCODE_IMG.$text.'shelves.png', QR_ECLEVEL_L, $size, $margin, true);
        }
        //ImagePng(ROOT_SHELVESCODE_IMG.$text.'prostatus.png');
        $imgs[0] = ROOT_SHELVESCODE_IMG . $text . 'shelves.png';
        //$imgs[1] = ROOT_SHELVESCODE_IMG.$text.'down.png';
        $target  = ROOT_SHELVESCODE_IMG . 'shelves.png'; //背景图片Imagecreatefromjpeg
        $imageInfo = getimagesize($imgs[0]);
        //print_r($target);exit;
        $target_img = imagecreatefrompng($target);
        //$tbgcolor = imagecolorallocate($target_img,255,255,255);
        $bai = imagecolorallocate($target_img, 1, 1, 1);
        $source = array();
        foreach ($imgs as $k => $v) {
            $source[$k]['source'] = Imagecreatefrompng($v);
            $source[$k]['size'] = getimagesize($v);
        }
        $name_x = 50;
        $code_x = 130;
        //$name_y = 465;
        $half_img_w = 202;//图片宽度（px）的一半
        if ($name != null) {

            $fwh_name = imagettfbbox(29, 0, EXTEND_PATH . 'file/ht.ttf', $name);
            $name_width = ($fwh_name[2] - $fwh_name[0]) / 2;//字体宽度（px）的一半
            $name_x = $half_img_w - $name_width;
            if ($name_x < 0) $name_x = 0;
        }
        if ($shelf_code != null) {

            $fwh_code = imagettfbbox(29, 0, EXTEND_PATH . 'file/ht.ttf', $shelf_code);
            $code_width = ($fwh_code[2] - $fwh_code[0]) / 2;//字体宽度（px）的一半
            $code_x = $half_img_w - $code_width;
            if ($code_x < 0) $code_x = 0;
        }
        //$fwh_code = imagettfbbox(29 , 0, EXTEND_PATH . 'file/ht.ttf', $shelf_code);
        imagecopy($target_img, $source[0]['source'], 66, 120, 0, 0, $source[0]['size'][0], $source[0]['size'][1]);
        imagettftext($target_img,29, 0, $code_x, 408, $bai,EXTEND_PATH . 'file/ht.ttf',$shelf_code);
        imagettftext($target_img,29, 0, $name_x, 465, $bai,EXTEND_PATH . 'file/ht.ttf',$name);
        Imagepng($target_img, null);
        exit;
    }
}
//fba sku_id_size_time 二维码，包含文字
if (!function_exists('skuIdSizeQrCode')) {
    function skuIdSizeQrCode($sku, $sku_id, $size) {

        require_once(EXTEND_PATH . "phpqrcode/qrlib.php");
        header("Content-Disposition:inline;filename=" . $sku_id.'fbasku.png');
        ob_clean();
        \QRcode::png($sku . '_' . $sku_id . '_' . $size . '_' . time(), ROOT_QRCODE_FNSKU . $sku_id.'fbasku.png', QR_ECLEVEL_L, 10, 3, true);

        $imgs[0] = ROOT_QRCODE_FNSKU . $sku_id.'fbasku.png';
        $imageInfo = getimagesize($imgs[0]);//获取图片详情
        $target_img = ImageCreate($imageInfo[0] + 50,$imageInfo[1] + 80);//0宽，1高
        $tbgcolor = imagecolorallocate($target_img,255,255,255);//背景图片的颜色，白色
        $bai = imagecolorallocate($target_img, 1, 1, 1);//字体颜色，黑
        $source = array();
        foreach ($imgs as $k => $v) {
            $source[$k]['source'] = Imagecreatefrompng($v);
            $source[$k]['size'] = getimagesize($v);
        }
        $sku_x = 50;
        $code_x = 130;
        $size_x = 130;
        //$name_y = 465;
        $half_img_w = 202;//图片宽度（px）的一半
        if ($sku != null) {

            $fwh_sku = imagettfbbox(18, 0, EXTEND_PATH . 'file/ht.ttf', $sku);
            $sku_width = ($fwh_sku[2] - $fwh_sku[0]) / 2;//字体宽度（px）的一半
            $sku_x = $half_img_w - $sku_width;
            if ($sku_x < 0) $sku_x = 0;
        }
        if ($size != null) {

            $fwh_size = imagettfbbox(18, 0, EXTEND_PATH . 'file/ht.ttf', $size);
            $size_width = ($fwh_size[2] - $fwh_size[0]) / 2;//字体宽度（px）的一半
            $size_x = $half_img_w - $size_width;
            if ($size_x < 0) $size_x = 0;
        }
        if ($sku_id != null) {

            $fwh_code = imagettfbbox(18, 0, EXTEND_PATH . 'file/ht.ttf', $sku_id);
            $code_width = ($fwh_code[2] - $fwh_code[0]) / 2;//字体宽度（px）的一半
            $code_x = $half_img_w - $code_width;
            if ($code_x < 0) $code_x = 0;
        }
        imagecopy($target_img, $source[0]['source'], 25, 2, 0, 0, $source[0]['size'][0], $source[0]['size'][1]);
        imagettftext($target_img,18, 0, $sku_x, 348, $bai,EXTEND_PATH . 'file/ht.ttf',$sku);
        imagettftext($target_img,18, 0, $code_x, 378, $bai,EXTEND_PATH . 'file/ht.ttf',$sku_id);
        imagettftext($target_img,18, 0, $size_x, 398, $bai,EXTEND_PATH . 'file/ht.ttf',$size);
        Imagepng($target_img, ROOT_QRCODE_FNSKU . $sku_id.'fbaskuNEW.png');

        return ROOT_QRCODE_FNSKU . $sku_id.'fbaskuNEW.png';
    }
}


function InWhQrCode($sku, $sku_id, $size,$unique_id) {

    require_once(EXTEND_PATH . "phpqrcode/qrlib.php");
    header("Content-Disposition:inline;filename=" . $sku_id.'.png');
    ob_clean();
    \QRcode::png($unique_id, ROOT_QRCODE_INWH . $sku_id.'.png', QR_ECLEVEL_L, 10, 3, true);

    $imgs[0] = ROOT_QRCODE_INWH . $sku_id.'.png';
    $imageInfo = getimagesize($imgs[0]);//获取图片详情
    $target_img = ImageCreate($imageInfo[0] + 50,$imageInfo[1] + 80);//0宽，1高
    $tbgcolor = imagecolorallocate($target_img,255,255,255);//背景图片的颜色，白色
    $bai = imagecolorallocate($target_img, 1, 1, 1);//字体颜色，黑
    $source = array();
    foreach ($imgs as $k => $v) {
        $source[$k]['source'] = Imagecreatefrompng($v);
        $source[$k]['size'] = getimagesize($v);
    }
    $sku_x = 50;
    $code_x = 130;
    $size_x = 130;
    //$name_y = 465;
    $half_img_w = 202;//图片宽度（px）的一半
    if ($sku != null) {

        $fwh_sku = imagettfbbox(18, 0, EXTEND_PATH . 'file/ht.ttf', $sku);
        $sku_width = ($fwh_sku[2] - $fwh_sku[0]) / 2;//字体宽度（px）的一半
        $sku_x = $half_img_w - $sku_width;
        if ($sku_x < 0) $sku_x = 0;
    }
    if ($size != null) {

        $fwh_size = imagettfbbox(18, 0, EXTEND_PATH . 'file/ht.ttf', $size);
        $size_width = ($fwh_size[2] - $fwh_size[0]) / 2;//字体宽度（px）的一半
        $size_x = $half_img_w - $size_width;
        if ($size_x < 0) $size_x = 0;
    }
    if ($sku_id != null) {

        $fwh_code = imagettfbbox(18, 0, EXTEND_PATH . 'file/ht.ttf', $sku_id);
        $code_width = ($fwh_code[2] - $fwh_code[0]) / 2;//字体宽度（px）的一半
        $code_x = $half_img_w - $code_width;
        if ($code_x < 0) $code_x = 0;
    }
    imagecopy($target_img, $source[0]['source'], 25, 2, 0, 0, $source[0]['size'][0], $source[0]['size'][1]);
    imagettftext($target_img,18, 0, $sku_x, 348, $bai,EXTEND_PATH . 'file/ht.ttf',$sku);
    imagettftext($target_img,18, 0, $code_x, 378, $bai,EXTEND_PATH . 'file/ht.ttf',$sku_id);
    imagettftext($target_img,18, 0, $size_x, 398, $bai,EXTEND_PATH . 'file/ht.ttf',$size);
    Imagepng($target_img, ROOT_QRCODE_INWH . $sku_id.'.png');

    return ROOT_QRCODE_INWH . $sku_id.'.png';
}



    function allshelvesQrCode($text, $size = 10, $margin = 1, $shelf_code = '', $name = '',$qrcodetype=1) {
        require_once(EXTEND_PATH . "phpqrcode/qrlib.php");
        header('Content-type:image/png');
        header("Content-Disposition:inline;filename=" . $text . ".png");
        $url = 'http://www.caizhichao.cn'; //二维码内容
        $errorCorrectionLevel = 'H'; //容错级别
        $matrixPointSize = 5; //生成图片大小
        ob_start();
        QRcode::png($shelf_code, false, QR_ECLEVEL_L, 10, 1);
        $ob_contents = ob_get_contents(); //读取缓存区数据
        ob_end_clean();
        $myImage = ImageCreate(245,245); //参数为宽度和高度
        $qr = imagecreatefromstring($ob_contents);
        $qr_size = imagesx($qr);
        $img_x_y = (400-$qr_size)/2; //245为宽度
        $white = ImageColorAllocate($myImage, 255, 255, 255);
        $green=ImageColorAllocate($myImage, 44, 195, 179);
        $font_path = EXTEND_PATH . 'file/ht.ttf'; //引入字体

        $target  = ROOT_SHELVESCODE_IMG . 'shelves.png'; //背景图片Imagecreatefromjpeg

        $myImage = imagecreatefrompng($target);
        $bai = imagecolorallocate($myImage, 1, 1, 1);

        $name_x = 50;
        $code_x = 130;
        $half_img_w = 182;//图片宽度（px）的一半
        if ($name != null) {

            $fwh_name = imagettfbbox(29, 0, EXTEND_PATH . 'file/ht.ttf', $name);
            $name_width = ($fwh_name[2] - $fwh_name[0]) / 2;//字体宽度（px）的一半
            $name_x = $half_img_w - $name_width;
            if ($name_x < 0) $name_x = 0;
        }


        if ($shelf_code != null) {

            $fwh_code = imagettfbbox(29, 0, EXTEND_PATH . 'file/ht.ttf', $shelf_code);
            $code_width = ($fwh_code[2] - $fwh_code[0]) / 2;//字体宽度（px）的一半
            $code_x = $half_img_w - $code_width;
            if ($code_x < 0) $code_x = 0;
        }
//        imagecopy($myImage, $source[0]['source'], 66, 120, 0, 0, $source[0]['size'][0], $source[0]['size'][1]);
        imagettftext($myImage, 29, 0, $code_x, 390, $bai, $font_path, $shelf_code);
        imagettftext($myImage,29, 0, $name_x, 430, $bai,$font_path,$name);
        imagecopyresampled($myImage, $qr, 70, 125, 0, 0, $qr_size, $qr_size, $qr_size, $qr_size); //重新组合图片并调整大小
        Header("Content-type: image/png");
        if (!$qrcodetype){
            imagepng($myImage , ROOT_SHELVESCODE_IMG . $text . 'shelves.png');
        }else{
        imagepng($myImage , ROOT_EP_SHELVESCODE_IMG . $text . 'shelves.png'); //带文字二维码的文件名
        }
    }

/*clQrCode($text, $size = 10, $margin = 3, $shelf_code = '', $name = '', $p_time = '', $p_size = '', $num = '', $fac = '')
它们的含义分别是：
$text,                                  // 生成的二维码 内容
$size = 3,                           //大小
$margin = 4,                     //外边距
$shelf_code=''       //货架码
$name= 名称
$p_time= 时间
$p_size= 规格
$num= 数量
$fac= 厂商
*/
if (!function_exists('clQrCode')) {
    function clQrCode($text, $size = 10, $margin = 3, $shelf_code = '', $name = '', $p_time = '', $p_size = '', $num = '', $fac = '') {

        require_once(EXTEND_PATH . "phpqrcode/qrlib.php");
        header('Content-Type: image/png');
        header("Content-Disposition:inline;filename=" . $text . ".png");
        ob_clean();
        \QRcode::png($text . '~' . $shelf_code, ROOT_SHELVESCODE_IMG.$text.'cl.png', QR_ECLEVEL_L, $size, $margin, true);
        //ImagePng(ROOT_SHELVESCODE_IMG.$text.'prostatus.png');
        $imgs[0] = ROOT_SHELVESCODE_IMG . $text . 'cl.png';
        //$imgs[1] = ROOT_SHELVESCODE_IMG.$text.'down.png';
        $target  = ROOT_SHELVESCODE_IMG . 'cl.png'; //背景图片Imagecreatefromjpeg
        $imageInfo = getimagesize($imgs[0]);
        //print_r($target);exit;
        $target_img = imagecreatefrompng($target);
        //$tbgcolor = imagecolorallocate($target_img,255,255,255);
        $bai = imagecolorallocate($target_img, 1, 1, 1);
        $source = array();
        foreach ($imgs as $k => $v) {
            $source[$k]['source'] = Imagecreatefrompng($v);
            $source[$k]['size'] = getimagesize($v);
        }
        //$name_x = 50;
        $code_x = 130;
        //$name_y = 465;
        $half_img_w = 546;//图片像素宽度（px）的一半

        if ($shelf_code != null) {

            $fwh_code = imagettfbbox(35, 0, EXTEND_PATH . 'file/ht.ttf', $shelf_code);
            $code_width = ($fwh_code[2] - $fwh_code[0]) / 2;//字体宽度（px）的一半
            $code_x = $half_img_w - $code_width;
            if ($code_x < 0) $code_x = 0;
        }
        //$fwh_code = imagettfbbox(29 , 0, EXTEND_PATH . 'file/ht.ttf', $shelf_code);
        imagecopy($target_img, $source[0]['source'], 411, 200, 0, 0, $source[0]['size'][0], $source[0]['size'][1]);
        imagettftext($target_img,35, 0, $code_x, 512, $bai,EXTEND_PATH . 'file/ht.ttf',$shelf_code);
        imagettftext($target_img,48, 0, 270, 630, $bai,EXTEND_PATH . 'file/ht.ttf',$name);
        imagettftext($target_img,35, 0, 750, 760, $bai,EXTEND_PATH . 'file/ht.ttf',$p_time);
        imagettftext($target_img,30, 0, 270, 760, $bai,EXTEND_PATH . 'file/ht.ttf',$p_size);
        imagettftext($target_img,40, 0, 270, 890, $bai,EXTEND_PATH . 'file/ht.ttf',$num);
        imagettftext($target_img,48, 0, 270, 1005, $bai,EXTEND_PATH . 'file/ht.ttf',$fac);
        Imagepng($target_img, null);
        exit;
    }
}

//原始条形码
if (!function_exists('barcode_user_code')) {
    function barcode_user_code($codebar, $text, $height = '', $size = null) {
        $lineSize = isset($size) ? $size : 4;
        //$codebar = $codebar; //条形码将要数据的内容
        //$text = $text;//input('request.text'); //条形码将要数据的内容
        $height = !empty($height) ? $height : 40;
        $height = $height / $lineSize;
        require_once(EXTEND_PATH . "barcodegen.1d-php5.v5.0.1/class/BCGColor.php");
        require_once(EXTEND_PATH . "barcodegen.1d-php5.v5.0.1/class/BCGDrawing.php");
        require_once(EXTEND_PATH . "barcodegen.1d-php5.v5.0.1/class/" . $codebar . ".barcode.php");//BCGcode128
        require_once(EXTEND_PATH . "barcodegen.1d-php5.v5.0.1/class/BCGFontFile.php");

        $font = new \BCGFontFile(EXTEND_PATH . 'barcodegen.1d-php5.v5.0.1/class/font/Arial.ttf', 18);
        $color_black = new \BCGColor(0, 0, 0);
        $color_white = new \BCGColor(255, 255, 255);
        $drawException = null;

        try {
            $code = new $codebar(); //实例化对应的编码格式
            $code->setScale($lineSize); // Resolution
            $code->setThickness($height); // Thickness
            $code->setForegroundColor($color_black); // Color of bars
            $code->setBackgroundColor($color_white); // Color of spaces
            $code->setFont($font); // Font (or 0)
            $code->setStart(null);//BCGcode128
            $code->setTilde(true);//BCGcode128
            //$code->setChecksum(false);//BCGcode39
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
        header("Content-Disposition:inline;filename=user.png");
        // Draw (or save) the image into PNG format.
        //Imagepng($drawing,ROOT_BARCODE_IMG.$text.'.png');

        $drawing->finish(\BCGDrawing::IMG_FORMAT_PNG);
        //Imagepng($drawing->get_im(),ROOT_BARCODE_IMG.$text.'up.png');
        //$sizes = getimagesize($drawing->get_im());
        //$wh = $drawing->getwh();
        //$drawing->destroy();
    }
}
//fba打印标签条形码
/*if (!function_exists('barcode_fba')) {
    function barcode_fba($codebar, $text, $height = '', $size = null)
    {
        $lineSize = isset($size) ? $size : 4;
        //$codebar = $codebar; //条形码将要数据的内容
        //$text = $text;//input('request.text'); //条形码将要数据的内容
        $height = !empty($height) ? $height : 40;
        $height = $height / $lineSize;
        require_once(EXTEND_PATH . "barcodegen.1d-php5.v5.0.1/class/BCGColor.php");
        require_once(EXTEND_PATH . "barcodegen.1d-php5.v5.0.1/class/BCGDrawing.php");
        require_once(EXTEND_PATH . "barcodegen.1d-php5.v5.0.1/class/" . $codebar . ".barcode.php");//BCGcode128
        require_once(EXTEND_PATH . "barcodegen.1d-php5.v5.0.1/class/BCGFontFile.php");

        $font = new \BCGFontFile(EXTEND_PATH . 'barcodegen.1d-php5.v5.0.1/class/font/Arial.ttf', 30);
        $color_black = new \BCGColor(0, 0, 0);
        $color_white = new \BCGColor(255, 255, 255);
        $drawException = null;

        try {
            $code = new $codebar(); //实例化对应的编码格式
            $code->setScale($lineSize); // Resolution
            $code->setThickness($height); // Thickness
            $code->setForegroundColor($color_black); // Color of bars
            $code->setBackgroundColor($color_white); // Color of spaces
            $code->setFont(0); // Font (or 0)
            $code->setStart(null);//BCGcode128
            $code->setTilde(true);//BCGcode128
            //$code->setChecksum(false);//BCGcode39
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
        header("Content-Disposition:inline;filename=".$text.".png");
        // Draw (or save) the image into PNG format.
        //Imagepng($drawing,ROOT_BARCODE_IMG.$text.'.png');

        //$drawing->finish(\BCGDrawing::IMG_FORMAT_PNG);
        Imagepng($drawing->get_im());
        //$sizes = getimagesize($drawing->get_im());
        exit;
        //return $target_img;
    }
}*/
//条形码,自行拼接图片
if (!function_exists('barcode_cn')) {
    function barcode_cn($codebar, $text, $ab, $height = '', $size = null)
    {
        $lineSize = isset($size) ? $size : 4;
        //$codebar = $codebar; //条形码将要数据的内容
        //$text = $text;//input('request.text'); //条形码将要数据的内容
        $height = !empty($height) ? $height : 40;
        $height = $height / $lineSize;
        require_once(EXTEND_PATH . "barcodegen.1d-php5.v5.0.1/class/BCGColor.php");
        require_once(EXTEND_PATH . "barcodegen.1d-php5.v5.0.1/class/BCGDrawing.php");
        require_once(EXTEND_PATH . "barcodegen.1d-php5.v5.0.1/class/" . $codebar . ".barcode.php");//BCGcode128
        require_once(EXTEND_PATH . "barcodegen.1d-php5.v5.0.1/class/BCGFontFile.php");

        $font = new \BCGFontFile(EXTEND_PATH . 'barcodegen.1d-php5.v5.0.1/class/font/Arial.ttf', 30);
        $color_black = new \BCGColor(0, 0, 0);
        $color_white = new \BCGColor(255, 255, 255);
        $drawException = null;

        try {
            $code = new $codebar(); //实例化对应的编码格式
            $code->setScale($lineSize); // Resolution
            $code->setThickness($height); // Thickness
            $code->setForegroundColor($color_black); // Color of bars
            $code->setBackgroundColor($color_white); // Color of spaces
            $code->setFont(0); // Font (or 0)
            $code->setStart(null);//BCGcode128
            $code->setTilde(true);//BCGcode128
            //$code->setChecksum(false);//BCGcode39
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
        header("Content-Disposition:inline;filename=".$text.".png");
        // Draw (or save) the image into PNG format.
        //Imagepng($drawing,ROOT_BARCODE_IMG.$text.'.png');

        //$drawing->finish(\BCGDrawing::IMG_FORMAT_PNG);
        Imagepng($drawing->get_im(),ROOT_BARCODE_IMG.$text.'up.png');
        //$sizes = getimagesize($drawing->get_im());
        $wh = $drawing->getwh();
        $drawing->destroy();
        //$timage = ImageCreate($wh['w'],30);
        //$tbgcolor = imagecolorallocate($timage,255,255,255);

        //$bai = imagecolorallocate($timage, 1, 1, 1);
        //imagettftext($timage,18, 0, 15, 22, $bai,EXTEND_PATH . 'file/ht.ttf',$ab);
        //Imagepng($timage,ROOT_BARCODE_IMG.$text.'down.png');
        $imgs[0] = ROOT_BARCODE_IMG.$text.'up.png';
        //$imgs[1] = ROOT_BARCODE_IMG.$text.'down.png';
        //$target  = 'emp.jpg'; //背景图片

        $target_img = ImageCreate($wh['w'],100);
        $tbgcolor = imagecolorallocate($target_img,255,255,255);
        $bai = imagecolorallocate($target_img, 1, 1, 1);
        $source = array();

        foreach ($imgs as $k => $v) {
            $source[$k]['source'] = Imagecreatefrompng($v);
            $source[$k]['size'] = getimagesize($v);
        }

        imagecopy($target_img, $source[0]['source'], 0, 0, 0, 0, $source[0]['size'][0], $source[0]['size'][1]);
        imagettftext($target_img,17, 0, 15, 88, $bai,EXTEND_PATH . 'file/ht.ttf',$ab);
        Imagepng($target_img, null);
        //exit;
        //return $target_img;
    }
}
//fba标签
if (!function_exists('barcode_fba')) {
    function barcode_fba($id, $codebar, $text, $height = '', $size = null)
    {
        $lineSize = isset($size) ? $size : 4;
        //$codebar = $codebar; //条形码将要数据的内容
        //$text = $text;//input('request.text'); //条形码将要数据的内容
        $height = !empty($height) ? $height : 40;
        $height = $height / $lineSize;
        require_once(EXTEND_PATH . "barcodegen.1d-php5.v5.0.1/class/BCGColor.php");
        require_once(EXTEND_PATH . "barcodegen.1d-php5.v5.0.1/class/BCGDrawing.php");
        require_once(EXTEND_PATH . "barcodegen.1d-php5.v5.0.1/class/" . $codebar . ".barcode.php");//BCGcode128
        require_once(EXTEND_PATH . "barcodegen.1d-php5.v5.0.1/class/BCGFontFile.php");

        $font = new \BCGFontFile(EXTEND_PATH . 'barcodegen.1d-php5.v5.0.1/class/font/Arial.ttf', 30);
        $color_black = new \BCGColor(0, 0, 0);
        $color_white = new \BCGColor(255, 255, 255);
        $drawException = null;

        try {
            $code = new $codebar(); //实例化对应的编码格式
            $code->setScale($lineSize); // Resolution
            $code->setThickness($height); // Thickness
            $code->setForegroundColor($color_black); // Color of bars
            $code->setBackgroundColor($color_white); // Color of spaces
            $code->setFont(0); // Font (or 0)
            $code->setStart(null);//BCGcode128
            $code->setTilde(true);//BCGcode128
            //$code->setChecksum(false);//BCGcode39
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
        header("Content-Disposition:inline;filename=".$text.".png");
        // Draw (or save) the image into PNG format.
        //Imagepng($drawing,ROOT_BARCODE_IMG.$text.'.png');

        //$drawing->finish(\BCGDrawing::IMG_FORMAT_PNG);
        Imagepng($drawing->get_im(),ROOT_FBAORDERLABEL_IMG . $id . '_' . $text . '_label.png');
        //$sizes = getimagesize($drawing->get_im());

        /*$wh = $drawing->getwh();
        $drawing->destroy();
        //$timage = ImageCreate($wh['w'],30);
        //$tbgcolor = imagecolorallocate($timage,255,255,255);

        //$bai = imagecolorallocate($timage, 1, 1, 1);
        //imagettftext($timage,18, 0, 15, 22, $bai,EXTEND_PATH . 'file/ht.ttf',$ab);
        //Imagepng($timage,ROOT_BARCODE_IMG.$text.'down.png');
        $imgs[0] = ROOT_BARCODE_IMG.$text.'fbacode.png';
        //$imgs[1] = ROOT_BARCODE_IMG.$text.'down.png';
        //$target  = 'emp.jpg'; //背景图片

        $target_img = ImageCreate($wh['w'] + 5,139);
        $tbgcolor = imagecolorallocate($target_img,255,255,255);
        $bai = imagecolorallocate($target_img, 1, 1, 1);
        $source = array();
        //$code_x = 130;
        //$name_y = 465;
        $half_img_w = 236;//图片像素宽度（px）的一半

        $fwh_code = imagettfbbox(12, 0, EXTEND_PATH . 'file/ht.ttf', $text);
        $code_width = ($fwh_code[2] - $fwh_code[0]) / 2;//字体宽度（px）的一半
        $code_x = $half_img_w - $code_width;
        if ($code_x < 0) $code_x = 0;

        foreach ($imgs as $k => $v) {
            $source[$k]['source'] = Imagecreatefrompng($v);
            $source[$k]['size'] = getimagesize($v);
        }
        $ab_1 = substr($ab, 0, 36);
        $ab_2 = substr($ab, -36);
        $ab_all = $ab_1 . '...' . $ab_2;

        imagecopy($target_img, $source[0]['source'], 2, 0, 0, 0, $source[0]['size'][0], $source[0]['size'][1]);
        imagettftext($target_img,12, 0, $code_x, 75, $bai,EXTEND_PATH . 'file/ht.ttf',$text);
        imagettftext($target_img,8, 0, 5, 87, $bai,EXTEND_PATH . 'file/ht.ttf',$ab_all);
        imagettftext($target_img,8, 0, 5, 102, $bai,EXTEND_PATH . 'file/ht.ttf',$co);
        imagettftext($target_img, 10, 0, 5, 115, $bai,EXTEND_PATH . 'file/ht.ttf',$sisz);
        imagettftext($target_img, 11, 0, 5, 128, $bai,EXTEND_PATH . 'file/ht.ttf','MADE IN CHINA');
        Header("Content-type: image/png");
        Imagepng($target_img, ROOT_FBAORDERLABEL_IMG . $id . '_label.png');*/
        //exit;
        //return $target_img;
    }
}

function resize_image($filename, $tmpname, $xmax, $ymax)
{
    $ext = explode(".", $filename);
    $ext = $ext[count($ext)-1];

    if($ext == "jpg" || $ext == "jpeg")
        $im = imagecreatefromjpeg($tmpname);
    elseif($ext == "png")
        $im = imagecreatefrompng($tmpname);
    elseif($ext == "gif")
        $im = imagecreatefromgif($tmpname);

    $x = imagesx($im);
    $y = imagesy($im);

    if($x <= $xmax && $y <= $ymax)
        return $im;

    if($x >= $y) {
        $newx = $xmax;
        $newy = $newx * $y / $x;
    }
    else {
        $newy = $ymax;
        $newx = $x / $y * $newy;
    }

    $im2 = imagecreatetruecolor($newx, $newy);
    imagecopyresized($im2, $im, 0, 0, 0, 0, floor($newx), floor($newy), $x, $y);
    return $im2;
}


//拼接文字
if (!function_exists('mark_bar_photo')) {
    function mark_bar_photo($background, $text, $logo, $filename)
    {
        $back = imagecreatefrompng($background);
        $color = imagecolorallocate($back, 0, 0, 0);
        $logo = imagecreatefrompng($logo);
        $logo_w = imagesx($logo);
        $logo_h = imagesy($logo);
        $font = EXTEND_PATH . '/widget/font/elephant.ttf'; // 字体文件
        //imagettftext只认utf8字体，所以用iconv转换
        imagettftext($back, 21, 0, 40, 337, $color, $font, $text);//调二维码中字体位置
        //执行合成调整位置
        imagecopyresampled($back, $logo, 139, 140, 0, 0, 65, 65, $logo_w, $logo_h);//调中间logo位置
        imagejpeg($back, $filename);
        imagedestroy($back);
        imagedestroy($logo);
    }
}

//判断手机端
if (!function_exists('is_mobile_request')) {
    function is_mobile_request()
    {
        $_SERVER['ALL_HTTP'] = isset($_SERVER['ALL_HTTP']) ? $_SERVER['ALL_HTTP'] : '';
        $mobile_browser = '0';
        if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|iphone|ipad|ipod|android|xoom)/i', strtolower($_SERVER['HTTP_USER_AGENT'])))
            $mobile_browser++;
        if ((isset($_SERVER['HTTP_ACCEPT'])) and (strpos(strtolower($_SERVER['HTTP_ACCEPT']), 'application/vnd.wap.xhtml+xml') !== false))
            $mobile_browser++;
        if (isset($_SERVER['HTTP_X_WAP_PROFILE']))
            $mobile_browser++;
        if (isset($_SERVER['HTTP_PROFILE']))
            $mobile_browser++;
        $mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
        $mobile_agents = array(
            'w3c ', 'acs-', 'alav', 'alca', 'amoi', 'audi', 'avan', 'benq', 'bird', 'blac',
            'blaz', 'brew', 'cell', 'cldc', 'cmd-', 'dang', 'doco', 'eric', 'hipt', 'inno',
            'ipaq', 'java', 'jigs', 'kddi', 'keji', 'leno', 'lg-c', 'lg-d', 'lg-g', 'lge-',
            'maui', 'maxo', 'midp', 'mits', 'mmef', 'mobi', 'mot-', 'moto', 'mwbp', 'nec-',
            'newt', 'noki', 'oper', 'palm', 'pana', 'pant', 'phil', 'play', 'port', 'prox',
            'qwap', 'sage', 'sams', 'sany', 'sch-', 'sec-', 'send', 'seri', 'sgh-', 'shar',
            'sie-', 'siem', 'smal', 'smar', 'sony', 'sph-', 'symb', 't-mo', 'teli', 'tim-',
            'tosh', 'tsm-', 'upg1', 'upsi', 'vk-v', 'voda', 'wap-', 'wapa', 'wapi', 'wapp',
            'wapr', 'webc', 'winw', 'winw', 'xda', 'xda-'
        );
        if (in_array($mobile_ua, $mobile_agents))
            $mobile_browser++;
        if (strpos(strtolower($_SERVER['ALL_HTTP']), 'operamini') !== false)
            $mobile_browser++;
        // Pre-final check to reset everything if the user is on Windows
        if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows') !== false)
            $mobile_browser = 0;
        // But WP7 is also Windows, with a slightly different characteristic
        if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows phone') !== false)
            $mobile_browser++;
        if ($mobile_browser > 0)
            return true;
        else
            return false;
    }
}

//判断手机端
if (!function_exists('isMobile')) {
    function isMobile()
    {
        // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
        if (isset($_SERVER['HTTP_X_WAP_PROFILE'])) {
            return true;
        }
        // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
        if (isset($_SERVER['HTTP_VIA'])) {
            // 找不到为flase,否则为true
            return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
        }
        // 脑残法，判断手机发送的客户端标志,兼容性有待提高。其中'MicroMessenger'是电脑微信
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $clientkeywords = array('nokia', 'sony', 'ericsson', 'mot', 'samsung', 'htc', 'sgh', 'lg', 'sharp', 'sie-', 'philips', 'panasonic', 'alcatel', 'lenovo', 'iphone', 'ipod', 'blackberry', 'meizu', 'android', 'netfront', 'symbian', 'ucweb', 'windowsce', 'palm', 'operamini', 'operamobi', 'openwave', 'nexusone', 'cldc', 'midp', 'wap', 'mobile', 'MicroMessenger');
            // 从HTTP_USER_AGENT中查找手机浏览器的关键字
            if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
                return true;
            }
        }
        // 协议法，因为有可能不准确，放到最后判断
        if (isset ($_SERVER['HTTP_ACCEPT'])) {
            // 如果只支持wml并且不支持html那一定是移动设备
            // 如果支持wml和html但是wml在html之前则是移动设备
            if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
                return true;
            }
        }
        return false;
    }
}


if (!function_exists('getDatesBetweenTwoDays')) {
    function getDatesBetweenTwoDays($startDate,$endDate){
        $dates = [];
        if(strtotime($startDate)>strtotime($endDate)){
            //如果开始日期大于结束日期，直接return 防止下面的循环出现死循环
            return $dates;
        }elseif($startDate == $endDate){
            //开始日期与结束日期是同一天时
            array_push($dates,$startDate);
            return $dates;
        }else{
            array_push($dates,$startDate);
            $currentDate = $startDate;
            do{
                $nextDate = date('Y-m-d', strtotime($currentDate.' +1 days'));
                array_push($dates,$nextDate);
                $currentDate = $nextDate;
            }while(strtotime($endDate) > strtotime($currentDate));
            return $dates;
        }
    }
}

if (!function_exists('downloadzipFile')) {
    function downloadzipFile($filePath, $showName)
    {
        if (is_file($filePath)) {
            //打开文件
            $file = fopen($filePath, "r");
            //返回的文件类型
            Header("Content-type: application/octet-stream");
            //按照字节大小返回
            Header("Accept-Ranges: bytes");
            //返回文件的大小
            Header("Accept-Length: " . filesize($filePath));
            //这里设置客户端的弹出对话框显示的文件名
            Header("Content-Disposition: attachment; filename=" . $showName);
            //一次性将数据传输给客户端
            //echo fread($file, filesize($filePath));
            //一次只传输1024个字节的数据给客户端
            //向客户端回送数据
            $buffer = 1024;//
            //判断文件是否读完
            while (!feof($file)) {
                //将文件读入内存
                $file_data = fread($file, $buffer);
                //每次向客户端回送1024个字节的数据
                echo $file_data;
            }
            fclose($file);
            return true;
        } else {
            return false;
        }
    }
}
//下載文件
if (!function_exists('downloadFile')) {
    function downloadFile($filePath, $showName)
    {
        if (is_file($filePath)) {
            //打开文件
            $file = fopen ($filePath, "r");
            //输入文件标签
            Header ( "Content-type: application/octet-stream" );
            Header ( "Accept-Ranges: bytes" );
            Header ( "Accept-Length: " . filesize ($filePath));
            Header ( "Content-Disposition: attachment; filename=" . $showName );
            //输出文件内容
            //读取文件内容并直接输出到浏览器
            echo fread ( $file, filesize ($filePath) );
            fclose ($file);
            exit ();

        } else {
            echo '找不到文件';
            exit;
        }
    }
}
//根据键值排序二维数组
if (!function_exists('array_sorts')) {
    function array_sorts($arr, $keys, $type = 'asc', $fac = 0)
    {
        $keysvalue = array();
        $new_array = array();

        foreach ($arr as $k => $v) {
            $keysvalue[$k] = $v[$keys];
        }

        if ($type == 'asc') {
            asort($keysvalue);
        } else {
            arsort($keysvalue);
        }

        reset($keysvalue);

        foreach ($keysvalue as $k => $v) {
            if ($arr[$k]['working_type'] == $fac && $fac != 0) {
                $new_array[$k] = $arr[$k];
            }
        }

        return $new_array;
    }
}

//工厂排序
if (!function_exists('array_sorts_three')) {
    function array_sorts_three($arr, $keys_one, $keys_two, $type = 'asc', $fac = 0)
    {//$keys_one:第一维键
        //$keys_two:第二维键
        $keysvalue = array();
        $new_array = array();

        foreach ($arr as $k => $v) {
            $keysvalue[$k] = $v[$keys_one][$keys_two];
        }

        if ($type == 'asc') {
            asort($keysvalue);
        } else {
            arsort($keysvalue);
        }

        reset($keysvalue);

        foreach ($keysvalue as $k => $v) {
            if ($arr[$k][$keys_one]['working_type'] == $fac && $fac != 0) {
                $new_array[$k] = $arr[$k];
            }
            //$new_array[$k] = $arr[$k];
        }

        return $new_array;
    }

}
    function filedArr(){
        return $field=array("order id"=>'OdrNum','sku'=>'sku',
            'description'=>'intro',
            'type'=>'type'
        ,"total"=>'money','quantity'=>'num','fulfillment'=>'fulfillment','product sales'=>'product_sales',
            'product sales tax'=>'product_sales_tax',
            'sales tax collected'=>'product_sales_tax',
            'shipping credits'=>'shipping_credits'
        ,'shipping credits tax'=>'shipping_credits_tax',
            'gift wrap credits'=>'gift_wrap_credits',
            'giftwrap credits tax'=>'giftwrap_credits_tax',
            'promotional rebates'=>'promotional_rebates'
        ,'promotional rebates tax'=>'promotional_rebates_tax',
            'marketplace withheld tax'=>'marketplace_withheld_tax',
            'selling fees'=>'selling_fees',
            'fba fees'=>'fba_fees',
            'other transaction fees'=>'other_transaction_fees','other'=>'other',
            'トランザクションの種類'=>'type',
            '数量'=>'num',
            '合計'=>'money',
            'SKU'=>'sku',
            '説明'=>'intro',
            '注文番号' => 'OdrNum',
            'フルフィルメント' => 'fulfillment',
            '商品売上'=>'product_sales',
            '配送料' => 'shipping_credits',
            'ギフト包装手数料'=>'gift_wrap_credits',
            'Amazonポイントの費用' => 'amazon_points',
            'プロモーション割引額' => 'promotional_rebates',
            '手数料' => 'selling_fees',
            'FBA 手数料' => 'fba_fees',
            'トランザクションに関するその他の手数料' => 'other_transaction_fees',
            'その他' => 'other',
            'Datum/Uhrzeit'=>'日期时间',
            'date/heure' => '日期时间',
            'Data/Ora:'=>'日期时间',
            'fecha y hora'=>'日期时间',
            'fecha/hora'=>'日期时间',
            'datum/tijd'=>'日期时间',
            'Typ'=>'type',
            'Bestellnummer'=>'OdrNum', 'SKU'=>'sku','Beschreibung'=>'intro',
            'Menge'=>'num',
            'Versand'=> 'fulfillment',
            'Umsätze'=>'product_sales',
            'Gutschrift für Versandkosten'=>'shipping_credits',
            'Gutschrift für Geschenkverpackung'=>'gift_wrap_credits',
            'Rabatte aus Werbeaktionen'=>'promotional_rebates',
            'Verkaufsgebühren'=>'selling_fees',
            'Gebühren zu Versand durch Amazon'=>'fba_fees',
            'Andere Transaktionsgebühren'=>'other_transaction_fees',
            'Andere'=>'other',
            'Gesamt' => 'money',
            'numéro de la commande'=>'OdrNum',
            'quantité'=>'num',
            "traitement" => 'fulfillment',
            "ventes de produits"=>'product_sales',
            "crédits d'expédition" => 'shipping_credits',
            "crédits sur l'emballage cadeau"=>"gift_wrap_credits",
            "Rabais promotionnels"=> "promotional_rebates",
            "frais de vente" => 'selling_fees',
            "Frais Expédié par Amazon" => 'fba_fees',
            "autres frais de transaction" => 'other_transaction_fees',
            "autre" => 'other',
            "Tipo" => 'type',
            "Numero ordine" => 'OdrNum',
            "Descrizione" => 'intro',
            "Quantità" => 'num',
            "Gestione" => 'fulfillment',
            "Vendite" => 'product_sales',
            "Accrediti per le spedizioni" => 'shipping_credits',
            "Accrediti per confezioni regalo" => 'gift_wrap_credits',
            "Sconti promozionali" => 'promotional_rebates',
            "Commissioni di vendita" => 'selling_fees',
            "Costi del servizio Logistica di Amazon" => 'fba_fees',
            "Altri costi relativi alle transazioni" => 'other_transaction_fees',
            "Altro" => 'other',
            "totale" => 'money',
            "tipo" =>"type",
            "número de pedido" =>"OdrNum",
            "descripción" =>"intro",
            "cantidad" =>"num",
            "gestión logística" =>"fulfillment",
            "ventas de productos" =>"product_sales",
            "abonos de envío" =>"shipping_credits",
            "abonos de envoltorio para regalo" =>"gift_wrap_credits",
            "devoluciones promocionales" =>"promotional_rebates",
            "tarifas de venta" =>"selling_fees",
            "tarifas de Logística de Amazon" =>"fba_fees",
            "tarifas de otras transacciones" =>"other_transaction_fees",
            "otro" =>"other",
            "bestelnummer" =>"OdrNum",
            "beschrijving" =>"intro",
            "aantal" =>"num",
            "verkoop van producten" =>'product_sales',
            "Verzendtegoeden" =>"shipping_credits",
            "kredietpunten cadeauverpakking" =>"gift_wrap_credits",
            "promotiekortingen" =>"promotional_rebates",
            "verkoopkosten" =>"selling_fees",
            "fba-vergoedingen" =>"fba_fees",
            "overige transactiekosten" =>"other_transaction_fees",
            "fba-vergoedingen" =>"fba_fees",
            "overige" =>"other",
            "totaal" =>"money",
            'fulfilment'=>'fulfillment'
        );
    }

    function searchStrat(){
       return array(
            'date/time'=>'日期时间',
            '日付/時間'=>'日期时间',
           'Datum/Uhrzeit'=>'日期时间',
           'date/heure' => '日期时间',
           'Data/Ora:'=>'日期时间',
           'fecha y hora'=>'日期时间',
           'fecha/hora'=>'日期时间',
           'datum/tijd'=>'日期时间',
       );
    }

    function moneyFormat(){
        return array(
            'Typ'=>'type',
        );
    }

    //url 获取
    function curlPost($url, $data, $header=0){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);//抓取指定网页
        curl_setopt($ch, CURLOPT_HEADER, $header);//设置header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }


    //计算订单即将超时
    if (!function_exists('outFbaTime')) {
        function outFbaTime($expect_delivery_time) {
            $today = date('Y-m-d H:i:s', time());
            $two = date('Y-m-d H:i:s', strtotime($expect_delivery_time) - 86400*2);
            if ($today > $expect_delivery_time) {
                return "<span style='background-color: #990000; color: #ffffff; width: 70px;'>" . date('Y-m-d', strtotime($expect_delivery_time)) . "</span>";//大于超时
            } else if ($today > $two) {
                return "<span style='background-color: #fab703; color: #ffffff; width: 70px;'>" . date('Y-m-d', strtotime($expect_delivery_time)) . "</span>";//大于两天提醒即将超时
            } else {
                return date('Y-m-d', strtotime($expect_delivery_time));//小于则不提醒
            }
        }
    }

    //截取两个字符之间的字符串
    if (!function_exists('getNeedBetween')) {
        function getNeedBetween($str,$mark1,$mark2){
            $res = '123' . $str . '123';
            $st = stripos($res, $mark1);
            $ed = stripos($res, $mark2);
            if(($st == false || $ed == false) || $st >= $ed) return 0;
            $res = substr($res, ($st + 1), ($ed - $st - 1));
            return $res;
        }
    }

//订单二维码生成
function orderQrcode($text,$size=2,$margin=1){
    require_once(EXTEND_PATH . "phpqrcode/qrlib.php");
    header('Content-type:image/png');
    header("Content-Disposition:inline;filename=" . $text . ".png");
    $QR = ROOT_PATHNEWORDER_IMG.$text.'.png';
    QRcode::png($text,$QR, QR_ECLEVEL_L, $size, $margin, false);
}


/**
 * 判断email格式是否正确
 * @param $email
 */
function is_email($email) {
    return strlen($email) > 6 && preg_match("/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/", $email);
}

/**
 * 判断手机号码格式是否正确
 * @param $mobilephone
 */
function is_mobilephone($mobilephone) {
    return strlen($mobilephone) == 11 && preg_match("/^13[0-9]{1}[0-9]{8}$|14[0-9]{1}[0-9]{8}$|15[0-9]{1}[0-9]{8}$|18[0-9]{1}[0-9]{8}|17[0-9]{1}[0-9]{8}$/", $mobilephone);
}

/**
 * 获取客户端IP地址[已集成 CDN获取底层用户IP]
 *
 * @return 返回IP地址
 */
function i2c_realip() {
    $ip = FALSE;
    if (isset($_SERVER["HTTP_CDN_SRC_IP"])) {
        return $_SERVER["HTTP_CDN_SRC_IP"];
    }
    // If HTTP_CLIENT_IP is set, then give it priority
    if (!empty($_SERVER ["HTTP_CLIENT_IP"])) {
        $ip = $_SERVER ["HTTP_CLIENT_IP"];
    }
    // User is behind a proxy and check that we discard RFC1918 IP addresses
    // if they are behind a proxy then only figure out which IP belongs to the
    // user.  Might not need any more hackin if there is a squid reverse proxy
    // infront of apache.
    if (!empty($_SERVER ['HTTP_X_FORWARDED_FOR'])) {

        // Put the IP's into an array which we shall work with shortly.
        $ips = explode(", ", $_SERVER ['HTTP_X_FORWARDED_FOR']);
        if ($ip) {
            array_unshift($ips, $ip);
            $ip = FALSE;
        }

        for ($i = 0; $i < count($ips); $i ++) {
            // Skip RFC 1918 IP's 10.0.0.0/8, 172.16.0.0/12 and
            // 192.168.0.0/16
            if (!preg_match('/^(?:10|172\.(?:1[6-9]|2\d|3[01])|192\.168)\./', $ips [$i])) {
                if (version_compare(phpversion(), "5.0.0", ">=")) {
                    if (ip2long($ips [$i]) != false) {
                        $ip = $ips [$i];
                        break;
                    }
                } else {
                    if (ip2long($ips [$i]) != - 1) {
                        $ip = $ips [$i];
                        break;
                    }
                }
            }
        }
    }
    // Return with the found IP or the remote address
    return ($ip ? $ip : $_SERVER ['REMOTE_ADDR']);
}