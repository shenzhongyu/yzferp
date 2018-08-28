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


class Classify extends Auth
{
    // 市场部人员列表
    public function marketDataList()
    {
        if (!$this->uuid) {
            return self::showReturnCodeWithOutData(1004);
        }
        $map = [
            'status' => 1,
            'department_id' => $this->member_info->department_id,
            'company_id' => $this->member_info->company_id
        ];
        $list = $this->showEasyUiJsonList('base/PersonnelUser', false, $map, false, [], false, 'uuid,name');
        if ($this->request->isPost()) {
            return $list;
        }

        return $this->fetch("classify/marketDataList");
    }

    //市场部人员转单详情
    public function marketDataDetails()
    {

        if (!$this->uuid) {
            return self::showReturnCodeWithOutData(1004);
        }
        $month = "m";
        $guid = $this->request->param("guid");
        $type = $this->request->param("type");
        {
            if ($type == 1) {
                $month = "m";
            } else if ($type == 2) {
                $month = "last month";
            }
        }
        if (empty($guid)) return self::showJsonReturnCode(1003);


        if ($this->request->isPost()) {
            $list = Db::table('mk_project')->whereTime('create_time', $month)->where(['uuid' => $guid])->select();
            $all = count($list);
            $waste = 0;
            $has = 0;
            $now = 0;
            foreach ($list as $value) {
                if ($value["status"] == -2) {
                    $waste++;
                };
                if ($value["feedback_stage"] > 1) {
                    $has++;
                };
                if ($value["feedback_stage"] == 1) {
                    $now++;
                };
            }
            $info = PersonnelUser::quickGet($guid);
            if ($all == 0) {
                $num = 0;
            } else if
            (($has / $all) == 0
            ) {
                $num = 0;
            } else {
                $num = ($has / $all * 100) . '%';
            }
            $array = [
                "waste" => $waste,
                "has" => $has,
                "all" => $all,
                "now" => $now,
                "info" => $info,
                "num" => $num
            ];

            return $array;
        }
        $arr = [
            "url" => url("marketDataDetails", ["guid" => $guid])
        ];
        $this->assign($arr);
        return $this->fetch("classify/marketDataDetails");

    }

    //设计部人员列表
    public function designerDataList()
    {
        if (!$this->uuid) {
            return self::showReturnCodeWithOutData(1004);
        }

        $map = [
            'status' => 1,
            'department_id' => $this->member_info->department_id,
            'company_id' => $this->member_info->company_id
        ];


        $list = $this->showEasyUiJsonList('base/PersonnelUser', false, $map, false, [], false, 'uuid,name');
        if ($this->request->isPost()) {
            return $list;
        }

        return $this->fetch("classify/designerDataList");
    }

    //设计部人员转单详情
    public function designerDataDetails()
    {

        if (!$this->uuid) {
            return self::showReturnCodeWithOutData(1004);
        }
        $month = "m";
        $guid = $this->request->param("guid");
        $type = $this->request->param("type");
        {
            if ($type == 1) {
                $month = "m";
            } else if ($type == 2) {
                $month = "last month";
            }
        }
        if (empty($guid)) return self::showJsonReturnCode(1003);


        if ($this->request->isPost()) {


            $department_type = array_search("设计部", FieldValue::getFieldValue("department_type")) ?: 3;
            $data = Db::view('mk_project_related_person', 'project_id')
                ->view('mk_project', 'feedback_stage,engin_status,status', 'mk_project.guid=mk_project_related_person.project_id')
                ->where('mk_project_related_person.uuid', '=', $guid)
                ->where('mk_project_related_person.department_type', '=', $department_type)
                ->whereTime('mk_project.create_time', $month)
                ->select();
            $waste = 0;
            $has = 0;
            $now = 0;
            $all = count($data);
            foreach ($data as $value) {
                if ($value["status"] == -2) {
                    $waste++;
                };
//                if($value["feedback_stage"]>1){
//                    $has++;
//                };
                if ($value['feedback_stage'] >= 2) {
                    $re = Db::table('mk_project_contract_audit')
                        ->where(['project_id' => $value['project_id'], 'examine_status' => 1])
                        ->select();
                    if (!empty($re)) {
                        $has++;
                    }
                }

                $now = $all - $has - $waste;
            }
            $info = PersonnelUser::quickGet($guid);
            if ($all == 0) {
                $num = 0;
            } else if (($has / $all) == 0) {
                $num = 0;
            } else {
                $num = ($has / $all * 100) . '%';
            }
            $array = [
                "waste" => $waste,
                "has" => $has,
                "all" => $all,
                "now" => $now,
                "info" => $info,
                "num" => $num
            ];

            return $array;
        }
        $arr = [
            "url" => url("designerDataDetails", ["guid" => $guid])
        ];
        $this->assign($arr);
        return $this->fetch("classify/designerDataDetails");

    }

    // 客服部人员列表
    public function customerDataList()
    {
        if (!$this->uuid) {
            return self::showReturnCodeWithOutData(1004);
        }
        $map = [
            'status' => 1,
            'department_id' => $this->member_info->department_id,
            'company_id' => $this->member_info->company_id
        ];
        $list = $this->showEasyUiJsonList('base/PersonnelUser', false, $map, false, [], false, 'uuid,name');
        if ($this->request->isPost()) {
            return $list;
        }

        return $this->fetch("classify/customerDataList");
    }

