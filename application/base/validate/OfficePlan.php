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

class OfficePlan extends Validate
{
    protected $rule = [

        ['plan_name','require|length:1,50', '计划名称不能为空|计划名称长度必须在1--50位之间'],
        ['plan_content','require|length:1,200','内容不能为空|内容长度为1--200位'],
        ['start_time','require|length:1,200',"开始时间不能为空|开始时间错误"],
        ['end_time','require|length:1,200',"结束时间不能为空|结束时间错误"],
        ['plan_desc','length:1,200','内容长度为1--200位'],
        ['plan_grade','require|length:1,10','请为该计划指定一个重要性|计划等级错误'],
        ['dep_manager','require','请选择审核状态'],
        ['manager_content','length:1,75','内容长度为1--75位'],
        ['work_schedule','length:1,5',"工作进度更改错误"]
    ];
    protected $scene = [
        'edit'    => ['plan_name','plan_content','start_time','end_time','paln_desc','plan_grade'],
        'notedit'=>['dep_manager','manager_content'],
        'scheduleedit'=>['work_schedule']
    ];




}