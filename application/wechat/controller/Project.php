<?php
/**
 * Created by PhpStorm.
 * Power By Mikkle
 * Email：776329498@qq.com
 * Date: 2017/8/4
 * Time: 15:06
 */

namespace app\wechat\controller;

use app\base\model\budget\BudgetBookNumber;
use app\base\model\budget\PrintRequest;
use app\base\model\budget\ReviseBudget;
use app\base\model\BudgetDefaultProject;
use app\base\model\build\BuildProjectPercentage;
use app\base\model\DesignBudget;
use app\base\model\DesignPhase;
use app\base\model\engin\EnginProjectBuildDate;
use app\base\model\engin\EnginProjectBuildTypeAudit;
use app\base\model\engin\EnginProjectBuildTypeDay;
use app\base\model\engin\EnginProjectBuildTypeDayAccess;
use app\base\model\engin\EnginProjectCompletionAudit;
use app\base\model\engin\EnginProjectMaterial;
use app\base\model\engin\EnginProjectMaterialAddress;
use app\base\model\engin\EnginProjectMaterialApply;
use app\base\model\MaterialDataStyle;
use app\base\model\OfficeComplaint;
use app\base\model\OfficePlanLog;
use app\base\model\personal\UserOpus;
use app\base\model\PersonnelUser;
use app\base\model\project\ProjectCollectPrice;
use app\base\model\project\ProjectCollectPriceRefund;
use app\base\model\project\ProjectContract;
use app\base\model\project\ProjectDepositAudit;
use app\base\model\project\WasteApply;
use app\base\model\ProjectAudit;
use app\base\model\ProjectContacts;
use app\base\model\ProjectStructure;
use app\base\model\ProjectBuilding;
use app\base\model\ProjectRemindTime;
use app\base\model\ProjectTraceLog;
use app\base\model\waste_single\WasteProjectApply;
use app\erp\config\Config;
use app\erp\config\FieldValue;
use app\base\model\Project as ProjectModel;
use app\erp\controller\BaseEasyUI;
use mikkle\tp_wechat\support\Db;
use think\Loader;


class Project extends Auth
{
    public function addProject()
    {


        if ($this->request->isPost()) {
            if (!$this->uuid) {
                return self::showReturnCodeWithOutData(1004);
            }
            $validate_name = 'base/Project.edit';
            $model_name = 'base/Project';
            $save_data = $this->request->post();
            $save_data['guid'] = $this->request->param("id");
            $re = $this->doModelAction($save_data, $validate_name, $model_name, 'saveInfoByGuid');
            if ($re["code"] == "1001") {
                $model = new ProjectTraceLog();
                $log = [];
                $log["log_content"] = "修改项目信息";
                $log["guid"] = $this->request->param('project_guid');
                $log["uuid"] = $this->uuid;
                $log["jobs_id"] = $this->member_info->jobs_id;
                $log["department_id"] = $this->member_info->department_id;
                $model->addProjectLog($log);
            }
            return json($re);

        } else {
            if (!$this->uuid) {
                return self::showJsonReturnCodeWithOutData(1004);
            }
            $model = new FieldValue();

            $assign_data = [
                'data' => $model->decoration_grade,
                'dataa' => $model->color_orientation,
                'datab' => $model->decoration_style,
                'datac' => $model->decoration_type,
                'datad' => $model->customer_source,
                'datae' => $model->sex_value,
                'dataf' => $model->householder_relation,
            ];
            $this->assign($assign_data);
            return $this->fetch("addProject");
        }


    }

    //拍照上传
    public function photoUpload()
    {

        return $this->fetch("project/photoUpload");

    }

    public function projectPending()
    {

        $guid = $this->request->param('guid');
        $this->assign("guid", $guid);
        return $this->fetch("projectPending");

    }

    public function transferRequest()
    {
        $guid = $this->request->param('guid');
        $this->assign("guid", $guid);
        return $this->fetch("transferRequest");

    }

    //图片上传
    public function addFile()
    {
        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        $guid = $this->request->param('guid');
        $this->assign("project_guid", $guid);
        return $this->fetch("addFile");

    }

    public function seePhoto()
    {
        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        $guid = $this->request->param('guid');
        $this->assign("project_guid", $guid);
        return $this->fetch("seePhoto");

    }

    //文件上传
    public function addFileFile()
    {
        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        $guid = $this->request->param('guid');
        $this->assign("project_guid", $guid);
        return $this->fetch("addFileFile");

    }

    //转部申请
    public function transferDetails()
    {

        $data["guid"] = $this->request->param('guid/s');


        if ($this->request->isPost()) {
            if (!$this->uuid) {
                return self::showReturnCodeWithOutData(1004);
            }
            $model = new ProjectAudit();
            $map = ["company_id" => $this->member_info->company_id];
            $map["project_id"] = $data["guid"];
            $append = ["project_name", "transfer_name", "department_name",
                "transfer_status_name", "examine_status_name"];
            $list = $model->getListByMap($map, true, $append, false);
            if ($list) {
                return self::showJsonReturnCode(1001, $list);
            } else {
                return self::showJsonReturnCodeWithOutData(1010);
            }

        } else {
            $this->assign($data);

            return $this->fetch("transferDetails");
        }

    }

//转部审核详情页
    public function pendingDetails()
    {

        $data["guid"] = $this->request->param('guid/s');
        $data["id"] = $this->request->param('id/n');


        if ($this->request->isPost()) {
            if (!$this->uuid) {
                return self::showReturnCodeWithOutData(1004);
            }
            $model = new ProjectAudit();
            $map = ["company_id" => $this->member_info->company_id, 'examine_status' => '0', 'transfer_status' => '1', 'transfer_type' => '1'];
            $map["id"] = $data["id"];
            $append = ["project_name", "transfer_name", "department_name"];
            $info = $model->getInfoByMap($map, true, $append, false);
            if ($info) {
                return self::showJsonReturnCode(1001, $info);
            } else {
                return self::showJsonReturnCodeWithOutData(1010);
            }

        } else {
            $this->assign($data);

            return $this->fetch("pendingDetails");
        }

    }

//费单审核列表(客服部门)
    public function projectWrongPendingList()
    {

        if (!$this->uuid) {
            $this->redirect("index/myError");
        }


        return $this->fetch("projectWrongPendingList");
    }

    //费单审核列表（业务部门）
    public function projectWrongPendingListBusiness()
    {

        if (!$this->uuid) {
            $this->redirect("index/myError");
        }


        return $this->fetch("projectWrongPendingListBusiness");
    }

//项目列表页（部门）
    public function projectList()
    {

        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        $ass = [
            'data_url' => url('erp/system/projectsumlist'),
            "as" => "部门",
        ];

        $this->assign($ass);
        return $this->fetch("projectListBusiness");
    }


    //业务部
    //项目列表页（个人）
    public function projectListPersion()
    {

        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        $ass = [
            'data_url' => url('erp/project/showprojectuserlist'),
            'as' => "个人"
        ];
        $this->assign($ass);
        return $this->fetch("projectListBusiness");
    }

