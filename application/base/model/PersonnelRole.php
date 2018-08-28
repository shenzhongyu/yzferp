<?php

/**

 * Created by PhpStorm.

 * Power by Mikkle

 * QQ:776329498

 * Date: 2017/4/25

 * Time: 10:21

 */



namespace app\base\model;





class PersonnelRole extends BaseHash

{

    protected $table = "mk_personnel_role";

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

    public function addRoleData($data){
        if (empty($data)) return self::showReturnCode(1003);
        try{
            $this->startTrans();
            if (isset($data['guid'])){
                $this->data(['role_name'=>$data['role_name'],'role_desc'=>$data['role_desc']])->isUpdate(true,['guid'=>$data['guid']])->save();
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
    public function delRoleData($data){
        if (empty($data)) return self::showReturnCode(1003);
        if (!isset($data['id'])) return self::showReturnCode(1003);
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