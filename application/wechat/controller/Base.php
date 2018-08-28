<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/4/17
 * Time: 9:33
 */

namespace app\wechat\controller;

use think\Loader;
use think\Log;
use think\Session;
class Base extends \app\base\controller\Base
{
    protected $isLogin = false;  //判断是否登陆
    protected $uuid;              //登陆后的UUID

    protected $easyui_field_list;
    protected $error;             //出错时候的记录
    protected $log=[];            //要保存的记录


    protected $isWechatBrowser;
    protected $config_list=[];

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

        $user_agent = $this->request->server('HTTP_USER_AGENT');
        if (! strpos($user_agent, 'MicroMessenger') === false ) $this->isWechatBrowser = true;
    }


    protected function addLog($code='',$msg=''){
        $log = [
            'uuid' => $this->uuid,
            'url' => $this->request->url(true),
            'method' => $this->request->method(),
            'data' => $this->getData(),
            'ip' => $this->request->ip(),
            'code'=>$code,
            'desc' => $msg,
        ];
        $this->log[] =$log;
        Log::notice($log);
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