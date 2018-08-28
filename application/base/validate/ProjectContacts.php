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

class ProjectContacts extends Validate
{
    protected $rule = [
        ['contact_name','require|chs|length:1,10', '联系人姓名不能为空|姓名格式为汉字|姓名长度必须在1--10位之间'],
        ['householder_relation','require', '请输入与户主的关系'],
        ['customer_email','email', '请输入正确的邮箱地址'],
        ['customer_qq','number|length:5,15', 'QQ号不能为空|请输入正确的QQ号|QQ号长度必须为5-15位'],
        ['sex','require', '请选择性别'],
        ['contact_number',"number|length:1,11", '手机不能为空|请输入正确的手机号|手机号长度必须为11位'],
    ];
    protected $scene = [
        'edit'    => ['contact_name','householder_relation','customer_email','customer_qq','sex','contact_number'],
    ];

}