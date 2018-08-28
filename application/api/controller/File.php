<?php
/**
 * Created by PhpStorm.
 * Power By Mikkle
 * Email：776329498@qq.com
 * Date: 2017/7/28
 * Time: 13:12
 */

namespace app\api\controller;


use app\base\controller\Image;
use app\base\controller\Upload;
use think\Cache;
use think\Config;
use think\Log;

class File extends Base
{

    public function uploadPicture($file_name = "file",$route=true)
    {
        Config::set(['default_return_type'    => 'json',]);
        if ($this->request->isPost()) {
            $pic = $this->request->file($file_name);
            if (isset($pic)) {
                if (is_array($pic) && !is_object($pic)){
                    return self::showReturnCodeWithOutData(1054, "只容许单张图片上传");
                }
                $rule=[];
                return Upload::uploadPicture($pic,"",true,$rule,$route);
            } else {
                Log::error(json_encode($pic));
                return self::showReturnCodeWithOutData(1010, "上传的图片不存在");
            }
        } else {
           // dump($this->request);
            return self::showReturnCodeWithOutData(1002);
        }
    }

    public function uploadFile($file_name = "file",$route=true)
    {
        Config::set(['default_return_type'    => 'json',]);
        if ($this->request->isPost()) {

            $pic = $this->request->file($file_name);
            if (isset($pic)) {
                if (is_array($pic) && !is_object($pic)){
                    return self::showReturnCodeWithOutData(1054, "只容许文件上传");
                }
                $rule=[];
                return Upload::uploadFile($pic,"",true,$rule,$route);
            } else {
                return self::showReturnCodeWithOutData(1010, "上传的文件不存在或超过最大限制");
            }
        } else {
            return self::showJsonReturnCodeWithOutData(1002);
        }
    }


    public function showPicture($md5="",$size="480_600")
    {
        $size_array = explode("_",$size);
        $width = is_numeric($size_array[0])?$size_array[0]:480;
        $height = is_numeric($size_array[1])?$size_array[1]:600;
        $path = Upload::getPicturePathByMd5($md5);
        if($path){
            Image::open($path)->showImage($width,$height);
        }
        return;
    }


    public function downloadPicture($md5="",$size="0_0")
    {
        if($size!="0_0"){
            $size_array = explode("_",$size);
            $width = is_numeric($size_array[0])?$size_array[0]:480;
            $height = is_numeric($size_array[1])?$size_array[1]:600;
            $path = Upload::getPicturePathByMd5($md5);
            Image::open($path)->downloadImage($width,$height);
            return;
        }else{
            Upload::downloadPictureByMd5($md5);
        }
    }

    public function downloadFiles($md5="")
    {
            Upload::downloadFileByMd5($md5);
    }




}