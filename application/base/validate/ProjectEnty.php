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

class ProjectEnty extends Validate
{
    protected $rule = [
        ['project_name','require|chsAlphaNum|length:1,10', '项目名称不能为空|项目名称为字母、汉字和数字|项目名称长度必须在1--20位之间'],
        ['expected_duration','chsAlphaNum|length:1,20', '预计总工期为汉字和数字|预计总工期长度必须在1--20位之间'],
        ['decoration_grade','length:1,20', '请选择一个具体的装修档次'],
        ['color_orientation','length:1,20', '请选择一个具体的色彩取向'],
        ['decoration_style','length:1,20', '请选择一个具体的装修风格'],
        ['decoration_type','length:1,20', '请选择一个具体的装修类型'],
        ['customer_source','length:1,20', '请选择一个具体的客户来源'],
        ['project_budget','require|chsAlphaNum|length:1,20', '工程预算不能为空|工程预算为字母、汉字和数字|工程预算长度必须为1--20位'],
        ['decoration_area','require|chsAlphaNum|length:1,20', '装修面积不能为空|装修面积为字母、汉字和数字|装修面积长度必须为1--20位'],
        ['project_description','length:1,70', '输入的内容长度为1--70位'],

        ['contact_name','require|chs|length:1,10', '联系人姓名不能为空|姓名格式为汉字|姓名长度必须在1--10位之间'],
        ['householder_relation','require', '请输入与户主的关系'],
        ['customer_email','email', '请输入正确的邮箱地址'],
        ['customer_qq','number|length:5,15', 'QQ号不能为空|请输入正确的QQ号|QQ号长度必须为5-15位'],
        ['sex','require', '请选择性别'],
        ['contact_number',"require", '手机不能为空'],
    ];
    protected $scene = [
        'edit'    => ['project_name','expected_duration',
            'customer_source','project_budget','decoration_area','project_description'],
        'cont' => ['contact_name','householder_relation',
            'customer_email','customer_qq','sex','contact_number'],
        'building' => ['building_name','building_adress',
            'building_price','room_number'],
        'structure' => ['living_room_structure','housing_use',
            'house_orientation','lighting','house_type'],

    ];

}