    //项目列表页（公司）
    public function projectListConpany()
    {

        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        $ass = [
            'data_url' => url('erp/project/showprojectcomlist'),
            'as' => "公司"
        ];
        $this->assign($ass);
        return $this->fetch("projectListBusiness");
    }


    //项目列表页（部门）
    public function projectListDepartant()
    {

        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        $ass = [
//            'data_url'=>url('erp/project/showprojectdeplist'),
            'data_url' => url('erp/project/showprojectdeplist'),
            'as' => "部门",

        ];
        $this->assign($ass);
        return $this->fetch("projectListBusiness");
    }


//客服部
    //项目列表页（个人）
    public function projectListPersionCopy()
    {

        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        $ass = [
            'data_url' => url('erp/project/showprojectuserlistcopy'),
            'as' => "个人"
        ];
        $this->assign($ass);
        return $this->fetch("projectListBusiness");
    }

    //项目列表页（公司）
    public function projectListConpanyCopy()
    {

        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        $ass = [
            'data_url' => url('erp/project/showprojectsevcomlist'),
            'as' => "公司"
        ];
        $this->assign($ass);
        return $this->fetch("projectListBusiness");
    }


    //项目列表页（部门）
    public function projectListDepartantCopy()
    {

        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        $ass = [
//            'data_url'=>url('erp/project/showprojectdeplist'),
            'data_url' => url('erp/project/showservicedeplist'),
            'as' => "部门",

        ];
        $this->assign($ass);
        return $this->fetch("projectListBusiness");
    }


    public function projectContacts()
    {
        $guid = $this->request->param('guid');
        if ($this->request->isPost()) {
            if (empty($guid)) {
                return self::showJsonReturnCodeWithOutData(1003, "参数丢失");
            }

            $model = new ProjectContacts();
            $map = [
                "status" => 1,
                "project_guid" => $guid
            ];

            $project_info = $model->getList($map);
            $return_data["sex"] = FieldValue::getFieldValue("sex", "array");
            $return_data["householder_relation"] = FieldValue::getFieldValue("householder_relation", "array");
            $return_data["project_list"] = $project_info;
            return self::showJsonReturnCode(1001, $return_data);


        } else {

            if (empty($guid)) {
                $this->error("参数错误");
            }
            $this->assign("project_guid", $guid);

            return $this->fetch("projectContacts");
        }


    }


//项目提醒信息
    public function projectColl()
    {
        $guid = $this->request->param('guid');
        if ($this->request->isPost()) {
            if (empty($guid)) {
                return self::showJsonReturnCodeWithOutData(1003, "参数丢失");
            }

            $model = new ProjectRemindTime();
            $map = [
                "status" => 1,
                "project_guid" => $guid
            ];
            $project_info = $model->getList($map);

            return json($project_info);
        } else {
            $model = new ProjectRemindTime();
            $map = [
                "status" => 1,
                "project_guid" => $guid
            ];
            $project_info = $model->getList($map);
            if (empty($guid)) {
                $this->error("参数错误");
            }
            $arr = [];
            $arr["name"] = $this->member_info->username;
            $arr["guid"] = $guid;
            $l = new \app\base\model\Project();
            $li = $l->getList(['guid' => $guid]);
            if (!empty($li)) {
                $arr["pro_name"] = $li[0]["project_name"];
            } else {
                $arr["pro_name"] = "";
            }

            if (!empty($project_info)) {
                foreach ($project_info as $value) {
                    $value['remind_time'] = date("Y-m-d H:i:s", $value['remind_time']);
                }
            }
            $this->assign("project_guid", $guid);
            $this->assign("project", $project_info);
            $this->assign("pro", $arr);
//            dump($project_info);
            return $this->fetch("projectColl");
        }


    }

//房屋信息
    public function projectStructure()
    {
        $guid = $this->request->param('guid');
        if ($this->request->isPost()) {
            if (empty($guid)) {
                return self::showJsonReturnCodeWithOutData(1003, "参数丢失");
            }
            $data = [];
            $append = [
                "living_room_structure_name",
                "house_orientation_name",
                "house_type_name",
                "housing_use_name",
                "lighting_name",
            ];
            $data = Loader::model("base/ProjectStructure")->getInfoByMap(["project_guid" => $guid], true, $append);
            if ($data) {

                $return = $data->toArray();
                $return["lighting"] = FieldValue::getFieldValue("lighting", "array");
                $return["living_room_structure"] = FieldValue::getFieldValue("living_room_structure", "array");
                $return["house_orientation"] = FieldValue::getFieldValue("house_orientation", "array");
                $return["house_type"] = FieldValue::getFieldValue("house_type", "array");
                $return["housing_use"] = FieldValue::getFieldValue("housing_use", "array");
                //    dump($return);
                return self::showJsonReturnCode(1001, $return);

            } else {
                $data["lighting"] = FieldValue::getFieldValue("lighting", "array");
                $data["living_room_structure"] = FieldValue::getFieldValue("living_room_structure", "array");
                $data["house_orientation"] = FieldValue::getFieldValue("house_orientation", "array");
                $data["house_type"] = FieldValue::getFieldValue("house_type", "array");
                $data["housing_use"] = FieldValue::getFieldValue("housing_use", "array");

                return self::showJsonReturnCode(1001, $data);
            }

        } else {

            return $this->fetch("projectStructure");
        }
    }


//楼盘信息
    public function projectBuilding()
    {
        $guid = $this->request->param('guid');
        if ($this->request->isPost()) {
            if (empty($guid)) {
                return self::showJsonReturnCodeWithOutData(1003, "参数丢失");
            }
            $append = [
                "building_price_name",
                "building_price_name_list"
            ];
            $map = [
                "status" => 1,
                "project_guid" => $guid
            ];
            $data = Loader::model("base/ProjectBuilding")->getInfoByMap($map, true, $append);
            if ($data) {
                $data->toArray();
                return self::showJsonReturnCode(1001, $data);
            } else {
                $data["building_price_name_list"] = FieldValue::getFieldValue("building_price", "array");
                return self::showJsonReturnCode(1001, $data);
            }

        } else {
            return $this->fetch("projectBuilding");
        }
    }


//日志信息
    public function projectLog()
    {
        $guid = $this->request->param('guid');
        if ($this->request->isPost()) {
            if (empty($guid)) {
                return self::showJsonReturnCodeWithOutData(1003, "参数丢失");
            }
            $map = [
                "status" => 1,
                "project_guid" => $guid
            ];


            $system_info = Loader::model("base/ProjectTraceLog")->setCacheSystem();
            //dump($system_info);
            $data = Loader::model("base/ProjectTraceLog")->getList($map);
            if (is_array($data)) {
                $return_data = [];
                foreach ($data as $item => $value) {


                    $return_data[$item] = $data[$item]->toArray();
                    $return_data[$item]["log_type_name"] = FieldValue::getFieldValue("log_type", "item")[$value['log_type']];
                    //用户姓名

                    $return_data[$item]["name"] = isset($system_info["user"][$data[$item]["uuid"]]) ? $system_info["user"][$data[$item]["uuid"]]->name : "未知用户";
                    //用户职位
                    $return_data[$item]["jobs_name"] = isset($system_info["jobs"][$data[$item]["jobs_id"]]) ? $system_info["jobs"][$data[$item]["jobs_id"]]->jobs_name : "未知职位";
                    //用户部门
                    $return_data[$item]["department_name"] = isset($system_info["department"][$data[$item]["department_id"]]) ? $system_info["department"][$data[$item]["department_id"]]->department_name : "未知部门";
                    unset($return_data[$item]["uuid"]);
                    unset($return_data[$item]["jobs_id"]);
                    unset($return_data[$item]["department_id"]);
                    unset($return_data[$item]["status"]);
                    unset($return_data[$item]["update_time"]);

                }

                return self::showJsonReturnCode(1001, $return_data);
            } else {
                return self::showJsonReturnCodeWithOutData(1010);
            }

        } else {


            $this->assign("member_info", $this->member_info);

            $this->assign("project_guid", $guid);
            return $this->fetch("projectLog");
        }
    }

//项目详情
    public function projectDetails()
    {
        $guid = $this->request->param('guid');
        if (empty($guid)) {
            $this->error("参数错误");
        }
        $this->assignFieldValue();
        $model = new ProjectModel();
        $assign_data = $model->getInfoByGuId($guid)->toArray();
        $assign_data["house"] = $model->getCountObj('base/ProjectStructure', ["project_guid" => $guid]);
        $assign_data["building"] = $model->getCountObj('base/ProjectBuilding', ["project_guid" => $guid]);
        $assign_data["log"] = $model->getCountObj('base/ProjectTraceLog', ["project_guid" => $guid]);
        $assign_data["contacts"] = $model->getCountObj('base/ProjectContacts', ["project_guid" => $guid]);
        $assign_data["time"] = $model->getCountObj('base/ProjectRemindTime', ["project_guid" => $guid]);
        $assign_data["image"] = $model->getCountObj('base/project/ProjectPhoto', ["project_id" => $guid]);
        $assign_data["audit"] = $model->getCountObj('base/ProjectAudit', ["project_id" => $guid]);


        $this->assign("project", $assign_data);
        return $this->fetch("projectDetails");
    }


