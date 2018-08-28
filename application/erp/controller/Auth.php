<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/4/17
 * Time: 14:47
 */

namespace app\erp\controller;


use app\base\model\PersonnelNode;
use app\base\model\PersonnelRoleNodeAccess;
use think\Session;

abstract class Auth extends BaseEasyUI
{
    //權限
    protected $index_array=[
        'Index'=>[
            'index'=>'true',
            'indexnew'=>1,
            'main'=>'true',
            'mainnew'=>1,
            'getuseronline'=>1, //在线人员
            'saleschampion'=>1,  //销售月冠
            'team_opus'=>1,
            'getmenujson'=>true,
            'showrolejson'=>1,
            'getdepartmentnotice'=>1,
            'getcompanynotice'=>1,
            'getteamdata'=>1,
            'getelectrondata'=>1,//精美案例
            'getuserwork'=>1,//个人的设计量，业务量
        ],
        'Remind'=>[
            'projectremind'=>1,//项目提醒个数
            'showprojectremind'=>'1',//显示本人添加的项目提醒信息
            'remindnumber'=>'1',
            'showcollectremind'=>'1',
        ],
        'Statistics'=>[
            'homedatanumber'=>1,
        ],
        'Contract'=>[
          'showcontractdata'=>1
        ],
        'Project'=>[
            'showdepartmentdata'=>1,
            'showuserdata'=>1,
            'showdepartmentdataby'=>1,
            'showuserdataby'=>1,
            'showcompanyjson'=>1,
            'showrolejson'=>1,
            'showcontactedit'=>1,
//            'editcontactsdata'=>1, 修改联系人数据
             'showstructureedit'=>1,
//            'editstructuredata'=>1,  修改房屋结构
            'showbuildingedit'=>1,
//            'editbuildingdata'=>1,  修改楼盘信息
            'showprojectedit'=>1,
//            'editprojectdata'=>1,   修改项目信息
            'showdepartmentdataall'=>1,
            'showuserdataall'=>1,
            'showaddfield'=>1,// 上传图片
            'showdepartmentjson'=>1,
        ],
        'System'=>[
            'showuserpassword'=>1, //密码修改页
            'setpersonnelpassword'=>1,  //密码修改
            'showjobsjson'=>1,
            'showuserdata'=>1,
            'showdepartmentjson'=>1,
            'showuserdataby'=>1,
            'showcompanyjson'=>1,
            'showrolejson'=>1,
            'shownodejson'=>1,
            'showappidjson'=>1,
            'showmenujson'=>1,
            'showbuttonjson'=>1,
            'showmenubutton'=>1,
//            'editmenubutton'=>1,  微信按钮修改
            'showuserjson'=>1,
            'showdepartmentjsonuser'=>1,
            'addleavedata'=>1,
            'loadmenubuttonguid'=>1,
            'editmenubuttonguid'=>1,
            'projectedit'=>1,
            'delmenubutton'=>1,
            'addstaff'=>1,
            'sendmenu'=>1,
            'addplan'=>1,
            'showworklog'=>1,
//            'addworklog'=>1, 添加计划日记
            'showlogwork'=>1,
//            'showlogworklist'=>1,  查看计划日志
            'showplanedit'=>1,
//            'planedit'=>1    修改个人计划


        ],
        'Work'=>[
            'showworkstatus'=>1,
//            'editworkstatus'=>1,  修改计划状态
            'showplanexamine'=>1,
            'showrolejson'=>'1',
//            'editplanexamine'=>1,  计划审批
            'showplanall'=>1,
            'showworkdetailed'=>1,
            'showplandep'=>1,
//            'addnoticeanddepartment'=>1,  公告发布
            'shownoticedetailed'=>1,
            'shownoticeall'=>1,
//            'addmanagerline'=>1,   添加总经理专线
             'showmanagerlistuser'=>1,
//            'managerliststatus'=>1,   专线列表数据
//            'editmanagerstatus'=>1,   进入专线审核页面
//            'editstatusmanager'=>1,   专线审阅的权限
            'showuser'=>1,
            'showcomplaintdesc'=>1,
            'showcompanyjson'=>1,
            'showdepartmentjson'=>1,
            'showjobsjson'=>1,
            'test'=>1,
        ],
        'Design'=>[
            'getuserjson'=>1,//获取设计部的人员
            'showbudgetjson'=>1 ,//获取空间模板数据
            'showtembudgetjson'=>1,
            'showedit'=>1, //显示单个数量修改页
            'showdatanumberhtml'=>1, //显示所有数量修改页
            'showaddbudgethtml'=>1,
            'showschedulephotohtml'=>1,
            'budgetbook'=>1,
            'showratehtml'=>1,//显示修改费率值页面
            'showratejson'=>1,//显示费率数据
            'showdatajson'=>1, //显示装饰数据
            'showbook'=>1,
            'showdatadeschtml'=>1,
            'showdatajsondesc'=>1,
            'savedatadesc'=>1,
            'showuploadhtml'=>1, //图片上传
            'showuploadfile'=>1, //附件上传
            'showdesignphotolist'=>1,//显示图片列表
            'showdesignfieldlist'=>1,//显示文件列表
            'showdesc'=>1,//申请进度验收
            'showbudgetlistedit'=>1,
            'showaddschedulefieldhtml'=>1,
//            'showbudgetbookcopy'=>'1',
          //  'showprolistbyusercopy'=>1,//测试  等下删除
        ],
        'Material'=>[
            'showcategoryjson'=>1,
            'showcatejson'=>1,
            'showcategorytree'=>1,
            'showbasestylejson'=>1,
            'showdatastylejson'=>1,
            'showtemplatejson'=>1,
            'showdatalist'=>1,
            'showunitjson'=>1,
            'showtreebudget'=>1,
            'showuploadhtml'=>1,//显示上传图片页面
            'showpackagetypejson'=>1,
            'showpackagestylejson'=>1,
        ],
        'MaterialMarket'=>[
            'showmarketdata'=>1, //显示主材商城
        ],
        'MarketAuxiliary'=>[
            'showmarketdata'=>1, //显示辅材商城
        ],
        'User'=>[
            'showcompanyjson'=>1, //显示本公司数据
            'showdepartmentjson'=>1, //显示本公司部门
            'showjobsjson'=>1, //显示本公司职位
            'showrolejson'=>1,
            'showusertree'=>1,
            'showedituseropus'=>1,

        ],
        'DesignExtract'=>[
            'showapplyedit'=>1,
            'showapprovaledit'=>1,
            'showlogapp'=>1, //显示申请打印记录
            'showlogchange'=>1, //显示申请预算修改记录
            'lookspace'=>1, //显示空间内容
            'delspace'=>1,
            'getspacedata'=>1,
            'copys'=>1, //复制空间
            'showdudgetbook'=>'1',//浏览预算
        ],
        'ProjectDeposit'=>[
            'showpaystylejson'=>1,  //付款方式json数据
            'showbankjson'=>1, //账号数据
            'showcollectjson'=>1,
            'showcashieruserjson'=>1,
            'formload'=>1,
            'loadformrefund'=>1,
            'showcollectplanjson'=>1,
            'showcollectplancon'=>1,//单个计划的详细
        ],
        'Finance'=>[
            'showcollpanlist'=>1, //显示收款计划模板
            'pricepaylist'=>1, //付款数据
            'showaddphotohtml'=>1,
            'showbankjson'=>1,
        ],
        'Engin'=>[
            'showmore'=>1, //显示材料详细
            'showmorecopy'=>1,
            'showmaterialsup'=>1, //显示该项目里面材料对应的供应商
            'showmateriallist'=>1,
            'showapplyedit'=>1, //显示采购单页面
            'consignee'=>1, //收货人信息
        ],
        'EnginProject'=>[
            'showbuildtype'=>1,
            'showuser'=>1,
           
        ],
        'Sign'=>[
            'showpersonnelsign'=>1
        ],
        'ProjectPayment'=>[
            'formload'=>1,
        ],
        'BuildNone'=>[
            'text'=>1,
            'userjson'=>1,
            'showuserjson'=>1,
            'showaddschedulephotohtml'=>1,
            'showpaydetailed'=>1,
        ],

    ];
    protected $log_string;
    protected $member_info;
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub

