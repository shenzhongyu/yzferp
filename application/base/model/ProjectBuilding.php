<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/22
 * Time: 14:27
 */

namespace app\base\model;


use app\erp\config\FieldValue;

class ProjectBuilding extends Base
{
    protected $table = "mk_project_building";
    protected $insert = ['guid','status'=>1];
    protected $autoWriteTimestamp = true;

    protected function setGuidAttr($value, $data)
    {
        do {
            $mk_guid= $this->create_uuid();
        } while ($this->where('guid',$mk_guid)->count()==1);
        return $mk_guid ;
    }


    protected function getBuildingPriceNameAttr($value, $data){
        $array=FieldValue::getFieldValue("building_price");

        return isset($array[$data["building_price"]]) ? $array[$data["building_price"]] : $data["building_price"];

    }

    protected function getBuildingPriceNameListAttr($value, $data){

        $array=FieldValue::getFieldValue("building_price","array");
        return isset($array) ? $array : [] ;

    }


//    protected function getProjectContactsAttr($value, $data)
//    {
//        return ($this->belongsTo('ProjectBuilding')->where(['project_guid'=>$data['guid']])->find());
//
//
//    }




    public function getList($map = [],$field=false){
        if (empty($map)){
            $map = [];
        }else{
            $map['status'] = 1 ;
        }
        if (empty($field)){
            $field = '';
        }
        return $this->where($map)->field($field)->select();


//        $list_obj = $this->where($map)->field($field)->select();
//
//        $data = [];
//        foreach ($list_obj as $value){
//          //  dump($value);
//            $data = $value->append(['project_contacts'])->toArray();
//        }
//        return $data;

    }





}