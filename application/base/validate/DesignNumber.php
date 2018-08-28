<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/4/26
 * Time: 15:20
 */

namespace  app\base\validate;


use think\Validate;

class DesignNumber extends Validate
{

    protected $rule = [
        ['number','require|number|length:1,30', '数量不能为空|数量为数字|库存数量长度为1--30位之间'],







    ];
    protected $scene = [
        'add'=>[
            'number'
        ],

    ];



}