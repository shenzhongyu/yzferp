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

class PersonnelTrackStatus extends Validate
{
    protected $rule = [

        ['strack_name','require|length:1,25', '名称不能为空|公司名称长度必须在1--25位之间'],
        ['font_color','require|length:1,10', '颜色选择不能为空|颜色选择错误'],
        ['department_type','require|length:1,10', '部门类型不能为空|部门类型错误'],
        ['sort_value','require|number|length:1,10', '不能为空|排序值输入错误|长度必须在1--10位之间'],

    ];
    protected $scene = [

        'edit'    => ['strack_name','font_color','department_type','sort_value'],
    ];




}