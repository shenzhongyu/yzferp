<?php
/**
 * Created by PhpStorm.
 * User: Mikkle
 * Q Q:776329498
 * Date: 2017/2/25
 * Time: 22:27
 */

namespace app\base\controller;

use com\barcodegen\BCGcode128;
use com\barcodegen\BCGBarcode;
use com\barcodegen\BCGColor;
use com\barcodegen\BCGDrawing;
use com\qrcode\QRcode;
use com\qrcode\QRencode;

class BarCodeGen
{
    function Code128($value='CP58B531BE03B0A101485'){

        $colorFront = new BCGColor(0, 0, 0);
        $colorBack = new BCGColor(255, 255, 255);

// Barcode Part
        $code = new BCGcode128();
        $code->setScale(2);
        $code->setColor($colorFront, $colorBack);
        $code->parse($value);

// Drawing Part
        $drawing = new BCGDrawing('', $colorBack);
        $drawing->setBarcode($code);
        $drawing->setRotationAngle(1);
        $drawing->draw();

        ob_start();
        $drawing->finish(BCGDrawing::IMG_FORMAT_PNG);
        $content = ob_get_contents();
        ob_end_clean();

        return  response($content,200,['Content-Length' => strlen($content)])->contentType('image/png');


    }
    function  qrCode()
    {

        $value = 'http://www.weilejia-china.com/'; //二维码内容
        $errorCorrectionLevel = 'H';//容错级别
        $matrixPointSize = 16;//生成图片大小
//生成二维码图片
        $c_url='uploads/qrcode/qrcode.png'; //已经生成的原始二维码图
        $logo = 'uploads/qrcode/logo.jpg';//准备好的logo图片
        QRcode::png($value, $c_url, $errorCorrectionLevel, $matrixPointSize, 2);
        $QR='uploads/qrcode/qrcode.png';


        if ($logo !== FALSE) {
            $QR = imagecreatefromstring(file_get_contents($c_url));
            $logo = imagecreatefromstring(file_get_contents($logo));
            $QR_width = imagesx($QR);//二维码图片宽度
            $QR_height = imagesy($QR);//二维码图片高度
            $logo_width = imagesx($logo);//logo图片宽度
            $logo_height = imagesy($logo);//logo图片高度
            $logo_qr_width = $QR_width / 5;
            $scale = $logo_width/$logo_qr_width;
            $logo_qr_height = $logo_height/$scale;
            $from_width = ($QR_width - $logo_qr_width) / 2;
            //重新组合图片并调整大小
            imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width,
                $logo_qr_height, $logo_width, $logo_height);
        }

        ob_start();
        ImagePng($QR);
        $content = ob_get_contents();
        ob_end_clean();

      return  response($content,200,['Content-Length' => strlen($content)])->contentType('image/png');

    }


}