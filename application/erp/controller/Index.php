<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/5/5
 * Time: 17:20
 */

namespace app\erp\controller;


use app\base\model\personal\UserOpus;
use app\base\model\PersonnelNode;
use app\base\model\PersonnelUser;
use app\base\model\Project;
use app\erp\config\FieldValue;
use mikkle\tp_wechat\support\Db;
use think\Loader;

class Index extends Auth
{
    public function index(){
        $arr=[
            'member_info' => $this->member_info,
            'version'=>$this->readVersion()
        ];
        $this->assign($arr);
        return $this->fetch('index_new');
    }


    public function indexNew(){
        $this->assign('member_info',$this->member_info);
        return $this->fetch('index_new');
    }

    public function electron(){  //精美案例
        return $this->fetch('electron_exhibition');
    }

    public function getUserOnline(){  //获取2小时内在线人员
        if ($this->request->isPost()){
            $model=new PersonnelUser();
            $list=$model->getUserOnline($this->member_info->company_id);
            return $list;
        }
    }
    /*销售月冠(未实现)*/
    public function salesChampion(){
        if ($this->request->isPost()){
            $model=new PersonnelUser();
            $list=$model->salesChampion($this->member_info->company_id);
            return json($list);
        }
    }
    /*获取自己的工作数量*/
    public function getUserWork(){
        $model=new Project();
        $list=$model->getUserData();
        return json($list);
    }



    /*精美案例*/
    public function getElectronData(){  //
        if ($this->request->isPost()){
            $model=new UserOpus();
            $map=[
               'company_id'=>$this->member_info->company_id
            ];
            $data=$this->request->post();
            $field='uuid,photo_name,photo_address,photo_desc,create_time';
            if (empty($data)){
                $list=$model->getTeamData($map,$field);
            }else{
                $page=$data['page'];
                $rows=$data['rows'];
                $list=$model->getTeamData($map,$field,$page,$rows);
            }
            return self::showJsonReturnCode(1001,$list);
        }
    }
    public function material(){  //主材商城
        return $this->fetch('material_mark');
    }
    public function material_auxiliary(){  //辅材商城
        return $this->fetch('material_auxiliary');
    }
    public function team(){  //优秀团队
        return $this->fetch('team');
    }
    /*优秀团队的个人作品*/
    public function team_opus($guid=""){
        if (empty($guid)) return self::showJsonReturnCode(1003);
        if ($this->request->isPost()){
            $model=new UserOpus();
            $list=$model->getList(['uuid'=>$guid,'status'=>1]);
            return ['code'=>1001,'data'=>$list];
        }else{
            $user=PersonnelUser::quickGet($guid);

            $arr=[
                'user'=>$user,
            ];
            $this->assign($arr);
            self::echoHtml();
            return $this->fetch('team_opus');
        }

    }