    public function assignFieldValue()
    {
        $field_value = new FieldValue();
        $this->assign('value', (array)$field_value);
    }

    //待验收项目列表
    public function designerPendingList()
    {
        if (!$this->uuid) {
            $this->redirect("login");
        }
        return $this->fetch("project/designerPendingList");

    }

    //进度申请审核
    public function designerPendingDetails()
    {
        if (!$this->uuid) {
            $this->redirect("login");
        }
        $guid = $this->request->param('guid');
        $this->assign("guid", $guid);
        return $this->fetch("project/designerPendingDetails");

    }


    public function budgetListEdit()
    {
        $guid = $this->request->param('guid');
        if (empty($guid)) return self::showReturnCodeWithOutData(1003);
        $model = new DesignPhase();
        $list = $model->getList(['project_id' => $guid]);
        $arr = [];
        if (!empty($list)) {
            foreach ($list as $value) {
                $obj = Loader::model('base/design/DesignCheck')->where(['sheet_id' => $value['guid'], 'status' => 1])->find();
                if (empty($obj)) {
                    continue;
                }
                if (is_object($obj)) {
                    $obj = $obj->toArray();
                }
                $value['date'] = $value['start_data'] + ($value['expected_days'] * 24 * 60 * 60);
                $value['check_desc'] = $obj;
                $arr[] = $value;
            }
        }
        $arr = $model->getTime($arr, 'start_data', false);
        $arr = $model->getTime($arr, 'completion_date', false);
        $arr = $model->getTime($arr, 'date', false);
        return $arr;
    }

//定金审核列表
    public function moneyPending()
    {
        if (!$this->uuid) {
            $this->redirect("index/login");
        }
        $guid = $this->request->param('guid');
        $this->assign("guid", $guid);
        return $this->fetch("moneyPending");

    }

//定金审核详情
    public function moneyPendingDetails()
    {
        $guid = $this->request->param('guid');
        $this->assign("guid", $guid);
        if (empty($guid)) {
            $this->error("参数错误");
        }

        $this->assignFieldValue();
        $model = new ProjectDepositAudit();

        $assign_data = $model->getInfoByGuId($guid);
        $assign_data = $model->getTime($assign_data, 'payment_date', false);
//        $assign_data=$model->getTimeHours($assign_data,'update_time',false);

        $this->assign("data", $assign_data);

        return $this->fetch("project/moneyPendingDetails");

    }

    //已签合同列表审核（收款）
    public function moneyTransfer()
    {

        $guid = $this->request->param('guid');
        $this->assign("guid", $guid);
        $data = [
            "title" => "已签合同列表",
            "tatils" => "/wechat/project/moneyTransferDetails",
            "url" => "/erp/finance/showcontractprojectlist"
        ];
        $this->assign("data", $data);
        return $this->fetch("moneyTransfer");

    }

    public function moneyTransferPay()
    {
        $guid = $this->request->param('guid');
        $model = new ProjectModel();
        $number = $model->getCountObj('base/ProjectStructure', ["project_guid" => $guid]);
        $this->assign("guid", $guid);
        $data = [
            "title" => "已签合同列表",
            "tatils" => "/wechat/project/moneyTransferDetailsPay",
            "url" => "/erp/finance/showcontractprojectlist",
            "num" => $number
        ];
        $this->assign("data", $data);
        return $this->fetch("moneyTransferPay");
    }

    //已签合同付款审核详情
    public function moneyTransferDetails()
    {
        $guid = $this->request->param('guid');
        $this->assign("guid", $guid);
        if (empty($this->uuid)) {
            $this->error("参数错误");
        }
        $this->assignFieldValue();
//        $assign_data=$model->getTime($assign_data,'payment_date',false);
//        $assign_data=$model->getTimeHours($assign_data,'update_time',false);
        if ($this->request->isPost()) {
            $data = $this->request->post();
            if (empty($data)) {
                return self::showJsonReturnCodeWithOutData(1018);
            }
            if (!isset($data["project_id"])) {
                return self::showJsonReturnCodeWithOutData(1019);
            }
            if (!isset($data["book_number"])) {
                return self::showJsonReturnCodeWithOutData(1010);
            }
            $list = $this->showEasyUiJsonListNo('base/project/ProjectCollectPrice', false, ["project_id" => $data["project_id"], "book_number" => $data["book_number"], "status" => 1, 'collect_status' => 0]);
            return $list;
        } else {
            $model = new ProjectContract();
            $assign_data = $model->getInfoByGuId($guid);
            $this->assign("data", $assign_data);
            return $this->fetch("project/moneyTransferDetails");
        }
    }

