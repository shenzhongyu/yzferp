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

class OfficePlanLog extends Validate
{
    protected $rule = [
        ['log_content','require|length:1,200','内容不能为空|内容长度为1--200位'],
        ['log_time','require|length:1,100',"开始时间不能为空|开始时间错误"],
        ['log_desc','length:1,200','内容长度为1--200位'],
    ];
    protected $scene = [
        'edit'    => ['log_content','log_time','log_desc'],
    ];




}