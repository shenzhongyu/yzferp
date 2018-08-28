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

class BudgetTem extends Base
{

    protected $rule = [
        ['name','require','模板名称不能为空'],
    ];
    protected $scene = [
        'add'=>[
            'name'
        ],

    ];



}