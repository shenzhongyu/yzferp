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

class DefaultRate extends Validate
{

    protected $rule = [
        ['material_name','require|length:1,50', '材料名称不能为空|材料名称长度必须在1--50位之间'],
        ['specifications','length:1,30', '规格内容长度为1--30位之间'],
        ['material_pin','length:1,80', '品牌内容长度为1--80位之间'],
        ['material_version','length:1,80', '材料型号长度为1--80位之间'],
        ['material_stock','require|number|length:1,30', '库存不能为空|库存数量为数字|库存数量长度为1--30位之间'],
        ['material_price','require|number|length:1,10', '售价不能为空|售价类型为数字|长度必须在1--10位之间'],
        ['inside_price','require|number|length:1,10', '发包价不能为空|发包价类型为数字|长度必须在1--10位之间'],
        ['active_price','number|length:1,10', '活动价类型为数字|长度必须在1--10位之间'],
        ['floor_price','require|number|length:1,10', '进价不能为空|进价类型为数字|长度必须在1--10位之间'],
        ['material_desc','length:1,200', '材料描述内容长度超出(1-200)'],

        ['base_name','require|length:1,80','基装类型不能为空|长度在1-80之间'],
        ['base_desc','length:1,200', '基装类型备注内容长度超出(1-200)'],

        ['template_name','require|length:1,80','模板类型不能为空|长度在1-80之间'],
        ['template_desc','length:1,200', '模板类型备注描述内容长度超出(1-200)'],

        ['categories_name','require|length:1,80','模板类型不能为空|长度在1-80之间'],
        ['base_id','length:20,40','类型不能为空|类型选择错误'],
        ['categories_desc','length:1,200', '模板类型备注描述内容长度超出(1-200)'],

        ['template_id','require|length:20,40','预算模板类型不能为空|选择错误'],
        ['budget_name','require|length:1,80','模板类型不能为空|长度在1-80之间'],
        ['budget_desc','length:1,200', '模板类型备注描述内容长度超出(1-200)'],

        ['rate_value','require|float|length:1,4|between:0,1','费率值不能为空|费率数值类型错误，类型为小数，且不超过3位|费率值应该是0-1之间的两位小数|费率值应该是0-1之间的两位小数'],
        ['rate_name','require|length:1,120','费率项名称不能为空|长度在1-120之间'],
        ['rate_desc','length:1,200', '费率项备注描述内容长度超出(1-200)'],

        ['rate_default_value','require|float|length:1,5|between:0,1','费率值不能为空|费率数值类型错误，类型为小数，且不超过3位|费率值应该是0-1之间的三位小数|费率值应该是0-1之间的两位小数'],
        ['rate_default_name','require|length:1,120','费率项名称不能为空|长度在1-120之间'],

        ['name','require|length:1,100','名称不能为空|名称长度为1-100位'],
        ['unit','require|length:1,100','单位不能为空|单位长度为1-100位'],
        ['unit_price','require|number|length:1,10', '主材单价不能为空|主材单价类型为数字|主材单价长度必须在1--10位之间|主材单价不能低于0'],
        ['auxiliary_unit','require|number|length:1,10', '辅材单价不能为空|辅材单价类型为数字|辅材单价长度必须在1--10位之间|辅材单价不能小于0'],
        ['artificial_price','require|number|length:1,10', '人工单价不能为空|人工单价类型为数字|人工单价长度必须在1--10位之间|人工单价不能小于0'],
        ['unit_profit','require|float|length:1,10', '主材毛利润不能为空|主材毛利润类型为数字|主材毛利润长度必须在1--10位之间|主材毛利润不能小于0'],
        ['auxiliary_profit','require|float|length:1,10', '辅材毛利润不能为空|辅材毛利润类型为数字|辅材毛利润长度必须在1--10位之间|辅材毛利润不能小于0'],
        ['artificial_profit','require|float|length:1,10', '人工毛利润不能为空|人工毛利润类型为数字|人工毛利润长度必须在1--10位之间|人工毛利润不能小于0'],
        ['loss_coefficient','require|float|length:1,10|between:0,1', '损耗系数不能为空|损耗系数类型为数字|损耗系数长度必须在1--10位之间|损耗系数应该在0-1之间的两位小数'],
        ['mechanical_coefficient','require|float|length:1,10|between:0,1', '机械费率不能为空|机械费率类型为数字|机械费率长度必须在1--10位之间|机械费率应该在0-1之间的两位小数'],
        ['desc','length:1,200', '备注内容长度超出(1-200)'],


        ['style','require|length:1,100','预算类型不能为空|预算类型长度为1-100位'],
        ['address','length:1,200','封面地址长度为1-200'],
        ['telephone','length:1,100','电话长度为1-120'],


     



    ];
    protected $scene = [
        'add'=>[
           'loss_coefficient','mechanical_coefficient','unit_profit','auxiliary_profit'
            ,'artificial_profit'
        ],
        'save_rate'=>['rate_default_value','rate_default_name']

    ];



}