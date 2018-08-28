<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/22
 * Time: 14:27
 */

namespace app\base\model;



use app\erp\config\FieldValue;
use think\Cache;
use think\Db;
use think\Exception;

class Project extends BaseHash
{
    protected $table = "mk_project";
    protected $insert = ['guid','status'=>1];
    protected $autoWriteTimestamp = true;
    protected $hashKey="guid";

    protected function setGuidAttr($value, $data)
    {
        do {
            $mk_guid= $this->create_uuid();
        } while ($this->where('guid',$mk_guid)->count()==1);
        return $mk_guid ;
    }

    protected function getDecorationStyleNameAttr($value, $data){
        $array=FieldValue::getFieldValue("decoration_style");
        return isset($array[$data["decoration_style"]]) ? $array[$data["decoration_style"]] : $value;

    }

    public function getProject($guid){
        $pro=$this->getList(["guid"=>$guid],'project_name,decoration_area,project_address,decoration_style');
        $pro_address="";
        $pro_area="";
        $pro_style="";
        $pro_name="";
        if (!empty($pro)){
            $pro_name=$pro[0]['project_name'].'预算书';
            $pro_address=$pro[0]['project_address'];
            $pro_area=$pro[0]['decoration_area'];
            $pro_style=$pro[0]['decoration_style'];
        }else{
            $pro_name="预算书";
        }
        return $arr=[
            'pro_address'=>$pro_address,
            'pro_area'=>$pro_area,
            'pro_style'=>$pro_style,
            'name'=>$pro_name,
        ];
    }


    public function getList($map = [],$field=false){
        if (empty($map)){
            $map = [];
        }
        if (empty($field)){
            $field = '';
        }
        return  $this->where($map)->field($field)->select();
    }

    public function getListAll($map = [],$field=false){
        if (empty($map)){
            $map = [];
        }
        if (empty($field)){
            $field = '';
        }
        return  $this->where($map)->field($field)->select();

    }

