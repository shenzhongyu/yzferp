<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/4/17
 * Time: 9:33
 */

namespace app\erp\controller;

use think\Loader;
use think\Session;
class Base extends \app\base\controller\Base
{
    protected $isLogin = false;  //判断是否登陆
    protected $uuid;              //登陆后的UUID

    protected $easyui_field_list;
    protected $error;             //出错时候的记录
    protected $log=[];            //要保存的记录


    protected $config_list=[];
    public function _empty(){
        $action = $this->request->action();
        if (isset($this->config_list[$action])){
            $action_name = $this->config_list[$action]['action_name'];
            if( method_exists($this,$action_name)){
                return $this->$action_name($action);
            }else{
                $this->error('你配置的方法不存在');
            }
        }else{
            $this->error('你配置的参数不存在');
        }
    }
    /**
     * 检查登陆信息
     * Power by Mikkle
     * QQ:776329498
     */
    public function _initialize()
    {
        if ($this->checkLoginGlobal()) {
            $this->isLogin = true;
        }
    }


    protected function addLog($code='',$msg=''){
        $this->log[] =[
            'uuid' => $this->uuid,
            'url' => $this->request->url(true),
            'method' => $this->request->method(),
            'data' => $this->getData(),
            'ip' => $this->request->ip(),
            'code'=>$code,
            'desc' => $msg,
        ];
    }
    protected function toSaveLog(){
        $this->save_log = true ;
        $this->addLog();
    }

    protected function showReturnCodeWithSaveLog($code = '', $data = [], $msg = ''){
        $this->save_log = true ;
        $this->addLog($code,$msg);
        return self::showReturnCode($code, $data, $msg);
    }




}