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

class ProjectCollectPrice extends Validate
{
    protected $rule = [
        ['collect_type','require', '收款类型不能为空'],
        ['collect_plan_id','require', '计划名称不能为空'],
        ['collect_name','require', '款项名称不能为空'],
        ['collect_price','require|float|>:0', '金额不能为空|付款金额数值为浮点数字|付款金额不能小于0'],
        ['collect_date','require', '计划收款日期不能为空'],
        ['cashier_uuid','require', '出纳人不能为空'],
        ['invoice_value','require|in:0,1', '是否开发票选择错误'],
        ['invoice_price','require|float|>=:0', '发票金额出现错误'],


        ['bank_id','require', '资金账户不能为空'],
        ['payment_id','require', '支付方式不能为空'],
    ];
    protected $scene = [
        'add'    => ['collect_type','collect_name','collect_price','collect_date','cashier_uuid','invoice_value','invoice_price'],
        'price'=>['payment_id','bank_id','collect_price'],
        'addTo'=>['collect_type','collect_plan_id','collect_date','cashier_uuid','invoice_value','invoice_price'],
    ];

}