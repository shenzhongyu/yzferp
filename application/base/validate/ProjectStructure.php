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

class ProjectStructure extends Validate
{
    protected $rule = [

        ['living_room_structure','length:1,10', '请选择具体的居室结构'],
        ['housing_use','length:1,10', '请选择具体的房屋用途'],
        ['house_orientation','length:1,10', '请选择具体的房屋朝向'],
        ['lighting','length:1,10', '请选择具体的采光'],
        ['house_type','length:1,10', '请选择具体的房屋类型'],
        ['floor','length:1,28','内容长度为1-28位']
    ];
    protected $scene = [
        'edit'    => ['living_room_structure','housing_use','house_orientation','lighting','house_type','floor'],
    ];

}