<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/5/12
 * Time: 17:32
 */

namespace app\erp\controller;


class EasyuiField
{
    public $field_list=[];

    public  $field_obj;


    public function createField($field,$name){
        $this->field_obj['field']=$field;
        $this->field_obj['name']=$name;

        return $this;
    }
    //设置字段编辑类型
    public function setInputText(){
        $this->field_obj['input'] = "text";
        return $this;
    }
    //设置字段编辑类型
    public function setInputInt(){
        $this->field_obj['input'] = "int";
        return $this;
    }
    public function setInputWidth($width){
        if (is_numeric($width)){
            $this->field_obj['input']['style'] = "width:".$width;
        }
        return $this;
    }

    public function complete(){
        $this->field_list[$this->field_obj["field"]]=$this->field_obj;
        $this->field_obj =  [];

        return $this;
    }

}