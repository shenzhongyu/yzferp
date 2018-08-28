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

class PersonnelUser extends Base
{
    protected $rule = [
        'username'=>'require|alphaDash|length:1,25|unique:personnelUser',
        'password'=>'require|alphaDash|length:4,25',
        'name'=>'require',
        'company_id'=>'require',
        'department_type'=>'require',
        'department_id'=>'require',
        'jobs_id'=>'require',
        'role_id'=>'require',
        'mobile'=>'require|number|length:11,11|unique:personnelUser',
        'user_sex'=>"require|'sex'=>'notIn:1,2'",
        'user_family'=>'length:1,200',
//        ['username','require|alphaDash|length:1,25', '|请输入正确的用户名(为字母和数字，下划线_及破折号-)|用户名长度必须在4--20位之间'],
//        ['username','require|alphaDash|length:1,25|unique:personnelUser', '用户名不能为空|请输入正确的用户名(为字母和数字，下划线_及破折号-)|用户名长度必须在4--20位之间|用户已经存在'],
//        ['password','require|alphaDash|length:4,25', '密码不能为空|请输入正确的密码(为字母和数字，下划线_及破折号-)|密码长度必须在4--20位之间'],
//        ['name','require','请输入用户的姓名'],
//        ['company_id','require', '请选择公司'],
//        ['department_type','require', '请选择一个具体的部门类型'],
//        ['department_id','require', '请选择部门'],
//        ['jobs_id','require', '请选择职位'],
//        ['role_id','require', '请选择角色'],
//        ['mobile','require|number|length:11,11', '手机不能为空|请输入正确的手机号|手机号长度必须为11位'],
//        ['user_sex',"require|'sex'=>'notIn:1,2'","性别不能为空|性别选择错误"],
//        ['user_family',"length:1,200","内容过长，请精简内容."]
    ];
    protected  $message=[
        'username.require'=>'用户名不能为空',
        'username.alphaDash'=>'请输入正确的用户名(为字母和数字，下划线_及破折号-)',
        'username.length:1,25'=>'用户名长度必须在4--20位之间',
        'username.unique'=>'用户已经存在',
        'password.require'=>'密码不能为空',
        'password.alphaDash'=>'请输入正确的密码(为字母和数字，下划线_及破折号-)',
        'password.length:1,25'=>'密码长度必须在4--20位之间',
        'name.require'=>'用户的姓名不能为空',
        'company_id.require'=>'请选择公司',
        'department_type.require'=>'请选择一个具体的部门类型',
        'department_id.require'=>'请选择一个具体的部门类型',
        'jobs_id.require'=>'请选择职位',
        'role_id.require'=>'请选择角色',
        'mobile.require'=>'手机不能为空',
        'mobile.number'=>'请输入正确的手机号',
        'mobile.length:11,11'=>'手机号长度必须为11位',
        'mobile.unique'=>'手机号已存在',
        'user_sex.require'=>'性别不能为空',
//        'user_sex.require'=>'性别选择错误',
        'user_family.length:1,200'=>'内容过长，请精简内容.',
        'uniqueUuid:personnelUser'=>'用户名已存在'
    ];


    protected $scene = [
        'login'    => ['password'],
        'edit'    => [
            'name',
            'company_id',
            'department_id',
//            'department_type',
            'jobs_id',
            'role_id',
//            'username'=>'require|alphaDash|length:1,25',
            'mobile'=>'require|number|length:11,11',//|uniqueUuid:personnelUser,mobile^status'
        ],
        'eredit'    => [
            'name',
            'company_id',
            'department_id',
//            'department_type',
            'jobs_id',
            'mobile'=>'require|number|length:11,11',
        ],
        'add'    => [
            'name',
            'company_id',
            'department_id',
            'jobs_id',
            'username'=>'require|alphaDash|length:1,25|unique:personnelUser',
            'mobile'=>'require|number|length:11,11|unique:personnelUser,mobile^status',
            "sex",
            "family",

        ],
        'edit_user'    => [
            'name',
            'company_id',
            'department_id',
            'jobs_id',
            "sex",
            "family",
            'mobile'=>'require|number|length:11,11',

        ]
    ];

}