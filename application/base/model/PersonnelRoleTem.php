<?php

/**

 * Created by PhpStorm.

 * Power by Mikkle

 * QQ:776329498

 * Date: 2017/4/25

 * Time: 10:21
 * 角色权限模板

 */



namespace app\base\model;

class PersonnelRoleTem extends BaseHash{

    protected $table = "mk_personnel_role_tem";

    protected $insert = ['status'=>1,'guid'];

    protected $hashKey="guid";
    protected $autoWriteTimestamp = true;



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

            $map['status'] = 1 ;

        }
        if (empty($field)){

            $field = 'guid as role_id,role_name';

        }

        return $this->where($map)->field($field)->select();



    }
}