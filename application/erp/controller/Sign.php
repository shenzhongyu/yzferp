<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/23
 * Time: 13:02
 * 签到
 */

namespace app\erp\controller;

use app\base\controller\PDF;
use app\base\controller\Word;
use app\base\model\Base;
use app\base\model\budget\BudgetBookNumber;
use app\base\model\budget\DefaultProjectCopy;
use app\base\model\engin\EnginProjectBuildDate;
use app\base\model\engin\EnginProjectBuildType;
use app\base\model\engin\EnginProjectBuildTypeAccess;
use app\base\model\engin\EnginProjectBuildTypeDay;
use app\base\model\engin\EnginProjectBuildTypeDayAccess;
use app\base\model\engin\EnginProjectBuildTypeRemind;
use app\base\model\engin\EnginProjectBuildTypeUser;
use app\base\model\MaterialDataStyle;
use app\base\model\OfficeSign;
use app\base\model\PersonnelUser;
use app\base\model\Project;
use app\base\model\ProjectTraceLog;
use app\erp\config\FieldValue;
use mikkle\tp_wechat\support\Db;
use think\Loader;
use think\Session;
use app\erp\config\Config;


class Sign extends Auth
{
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
//        $this->config_list = Config::$Design;
    }

    /*个人记录*/
    public function showUserSign($uuid=""){
        if ($this->request->isPost()){
            if (empty($uuid)){
                $list=$this->showEasyUiJsonList('base/OfficeSign',false,['user_uuid'=>$this->uuid]);
            }else{
                $list=$this->showEasyUiJsonList('base/OfficeSign',false,['user_uuid'=>$uuid]);
            }
            return $list;
        }else{
            if (!empty($uuid)){
                $user=PersonnelUser::quickGet($uuid);
                $title=$user['name'].'的签到记录';
                $url=url("showUserSign",['uuid'=>$uuid]);
            }else{
                $title='我的签到记录';
                $url=url("showUserSign");
            }

            $arr=[
                'title'=>$title,
                'data_url'=>$url,
            ];
            $this->assign($arr);
            self::echoHtml();
            return $this->fetch('show_user_sign');

        }
    }
    /*菜单表中未找到*/
    public function buildPlan($guid=""){
        if (empty($guid)) return self::showReturnCode(1003);
        $numb_model=new BudgetBookNumber();
        $obj=$numb_model->where(['project_id'=>$guid,'status'=>1])->find();
        if ($this->request->isPost()){
            $map=[
                'project_id'=>$guid,
                'book_number'=>$obj['book_number']
            ];
            $arr=[];
            $build_obj=Loader::model('base/engin/EnginProjectBuildDate')->where($map)->find();
            $build_obj=is_object($build_obj)?$build_obj->toArray():$build_obj;
            $build_obj['build_end_time']=$build_obj['build_time']+($build_obj['build_days']*60*60*24);
            $build_obj=$numb_model->getTime($build_obj,'build_time',false);
            $arr['rows'][]=[
                'build_name'=>'项目总工期',
                'build_time'=>date("Y-m-d ",$build_obj['build_time']),
                'build_end_time'=>date("Y-m-d ",$build_obj['build_end_time']),
                'desc'=>'项目的总工期',
                'guid'=>$build_obj['guid'],
                'build_days'=>$build_obj['build_days']
            ];
            $list=$this->showEasyUiJsonListNo('base/engin/EnginProjectBuildType',false,$map);
            if (!empty($list['rows'])){
                foreach ($list['rows'] as $value){
                    $value['build_end_time']=$value['build_time']+($value['build_days']*60*60*24);
                    $value=$numb_model->getTime($value,'build_time',false);
                    $value=$numb_model->getTime($value,'build_end_time',false);
                    $arr['rows'][]=$value;
                }
            }
            $arr['total']=count($arr['rows']);
            return $arr;
        }else{
            $project_cont=Project::quickGet($guid);
            $con=Loader::model('base/engin/EnginProjectBuildDate')->where(['project_id'=>$guid])->count();
            $arr=[
                'title'=>"工程进度表",
                'pro_id'=>$guid,
                'data_url'=>url('buildPlan',['guid'=>$guid]),
                'name'=>$project_cont['project_name'],
                'book_number'=>$obj['book_number'],
                'address'=>$project_cont['project_address'],
                'num'=>$con,
            ];
            $this->assign($arr);
            self::echoHtml();
            return $this->fetch('build_plan');
        }
    }
    /*签到记录*/
    public function showUserList(){
        if ($this->request->isPost()){
            $list=$this->showEasyUiJsonList('base/PersonnelUser',false,['company_id'=>$this->member_info->company_id]);
            return $list;
        }else{
            $arr=[
                'title'=>'签到记录',
                'data_url'=>url("showUserList"),
                'map'=>['company_id'=>$this->member_info->company_id]
            ];
            $this->assign($arr);
            self::echoHtml();
            return $this->fetch('show_user_list');

        }
    }
    /*查看签到具体位置*/
    public function showAddress($guid=""){
        if (empty($guid)) return self::showReturnCode(1003);
        $obj=OfficeSign::quickGet($guid);
        $arr=[
            'desc'=>$obj['message'],
            'lat'=>$obj['latitude'],
            'lon'=>$obj['longitude'],
        ];
        $this->assign($arr);
        self::echoHtml();
        return $this->fetch('address');
    }
    /*当月出勤*/
    public function showPersonnelSign(){
        if ($this->request->isPost()){
            $rows=$this->request->param("rows")?$this->request->param("rows"):"20";
            $page=$this->request->param("page")?$this->request->param("page"):"1";
            $name=$this->request->param("user_name")?$this->request->param("user_name"):"";
            $list=Db::view("mk_office_sign a",['user_name','user_uuid',"sum(type=1)"=>"in","sum(type=2)"=>"out"])
                ->view("mk_personnel_department b",'department_name','b.guid=a.department_id','LEFT')
                ->view("mk_personnel_jobs c","jobs_name",'c.guid=a.jobs_id','LEFT')
                ->whereTime("mk_office_sign.create_time",'m')
                ->group("a.user_uuid")
              ->where("a.company_id",$this->member_info->company_id);
                 $name && $list = $list->where("a.user_name",["like","%".$name."%"]);

            $list=$list->paginate($rows, false, array('page' => $page));
            $arr=[];
            $arr["total"]=$list->total();
            $arr["rows"]=$list->items();
            return $arr;
        }else{
            $arr=[
                "title"=>"本月考勤",
                'data_url'=>url("showPersonnelSign"),
            ];
            $this->assign($arr);
            self::echoHtml();
            return $this->fetch("show_personnel_sign");
        }
    }



    public function showMonthSign($uuid=""){
        if (empty($uuid)) return self::showJsonReturnCode(1003);
        if ($this->request->isPost()){
            $rows=$this->request->param("rows")?$this->request->param("rows"):"20";
            $page=$this->request->param("page")?$this->request->param("page"):"1";
            $name=$this->request->param("user_name")?$this->request->param("user_name"):"";
            $list=Db::view("mk_office_sign a","user_name,type,create_time")
                ->view("mk_personnel_department b",'department_name','b.guid=a.department_id','LEFT')
                ->view("mk_personnel_jobs c","jobs_name",'c.guid=a.jobs_id','LEFT')
                ->whereTime("a.create_time",'m')
                ->where("a.user_uuid",$uuid)
                ->where("a.company_id",$this->member_info->company_id);
            $name && $list = $list->where("a.user_name",["like","%".$name."%"]);
            $list=$list->paginate($rows, false, array('page' => $page));
            $arr=[];
            $arr["total"]=$list->total();
            $arr["rows"]=$list->items();
            if(!empty($arr["rows"])){
                foreach ($arr["rows"] as $key=>$v){
                    $arr["rows"][$key]["create_time"]=date("Y-m-d H:i:s", $v["create_time"]);
                }
            }
            return $arr;
        }else{
            $arr=[
                "title"=>"本月考勤",
                'data_url'=>url("showMonthSign",["uuid"=>$uuid]),
            ];
            $this->assign($arr);
            self::echoHtml();
            return $this->fetch("show_month_sign");
        }
    }
}