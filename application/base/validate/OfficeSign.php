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

class OfficeSign extends Validate
{
    protected $rule = [

        ['image','require', '图片不能为空|图片路径错误'],
        ['photo_m','require|alphaNum', '图片不能为空|图片路径错误'],
        ['latitude','require|float',"地点错误"],
        ['longitude','require|float',"地点错误"],
        ['speed','float',"地点错误"],
        ['accuracy','float',"地点错误"],


    ];
    protected $scene = [
        'add'    => [
//            'photo_desc',
            'image',
            'photo_m',
            'latitude',
            'longitude',
            'speed',
            'accuracy',
        ],

    ];




}