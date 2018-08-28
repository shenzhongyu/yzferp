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
use think\Loader;

class ProjectAudit extends Base
{
    protected $table = "mk_project_audit";
    protected $insert = ['guid','status'=>1];
    protected $autoWriteTimestamp = true;

    protected function setGuidAttr($value, $data)
    {
        do {
            $mk_guid= $this->create_uuid();
        } while ($this->where('guid',$mk_guid)->count()==1);
        return $mk_guid ;
    }


    protected function getProjectNameAttr($value, $data){
//        $system = $this->setCacheProjectData();
//        if(!isset($data["project_id"])) return "未知项目";
//        if(isset($system["project"][$data["project_id"]])){
//            return $system["project"][$data["project_id"]];
//        }else{
//            return $data["project_id"];
//        }
        if(!isset($data["project_id"])) return "未知项目";
        $return = $this->table("mk_project")->where(["guid"=>$data["project_id"]])->cache()->value("project_name");
        return empty($return) ? "未知项目" :$return ;

    }

    protected function getTransferNameAttr($value, $data){

        if(!isset($data["transfer_uuid"])) return "未知操作人";
        $return = $this->table("mk_personnel_user")->where(["uuid"=>$data["transfer_uuid"]])->cache()->value("name");
        return empty($return) ? "未知项目" :$return ;

    }
    protected function getDepartmentNameAttr($value, $data){
        if(!isset($data["transfer_uuid"])) return "未知部门";
        $system = $this->setCacheSystem();

        if(isset($system["department"][$system["user"][$data["transfer_uuid"]]["department_id"]])){
            return
                isset($system["department"][$system["user"][$data["transfer_uuid"]]["department_id"]]["department_name"])
                    ? $system["department"][$system["user"][$data["transfer_uuid"]]["department_id"]]["department_name"]
                    : "未知部门";
        }else{
            return "未知部门";
        }
    }


    public function getList($map = [],$field=false){
        if (empty($map)){
            $map = [];
        }
        if (empty($field)){
            $field = '';
        }
        return $this->where($map)->field($field)->select();


    }


    protected function getTransferStatusNameAttr($value, $data)
    {
        $value=[1=>"转部中",2=>"转部失败",0=>"处于转部中"];
        return  isset($value[$data["transfer_status"]]) ? $value[$data["transfer_status"]] : $data["transfer_status"];
    }
    protected function getExamineStatusNameAttr($value, $data)
    {
        $value=[0=>"正在审核",1=>"审核通过",-1=>"未通过",];
        return  isset($value[$data["examine_status"]]) ? $value[$data["examine_status"]] : $data["examine_status"];
    }

    public function getDataByProject($map = [],$field=false){
        if (empty($map)){
            $map = [];
        }else{
            
        }
        if (empty($field)){
            $field = '';
        }
        return $this->where($map)->field($field)->limit(1)->order(["create_time"=>'desc'])->select();


    }
    public function del($guid){
        try{
            $this->startTrans();
            $this->data(['status'=>-1])->isUpdate(true,["project_id"=>$guid,'transfer_status'=>1,'examine_status'=>0])->save();
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
        
    }

    public function addProjectAudit($data){  //添加转部信息
        if (empty($data)) return self::showReturnCode(1003);
        $model=new PersonnelDepartment();
        $department=$model->getInfoByGuId($data['into_department_id']);
//        $department=PersonnelDepartment::quickGet($data['into_department_id']);
        if (empty($department)) return self::showReturnCode(1003,[],'转入的部门不存在');
        $data['into_department_name']=$department['department_name'];
        $obj=$this->where(['project_id'=>$data['project_id'],'status'=>1,'transfer_type'=>$data['transfer_type'],'examine_status'=>0])->find();
        if (!empty($obj)) return self::showReturnCode(1003,[],'该项目已申请转部');
        try{
            $this->startTrans();
            $this->data($data)->isUpdate(false)->save();
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }
    public function editProjectAudit($data,$guid){
        if (empty($data)) return self::showReturnCode(1003);

        if (empty($guid)) return self::showReturnCode(1003);

        $arr=[
            "examine_status"=>$data['examine_status'],
            "examine_desc"=>$data["examine_desc"],
            'examine_uuid'=>$data['uuid'],
            'transfer_status'=>'2' //已经申请过的
        ];
        if (!in_array($data['examine_status'],['-1','0','1'])){
            return self::showReturnCode(1003);
        };
        $model=new Project();
        $model_re=new ProjectRelatedPerson();
        try{
            $this->startTrans();
            $re=$this->data($arr)->isUpdate(true,['guid'=>$guid,'examine_status'=>0])->save();
            if ($re!=1){
                $this->throwException("审核失败，该审核信息已变动");
            }
            if($data['examine_status']=="1"){
                $depart=$this->where(['guid'=>$guid])->find();
                $model->data(['design_department'=>$depart['into_department_id'],"feedback_stage"=>'2'])->isUpdate(true,['guid'=>$depart['project_id']])->save();
                $cont["project_id"]=$depart['project_id'];
                $pro_data=$model->getPerByGuid($depart['project_id']);
                $cont["company_id"]=$pro_data["company_id"];
                $cont["uuid"]=$pro_data["uuid"];
                $model_dep=new PersonnelDepartment();
                $dep=$model_dep->getInfoByGuId($pro_data["department_id"]);
                $cont["department_type"]=$dep["department_type"];
                $model_re->data($cont)->isUpdate(false)->save();
                $project=$model->getInfoByGuId($depart['project_id']);
//                $project=Project::quickGet($depart['project_id']);
                $type=empty($project)?"1":$project['project_type'];
                Loader::model('base/project/ProjectSuccessTime')->saveTime($depart['project_id'],$type);
            }
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }
}