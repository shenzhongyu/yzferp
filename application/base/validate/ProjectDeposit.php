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

class ProjectDeposit extends Validate
{
    protected $rule = [
        ['payment_id','require', '支付方式不能为空'],
        ['payment_price','require|float|>:0', '付款金额不能为空|付款金额数值为浮点数字|付款金额不能小于0'],
        ['payment_date','require', '付款时间不能为空'],

        ['bank_id','require', '收款账号不能为空'],
        ['collected_uuid','require','收款确认人不能为空'],
        ['collected_price','require|float|>:0', '收款金额不能为空|收款金额数值为浮点数字|收款金额不能小于0'],
        ['collected_date','require', '收款时间不能为空'],
    ];
    protected $scene = [
        'add'    => ['payment_id','payment_price','payment_date'],
        'audit'=>['bank_id','collected_uuid','collected_price','collected_date'],
    ];

}