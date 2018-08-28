<?php
/**
 * Created by PhpStorm.
 * Power By Mikkle
 * Email：776329498@qq.com
 * Date: 2017/8/4
 * Time: 15:06
 */

namespace app\wechat\controller;
use app\base\model\design\DesignCheck;
use app\base\model\DesignPhase;
use app\base\model\Project;
use app\base\model\project\ProjectContractAudit;
use app\base\model\ProjectAudit;
use app\base\model\ProjectContacts;
use app\base\model\ProjectStructure;
use app\base\model\ProjectBuilding;
use app\base\model\ProjectRemindTime;
use app\base\model\ProjectTraceLog;
use app\erp\config\FieldValue;
use app\base\model\Project as ProjectModel;
//use app\base\model\Project as ProjectModel;
use app\erp\controller\BaseEasyUI;
use think\Loader;

class Designer extends Auth
{
    public function addProjectD(){
        if($this->request->isPost()){
            if (!$this->uuid){
                return self::showReturnCodeWithOutData(1004);
            }
            $validate_name='base/Project.edit';
            $model_name='base/Project';
            $save_data = $this->request->post();
            $save_data['guid'] = $this->request->param("id");
            $re=$this->doModelAction($save_data, $validate_name, $model_name, 'saveInfoByGuid');
            if($re["code"]=="1001"){
                $model=new ProjectTraceLog();
                $log=[];
                $log["log_content"]="修改项目信息";
                $log["guid"]=$this->request->param('project_guid');
                $log["uuid"]=$this->uuid;
                $log["jobs_id"]=$this->member_info->jobs_id;
                $log["department_id"]=$this->member_info->department_id;
                $model->addProjectLog($log);
            }
            return json($re);
        }else{
            if (!$this->uuid){
                return self::showJsonReturnCodeWithOutData(1004);
            }
            $model=new FieldValue();
            $assign_data= [
                'data' =>$model->decoration_grade,
                'dataa' =>$model->color_orientation,
                'datab' =>$model->decoration_style,
                'datac' =>$model->decoration_type,
                'datad' =>$model->customer_source,
                'datae' =>$model->sex_value,
                'dataf' =>$model->householder_relation,
                'value' =>0,
                "title"=>"业务登记",
                 "url"=>('/erp/project/addProjectEntry')
            ];
            $this->assign($assign_data);
            return $this->fetch("addProjectD");
        }
    }




