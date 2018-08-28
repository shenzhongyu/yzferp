<?php
/**
 * Created by PhpStorm.
 * Power By Mikkle
 * Email：776329498@qq.com
 * Date: 2017/8/2
 * Time: 17:14
 */

namespace app\wechat\controller;


use app\base\model\OfficeLeave;
use app\base\model\PersonnelCompany;
use app\base\model\PersonnelDepartment;
use app\base\model\PersonnelJobs;
use app\base\model\PersonnelNode;
use app\base\model\PersonnelUser;
use think\Db;
use think\Exception;
use think\Loader;
use think\Log;
use think\Url;
use app\erp\config\FieldValue;
use app\base\model\Project as ProjectModel;


class Index extends Base
{
    public function prospectus (){
        $isweixin="";
//        $user_agent = $this->request->server('HTTP_USER_AGENT');
//        if (! strpos($user_agent, 'MicroMessenger') === false ) {
//            $this->assign("true",$isweixin);
//            return $this->fetch("prospectus");
//        }else{
            return $this->fetch("prospectus_false");
//        }




    }
    public function index()
    {
        try{
            if (!$this->uuid) {
                $this->redirect("login");
            }
            if(isset($this->member_info->company_id)){

                $company_name = PersonnelCompany::quickGet($this->member_info->company_id,'company_name');
            }else{
                $company_name ="";
            }
            if(isset($this->member_info->department_id)){
                $department = PersonnelDepartment::quickGet($this->member_info->department_id,'department_name');
            }else{
                $department ="";
            }
            if(isset($this->member_info->jobs_id)){
                $jobs_name = PersonnelJobs::quickGet($this->member_info->jobs_id,'jobs_name');
            }else{
                $jobs_name="";
            }
            $info = [
                "styles"=>$this->isWechatBrowser==true?"":"margin-top:50px",
                "iswechat"=>$this->isWechatBrowser==true?"display:none":"display:block",
                'company_name' => $company_name,
                'department_name' => $department,
                'job_name' => $jobs_name,
                'information' => $this->member_info,

            ];
            $this->assign("info", $info);
            return $this->fetch("index");
        }catch (Exception $e){
            Log::error($e->getMessage());
             $this->redirect("index/myError");
        }


    }
    //请假申请
    public function leave()
    {
        if (!$this->uuid){
            $this->redirect("index/myError");
        }
        $model = new PersonnelUser();
        $da = $model->setCacheSystem();
        $company_name = $da['company'][$this->member_info->company_id]['company_name'];
        $department = $da['department'][$this->member_info->department_id]['department_name'];
        $jobs_name = $da['jobs'][$this->member_info->jobs_id]['jobs_name'];
        $info = [

            'company_name' => $company_name,
            'department_name' => $department,
            'job_name' => $jobs_name,
            'information' => $this->member_info,
        ];
        $this->assign("info", $info);
        return $this->fetch("designer/leave");

    }
//假条列表
    public function leaveList()
{
    if (!$this->uuid) {
        $this->redirect("login");
    }
    return $this->fetch("designer/leaveList");

}
//假条列表（所有的）
    public function leaveListAll()
    {
        if (!$this->uuid) {
            $this->redirect("login");
        }
        return $this->fetch("designer/leaveListAll");

    }
    //假条详情
    public function leaveDetails()
    {
        if (!$this->uuid) {
            $this->redirect("login");
        }

        $guid =$this->request->param('guid');
        if(empty($guid)){
            $this->error("参数错误");
        }

        if($this->request->isPost()) {
        $model=new OfficeLeave();
        $assign_data=$model->getInfoByGuId($guid);
        if ($assign_data){
            $assign_data=$model->getTimeHours($assign_data,'start_time',false);
            $assign_data=$model->getTimeHours($assign_data,'end_time',false);

            return self::showJsonReturnCode(1001,$assign_data->toArray());
        }else{
            return self::showJsonReturnCodeWithOutData(1010);
        }
        }else{

            $guid =$this->request->param('guid');
            $this->assign("guid",$guid);
            return $this->fetch("designer/leaveDetails");
        }


    }

