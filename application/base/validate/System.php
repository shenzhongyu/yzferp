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

class System extends Validate
{
    protected $rule = [

        ['mobile','require|regex:1[34578]\d{9}', '手机号码不能为空|请输入正确的手机号码'],
        ['code','require|regex:\d{5}', '手机验证码不能为空|请输入正确的手机验证码'],
        ['username','require','用户名不能为空']
    ];
    protected $scene = [

        'mobile'    => ['mobile'],
        'checkCode' =>['mobile','code','username']
    ];

}