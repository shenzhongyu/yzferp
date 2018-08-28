<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/5/16
 * Time: 16:00
 */

namespace app\install\config;



class FieldValue
{
    public static $instance;

//    //对应部门类型
//    public $department=['6'=>'市场部','7'=>'材料部','8'=>'客服部','2'=>'人事部','3'=>'设计部','4'=>'工程部','5'=>'财务部'];
//    public $jobs=['6'=>'业务员','7'=>'前台客服','2'=>'人事经理','3'=>'设计师','5'=>'工程经理','6'=>'会计'];
    static public function getFieldValue($name,$type=false){
        if (is_null(self::$instance)) {
            self::$instance = new static();
        }
        switch ($type){
            case "array":
                $array=[];
                foreach ( self::$instance->$name as $item=>$value){
                    $array[]=["value"=>$item,"text"=>$value];
                }
                return $array;
                break;
            case "item":
                $array=[];
                foreach ( self::$instance->$name as $item=>$value){
                    $array[$item]=$value;
                }
                return $array;
                break;

            default:
                return self::$instance->$name;
        }

    }





}