    //客户部人员转单详情
    public function customerDataDetails()
    {

        if (!$this->uuid) {
            return self::showReturnCodeWithOutData(1004);
        }
        $month = "m";
        $guid = $this->request->param("guid");
        $type = $this->request->param("type");
        {
            if ($type == 1) {
                $month = "m";
            } else if ($type == 2) {
                $month = "last month";
            }
        }
        if (empty($guid)) return self::showJsonReturnCode(1003);

        if ($this->request->isPost()) {
            $list = Db::table('mk_project')->whereTime('create_time', $month)->where(['uuid' => $guid])->select();
            $all = count($list);
            $waste = 0;
            $has = 0;
            $now = 0;
            foreach ($list as $value) {
                if ($value["status"] == -2) {
                    $waste++;
                };
                if ($value["feedback_stage"] > 1) {
                    $has++;
                };
                if ($value["feedback_stage"] == 1) {
                    $now++;
                };
            }
            $info = PersonnelUser::quickGet($guid);
            if ($all == 0) {
                $num = 0;
            } else if (($has / $all) == 0) {
                $num = 0;
            } else {
                $num = ($has / $all * 100) . '%';
            }
            $array = [
                "waste" => $waste,
                "has" => $has,
                "all" => $all,
                "now" => $now,
                "info" => $info,
                "num" => $num
            ];

            return $array;
        }
        $arr = [
            "url" => url("customerDataDetails", ["guid" => $guid])
        ];
        $this->assign($arr);
        return $this->fetch("classify/customerDataDetails");
    }
    //部门列表
    public function departmentDataList(){

        if (!$this->uuid) {
            return self::showReturnCodeWithOutData(1004);
        }
        $arr=[
            $department_type=array_search("市场部",FieldValue::getFieldValue("department_type"))?:6,
            $department_type=array_search("客服部",FieldValue::getFieldValue("department_type"))?:8,
            $department_type=array_search("设计部",FieldValue::getFieldValue("department_type"))?:3
        ];
        $map = [
            'status' => 1,
            'department_type' => ['in',$arr],
            'company_id' => $this->member_info->company_id
        ];
        $list = $this->showEasyUiJsonList('base/personnelDepartment', false, $map, false, [], false);
        if ($this->request->isPost()) {
            return $list;
        }
        return $this->fetch("classify/departmentDataList");
    }

        //部门业务转单详情
    public function departmentDataDetails()
    {

        if (!$this->uuid) {
            return self::showReturnCodeWithOutData(1004);
        }
        $month = "m";
        $guid = $this->request->param("guid");
        $type = $this->request->param("type");
        {
            if ($type == 1) {
                $month = "m";
            } else if ($type == 2) {
                $month = "last month";
            }
        }
        if (empty($guid)) return self::showJsonReturnCode(1003);

        if ($this->request->isPost()) {

            $list=Db::view('mk_personnel_user','name,uuid')
                ->view('mk_project','feedback_stage,engin_status,status','mk_project.uuid=mk_personnel_user.uuid')
                ->where('mk_personnel_user.status','=','1')
                ->where('mk_personnel_user.department_id','=',$guid)
                ->where('mk_personnel_user.company_id','=',$this->member_info->company_id)
                ->whereTime('mk_project.create_time',$month)
                ->select();
//            $list = Db::table('mk_project')->whereTime('create_time', $month)->where(['uuid' => $guid])->select();
            $all = count($list);
            $waste = 0;
            $has = 0;
            $now = 0;
            foreach ($list as $value) {
                if ($value["status"] == -2) {
                    $waste++;
                };
                if ($value["feedback_stage"] > 1) {
                    $has++;
                };
                if ($value["feedback_stage"] == 1) {
                    $now++;
                };
            }

            if ($all == 0) {
                $num = 0;
            } else if (($has / $all) == 0) {
                $num = 0;
            } else {
                $num = ($has / $all * 100) . '%';
            }
            $array = [
                "waste" => $waste,
                "has" => $has,
                "all" => $all,
                "now" => $now,
                "num" => $num
            ];

            return $array;
        }
        $arr = [
            "url" => url("departmentDataDetails", ["guid" => $guid])
        ];
        $this->assign($arr);
        return $this->fetch("classify/departmentDataDetails");
    }

    public function textchat()
    {
        return $this->fetch("classify/textchat");
    }
    //各部门总客户量列表
    public function departmentListAll(){
        if (!$this->uuid) {
            return self::showReturnCodeWithOutData(1004);
        }
        $month = "m";
        $guid = $this->request->param("guid");
        $type = $this->request->param("type");
        $type="1";
        {
            if ($type == 1) {
                $month = "m";
            } else if ($type == 2) {
                $month = "last month";
            }
        }
        if (empty($guid)) return self::showJsonReturnCode(1003);
        if($this->request->isPost()){
            $list=Db::view('mk_personnel_user','name,uuid')
                ->view('mk_project','feedback_stage,engin_status,status','mk_project.uuid=mk_personnel_user.uuid')
                ->where('mk_personnel_user.status','=','1')
                ->where('mk_personnel_user.department_id','=',$guid)
                ->where('mk_personnel_user.company_id','=',$this->member_info->company_id)
                ->whereTime('mk_project.create_time',$month)
                ->select();
            $array = [
                "url"=>url("departmentListAll"),
                "title"=>"总客户量列表",
                "total"=>url("marketDataDetails"),
                "list"=>$list,

            ];
            return $array;
        }

        return $this->fetch("departmentListAll");
    }

    }