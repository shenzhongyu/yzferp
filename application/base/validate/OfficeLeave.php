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

class OfficeLeave extends Validate
{
    protected $rule = [

        ['leave_name','require|length:1,33', '请假人姓名不能为空|姓名长度必须在1--10位之间'],
        ['department_id','length:1,35', '部门名称选择错误'],
        ['jobs_id','length:1,35', '职位名称选择错误'],
        ['leave_type','length:1,35', '请假类型错误'],
        ['leave_content','require|length:1,75', '请假事由不能为空|内容长度为1--75位'],
        ["start_time","require|length:1,20","开始日期不能为空|日期错误"],
        ["end_time","require|length:1,20","结束日期不能为空|日期错误"],
        ['dep_manager','require','请选择审核状态'],
        ['manager_content','length:1,75','内容长度为1--75位'],

    ];
    protected $scene = [
        'edit'    => ['leave_name','department_id','jobs_id','leave_type','leave_content','start_time','end_time'],
        'notedit'=>['dep_manager','manager_content'],
    ];




}