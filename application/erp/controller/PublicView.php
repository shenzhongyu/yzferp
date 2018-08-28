<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/5/5
 * Time: 17:20
 * 登录页的其他页面
 */

namespace app\erp\controller;




class PublicView  extends Base
{
    public function index(){

        $this->assign('member_info',$this->member_info);
        return $this->fetch();
    }

    public function service(){
        $arr=[
            'title'=>'装企ERP管理系统服务条款',
        ];
        $this->assign($arr);
        self::echoHtml();
        return $this->fetch('login/service_terms');
    }
    public function statement(){
        $arr=[
            'title'=>'装企ERP管理系统法律声明',
        ];
        $this->assign($arr);
        self::echoHtml();
        return $this->fetch('login/legal_statement');
    }
    public function aboutUs(){
        $arr=[
            'title'=>'装企ERP管理系统关于我们',
        ];
        $this->assign($arr);
        self::echoHtml();
        return $this->fetch('login/about_us');
    }
}