    //假条详情（所有的）
    public function leaveDetailsAll()
    {
        if (!$this->uuid) {
            $this->redirect("login");
        }

        $guid =$this->request->param('guid');
        if(empty($guid)){
            $this->error("参数错误");
        }

        if($this->request->isPost()) {
            $model=new OfficeLeave();
            $assign_data=$model->getInfoByGuId($guid);
            if ($assign_data){
                $assign_data["start_time"]=date("Y-m-d H:i",$assign_data["start_time"]);
                $assign_data["end_time"]=date("Y-m-d H:i",$assign_data["end_time"]);

                return self::showJsonReturnCode(1001,$assign_data->toArray());
            }else{
                return self::showJsonReturnCodeWithOutData(1010);
            }
        }else{

            $guid =$this->request->param('guid');
            $this->assign("guid",$guid);
            return $this->fetch("designer/leaveDetailsAll");
        }


    }

    /**
     * 登陆验证
     * Power by Mikkle
     * QQ:776329498
     * @return array
     */
    public function login(){
        if ($this->request->isAjax()) {


            //数据库字段 网页字段转换
            $param = [
                'username' => 'username',
                'password' => 'password',
            ];
            $param_data = $this->buildParam($param);
            //      $param_data['id'] = $this->request->get('id');
            $check_login = $this->doModelAction($param_data, 'base/PersonnelUser.login', 'base/PersonnelUser', 'checkLogin');
            if (!isset($check_login['code'])) $this->showReturnCodeWithSaveLog(1111);
            if ($check_login['code'] == 1001) {
                $this->setLoginGlobal($check_login['data'], 1);
            }


            $urlSrc = url('index/index');

            $arr=[
                'code'=>$check_login['code'],
                'url'=>$urlSrc,
            ];
            return $arr;
        }else{
            return $this->fetch("login");
        }
    }


    public function workerInfo()
    {
        return $this->fetch("infoin/workerInfo");
    }
//设计师页面
    public function designer()
    {
        $log["info"] = $this->member_info;
        $this->assign("info", $log["info"]);

        return $this->fetch("designer/indexDesigner");
    }
    public function myError()
    {
        return $this->fetch("index/login");
    }

    public function entry()
    {
        return $this->fetch("infoin/entry");
    }

    public function itemEntry()
    {
        return $this->fetch("infoin/itemEntry");
    }

    public function getMenuJsonByGroup($group = "quickList",$role_id = "")
    {

        if (empty($this->member_info)) return json([]);
        $role_id = empty($role_id) ? $this->member_info['role_id'] : $role_id;
        $model = new PersonnelNode();
        $arr_node = Db::table('mk_personnel_role_node_access')->where('role_id', $role_id)->column('node_id');

        $map = [
            "is_mobile" => 1,
            "is_menu" => 1,
            "group" => $group,
            "guid" => ["in", $arr_node]
        ];
        $field = "guid as menu_id,pid,node_name as menu_name,icon ,CASE action_name  when '#' then '' else   CONCAT(module_name ,'/',control_name , '\/' , action_name)  END as url";
        $list = $model->getListByMap($map, $field);

        foreach ($list as $item => $value) {



            switch($value["url"]){
                case "wechat/Project/projectpending":
                    $list[$item]['number'] = $model->getCountObj('base/ProjectAudit',['examine_status'=>0,"company_id"=>$company_id]);;
                    break;
                case "wechat/Designer/contractask":
                    $list[$item]['number'] = $model->getCountObj('base/project/ProjectContractAudit',['examine_status'=>0,"company_id"=>$company_id]);
                    break;

                case "wechat/index/leaveList":
                    $list[$item]['number'] = $model->getCountObj('base/OfficeLeave',['dep_manager'=>0,"company_id"=>$company_id]);
                    break;
                case "wechat/Project/designerPendingList":
                    $list[$item]['number'] =$model->getCountObj('base/design/DesignCheck',['check_status'=>0,"company_id"=>$company_id]);
                    break;
                case "wechat/Project/moneyPending":
                    $list[$item]['number'] =$model->getCountObj('base/project/ProjectDepositAudit',['status'=>1,"company_id"=>$company_id]);
                    break;
                case "wechat/Project/moneyTransfer":
                    $list[$item]['number'] = $model->getCountObj('base/project/ProjectContract',['status'=>1,"company_id"=>$company_id]);
                    break;
                case "wechat/project/budgetList":
                    $list[$item]['number'] = $model->getCountObj('base/budget/ReviseBudget',['status'=>1,'examine_status'=>0,"company_id"=>$company_id]);
                    break;
                case "wechat/project/printList":
                    $list[$item]['number'] = $model->getCountObj('base/budget/PrintRequest',['status'=>1,'examine_status'=>0,"company_id"=>$company_id]);
                    break;
                case "wechat/project/materiaLlist":
                    $list[$item]['number'] = $model->getCountObj('base/engin/EnginProjectMaterialApply',['status'=>1,'examine_status'=>0,"company_id"=>$company_id]);
                    break;
                default:
            }
            if (!empty($list[$item]['url'])) {
                $list[$item]['url'] = Url::build($value['url']);
            }
        }
        return json($list);

    }

