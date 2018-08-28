<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/23
 * Time: 13:02
 * 显示已转部项目
 */

namespace app\erp\controller;

use app\base\controller\Upload;
use app\base\model\Base;
use app\base\model\finance\Bank;
use app\base\model\finance\CollectPlan;
use app\base\model\finance\CollectPlanAccess;
use app\base\model\finance\CollectStyle;
use app\base\model\finance\PaymentStyle;
use app\base\model\PersonnelDepartment;
use app\base\model\PersonnelUser;
use app\base\model\Project;
use app\base\model\project\ProjectCollectPrice;
use app\base\model\project\ProjectCollectPricePay;
use app\base\model\project\ProjectCollectPriceRefund;
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
use think\Db;
use think\Loader;
use think\Log;
use think\Session;
use app\erp\config\Config;


class TransferredProject extends Auth
{
    /*我的已转部业务(市场部)*/
    public function showMarketUserProject(){
        if($this->request->isPost()){
            $list=$this->showEasyUiJsonList("base/Project",false,["company_id"=>$this->member_info->company_id,'uuid'=>$this->uuid,'project_type'=>'0','feedback_stage'=>[">",'1']]);
            return $list;
        }else{
            $arr=[
                'title'=>'已转部项目',
                'data_url'=>url("showMarketUserProject"),
            ];
            $this->assign($arr);
            return $this->fetch("user_project_list");
        }
    }
    /*我的已转部业务(客服部)*/
    public function showSeverUserProject(){
        if($this->request->isPost()){
            $list=$this->showEasyUiJsonList("base/Project",false,["company_id"=>$this->member_info->company_id,'uuid'=>$this->uuid,'project_type'=>'1','feedback_stage'=>[">",'1']]);
            return $list;
        }else{
            $arr=[
                'title'=>'已转部项目',
                'data_url'=>url("showMarketUserProject"),
            ];
            $this->assign($arr);
            return $this->fetch("user_project_list");
        }
    }
    public function assignFieldValue(){
        $field_value = new FieldValue();
        $this ->assign('value',(array)$field_value);
    }
    public function lookProjectInfo(){
        $this->assignFieldValue();
        $id =$this->request->param('guid');
        if (empty($id)) return self::showJsonReturnCode(1003);
        $model_project= new Project();
        $project_info= $model_project->getInfoByGuId($id);
        if(empty($project_info)){
            return self::showJsonReturnCode(1003);
        }
        $model_contacts = new ProjectContacts();
        $contacts_list = $model_contacts->getList(['project_guid'=>$id]);

        $model_structure = new ProjectStructure();
        $structure_list = $model_structure->getProjectByProjectGuId($id);

        $model_building = new ProjectBuilding();
        $building_list = $model_building->getProjectByProjectGuId($id);
        $arr=[
            'project_id'=>$id,
            'project'=>$project_info,
            'contacts'=>$contacts_list,
            'structure'=>$structure_list,
            'building'=>$building_list
        ];
        $this->assign($arr);
//        $this->assign('project',$project_info);
//        $this->assign('contacts',$contacts_list);
//        $this->assign('structure',$structure_list);
//        $this->assign('building',$building_list);
        if ($project_info['company_id']!=$this->member_info->company_id){
            Log::error($this->member_info->name.'非法访问项目'.$project_info['project_name']);
            return self::showJsonReturnCode(1010,[],'无权限访问');
        }

        if ($project_info['uuid']!=$this->member_info->uuid ){
            Log::error($this->member_info->name.'非法访问项目'.$project_info['project_name']);
            return self::showJsonReturnCode(1010,[],'无权限访问');
        }
        return $this->fetch("look_project_info");
    }
}