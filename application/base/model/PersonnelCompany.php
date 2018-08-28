<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/4/25
 * Time: 10:21
 */

namespace app\base\model;


class PersonnelCompany extends  BaseHash
{
    protected $table = "mk_personnel_company";
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
        }
        if (empty($field)){
            $field = 'guid as id,company_name as text';
        }
        return $this->where($map)->field($field)->select();

    }

    public function getListCom($map = [],$field=false){
        if (empty($map)){
            $map = ['status'=>1];
        }else{
        }
        if (empty($field)){
            $field = 'guid ,company_name ';
        }
        return $this->where($map)->field($field)->select();
    }





}