    public function addProjectDCopy(){
        if($this->request->isPost()){
            if (!$this->uuid){
                return self::showReturnCodeWithOutData(1004);
            }
            $validate_name='base/Project.edit';
            $model_name='base/Project';
            $save_data = $this->request->post();
            $save_data['guid'] = $this->request->param("id");
            $re=$this->doModelAction($save_data, $validate_name, $model_name, 'saveInfoByGuid');
            if($re["code"]=="1001"){
                $model=new ProjectTraceLog();
                $log=[];
                $log["log_content"]="修改项目信息";
                $log["guid"]=$this->request->param('project_guid');
                $log["uuid"]=$this->uuid;
                $log["jobs_id"]=$this->member_info->jobs_id;
                $log["department_id"]=$this->member_info->department_id;
                $model->addProjectLog($log);
            }
            return json($re);
        }else{
            if (!$this->uuid){
                return self::showJsonReturnCodeWithOutData(1004);
            }
            $model=new FieldValue();
            $assign_data= [
                'data' =>$model->decoration_grade,
                'dataa' =>$model->color_orientation,
                'datab' =>$model->decoration_style,
                'datac' =>$model->decoration_type,
                'datad' =>$model->customer_source,
                'datae' =>$model->sex_value,
                'dataf' =>$model->householder_relation,
                'value' =>1,
                "title"=>"客户录入",
                "url"=>('/erp/project/addProjectEntryCopy')
            ];
            $this->assign($assign_data);
            return $this->fetch("addProjectD");
        }
    }
    //待签合同项目列表（个人）
    public function contract(){

        $ass=["url"=>url('erp/design_extract/shownocontract'),
            "message"=>"个人"];
        $this->assign($ass);
        return $this->fetch("designer/contract");
    }
    //待签合同项目列表（部门）
    public function contractdep(){

        $ass=["url"=>url('erp/design_extract/shownocontractbydep'),
            "message"=>"部门"];
        $this->assign($ass);
        return $this->fetch("designer/contract");
    }
    //待签合同项目列表（公司）
    public function contractcom(){

        $ass=["url"=>url('erp/design_extract/shownocontractbycom'),
            "message"=>"公司"];
        $this->assign($ass);
        return $this->fetch("designer/contract");
    }
    public function contractask(){

    return $this->fetch("designer/contractask");
}
//
    public function contractPending(){
        $guid =$this->request->param('guid');
        if(empty($guid)){
            $this->error("参数错误");
        }
        $number =$this->request->param('number');
        if ($this->request->isPost()){
            $model=new  ProjectContractAudit();
            $list=$model->getListByMap(['project_id'=>$guid,'book_number'=>$number],'',[],true);
            return $list;
        }else{
            $this->assign("number",$number);
            $this->assign("project_guid",$guid);
            return $this->fetch("designer/contractPending");
        }

    }
    //已签合同项目列表页（个人）
    public function contracted(){
        $ass=["url"=>url('erp/design_extract/showcontractprojectlist'),
            "message"=>"个人"];
        $this->assign($ass);
        return $this->fetch("designer/contracted");
    }
    //已签合同项目列表页（公司）
    public function contractedCom(){
        $ass=["url"=>url('erp/design_extract/showconprolistbycom'),
                "message"=>"公司"];
        $this->assign($ass);
        return $this->fetch("designer/contracted");
    }
    public function contractedDetails(){
        $guid =$this->request->param('guid');

        if(empty($guid)){
            $this->error("参数错误");
        }
        $this->assignFieldValue();
        $model=new ProjectModel();
        $assign_data=$model->getInfoByGuId($guid);
        $this->assign("project",$assign_data);
        $this->assign("project_guid",$guid);
        return $this->fetch("designer/contractedDetails");
    }
    public function contractDetails(){
        $guid =$this->request->param('guid');

        if(empty($guid)){
            $this->error("参数错误");
        }
        $this->assignFieldValue();
        $model=new ProjectModel();
        $assign_data=$model->getInfoByGuId($guid);
        $this->assign("project",$assign_data);

        $number =$this->request->param('number');
        $this->assign("number",$number);
        $this->assign("project_guid",$guid);
        return $this->fetch("designer/contractDetails");
    }
    //拍照上传
    public function photoUpload(){

        return $this->fetch("project/photoUpload");

    }
    public function projectPendingD(){

        $guid =$this->request->param('guid');
        $this->assign("guid",$guid);
        return $this->fetch("projectPendingD");

    }
    public function transferRequest(){
        $guid =$this->request->param('guid');
        $this->assign("guid",$guid);
        return $this->fetch("transferRequest");

    }

    //图片上传
    public function addFileD(){
        if (!$this->uuid){
            $this->redirect("index/myError");
        }
        $guid =$this->request->param('guid');
        $this->assign("project_guid",$guid);
        return $this->fetch("addFileD");

    }
    //文件上传
    public function addFileDesigner(){
        if (!$this->uuid){
            $this->redirect("index/myError");
        }

        $guid =$this->request->param('guid');
        $this->assign("project_guid",$guid);
        return $this->fetch("addFileDesigner");

    }
//文件上传（设计师）
    public function fileDesigner(){
        if (!$this->uuid){
            $this->redirect("index/myError");
        }

        $id =$this->request->param('id');
        $this->assign("id",$id);
        $guid =$this->request->param('guid');
        $this->assign("project_guid",$guid);
        return $this->fetch("fileDesigner");

    }
    public function seePhotoD(){
        if (!$this->uuid){
            $this->redirect("index/myError");
        }
        $guid =$this->request->param('guid');
        $this->assign("project_guid",$guid);
        return $this->fetch("seePhotoD");

    }
    public function progressImg(){
        if (!$this->uuid){
            $this->redirect("index/myError");
        }

        $id =$this->request->param('id');
        $this->assign("id",$id);
        $guid =$this->request->param('guid');
        $this->assign("project_guid",$guid);
        return $this->fetch("progressImg");

    }
    public function progressFile(){
        if (!$this->uuid){
            $this->redirect("index/myError");
        }

        $id =$this->request->param('id');
        $this->assign("id",$id);
        $guid =$this->request->param('guid');
        $this->assign("project_guid",$guid);
        return $this->fetch("progressFile");

    }
    //文件上传
    public function addFileFile(){
        if (!$this->uuid){
            $this->redirect("index/myError");
        }
        $guid =$this->request->param('guid');
        $this->assign("project_guid",$guid);
        return $this->fetch("addFileFile");

    }

