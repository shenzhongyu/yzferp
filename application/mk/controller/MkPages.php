<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/4/17
 * Time: 17:39
 */

namespace app\mk\controller;


 class MkPages extends Mk
{
    private $_config =[] ;
    private $_keyList = [];
    private $_buttonList = [];
    private $_pagination = [];
    private $_data = [];
    private $_db = '';

    private $_statusUrl = "setStatus"; // 状态修改连接
    private $_deleteTrueUrl = "delete"; // 彻底删除连接



    public function setConfigValue($name,$value) {
        $this->_config[$name] = $value;
        return $this;
    }

    /**
     * 批量追加Config数据
     * Power by Mikkle
     * QQ:776329498
     * @param array $array
     * @return $this
     */
    public function appendConfig($array=[]){
        $this->_config = array_merge($this->_config,$array);
        return $this;
    }

    /**
     * 设置model名称
     * Power by Mikkle
     * QQ:776329498
     * @param $value
     * @return $this
     */
    public function setModelName($value){
        $this->_config['model_name'] = $value;
        return $this;
    }
    public function setValidateName($value){
        $this->_config['validate_name'] = $value;
        return $this;
    }

    public function setTitle($value) {
        $this->_config['title'] = $value;
        return $this;
    }

    public function setSuggest($suggest) {
        $this->_config['suggest'] = $suggest;
        return $this;
    }

    public function setKeyToList($field,$name,$type,$data){
        $this->_keyList[$field]=['name'=>$name,'type'=>$type,'data'=>$data];
        return $this;
    }

     public function keyText($field,$name){
          return $this->setKeyToList($field,$name,'text',[]);
     }

     public function keySelectValue($field,$name,$value=["0"=>"否","1"=>"是"]){
         return $this->setKeyToList($field,$name,'select',$value);
     }

     public function keySelectByUrl($field,$name,$url=""){
         return $this->setKeyToList($field,$name,'select_url',$url);
     }

     





}