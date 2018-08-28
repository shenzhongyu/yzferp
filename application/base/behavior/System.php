<?php
/**
 * Created by PhpStorm.
 * Power By Mikkle
 * Email：776329498@qq.com
 * Date: 2017/9/13
 * Time: 9:29
 */

namespace app\base\behavior;


use think\Exception;
use think\Log;
use think\Request;

class System
{

    public function appInit(&$params)
    {
        try{

            Domain::setDomainDatabase();


        }catch (Exception $e){
            Log::error($e->getMessage());
        }

    }



}