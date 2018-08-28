<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/5/5
 * Time: 17:20
 */

namespace app\erp\controller;


use app\base\model\PersonnelDepartment;
use app\base\model\PersonnelNode;
use app\base\model\PersonnelRoleNodeAccess;
use app\base\model\PersonnelUser;
use app\base\model\Project;
use app\base\model\ProjectRelatedPerson;
use app\erp\config\FieldValue;
use mikkle\tp_wechat\support\Db;
use think\Build;
use think\Exception;
use think\Loader;
use think\Session;
use think\Url;

class Statistics extends Auth  //数据统计
{

    public function HomeDataNumber(){
        $arr=[];
        $model_node=new PersonnelNode();
        $node_access=new PersonnelRoleNodeAccess();
        $role_id =$this->member_info['role_id'];
        $arr_node = $node_access->where('role_id', $role_id)->column('node_id');
        $map=[
            'group'=>'Statistics',
            'is_mobile'=>0,
            'guid'=>["in", $arr_node],
        ];
        $field = "guid as menu_id,pid,node_name as menu_name,icon ,CASE action_name  when '#' then '' else   CONCAT(module_name ,'/',control_name , '\/' , action_name)  END as url";
        $list=$model_node->getListByMap($map,$field);
        if (!empty($list)){
            foreach ($list as $value){

                switch ($value['url']){
                    case 'erp/Project/showProjectDepList':
                        $pro_map=[
                            'company_id'=>$this->member_info->company_id,
                            'feedback_stage'=>1,
                            'department_id'=>$this->member_info->department_id,

                        ];
                        $number=$model_node->getCountObj('base/Project',$pro_map);
                        $arr[]=[
                            'color'=>'#1da02b',
                            'name'=>'我部门的业务',
                            'icon'=>'&#xe61b;',
                            'number'=>$number,
                            'title'=>$value['menu_name'],
                            'url'=>Url::build($value['url']),
                        ];
                        break;
                    case 'erp/Project/showProjectUserList':
                        $pro_map=[
                            'company_id'=>$this->member_info->company_id,
                            'feedback_stage'=>1,
                            'uuid'=>$this->uuid,
                        ];
                        $number=$model_node->getCountObj('base/Project',$pro_map);
                        $arr[]=[
                            'color'=>'#f29503',
                            'name'=>'我的业务',
                            'icon'=>'&#xe61c;',
                            'number'=>$number,
                            'url'=>Url::build($value['url']),
                        ];
                        break;
                    case 'erp/Design/showProListByUser':
                        $department_type=array_search("设计部",FieldValue::getFieldValue("department_type"))?:3;
                        $pro_map=[
                             'department_type'=>$department_type,
                             'uuid'=>$this->uuid
                        ];
                        $model=new ProjectRelatedPerson();
                        $list=$model->getList($pro_map);
                        $number=0;
                        if (!empty($list)){
                            foreach ($list as $item){
                                $re=Loader::model('base/Project')->getInfoByGuid($item['project_id']);
                                if (!empty($re)){
                                    if ($re['feedback_stage']=="2" && $re['status']=='1'){
                                        $number++;
                                    }
                                }

                            }
                        }
                        $arr[]=[
                            'color'=>'#11a9e2',
                            'name'=>'我的设计预算',
                            'icon'=>'&#xe61b;',
                            'number'=>$number,
                            'title'=>$value['menu_name'],
                            'url'=>Url::build($value['url']),
                        ];
                        break;
                    case 'erp/Design/showProListByDep':
                        $pro_map=[
                            'company_id'=>$this->member_info->company_id,
                            'feedback_stage'=>2,
                            'design_department'=>$this->member_info->department_id,

                        ];
                        $number=$model_node->getCountObj('base/Project',$pro_map);
                        $arr[]=[
                            'color'=>'#9b59b6',
                            'name'=>'我部门的设计预算',
                            'icon'=>'&#xe61b;',
                            'number'=>$number,
                            'title'=>$value['menu_name'],
                            'url'=>Url::build($value['url']),
                        ];
                        break;
                    case 'erp/DesignExtract/showNoContract':
                        $list=Loader::model('base/project/ProjectContract')->getList(['company_id'=>$this->member_info->company_id,'uuid'=>$this->uuid]);
                        $number=0;
                        if (!empty($list)){
                            foreach ($list as $item){
                                $cont=Loader::model('base/project/ProjectContractAudit')->where([ 'project_id'=>$item['project_id'],'book_number'=>$item['book_number'],'status'=>1,'examine_status'=>1])->count();
                                if ($cont==0){
                                    $number++;
                                }

                            }
                        }
                        $arr[]=[
                            'color'=>'#CD5C5C',
                            'name'=>'我的待签合同',
                            'icon'=>'&#xe61b;',
                            'number'=>$number,
                            'title'=>$value['menu_name'],
                            'url'=>Url::build($value['url']),
                        ];
                        break;
                    case 'erp/DesignExtract/showNoContractByDep':
                        $list=Loader::model('base/project/ProjectContract')->getList(['company_id'=>$this->member_info->company_id,'uuid'=>$this->uuid]);
                        $number=0;
                        if (!empty($list)){
                            foreach ($list as $item){
                                $cont=Loader::model('base/project/ProjectContractAudit')->where([ 'project_id'=>$item['project_id'],'book_number'=>$item['book_number'],'status'=>1,'examine_status'=>1])->count();
                                if ($cont==0){
                                    $pro_model=new Project();
                                    $pro=$pro_model->getInfoByGuId($item['project_id']);
                                    if ($pro['design_department']==$this->member_info->department_id&&$pro['status']==1){
                                        $number++; 
                                    };
                                }

                            }
                        }
                        $arr[]=[
                            'color'=>'#68838B',//
                            'name'=>'我部门的待签合同',
                            'icon'=>'&#xe61b;',
                            'number'=>$number,
                            'title'=>$value['menu_name'],
                            'url'=>Url::build($value['url']),
                        ];
                        break;
                    case 'erp/DesignExtract/showContractProjectList':
                        $list=Loader::model('base/project/ProjectContract')->getList(['company_id'=>$this->member_info->company_id,'uuid'=>$this->uuid]);
                        $number=0;
                        if (!empty($list)){
                            foreach ($list as $item){
                                $cont=Loader::model('base/project/ProjectContractAudit')->where([ 'project_id'=>$item['project_id'],'book_number'=>$item['book_number'],'status'=>1,'examine_status'=>1])->count();
                                if ($cont==1){
                                    $number++;
                                }

                            }
                        }
                        $arr[]=[
                            'color'=>'#BDB76B',//
                            'name'=>'我的待收款合同',
                            'icon'=>'&#xe61b;',
                            'number'=>$number,
                            'title'=>$value['menu_name'],
                            'url'=>Url::build($value['url']),
                        ];
                        break;
                    case 'erp/DesignExtract/showConProListByCom':
                        $list=Loader::model('base/project/ProjectContract')->getList(['company_id'=>$this->member_info->company_id]);
                        $number=0;
                        if (!empty($list)){
                            foreach ($list as $item){
                                $cont=Loader::model('base/project/ProjectContractAudit')->where([ 'project_id'=>$item['project_id'],'book_number'=>$item['book_number'],'status'=>1,'examine_status'=>1])->count();
                                if ($cont==0){
                                    $pro=Project::quickGet($item['project_id']);
                                    if ($pro['design_department']==$this->member_info->department_id&&$pro['status']==1){
                                        $number++;
                                    };
                                }

                            }
                        }
                        $arr[]=[
                            'color'=>'#8B5742',//
                            'name'=>'待收款合同',
                            'icon'=>'&#xe61b;',
                            'number'=>$number,
                            'title'=>$value['menu_name'],
                            'url'=>Url::build($value['url']),
                        ];
                        break;
                    default:
                        break;
                }
            }
        }
        return $arr;
    }



