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

class PersonnelMaterial extends Validate
{

    protected $rule = [
        ['material_name','require|length:1,25', '材料不能为空|公司名称长度必须在1--25位之间'],
        ['specifications','length:1,15', '规格长度错误'],
        ['unit_name','require|length:1,10', '材料单位不能为空|材料单位错误'],
        ['material_price','require|number|length:1,10', '单价不能为空|单价输入错误|长度必须在1--10位之间'],
        ['floor_price','length:1,10', '底价长度错误'],
        ['explain','length:1,40', '材料说明长度超出'],



    ];
    protected $scene = [

        'edit'    => ['material_name','specifications','unit_name','material_price','floor_price','explain'],
    ];



}