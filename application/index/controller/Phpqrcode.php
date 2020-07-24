<?php
namespace app\index\controller;

class Phpqrcode extends Base {
    public function qr_code() {
        $text = input('get.text');
        if (is_numeric(input('get.size')) && input('get.size') != 0) {
            $size = input('get.size');
        } else {
            $size = 6;
        }
        if (is_numeric(input('get.margin')) && input('get.margin') != 0) {
            $margin = input('get.margin');
        } else {
            $margin = 2;
        }

        if ($text == '') return '请填写字符串';
        phpQrCode($text, $size, $margin);exit;
    }
}