    public function moneyTransferDetailsPay()
    {
        $guid = $this->request->param('guid');
        $this->assign("guid", $guid);
        if (empty($this->uuid)) {
            $this->error("参数错误");
        }
        $this->assignFieldValue();
        if ($this->request->isPost()) {
            $data = $this->request->post();
            if (empty($data)) {
                return self::showJsonReturnCodeWithOutData(1018);
            }
            if (!isset($data["project_id"])) {
                return self::showJsonReturnCodeWithOutData(1019);
            }
            if (!isset($data["book_number"])) {
                return self::showJsonReturnCodeWithOutData(1010);
            }
            $list = $this->showEasyUiJsonListNo('base/project/ProjectCollectPricePay', false, ["project_id" => $data["project_id"], "book_number" => $data["book_number"], "status" => 1, 'collect_status' => 0]);
            return $list;

        } else {
            $model = new ProjectContract();
            $assign_data = $model->getInfoByGuId($guid);
            $this->assign("data", $assign_data);
            return $this->fetch("project/moneyTransferDetailsPay");
        }
    }

    //预算修改审核列表
    public function budgetList()
    {

        if (!$this->uuid) {
            $this->redirect("login");
        }
        return $this->fetch("budgetList");
    }

//预算修改审核详情
    public function budgetDetails()
    {
        if (!$this->uuid) {
            $this->redirect("login");
        }
        $guid = $this->request->param('guid');

        if ($this->request->isPost()) {
            $model = new ReviseBudget();
            $assign_data = $model->getInfoByGuId($guid)->toArray();
            return $assign_data;
        }
        $this->assign("guid", $guid);
        return $this->fetch("project/budgetDetails");
    }

    //打印审核列表
    public function printList()
    {

        if (!$this->uuid) {
            $this->redirect("login");
        }
        $ass = ["title" => "打印审核列表",
            "url" => "erp/design_extract/showapplylist"];
        $this->assign($ass);
        return $this->fetch("printList");
    }

    //打印审核详情
    public function printDetails()
    {
        if (!$this->uuid) {
            $this->redirect("login");
        }
        $guid = $this->request->param('guid');
        if (empty($guid)) {
            $this->error("参数错误");
        }
        if ($this->request->isPost()) {

            $model = new PrintRequest();
            $assign_data = $model->getInfoByGuId($guid)->toArray();
            return $assign_data;
        }
        $this->assign("guid", $guid);
        return $this->fetch("project/printDetails");
    }

    //计划审核列表
    public function planList()
    {

        if (!$this->uuid) {
            $this->redirect("login");
        }
        $ass = ["title" => "计划审核列表",
            "url" => "erp/system/workexaminelist"];
        $this->assign($ass);
        return $this->fetch("project/planList");
    }

    //计划审核详情
    public function planDetails()
    {
        if (!$this->uuid) {
            $this->redirect("login");
        }
        $guid = $this->request->param('guid');
        if (empty($guid)) {
            $this->error("参数错误");
        }
        if ($this->request->isPost()) {
            $model = new OfficePlanLog();
            $assign_data = $model->getInfoByGuId($guid)->toArray();
            return $assign_data;
        }
        $this->assign("guid", $guid);
        return $this->fetch("project/planDetails");
    }

    //选材审核列表
    public function materialList()
    {
        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        $ass = [
            'data_url' => url('erp/engin/showprojectmaterialaudit'),
            'todetail' => ('project/materialDetails'),
            'as' => "选材审核列表"
        ];
        $this->assign($ass);
        return $this->fetch("materialList");
    }

    //选材审核详情
    public function materialDetails()
    {

        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        $project_id = $this->request->param('pro_id');
        $guid = $this->request->param('guid');
        if (empty($guid)) {
            $this->error("参数错误");
        }
        if ($this->request->isPost()) {
            $model_apply = new EnginProjectMaterialApply();
            $pro_id = $model_apply->where(["guid" => $guid])->value('project_id');
            $model = new ProjectModel();
            $assign_data = $model->getInfoByGuId($pro_id)->toArray();
            return self::showJsonReturnCode(1001, $assign_data);
        }
        $this->assign("guid", $guid);
        $this->assign("project_id", $project_id);
        return $this->fetch("materialDetails");
    }

//材料清单
    public function PurchaseList($pro_id = "")
    {
        $model = new FieldValue();
        $assign_data = [
            'dataf' => $model->householder_relation,
        ];
        if (empty($pro_id)) return self::showReturnCodeWithOutData(1003);
        $numb_model = new BudgetBookNumber();
        $book_number = $numb_model->where(['project_id' => $pro_id, 'status' => 1])->value('book_number');
        if ($this->request->isPost()) {
            $model = "base/budget/DefaultProjectCopy";
            $map = ["company_id" => $this->member_info->company_id, 'project_id' => $pro_id, 'book_number' => $book_number, 'zc_dj' => ['>', 0]];
            $list = $this->showEasyUiJsonListNo($model, false, $map, false, false);
            $material_model = new EnginProjectMaterial();
            if (!empty($list['rows'])) {
                foreach ($list['rows'] as $value) {
                    $material_list = $material_model->getList(['project_id' => $pro_id, 'book_number' => $book_number, 'project_data_id' => $value['guid']]);
                    if (!empty($material_list)) {
                        foreach ($material_list as $item) {
                            $item['name'] = $item['material_name'];
                            $item['price'] = $item['floor_price']; //单价等于成本价
                            $item['_parentId'] = $value['guid'];
                            $item['desc'] = $item['material_desc'];
                            $item['is_material'] = 1;
                            $item['unit'] = $item['unit_name'];
                            $list['rows'][] = $item;
                        }
                    }
                }
            }
            $list['total'] = count($list['rows']);
            return $list;
        } else {
            $map = [
                'project_id' => $pro_id,
                'status' => 1,
                'book_number' => $book_number,
                'examine_status' => 0
            ];
            $re = Loader::model('base/engin/EnginProjectMaterialApply')->where($map)->find();
            $assign_data = [
                'title' => '项目材料清单',
                'pro_id' => $pro_id,
                'none' => empty($re) ? "inline-block" : 'none',
                'book_number' => $book_number,
                'data_url' => url("lookPurchaseList", ['pro_id' => $pro_id, 'number' => $book_number])
            ];
            $this->assign($assign_data);
            return $this->fetch('PurchaseList');
        }
    }

//材料采购列表
    public function materialBuyList()
    {

        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        $ass = [
            'data_url' => "/wechat/project/materialBuyList",
            'todetail' => ('materialbuyDetails'),
            'as' => "材料采购列表"
        ];
        if ($this->request->isPost()) {
            $model = new ProjectContract();
            $list = $this->showEasyUiJsonListNo('base/project/ProjectContract', false, ["company_id" => $this->member_info->company_id]);
            foreach ($list['rows'] as $value) {
                $value['number'] = $model->getCountObj("base/engin/EnginProjectMaterialAddress", ['examine_status' => '0', "company_id" => $this->member_info->company_id, 'project_id' => $value["project_id"]]);
            }
            return $list;
        }
        $this->assign($ass);
        return $this->fetch("materialBuyList");
    }

