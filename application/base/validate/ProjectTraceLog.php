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

class ProjectTraceLog extends Validate
{
    protected $rule = [
        ['log_content','require|length:1,255', '请填写日志内容|内容超出,请精简内容,字数在80字以类'],
        ["remind_time","require|length:1,20","请设置提醒时间|日期错误"],
        ["remind_content","require|length:1,255","请填写日志内容|内容超出,请精简内容,字数在80字以类"],
    ];
    protected $scene = [
        'edit'    => ['log_content'],
        'time'    =>['remind_time','remind_content'],
    ];

}