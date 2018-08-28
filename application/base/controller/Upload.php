<?php
/**
 * Created by PhpStorm.
 * Power By Mikkle
 * Email：776329498@qq.com
 * Date: 2017/7/27
 * Time: 11:05
 */

namespace app\base\controller;


use think\Cache;
use think\Config;
use think\Db;
use think\File;

class Upload
{

    /**
     * Power: Mikkle
     * Email：776329498@qq.com
     * @param File $file
     * @param string $save_path
     * @param bool|true $is_record
     * @param array $rule
     * @param bool|true $route
     * @return array
     */
    static public function uploadPicture(File $file,$save_path="",$is_record=true ,$rule=[],$route=true) {

        $file_hash_md5 = $file->hash("md5");
        $file_hash_sha1 = $file->hash("sha1");
        $return=[
            "code"=>1001,
            "data"=>[
                "md5"=>$file_hash_md5,
            ],
            "msg"=>"图片上传成功",
        ];

        //判断数据库中是否存在
        if ($is_record){
            $images_table = Config::get("upload.upload_images_table");
            $map = [
                "md5"=>$file_hash_md5,
                "sha1"=>$file_hash_sha1,
            ];
            $search_image=Db::table($images_table)->where($map)->find();
            if ($search_image){
                $return['data']["path"] =  $search_image["path"];
                $return["msg"] =  "获取已存在图像成功";

                $return['data']['id'] = $search_image["id"];
                if($route){
                    $return['data']['path'] = self::getRouteUrl($file_hash_md5,"images");
                }else{
                    $return['data']['path'] = $search_image["path"];
                }
                $return['data']['type'] = "images" ;
                return $return;
            }
        }

        $save_path = self::getSavePath("images");
        $validate_rule = $rule ? $rule : Config::get("upload.upload_images_validate");
        $info = $file->validate($validate_rule)->move($save_path);

        if ( $info ) {

            $oinfo = $info->getInfo();

            $data['name'] = $oinfo['name'];
            $data['path'] = self::getSavePath("images",false).DS.$info->getSaveName();
            $data['path'] = str_replace('\\', '/', $data['path']);
            $data['md5'] = $file_hash_md5;
            $data['sha1'] = $file_hash_sha1;
            $data['size'] = $oinfo['size'];
            $data['type'] = 'local';
            $data['create_time'] = time();
            $data['width'] = 0;
            $data['height'] = 0;


            if($is_record){
                $images_table = Config::get("upload.upload_images_table");
                if ( $id = Db::table($images_table)->insertGetId($data) ) {
                    $return['data']['id'] = $id;
                    if($route){
                        $return['data']['path'] = self::getRouteUrl($file_hash_md5,"images");
                    }else{
                        $return['data']['path'] = $data['path'];
                    }
                    $return['data']['type'] = "images" ;
                } else {
                    $return['code'] = 1041;
                    $return['msg'] = '记录到数据库失败！';
                }
            }else{
                if($route){
                    $return['data']['path'] = self::getRouteUrl($file_hash_md5,"images");
                }else{
                    $return['data']['path'] = $data['path'];
                }
                $return['data']['type'] = "images" ;
            }

        } else {
            $return['code'] = 1040;
            $return['msg'] = $file->getError();
        }
        return $return;
    }

    /**
     * Power: Mikkle
     * Email：776329498@qq.com
     * @param File $file
     * @param string $save_path
     * @param bool|true $is_record
     * @param array $rule
     * @return array
     */
    static public function uploadFile(File $file,$save_path="",$is_record=true,$rule=[],$route=true ) {
        $return=[
            "code"=>1001,
            "data"=>[],
            "msg"=>"文件上传成功",
        ];

        $file_hash_md5 = $file->hash("md5");
        $file_hash_sha1 = $file->hash("sha1");
        //判断数据库中是否存在
        if ($is_record){
            $files_table = Config::get("upload.upload_files_table");
            $map = [
                "md5"=>$file_hash_md5,
                "sha1"=>$file_hash_sha1,
            ];
            $search_file=Db::table($files_table)->where($map)->find();

            if ($search_file){
                $return['data']["path"] =  $search_file["path"];
                $return['data']["md5"] =  $file_hash_md5;
                $return["msg"] =  "获取已存在文件成功";

                $return['data']['id'] = $search_file["id"];

                if($route){
                    $return['data']['path'] = self::getRouteUrl($file_hash_md5,"files");
                    $return['data']["md5"] =  $file_hash_md5;
                }else{
                    $return['data']['path'] = $search_file["path"];
                    $return['data']["md5"] =  $file_hash_md5;
                }

                $return['data']['type'] = "files" ;
                return $return;
            }
        }

        $save_path = self::getSavePath("files");
        $validate_rule = $validate_rule = $rule ? $rule : Config::get("upload.upload_files_validate");
        $info = $file->validate($validate_rule)->move($save_path);

        if ( $info ) {
            $oinfo = $info->getInfo();

            $data['name'] = $oinfo['name'];

            $data['path'] = self::getSavePath("files", false) . DS . $info->getSaveName();
            $data['path'] = str_replace('\\', '/', $data['path']);
            $data['md5'] = $file_hash_md5;
            $data['sha1'] = $file_hash_sha1;
            $data['size'] = $oinfo['size'];
            $data['type'] = 'local';
            $data['create_time'] = time();


            if($is_record){
                $files_table = Config::get("upload.upload_files_table");
                $id = Db::table($files_table)->insertGetId($data);
                if ( $id > 0  ) {
                    $return['data']['id'] = $id;
                    if($route){
                        $return['data']["md5"] =  $file_hash_md5;
                        $return['data']['path'] = self::getRouteUrl($file_hash_md5,"files");
                    }else{
                        $return['data']["md5"] =  $file_hash_md5;
                        $return['data']['path'] = $data['path'];
                    }

                    $return['data']['type'] = "images" ;
                } else {
                    $return['code'] = 1041;
                    $return['msg'] = '记录到数据库失败！';
                }
            }else{
                if($route){
                    $return['data']['path'] = self::getRouteUrl($file_hash_md5,"files");
                }else{
                    $return['data']['path'] = $data['path'];
                }
                $return['data']['type'] = "files" ;
            }

        } else {
            $return['code'] = 1040;
            $return['msg'] = $file->getError();
        }
        return $return;
    }