    /*市场营销*/
    public function marketData(){
        $arr=[
            'now_month'=>url("marketDataNowMonth"),
            'last_month'=>url('marketDataLastMonth'),
        ];
        $this->assign($arr);
        return $this->fetch('market_data');
    }
    /*本月数据统计*/
    public function marketDataNowMonth(){
        if ($this->request->isPost()){
            $model=new PersonnelUser();
            $time='m';
            $user=$model->marketData($time); //查询用户和项目的关联数据
            $map=[
                'status'=>1,
                'department_id'=>$this->member_info->department_id,
                'company_id'=>$this->member_info->company_id
            ];
            $list=$this->showEasyUiJsonList('base/PersonnelUser',false,$map,false,[],false,'uuid,name');
            $list=$model->statisticsUserWithProject($list,$user);
            return $list;
        }else{
            $arr=[
                'time'=>'now',
                'data_url'=>url("marketDataNowMonth"),
                'more_url'=>url('lookMoreOfUser'),
                'success_url'=>url('lookSuccessOfUser'),
                'false_url'=>url('lookFalseOfUser'),
                'now_url'=>url('lookNowOfUser'),
            ];
            $this->assign($arr);
            return $this->fetch('market_data_list');
        }
    }
    /*上月数据统计*/
    public function marketDataLastMonth(){
        if ($this->request->isPost()){
            $model=new PersonnelUser();
            $time='last month';
            $user=$model->marketData($time); //查询用户和项目的关联数据
            $map=[
                'status'=>1,
                'department_id'=>$this->member_info->department_id,
                'company_id'=>$this->member_info->company_id
            ];
            $list=$this->showEasyUiJsonList('base/PersonnelUser',false,$map,false,[],false,'uuid,name');
            $list=$model->statisticsUserWithProject($list,$user);
            return $list;
        }else{
            $arr=[
                'data_url'=>url("marketDataLastMonth"),
                'time'=>'last',
                'more_url'=>url('lookMoreOfUser'),
                'success_url'=>url('lookSuccessOfUser'),
                'false_url'=>url('lookFalseOfUser'),
                'now_url'=>url('lookNowOfUser'),
            ];
            $this->assign($arr);
            return $this->fetch('market_data_list');
        }
    }
    /*查看个人总客户量详细*/
    public function lookMoreOfUser($time="",$id=""){
        if (empty($time) || empty($id)) return self::showJsonReturnCode(1003);
        if ($this->request->isPost()){
            $model=new Project();
            $limit=$this->request->param('rows');
            $page=$this->request->param('page');
            $limit=empty($limit)?'20':$limit;
            $page=empty($page)?1:$page;
            $list=$model->lookMoreOfUser($time,$id,$this->member_info->company_id,$limit,$page);
            return $list;
        }else{
            $arr=[
                'data_url'=>url("lookMoreOfUser",['time'=>$time,'id'=>$id]),
                'title'=>'',
                'dialog_url'=>'',
            ];
            $this->assign($arr);
            self::echoHtml();
            return $this->fetch('project_user_all');
        }
    }
    /*查看个人签单量详细*/
    public function lookSuccessOfUser($time="",$id=""){
        if (empty($time) || empty($id)) return self::showJsonReturnCode(1003);
        if ($this->request->isPost()){
            $model=new Project();
            $limit=$this->request->param('rows');
            $page=$this->request->param('page');
            $limit=empty($limit)?'20':$limit;
            $page=empty($page)?1:$page;
            $list=$model->lookSuccessOfUser($time,$id,$this->member_info->company_id,$limit,$page);
            return $list;
        }else{
            $arr=[
                'data_url'=>url("lookSuccessOfUser",['time'=>$time,'id'=>$id]),
                'title'=>'',
                'dialog_url'=>'',
            ];
            $this->assign($arr);
            self::echoHtml();
            return $this->fetch('project_user_all');
        }
    }
    /*查看个人废单量详细*/
    public function lookFalseOfUser($time="",$id=""){
        if (empty($time) || empty($id)) return self::showJsonReturnCode(1003);
        if ($this->request->isPost()){
            $model=new Project();
            $limit=$this->request->param('rows');
            $page=$this->request->param('page');
            $limit=empty($limit)?'20':$limit;
            $page=empty($page)?1:$page;
            $list=$model->lookFalseOfUser($time,$id,$this->member_info->company_id,$limit,$page);
            return $list;
        }else{
            $arr=[
                'data_url'=>url("lookFalseOfUser",['time'=>$time,'id'=>$id]),
                'title'=>'',
                'dialog_url'=>'',
            ];
            $this->assign($arr);
            self::echoHtml();
            return $this->fetch('project_user_all');
        }
    }
    /*查看个人正在跟踪量详细*/
    public function lookNowOfUser($time="",$id=""){
        if (empty($time) || empty($id)) return self::showJsonReturnCode(1003);
        if ($this->request->isPost()){
            $model=new Project();
            $limit=$this->request->param('rows');
            $page=$this->request->param('page');
            $limit=empty($limit)?'20':$limit;
            $page=empty($page)?1:$page;
            $list=$model->lookNowOfUser($time,$id,$this->member_info->company_id,$limit,$page);
            return $list;
        }else{
            $arr=[
                'data_url'=>url("lookNowOfUser",['time'=>$time,'id'=>$id]),
                'title'=>'',
                'dialog_url'=>'',
            ];
            $this->assign($arr);
            self::echoHtml();
            return $this->fetch('project_user_all');
        }
    }