    public function getTeamData(){ //获取团队信息
        $model=new PersonnelUser();

        $map=[
            'company_id'=>$this->member_info->company_id,
            'hide'=>1,
        ];
        $data=$this->request->post();
        $field='name,jobs_id,photo,desc,uuid';
        if (empty($data)){
            $list=$model->getTeamData($map,$field);
        }else{
            $page=$data['page'];
            $rows=$data['rows'];
            $list=$model->getTeamData($map,$field,$page,$rows);
        }

        if(empty($list['rows'])){
            $department_type=array_search("设计部",FieldValue::getFieldValue("department_type"))?:3;
            $map=[
                'company_id'=>$this->member_info->company_id,
                'department_type'=>$department_type,
            ];
            if (empty($data)){
                $list=$model->getTeamData($map,$field);
            }else{
                $page=$data['page'];
                $rows=$data['rows'];
                $list=$model->getTeamData($map,$field,$page,$rows);
            }
        }

        return self::showJsonReturnCode(1001,$list);
    }
    public function main(){
        $list_com=Loader::model("base/OfficeNotice")->getList(["type"=>1,'company_id'=>$this->member_info->company_id]);
        if(!empty($list_com)){
            foreach($list_com as $va){
                if(!empty($va["create_time"])){
                    $va["time"]=substr($va["create_time"],5,5);
                }
            }
        }
        $list_dep=Loader::model("base/OfficeNoticeAccess")->getList(["department_id"=>$this->member_info->department_id]);
        $array=[];
        if(!empty($list_dep)){
            foreach($list_dep as $value){
                if(!empty($value["notice_id"])){
                    $v=Loader::model("base/OfficeNotice")->getPerByGuId($value["notice_id"],['company_id'=>$this->member_info->company_id]);
                    if(!empty($v["create_time"])) {
                        $v["time"] = substr($v["create_time"], 5, 5);
                    }
                    if(empty($v)){
                        continue;
                    }
                    $array[]=$v;
                }
            }
        }
        rsort($array);
        $assign_data = [
            "member_info"=>$this->member_info,
            "com"=>$list_com,
            "dep"=>$array,
        ];
        $this->assign($assign_data);


//        $this->assign('member_info',$this->member_info);
        return $this->fetch('main_new');
    }


    public function mainNew(){
        $list_com=Loader::model("base/OfficeNotice")->getList(["type"=>1,'company_id'=>$this->member_info->company_id]);
        if(!empty($list_com)){
            foreach($list_com as $va){
                if(!empty($va["create_time"])){
                    $va["time"]=substr($va["create_time"],5,5);
                }
            }
        }
        $list_dep=Loader::model("base/OfficeNoticeAccess")->getList(["department_id"=>$this->member_info->department_id]);
        $array=[];
        if(!empty($list_dep)){
            foreach($list_dep as $value){
                if(!empty($value["notice_id"])){
                    $v=Loader::model("base/OfficeNotice")->getPerByGuId($value["notice_id"],['company_id'=>$this->member_info->company_id]);
                    if(!empty($v["create_time"])) {
                        $v["time"] = substr($v["create_time"], 5, 5);
                    }
                    if(empty($v)){
                        continue;
                    }
                    $array[]=$v;
                }
            }
        }
        rsort($array);
        $assign_data = [
            "member_info"=>$this->member_info,
            "com"=>$list_com,
            "dep"=>$array,
        ];
        $this->assign($assign_data);


//        $this->assign('member_info',$this->member_info);
        return $this->fetch('main_new');
    }



    public function getMenuJson($role_id=""){
        if (empty($this->member_info)) return json([]);
        $role_id  = empty($role_id) ? $this->member_info['role_id'] : $role_id;
        $model = new PersonnelNode();
        $tree = $model->getUserMenuJson($role_id);
        $json = [];
        foreach ($tree as $item => $value) {
            $json[$value['menu_id']] = $value;
        }
        return json($json);
    }
    public function getDepartmentNotice(){
        $list_dep=Loader::model("base/OfficeNoticeAccess")->getList(["department_id"=>$this->member_info->department_id]);
        $array=[];
        if(!empty($list_dep)){
            foreach($list_dep as $value){
                if(!empty($value["notice_id"])){
                    $v=Loader::model("base/OfficeNotice")->getPerByGuId($value["notice_id"]);
                    if(!empty($v["create_time"])){
                        $v["time"]=substr($v["create_time"],5,5);
                    }
                    $array[]=$v;
                }
            }
        }else{
            return json(1);
        }
        rsort($array);
        return json($array);
    }
    public function getCompanyNotice(){
        $list_com=Loader::model("base/OfficeNotice")->getList(["type"=>1]);
        if(!empty($list_com)){
            foreach($list_com as $va){
                if(!empty($va["create_time"])){
                    $va["time"]=substr($va["create_time"],5,5);
                }
            }
        }else{
            return json(1);
        }
        return json($list_com);
    }



}