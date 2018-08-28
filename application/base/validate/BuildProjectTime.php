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

class BuildProjectTime extends Validate
{

    protected $rule = [
        ['build_time','require', '施工开始日期不能为空'],
        ['build_days','require|number', '施工总天数不能为空'],

        ['value','require|float|length:1,10|between:0,1','领款百分比不能为空|百分比为浮点数字|长度为10位|数值在0-1之间'],
    ];
    protected $scene = [
        'add'=>[
            'build_time','build_days'
        ],
        'value'=>[
            'value',
        ],

    ];



}