    /*客服前台*/
    public function serviceData(){
        $arr=[
            'now_month'=>url("serviceDataNowMonth"),
            'last_month'=>url('serviceDataLastMonth'),
        ];
        $this->assign($arr);
        return $this->fetch('market_data');
    }
    /*本月数据统计*/
    public function serviceDataNowMonth(){
        if ($this->request->isPost()){
            $model=new PersonnelUser();
            $time='m';
            $user=$model->marketData($time); //查询用户和项目的关联数据
            $map=[
                'status'=>1,
                'department_id'=>$this->member_info->department_id,
                'company_id'=>$this->member_info->company_id
            ];

            $list=$this->showEasyUiJsonList('base/PersonnelUser',false,$map,false,[],false,'uuid,name');
            $list=$model->statisticsUserWithProject($list,$user);
            return $list;
        }else{
            $arr=[
                'time'=>'now',
                'data_url'=>url("serviceDataNowMonth"),
                'more_url'=>url('lookMoreOfUserService'),
                'success_url'=>url('lookSuccessOfUserService'),
                'false_url'=>url('lookFalseOfUserService'),
                'now_url'=>url('lookNowOfUserService'),
            ];
            $this->assign($arr);
            return $this->fetch('market_data_list');
        }
    }
    /*上月数据统计*/
    public function serviceDataLastMonth(){
        if ($this->request->isPost()){
            $model=new PersonnelUser();
            $time='last month';
            $user=$model->marketData($time); //查询用户和项目的关联数据
            $map=[
                'status'=>1,
                'department_id'=>$this->member_info->department_id,
                'company_id'=>$this->member_info->company_id
            ];
            $list=$this->showEasyUiJsonList('base/PersonnelUser',false,$map,false,[],false,'uuid,name');
            $list=$model->statisticsUserWithProject($list,$user);
            return $list;
        }else{
            $arr=[
                'data_url'=>url("serviceDataLastMonth"),
                'time'=>'last',
                'more_url'=>url('lookMoreOfUserService'),
                'success_url'=>url('lookSuccessOfUserService'),
                'false_url'=>url('lookFalseOfUserService'),
                'now_url'=>url('lookNowOfUserService'),
            ];
            $this->assign($arr);
            return $this->fetch('market_data_list');
        }
    }
    /*查看个人总客户量详细*/
    public function lookMoreOfUserService($time="",$id=""){
        if (empty($time) || empty($id)) return self::showJsonReturnCode(1003);
        if ($this->request->isPost()){
            $model=new Project();
            $limit=$this->request->param('rows');
            $page=$this->request->param('page');
            $limit=empty($limit)?'20':$limit;
            $page=empty($page)?1:$page;
            $list=$model->lookMoreOfUser($time,$id,$this->member_info->company_id,$limit,$page);
            return $list;
        }else{
            $arr=[
                'data_url'=>url("lookMoreOfUserService",['time'=>$time,'id'=>$id]),
                'title'=>'',
                'dialog_url'=>'',
            ];
            $this->assign($arr);
            self::echoHtml();
            return $this->fetch('project_user_all');
        }
    }
    /*查看个人签单量详细*/
    public function lookSuccessOfUserService($time="",$id=""){
        if (empty($time) || empty($id)) return self::showJsonReturnCode(1003);
        if ($this->request->isPost()){
            $model=new Project();
            $limit=$this->request->param('rows');
            $page=$this->request->param('page');
            $limit=empty($limit)?'20':$limit;
            $page=empty($page)?1:$page;
            $list=$model->lookSuccessOfUser($time,$id,$this->member_info->company_id,$limit,$page);
            return $list;
        }else{
            $arr=[
                'data_url'=>url("lookSuccessOfUserService",['time'=>$time,'id'=>$id]),
                'title'=>'',
                'dialog_url'=>'',
            ];
            $this->assign($arr);
            self::echoHtml();
            return $this->fetch('project_user_all');
        }
    }
    /*查看个人废单量详细*/
    public function lookFalseOfUserService($time="",$id=""){
        if (empty($time) || empty($id)) return self::showJsonReturnCode(1003);
        if ($this->request->isPost()){
            $model=new Project();
            $limit=$this->request->param('rows');
            $page=$this->request->param('page');
            $limit=empty($limit)?'20':$limit;
            $page=empty($page)?1:$page;
            $list=$model->lookFalseOfUser($time,$id,$this->member_info->company_id,$limit,$page);
            return $list;
        }else{
            $arr=[
                'data_url'=>url("lookFalseOfUserService",['time'=>$time,'id'=>$id]),
                'title'=>'',
                'dialog_url'=>'',
            ];
            $this->assign($arr);
            self::echoHtml();
            return $this->fetch('project_user_all');
        }
    }
    /*查看个人正在跟踪量详细*/
    public function lookNowOfUserService($time="",$id=""){
        if (empty($time) || empty($id)) return self::showJsonReturnCode(1003);
        if ($this->request->isPost()){
            $model=new Project();
            $limit=$this->request->param('rows');
            $page=$this->request->param('page');
            $limit=empty($limit)?'20':$limit;
            $page=empty($page)?1:$page;
            $list=$model->lookNowOfUser($time,$id,$this->member_info->company_id,$limit,$page);
            return $list;
        }else{
            $arr=[
                'data_url'=>url("lookNowOfUserService",['time'=>$time,'id'=>$id]),
                'title'=>'',
                'dialog_url'=>'',
            ];
            $this->assign($arr);
            self::echoHtml();
            return $this->fetch('project_user_all');
        }
    }

