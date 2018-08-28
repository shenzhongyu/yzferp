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

class WeMenu extends Validate
{
    protected $rule = [

        ['title','require|chs|length:1,25', '菜单名称不能为空|格式错误|菜单名称长度必须在1--25位之间'],
        ['appid','require|length:1,15', '微信不能为空|微信选择错误'],
        ['tag_id','require|length:1,10', 'tag_id不能为空|tag_id选择错误'],
    ];
    protected $scene = [

        'edit'    => ['title','appid','tag_id'],
    ];




}