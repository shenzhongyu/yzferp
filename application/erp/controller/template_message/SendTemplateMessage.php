<?php
/**
 * Created by PhpStorm.
 * Power By Mikkle
 * Email：776329498@qq.com
 * Date: 2017/8/31
 * Time: 16:42
 */

namespace app\erp\controller\template_message;


use app\erp\controller\Base;
use think\Request;

class SendTemplateMessage
{
    static protected $instance;
    protected $options;
    protected $templateClass;
    public function __construct($class=false)
    {
        $class = __NAMESPACE__."\\".($class ?$class : "YzfErp");
        $this->request = Request::instance();
        $this->options = "erp_options";  //名字对应Config
        $this->templateClass = new $class();
    }



    static public function sendTemplateMessage($type,$guid="",$class=false)
    {
        $class_name = $class ? $class:false;
        $self = self::instance($class_name);
        $self->templateClass->superReview($type,$guid) ;
    }


    static public function instance($class_name=false){
        if (is_null(self::$instance)) {
            self::$instance = new static($class_name);
        }
        return self::$instance;
    }


}