    public function showDesignPhotoList($guid=""){  //显示设计进度图片列表
        if(empty($guid)) return self::showJsonReturnCode(1003);
        $model=new DesignPhoto();

        $list=$model->getList(['design_id'=>$guid]);
        return $list;
    }

    public function addImg(){
        if (!$this->uuid){
            $this->redirect("index/myError");
        }
        $id =$this->request->param('id');
        $this->assign("id",$id);
        $guid =$this->request->param('guid');
        $this->assign("project_guid",$guid);
        return $this->fetch("addImg");
    }
    //转部申请
    public function transferDetailsD(){
        $data["guid"] =$this->request->param('guid/s');
        if($this->request->isPost()) {
            if (!$this->uuid) {
                return self::showReturnCodeWithOutData(1004);
            }
            $model =new ProjectAudit();
            $map = ["company_id"=>$this->member_info->company_id];
            $map["project_id"] = $data["guid"];
            $append=["project_name","transfer_name","department_name",
                "transfer_status_name","examine_status_name"];
            $list=$model->getListByMap($map,true,$append,false);
            if ($list){
                return self::showJsonReturnCode(1001,$list);
            }else{
                return self::showJsonReturnCodeWithOutData(1010);
            }

        }else{
            $this->assign($data);

            return $this->fetch("transferDetailsD");
        }
    }
//转部审核详情页
    public function pendingDetailsD(){

        $data["guid"] =$this->request->param('guid/s');
        $data["id"] =$this->request->param('id/n');
        if($this->request->isPost()) {
            if (!$this->uuid) {
                return self::showReturnCodeWithOutData(1004);
            }
            $model =new ProjectAudit();
            $map = ["company_id"=>$this->member_info->company_id,'examine_status'=>'0','transfer_status'=>'1','transfer_type'=>'2'];
            $map["id"] = $data["id"];
            $append=["project_name","transfer_name","department_name"];
            $info=$model->getInfoByMap($map,true,$append,false);
            if ($info){
                return self::showJsonReturnCode(1001,$info);
            }else{
                return self::showJsonReturnCodeWithOutData(1010);
            }
        }else{
            $this->assign($data);

            return $this->fetch("pendingDetailsD");
        }
    }
//项目列表页（设计师个人）
    public function projectListDesigner(){

        if (!$this->uuid){
            $this->redirect("index/myError");
        }
        return $this->fetch("designer/projectListDesigner");
    }
//项目列表页（设计师公司）
    public function projectListDesignerCom(){

        if (!$this->uuid){
            $this->redirect("index/myError");
        }
        return $this->fetch("designer/projectListDesignerCom");
    }
    //项目列表页（设计师部门）
    public function projectListDesignerDep(){

        if (!$this->uuid){
            $this->redirect("index/myError");
        }
        return $this->fetch("designer/projectListDesignerDep");
    }
    public function progress(){

        if (!$this->uuid){
            $this->redirect("index/myError");
        }
        $this->assign("member_info",$this->member_info);
        $guid =$this->request->param('guid');
        $this->assign("project_guid",$guid);
        $type =$this->request->param('type');
        $this->assign("type_back",$type);
        $pro_info = Project::get(["guid"=>$guid])->getData();
        $this->assign("pro_info",$pro_info);
        return $this->fetch("designer/progress");
    }
    public function progressDone(){
        if (!$this->uuid){
            $this->redirect("index/myError");
        }
        $typeHasDone =$this->request->param('typeHasDone');
        $type =$this->request->param('type');
        $this->assign("member_info",$this->member_info);
        $guid =$this->request->param('guid');
        $model=new DesignPhase();
        if($this->request->isPost()){
            if(empty($guid)) return self::showJsonReturnCode(1003);

            $map=[
                "project_id"=>$guid,
                "status"=>$typeHasDone
            ];
            $arr = $model->getListHasType($map);
//            $arr["acceptance_date"] = date("Y-m-d H:i:s",$arr['acceptance_date']);

           $response=[
               "rows"=>$arr
           ];
            return $response;
        }else{
            $arra=[
                'typeHasDone'=>$typeHasDone,
                'project_guid'=>$guid,

            ];
            $this->assign("project_guid",$guid);
            $this->assign($arra);
            $this->assign("type_back",$type);
            $this->assign("type",$type);
            $pro_info = Project::get(["guid"=>$guid])->getData();


           $pro_info = $model->getTime($pro_info,true);


            $this->assign("pro_info",$pro_info);
            return $this->fetch("designer/progress");
        }
    }
    public function progressCom(){

        if (!$this->uuid){
            $this->redirect("index/myError");
        }
        $this->assign("member_info",$this->member_info);
        $guid =$this->request->param('guid');
        $pro_info = Project::get(["guid"=>$guid])->getData();
        $this->assign("pro_info",$pro_info);
        $this->assign("project_guid",$guid);
        return $this->fetch("designer/progressCom");
    }
    //分配设计师
    public function designer(){
        if (!$this->uuid){
            $this->redirect("index/myError");
        }
        $this->assign("member_info",$this->member_info);
        $guid =$this->request->param('guid');

        $this->assign("project_guid",$guid);
        return $this->fetch("designer/designer");
    }
    //重新分配设计师
    public function designerAgain(){
        if (!$this->uuid){
            $this->redirect("index/myError");
        }
        $this->assign("member_info",$this->member_info);
        $guid =$this->request->param('guid');

        $this->assign("project_guid",$guid);
        return $this->fetch("designer/designerAgain");
    }
    //解除分配设计师
    public function designerDelete(){
        if (!$this->uuid){
            $this->redirect("index/myError");
        }
        $this->assign("member_info",$this->member_info);
        $guid =$this->request->param('guid');

        $this->assign("project_guid",$guid);
        return $this->fetch("designer/designerDelete");
    }
    public function projectContactsD(){
        $guid =$this->request->param('guid');
        if($this->request->isPost()){
            if(empty($guid)){
                return self::showJsonReturnCodeWithOutData(1003,"参数丢失");
            }
            $model=new ProjectContacts();
            $map=[
                "status"=>1,
                "project_guid"=>$guid
            ];
            $project_info= $model->getList($map);
            $return_data["sex"]=FieldValue::getFieldValue("sex","array");
            $return_data["householder_relation"]=FieldValue::getFieldValue("householder_relation","array");
            $return_data["project_list"] =$project_info;
            return self::showJsonReturnCode(1001,$return_data);
        }else{

            if(empty($guid)){
                $this->error("参数错误");
            }
            $this->assign("project_guid",$guid);

            return $this->fetch("projectContactsD");
        }

    }

//项目提醒信息
    public function projectCollD(){
        $guid =$this->request->param('guid');
        if($this->request->isPost()){
            if(empty($guid)){
                return self::showJsonReturnCodeWithOutData(1003,"参数丢失");
            }

            $model=new ProjectRemindTime();
            $map=[
                "status"=>1,
                "project_guid"=>$guid
            ];
            $project_info= $model->getList($map);

            return json($project_info);
        }else{
            $model=new ProjectRemindTime();
            $map=[
                "status"=>1,
                "project_guid"=>$guid
            ];
            $project_info= $model->getList($map);
            if(empty($guid)){
                $this->error("参数错误");
            }
            $arr=[];
            $arr["name"]=$this->member_info->username;
            $arr["guid"]=$guid;
            $l=new \app\base\model\Project();
            $li=$l->getList(['guid'=>$guid]);
            if (!empty($li)){
                $arr["pro_name"]=$li[0]["project_name"];
            }else{
                $arr["pro_name"]="";
            }

            if (!empty($project_info)){
                foreach ($project_info as $value){
                    $value['remind_time']=date("Y-m-d H:i:s",$value['remind_time']);
                }
            }
            $this->assign("project_guid",$guid);
            $this->assign("project",$project_info);
            $this->assign("pro",$arr);
            return $this->fetch("projectCollD");
        }
    }

//房屋信息
    public function projectStructureD()
    {
        $guid = $this->request->param('guid');
        if ($this->request->isPost()) {
            if (empty($guid)) {
                return self::showJsonReturnCodeWithOutData(1003, "参数丢失");
            }
            $data=[];
            $append =[
                "living_room_structure_name",
                "house_orientation_name",
                "house_type_name",
                "housing_use_name",
                "lighting_name",
            ];
            $data=Loader::model("base/ProjectStructure")->getInfoByMap(["project_guid"=>$guid],true,$append);
            if($data){
                $return = $data->toArray();
                $return["lighting"]=FieldValue::getFieldValue("lighting","array");
                $return["living_room_structure"]=FieldValue::getFieldValue("living_room_structure","array");
                $return["house_orientation"]=FieldValue::getFieldValue("house_orientation","array");
                $return["house_type"]=FieldValue::getFieldValue("house_type","array");
                $return["housing_use"]=FieldValue::getFieldValue("housing_use","array");
                return self::showJsonReturnCode(1001,$return);

            }else{
                $data["lighting"]=FieldValue::getFieldValue("lighting","array");
                $data["living_room_structure"]=FieldValue::getFieldValue("living_room_structure","array");
                $data["house_orientation"]=FieldValue::getFieldValue("house_orientation","array");
                $data["house_type"]=FieldValue::getFieldValue("house_type","array");
                $data["housing_use"]=FieldValue::getFieldValue("housing_use","array");
                return self::showJsonReturnCode(1001,$data);
            }
        } else {
            return $this->fetch("projectStructureD");
        }
    }
//楼盘信息
    public function projectBuildingD()
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
            return $this->fetch("projectBuildingD");
        }
    }
