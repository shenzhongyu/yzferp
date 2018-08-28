<?php
/**
 * Created by PhpStorm.
 * Power By Mikkle
 * Email：776329498@qq.com
 * Date: 2017/8/31
 * Time: 17:25
 */

namespace app\erp\controller\template_message;


use app\erp\controller\Base;
use think\Exception;
use think\Log;
use think\Session;
use think\View;

class TemplateBase extends Base
{
    static protected $instance;


    static public function assignTemplate($name, $data = '')
    {
        $template = self::getTemplate($name);

        if (empty($data)){
            return $template;
        }else{
            try{
                return View::instance()->display($template,$data);
            }catch (Exception $e){
                Log::error($e->getMessage());
                ob_get_clean();
                throw $e;
                return false;
            }
        }
    }

    static public function getTemplate($name)
    {

        if (is_null(self::$instance)) {
            self::$instance = new static();
        }
        if(isset(self::$instance->$name)){
            return self::$instance->$name;

        }else{
            return false;
        }
    }



}