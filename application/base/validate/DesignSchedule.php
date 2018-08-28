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

class DesignSchedule extends Validate
{
    protected $rule = [

        ['design_phase','require|length:1,100', '项目阶段不能为空|姓名长度必须在1--10位之间'],
        ['start_data','require|number|length:1,150', '开始日期不能为空|开始日期数据格式错误|开始日期错误'],
        ['expected_days','require|float|length:1,35', '预计天数不能为空|预计天数格式错误|预计天数过大'],
        ['design_desc','length:1,150', '请假类型错误'],

    ];
    protected $scene = [
        'add'    => ['design_phase','start_data','expected_days','design_desc'],

    ];




}