    /*预算报价*/
    public function designData(){
        $arr=[
            'now_month'=>url("designDataNowMonth"),
            'last_month'=>url('designDataLastMonth'),
        ];
        $this->assign($arr);
        return $this->fetch('market_data');
    }
    /*本月数据统计*/
    public function designDataNowMonth(){
        if ($this->request->isPost()){
            $model=new PersonnelUser();
            $time='m';
            $user=$model->designData($time); //查询用户和项目的关联数据
            $map=[
                'status'=>1,
                'department_id'=>$this->member_info->department_id,
                'company_id'=>$this->member_info->company_id
            ];

            $list=$this->showEasyUiJsonList('base/PersonnelUser',false,$map,false,[],false,'uuid,name');
            $list=$model->statisticsUserWithDesign($list,$user);
            return $list;
        }else{
            $arr=[
                'time'=>'now',
                'data_url'=>url("designDataNowMonth"),
                'more_url'=>url('lookMoreOfUserDesign'),
                'success_url'=>url('lookSuccessOfUserDesign'),
                'false_url'=>url('lookFalseOfUserDesign'),
                'now_url'=>url('lookNowOfUserDesign'),
            ];
            $this->assign($arr);
            return $this->fetch('market_data_list');
        }
    }
    /*上月数据统计*/
    public function designDataLastMonth(){
        if ($this->request->isPost()){
            $model=new PersonnelUser();
            $time='last month';
            $user=$model->designData($time); //查询用户和项目的关联数据
            $map=[
                'status'=>1,
                'department_id'=>$this->member_info->department_id,
                'company_id'=>$this->member_info->company_id
            ];
            $list=$this->showEasyUiJsonList('base/PersonnelUser',false,$map,false,[],false,'uuid,name');
            $list=$model->statisticsUserWithDesign($list,$user);
            return $list;
        }else{
            $arr=[
                'data_url'=>url("designDataLastMonth"),
                'time'=>'last',
                'more_url'=>url('lookMoreOfUserDesign'),
                'success_url'=>url('lookSuccessOfUserDesign'),
                'false_url'=>url('lookFalseOfUserDesign'),
                'now_url'=>url('lookNowOfUserDesign'),
            ];
            $this->assign($arr);
            return $this->fetch('market_data_list');
        }
    }
    /*查看个人总客户量详细*/
    public function lookMoreOfUserDesign($time="",$id=""){
        if (empty($time) || empty($id)) return self::showJsonReturnCode(1003);
        if ($this->request->isPost()){
            $model=new Project();
            $limit=$this->request->param('rows');
            $page=$this->request->param('page');
            $limit=empty($limit)?'20':$limit;
            $page=empty($page)?1:$page;
            $list=$model->lookMoreOfUserDesign($time,$id,$this->member_info->company_id,$limit,$page);
            return $list;
        }else{
            $arr=[
                'data_url'=>url("lookMoreOfUserDesign",['time'=>$time,'id'=>$id]),
                'title'=>'',
                'dialog_url'=>'',
            ];
            $this->assign($arr);
            self::echoHtml();
            return $this->fetch('project_user_all');
        }
    }
    /*查看个人签单量详细*/
    public function lookSuccessOfUserDesign($time="",$id=""){
        if (empty($time) || empty($id)) return self::showJsonReturnCode(1003);
        if ($this->request->isPost()){
            $model=new Project();
            $limit=$this->request->param('rows');
            $page=$this->request->param('page');
            $limit=empty($limit)?'20':$limit;
            $page=empty($page)?1:$page;
            $list=$model->lookSuccessOfUserDesign($time,$id,$this->member_info->company_id,$limit,$page);
            return $list;
        }else{
            $arr=[
                'data_url'=>url("lookSuccessOfUserDesign",['time'=>$time,'id'=>$id]),
                'title'=>'',
                'dialog_url'=>'',
            ];
            $this->assign($arr);
            self::echoHtml();
            return $this->fetch('project_user_all');
        }
    }
    /*查看个人废单量详细*/
    public function lookFalseOfUserDesign($time="",$id=""){
        if (empty($time) || empty($id)) return self::showJsonReturnCode(1003);
        if ($this->request->isPost()){
            $model=new Project();
            $limit=$this->request->param('rows');
            $page=$this->request->param('page');
            $limit=empty($limit)?'20':$limit;
            $page=empty($page)?1:$page;
            $list=$model->lookFalseOfUserDesign($time,$id,$this->member_info->company_id,$limit,$page);
            return $list;
        }else{
            $arr=[
                'data_url'=>url("lookFalseOfUserService",['time'=>$time,'id'=>$id]),
                'title'=>'',
                'dialog_url'=>'',
            ];
            $this->assign($arr);
            self::echoHtml();
            return $this->fetch('project_user_all');
        }
    }
    /*查看个人正在跟踪量详细*/
    public function lookNowOfUserDesign($time="",$id=""){
        if (empty($time) || empty($id)) return self::showJsonReturnCode(1003);
        if ($this->request->isPost()){
            $model=new Project();
            $limit=$this->request->param('rows');
            $page=$this->request->param('page');
            $limit=empty($limit)?'20':$limit;
            $page=empty($page)?1:$page;
            $list=$model->lookNowOfUserDesign($time,$id,$this->member_info->company_id,$limit,$page);
            return $list;
        }else{
            $arr=[
                'data_url'=>url("lookNowOfUserDesign",['time'=>$time,'id'=>$id]),
                'title'=>'',
                'dialog_url'=>'',
            ];
            $this->assign($arr);
            self::echoHtml();
            return $this->fetch('project_user_all');
        }
    }
    /*公司业绩*/
    public function showMarketData(){
        $arr=[
            'now_month'=>url("showMarketDataNowMonth"),
            'last_month'=>url('showMarketDataLastMonth'),
        ];
        $this->assign($arr);
        return $this->fetch('market_data');
    }
    public function showMarketDataNowMonth(){
        if ($this->request->isPost()){
            $model=new PersonnelUser();
            $time='m';
            $user=$model->marketDataAll($time); //查询用户和项目的关联数据
            $type=array_search("市场部",FieldValue::getFieldValue("department_type"))?:6;
            $dep_list=Db::table('mk_personnel_department')
                ->where(['company_id'=>$this->member_info->company_id,'status'=>1,'department_type'=>$type])
                ->select();
            $arr=[];
            if (!empty($dep_list)){
                foreach ($dep_list as $value){
                    $arr[]=$value['guid'];
                }
            }
            $map=[
                'status'=>1,
                'department_id'=>['in',$arr],
                'company_id'=>$this->member_info->company_id
            ];
            $list=$this->showEasyUiJsonList('base/PersonnelUser',false,$map,false,[],false,'uuid,name');
            $list=$model->statisticsUserWithProject($list,$user);
            if (!empty($dep_list)){
                foreach ($dep_list as $value){
                    $sum=0;
                    $cg=0;
                    $fa=0;
                    $now=0;
                    if (!empty($list['rows'])){
                        foreach ($list['rows'] as $key=> $item){
                            if ($value['guid']==$item['department_id']){
                                $sum+=$item['sum'];
                                $cg+=$item['success'];
                                $fa+=$item['false'];
                                $now+=$item['now'];
                                $list['rows'][$key]['_parentId']=$value['guid'];
                                $list['rows'][$key]['iconCls']='icon-blank';
                            }
                        }
                        $list['rows'][]=[
                            'department_id'=>$value['guid'],
                            'department_name'=>$value['department_name'],
                            'sum'=>$sum,
                            'success'=>$cg,
                            'false'=>$fa,
                            'now'=>$now,
                            'rate'=>$sum==0?'0%':($cg/$sum*100).'%',
                            'iconCls'=>'icon-blank'
                        ];
                    }
                }
            }
            return $list;
        }else{
            $arr=[
                'time'=>'now',
                'data_url'=>url("showMarketDataNowMonth"),
                'more_url'=>url('lookMoreOfUser'),
                'success_url'=>url('lookSuccessOfUser'),
                'false_url'=>url('lookFalseOfUser'),
                'now_url'=>url('lookNowOfUser'),
            ];
            $this->assign($arr);
            return $this->fetch('data_list');
        }
    }
    /*市场部上月业绩数据*/
    public function showMarketDataLastMonth(){
        if ($this->request->isPost()){
            $model=new PersonnelUser();
            $time='last month';
            $user=$model->marketDataAll($time); //查询用户和项目的关联数据
            $type=array_search("市场部",FieldValue::getFieldValue("department_type"))?:6;
            $dep_list=Db::table('mk_personnel_department')
                ->where(['company_id'=>$this->member_info->company_id,'status'=>1,'department_type'=>$type])
                ->select();
            $arr=[];
            if (!empty($dep_list)){
                foreach ($dep_list as $value){
                    $arr[]=$value['guid'];
                }
            }
            $map=[
                'status'=>1,
                'department_id'=>['in',$arr],
                'company_id'=>$this->member_info->company_id
            ];
            $list=$this->showEasyUiJsonList('base/PersonnelUser',false,$map,false,[],false,'uuid,name');
            $list=$model->statisticsUserWithProject($list,$user);
            if (!empty($dep_list)){
                foreach ($dep_list as $value){
                    $sum=0;
                    $cg=0;
                    $fa=0;
                    $now=0;
                    if (!empty($list['rows'])){
                        foreach ($list['rows'] as $key=> $item){
                            if ($value['guid']==$item['department_id']){
                                $sum+=$item['sum'];
                                $cg+=$item['success'];
                                $fa+=$item['false'];
                                $now+=$item['now'];
                                $list['rows'][$key]['_parentId']=$value['guid'];
                                $list['rows'][$key]['iconCls']='icon-blank';
                            }
                        }
                        $list['rows'][]=[
                            'department_id'=>$value['guid'],
                            'department_name'=>$value['department_name'],
                            'sum'=>$sum,
                            'success'=>$cg,
                            'false'=>$fa,
                            'now'=>$now,
                            'rate'=>$sum==0?'0%':($cg/$sum*100).'%',
                            'iconCls'=>'icon-blank'
                        ];
                    }
                }
            }
            return $list;
        }else{
            $arr=[
                'time'=>'now',
                'data_url'=>url("showMarketDataLastMonth"),
                'more_url'=>url('lookMoreOfUser'),
                'success_url'=>url('lookSuccessOfUser'),
                'false_url'=>url('lookFalseOfUser'),
                'now_url'=>url('lookNowOfUser'),
            ];
            $this->assign($arr);
            return $this->fetch('data_list');
        }
    }
    /*客服部业绩*/
    public function showServiceData(){
        $arr=[
            'now_month'=>url("showServiceDataNowMonth"),
            'last_month'=>url('showServiceDataLastMonth'),
        ];
        $this->assign($arr);
        return $this->fetch('market_data');
    }
    /*客服部本月业绩数据*/
    public function showServiceDataNowMonth(){
        if ($this->request->isPost()){
            $model=new PersonnelUser();
            $time='m';
            $user=$model->serviceDataAll($time); //查询用户和项目的关联数据
            $type=array_search("客服部",FieldValue::getFieldValue("department_type"))?:8;
            $dep_list=Db::table('mk_personnel_department')
                ->where(['company_id'=>$this->member_info->company_id,'status'=>1,'department_type'=>$type])
                ->select();
            $arr=[];
            if (!empty($dep_list)){
                foreach ($dep_list as $value){
                    $arr[]=$value['guid'];
                }
            }
            $map=[
                'status'=>1,
                'department_id'=>['in',$arr],
                'company_id'=>$this->member_info->company_id
            ];
            $list=$this->showEasyUiJsonList('base/PersonnelUser',false,$map,false,[],false,'uuid,name');
            $list=$model->statisticsUserWithProject($list,$user);
            if (!empty($dep_list)){
                foreach ($dep_list as $value){
                    $sum=0;
                    $cg=0;
                    $fa=0;
                    $now=0;
                    if (!empty($list['rows'])){
                        foreach ($list['rows'] as $key=> $item){
                            if ($value['guid']==$item['department_id']){
                                $sum+=$item['sum'];
                                $cg+=$item['success'];
                                $fa+=$item['false'];
                                $now+=$item['now'];
                                $list['rows'][$key]['_parentId']=$value['guid'];
                                $list['rows'][$key]['iconCls']='icon-blank';
                            }
                        }
                        $list['rows'][]=[
                            'department_id'=>$value['guid'],
                            'department_name'=>$value['department_name'],
                            'sum'=>$sum,
                            'success'=>$cg,
                            'false'=>$fa,
                            'now'=>$now,
                            'rate'=>$sum==0?'0%':($cg/$sum*100).'%',
                            'iconCls'=>'icon-blank'
                        ];
                    }
                }
            }
            return $list;
        }else{
            $arr=[
                'time'=>'now',
                'data_url'=>url("showServiceDataNowMonth"),
                'more_url'=>url('lookMoreOfUserService'),
                'success_url'=>url('lookSuccessOfUserService'),
                'false_url'=>url('lookFalseOfUserService'),
                'now_url'=>url('lookNowOfUserService'),
            ];
            $this->assign($arr);
            return $this->fetch('data_list');
        }
    }
    /*客服部上月业绩数据*/
    public function showServiceDataLastMonth(){
        if ($this->request->isPost()){
            $model=new PersonnelUser();
            $time='m';
            $user=$model->serviceDataAll($time); //查询用户和项目的关联数据
            $type=array_search("客服部",FieldValue::getFieldValue("department_type"))?:8;
            $dep_list=Db::table('mk_personnel_department')
                ->where(['company_id'=>$this->member_info->company_id,'status'=>1,'department_type'=>$type])
                ->select();
            $arr=[];
            if (!empty($dep_list)){
                foreach ($dep_list as $value){
                    $arr[]=$value['guid'];
                }
            }
            $map=[
                'status'=>1,
                'department_id'=>['in',$arr],
                'company_id'=>$this->member_info->company_id
            ];
            $list=$this->showEasyUiJsonList('base/PersonnelUser',false,$map,false,[],false,'uuid,name');
            $list=$model->statisticsUserWithProject($list,$user);
            if (!empty($dep_list)){
                foreach ($dep_list as $value){
                    $sum=0;
                    $cg=0;
                    $fa=0;
                    $now=0;
                    if (!empty($list['rows'])){
                        foreach ($list['rows'] as $key=> $item){
                            if ($value['guid']==$item['department_id']){
                                $sum+=$item['sum'];
                                $cg+=$item['success'];
                                $fa+=$item['false'];
                                $now+=$item['now'];
                                $list['rows'][$key]['_parentId']=$value['guid'];
                                $list['rows'][$key]['iconCls']='icon-blank';
                            }
                        }
                        $list['rows'][]=[
                            'department_id'=>$value['guid'],
                            'department_name'=>$value['department_name'],
                            'sum'=>$sum,
                            'success'=>$cg,
                            'false'=>$fa,
                            'now'=>$now,
                            'rate'=>$sum==0?'0%':($cg/$sum*100).'%',
                            'iconCls'=>'icon-blank'
                        ];
                    }
                }
            }
            return $list;
        }else{
            $arr=[
                'time'=>'now',
                'data_url'=>url("showServiceDataLastMonth"),
                'more_url'=>url('lookMoreOfUserService'),
                'success_url'=>url('lookSuccessOfUserService'),
                'false_url'=>url('lookFalseOfUserService'),
                'now_url'=>url('lookNowOfUserService'),
            ];
            $this->assign($arr);
            return $this->fetch('data_list');
        }
    }

