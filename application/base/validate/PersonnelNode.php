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

class PersonnelNode extends Validate
{

    protected $rule = [
        ['id','number', '请输入正确的数字'],
        ['pid','require|alphaNum|different:guid', '归属不能为空|请选择正确归属|分类不能选择自身'],
        ['node_name','require|length:1,30', '名称不能为空|请输入正确的名称(2~10位)'],
        ['module_name','require|length:1,30', '模块名称不能为空|请输入正确的名称(2~10位)|只能为字母和数字，下划线_及破折号-'],
        ['control_name','require|length:1,30', '控制器名称不能为空|请输入正确的名称(2~10位)|只能为字母和数字，下划线_及破折号-'],
        ['action_name','require|length:1,40', '方法名称不能为空|请输入正确的名称(2~40位)|只能为字母和数字，下划线_及破折号-'],
        ['icon','length:1,60', 'icon长度为1-20'],
        ['is_menu','in:0,1,2', '请选择是否为目录'],
        ['auth_grade','in:0,1,2', '请输入正确的权限'],
        ['group','length:2,20', '请输入正确的分组名称(2~10位)'],
        ['sort','number', '请输入正确的排序数字'],

    ];
    protected $scene = [
        'edit'   =>  ['id','pid','node_name','module_name','control_name','action_name','icon','is_menu','auth_grade','group','sort'],

    ];


}