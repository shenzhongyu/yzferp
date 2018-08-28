<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/5/3
 * Time: 16:26
 * 申请验证类型
 */

namespace  app\base\validate;


use think\Validate;

class ApplyModel extends Validate
{
    protected $rule = [
        ['apply_desc','require', '申请说明不能为空'],

    ];
    protected $scene = [
        'apply_track'    => ['apply_desc'],
    ];

}