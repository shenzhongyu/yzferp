<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/4/27
 * Time: 13:33
 */

namespace app\api\controller;


class YzfErpApi extends BaseWeApi
{


    protected $app_config=[
        'token'=>'yzferp',
        'appid'=>'wxa13efbf234271d72',
        'appsecret'=>'1fc235c095038500c9c7f1e0b9eca2f2',
        'encodingaeskey'=>'i1ttHxdPJ5ul0HSF5ZcHSFJnxRq5QMMtOgY5Z4nsdRf',

        //7搜同城公众号
//        'token'=>'YZFKJ',
//        'appid'=>'wxa3f229dad32836ea',
//        'appsecret'=>'02e0b81c046ea6084759013df8daeda3',
//        'encodingaeskey'=>'zRkyumgpMBZlPI66xWcpDghZecPXhzOCS8VXYaVh62d',

    ];
    protected $valid = 0;  //网站第一次匹配 true 1为匹配





}