<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/4/17
 * Time: 9:33
 */

namespace app\install\controller;

use think\Config;
use think\Exception;
use think\Loader;
use think\Log;
use think\Session;
class Base extends \app\base\controller\Base
{
    protected $version; //版本号
    protected $file;   //版本号读写的路径

    public function _initialize(){
        $this->file='install/version/version_number.txt';
        if(is_file(APP_PATH . $this->file)){
            $this->version=file_get_contents(APP_PATH .$this->file);
        }
    }

    protected  function writeVersion($version_number='1.0.0'){
        try{
            if (is_file(APP_PATH . $this->file)) {
                unlink ( APP_PATH . $this->file);
            }
            if (!is_dir(APP_PATH . dirname($this->file))) {
                // 创建目录
                mkdir(APP_PATH . dirname($this->file), 0777, true);
            }
            if (!is_file(APP_PATH . $this->file)) {
                file_put_contents(APP_PATH .$this->file,$version_number);
            }
            return true;
        }catch (Exception $e){
            Log::error($e);
            return false;
        }
    }
    protected  function readVersion(){
        if (empty($this->version)){
            if(is_file(APP_PATH . $this->file)){
                $this->version=file_get_contents(APP_PATH .$this->file);
            }else{
                $this->version='1.0.0';
            }
        }
        return $this->version;
    }

    protected function readUpdateField($field_path=""){
        $content='';
        if (!empty($field_path)){
            if (is_file($field_path)){
                $content=file_get_contents(APP_PATH .$this->file);
            }
        }
        return $content;
    }

    protected function writeUpdateField($field_path='',$content=""){
        try{
            if (!empty($field_path) && !empty($content)){
                if (is_dir(APP_PATH . dirname($field_path))) {
                    // 创建目录
                    @mkdir(APP_PATH . dirname($field_path), 0777, true);
                }
                if (is_file(APP_PATH . $this->file)) {
                    @file_put_contents(APP_PATH .$field_path,$content);
                }
            }
            return true;
        }catch (Exception $e){
            Log::error($e);
            return false;
        }

    }
    protected function writeField($field_path='',$content=""){
        try{
            if (!empty($field_path) && !empty($content)){
                if (!is_dir(ROOT_PATH . dirname($field_path))) {
                    // 创建目录
                    mkdir(ROOT_PATH . dirname($field_path), 0777, true);
                }
                if (!is_file(ROOT_PATH . $this->file)) {
                    file_put_contents(ROOT_PATH .$field_path,$content);
                }
            }
            return true;
        }catch (Exception $e){
            Log::error($e->getMessage());
            return false;
        }

    }
    protected static function checkDirBuild($dirname)
    {
        if (!is_dir($dirname)) {
            mkdir($dirname, 0777, true);
        }
    }

    protected static function chmodFile($path, $fileMode=0777)
    {
        self::checkDirBuild($path);
        switch (true) {
            case (is_file($path)):
                halt($path);
                chmod($path, $fileMode) ;
                break;
            case (is_dir($path)):
                break;
            default:
                ;
        }

    }
}