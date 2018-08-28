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

class OfficeComplaint extends Validate
{
    protected $rule = [

        ['project_id','require|length:1,40', '项目名称不能为空|名称长度必须在1--40位之间'],
        ['customer_name','require|length:1,35', '客户姓名不能为空|长度为1-35之间'],
        ['customer_phone','require|length:1,15', '客户联系电话不能为空|联系电话长度为1-15位'],
        ['time','require|length:1,20', '登记日期不能为空|日期错误'],
        ['end_time','require|length:1,20', '验收日期不能为空|日期错误'],
        ['complaint_content','require|length:1,255', '内容不能为空|内容长度为1--255位'],
        ['project_uuid',"require|length:1,40","原项目经理不能为空|长度不能超过40位"],
        ['complaint_uuid',"require|length:1,40","维修处理人不能为空|长度不能超过40位"],
        ["processing_status","require|length:1,2","处理状态不能为空"|'选择错误'],
        ['complaint_desc','require|length:1,255', '回访内容不能为空|长度必须在1--255位之间'],

    ];
    protected $scene = [
        'edit'    => ['project_id','customer_name','customer_phone','time','complaint_content','project_uuid','end_time'],
        'dit'     => ['complaint_uuid'],
        'ed'     => ['processing_status'],
        'desc'   =>['complaint_desc']
    ];




}