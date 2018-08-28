<?php

/**

 * Created by PhpStorm.

 * Power by Mikkle

 * QQ:776329498

 * Date: 2017/5/3

 * Time: 16:26

 */



namespace  app\base\validate;





use think\Validate;



class PersonnelRole extends Validate

{

    protected $rule = [
        ['role_name','require|length:2,25', '职位名称不能为空|职位名称长度必须在2--25位之间'],
        ['role_desc','length:0,100', '角色说简介长度不能超过100'],
    ];

    protected $scene = [
        'edit'    => ['role_name','role_desc'],
    ];



}