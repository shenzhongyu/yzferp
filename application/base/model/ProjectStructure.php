<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/22
 * Time: 14:27
 */

namespace app\base\model;


use app\erp\config\FieldValue;

class ProjectStructure extends Base
{
    protected $table = "mk_project_structure";
    protected $insert = ['guid','status'=>1];
    protected $autoWriteTimestamp = true;
    protected $field=[""];

    protected function setGuidAttr($value, $data)
    {
        do {
            $mk_guid= $this->create_uuid();
        } while ($this->where('guid',$mk_guid)->count()==1);
        return $mk_guid ;
    }


    protected function getLivingRoomStructureNameAttr($value, $data)
    {
        $value=FieldValue::getFieldValue("living_room_structure");
        return  isset($value[$data["living_room_structure"]]) ? $value[$data["living_room_structure"]] : $data["living_room_structure"];
    }

    protected function getHouseOrientationNameAttr($value, $data)
    {
        $value=FieldValue::getFieldValue("house_orientation");
        return  isset($value[$data["house_orientation"]]) ? $value[$data["house_orientation"]] : $data["house_orientation"];
    }
    protected function getBuildingPriceNameAttr($value, $data)
    {
        $value=FieldValue::getFieldValue("building_price");
        return  isset($value[$data["building_price"]]) ? $value[$data["building_price"]] : $data["building_price"];
    }

    protected function getHouseTypeNameAttr($value, $data)
    {
        $value=FieldValue::getFieldValue("house_type");
        return  isset($value[$data["house_type"]]) ? $value[$data["house_type"]] : $data["house_type"];
    }
    protected function getHousingUseNameAttr($value, $data)
    {
        $value=FieldValue::getFieldValue("housing_use");
        return  isset($value[$data["housing_use"]]) ? $value[$data["housing_use"]] : $data["housing_use"];
    }
    protected function getLightingNameAttr($value, $data)
    {
        $value=FieldValue::getFieldValue("lighting");
        return  isset($value[$data["lighting"]]) ? $value[$data["lighting"]] : $data["lighting"];
    }
    protected function getFloorNameAttr($value, $data)
    {
        $value=FieldValue::getFieldValue("floor");
        return  isset($value[$data["floor"]]) ? $value[$data["floor"]] : $data["floor"];
    }
    protected function getlogTypeNameAttr($value, $data)
    {
        $value=FieldValue::getFieldValue("log_type");
        return  isset($value[$data["log_type"]]) ? $value[$data["log_type"]] : $data["log_type"];
    }












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
    }

    


}