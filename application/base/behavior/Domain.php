<?php
/**
 * Created by PhpStorm.
 * Power By Mikkle
 * Email：776329498@qq.com
 * Date: 2017/9/12
 * Time: 17:21
 */

namespace app\base\behavior;


use think\Config;
use think\Db;
use think\Exception;
use think\exception\PDOException;
use think\Log;
use think\Request;

class Domain
{
    static public $domain_list=[
        "www.yzfErp.com"=>[
            // 数据库类型
            'type'        => 'mysql',
            // 服务器地址
            'hostname'        => '127.0.0.1',
            // 数据库名
            'database'        => 'yzfErp',
            // 用户名
            'username'        => 'yzfErp',
            // 密码
            'password'        => 'yzfErp',
        ],
        "yzf.mikkle.cn"=>[
            // 数据库类型
            'type'        => 'mysql',
            // 服务器地址
            'hostname'        => '127.0.0.1',
            // 数据库名
            'database'        => 'yzfErp',
            // 用户名
            'username'        => 'yzfErp',
            // 密码
            'password'        => 'yzfErp',
        ],

    ];


    static public function  setDomainDatabase(){
        try{
            $request =Request::instance();
            $domain = $request->host();
//
//            if(isset(self::$domain_list[$domain])){
//                if (self::checkDomainOptions(self::$domain_list[$domain])){
//
//                }
//
//
//            }
            switch($domain){
                case "www.yzferp.com":



                    break;
                case "yzf.mikkle.cn":

                    if(isset(self::$domain_list[$domain])){
                        if (self::checkDomainOptions(self::$domain_list[$domain])){
                            self::updateDatabaseConfig(self::$domain_list[$domain]);
                        }
                    }

                    break;
                default:


            }




        }catch (Exception $e){
            Log::error($e->getMessage());
        }
    }

    static public function checkDomainOptions($options=[]){


        try{
         //   dump($options);
            $options_list=["type","hostname","database","username","password"];
            foreach($options_list as $value){
                if(!isset($options[$value])){

                    throw new Exception("数据库参数[{$value}]不全");
                }
            }
            $db = Db::connect($options );

          //  dump($db->table("mk_personnel_user")->count());
            if($db->table("mk_personnel_user")->count()){

            }

            return true;
        }catch(\PDOException $e){
            Log::error($e->getMessage());
            return false;
        }
        catch (Exception $e){
            Log::error($e->getMessage());
            return false;
        }

    }

    static public function updateDatabaseConfig($options=[]){
        try{
            if(!empty($options)){
                $database = Config::get("database");
                $options_list=["type","hostname","database","username","password"];
                foreach($options_list as $value){
                    $database[$value] = $options[$value];
                }
                Config::set("database",$database);
            }else{
                return false;
            }
        }catch (Exception $e){
            Log::error($e->getMessage());
            return false;
        }
    }

}