//日志信息
    public function projectLogD()
    {
        $guid = $this->request->param('guid');
        if ($this->request->isPost()) {
            if (empty($guid)) {
                return self::showJsonReturnCodeWithOutData(1003, "参数丢失");
            }
            $map=[
                "status"=>1,
                "project_guid"=>$guid
            ];
            $system_info = Loader::model("base/ProjectTraceLog")->setCacheSystem();
            $data=Loader::model("base/ProjectTraceLog")->getList($map);
            if(is_array($data)){
                $return_data=[];
                foreach($data as $item=>$value){
                    $return_data[$item]=$data[$item]->toArray();
                    $return_data[$item]["log_type_name"]=FieldValue::getFieldValue("log_type","item")[$value['log_type']];
                    //用户姓名
                    $return_data[$item]["name"]=isset($system_info["user"][$data[$item]["uuid"]])?$system_info["user"][$data[$item]["uuid"]]->name:"未知用户";
                    //用户职位
                    $return_data[$item]["jobs_name"]=isset($system_info["jobs"][$data[$item]["jobs_id"]])?$system_info["jobs"][$data[$item]["jobs_id"]]->jobs_name:"未知职位";
                    //用户部门
                    $return_data[$item]["department_name"]=isset($system_info["department"][$data[$item]["department_id"]])?$system_info["department"][$data[$item]["department_id"]]->department_name:"未知部门";
                    unset($return_data[$item]["uuid"]);
                    unset($return_data[$item]["jobs_id"]);
                    unset($return_data[$item]["department_id"]);
                    unset($return_data[$item]["status"]);
                    unset($return_data[$item]["update_time"]);
                }
                return self::showJsonReturnCode(1001,$return_data);
            }else{
                return self::showJsonReturnCodeWithOutData(1010);
            }

        } else {
            $this->assign("member_info",$this->member_info);
            $this->assign("project_guid",$guid);
            return $this->fetch("projectLogD");
        }
    }
