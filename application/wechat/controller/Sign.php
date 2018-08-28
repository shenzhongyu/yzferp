<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/2
 * Time: 16:29
 */

namespace app\wechat\controller;
use app\base\controller\Rsa;
use app\base\model\OfficeLeave;
use app\base\model\OfficeSign;
use app\base\model\PersonnelNode;
use app\base\model\PersonnelUser;
use think\Db;
use think\Loader;
use think\Url;
use app\erp\config\FieldValue;
use app\base\model\Project as ProjectModel;

class Sign extends Auth
{
    public function sign(){
        if (!$this->uuid){
            $this->redirect("index/myError");
        }else{
            if(isset($this->member_info["we_openid"])){
                $pam = [
                    "uuid"=>$this->uuid,
                    "open_id"=>$this->member_info["we_openid"],
                    "md5"=>md5($this->request->host().$this->uuid.$this->member_info["we_openid"]),
                ];
                $url = Url::build("center/sign/sign",$pam);
                $this->redirect("http://wechat.yzfErp.com$url");
            }
        }
//        if($this->request->isPost()) {
//            $data= $this->request->post();
//            if(empty($data)){
//                return self::showJsonReturnCodeWithOutData(1001);
//            }
//            if(!isset($data["latitude"])){
//                return self::showJsonReturnCodeWithOutData(1019);
//            }
//            if(!isset($data["longitude"])){
//                return self::showJsonReturnCodeWithOutData(1010);
//            }
//            $validate_name='base/OfficeSign.add';
//            $result = $this->validate($data, $validate_name);
//            if (true !== $result) return ['code' => '1003', 'msg' => $result,];
//            $data['user_uuid']=$this->uuid;
//            $data['company_id']=$this->member_info->company_id;
//            $data['department_id']=$this->member_info->department_id;
//            $data['jobs_id']=$this->member_info->jobs_id;
//            $data['user_name']=$this->member_info->name;
//            $data['type']="1";
//            $model=new OfficeSign();
//            return $model->addData($data);
//        }else{
//            $arr=[
//                'title'=>'签到',
//                'url'=>url('sign/sign'),
//            ];
//            $this->assign($arr);
//            return $this->fetch("index/sign");
//        }
    }
    public function signOut(){

        if (!$this->uuid){
            $this->redirect("index/myError");
        }else{
            if(isset($this->member_info["we_openid"])){
                $pam = [
                    "uuid"=>$this->uuid,
                    "open_id"=>$this->member_info["we_openid"],
                    "md5"=>md5($this->request->host().$this->uuid.$this->member_info["we_openid"]),
                ];
                $url = Url::build("center/sign/signOut",$pam);
                $this->redirect("http://wechat.yzfErp.com$url");

            }
        }
//
//        if (!$this->uuid){
//            $this->redirect("index/myError");
//        }
//        if($this->request->isPost()) {
//            $data= $this->request->post();
//            if(empty($data)){
//                return self::showJsonReturnCodeWithOutData(1001);
//            }
//            if(!isset($data["latitude"])){
//                return self::showJsonReturnCodeWithOutData(1019);
//            }
//            if(!isset($data["longitude"])){
//                return self::showJsonReturnCodeWithOutData(1010);
//            }
//
//            $validate_name='base/OfficeSign.add';
//            $result = $this->validate($data, $validate_name);
//            if (true !== $result) return ['code' => '1003', 'msg' => $result,];
//            $data['user_uuid']=$this->uuid;
//            $data['company_id']=$this->member_info->company_id;
//            $data['department_id']=$this->member_info->department_id;
//            $data['jobs_id']=$this->member_info->jobs_id;
//            $data['user_name']=$this->member_info->name;
//            $data['type']="2";
//            $model=new OfficeSign();
//            return $model->addData($data);
//
//        }else{
//            $arr=[
//                'title'=>'签退',
//                'url'=>url("sign/signOut")
//            ];
//            $this->assign($arr);
//            return $this->fetch("index/sign");
//        }
    }
    public function sginInfo($guid="")
    {
//        $timenumber = $this->request->param("timenumber");
       if($this->request->isPost()){

           $a=$this->request->param('type');
           if(empty($guid)) {
               $guid=$this->member_info->uuid;
           }

               if($a==1){
                   $list=Db::table('mk_office_sign')->whereTime('create_time', 'm')->where('user_uuid',$guid)->select();
               }else if($a==2){
                   $list=Db::table('mk_office_sign')->whereTime('create_time', 'last month')->where('user_uuid',$guid)->select();
               }else{
                   $list=Db::table('mk_office_sign')->whereTime('create_time', 'm')->where('user_uuid',$guid)->select();
               }
               $model = new PersonnelUser();
               $list = $model->getTimeHours($list,'update_time',false);

               return $list;
       }
       if(empty($guid)){
            $name=$this->member_info["name"];
            $this->assign("uuid",$this->member_info['uuid']);
           $this->assign("name",$name);
       }else{
           $name =PersonnelUser::quickGet($guid)["name"];
           $this->assign("uuid",$guid);
           $this->assign("name",$name);
       }
     return  $this->fetch("index/sginInfo");
    }
    public function sginList(){
        if (!$this->uuid){
            $this->redirect("index/myError");
        }
        if($this->request->isPost()){
           $list = $this->showEasyUiJsonList("base/PersonnelUser",false,["company_id"=>$this->member_info->company_id]);
        return $list;
        }
        return $this->fetch("index/sginList");
    }
    public function informListCom(){

        if (!$this->uuid){
            $this->redirect("index/myError");
        }
        $ass=[
            'data_url'=>url(''),
            "as" =>"公司",
        ];
        if($this->request->isPost()){
            $list=$this->showEasyUiJsonList('base/officeNotice',false,["company_id"=>$this->member_info->company_id,"type"=>1]);
            return $list;
        }
        $this->assign($ass);
        return $this->fetch("index/informList");
    }
    public function informListDep(){

        if (!$this->uuid){
            $this->redirect("index/myError");
        }
        $ass=[
            'data_url'=>url(''),
            "as" =>"部门",
        ];
        if($this->request->isPost()){
            $department_id=$this->member_info->department_id;
            $list=$this->showEasyUiJsonList('base/officeNotice',false,["company_id"=>$this->member_info->company_id,"type"=>2]);
            if($list["rows"]){
                foreach ($list["rows"] as $key=>$value){
                    $dep_id = Loader::model("base/officeNoticeAccess")->where(["notice_id"=>$value["guid"]])->field("department_id")->select();

             if($dep_id){
                 foreach ($dep_id as $valueb){
                    if($valueb["department_id"] !=$department_id){
                        unset($list["rows"][$key]);
                    }
                 }
             }
                }
            }
            return $list;
        }
        $this->assign($ass);
        return $this->fetch("index/informList");
    }
    public function informDetails(){
            $guid=$this->request->param("guid");
        if (!$this->uuid){
            $this->redirect("index/myError");
        }
        $ass=[
            "guid"=>$guid
        ];
        if($this->request->isPost()){
            $list=$this->showEasyUiJsonList('base/officeNotice',false,["guid"=>$guid]);
            return $list;
        }
        $this->assign($ass);
        return $this->fetch("index/informDetails");
    }
}
