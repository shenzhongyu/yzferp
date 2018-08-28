<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/5/3
 * Time: 16:26
 */

namespace app\base\validate;


use think\Validate;

class PersonnelCompany extends Validate
{
    protected $rule = [

        ['company_name','require|length:2,25', '公司名称不能为空|公司名称长度必须在2--20位之间'],
        ['company_desc','length:1,50', '公司简介长度不能超过50'],
    ];
    protected $scene = [

        'edit'    => ['company_name','company_desc'],
    ];




}