    public function materialbuyDetails()
    {

        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        $guid = $this->request->param('guid');
        $model = new ProjectModel();

        $ass = [
            "project_name" => $model->getInfoByGuId($guid)["project_name"],
            'data_url' => url('erp/engin/showprojectmaterialaudit'),
            'todetail' => ('project/materialBuyDetails'),
            'as' => "材料采购审核",
            "guid" => $guid
        ];
        if ($this->request->isPost()) {
            $numb_model = new BudgetBookNumber();
            $book_number = $numb_model->where(['project_id' => $guid, 'status' => 1])->value('book_number');
            $list = $this->showEasyUiJsonListNo("base/engin/EnginProjectMaterialAddress", false, ["number" => $book_number, "project_id" => $guid]);
            return $list;
        }
        $this->assign($ass);
        $this->assign("project_id", $guid);
        return $this->fetch("materialbuyDetails");
    }

//材料验收列表
    public function materialChecklist()
    {

        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        $ass = [

            'data_url' => url('/wechat/project/materialChecklist'),
            'todetail' => ('materialCheckDetails'),
            'as' => "材料验收列表"
        ];
        if ($this->request->isPost()) {
            $model = new ProjectContract();
            $list = $this->showEasyUiJsonListNo('base/project/ProjectContract', false, ["company_id" => $this->member_info->company_id]);
            foreach ($list['rows'] as $value) {
                $value['number'] = $model->getCountObj("base/engin/EnginProjectMaterialAddress", ['project_id' => $value['project_id'], 'examine_status' => 1, 'accepatance_status' => 0, "status" => 2]);
            }
            return $list;
        }

        $this->assign($ass);
        return $this->fetch("materialBuyList");
    }

//材料验收审核
    public function materialCheckDetails()
    {
        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        $guid = $this->request->param('guid');
        $model = new ProjectModel;
        $ass = [
            "project_name" => $model->getInfoByGuId($guid)["project_name"],
            'as' => "材料验收审核",
            "guid" => $guid
        ];
        if ($this->request->isPost()) {
            $numb_model = new BudgetBookNumber();
            $book_number = $numb_model->where(['project_id' => $guid, 'status' => 1])->value('book_number');
            $list = $this->showEasyUiJsonListNoStatus("base/engin/EnginProjectMaterialAddress", false, ["number" => $book_number, "project_id" => $guid, 'examine_status' => 1, 'accepatance_status' => 0, "status" => 2]);
            return $list;
        }
        $this->assign($ass);
        return $this->fetch("materialCheckDetails");
    }

//单据详情
    public function billInformation()
    {

        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        $project_id = $this->request->param('project_id');
        $guid = $this->request->param('guid');
        $modela = new  ProjectModel();

        if (empty($guid)) return self::showReturnCode(1003);
        $model = new EnginProjectMaterialAddress();
        $obj = $model->where(['guid' => $guid])->find();
        $obj = $model->getTime($obj, 'collect_date', false);
        $project = $model->setCacheProjectData()['project'];
        $arr = [
            "project_name" => $modela->getInfoByGuId($project_id)["project_name"],
            'name' => $project[$obj['project_id']]['project_name'],
            'obj' => $obj,
            'guid' => $guid,
            'create_time' => $obj['create_time'],
        ];
        $this->assign($arr);
        return $this->fetch("project/billInformation");


    }

//单据明细
    public function billInformationList()
    {

        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        $guid = $this->request->param('guid');
        $project_id = $this->request->param("project_id");
        if (empty($guid)) return self::showReturnCode(1003);
        $model = new EnginProjectMaterialAddress();
        $modela = new ProjectModel();
        $project_name = $modela->getInfoByGuId($project_id)["project_name"];
        $modelunit = new FieldValue();
        $unit = $modelunit->unit_name;
        foreach ($unit as $value) {
        }
        if ($this->request->isPost()) {
            $list = $this->showEasyUiJsonListNo('base/engin/MaterialAdressAccess', false, ['receipt_id' => $guid]);
            if (!empty($list['rows'])) {
                foreach ($list['rows'] as $key => $value) {
                    $obj = Loader::model('base/engin/EnginProjectMaterialCopy')->getInfoByGuid($value['material_id']);

                    $value['material_name'] = $obj['material_name'];
                    $value['unit_name'] = $obj['unit_name'];
                    $value['specifications'] = $obj['specifications'];
                    $value['floor_price'] = $obj['floor_price'];
                    $value['unit'] = $unit[$value["status"]];
                    $value['project_name'] = $project_name;
                }
            }
            return $list;
        } else {
            $this->assign("guid", $guid);
            $this->assign("project_id", $project_id);
            return $this->fetch("project/billInformationList");
        }
    }

    //项目列表页（施工阶段）
    public function projectListOnWorking()
    {

        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        $ass = [
            'data_url' => url('erp/engin_project/showprojectofuser'),
            'as' => "施工阶段"
        ];
        $this->assign($ass);
        return $this->fetch("projectListOnWorking");
    }

    public function projectDetailsOnWorking()
    {
        $guid = $this->request->param('guid');
        if (empty($guid)) {
            $this->error("参数错误");
        };
        $model = new  ProjectModel();
        $project_name = $model->getInfoByGuId($guid)["project_name"];
        $this->assign("project_name", $project_name);
        $this->assign("guid", $guid);
        return $this->fetch("projectDetailsOnWorking");
    }

    public function workingPlan()
    {
        //        if(empty($guid)){
//            $this->error("参数错误");
//        };
        $project_id = $this->request->param("project_id");
        $guid = $this->request->param("guid");
        $model = new  ProjectModel();
        $project_name = $model->getInfoByGuId($project_id)["project_name"];
        $this->assign("project_name", $project_name);
        if ($this->request->isPost()) {
            $list = $this->showEasyUiJsonList("base/engin/EnginProjectBuildTypeDayAccess", false, ["day_id" => $guid]);
            return $list;
        } else {
            $day_name = EnginProjectBuildTypeDay::quickGet($guid);
            $this->assign("day_name", $day_name['day_name']);
            $this->assign("guid", $guid);
            $this->assign("project_name", $project_name);
            $this->assign("url", url("workingPlan", ["guid" => $guid]));
            return $this->fetch("workingPlan");
        }
    }

    public function dayList()
    {
        $id = $this->request->param("id");
        $project_id = $this->request->param("project_id");
        if ($this->request->isPost()) {
            $list = $this->showEasyUiJsonList('base/engin/EnginProjectBuildTypeDay', false, ["build_type_id" => $id], false, [], true);
            return $list;
        }
        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        $ass = [
            'data_url' => url('erp/engin_project/showprojectofuser'),
            'as' => "施工阶段",
            'project_id' => $project_id
        ];
        $this->assign("id", $id);
        $this->assign($ass);
        return $this->fetch("dayList");
    }

    public function workingPhoto()
    {
        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        $guid = $this->request->param("guid");
        $this->assign("guid", $guid);
        return $this->fetch("project/workingPhoto");
    }


