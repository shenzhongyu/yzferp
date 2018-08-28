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

class OfficeManagerLine extends Validate
{
    protected $rule = [
        ['content','require|length:1,250','内容不能为空|内容长度为1--250位'],
        ['title','require|length:1,100',"标题不能为空|长度为1--100位"],
        ['manager_desc','require|length:1,250',"标题不能为空|长度为1--250位"],
        ['manager_status','require|length:1,10',"标题不能为空|长度为1--10位"],
    ];
    protected $scene = [
        'edit'    => ['content','title'],
        'dit'    => ['manager_desc','manager_status'],
    ];




}