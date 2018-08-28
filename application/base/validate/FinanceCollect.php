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

class FinanceCollect extends Validate
{

    protected $rule = [
        ['collect_name','require', '收款类型名称不能为空'],
        ['collect_value','require|float|between:0,1', '收款比例不能为空|数值类型错误|收款比例在0-1之间'],

    ];
    protected $scene = [
        'add'=>[
            'collect_name','collect_value'
        ],

    ];



}