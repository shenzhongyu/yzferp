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

class EnginProjectMaterial extends Validate
{

    protected $rule = [
        ['number','require|float|>=:0', '更改的需求量不能为空|数值类型错误|需求量不能为小于0'],


        ['collect_type','require', '款项类型不能为空'],
        ['collect_name','require', '款项名称不能为空'],
        ['contact_number','require|number', '联系电话不能为空|联系电话为数字'],
        ['collect_date','require', '计划发货日期不能为空'],
        ['consignee','require', '收货人不能为空'],
        ['address','require', '收货地址不能为空'],

    ];
    protected $scene = [
        'add'=>[
            'number'
        ],
        'buy'=>[
            'collect_type',
            'collect_name',
            'contact_number',
            'collect_date',
            'consignee',
            'address',
        ]

    ];



}