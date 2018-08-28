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

class PersonnelDepartment extends Validate
{
    protected $rule = [

        ['company_id','require', '请选择公司'],
        ['department_type','require', '请选择一个具体的部门类型'],
        ['department_name','require|length:1,25', '部门名称不能为空|部门名称长度必须在2--20位之间'],
        ['department_desc','length:1,50', '部门简介长度不能超过50'],
    ];
    protected $scene = [
        'edit'    => ['company_id','department_name','department_type','department_desc'],
    ];

}