<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/4/26
 * Time: 15:20
 */

namespace  app\base\validate;



class Material extends Base
{

    protected $rule = [
        ['material_name','require|length:1,50', '材料名称不能为空|材料名称长度必须在1--50位之间'],
        ['specifications','length:1,30', '规格内容长度为1--30位之间'],
        ['material_pin','length:1,80', '品牌内容长度为1--80位之间'],
        ['material_version','length:1,80', '材料型号长度为1--80位之间'],
        ['material_stock','number|length:1,30', '库存不能为空|库存数量为数字|库存数量长度为1--30位之间'],
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

        ['name','require|length:1,100|uniqueGuid:MaterialListEdit,name^category_id^status^company_id^type^package_id^package_type','名称不能为空|名称长度为1-100位|同工程类别下的装饰项目名称已存在'],
        ['unit','require|length:1,100','单位不能为空|单位长度为1-100位'],
        ['unit_price','require|number|length:1,10', '主材单价不能为空|主材单价类型为数字|主材单价长度必须在1--10位之间|主材单价不能低于0'],
        ['auxiliary_unit','require|number|length:1,10', '辅材单价不能为空|辅材单价类型为数字|辅材单价长度必须在1--10位之间|辅材单价不能小于0'],
        ['artificial_price','require|number|length:1,10', '人工单价不能为空|人工单价类型为数字|人工单价长度必须在1--10位之间|人工单价不能小于0'],
        ['unit_profit','require|float|length:1,10', '主材毛利润不能为空|主材毛利润类型为数字|主材毛利润长度必须在1--10位之间|主材毛利润不能小于0'],
        ['auxiliary_profit','require|float|length:1,10', '辅材毛利润不能为空|辅材毛利润类型为数字|辅材毛利润长度必须在1--10位之间|辅材毛利润不能小于0'],
        ['artificial_profit','require|float|length:1,10', '人工毛利润不能为空|人工毛利润类型为数字|人工毛利润长度必须在1--10位之间|人工毛利润不能小于0'],
        ['loss_coefficient','require|float|length:1,10|between:0,1', '损耗系数不能为空|损耗系数类型为数字|损耗系数长度必须在1--10位之间|损耗系数应该在0-1之间的两位小数'],
        ['mechanical_coefficient','require|float|length:1,10|between:0,1', '机械费率不能为空|机械费率类型为数字|机械费率长度必须在1--10位之间|机械费率应该在0-1之间的两位小数'],
//        ['desc','length:1,', '备注内容长度超出(1-200)'],


        ['style','require|length:1,100','预算书名称不能为空|预算书名称长度为1-100位'],
        ['address','length:1,200','封面地址长度为1-200'],
        ['telephone','length:1,100','电话长度为1-120'],
        ['com_name','require|length:1,200','公司名称不能为空|公司名称长度范围(1-200)'],

        ['package_name','require','类别不能为空'],
        ['type_name','require','套餐种类不能为空'],


    ];
    protected $scene = [
        'add'    => [
            'material_name',
            'specifications',
            'material_pin',
            'material_version',
            //'material_stock',
            'material_price',
            'inside_price',
            'floor_price',
            'material_desc'
        ],
        'basestyle'=>['base_name','base_desc'],
        'templatestyle'=>['template_name','template_desc'],
        'datastyle'=>['categories_name','base_id','categories_desc'],
        'budget'=>['template_id','budget_name','budget_desc'],
        'rate'=>['rate_value','rate_name','rate_desc'],

        'editlist'=>['name','unit','unit_price','auxiliary_unit'
         ,'artificial_price','unit_profit','auxiliary_profit','artificial_profit' ,
            'loss_coefficient','mechanical_coefficient'

        ],
        'editbudgetlist'=>[
            'style','address','telephone','com_name'
        ],
        'packagecategorystyle'=>[
            'package_name'
        ],
        'packagetype'=>[
            'type_name'
        ]
    ];



}