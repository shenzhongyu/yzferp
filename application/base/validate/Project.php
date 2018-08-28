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

class Project extends Validate
{
    protected $rule = [

        ['project_name','require|chsAlphaNum|length:1,20', '项目名称不能为空|项目名称为字母、汉字和数字|项目名称长度必须在1--20位之间'],
        ['expected_duration','require|chsAlphaNum|length:1,20', '预计总工期不能为空|预计总工期为汉字和数字|预计总工期长度必须在1--20位之间'],
        ['decoration_grade','length:1,10', '选择装修档次错误'],
        ['color_orientation','length:1,10', '选择色彩取向错误'],
        ['decoration_style','length:1,10', '选择装修风格错误'],
        ['decoration_type','length:1,10', '选择装修类型错误'],
        ['customer_source','require', '请选择客户来源'],
        ['project_budget','require|chsAlphaNum|length:1,20', '工程预算不能为空|工程预算为字母、汉字和数字|工程预算长度必须为1--20位'],
        ['decoration_area','require|chsAlphaNum|length:1,20', '装修面积不能为空|装修面积为字母、汉字和数字|装修面积长度必须为1--20位'],
        ['project_description','length:1,255', '输入的内容长度为1--255位'],

        ['apply_type','require|number|length:1,2', '废单类型不能为空|废单类型不能为空|废单类型不能为空'],
        ['apply_desc','require|length:1,255', '输入的内容长度为1--255位'],
    ];
    protected $scene = [
        'edit'    => ['project_name','expected_duration','decoration_grade','color_orientation','decoration_style',
            'decoration_type','customer_source','project_budget','decoration_area','project_description'],
        'wasteApply'=>[
            'apply_type','apply_desc'
        ],
    ];

}