    public function workingPhotoNew()
    {
        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        $guid = $this->request->param("guid");
        $pro_id = $this->request->param("pro_id");
        $this->assign("pro_id", $pro_id);
        $this->assign("guid", $guid);
        return $this->fetch("workingPhotoNew");
    }

    public function workingPhotoPending()
    {
        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        $guid = $this->request->param("guid");
        $pro_id = $this->request->param("pro_id");
        $this->assign("pro_id", $pro_id);
        $this->assign("guid", $guid);
        return $this->fetch("workingPhotoPending");
    }

    public function addWorkingPhoto()
    {
        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        $guid = $this->request->param("guid");
        $this->assign("guid", $guid);
        return $this->fetch("project/addWorkingPhoto");
    }

    public function addWorkingPhotoNew()
    {
        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        $pro_id = $this->request->param("pro_id");
        $guid = $this->request->param("guid");
        $this->assign("guid", $guid);
        $this->assign("pro_id", $pro_id);
        return $this->fetch("project/addWorkingPhotoNew");
    }

    public function workingManDetails()
    {
        $pro_id = $this->request->param("pro_id");
        $guid = $this->request->param("guid");
        $this->assign("pro_id", $pro_id);
        $this->assign("guid", $guid);
        return $this->fetch("project/workingManDetails");
    }

//项目列表页（完工阶段）
    public function projectStepPending()
    {
        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        $ass = [
            'data_url' => url(''),
            "as" => "部门",
        ];
        if ($this->request->isPost()) {
            $list = $this->showEasyUiJsonList("base/engin/EnginProjectBuildTypeAudit", false, ["company_id" => $this->member_info->company_id]);
            $return_list = [];
            foreach ($list['rows'] as $row) {
                $return_list[] = [
                    "project_id" => $row["project_id"],
                    "project_name" => $row["project_name"],
                ];
            }
            if ($return_list) {
                foreach ($return_list as $key => $v) {
                    $v = join(',', $v); //降维,也可以用implode,将一维数组转换为用逗号连接的字符串
                    $temp[$key] = $v;
                }
                $temp = array_unique($temp); //去掉重复的字符串,也就是重复的一维数组
                foreach ($temp as $k => $v) {
                    $temp[$k] = explode(',', $v); //再将拆开的数组重新组装
                    $temp[$k]["project_id"] = $temp[$k][0];
                    $temp[$k]["project_name"] = $temp[$k][1];
                }
                $list["rows"] = $temp;
                $list["total"] = count($temp);
                $model = new EnginProjectBuildTypeAudit();
                foreach ($list['rows'] as $key => $value) {
                    $list['rows'][$key]['number'] = $model->getCountObj("base/engin/EnginProjectBuildTypeAudit", ['project_id' => $value['project_id'], 'examine_status' => 0]);
                }
            }
            return $list;
        }
        $this->assign($ass);
        return $this->fetch("projectStepPending");
    }

//项目完工阶段审核列表
    public function projectStepPendingInclude()
    {
        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        $guid = $this->request->param("guid");
        if ($this->request->isPost()) {
            $guid = $this->request->param("guid");

            $list = $this->showEasyUiJsonList("base/engin/EnginProjectBuildTypeAudit", false, ["company_id" => $this->member_info->company_id, "project_id" => $guid]);
            return $list;
        }
        $ass = [
            'data_url' => url('', ['guid' => $guid]),
            "as" => "部门",
            "project_id" => $guid
        ];
        $this->assign($ass);
        return $this->fetch("projectStepPendingInclude");
    }

//项目完工阶段审核详情
    public function workingStepPendingDetails()
    {
        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        $guid = $this->request->param("guid");
        $list = [
            "guid" => $guid,
        ];
        if ($this->request->isPost()) {
            $model = new EnginProjectBuildTypeAudit();
            $assign_data = $model->getInfoByGuId($guid);
            return $assign_data;
        }
        $this->assign($list);
        return $this->fetch("workingStepPendingDetails");
    }

//项目完工审核列表
    public function projectWorkedPendingList()
    {
        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        $ass = [
            'data_url' => url('erp/engin_project/showprojectcompletionlist'),
        ];
        $this->assign($ass);
        return $this->fetch("projectWorkedPendingList");
    }

//项目完工审核详情
    public function workingPendingDetails()
    {
        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        $guid = $this->request->param("guid");
        $list = [
            "guid" => $guid,
        ];
        if ($this->request->isPost()) {
            $model = new EnginProjectCompletionAudit();
            $assign_data = $model->where(['project_id' => $guid, 'examine_status' => 0])->find();
            return $assign_data;
        }
        $this->assign($list);
        return $this->fetch("workingPendingDetails");
    }

    //我的施工项目列表
    public function workingMyList()
    {
        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        $type_id = $this->request->param("guid");
        $ass = [
            'data_url' => url('', ['guid' => $type_id]),
        ];
        if ($this->request->isPost()) {
            if (!$this->uuid) {
                return self::showJsonReturnCodeWithOutData(1003, "参数丢失");
            } else {
                $day_info = $this->showEasyUiJsonList("base/engin/EnginProjectBuildTypeDay", false, ["build_type_id" => $type_id], false, [], true);
            }
            if (!$day_info) {
                return self::showJsonReturnCodeWithOutData(1003, "参数丢失");
            } else {
                return $day_info;
            }
        }
        $this->assign($ass);
        return $this->fetch("workingMyList");
    }

    public function workingMyListProject()
    {
        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        $ass = [
            'data_url' => url('/erp/build_none/showbuildprojectofmy/'),
        ];
        if ($this->request->isPost()) {

            $list = $this->showEasyUiJsonList("base/engin/EnginProjectBuildTypeUser", false, ["build_uuid" => $this->uuid]);
            return $list;
        }
        $this->assign($ass);
        return $this->fetch("workingMyListProject");
    }

    public function workingMyDetails()
    {
        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        $guid = $this->request->param("guid");
        $pro_id = $this->request->param("pro_id");
        $this->assign("pro_id", $pro_id);
        $this->assign("guid", $guid);
        return $this->fetch("workingMyDetails");
    }

