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

class ProjectField extends Validate
{
    protected $rule = [

        ['photo_name','require', '图片名称不能为空'],
        ['photo_address','require', '图片路径不能为空'],
        ['field_name','require', '文件名称不能为空'],
        ['field_address','require', '文件路径不能为空'],
    ];
    protected $scene = [
        'addP'    => ['photo_name','photo_address'],
        'addF'    => ['field_name','field_address'],
    ];

}