//项目详情（设计师）
    public function projectDetailsDesigner(){
        $guid =$this->request->param('guid');
        if(empty($guid)){
            $this->error("参数错误");
        }
        $this->assignFieldValue();
        $model=new ProjectModel();
        $assign_data=$model->getInfoByGuId($guid)->toArray();
        $assign_data["hasDone"]=$model->getCountObj("base/DesignPhase",["project_id"=>$guid,"status"=>1]);
        $assign_data["hasNoDone"]=$model->getCountObj("base/DesignPhase",["project_id"=>$guid,"status"=>2]);
        $assign_data["house"]=$model->getCountObj('base/ProjectStructure',["project_guid"=>$guid]);
        $assign_data["building"]=$model->getCountObj('base/ProjectBuilding',["project_guid"=>$guid]);
        $assign_data["log"]=$model->getCountObj('base/ProjectTraceLog',["project_guid"=>$guid]);
        $assign_data["contacts"]=$model->getCountObj('base/ProjectContacts',["project_guid"=>$guid]);
        $assign_data["time"]=$model->getCountObj('base/ProjectRemindTime',["project_guid"=>$guid]);
        $assign_data["image"]=$model->getCountObj('base/project/ProjectPhoto',["project_id"=>$guid]);
        $assign_data["audit"]=$model->getCountObj('base/ProjectAudit',["project_id"=>$guid]);

        $this->assign("project",$assign_data);
        return $this->fetch("designer/projectDetailsDesigner");
    }
    public function projectDetailsCom(){


        $guid =$this->request->param('guid');
        if(empty($guid)){
            $this->error("参数错误");
        }
        $this->assignFieldValue();
        $model=new ProjectModel();
        $assign_data=$model->getInfoByGuId($guid)->toArray();
//        $assign_data["hasDone"]=$model->getCountObj("base/DesignPhase",["project_id"=>$guid,"status"=>1]);
//        $assign_data["hasNoDone"]=$model->getCountObj("base/DesignPhase",["project_id"=>$guid,"status"=>2]);
        $assign_data["house"]=$model->getCountObj('base/ProjectStructure',["project_guid"=>$guid]);
        $assign_data["building"]=$model->getCountObj('base/ProjectBuilding',["project_guid"=>$guid]);
        $assign_data["log"]=$model->getCountObj('base/ProjectTraceLog',["project_guid"=>$guid]);
        $assign_data["contacts"]=$model->getCountObj('base/ProjectContacts',["project_guid"=>$guid]);
        $assign_data["time"]=$model->getCountObj('base/ProjectRemindTime',["project_guid"=>$guid]);
        $assign_data["image"]=$model->getCountObj('base/project/ProjectPhoto',["project_id"=>$guid]);
        $assign_data["audit"]=$model->getCountObj('base/ProjectAudit',["project_id"=>$guid]);

        $this->assign("project",$assign_data);
        return $this->fetch("designer/projectDetailsCom");





//
//        $guid =$this->request->param('guid');
//        if(empty($guid)){
//            $this->error("参数错误");
//        }
//        $this->assignFieldValue();
//        $model=new ProjectModel();
//        $assign_data=$model->getInfoByGuId($guid)->toArray();
//
//        $assign_data["hasDone"]=$model->getCountObj("base/DesignPhase",["project_id"=>$guid,"status"=>1]);
//        $assign_data["hasNoDone"]=$model->getCountObj("base/DesignPhase",["project_id"=>$guid,"status"=>2]);
//        $this->assign("project",$assign_data);
//        return $this->fetch("designer/projectDetailsCom");
    }
    public function assignFieldValue(){
        $field_value = new FieldValue();
        $this ->assign('value',(array)$field_value);
    }
