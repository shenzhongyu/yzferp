<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/22
 * Time: 14:27
 */

namespace app\base\model;


use app\erp\config\FieldValue;
use think\Exception;

class ProjectRelatedPerson extends Base
{
    protected $table = "mk_project_related_person";
    protected $insert = [];
    protected $autoWriteTimestamp = true;

    protected function setGuidAttr($value, $data)
    {
        do {
            $mk_guid= $this->create_uuid();
        } while ($this->where('guid',$mk_guid)->count()==1);
        return $mk_guid ;
    }


    public function getFind($map = [],$field=false){
        if (empty($map)){
            $map = [];
        }else{

        }
        if (empty($field)){
            $field = '';
        }
        return $this->where($map)->field($field)->find();
    }


    public function getList($map = [],$field=false){
        if (empty($map)){
            $map = [];
        }else{
        }
        if (empty($field)){
            $field = '';
        }
        return $this->where($map)->field($field)->select();
    }
    public function getCont($map = []){
        if (empty($map)){
            $map = [];
        }
        return $this->where($map)->count();
    }

    public function saveProjectRelatedPerson($data){
        $department_type=array_search("设计部",FieldValue::getFieldValue("department_type"))?:3;
        try{
            $this->startTrans();
            $info = isset($data["related_uuid"]) ? $data["related_uuid"] : null;
            if (empty($data["guid"])) throw new Exception("设计师分配失败");
            if (empty($info)) throw new Exception("分配设计师失败");
            $this->where(['project_id'=>$data["guid"],'department_type'=>$department_type])->delete();
            if(!empty($info)){
                if(is_array($info)){
                    foreach($info as $array){
                        $save_data = [
                            'uuid'=>$array,
                            'project_id'=>$data["guid"],
                            'company_id'=>$data["company_id"],
                            'department_type'=>$department_type, // 3 代表设计部
                        ];
                        $this->data($save_data)->isUpdate(false)->save();
                    }
                }else{
                    $save_data = [
                        'uuid'=>$info,
                        'project_id'=>$data["guid"],
                        'company_id'=>$data["company_id"],
                        'department_type'=>$department_type, // 3 代表设计部
                    ];
                    $this->data($save_data)->isUpdate(false)->save();
                }
            }
            $this->commit();
            return ['code' => '1001', 'msg' => '设计师分配成功'];
        }catch (Exception $e){

            $this->rollback();
            return ['code'=>'1008','msg'=>$e->getMessage()];
        }
    }

    public function delProjectRelatedPerson($data){
        $department_type=array_search("设计部",FieldValue::getFieldValue("department_type"))?:3;
        if(empty($data["id"])){
            return ["code"=>"1010","msg"=>'解除设计师分配错误!'];
        }else{
            $re=$this->where(['project_id'=>$data["id"],'department_type'=>$department_type])->delete();
            if($re=="0"){
                return ['code' => '1002', 'msg' => '未分配设计师'];
            }
            return ['code' => '1001', 'msg' => '解除分配成功'];
        }
    }
}