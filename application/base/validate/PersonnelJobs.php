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

class PersonnelJobs extends Validate
{
    protected $rule = [

        ['company_id','require', '请选择公司'],
        ['department_type','require', '请选择一个具体的部门类型'],
        ['department_id','require', '请选择部门'],
        ['jobs_name','require|length:2,25', '职位名称不能为空|职位名称长度必须在2--25位之间'],
        ['jobs_desc','length:0,100', '职位说简介长度不能超过100'],
    ];
    protected $scene = [
        'edit'    => ['company_id','department_id','department_type','jobs_name','jobs_desc'],
    ];

}