//量房客户列表
    public function projectRoom()
    {
        if (!$this->uuid) {
            $this->redirect("login");
        }
        $model=new DesignPhase();
        $list=$this->showEasyUiJsonList('base/Project',false,['company_id'=>$this->member_info->company_id]);
        $arr=[];
        $arr["rows"]=[];
        if (!empty($list["rows"])){
            array_unique($list["rows"]);
            foreach ($list["rows"] as $key=> $value){
                $obj=Loader::model('base/DesignPhase')->where(['design_type'=>1, "project_id"=>$value["guid"]])->find();

                if(!empty($obj)){

                    $arr["rows"][]=$value;
                }
                unset($list["rows"][$key]);
            }
        }
        $arr['total']=count($arr['rows']);
        if($this->request->isPost()){
            if (!$this->uuid){
                return self::showReturnCodeWithOutData(1004);
            }else{
                return json($arr);
            }
        }else{
            return $this->fetch("designer/projectRoom");
        }
    }
    //设计师图片
    public function designerImg(){
        if (!$this->uuid){
            $this->redirect("index/myError");
        }
        $id =$this->request->param('id');
        $this->assign("id",$id);
        $guid =$this->request->param('guid');
        $this->assign("project_guid",$guid);
        return $this->fetch("project/designerImg");
    }
    //设计师文件
    public function designerFile(){
        if (!$this->uuid){
            $this->redirect("index/myError");
        }
        $id =$this->request->param('id');
        $this->assign("id",$id);
        $guid =$this->request->param('guid');
        $this->assign("project_guid",$guid);
        return $this->fetch("project/designerFile");
    }
}