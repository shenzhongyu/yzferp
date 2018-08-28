<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/4/17
 * Time: 15:51
 */

namespace app\base\model;


use app\erp\config\FieldValue;
use think\Db;
use think\Exception;
use think\Request;

class MaterialListEdit extends BaseHash
{

    protected $table = "mk_material_list_edit";
    protected $insert = ['status'=>1,'guid'];
    protected $autoWriteTimestamp = false;
    protected $hashKey="guid";

    protected function setGuidAttr($value, $data)
    {
        do {
            $mk_guid= $this->create_uuid();
        } while ($this->where('guid',$mk_guid)->count()==1);
        return $mk_guid ;
    }

    public function getUnitAttr($value, $data){

         //$array= (new FieldValue())->unit_name;
        $array=FieldValue::getFieldValue("unit_name");
        return isset($array[$value]) ? $array[$value] : $value;
    }

    public function getList($map = [],$field=false){
        if (empty($map)){
            $map = ['status'=>1];
        }else{
            $map['status'] = 1 ;
        }
        if (empty($field)){
            $field = '';
        }
        return $this->where($map)->field($field)->select();
    }


    public function saveDate($data){
        try{
            $this->startTrans();
            if(isset($data['id'] ) || isset($data['guid'])){
                if(!empty($data['guid'])){
                    unset($data['serial_number']);
                    $guid=$data['guid'];
                    unset($data['id']);
                    unset($data['guid']);
                    $this->data($data)->isUpdate(true,['guid'=>$guid])->save();
                }
            }else{
                $this->data($data)->isUpdate(false)->save();
            }
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }

    }
    public function delData($data){
        try{
            $this->startTrans();
            $this->data(['status'=>-1])->isUpdate(true,['guid'=>$data['id']])->save();
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }




}