    public function showDesignData(){
        $arr=[
            'now_month'=>url("showDesignDataNowMonth"),
            'last_month'=>url('showDesignDataLastMonth'),
        ];
        $this->assign($arr);
        return $this->fetch('market_data');
    }
    /*设计部本月业绩数据*/
    public function showDesignDataNowMonth(){
        if ($this->request->isPost()){
            $model=new PersonnelUser();
            $time='m';
            $user=$model->designDataAll($time); //查询用户和项目的关联数据
            $type=array_search("设计部",FieldValue::getFieldValue("department_type"))?:3;
            $dep_list=Db::table('mk_personnel_department')
                ->where(['company_id'=>$this->member_info->company_id,'status'=>1,'department_type'=>$type])
                ->select();
            $arr=[];
            if (!empty($dep_list)){
                foreach ($dep_list as $value){
                    $arr[]=$value['guid'];
                }
            }
            $map=[
                'status'=>1,
                'department_id'=>['in',$arr],
                'company_id'=>$this->member_info->company_id
            ];
            $list=$this->showEasyUiJsonList('base/PersonnelUser',false,$map,false,[],false,'uuid,name');
            $list=$model->statisticsUserWithDesign($list,$user);
            if (!empty($dep_list)){
                foreach ($dep_list as $value){
                    $sum=0;
                    $cg=0;
                    $fa=0;
                    $now=0;
                    if (!empty($list['rows'])){
                        foreach ($list['rows'] as $key=> $item){
                            if ($value['guid']==$item['department_id']){
                                $sum+=$item['sum'];
                                $cg+=$item['success'];
                                $fa+=$item['false'];
                                $now+=$item['now'];
                                $list['rows'][$key]['_parentId']=$value['guid'];
                                $list['rows'][$key]['iconCls']='icon-blank';
                            }
                        }
                        $list['rows'][]=[
                            'department_id'=>$value['guid'],
                            'department_name'=>$value['department_name'],
                            'sum'=>$sum,
                            'success'=>$cg,
                            'false'=>$fa,
                            'now'=>$now,
                            'rate'=>$sum==0?'0%':($cg/$sum*100).'%',
                            'iconCls'=>'icon-blank'
                        ];
                    }
                }
            }
            return $list;
        }else{
            $arr=[
                'time'=>'now',
                'data_url'=>url("showDesignDataNowMonth"),
                'more_url'=>url('lookMoreOfUserDesign'),
                'success_url'=>url('lookSuccessOfUserDesign'),
                'false_url'=>url('lookFalseOfUserDesign'),
                'now_url'=>url('lookNowOfUserDesign'),
            ];
            $this->assign($arr);
            return $this->fetch('data_list');
        }
    }
    /*设计部月业绩数据*/
    public function showDesignDataLastMonth(){
        if ($this->request->isPost()){
            $model=new PersonnelUser();
            $time='last month';
            $user=$model->designDataAll($time); //查询用户和项目的关联数据
            $type=array_search("设计部",FieldValue::getFieldValue("department_type"))?:3;
            $dep_list=Db::table('mk_personnel_department')
                ->where(['company_id'=>$this->member_info->company_id,'status'=>1,'department_type'=>$type])
                ->select();
            $arr=[];
            if (!empty($dep_list)){
                foreach ($dep_list as $value){
                    $arr[]=$value['guid'];
                }
            }
            $map=[
                'status'=>1,
                'department_id'=>['in',$arr],
                'company_id'=>$this->member_info->company_id
            ];
            $list=$this->showEasyUiJsonList('base/PersonnelUser',false,$map,false,[],false,'uuid,name');
            $list=$model->statisticsUserWithDesign($list,$user);
            if (!empty($dep_list)){
                foreach ($dep_list as $value){
                    $sum=0;
                    $cg=0;
                    $fa=0;
                    $now=0;
                    if (!empty($list['rows'])){
                        foreach ($list['rows'] as $key=> $item){
                            if ($value['guid']==$item['department_id']){
                                $sum+=$item['sum'];
                                $cg+=$item['success'];
                                $fa+=$item['false'];
                                $now+=$item['now'];
                                $list['rows'][$key]['_parentId']=$value['guid'];
                                $list['rows'][$key]['iconCls']='icon-blank';
                            }
                        }
                        $list['rows'][]=[
                            'department_id'=>$value['guid'],
                            'department_name'=>$value['department_name'],
                            'sum'=>$sum,
                            'success'=>$cg,
                            'false'=>$fa,
                            'now'=>$now,
                            'rate'=>$sum==0?'0%':($cg/$sum*100).'%',
                            'iconCls'=>'icon-blank'
                        ];
                    }
                }
            }
            return $list;
        }else{
            $arr=[
                'data_url'=>url("showDesignDataLastMonth"),
                'time'=>'last',
                'more_url'=>url('lookMoreOfUserDesign'),
                'success_url'=>url('lookSuccessOfUserDesign'),
                'false_url'=>url('lookFalseOfUserDesign'),
                'now_url'=>url('lookNowOfUserDesign'),
            ];
            $this->assign($arr);
            return $this->fetch('data_list');
        }
    }
}