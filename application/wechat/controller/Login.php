<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/4/17
 * Time: 10:05
 */

namespace app\wechat\controller;


use think\Config;
use think\Session;

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

                $urlSrc = url('index/index');

            $arr=[
                'code'=>$check_login['code'],
                'url'=>$urlSrc,
            ];
            return $arr;
        }else{
            return $this->fetch("login");
        }
    }



}