        //检测是否登陆
        if(!$this->isLogin) {
            $this->redirect(url('login/login'));
        }
        //检测登陆权限
        $auth =$this->checkNodeAuth();

        //dump($auth);
        if($auth!=true || $auth==false || is_string($auth)){
           //  dump($this->log_string);
            $this->error($this->log_string,"erp/index/index");
        }
        $this->member_info = Session::get('member_info','Global');
    }

    protected function checkNodeAuth(){
        if ($this->checkIsAdmin()) {
            return true;
        } else {
            $request = $this->request;
            if ($this->checkIsIndex($request->controller(), $request->action())) {
                return true;
            }
            //检测权限// 当前模块名

            $node = new PersonnelNode();
            //跳过登录系列的检测以及主页权限
            $node_info = $node->getNodeInfo($request->module(), $request->controller(), $request->action());

           // dump($node_info);
            if (empty($node_info)) {
                $this->log_string='此页面访问权限未开放，请联系管理员';
                return false;
            }
            if ($node_info['auth_grade'] > 0) {
                return $this->checkUserNodeAuthByNodeGuId($node_info['guid']);
            }
            return true;
        }
    }

    protected function checkIsIndex($controller,$action)
    {
        return isset($this->index_array[$controller][$action])? true : false ;
    }

    protected function checkUserNodeAuthByNodeGuId($Guid)
    {

        $member = $this->member_info;
        $node_list =[];
        if (Session::has("role_node_list_{$Guid}")){
            $node_list=Session::has("role_node_list_{$Guid}");

        }else{
            $model = new PersonnelRoleNodeAccess();
            $node_list = $model->getRoleMenuList($member['role_id']);

        }

        if (!in_array($Guid, $node_list)) {
            $this->log_string="你没有权限，请联系系统管理员";
            return false;
        }else{
            return true;
        }
    }

    protected function checkIsAdmin()
    {
        if (Session::has('is_admin')) {
            return true;
        } else {
            return false;
        }
    }




}