    /**
     * 根据图片Md5 反查path路径
     * Power: Mikkle
     * Email：776329498@qq.com
     * @param $md5
     * @param bool|true $full
     * @return bool|mixed|string
     */
    static public function getPicturePathByMd5($md5,$full=true ){
        $info = Cache::get("Picture_{$md5}_info");
        if(empty($info)){
            $info=Db::table(Config::get("upload.upload_images_table"))->where(["md5"=>$md5,"status"=>["<>",-1]])->find();
            if($info){
                Cache::set("Picture_{$md5}_info",$info);
            }
        }
        if(!empty($info) && $full == true ){
            return $_SERVER['CONTEXT_DOCUMENT_ROOT'].$info["path"];
        }elseif(!empty($info) && $full != true ){
            return $info["path"];
        }else{
            return false;
        }
    }

    static public function getFilePathByMd5($md5,$full=true ){
        $info = Cache::get("File_{$md5}_info");
        if(empty($info)){
            $info=Db::table(Config::get("upload.upload_files_table"))->where(["md5"=>$md5,"status"=>["<>",-1]])->find();
            if($info){
                Cache::set("File_{$md5}_info",$info);
            }
        }
        if(!empty($info) && $full == true ){
            return $_SERVER['CONTEXT_DOCUMENT_ROOT'].$info["path"];
        }elseif(!empty($info) && $full != true ){
            return $info["path"];
        }else{
            return false;
        }
    }


    static public function getPictureInfoByMd5($md5,$full=true ){
        $info = Cache::get("Picture_{$md5}_info");
        if(empty($info)){
            $info=Db::table(Config::get("upload.upload_images_table"))->where(["md5"=>$md5,"status"=>["<>",-1]])->find();
            if($info){
                Cache::set("Picture_{$md5}_info",$info);
            }
        }
        return $info;
    }

    static public function getFileInfoByMd5($md5,$full=true ){
        $info = Cache::get("File_{$md5}_info");
        if(empty($info)){
            $info=Db::table(Config::get("upload.upload_files_table"))->where(["md5"=>$md5,"status"=>["<>",-1]])->find();
            if($info){
                Cache::set("File_{$md5}_info",$info);
            }
        }
        return $info;
    }





    /**
     * 获取保存路径
     * Power: Mikkle
     * Email：776329498@qq.com
     * @param $type
     * @param bool|true $absolute_path 服务器绝对路径
     * @return string
     */
    static public function getSavePath($type,$absolute_path=true){
        $root_path =$absolute_path? $_SERVER['CONTEXT_DOCUMENT_ROOT']:"";
        switch($type){
            case "images":
                $config_save_path = Config::get("upload.upload_images_path");
                if (!isset($save_path) && $config_save_path) {
                    $save_path = "{$root_path}{$config_save_path}";
                } elseif (!isset($save_path) && !$config_save_path) {
                    $save_path = "{$root_path}/upload/images";
                } else {
                    $save_path = "{$root_path}{$save_path}";
                }
            break;

            case "files":
                $config_save_path = Config::get("upload.upload_files_path");
                if (!isset($save_path) && $config_save_path) {
                    $save_path = "{$root_path}{$config_save_path}";
                } elseif (!isset($save_path) && !$config_save_path) {
                    $save_path = "{$root_path}/upload/files";
                } else {
                    $save_path = "{$root_path}{$save_path}";
                }
                break;
            default :
                $save_path = "{$root_path}/upload/others";
        }
        return $save_path;
    }

    static public function getRouteUrl($md5,$type,$width=480,$height=600){
        switch($type){
            case "images":
                    $save_path = "/upload/show_images/$md5/{$width}_{$height}";
                break;
            case "files":
                $save_path = "/upload/down_files/$md5";
                break;
            default :
                $save_path = "/upload/down_others/$md5";
        }
        return $save_path;
    }

    static public function getDownLoadUrl($md5,$type,$width=0,$height=0){
        switch($type){
            case "images":
                if($width>0&&$height>0){
                    $save_path = "/upload/down_images/$md5/{$width}_{$height}";
                }else{
                    $save_path = "/upload/down_images/$md5";
                }
                break;
            case "files":
                $save_path = "/upload/down_files/$md5";
                break;
            default :
                $save_path = "/upload/down_others/$md5";
        }
        return $save_path;
    }


    static public function downloadFileByMd5($md5){
        $info = self::getFileInfoByMd5($md5);
        $path=$_SERVER['CONTEXT_DOCUMENT_ROOT'].$info["path"];
        header("Content-type: octet/stream");
        header("Content-disposition:attachment;filename='".$info["name"]."';");
        header("Content-Length:".filesize($path));
        readfile($path);
        exit;
    }


    static public function downloadPictureByMd5($md5){
        $info = self::getPictureInfoByMd5($md5);
        $path=$_SERVER['CONTEXT_DOCUMENT_ROOT'].$info["path"];
        header("Content-type: octet/stream");
        header("Content-disposition:attachment;filename='".$info["name"]."';");
        header("Content-Length:".filesize($path));
        readfile($path);
        exit;
    }


}