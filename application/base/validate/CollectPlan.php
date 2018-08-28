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

class CollectPlan extends Validate
{

    protected $rule = [
        ['collect_plan_name','require', '计划名称不能为空'],
        ['collect_value','require|float|between:0,1', '收款比例不能为空|数值类型错误|收款比例在0-1之间'],
        ['name','require', '名称不能为空'],
        ['collect_days','require|number', '收款时间不能为空|数值类型错误'],
    ];
    protected $scene = [
        'add'=>[
            'collect_name',
        ],
        'editAdd'=>[
            'name','collect_value','collect_days'
        ],

    ];



}