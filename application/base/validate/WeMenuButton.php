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

class WeMenuButton extends Validate
{
    protected $rule = [

        ['name','require|length:1,25', '按钮名称不能为空|按钮名称长度必须在1--25位之间'],
        ['pid','require|length:1,35', '父按钮不能为空|父按钮选择错误'],
        ['url','length:1,100', '跳转地址长度超出(1-100)'],
        ['key','length:1,40', '关键字内容过长(1-40)'],
        ['sort','length:1,10|number', '长度超出(1--10)|排序输入格式错误'],
        ['type','require|length:1,30', '请选择按钮类型|按钮类型选择错误'],
    ];
    protected $scene = [

        'edit'    => ['name','button_type','pid','url','key','sort'],
    ];




}