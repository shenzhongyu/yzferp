<?php
/**
 * Created by PhpStorm.
 * Power By Mikkle
 * Email：776329498@qq.com
 * Date: 2017/10/23
 * Time: 11:10
 */

namespace app\base\behavior;


use think\Exception;
use think\Log;
use think\Request;
use think\response\Redirect;

class Install
{

    public function run(&$params)
    {
        try{

            self::checkInstall();

        }catch (Exception $e){
            Log::error($e->getMessage());
        }

    }

    static public function checkInstall(){


        $install_lock = APP_PATH . "install/install.lock";
        if (!file_exists($install_lock)) {
            $request = Request::instance();

//            if ($request->controller() == "Test") {
//                echo $request->controller();
//
//                Header("Location:{$request->domain()}/install/index/index");
//                die();
//
//            }
        }




    }
}