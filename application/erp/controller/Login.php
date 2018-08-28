<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/4/17
 * Time: 10:05
 */

namespace app\erp\controller;


use think\Cache;
use think\Config;
use think\Db;
use think\Loader;
use think\Session;
use mikkle\tp_wechat\Wechat;

class Login extends Base
{

    public function _initialize()
    {
       // parent::_initialize(); // TODO: Change the autogenerated stub
    }

    /**
     * 登陆验证
     * Power by Mikkle
     * QQ:776329498
     * @return array
     */
    public function login(){
        if ($this->request->isAjax()) {

            //数据库字段 网页字段转换
            $param = [
                'username' => 'username',
                'password' => 'password',
            ];
            $param_data = $this->buildParam($param);
      //      $param_data['id'] = $this->request->get('id');
            $check_login = $this->doModelAction($param_data, 'base/PersonnelUser.login', 'base/PersonnelUser', 'checkLogin');
            if (!isset($check_login['code'])) $this->showReturnCodeWithSaveLog(1111);
            if ($check_login['code'] == 1001) {
                $this->setLoginGlobal($check_login['data'], 1);
            }
            return $check_login;
        }else{
            if ($this->request->isMobile()){
                return $this->fetch('wechat@index/login');
            }else{
                $arr=[
                    'title'=>'装企ERP管理系统登录平台',
                ];
                $this->assign($arr);
                return $this->fetch("login");
            }

        }
    }

    public function getQrCode($app_id=1,$name="apply"){
        // Cache::clear();

        $log_id =$name;
        // return $log_id;
        $obj=Db::table('mk_wx_url')->where(['sid'=>$log_id])->find();
        if (!empty($obj)){
            $code = [
                'code' => 1001,
                'url' => $obj['code_url'],
                //   'cache'=>Cache::get('log_'.Session::get('log_id','login')),
            ];
            return json($code);
        }
        $options = Loader::model('base/We')->info($app_id);

        if(is_object($options)){
            $options = $options->toArray();
        }


        $this->weObj = Wechat::extend($options);

        $qr_code = $this->weObj->getQRCode($log_id,2)['ticket'];

        if ($qr_code) {
            $qr_url=$this->weObj->getQRUrl($qr_code);
            //写入session
            Session::set('log_id', $log_id, 'login');
            Session::set('qr_url', $qr_url, 'login');

            //写入Cache
            $log_data=['code'=>0,'type'=>'login','data'=>[]];
            Cache::set('log_'.$log_id,$log_data,210);

            //赋值
            $code = [
                'code' => 1001,
                'url' => $qr_url,
                //   'cache'=>Cache::get('log_'.Session::get('log_id','login')),
            ];
            Db::table('mk_wx_url')->insert(['sid'=>$log_id,'code_url'=>$qr_url]);
            return json($code);
        }
    }

}