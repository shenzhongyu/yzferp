<?php
namespace app\worker\controller;
use app\base\controller\Redis;

/**
 * Created by PhpStorm.
 * Power By Mikkle
 * Email：776329498@qq.com
 * Date: 2017/8/4
 * Time: 13:39
 */
class Test extends Base
{
    static public function test($data=""){


        if($data){

            return $data;
        }else{
            return Redis::instance()->get("test");
            return 444;
        }
    }

    static public function run($data=""){


        if($data){

            return $data;
        }else{
            return Redis::instance()->get("test");
            return 444;
        }




    }

    static public function job( $param = array() )
    {
        $time = time();
        echo "Time: {$time}, Func: ".get_class()."::".__FUNCTION__."(".json_encode($param).")\n";
    }

}