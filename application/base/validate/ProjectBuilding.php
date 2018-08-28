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

class ProjectBuilding extends Validate
{
    protected $rule = [
        ['building_name','chsDash|length:1,15', '楼盘名称为汉字、字母、数字和下划线_及破折号-|楼盘名称长度必须在1--15位之间'],
        ['building_adress','require|chsDash|length:1,20', '楼盘地址不能为空|楼盘地址为汉字、字母、数字和下划线_及破折号-|楼盘地址长度必须在1--20位之间'],
        ['building_price','require|length:1,20', '请输入楼盘均价'],
        ['room_number','length:1,51', '房产信息长度必须为1-50位'],

    ];
    protected $scene = [
        'edit'    => ['building_name','building_adress','building_price','room_number'],
    ];

}