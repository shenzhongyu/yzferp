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

class ProjectAudit extends Validate
{
    protected $rule = [
        ['transfer_desc','require|max:255', '申请说明不能为空|内容长度不超过255位'],
        ['into_department_id','require|length:20,40','转入的部门不能为空'],

        ['examine_status','require|in:-1,1', '不能为空|请选择是否通过审核'],
        ['examine_desc','require|max:255', '审核说明不能为空|内容长度不超过255位'],
    ];
    protected $scene = [
        'add'=>["transfer_desc",'into_department_id'],
        'addto'=>["waste_desc"],
        'save'=>[ 'examine_status', 'examine_desc',],
        'addEdit'=>["transfer_desc"],
    ];

}