    public function workingMyDetailsNew()
    {
        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        $pro_id = $this->request->param("project_id");
        if (empty($pro_id)) return self::showReturnCode(1003);
        if ($this->request->isPost()) {
            $model = new BuildProjectPercentage();
            $numb_model = new BudgetBookNumber();
            $book_number = $numb_model->where(['project_id' => $pro_id, 'status' => 1])->value('book_number');
            $map = [
                'project_id' => $pro_id,
                'book_number' => $book_number,
            ];
            $list = $model->getList($map);
            $price = 0;
            $sum = 0;
            $arr = [];
            if (!empty($list)) {
                $num = 0;
                foreach ($list as $key => $value) {
                    $re = Loader::model("base/build/BuildSupervisionTem")->where(['project_id' => $value['project_id'], 'book_number' => $value['book_number'], 'category_id' => $value['category_id'], 'user_id' => $this->uuid, 'status' => 1])->find();
                    if (empty($re)) {
                        continue;
                    }
                    $category = MaterialDataStyle::quickGet($value['category_id']);
                    $sum += $value['category_price'];
                    $value['category_name'] = empty($category) ? '' : $category['categories_name'];
                    $value['price'] = $value['category_price'] * $value['value'];
                    $price += $value['price'];
                    $value['value'] = ($value['value'] * 100) . '%';
                    $app_map = [
                        'project_id' => $pro_id,
                        'book_number' => $book_number,
                        'build_id' => $value['category_id'],
                        'examine_status' => 1,
                        'status' => 1,
                    ];
                    $apply = Loader::model('base/engin/EnginProjectBuildTypeAudit')->where($app_map)->count();
                    $value['apply_status'] = $apply;
                    if ($apply == 1) {
                        $num++;
                    }
                    $arr[] = $value;
                }
                $arr[] = [
                    'category_name' => '项目完工结算',
                    'price' => $sum - $price,
                    'value' => '',
                    'apply_status' => count($arr) == $num ? "1" : 0,
                    'category_id' => $book_number,
                ];
            }
            return $arr;
        } else {
            $this->assign("pro_id", $pro_id);
            return $this->fetch("workingMyDetailsNew");
//            return self::showReturnCode(1003);
        }
    }

//计划列表页（个人）
    public function myPlanList()
    {
        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        $ass = [
            'data_url' => url('erp/system/workuserlist'),
        ];
        $this->assign($ass);
        return $this->fetch("myPlanList");
    }

    //计划列表页（部门）
    public function myPlanListDep()
    {
        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        $ass = [
            'data_url' => url('erp/work/showPlanDep'),
        ];
        $this->assign($ass);
        return $this->fetch("myPlanList");
    }

    //计划列表页（公司）
    public function myPlanListCom()
    {
        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        $ass = [
            'data_url' => url('erp/work/showPlanAll'),
        ];
        $this->assign($ass);
        return $this->fetch("myPlanList");
    }

    //计划详情
    public function myPlanDetails()
    {
        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        $guid = $this->request->param("guid");
        if ($this->request->isPost()) {
            $list = $this->showEasyUiJsonList("base/officePlan", false, ["guid" => $guid]);
            return $list;
        }
        $this->assign("guid", $guid);
        return $this->fetch("myPlanDetails");
    }

    //项目列表（公司/本月）
    public function showProjectComListOnMonth()
    {
        $modelb = new ProjectAudit();
        $model = "base/ProjectAudit";
        if ($this->request->isPost()) {
            $arr = [];
            $list["rows"] = Db::table('mk_project')->whereTime('create_time', 'm')->where(['company_id' => $this->member_info->company_id, 'status' => 1])->select();
            $list["total"] = count($list["rows"]);
            return $list;
        } else {
            config(['default_ajax_return' => 'html',]);
            $assign_data = [
                'title' => '本月项目表',
                'dialog_url' => 'projectEdit',
                'data_url' => url('showProjectComListOnMonth'),
            ];
            $this->assign($assign_data);
            return $this->fetch('showProjectComListOnMonth');
        }
    }

    //项目列表（公司/上月）
    public function showProjectComListLastMonth()
    {
        $modelb = new ProjectAudit();
        $model = "base/ProjectAudit";
        if ($this->request->isPost()) {
            $arr = [];
            $list["rows"] = Db::table('mk_project')->whereTime('create_time', 'last m')->where(['company_id' => $this->member_info->company_id, 'status' => 1])->select();
            $list["total"] = count($list["rows"]);
            return $list;
        } else {
            config(['default_ajax_return' => 'html',]);
            $assign_data = [
                'title' => '上月项目表',
                'dialog_url' => 'projectEdit',
                'data_url' => url('showProjectComListLastMonth'),
            ];
            $this->assign($assign_data);
            return $this->fetch('showProjectComListOnMonth');
        }
    }

    //转工程部审核列表
    public function projectWorkPendingList()
    {
        if (!$this->uuid) {
            $this->redirect("login");
        }
        $ass = ["title" => "转工程部审核",
            "url" => "erp/design_extract/showapplyturninglist"];
        $this->assign($ass);
        return $this->fetch("projectWorkPendingList");
    }

    //转工程部审核详情
    public function projectWorkPendingDetails()
    {
        if (!$this->uuid) {
            $this->redirect("login");
        }
        $guid = $this->request->param("guid");
        if ($this->request->isPost()) {
        } else {
            $this->assign("guid", $guid);
            return $this->fetch("projectWorkPendingDetails");
        }
    }

    public function lookBudget($guid = "", $type = false)
    {
        if (!$this->uuid) {
            $this->redirect("login");
        }
        $guid = $this->request->param("guid");
        if (empty($guid)) {
            $this->error("参数错误");
        }
        if ($this->request->isPost()) {
            if (empty($guid)) return self::showJsonReturnCode(1003);
            $model = new BudgetDefaultProject();
            $data = $model->getDataList($guid); //装修信息
            $book = Loader::model("base/BudgetDefaultBook")->getBook($guid); //公司地址
            $numb_model = new BudgetBookNumber();
            $book_number = $numb_model->where(['project_id' => $guid, 'status' => 1])->value('book_number');
            $contUser = Loader::model("base/ProjectContacts")->getProjectCont($guid);  //业主的信息
            $pro = Loader::model("base/Project")->getProject($guid); //项目的信息
            if (!empty($pro)) {
                if (isset($pro['pro_style'])) {
                    $fi = new FieldValue();
                    $pro['pro_style_name'] = $fi->decoration_style[$pro['pro_style']];
                }
            }
            $sj_user = Loader::model("base/ProjectRelatedPerson")->getFind(['project_id' => $guid, 'department_type' => 3], 'uuid');
            if (empty($sj_user)) {
                $user_name = '';
            } else {
                $user_name = Loader::model("base/PersonnelUser")->getList(["uuid" => $sj_user['uuid']], 'name,mobile');
            }
            $arr_u = [
                'name' => empty($user_name) ? "" : $user_name[0]['name'],
                'tel' => empty($user_name) ? "" : $user_name[0]['mobile'],
            ];
            $field = new FieldValue();
            if (isset($field->decoration_style[$book['budget_style']])) {
                $book['budget_style'] = $field->decoration_style[$book['budget_style']];
            }
            $assign_data = [
                'book' => $book,
                'cont' => $contUser,
                'project' => $pro,
                'body' => $data['body'],
                'foot' => $data['foot'],
                'user' => $arr_u,
                'project_id' => $guid,
                'book_number' => $book_number
            ];
            return $assign_data;
        }
        $this->assign("guid", $guid);
        return $this->fetch("lookBudget");
    }

