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

class WorkTarget extends Validate
{
    protected $rule = [


        ['work_number','require|number|>:0', '任务指标不能为空|任务指标只能为正整数|任务指标不能为负数'],

    ];
    protected $scene = [
        'add'    => ['work_number'],

    ];

}