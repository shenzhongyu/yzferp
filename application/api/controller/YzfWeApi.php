<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/4/27
 * Time: 13:33
 */

namespace app\api\controller;


class YzfWeApi extends BaseWeApi
{


    protected $app_config=[

        //7搜同城公众号
        'token'=>'YZFKJ',
        'appid'=>'wxa3f229dad32836ea',
        'appsecret'=>'02e0b81c046ea6084759013df8daeda3',
        'encodingaeskey'=>'zRkyumgpMBZlPI66xWcpDghZecPXhzOCS8VXYaVh62d',

    ];
    protected $valid = 0;  //网站第一次匹配 true 1为匹配





}