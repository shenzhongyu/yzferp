<?php
/**
 * Created by PhpStorm.
 * User: Mikkle
 * Email:776329498@qq.com
 * Date: 2017/2/21
 * Time: 8:44
 */

namespace app\base\controller;


use think\Exception;

class Word
{
    static private $dir="/upload/word/";
    static private $error;
    static private function start($style="",$width="841.9pt",$height='595.3pt')
    {
        return
        "<html xmlns:v=\"urn:schemas-microsoft-com:vml\"
xmlns:o=\"urn:schemas-microsoft-com:office:office\"
xmlns:w=\"urn:schemas-microsoft-com:office:word\"
xmlns:m=\"http://schemas.microsoft.com/office/2004/12/omml\"
xmlns=\"http://www.w3.org/TR/REC-html40\">
<head>
<xml><w:WordDocument><w:View>Print</w:View></xml>
<meta http-equiv=Content-Type content=\"text/html; charset=utf-8\">
<meta name=ProgId content=Word.Document>
<meta name=Generator content=\"Microsoft Word 15\">
<meta name=Originator content=\"Microsoft Word 15\">
<style>
<!--
@page WordSection1
	{size: $width $height;
	margin:24.0pt 24.0pt 24.0pt 24.0pt; //页边距
	mso-header-margin:42.55pt;
	mso-footer-margin:49.6pt;
	mso-paper-source:0;}
div.WordSection1
	{page:WordSection1;}
{$style}
-->
</style>
</head>
<body lang=ZH-CN link=blue vlink=purple style='tab-interval:21.0pt'>
<div class=WordSection1>";
    }

    public static function save($name,$data,$style="")
    {
        $body = self::start($style);
        $body.=$data;
        $body.="</div></body></html>";
        self::start($style);
        $path=self::getPath($name);
        ;
        if(self::wirtefile ($path,$data)){
            return $path;
        }else{
            echo self::$error;
            return false;
        }
    }

    public static function downloadOnline($name,$data,$width="841.9pt",$height='595.3pt')
    {
        echo self::start('',$width,$height);
        echo $data;
        echo "</div></body></html>";
        ob_start(); //打开缓冲区
        header("Cache-Control: public");
        Header("Content-type: application/octet-stream");
        Header("Accept-Ranges: bytes");
        if (strpos($_SERVER["HTTP_USER_AGENT"],'MSIE')) {
            header("Content-Disposition: attachment; filename={$name}.doc");
        }else if (strpos($_SERVER["HTTP_USER_AGENT"],'Firefox')) {
            Header("Content-Disposition: attachment; filename={$name}.doc");
        } else {
            header("Content-Disposition: attachment; filename={$name}.doc");
        }
        header("Pragma:no-cache");
        header("Expires:0");
        ob_end_flush();//输出全部内容到浏览器
        die();
    }


    static private function wirtefile ($path,$data)
    {
        try{
            self::mkdirByPath($path);
            $fp=fopen($path,"wb");
            fwrite($fp,$data);
            fclose($fp);
            return true;
        }catch (Exception $e){
            self::$error=$e->getMessage();
            return false;
        }

    }

    static private function mkdirByPath($dir)
    {
        $dir = dirname($dir);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true) or die('创建失败!');;
            return;
        }
        return;
    }

    static private function getPath($name)
    {
        $dir =self::$dir;
        if(PHP_OS=="WIN"){
            $path=$dir.$name;
        }else{
            $path=$_SERVER['DOCUMENT_ROOT'].$dir.$name;
        }
        return $path;
    }


}