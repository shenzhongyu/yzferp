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

class FinanceBank extends Validate
{

    protected $rule = [
        ['name','require', '账户不能为空'],
        ['uuid','require', '账户管理者不能为空'],
        ['payment_id','require', '收付款方式不能为空'],
        ['balance_price','require|float|>=:0', '账户余额不能为空|数值类型错误|余额不能为小于0'],
        ['desc','require', '更改原因不能为空'],

    ];
    protected $scene = [
        'add'=>[
            'name','uuid','payment_id','balance_price'
        ],
        'edit'=>[
            'balance_price','desc'
        ]

    ];



}