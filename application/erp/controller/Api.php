<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/5/19
 * Time: 11:27
 */

namespace app\erp\controller;


use app\erp\config\FieldValue;
use app\erp\config\ListUrl;
use think\Controller;

class Api extends Controller
{
    public function showFieldJson($name){
        $field_value = new FieldValue();
        if (isset($field_value->$name)){
            $array=[];
            foreach ($field_value->$name as $item => $value){
                $array[] = ['text'=>$value,'value'=>$item];
            }
            return json($array) ;
        }
        else{
            return "{}";
        }
    }

    public function showFieldJsonByUrl($name){
        $list_url = new ListUrl();
        if (isset($list_url->$name)){
            $label= $list_url->$name;
            $list =  model($label['model_name'])->getEasyUilist($label['field_id'],$label['field_text']);
            $array=[];
            foreach ($list as $item => $value){
                $array[] = ['text'=>$value,'value'=>$item];
            }
            return json($array) ;
        }
    }






}