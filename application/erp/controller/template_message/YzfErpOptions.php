<?php
/**
 * Created by PhpStorm.
 * Power By Mikkle
 * Email：776329498@qq.com
 * Date: 2017/9/7
 * Time: 14:16
 */

namespace app\erp\controller\template_message;


class YzfErpOptions
{
    static public $template =' {
           "touser":"{$ToUserName}",
           "template_id":"{$template_id}",
           "url":"{$url}",
           "miniprogram":{
             "appid":"",
             "pagepath":""
           },
           "data":{$data},
       }';

    /**
     * 工作内容审核通知
     * Power: Mikkle
     * Email：776329498@qq.com
     * @var string
     *
     * {{first.DATA}}
    工作名称：{{keyword1.DATA}}
    添加时间：{{keyword2.DATA}}
    {{remark.DATA}}
    在发送时，需要将内容中的参数（{{.DATA}}内为参数）赋值替换为需要的信息
    内容示例
    xxx的工作计划已提交，请审核
    工作名称：2014年5月工作计划
    添加时间：2014年5月1日 18:36
    点击查看具体内容
     *
     */
    static public $mission =' {
           "touser":"{$ToUserName}",
           "template_id":"4h0d0zn5pfUUb0UKHxf74IrRtouVjhWygrrV6_mEio0",
           "url":"{$url}",
           "miniprogram":{
             "appid":"",
             "pagepath":""
           },
           "data":{
                   "first": {
                       "value":"{$first}",
                       "color":"#f75b47"
                   },
                   "keyword1":{
                       "value":"{$keyword1}",
                       "color":"#173177"
                   },
                   "keyword2": {
                       "value":"{$keyword2}",
                       "color":"lightgray"
                   },
                   "remark":{
                       "value":"{$remark}",
                       "color":"#4cd964"
                   }
           }
       }';


    static public $options=[
        "moneyPending"=>[
            "title"=>"项目定金",
            "node"=>["ND59A7BD80578F5485355"],
            "table"=>"mk_project_deposit_price_audit",
            "project_id"=>"project_id",
            "uuid"=>"payment_uuid",
            "url"=>"wechat/Project/moneyPending"
        ],
        "business"=>[
            "title"=>"业务转设计",
            "node"=>["ND599E7CFC84488995652"],
            "table"=>"mk_project_audit",
            "project_id"=>"project_id",
            "uuid"=>"transfer_uuid",
            "url"=>"wechat/Project/projectPending"
        ],
        "budgetPrint"=>[
            "title"=>"预算打印",
            "node"=>["ND59ACEB84231F1525051"],
            "table"=>"mk_budget_print_request",
            "project_id"=>"project_id",
            "uuid"=>"uuid",
            "url"=>"wechat/Project/printList"
        ],
        "scheduleAcceptance"=>[
            "title"=>"进度验收",
            "node"=>["ND59A6597DA2644100975"],
            "table"=>"mk_design_sheet_check",
            "project_id"=>"project_id",
            "uuid"=>"apply_uuid",
            "url"=>"wechat/Project/designerPendingList"
        ],
        "contractSigning"=>[
            "title"=>"合同签订",
            "node"=>["ND599F9EEDF1AA5100102"],
            "table"=>"mk_project_contract_audit",
            "project_id"=>"project_id",
            "uuid"=>"transfer_uuid",
            "url"=>"wechat/Designer/contractask"
        ],
        "leaveAudit"=>[
            "title"=>"请假",
            "node"=>["ND59A5026534CCE535152"],
            "table"=>"mk_office_leave",
            "project_id"=>"",
            "uuid"=>"leave_name",
            "url"=>"wechat/Index/leaveList"
        ],
        "collectionAudit"=>[
            "title"=>"收款",
            "node"=>["ND59A8B244B026D529848"],
            "table"=>"mk_project_collect_price",
            "project_id"=>"project_id",
            "uuid"=>"collect_uuid",
            "url"=>"wechat/Project/moneyTransfer"
        ],
        "budgetRevise"=>[
            "title"=>"预算修改",
            "node"=>["ND59ACC5038865D515656"],
            "table"=>"mk_budget_revise",
            "project_id"=>"project_id",
            "uuid"=>"uuid",
            "url"=>"wechat/Project/budgetList"
        ],
        "projectMaterial"=>[
            "title"=>"项目主材",
            "node"=>["ND59B0DEE15632C495354"],
            "table"=>"mk_engin_project_material_apply_audit",
            "project_id"=>"project_id",
            "uuid"=>"apply_id",
            "url"=>"wechat/Project/materialList"
        ],
        "materialBuy"=>[
            "title"=>"材料采购",
            "node"=>["ND59B23810E4D4E481015"],
            "table"=>"mk_engin_project_material_address",
            "project_id"=>"project_id",
            "uuid"=>"apply_uuid",
            "url"=>"wechat/Project/materialBuyList"
        ],
        "collectionPay"=>[
            "title"=>"付款",
            "node"=>["ND59B1FDCF263CE102505"],
            "table"=>"mk_project_collect_price_pay",
            "project_id"=>"project_id",
            "uuid"=>"collect_uuid",
            "url"=>"wechat/Project/moneyTransferPay"
        ],
        "materialCheck"=>[
            "title"=>"材料验收",
            "node"=>["ND59B3421707FFA554856"],
            "table"=>"mk_engin_project_material_address",
            "project_id"=>"project_id",
            "uuid"=>"apply_uuid",
            "url"=>"wechat/Project/materialChecklist"
        ],
        "projectStageCheck"=>[
            "title"=>"项目阶段完工",
            "node"=>["ND59BB9E3B37F8D985155"],
            "table"=>"mk_engin_project_build_type_audit",
            "project_id"=>"project_id",
            "uuid"=>"apply_uuid",
            "url"=>"wechat/Project/projectStepPendingInclude"
        ],
        "projectCheck"=>[
            "title"=>"项目完工",
            "node"=>["ND59BF355D05843100485"],
            "table"=>"mk_engin_project_completion_audit",
            "project_id"=>"project_id",
            "uuid"=>"apply_uuid",
            "url"=>"wechat/Project/projectWorkedPendingList"
        ],
//        "projectCheck"=>[
//            "title"=>"项目完工",
//            "node"=>["ND59BF355D05843100485"],
//            "table"=>"mk_engin_project_completion_audit",
//            "project_id"=>"project_id",
//            "uuid"=>"apply_uuid",
//            "url"=>"wechat/Project/projectWorkedPendingList"
//        ],
        "carCheck"=>[
            "title"=>"用车申请",
            "node"=>["ND5A14D8154D091535210"],
            "table"=>"mk_office_vehicle_apply",
            "project_id"=>"",
            "uuid"=>"uuid",
            "url"=>"wechat/human/carPending"
        ],
        "revokeCheck"=>[
            "title"=>"撤销收款",
            "node"=>["ND5A1BACAE6EC45101541"],
            "table"=>"mk_revoke_money",
            "project_id"=>"",
            "uuid"=>"uuid",
            "url"=>"wechat/budget/revokeList"
        ],
        "payCheck"=>[
            "title"=>"付款审核",
            "node"=>["ND5A1CBE609D432485710"],
            "table"=>"mk_project_collect_price_pay",
            "project_id"=>"",
            "uuid"=>"uuid",
            "url"=>"wechat/budget/payList"
        ],
        "wasteOutCheck"=>[
            "title"=>"废单转出",
            "node"=>["ND5A24A87579A89535557"],
            "table"=>"mk_waste_project_apply",
            "project_id"=>"project_id",
            "uuid"=>"uuid",
            "url"=>"wechat/budget/payList"
        ],
    ];

}