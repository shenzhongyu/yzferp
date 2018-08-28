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

class OfficeSupplies extends Validate
{
    protected $rule = [

        ['number','require', '车牌号不能为空'],
        ['photo_m','alphaNum', '图片路径错误'],

        ['start_time','require|number','用车日期不能为空|用车日期格式错误'],
        ['end_time','require|number','结束日期不能为空|结束日期格式错误'],
        ['reason','require','用车事由不能为空'],
        ['amount','number|>=:0','随车人数为数字格式|人数大于等于0'],


        ['name','require', '用品名称不能为空'],
        ['number','require|float|>=:0', '数量不能为空|必须为数字|数量不能为负数'],
        ['unit','require', '单位不能为空'],
        ['price','require|float|>=:0', '单价不能为空|必须为数字|单价不能为负数'],

        ['desc','require', '领取说明不能为空'],

    ];
    protected $scene = [
        'vehicle'    => [
            'number',
            'photo_m',
        ],
        'apply'=>[
            'start_time',
            'end_time',
            'reason',
            'amount'
        ],
        'supplies_add'=>[
            'name',
            'number',
            'unit',
            'price'
        ],
        'supplies_apply'=>[
            'number',
            'desc'
        ],
    ];




}