    public function saveProjectWithContacts($data_project,$data_contacts){

        try{
            $this->startTrans();
            $this->data($data_project)->isUpdate(false)->save();
            $project_guid = $this->guid;
            if (empty($project_guid)) throw new \Exception("项目信息添加失败");
            $data_contacts['project_guid'] = $project_guid;
            $this->hasMany('ProjectContacts', 'project_guid', 'guid')->save($data_contacts);
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }

    public function delData($id){
             if(empty($id)) return self::showReturnCodeWithOutData(1003,'没有接收到需要删除的主材数据');
        try{
            $this->startTrans();
            $this->data(['status'=>-1])->isUpdate(true,['guid'=>$id])->save();
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();

            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }


    public function showProListByUserCopy($uuid,$company_id){
        $department_type=array_search("设计部",FieldValue::getFieldValue("department_type"))?:3;
        $list=Db::view('mk_project_related_person','project_id')
            ->view('mk_project','*','mk_project.guid=mk_project_related_person.project_id')
            ->where('mk_project_related_person.uuid','=',$uuid)
            ->where('mk_project_related_person.department_type','=',$department_type)
            ->where('mk_project.company_id','=',$company_id)
            ->where('mk_project.feedback_stage','=',2)
            ->select();
        return self::showReturnCode(1001,['arr'=>$list,'total'=>count($list)]);




//        $arr=[];
//        //  $arr["total"]=Loader::model("base/ProjectRelatedPerson")->getCont(["uuid"=>$this->member_info->uuid,'department_type'=>$department_type]);
//        $arr["rows"]=[];
//        $proList=Loader::model("base/ProjectRelatedPerson")->getList(["uuid"=>$this->member_info->uuid,'department_type'=>$department_type],"project_id");
//
//        foreach($proList as $value){
//            $c=Loader::model("base/Project")->getInfoByGuId($value["project_id"],["company_id"=>$this->member_info->company_id,'feedback_stage'=>'2']);
//            if (!empty($c)){
//                $arr["rows"][]=$c;
//            }
//        }
////            $list=$this->showEasyUiJsonList($model,false,["company_id"=>$this->member_info->company_id,'feedback_stage'=>'2'],false,false);
//        if (!empty($arr["rows"])){
//            foreach($arr["rows"] as $value) {
//                if (empty($value)) {
//                    continue;
//                }
//                $ad = Loader::model("base/ProjectAudit")->getDataByProject(["project_id" => $value["guid"], 'transfer_type' => '2', 'status' => 1]);
//                $track = Loader::model("base/ProjectRelatedPerson")->where(["project_id" => $value["guid"], 'department_type' => $department_type])->select();
//                $count=Loader::model("base/BudgetDefaultBook")->where(['project_id'=>$value["guid"],'status'=>1])->count();
//                if ($count>0) {  //判断该项目是否创建了预算
//                    $value['budget'] = 1;
//                } else {
//                    $value['budget'] = 0;
//                }
//                $ch = Loader::model('base/budget/ReviseBudget')->getFind(['project_id' => $value['guid']]);
//                $value['changeStatus'] = 0;   //判断该项目的预算有没有申请预算修改
//                if (!empty($ch)) {
//                    if ($ch['examine_status'] == 0) {
//                        $value['changeStatus'] = 1;
//                    }
//                }
//                if (empty($ad)) {
//                    $value["transfer_status"] = "0";
//                    $value["transfer_desc"] = "";
//                    $value["examine_status"] = "";
//                    $value["examine_desc"] = "";
//                } else {
//                    $value["transfer_status"] = $ad[0]["transfer_status"];  //转部状态
//                    $value["transfer_desc"] = $ad[0]["transfer_desc"];
//                    $value["examine_status"] = $ad[0]["examine_status"];    //审核状态
//                    $value["examine_desc"] = $ad[0]["examine_desc"];
//                }
//                if (empty($track)) {
//                    $value["tracking_name"] = "";
//                } else {
//                    $m = new Project();
//                    $user = $m->setCacheSystem()['user'];
//                    $dep = $m->setCacheSystem()['department'];
//                    $str = '';
//                    $key_last = key($track); //获取最后一个下标
//                    foreach ($track as $k => $v) {
//                        $depart_id = $user[$v["uuid"]]["department_id"];
//                        if ($k != $key_last) {
//                            $str .= "" . $dep["$depart_id"]["department_name"] . "---" . $user[$v["uuid"]]["name"] . ',';
//                        } else {
//                            $str .= "" . $dep["$depart_id"]["department_name"] . "---" . $user[$v["uuid"]]["name"];
//                        }
//                    }
//                    $value["tracking_name"] = $str;
//                }
//            }
//        }
//        $arr["total"]=count($arr["rows"]);
//        return $arr;
    }
    protected function timer($time){
        switch ($time){
            case 'now':
                $timer='m';
                break;
            case 'last':
                $timer='last month';
                break;
            default:
                $timer='m';
                break;
        }
        return $timer;
    }
    public function lookMoreOfUser($time,$id,$company,$limit="20",$page="1"){
        $timer=$this->timer($time);
        $list=Db::view('mk_project','project_name,guid,status,engin_status,feedback_stage,create_time')
            ->where('uuid','=',$id)
            ->limit($limit)
            ->page($page)
            ->where('company_id','=',$company)
            ->whereTime('create_time',$timer)
            ->select();
        $list=$this->getTime($list,'create_time',false);
        return ['total'=>count($list),'rows'=>$list];
    }
    public function lookSuccessOfUser($time,$id,$company,$limit="20",$page="1"){
        $timer=$this->timer($time);
        $list=Db::view('mk_project','project_name,guid,status,engin_status,feedback_stage,create_time')
            ->where('uuid','=',$id)
            ->where('company_id','=',$this->member_info->company_id)
            ->where('feedback_stage','>',1)
            ->limit($limit)
            ->page($page)
            ->where('status','=',1)
            ->whereTime('create_time',$timer)
            ->select();
        $list=$this->getTime($list,'create_time',false);
        return ['total'=>count($list),'rows'=>$list];
    }
    public function lookFalseOfUser($time,$id,$company,$limit="20",$page="1"){
        $timer=$this->timer($time);
        $list=Db::view('mk_project','project_name,guid,status,engin_status,feedback_stage,create_time')
            ->where('uuid','=',$id)
            ->where('company_id','=',$this->member_info->company_id)
            ->where('status','=',-2)
            ->limit($limit)
            ->page($page)
            ->whereTime('create_time',$timer)
            ->select();
        $list=$this->getTime($list,'create_time',false);
        return ['total'=>count($list),'rows'=>$list];
    }
    public function lookNowOfUser($time,$id,$company,$limit="20",$page="1"){
        $timer=$this->timer($time);
        $list=Db::view('mk_project','project_name,guid,status,engin_status,feedback_stage,create_time')
            ->where('uuid','=',$id)
            ->where('company_id','=',$this->member_info->company_id)
            ->where('status','=',1)
            ->where('feedback_stage','=',1)
            ->limit($limit)
            ->page($page)
            ->whereTime('create_time',$timer)
            ->select();
        $list=$this->getTime($list,'create_time',false);
        return ['total'=>count($list),'rows'=>$list];
    }

    public function lookMoreOfUserDesign($time,$id,$company,$limit="20",$page="1"){
        $timer=$this->timer($time);
        $department_type=array_search("设计部",FieldValue::getFieldValue("department_type"))?:3;
        $list=Db::view('mk_project_related_person','uuid')
            ->view('mk_project','project_name,guid,status,engin_status,feedback_stage,create_time','mk_project.guid=mk_project_related_person.project_id')
            ->where('mk_project_related_person.uuid','=',$id)
            ->where('mk_project_related_person.uuid','=',$id)
            ->where('mk_project.company_id','=',$this->member_info->company_id)
            ->where('status','=',1)
            ->where('feedback_stage','=',2)
            ->where('mk_project_related_person.department_type','=',$department_type)
            ->whereTime('create_time',$timer)
            ->limit($limit)
            ->page($page)
            ->select();
        $list=$this->getTime($list,'create_time',false);
        return ['total'=>count($list),'rows'=>$list];
    }
    public function lookSuccessOfUserDesign($time,$id,$company,$limit="20",$page="1"){
        $timer=$this->timer($time);
        $department_type=array_search("设计部",FieldValue::getFieldValue("department_type"))?:3;
        $list=Db::view('mk_project_related_person','uuid')
            ->view('mk_project','project_name,guid,status,engin_status,feedback_stage,create_time','mk_project.guid=mk_project_related_person.project_id')
            ->view('mk_project_contract_audit','project_id','mk_project_contract_audit.project_id=mk_project_related_person.project_id')
            ->where('mk_project_related_person.uuid','=',$id)
            ->where('mk_project_contract_audit.examine_status','=',1)
            ->where('mk_project.company_id','=',$this->member_info->company_id)
            ->where('status','=',1)
            ->where('mk_project_related_person.department_type','=',$department_type)
            ->whereTime('create_time',$timer)
            ->limit($limit)
            ->page($page)
            ->select();
        $list=$this->getTime($list,'create_time',false);
        return ['total'=>count($list),'rows'=>$list];
    }
    public function lookFalseOfUserDesign($time,$id,$company,$limit="20",$page="1"){
        $timer=$this->timer($time);
        $department_type=array_search("设计部",FieldValue::getFieldValue("department_type"))?:3;
        $list=Db::view('mk_project_related_person','uuid')
            ->view('mk_project','project_name,guid,status,engin_status,feedback_stage,create_time','mk_project.guid=mk_project_related_person.project_id')
            ->where('mk_project_related_person.uuid','=',$id)
            ->where('mk_project.status','=',-2)
            ->where('mk_project.company_id','=',$this->member_info->company_id)
            ->where('mk_project_related_person.department_type','=',$department_type)
            ->whereTime('create_time',$timer)
            ->limit($limit)
            ->page($page)
            ->select();
        $list=$this->getTime($list,'create_time',false);
        return ['total'=>count($list),'rows'=>$list];
    }
    public function lookNowOfUserDesign($time,$id,$company,$limit="20",$page="1"){
        $timer=$this->timer($time);
        $department_type=array_search("设计部",FieldValue::getFieldValue("department_type"))?:3;
        $list=Db::view('mk_project_related_person','uuid')
            ->view('mk_project','project_name,guid,status,engin_status,feedback_stage,create_time','mk_project.guid=mk_project_related_person.project_id')
            ->view('mk_project_contract_audit','project_id','mk_project_contract_audit.project_id=mk_project_related_person.project_id')
            ->where('mk_project_related_person.uuid','=',$id)
            ->where('mk_project_contract_audit.examine_status','<>',1)
            ->where('mk_project.company_id','=',$this->member_info->company_id)
            ->where('status','=',1)
            ->where('mk_project_related_person.department_type','=',$department_type)
            ->whereTime('create_time',$timer)
            ->limit($limit)
            ->page($page)
            ->select();
        $list=$this->getTime($list,'create_time',false);
        return ['total'=>count($list),'rows'=>$list];
    }



    //获取自己的工作数量
    public function getUserData(){
        $num=$this->where(["uuid"=>$this->uuid,'status'=>1,'feedback_stage'=>1])->count();
        $department_type=array_search("设计部",FieldValue::getFieldValue("department_type"))?:3;
        $design_num=Db::view("mk_project p")
            ->view('mk_project_related_person a','project_id','p.guid=a.project_id')
            ->where('a.uuid',$this->uuid)
            ->where('a.department_type',$department_type)
            ->where('p.feedback_stage','2')
            ->count();
        $arr=[];
        $arr['design']=[
            'number'=>$design_num,
            'url'=>$design_num!=0 ? url("design/showProListByUser"):'',
            'title'=>'我的项目',
        ];
        $market=$department_type=array_search("市场部",FieldValue::getFieldValue("department_type"))?:6;
        $serve=$department_type=array_search("客服部",FieldValue::getFieldValue("department_type"))?:8;
        if($this->member_info->department_type==$market){
            $arr['business']=[
                'number'=>$num,
                'url'=>$num!=0 ? url("project/showProjectUserList"):'',
                'title'=>'我的业务',
            ];
        }else if($this->member_info->department_type==$serve){
            $arr['business']=[
                'number'=>$num,
                'url'=>$num!=0 ? url("project/showProjectUserListCopy"):'',
                'title'=>'我的录入',
            ];
        }else{
            $arr['business']=[
                'number'=>0,
                'url'=>'',
                'title'=>'',
            ];
        }

        return $arr;
    }
    public function showFirstUser(){  //显示每月的冠军


    }
}