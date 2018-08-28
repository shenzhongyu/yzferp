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

class MaterialDataItem extends Validate
{

    protected $rule = [
        ['data_id','require|length:20,40', '数据类别不能为空|数据类别选择错误'],
        ['dataitem_name','require|length:1,80', '数据项名称不能为空|内容长度为1--80位之间'],
        ['item_unit_name','require|length:1,80', '单位不能为空'],
        ['item_specifications','length:1,80', '规格长度为1--80位之间'],
        ['item_pin','length:1,180', '品牌长度为1--180位之间'],
        ['unit_price_sum','require|number|length:1,80', '单价不能为空|单价类型为数字|单价长度必须在1--80位之间'],
        ['mater_price','number|length:1,20', '主材单价类型为数字|主材单价长度必须在1--20位之间'],
        ['auxiliary_price','number|length:1,20', '辅材单价类型为数字|辅材单价长度必须在1--20位之间'],
        ['user_price','number|length:1,20', '人工单价类型为数字|人工单价长度必须在1--20位之间'],
        ['base_price','require|number|length:1,80', '底价不能为空|底价类型为数字|底价长度必须在1--80位之间'],
        ['loss_ratio','number|length:1,10', '损耗比例类型为数字|损耗比例长度必须在1--10位之间'],
        ['loss_style','number|length:1,10', '损耗类型错误'],
        ['item_desc','length:1,200', '数据项备注内容长度超出(1-200)'],
        ['material_desc','length:1,200', '材料描述内容长度超出(1-200)'],



        ['number','require|number','数量不能为空|数量类型为数字'],
        ['zc_dj','require|number|length:1,10', '主材单价不能为空|主材单价类型为数字|主材单价长度必须在1--10位之间|主材单价不能低于0'],
        ['fc_dj','require|number|length:1,10', '辅材单价不能为空|辅材单价类型为数字|辅材单价长度必须在1--10位之间|辅材单价不能小于0'],
        ['rg_dj','require|number|length:1,10', '人工单价不能为空|人工单价类型为数字|人工单价长度必须在1--10位之间|人工单价不能小于0'],
        ['zc_mlr','require|float|length:1,10', '主材毛利润不能为空|主材毛利润类型为数字|主材毛利润长度必须在1--10位之间|主材毛利润不能小于0'],
        ['fc_mlr','require|float|length:1,10', '辅材毛利润不能为空|辅材毛利润类型为数字|辅材毛利润长度必须在1--10位之间|辅材毛利润不能小于0'],
        ['rg_mlr','require|float|length:1,10', '人工毛利润不能为空|人工毛利润类型为数字|人工毛利润长度必须在1--10位之间|人工毛利润不能小于0'],
        ['sh_xs','require|float|length:1,10|between:0,1', '损耗系数不能为空|损耗系数类型为数字|损耗系数长度必须在1--10位之间|损耗系数应该在0-1之间的两位小数'],
        ['jx_xs','require|float|length:1,10|between:0,1', '机械费率不能为空|机械费率类型为数字|机械费率长度必须在1--10位之间|机械费率应该在0-1之间的两位小数'],




    ];
    protected $scene = [
        'add'    => [
            'data_id',
            'dataitem_name',
            'item_unit_name',
            'item_specifications',
            'item_pin',
            'unit_price_sum',
            'mater_price',
            'auxiliary_price',
            'user_price','base_price','loss_ratio','loss_style','item_desc','material_desc'
        ],

        'edit_budget'=>[
            'number','zc_dj', 'fc_dj','rg_dj','zc_mlr','fc_mlr','rg_mlr','sh_xs','jx_xs'
        ],
    ];



}