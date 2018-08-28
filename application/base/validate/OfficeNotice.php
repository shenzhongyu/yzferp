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

class OfficeNotice extends Validate
{
    protected $rule = [
        ['content','require','内容不能为空'],
        ['title','require|length:1,100',"标题不能为空|长度为1--250位"],
        ['type',"require|length:1,1",'类型不能为空|类型错误'],
    ];
    protected $scene = [
        'edit'    => ['content','title','type'],
        'ed'    => ['content','title'],
    ];




}