    public function workApplyMoney()
    {
        if (empty($this->uuid)) {
            $this->redirect("index/login");
        }
        $pro_id = $this->request->param("pro_id");
        $guid = $this->request->param("guid");
        $this->assign("pro_id", $pro_id);
        $this->assign("guid", $guid);
        if (!empty($pro_id)) {
            $list = \app\base\model\Project::quickGet($pro_id);

            $this->assign("list", $list);
        }
        if ($this->request->isPost()) {
            $numb_model = new BudgetBookNumber();
            $book_number = $numb_model->where(['project_id' => $pro_id, 'status' => 1])->value('book_number');
            $project = \app\base\model\Project::quickGet($pro_id);
            if ($guid == $book_number) {  //说明是最后结清款
                $map = [
                    'project_id' => $pro_id,
                    'status' => 1,
                    'cetegory_uuid' => $this->uuid,
                ];
                $list = Loader::model("base/build/BuildProjectPercentage")->where($map)->select();
                $price = 0;
                if (!empty($list)) {
                    foreach ($list as $value) {
                        $price += $value['category_price'] * (1 - $value['value']);
                    }
                }
            } else {
                $map = [
                    'project_id' => $pro_id,
                    'category_id' => $guid,
                    'status' => 1,
                    'book_number' => $book_number,
                ];
                $value = Loader::model("base/build/BuildProjectPercentage")->where($map)->find();
                $price = empty($value) ? '0' : $value['category_price'] * $value['value'];
            }
            $arr = [
                'number' => $book_number,
                'name' => empty($project) ? "" : $project['project_name'],
                'check' => '',
                'value' => $price,
            ];
            return $arr;
        }
        return $this->fetch("workApplyMoney");
    }

    //费单申请详情
    public function projectWrongPendingDetails()
    {
        if (!$this->uuid) {
            $this->redirect("login");
        }
        $guid = $this->request->param('guid');
        $this->assign("guid", $guid);
        if ($this->request->isPost()) {
            $model = new WasteApply();
            $list = $model->getInfoByGuId($guid);
            return $list;
        }
        return $this->fetch("project/projectWrongPendingDetails");
    }

    //费单申请详情（业务部门）
    public function projectWrongPendingDetailsBusiness()
    {
        if (!$this->uuid) {
            $this->redirect("login");
        }
        $guid = $this->request->param('guid');
        $this->assign("guid", $guid);
        if ($this->request->isPost()) {
            $model = new WasteApply();
            $list = $model->getInfoByGuId($guid);
            return $list;
        }
        return $this->fetch("project/projectWrongPendingDetailsBusiness");
    }

    //退款审核列表
    public function moneyBackPendingList()
    {
        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        return $this->fetch("moneyBackPendingList");
    }

    //退款审核详情
    public function moneyBackPendingDetails()
    {
        if (!$this->uuid) {
            $this->redirect("login");
        }
        $guid = $this->request->param('guid');
        $this->assign("guid", $guid);
        if ($this->request->isPost()) {
            $model = new ProjectCollectPriceRefund();
            $list = $model->getInfoByGuId($guid);
            $a = ProjectCollectPriceRefund::quickGet($guid)["project_id"];
            if (empty($a)) {
                $list["project_name"] == "";
            } else {
                $project_model = new ProjectModel;
                $list["project_name"] = $project_model->getInfoByGuId($a)["project_name"];
            }
            return $list;
        }
        return $this->fetch("project/moneyBackPendingDetails");
    }

    //申请使用人列表，老大大专属。
    public function shiYongList()
    {
        if (!$this->uuid) {
            $this->redirect("index/myError");
        }

    }

    //优秀团队列表
    public function goodTeam()
    {
        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        return $this->fetch("project/goodTeam");
    }

    //优秀团队详情
    public function goodTeamDetails()
    {
        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        $uuid = $this->request->param("uuid");

        $model = new UserOpus();
        if ($this->request->isPost()) {

            $list = $model->getList(["uuid" => $uuid, "status" => 1]);
            return $list;

        }
//        self::echoHtml();
        $this->assign("uuid", $uuid);
        return $this->fetch("project/goodTeamDetails");

    }

    //设计师个人作品列表
    public function personOpusList()
    {
        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        $model = new UserOpus();
        if ($this->request->isPost()) {

            $list = $model->getList(["uuid" => $this->uuid, "status" => 1]);
            return $list;

        }
        return $this->fetch("project/personOpusList");
    }

    //设计师个人作品上传
    public function addOpus()
    {
        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        return $this->fetch("project/addOpus");
    }

    //我的维修列表（分配给我的）
    public function repareList()
    {
        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        return $this->fetch("project/repareList");
    }

    //我的维修详情
    public function repareDetails()
    {
        $guid = $this->request->param("guid");
        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        if ($this->request->isPost()) {
            $model = new PersonnelUser();
            $list = Db::table("mk_office_complaint")->where(['guid' => $guid, 'status' => 1])->select();
            foreach ($list as $value) {
                $value['time'] = date("Y-m-d", $value['time']);
                $value['end_time'] = date("Y-m-d", $value['end_time']);

                if ($value["uuid"]) {
                    $value["register"] = $model->getInfoByUuId($value["uuid"]);
                } else {
                    $value["register"] = "";
                }
                if ($value["complaint_uuid"]) {
                    $value["handle"] = $model->getInfoByUuId($value["complaint_uuid"]);
                } else {
                    $value["handle"] = "";
                }
            }
            return $value;
        }
        $this->assign("guid", $guid);
        return $this->fetch("project/repareDetails");
    }

    //维修投诉申请录入
    public function applyRepare()
    {
        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        return $this->fetch("project/applyRepare");
    }

    //客户维修列表（本人）
    public function repareListCustomer()
    {
        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        return $this->fetch("project/repareListCustomer");
    }

    //客户投诉详情（本人）
    public function repareDetailsCustomer()
    {
        $guid = $this->request->param("guid");
        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        if ($this->request->isPost()) {
            $model = new PersonnelUser();

            $list = Db::table("mk_office_complaint")->where(['guid' => $guid, 'status' => 1])->select();
            foreach ($list as $value) {
                $value['time'] = date("Y-m-d", $value['time']);
                $value['end_time'] = date("Y-m-d", $value['end_time']);
                if ($value["uuid"]) {
                    $value["register"] = $model->getInfoByUuId($value["uuid"]);
                } else {
                    $value["register"] = "";
                }
                if ($value["complaint_uuid"]) {
                    $value["handle"] = $model->getInfoByUuId($value["complaint_uuid"]);
                } else {
                    $value["handle"] = "";
                }
            }
            return $value;
        }
        $this->assign("guid", $guid);
        return $this->fetch("project/repareDetailsCustomer");
    }

//废单列表

    public function wasteProjectList()
    {
        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        $url = "erp/waste_single/showwastesinglelist";
        $this->assign("url", $url);
        return $this->fetch("waste_project_list");

    }

    //废单详情及申请跟踪
    public function wasteProjectDetails()
    {
        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        $guid = $this->request->param("guid");

        if ($this->request->isPost()) {
            $model = new \app\base\model\Project();

            $data = $model->where(["guid" => $guid])->find();
            return $data;

        }
        $this->assign("guid", $guid);
        return $this->fetch("waste_project_details");
    }
    //审核详情

}