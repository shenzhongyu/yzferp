<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/4/14
 * Time: 17:30
 */

namespace app\base\model;


class ModelCode
{
    static public $uuid_list = [
        'PersonnelNode' => 'ND',//用户权限节点
        'PersonnelUser'=>'PU',  //用户GUID
        'PersonnelCompany'=>"CP",  //公司
        'PersonnelDepartment'=>"DP",   //部门
        'PersonnelJobs'=>'PJ',   //职位
        'PersonnelLogRecord'=>'RA',
        'PersonnelRole'=>'RB',
        'PersonnelTrackStatus'=>'RC',


        'Material'=>'MA', //材料
        'MaterialSupplier'=>'MB' , //材料供应商
        'MaterialBaseStyle'=>'MC', //基装类型
        'MaterialCategory'=>'MD', // 材料类别名称
        'MaterialDataItem'=>'ME', //数据项名称
        'MaterialDataStyle'=>'MF', //数据项类别
        'MaterialTemplateBudget'=>'MG',// 空间名称
        'MaterialTemplateBudgetAccess'=>'MH',
        'MaterialTemplateStyle'=>'MI', //空间类别
        'MaterialListEdit'=>'MJ',
        'MaterialRateItem'=>'MK',
        'MaterialPackageCategory'=>'ML', //套餐类型下面的类别
        'MaterialPackageType'=>'MM', //套餐类型

        'OfficeComplaint'=>'OA',// 维修单
        'OfficeLeave'=>'OB', // 请假申请
        'OfficeManagerLine'=>'OC', //总经理专线
        'OfficeNotice'=>'OD', //公告
        'OfficePlan'=>'OE', //计划
        'OfficePlanLog'=>'OF', //计划日志
        'OfficeSign'=>'OG' ,//签到
        'OfficeNoticeAccess'=>'OH',
        'OfficeVehicle'=>'OI',
        'OfficeVehicleApply'=>'OJ',
        'OfficeSupplies'=>'OK',

        'Project'=>'PA',//项目
        'ProjectTraceLog'=>'PT', //日志
        'ProjectRemindTime'=>'PR', //提醒时间
        'ProjectContacts'=>'PC', //项目联系人
        'ProjectAudit'=>'PD',//转部
        'ProjectBuilding'=>'PE',// 楼盘
        'ProjectRelatedPerson'=>'PF', // 项目关系人
        'ProjectStructure'=>'PG',// 房屋结构
        'ProjectField'=>"PH", //项目附件
        'ProjectPhoto'=>'PJ',//项目图片
        'ProjectContract'=>'PL',
        'ProjectContractAudit'=>'PN',
        'ProjectDepositAudit'=>'PM', //项目的定金单
        'ProjectCollectPrice'=>'PO',
        'ProjectCollectPlan'=>'PP',
        'ProjectCollectPricePay'=>'PQ',
        'ProjectCollectPhoto'=>'PR',
        'ProjectSuccessTime'=>'PS',



        'DesignBudget'=>'DA',// 预算设计
        'DesignBudgetAccess'=>'DB',
        'DesignBudgetListAccess'=>'DC',
        'DesignPhase'=>'DD',
        'DefaultRate'=>'DE',
        'DefaultRateCopy'=>'DF',
        'DefaultBookCopy'=>'DG',
        'PrintRequest'=>'DH',
        'ReviseBudget'=>'DJ',
        'DesignCheck'=>'DK',
        'DesignField'=>'DL',
        'DesignPhoto'=>'DM',
        'DesignApplyTurning'=>'DO',


        'BudgetListEdit'=>'BA',
        'BudgetDefaultBook'=>'BB',
        'BudgetDefaultRate'=>'BC',
        'BudgetDefaultProject'=>'BD',
        'BudgetDefaultMaterial'=>'BE',

        'BuildProjectTime'=>'BF',
        'BuildProjectCategoryTime'=>'BG',
        'BuildContractRint'=>'BH',
        'BuildSupervision'=>'BI',
        'BuildSupervisionTem'=>'Bj',

        'SpaceTemplateUser'=>'SA',
        'BudgetTem'=>'SB',
        'BudgetBookNumber'=>'SC',

        'Bank'=>'FA',
        'BankLog'=>'FB',
        'PaymentStyle'=>'FC',
        'CollectStyle'=>'CS',
        'CollectPlan'=>'CA',
        'CollectPlanAccess'=>'CB',
        'ContractTem'=>'CT',

        'EnginProjectMaterial'=>'EA',
        'EnginProjectMaterialCopy'=>'EB',
        'EnginProjectMaterialAddress'=>'EC',
        'EnginProjectMaterialApply'=>'ED',
        'EnginProjectBuildDate'=>'EE',
        'EnginProjectBuildType'=>'EF',
        'EnginProjectBuildTypeRemind'=>'EG',
        'EnginProjectBuildTypeUser'=>'EH',
        'EnginProjectBuildTypeDay'=>'EI',
        'EnginProjectBuildTypeAudit'=>'EJ',

        'WorkTarget'=>'WC',
        'WasteApply'=>'WA',
        'WasteProjectApply'=>'WB',


    ];

    //默认密碼数据加密KEY
    static private $data_auth_key = 'ZyfErpPowerByMikkle';

    static public function getMd5Password($password)
    {
        return md5(md5($password) . self::$data_auth_key);
    }




}