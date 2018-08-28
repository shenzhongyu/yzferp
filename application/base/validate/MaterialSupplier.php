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

class MaterialSupplier extends Validate
{

    protected $rule = [
        ['supplier_name','require|length:1,35', '供应商名称不能为空|供应商名称长度必须在1--35位之间'],
        ['supplier_brand','length:1,30', '供应商品牌长度为1-30位'],
        ['supplier_discount','number|length:1,20', '类型为数字类型|折扣长度为1-20位'],
        ['profit_margin','number|length:1,4', '类型为数字类型|长度必须在1--4位之间'],
        ['supplier_desc','length:1,255', '供应商说明内容长度为1-255位'],

        ['category_name','require|length:1,50', '材料类别名称不能为空|材料类别名称长度必须在1--50位之间'],
        ['category_desc','length:1,255', '供应商说明内容长度为1-255位'],

    ];
    protected $scene = [
        'add'    => ['supplier_name','supplier_brand','supplier_discount','profit_margin','supplier_desc'],
        'category'=>['category_name','category_desc']
    ];



}