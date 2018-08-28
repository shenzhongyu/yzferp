<?php
/**
 * Created by PhpStorm.
 * Power By Mikkle
 * Email：776329498@qq.com
 * Date: 2017/8/4
 * Time: 15:06
 */

namespace app\wechat\controller;


use app\base\model\office\OfficeVehicle;
use app\base\model\office\OfficeVehicleApply;
use app\base\model\PersonnelCompany;
use app\base\model\PersonnelDepartment;
use app\base\model\PersonnelJobs;
use app\base\model\PersonnelRole;
use app\base\model\PersonnelUser;
use app\base\model\Project as ProjectModel;
use app\erp\config\FieldValue;


class Human extends Auth
{
    // 车辆录入
    public function addCar()
    {
        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        $data = [
            "title" => "车辆录入",
            "url" => "erp/office_vehicle/addofficevehicle",
        ];
        $this->assign($data);
        return $this->fetch("human/add_car");
    }

    //车辆列表
    public function carList()
    {
        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        $data = [
            "url" => "erp/office_vehicle/showvehiclelist",
        ];
        $this->assign($data);
        return $this->fetch("human/car_list");
    }

    //车辆详情
    public function applyCar()
    {
        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        $guid = $this->request->param("guid");
        if (empty($guid)) {
            $this->error("参数错误");
        }
        $model = new OfficeVehicle();
        if ($this->request->isPost()) {
            $data = $model->getInfoByGuId($guid);
            return $data;
        }
        $this->assign("guid", $guid);
        return $this->fetch("human/apply_car");
    }

    //用车申请
    public function applyCarDetails()
    {
        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        $guid = $this->request->param("guid");
        if (empty($guid)) {
            $this->error("参数错误");
        }
        $model = new PersonnelDepartment();
        $info = $this->member_info["name"];
        $department = $model->getInfoByGuId($this->member_info["department_id"]);

        $this->assign("info", $info);
        $this->assign("guid", $guid);
        $this->assign("department", $department["department_name"]);

        return $this->fetch("human/apply_car_details");

    }
    //用车审核列表页
    public function carPending(){
        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
       $url="erp/office_vehicle/showapplyvehiclelist";
        $this->assign("url",$url);
        return $this->fetch("human/car_pending");
    }
   // 用车审核详情页
     public function carPendingDetails(){
         if (!$this->uuid) {
             $this->redirect("index/myError");
         }
        $guid=$this->request->param("guid");
        if(empty($guid)){
            $this->error("参数错误");
        }
        if($this->request->isPost()){
            $car_model=new OfficeVehicle();
            $member_model=new PersonnelUser();
            $department_model = new PersonnelDepartment();
            $model= new OfficeVehicleApply();
            $data=$model->getInfoByGuId($guid);
            $data["name"]=$member_model->getInfoByGuId($data['uuid']);
            $data["department_name"]=$department_model->getInfoByGuId( $data["name"]["department_id"])["department_name"];
            $data["car_num"]=$car_model->getInfoByGuId( $data["vehicle_id"])["number"];
            $data=$model->getTimeHours($data,"start_time",false);
            $data=$model->getTimeHours($data,"end_time",false);
            return $data;
        }

        $this->assign("guid",$guid);
        return $this->fetch("human/car_pending_details");


     }
    //员工录入
    public function addPeople(){
        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        if($this->request->isPost()){
            $modelCompany=new PersonnelCompany();
            $list=$modelCompany->getListCom(["guid"=>$this->member_info->company_id,'status'=>1]);
            $modelDepartment=new PersonnelDepartment();
            $arr=$modelDepartment->getListDep(['company_id'=>$this->member_info->company_id,'status'=>1]);
            $data =[
                "company"=>$list,
                "department"=>$arr,
            ];
            return $data;
        }
        return $this->fetch("add_people");
    }
    //办公用品录入
    public function  addOfficeSupplies(){
        if(!$this->uuid){
            $this->redirect("index/myError");
        }
        return$this->fetch("add_office_supplies");
    }
    //员工列表 fyqua
    public function personnerList(){
        if(!$this->uuid){
            $this->redirect("index/myError");
        }
        $url="erp/work/shownorolebyuser";
        $this->assign("url",$url);
        return$this->fetch("personner_list");
    }
    //员工权限编辑详情
    public function personnerPower(){
        if(!$this->uuid){
            $this->redirect("index/myError");
        }
        $uuid=$this->request->param("uuid");
        if($this->request->isPost()){

            $model=new PersonnelUser();
            $dep_model = new PersonnelDepartment();
            $job_model = new PersonnelJobs();

            $data=$model->where(["uuid"=>$uuid,"company_id"=>$this->member_info->company_id])->find();
            if(empty($data["department_id"]||$data["jobs_id"]||$uuid)){
                return self::showJsonReturnCode(1003);
            }
            $dep = $dep_model->where(["guid"=>$data["department_id"],"status"=>1])->value("department_name");
            $job= $job_model->where(["guid"=>$data["jobs_id"],"status"=>1])->value("jobs_name");
            $roledoel= new PersonnelRole();
            $role=$roledoel->getList(["company_id"=>$this->member_info->company_id]);
            $details=[
                "details"=>$data,
                "role"=>$role,
                "dep"=>$dep,
                "job"=>$job

            ];
            return $details;
        }
        $this->assign("uuid",$uuid);
        return $this->fetch("personner_power");
    }
    //

    //车辆列表
    public function carManagelist()
    {
        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        $data = [
            "url" => "erp/office_vehicle/showvehiclelist",
        ];
        $this->assign($data);
        return $this->fetch("human/car_manage_list");
    }

    //车辆详情
    public function carManageDetails()
    {
        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        $guid = $this->request->param("guid");
        if (empty($guid)) {
            $this->error("参数错误");
        }
        $model = new OfficeVehicle();
        if ($this->request->isPost()) {
            $data = $model->getInfoByGuId($guid);
            return $data;
        }
        $this->assign("guid", $guid);
        return $this->fetch("human/car_manage_details");
    }
    //用车记录
    public function carManageRecord(){
        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        $guid = $this->request->param("guid");
        $this->assign("guid",$guid);
            return $this->fetch("human/car_manage_record");
    }

}