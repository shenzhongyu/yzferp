<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/23
 * Time: 13:02
 */

namespace app\erp\controller;

use app\base\controller\Upload;
use app\base\model\Base;
use app\base\model\finance\Bank;
use app\base\model\finance\CollectPlan;
use app\base\model\finance\CollectPlanAccess;
use app\base\model\finance\CollectStyle;
use app\base\model\finance\PaymentStyle;
use app\base\model\PersonnelCompany;
use app\base\model\PersonnelDepartment;
use app\base\model\PersonnelUser;
use app\base\model\Project;
use app\base\model\project\ProjectCollectPrice;
use app\base\model\project\ProjectCollectPricePay;
use app\base\model\project\ProjectDepositAudit;
use app\base\model\project\ProjectField;
use app\base\model\project\ProjectPhoto;
use app\base\model\ProjectContacts;
use app\base\model\ProjectRemindTime;
use app\base\model\ProjectStructure;
use app\base\model\ProjectBuilding;
use app\base\model\ProjectTraceLog;
use app\erp\config\FieldValue;
use app\erp\controller\template_message\SendTemplateMessage;
use mikkle\tp_wechat\support\Db;
use think\Loader;
use think\Session;
use app\erp\config\Config;


class ProjectPayment extends Auth
{
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
//        $this->config_list = Config::$project;
    }
    /*项目添加付款列表*/
    public function showProjectPayment(){
        if ($this->request->isPost()){
            $model='base/Project';
            $list=$this->showEasyUiJsonList($model,false,["company_id"=>$this->member_info->company_id,'feedback_stage'=>'3'],false,false);
            return $list;
        }else{
            $arr=[
                'title'=>'项目列表',
                'data_url'=>url('showProjectPayment'),
            ];
            $this->assign($arr);
            self::echoHtml();
            return $this->fetch('project_pay_list');
        }
    }
    /*项目请款页面*/
    public function projectRequest($guid=""){
        if (empty($guid)) return self::showReturnCode(1003);
        if ($this->request->isPost()){

        }else{
            $book_number=Loader::model('base/budget/BudgetBookNumber')->where(['project_id'=>$guid,'status'=>1])->value('book_number');
            $obj=Loader::model('base/project/ProjectContract')->where(['project_id'=>$guid,'book_number'=>$book_number])->find();
            $project=Project::quickGet($guid);
            $arr=[
                'title'=>'请款',
                'number'=>$book_number,
                'name'=>$project['project_name'],
                'price'=>$obj['contract_amount'],//项目总金额
                'pay_price'=>$obj['pay_price'],//项目支出
                'surplus_price'=>number_format($obj['contract_amount']-$obj['pay_price'],2),//剩余金额
                'pro_id'=>$guid, // 项目的id
                'collection_pay'=>url('Finance/collectionPayList',['guid'=>$guid]),
                'pay_price_list'=>url('Finance/pricePayList',['guid'=>$guid]),
            ];
            $this->assign($arr);
            self::echoHtml();
            return $this->fetch('project_request');
        }
    }
    /*新增项目支付*/
    public function addPaymentPro($guid=""){
        if (empty($guid)) return self::showReturnCode(1003);
        if ($this->request->isPost()){
            $data=$this->request->post();
            $validate_time='base/ProjectCollectPricePay.add';
            if ($validate_time!= false) {
                $result = $this->validate($data, $validate_time);
                if (true !== $result) return self::showJsonReturnCodeWithOutData(1003,$result);
            }
            $data['collect_uuid']=$this->uuid;
            $data['collect_uuid_name']=$this->member_info->name;
            $data['company_id']=$this->member_info->company_id;
            $model=new ProjectCollectPricePay();
            $re=$model->addPaymentPro($data,$guid);
            return $re;
        }else{
            $book_number=Loader::model('base/budget/BudgetBookNumber')->where(['project_id'=>$guid,'status'=>1])->value('book_number');
            $project=Loader::model("base/Project")->getInfoByGuid($guid);
            $arr=[
                'number'=>$book_number,
                'name'=>$project['project_name'],
                'check'=>'',
            ];
            $this->assign($arr);
            self::echoHtml();
            return $this->fetch('add_payment_pro');
        }
    }
    /*显示单据详细*/
    public function showDetailed($guid=""){
        if (empty($guid)) return self::showReturnCodeWithOutData(1003);
        $model=new ProjectCollectPricePay();
        if ($this->request->isPost()){
            $data=$this->request->post();
            return $model->addCollectPrice($data,$guid);
        }else{
            $obj=$model->getInfoByGuId($guid);
            $obj=$model->getTime($obj,'collect_date',false);
            $project_name=Loader::model("base/Project")->where(['guid'=>$obj['project_id']])->value('project_name');
            if (empty($obj['payment_id'])){
                $payment='';
            }else{
                $payment=Loader::model('base/finance/PaymentStyle')->where(['guid'=>$obj['payment_id']])->value('name');
            }
            $com_model=new PersonnelCompany();
            $company=$com_model->getInfoByGuId($this->member_info->company_id);
            $assign_data= [
                'name'=>$project_name,
                'obj'=>$obj,
                'company'=>$company?$company["company_name"]:'',
                'ex'=>$obj['collect_status']=="1"?'已收款':'未收款',
                'examine'=>$obj['examine_status']=="1"?'审核通过':'未审核或审核不通过',
                'invoice'=>$obj['invoice_value']==0?'无发票':'开发票',
                'payment'=>$payment
            ];
            $this->assign($assign_data);
            self::echoHtml();
            return $this->fetch('project_collection/show_detailed');
        }
    }
    /*删除付款申请*/
    public function delContent(){
        if ($this->request->isPost()){
            $data=$this->request->post();
            if (empty($data)) return self::showReturnCode(1003);
            if (!isset($data['id'])) return self::showReturnCode(1003);
            $model=new ProjectCollectPricePay();
            $pro_id=$model->where(['guid'=>$data['id']])->value('project_id');
            $re=$model->delDate($data['id']);
            if ($re['code']=="1001"){
                $model=new ProjectTraceLog();
                $log=[];
                $log["log_content"]="删除付款申请";
                $log["guid"]=$pro_id;
                $log["uuid"]=$this->member_info->uuid;
                $log["jobs_id"]=$this->member_info->jobs_id;
                $log["department_id"]=$this->member_info->department_id;
                $model->addProjectLog($log);
            }
            return $re;
        }else{
            return self::showReturnCode(1003);
        }
    }
    public function formLoad($guid=""){  //修改加载表单
        if (!empty($guid)) {
            $model=new ProjectCollectPricePay();
            return json($model->getInfoByGuid($guid));
        } else {
            return json([]);
        }
    }
    /*修改请款申请*/
    public function editCollect($guid=""){
        if (empty($guid)) return self::showReturnCode(1003);
        $model=new ProjectCollectPricePay();
        if ($this->request->isPost()){
            $data=$this->request->post();
            $validate_time='base/ProjectCollectPricePay.add';
            if ($validate_time!= false) {
                $result = $this->validate($data, $validate_time);
                if (true !== $result) return self::showJsonReturnCodeWithOutData(1003,$result);
            }
            $re=$model->editCollect($data,$guid);
            $pro_id=$model->where(['guid'=>$guid])->value('project_id');
            if ($re['code']=="1001"){
                $model=new ProjectTraceLog();
                $log=[];
                $log["log_content"]="修改付款申请";
                $log["guid"]=$pro_id;
                $log["uuid"]=$this->member_info->uuid;
                $log["jobs_id"]=$this->member_info->jobs_id;
                $log["department_id"]=$this->member_info->department_id;
                $model->addProjectLog($log);
            }
            return $re;
        }else{
            $obj=$model->getInfoByGuId($guid);
            $project_name=Loader::model("base/Project")->where(['guid'=>$obj['project_id']])->value('project_name');
            $assign_data= [
//
                'number'=>$obj['book_number'],
                'name'=>$project_name,
                'check'=>$obj['invoice_value']==1 ? 'true' : 'false' ,

            ];
            $this->assign($assign_data);
            self::echoHtml();
            return $this->fetch('add_payment_pro');
        }
    }
    /*审核付款*/
    public function editExamine($guid=""){
        if (empty($guid)) return self::showReturnCode(1003);
        $model=new ProjectCollectPricePay();
        if ($this->request->isPost()){
            $data=$this->request->post();
            $data['examine_uuid']=$this->uuid;
            $data['examine_uuid_name']=$this->member_info->name;
            $re=$model->editExamine($data,$guid);
            $pro_id=$model->where(['guid'=>$guid])->value('project_id');
            if ($re['code']=="1001"){
                $model=new ProjectTraceLog();
                $log=[];
                $log["log_content"]="收款审核";
                $log["guid"]=$pro_id;
                $log["uuid"]=$this->member_info->uuid;
                $log["jobs_id"]=$this->member_info->jobs_id;
                $log["department_id"]=$this->member_info->department_id;
                $model->addProjectLog($log);
            }
            return $re;
        }else{
            $obj=$model->getInfoByGuId($guid);
            $project_name=Loader::model("base/Project")->where(['guid'=>$obj['project_id']])->value('project_name');
            $assign_data= [
                'number'=>$obj['number'],
                'book_number'=>$obj['book_number'],
                'name'=>$project_name,
                'price'=>$obj['collect_price'],
                'price_no'=>$obj['price'],
                'collect_type'=>$obj['collect_type_name'],
                'collect_name'=>$obj['collect_name']
            ];
            $this->assign($assign_data);
            self::echoHtml();
            return $this->fetch('edit_examine');
        }
    }


    /*新增项目付款(财务)*/
    public function addPayment($guid=""){
        if (empty($guid)) return self::showJsonReturnCode(1003);
        if ($this->request->isPost()){
            $data=$this->request->post();
            $validate_time='base/ProjectCollectPricePay.add';
            if ($validate_time!= false) {
                $result = $this->validate($data, $validate_time);
                if (true !== $result) return self::showJsonReturnCodeWithOutData(1003,$result);
            }
            $data['collect_uuid']=$this->uuid;
            $data['collect_uuid_name']=$this->member_info->name;
            $data['company_id']=$this->member_info->company_id;
            $model=new ProjectCollectPricePay();
            $re=$model->addPaymentPro($data,$guid);
            if ($re['code']=="1001") {
                $log_model = new ProjectTraceLog();
                $log = [];
                $log["log_content"] = '新增项目付款，原因是'.$data["desc"];
                $log["guid"] = $guid;
                $log["uuid"] = $this->member_info->uuid;
                $log["jobs_id"] = $this->member_info->jobs_id;
                $log["department_id"] = $this->member_info->department_id;
                $log_model->addProjectLog($log);
                SendTemplateMessage::sendTemplateMessage("payCheck");
            }
            return $re;
        }else{
            $book_number=Loader::model('base/budget/BudgetBookNumber')->where(['project_id'=>$guid,'status'=>1])->value('book_number');
            $project=Loader::model("base/Project")->getInfoByGuid($guid);
            $arr=[
                'number'=>$book_number,
                'name'=>$project['project_name'],
                'check'=>'',
            ];
            $this->assign($arr);
            self::echoHtml();
            return $this->fetch('apply_model/collect_price');
        }
    }
    /*项目付款审核列表*/
    public function showPaymentList(){
        if ($this->request->isPost()){
            $list=$this->showEasyUiJsonList('base/project/ProjectCollectPricePay',false,['collect_status'=>0,'examine_status'=>0,'company_id'=>$this->member_info->company_id],false,[],false,'project_id,collect_uuid_name,desc,guid');
            if (!empty($list["rows"])){
                foreach ($list["rows"] as $key=>$value){
                    $list["rows"][$key]["project_name"]=Db::table("mk_project")->where(["guid"=>$value["project_id"]])->value("project_name");
                }
            }
            return $list;
        }else{
            $arr=[
                'title'=>'项目付款审核列表',
                'data_url'=>url("showPaymentList"),
                'edit_url'=>url("editPayment")
            ];
            $this->assign($arr);
            self::echoHtml();
            return $this->fetch("project_payment_apply/show_payment_list");
        }
    }
    /*查看付款明细*/
    public function showMoreInfo($guid=""){
        if (empty($guid)) return self::showJsonReturnCode(1003);
        $model=new ProjectCollectPricePay();
        $obj=$model->getInfoByGuId($guid);
        $project_name=Loader::model("base/Project")->where(['guid'=>$obj['project_id']])->value('project_name');
        if (empty($obj['payment_id'])){
            $payment='';
        }else{
            $payment=Loader::model('base/finance/PaymentStyle')->where(['guid'=>$obj['payment_id']])->value('name');
        }
        $obj=$model->getTime($obj,"collect_date",false);
        $assign_data= [
            'name'=>$project_name,
            'obj'=>$obj,
            'ex'=>$obj['collect_status']=="1"?'已付款':'未付款',
            'examine'=>$obj['examine_status']=="1"?'审核通过':'未审核或审核不通过',
            'invoice'=>$obj['invoice_value']==0?'无发票':'开发票',
            'payment'=>$payment
        ];
        $this->assign($assign_data);
        self::echoHtml();
        return $this->fetch('project_payment_apply/show_detailed');

    }
    /*审核付款*/
    public function editPayment($guid=""){
        if (empty($guid)) return self::showJsonReturnCode(1003);
        if($this->request->isPost()){
            $data=$this->request->post();
            $model=new ProjectCollectPricePay();
            $data['examine_uuid']=$this->uuid;
            $data['examine_uuid_name']=$this->member_info->name;
            $re=$model->editExamine($data,$guid);
            $pro_id=$model->where(['guid'=>$guid])->value('project_id');
            if ($re['code']=="1001") {
                $log_model = new ProjectTraceLog();
                $log = [];
                if ($data['examine_status']=="1"){
                    $title="付款审核通过";
                }else if($data['examine_status']=="-1"){
                    $title="付款审核不通过";
                }else{
                    $title="付款审核";
                }
                $log["log_content"] = $title;
                $log["guid"] = $pro_id;
                $log["uuid"] = $this->member_info->uuid;
                $log["jobs_id"] = $this->member_info->jobs_id;
                $log["department_id"] = $this->member_info->department_id;
                $log_model->addProjectLog($log);
            }
            return $re;
        }else{
            self::echoHtml();
            return $this->fetch('apply_model/track_log_edit');
        }

    }
}