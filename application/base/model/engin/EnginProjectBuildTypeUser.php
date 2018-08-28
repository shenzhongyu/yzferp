<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/4/17
 * Time: 15:51
 */

namespace app\base\model\engin;


use app\base\model\Base;
use app\base\model\BaseHash;
use app\base\model\finance\BankTransactionRecord;
use app\base\model\finance\CollectPlanAccess;
use app\base\model\Material;
use app\base\model\PersonnelDepartment;
use app\base\model\PersonnelJobs;
use app\base\model\PersonnelUser;
use app\base\model\Project;
use app\base\model\project\ProjectContract;
use think\Db;
use think\Exception;
use think\Loader;

class EnginProjectBuildTypeUser extends BaseHash
{

    protected $table = "mk_engin_project_build_type_user";
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
    public function getList($map = [],$field=false){
        if (empty($map)){
            $map =['status'=>1];
        }else{
            $map['status']=1;
        }
        if (empty($field)){
            $field = '';
        }
        return  $this->where($map)->field($field)->select();
    }
    public function editNumber($data,$guid){
        if (empty($guid)){
            return self::showReturnCodeWithOutData(1003);
        }
        if (empty($data)){
            return self::showReturnCodeWithOutData(1003);
        }
        try{
            $this->startTrans();
            $this->data($data)->isUpdate(true,['guid'=>$guid])->save();
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }
    public function addBuildUuid($data){
        if (empty($data)){
            return self::showReturnCodeWithOutData(1003);
        }
        if (!isset($data['guid'])){
            return self::showReturnCodeWithOutData(1003);
        }
        if (!isset($data['appoint_uuid'])){
            return self::showReturnCodeWithOutData(1003);
        }
        if (! is_array($data['appoint_uuid'])) return self::showReturnCodeWithOutData(1003);
        $model_type=new EnginProjectBuildType();
        $pro=$model_type->getInfoByGuId($data['guid']);
//        $pro=EnginProjectBuildType::quickGet($data['guid']);
        $model=new Project();
        $project_name=$model->getInfoByGuId($pro['project_id']);
//        $project_name=Project::quickGet($pro['project_id']);
        $dep=new PersonnelDepartment();
        try{
            $this->startTrans();
            foreach ($data['appoint_uuid'] as $value){
                $this->where(['build_uuid'=>$value])->delete(); //删除重复数据
                $obj=Loader::model('base/PersonnelUser')->where(['uuid'=>$value])->find();
                $department=$dep->getInfoByGuId($obj['department_id']);
                //$department=PersonnelDepartment::quickGet($obj['department_id']);
                $jobs=Loader::model('base/PersonnelJobs')->where(['guid'=>$obj['jobs_id']])->find();;
                $arr=[
                    'build_type_id'=>$data['guid'],
                    'build_uuid'=>$value,
                    'project_id'=>$pro['project_id'],
                    'project_name'=>$project_name['project_name'],
                    'build_uuid_department'=>$department['department_name'],
                    'build_uuid_jobs'=>$jobs['jobs_name'],
                    'mobile'=>$obj['mobile'],
                    'build_uuid_name'=>$obj['name'],
                    'type'=>0
                ];
                $this->data($arr)->isUpdate(false)->save();
            }
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }
    public function delUser($data){
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

    public function changChargeUser($data){
        if (empty($data)) return self::showReturnCode(1003);
        if (!isset($data['id'])) return self::showReturnCode(1003);
        try{
            $this->startTrans();
            $this->data(['type'=>1])->isUpdate(true,['guid'=>$data['id']])->save();

            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }
}