    public function getMenuJsonBy($group = "quick",$role_id = "")
    {

        if (empty($this->member_info)) return json([]);
        $role_id = empty($role_id) ? $this->member_info['role_id'] : $role_id;
        $model = new PersonnelNode();
        $arr_node = Db::table('mk_personnel_role_node_access')->where('role_id', $role_id)->column('node_id');

        $map = [
            "is_mobile" => 1,
            "is_menu" => 1,
            "group" => $group,
            "guid" => ["in", $arr_node]
        ];
        $field = "guid as menu_id,pid,node_name as menu_name,icon ,CASE action_name  when '#' then '' else   CONCAT(module_name ,'/',control_name , '\/' , action_name)  END as url";
        $list = $model->getListByMap($map, $field);
        $company_id=$this->member_info->company_id;

        $project_del_list = Db::table("mk_project")->where(["company_id"=>$company_id,"status"=>-1])->column("guid");

        foreach ($list as $item => $value) {
            switch($value["url"]){
                case "wechat/Project/projectpending":
                    $list[$item]['number'] = $model->getCountObj('base/ProjectAudit',['examine_status'=>0,"company_id"=>$company_id,"project_id"=>["not in",$project_del_list]]);
                    break;
                case "wechat/Designer/contractask":
                    $list[$item]['number'] = $model->getCountObj('base/project/ProjectContractAudit',['examine_status'=>0,"company_id"=>$company_id,"status"=>1,"project_id"=>["not in",$project_del_list]]);
                    break;
                case "wechat/index/leaveList":
                    $list[$item]['number'] = $model->getCountObj('base/OfficeLeave',['dep_manager'=>0,"company_id"=>$company_id]);
                    break;
                case "wechat/Project/designerPendingList":
                    $list[$item]['number'] =$model->getCountObj('base/design/DesignCheck',['check_status'=>0,"company_id"=>$company_id,"project_id"=>["not in",$project_del_list]]);
                    break;
                case "wechat/Project/moneyPending":
                    $list[$item]['number'] =$model->getCountObj('base/project/ProjectDepositAudit',['status'=>1,"company_id"=>$company_id,"project_id"=>["not in",$project_del_list]]);
                    break;
                case "wechat/Project/moneyTransfer":
                    $list[$item]['number'] = $model->getCountObj('base/project/ProjectCollectPrice',['status'=>1,'collect_status'=>0,"company_id"=>$company_id,"project_id"=>["not in",$project_del_list]]);
                    break;
                case "wechat/project/budgetList":
                    $list[$item]['number'] = $model->getCountObj('base/budget/ReviseBudget',['status'=>1,'examine_status'=>0,"company_id"=>$company_id,"project_id"=>["not in",$project_del_list]]);
                    break;
                case "wechat/project/printList":
                    $list[$item]['number'] = $model->getCountObj('base/budget/PrintRequest',['status'=>1,'examine_status'=>0,"company_id"=>$company_id,"project_id"=>["not in",$project_del_list]]);
                    break;
                case "wechat/project/materialList":
                    $list[$item]['number'] = $model->getCountObj('base/engin/EnginProjectMaterialApply',['status'=>1,'examine_status'=>0,"company_id"=>$company_id,"project_id"=>["not in",$project_del_list]]);
                    break;
                case "wechat/project/moneyTransferPay":
                    $list[$item]['number'] = $model->getCountObj('base/project/ProjectCollectPricePay',['collect_status'=>0,'status'=>1,"company_id"=>$company_id,"project_id"=>["not in",$project_del_list]]);
                    break;
                case "wechat/project/materialBuyList":
                    $list[$item]['number'] = $model->getCountObj('base/engin/EnginProjectMaterialAddress',['examine_status'=>'0',"company_id"=>$company_id,"project_id"=>["not in",$project_del_list]]);
                    break;
                case "wechat/project/materialChecklist":
                    $list[$item]['number'] = $model->getCountObj('base/engin/EnginProjectMaterialAddress',['examine_status'=>1,'accepatance_status'=>0,"status"=>2,"company_id"=>$company_id,"project_id"=>["not in",$project_del_list]]);
                    break;
                case "wechat/project/projectStepPending":
                    $list[$item]['number'] = $model->getCountObj('base/engin/EnginProjectBuildTypeAudit',['examine_status'=>0,"company_id"=>$company_id,"project_id"=>["not in",$project_del_list]]);
                    break;
                case "wechat/project/projectWorkedPendingList":
                    $list[$item]['number'] = $model->getCountObj('base/engin/EnginProjectCompletionAudit',['examine_status'=>0,"company_id"=>$company_id,"project_id"=>["not in",$project_del_list]]);
                    break;
                case "wechat/project/projectWrongPendingList":

                    $list[$item]['number'] = $model->getCountObj('base/project/WasteApply',['examine_status'=>0,'project_type'=>"1","company_id"=>$company_id,"project_id"=>["not in",$project_del_list]]);
                    break;
                case "wechat/project/projectWrongPendingListBusiness":
                    $list[$item]['number'] = $model->getCountObj('base/project/WasteApply',['examine_status'=>0,'project_type'=>"0","company_id"=>$company_id,"project_id"=>["not in",$project_del_list]]);
                    break;
                case "wechat/project/moneyBackPendingList":
                    $list[$item]['number'] = $model->getCountObj('base/project/ProjectCollectPriceRefund',['examine_status'=>0,"company_id"=>$company_id,"project_id"=>["not in",$project_del_list]]);
                    break;
                case "wechat/human/carPending":
                    $list[$item]['number'] = $model->getCountObj('base/office/OfficeVehicleApply',['examine_status'=>0,"company_id"=>$company_id,"status"=>1]);
                    break;
                case "wechat/budget/revokeList":
                    $list[$item]['number'] = $model->getCountObj('base/finance/RevokeMoney',['examine_status'=>0,"company_id"=>$company_id,"status"=>1]);
                    break;
                case "wechat/budget/payList":
                    $list[$item]['number'] = $model->getCountObj('base/project/ProjectCollectPricePay',['examine_status'=>0,"company_id"=>$company_id,"status"=>1,"collect_status"=>0]);
                    break;
                case "wechat/budget/wasteOutList":
                    $list[$item]['number'] = $model->getCountObj('base/waste_single/WasteProjectApply',['examine_status'=>0,"company_id"=>$company_id,"status"=>1]);
                    break;

                default:
            }

            if (!empty($list[$item]['url'])) {
                $list[$item]['url'] = Url::build($value['url']);
            }
        }
        return json($list);
    }

