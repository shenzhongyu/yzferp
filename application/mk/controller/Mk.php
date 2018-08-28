<?php
namespace app\mk\controller;
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/4/17
 * Time: 17:28
 */
abstract class Mk extends \think\Controller
{

    public function fetch($template='', $vars=array(), $replace=array(), $config=array()) {
        $template = dirname(__FILE__) . '/../view/mk/' . $template . '.html';
        return parent::fetch($template);
    }



}