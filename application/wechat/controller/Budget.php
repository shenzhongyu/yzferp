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
use app\base\model\finance\RevokeMoney;
use app\base\model\MaterialDataStyle;
use app\base\model\OfficeComplaint;
use app\base\model\OfficePlanLog;
use app\base\model\personal\UserOpus;
use app\base\model\PersonnelCompany;
use app\base\model\PersonnelUser;
use app\base\model\project\ProjectCollectPrice;
use app\base\model\project\ProjectCollectPricePay;
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


class Budget extends Auth
{
    public function budgetDetails()
    {
        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        $id = $this->request->param("guid");
        $this->assign("id", $id);
        return $this->fetch("budget/budgetDetails");
    }

    public function budgetSpaceDetails()
    {
        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        $id = $this->request->param("id");
        $arr = [];
        $arr['rows'] = [];
        $guid = $this->request->param('guid');
        $this->assign("id", $id);
        $this->assign("guid", $guid);
        if (empty($id)) return self::showJsonReturnCode(1003);
        $name = '';
        if ($this->request->isPost()) {

            $i = 1;
            if (!empty($guid)) {
                $children = $this->showEasyUiJsonList('base/BudgetDefaultProject', false, ['project_id' => $id, 'company_id' => $this->member_info->company_id, 'pid' => $guid], false, [], false, 'id,guid,pid,name,unit,number,price,desc');
            }
            return json($children);
        }
        return $this->fetch("budget/budgetSpaceDetails");
    }

    public function budgetAddSpace()
    {
        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        $project_id = $this->request->param("id");
        $this->assign("project_id", $project_id);
        return $this->fetch("budget/budgetAddSpace");
    }

    public function addDecorate()
    {
        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        $id = $this->request->param("id");
        $guid = $this->request->param("guid");
        $this->assign("guid", $guid);
        $this->assign("id", $id);
        return $this->fetch("budget/addDecorate");
    }

//申请打印记录
    public function printNotes()
    {
        $pro_id = $this->request->param("pro_id");
        $id = $this->request->param("id");
        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        if ($this->request->isPost()) {
            $list = $this->showEasyUiJsonListNoStatus("base/budget/PrintRequest", false, ['project_id' => $pro_id]);
            return $list;
        }
        $this->assign("id", $id);
        return $this->fetch("budget/printNotes");
    }

//已收款撤销审核列表
    public function revokeList()
    {
        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        $url = "erp/finance_revoke/showrevokelist";
        $this->assign("url", $url);
        return $this->fetch("revoke_list");
    }

//已收款撤销审核详情
    public function revokeDetails()
    {
        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        $guid = $this->request->param("guid");
        if (empty($guid)) {
            $this->error("参数错误");
        }
        if ($this->request->isPost()) {

            $modelDetails = new RevokeMoney();
            $details = $modelDetails->getInfoByGuId($guid);
            $collectid = $details["collect_id"];
            if (empty($collectid)) {
                return self::showJsonReturnCode(1003);
            }
            $modelName = new PersonnelUser();
            if (empty($details["uuid"])) {
                return self::showJsonReturnCode(1003);
            }
            $name = $modelName->getInfoByGuId($details["uuid"])["name"];
            $model = new ProjectCollectPrice();
            $obj = $model->getInfoByGuId($collectid);

            $obj = $model->getTime($obj, 'collect_date', false);
            $project_name = Loader::model("base/Project")->where(['guid' => $obj['project_id']])->value('project_name');
            if (empty($obj['payment_id'])) {
                $payment = '';
            } else {
                $payment = Loader::model('base/finance/PaymentStyle')->where(['guid' => $obj['payment_id']])->value('name');
            }
            $com_model = new PersonnelCompany();
            $company = $com_model->getInfoByGuId($this->member_info->company_id);
            $assign_data = [
                'name' => $project_name,
                'obj' => $obj,
                'company' => $company ? $company["company_name"] : '',
                'ex' => $obj['collect_status'] == "1" ? '已收款' : '未收款',
                'examine' => $obj['examine_status'] == "1" ? '审核通过' : '未审核或审核不通过',
                'invoice' => $obj['invoice_value'] == 0 ? '无发票' : '开发票',
                'payment' => $payment,
                "details" => $details,
                "applyname" => $name,
                "collect_id" => $collectid
            ];
            return $assign_data;
        }
        $this->assign("guid", $guid);
        return $this->fetch("revoke_details");
    }

//付款审核列表
    public function payList()
    {
        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        $url = "erp/project_payment/showpaymentlist";
        $this->assign("url", $url);
        return $this->fetch("pay_list");
    }

//付款审核详情
    public function payDetails()
    {
        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        $guid = $this->request->param("guid");
        if (empty($guid)) {
            $this->error("参数错误");
        }
        if ($this->request->isPost()) {
            $modelDetails = new ProjectCollectPricePay();
            $obj = $modelDetails->getInfoByGuId($guid);
            $model = new ProjectCollectPrice();
            $obj = $model->getTime($obj, 'collect_date', false);
            $project_name = Loader::model("base/Project")->where(['guid' => $obj['project_id']])->value('project_name');
            if (empty($obj['payment_id'])) {
                $payment = '';
            } else {
                $payment = Loader::model('base/finance/PaymentStyle')->where(['guid' => $obj['payment_id']])->value('name');
            }
            $com_model = new PersonnelCompany();
            $company = $com_model->getInfoByGuId($this->member_info->company_id);
            $assign_data = [
                'name' => $project_name,
                'obj' => $obj,
                'company' => $company ? $company["company_name"] : '',
                'ex' => $obj['collect_status'] == "1" ? '已收款' : '未收款',
                'examine' => $obj['examine_status'] == "1" ? '审核通过' : '未审核或审核不通过',
                'invoice' => $obj['invoice_value'] == 0 ? '无发票' : '开发票',
                'payment' => $payment,
            ];
            return $assign_data;
        }
        $this->assign("guid", $guid);
        return $this->fetch("pay_details");
        //

    }
    //废单转出列表
    public function wasteOutList(){
        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        $url="erp/waste_single/showwasteprojectapply";
        $this->assign("url",$url);
        return $this->fetch("waste_out_list");

    }
    //废单转出详情
    public function wasteOutDetails(){
        if (!$this->uuid) {
            $this->redirect("index/myError");
        }
        $guid=$this->request->param("guid");
        $this->assign("guid",$guid);
        if($this->request->isPost()){
            $usermodel= new PersonnelUser();
            $model = new WasteProjectApply();
            $data= $model->getInfoByGuId($guid);
            $uuid=$data["uuid"];
            $data["name"]=$usermodel->getInfoByGuId($uuid)["name"];
            return $data;
        }
        return $this->fetch("waste_out_details");
    }
}