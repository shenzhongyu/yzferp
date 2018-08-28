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
use app\base\model\PersonnelDepartment;
use app\base\model\PersonnelUser;
use app\base\model\Project as ProjectModel;
use app\erp\config\FieldValue;
use think\Db;
use think\Loader;


class Working extends Auth
{
    //在建项目
    public function projectOnWorking()
    {
        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        $url = "erp/engin_project/showprojectofuser";
        $this->assign("url", $url);
        return $this->fetch("project_on_working");
    }

    //在建项目类别
    public function projectTypeOnWorking()
    {
        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        $guid = $this->request->param("guid");
        $url = "/erp/build_none/lookbuild";
        if (empty($guid)) {
            $this->error("参数错误");
        }
        $this->assign("guid", $guid);
        $this->assign("url", $url);
        return $this->fetch("project_type_on_working");
    }
    //发包
    public function projectAwardOnWorking()
    {
        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        $pro_id = $this->request->param("pro_id");
        $guid = $this->request->param("guid");

        if (empty($guid)) {
            $this->error("参数错误");
        }
        if (empty($pro_id)) {
            $this->error("参数错误");
        }
        $this->assign("guid", $guid);
        $this->assign("pro_id", $pro_id);

        return $this->fetch("project_award_on_working");
    }

    //项目明细

    public function projectDetailsOnWorking()
    {
        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        $pro_id = $this->request->param("pro_id");
        $guid = $this->request->param("guid");

        if (empty($guid)) {
            $this->error("参数错误");
        }
        if (empty($pro_id)) {
            $this->error("参数错误");
        }
        $this->assign("guid", $guid);
        $this->assign("pro_id", $pro_id);

        return $this->fetch("project_details_on_working");
    }

    //待建项目列表
    public function projectBeforeWorking()
    {
        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        $url = "erp/engin_project/erp/build_none/showbuildprojectlist";
        $this->assign("url", $url);
        return $this->fetch("project_before_working");
    }
    //待建项目工程分类
    public function projectTypeBeforeWorking()
    {
        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        $guid = $this->request->param("guid");
        if (empty($guid)) {
            $this->error("参数错误");
        }
        $this->assign("guid", $guid);
        if ($this->request->isPost()) {
            $model = new ProjectModel;
            $obj = Loader::model('base/build/BuildProjectTime')->where(['project_id' => $guid, 'status' => 1])->find();
            if (empty($obj['build_time'])) {
                $obj['build_end_time'] == "";
            } else {
                $obj['build_end_time'] = date("Y-m-d ", $obj['build_time'] + $obj['build_days'] * 60 * 60 * 24);
                $obj['build_time'] = date("Y-m-d ", $obj['build_time']);
            }

            $obj['address'] = $model->getInfoByGuId($guid)["project_address"];

            return $obj;
        }
        return $this->fetch("project_type_before_working");
    }
    //设置施工日期
    public function projectTimeBeforeWorking()
    {
        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        $guid = $this->request->param("guid");
        $model = new ProjectModel;
        $project_name = $model->getInfoByGuId("$guid")["project_name"];
        $this->assign("guid", $guid);
        $this->assign("project_name", $project_name);
        return $this->fetch("project_time_before_working");
    }
    //项目日志
    public function projectLogWorking()
    {
        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        $guid = $this->request->param("guid");
        $this->assign("guid", $guid);
        return $this->fetch("project_log_working");
    }
    //完工工程列表
    public function peojectFinishWorking()
    {
        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        $url = "erp/engin_project/showprojectsuccess";
        $this->assign("url", $url);
        return $this->fetch("project_finish_working");
    }
    //完工工程详情
    public function projectTypeFinish()
    {
        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        $guid = $this->request->param("guid");
        $url = "/erp/build_none/lookbuild";
        if (empty($guid)) {
            $this->error("参数错误");
        }
        $this->assign("guid", $guid);
        $this->assign("url", $url);
        return $this->fetch("project_type_finish");
    }
    //公司自营
    public function projectToOwn()
    {
        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        $category_id=$this->request->param("category_id");
        $guid = $this->request->param("guid");
        $model = new ProjectModel;
        $project_name = $model->getInfoByGuId("$guid")["project_name"];
        if ($this->request->isPost()){
            $department_type=array_search("工程部",FieldValue::getFieldValue("department_type"))?:4;
            $list=$this->showEasyUiJsonList("base/PersonnelUser",false,['company_id'=>$this->member_info->company_id,'department_type'=>$department_type]);
            return $list;
        }
        $this->assign("category_id",$category_id);
        $this->assign("guid", $guid);
        $this->assign("project_name", $project_name);
        return $this->fetch("project_to_own");
    }
    public function yijuhua(){

    }
}