    public function getMenuJsonByGroupThree($group = "quickListThree",$role_id = "")
    {
        if (empty($this->member_info)) return json([]);
        $role_id = empty($role_id) ? $this->member_info['role_id'] : $role_id;
        $model = new PersonnelNode();
        $arr_node = Db::table('mk_personnel_role_node_access')->where('role_id', $role_id)->column('node_id');
        $map = [
            "is_mobile" => 1,
            "is_menu" => 1,
            "group" => $group,
            "guid" => ["in", $arr_node]
        ];
        $field = "guid as menu_id,pid,node_name as menu_name,icon ,CASE action_name  when '#' then '' else   CONCAT(module_name ,'/',control_name , '\/' , action_name)  END as url";
        $list = $model->getListByMap($map, $field);
        foreach ($list as $item => $value) {
            switch($value["url"]){
                default:
            }
            if (!empty($list[$item]['url'])) {
                $list[$item]['url'] = Url::build($value['url']);
            }
        }
        return json($list);
    }
    public function sign(){
      return $this->fetch("index/sign");
    }
    public function calendar(){
        $company_id= $this->member_info->company_id;
        if (!$this->uuid){
            $this->redirect("index/myError");
        }
        if ($this->request->isPost()){
            $list =  Db::view("mk_personnel_user","uuid,name,department_id,jobs_id,mobile")
                ->view("mk_personnel_department","department_name","mk_personnel_department.guid=mk_personnel_user.department_id")
                ->view("mk_personnel_jobs","jobs_name","mk_personnel_jobs.guid=mk_personnel_user.jobs_id")
                ->where(['mk_personnel_user.company_id'=>$company_id,'mk_personnel_user.status'=>1])
                ->select();
            if ($list){
                return self::showJsonReturnCode(1001,$list);
            }else{
                return self::showJsonReturnCodeWithOutData(1010);
            }
        }
        $this->assign("company_id",$company_id);
        return $this->fetch("calendar");
    }
    //申请试用
    public function apply(){
        $user_agent = $this->request->server('HTTP_USER_AGENT');
        if(strpos($user_agent, 'MicroMessenger') === false ){
            return $this->fetch("apply_code");
        }else{

        }
        return $this->fetch("apply");

    }
}