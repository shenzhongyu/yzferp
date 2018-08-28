<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/4/26
 * Time: 15:20
 */

namespace  app\base\validate;


use think\Validate;

class Install extends Validate
{

    protected $rule = [
        ['company_name','require', '公司名称不能为空'],
        ['name','require', '用户名不能为空'],
        ['username','require', '登录账号不能为空'],
        ['password','require|length:4,20', '密码不能为空|密码长度为4-20位'],

    ];
    protected $scene = [
        'add'=>[
            'company_name','username','password','name'
        ],

    ];



}