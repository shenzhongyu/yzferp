<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/4/25
 * Time: 10:21
 */

namespace app\base\model;


use think\Cache;

class PersonnelDepartment extends BaseHash
{
    protected $table = "mk_personnel_department";
    protected $insert = ['status'=>1,'guid'];
    protected $autoWriteTimestamp = true;
    protected $hashKey="guid";

    protected function setGuidAttr($value, $data)
    {
        do {
            $mk_guid= $this->create_uuid();
        } while ($this->where('guid',$mk_guid)->count()==1);
        return $mk_guid ;
    }

    public function getList($map = [],$field=false){
        if (empty($map)){
            $map = ['status'=>1];
        }else{
            $map['status']=1;
        }
        if (empty($field)){
            $field = 'guid as department_id,department_name,department_type';
        }
        return $this->where($map)->field($field)->select();

    }
    public function getListDep($map = [],$field=false){
        if (empty($map)){
            $map = ['status'=>1];
        }else{
            $map['status']=1;
        }
        if (empty($field)){
            $field = 'guid ,department_name';
        }
        return $this->where($map)->field($field)->select();

    }


    public function getDepartmentName($value, $data)
    {
        $department_id = $data['guid'];
        if (Cache::has('department_name_'.$department_id)){
            return Cache::get('member_department_name_'.$department_id);
        }else{
            $department_name = $this->where(['guid'=>$department_id])->value('department_name');
            Cache::set('member_department_name_'.$department_id,$department_name);
            return $department_name;
        }
    }






}