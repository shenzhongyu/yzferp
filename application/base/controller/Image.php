<?php
/**
 * Created by PhpStorm.
 * Power By Mikkle
 * Email：776329498@qq.com
 * Date: 2017/8/3
 * Time: 15:29
 */

namespace app\base\controller;


use think\Response;
use think\Image as thinkImage;
use think\image\Exception as ImageException;
class Image extends thinkImage
{
    public function showImage($w=200,$h=200){

        if($w>600) $w=600;
        if($h>800) $h=800;
        $this->thumb($w,$h);
        Header("Content-type: image/png");
        ob_start(); //打开缓冲区
        imagepng($this->im, null, 9);
        ob_end_flush();//输出全部内容到浏览器

        die;
        return;

    }

    public function downloadImage($w=200,$h=200){
        $this->thumb($w,$h);
        header("Content-type: octet/stream");
        header("Content-disposition:attachment;filename=imgae.png;");
        ob_start(); //打开缓冲区
        imagepng($this->im, null, 9);
        ob_end_flush();//输出全部内容到浏览器

        die;
        return;

    }

    /**
     * Power: Mikkle
     * Email：776329498@qq.com
     * @param \SplFileInfo|string $file
     * @return Image
     */
    public static function open($file)
    {
        if (is_string($file)) {
            $file = new \SplFileInfo($file);
        }
        if($file){

        }
        if (!$file->isFile()) {
            throw new ImageException('image file not exist');
        }
        return new self($file);
    }



}