<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/5/16
 * Time: 15:49
 */

namespace app\erp\config;


class Config
{
    public static $system=[
        //nodeList
        'nodelist' => [
            'action_name' => 'showEasyUiList',
            'model_name' => "base/PersonnelNode",
            'validate_name' => '',
            'template_name' => 'node_list',
            'map'=>["is_mobile"=>0],
            'assign_data' => [
                'title' => '菜单管理列表',
                'dialog_url' => 'nodeEdit',
                'data_url' => '',
            ],

        ],
        //
        'nodeedit' => [
            'action_name' => 'showEasyUiEdit',
            'model_name' => "base/PersonnelNode",
            'validate_name' => 'base/PersonnelNode.edit',
            'template_name' => 'node_edit_mini',
        ],

        'nodemobilelist' => [
            'action_name' => 'showEasyUiList',
            'model_name' => "base/PersonnelNode",
            'validate_name' => '',
            'template_name' => 'node_mobile_list',
            'map'=>["is_mobile"=>1],
            'assign_data' => [
                'title' => '手机菜单管理列表',
                'dialog_url' => 'nodemobileedit',
                'data_url' => '',
            ],

        ],
        //
        'nodemobileedit' => [
            'action_name' => 'showEasyUiEdit',
            'model_name' => "base/PersonnelNode",
            'validate_name' => 'base/PersonnelNode.edit',
            'template_name' => 'node_edit_mobile',
        ],



        //companyList
        'companylist' => [
            'action_name' => 'showEasyUiList',
            'model_name' => "base/PersonnelCompany",
            'validate_name' => '',
            'template_name' => 'company_list',
//            'parameter_name'=>'company_id',
//            'map'=>['company_id' => ''],
            'assign_data' => [
                'title' => '公司管理列表',
                'dialog_url' => 'companyEdit',
                'data_url' => '',
            ],
        ],
        'companyedit' => [
            'action_name' => 'showEasyUiEdit',
            'model_name' => "base/PersonnelCompany",
            'validate_name' => 'base/PersonnelCompany.edit',
            'template_name' => 'company_edit',
        ],
        //departmentList
        'departmentlist' => [
            'action_name' => 'showEasyUiList',
            'model_name' => "base/PersonnelDepartment",
            'validate_name' => '',
            'template_name' => 'department_list',
            'assign_data' => [
                'title' => '部门管理列表',
                'dialog_url' => 'departmentEdit',
                'data_url' => '',
            ],
        ],
        'departmentedit' => [
            'action_name' => 'showEasyUiEdit',
            'model_name' => "base/PersonnelDepartment",
            'validate_name' => 'base/PersonnelDepartment.edit',
            'template_name' => 'department_edit',
        ],
        //jobsList
        'jobslist' => [
            'action_name' => 'showEasyUiList',
            'model_name' => "base/PersonnelJobs",
            'validate_name' => '',
            'template_name' => 'jobs_list',
            'assign_data' => [
                'title' => '职位管理列表',
                'dialog_url' => 'jobsEdit',
                'data_url' => '',
            ],
        ],
        'jobsedit' => [
            'action_name' => 'showEasyUiEdit',
            'model_name' => "base/PersonnelJobs",
            'validate_name' => 'base/PersonnelJobs.edit',
            'template_name' => 'jobs_edit',
        ],
        //roleList
        'rolelist' => [
            'action_name' => 'showEasyUiList',
            'model_name' => "base/PersonnelRole",
            'validate_name' => '',
            'template_name' => 'role_list',
            'assign_data' => [
                'title' => '角色管理列表',
                'dialog_url' => 'roleEdit',
                'data_url' => '',
            ],
        ],
        'roleedit' => [
            'action_name' => 'showEasyUiEdit',
            'model_name' => "base/PersonnelRole",
            'validate_name' => 'base/PersonnelRole.edit',
            'template_name' => 'role_edit',
        ],
        //userList (管理员添加的用户表)
        'userlist' => [
            'action_name' => 'showEasyUiList',
            'model_name' => "base/PersonnelUser",
            'validate_name' => '',
            'template_name' => 'user_list',
            'assign_data' => [
                'title' => '用户列表',
                'dialog_url' => 'userEdit',
                'data_url' => '',
            ],
        ],
        'useredit' => [
            'action_name' => 'showEasyUiEdit',
            'model_name' => "base/PersonnelUser",
            'validate_name' => 'base/PersonnelUser.edit',
            'template_name' => 'user_edit',
        ],
        //fensList
        'fanslist' => [
            'action_name' => 'showEasyUiList',
            'model_name' => "base/WeFans",
            'validate_name' => '',
            'template_name' => 'fans_list',
            'assign_data' => [
                'title' => '粉丝信息列表',
                'dialog_url' => '',
                'data_url' => '',
            ],
        ],
        //messageList
        'messagelist' => [
            'action_name' => 'showEasyUiList',
            'model_name' => "base/PersonnelLogRecord",
            'validate_name' => '',
            'template_name' => 'message_list',
            'assign_data' => [
                'title' => '用户登录信息列表',
                'dialog_url' => '',
                'data_url' => '',
            ],

        ],
        //projectList(所有)
        'projectsumlist' => [
            'action_name' => 'showEasyUiList',
            'model_name' => "base/Project",
            'validate_name' => '',
            'template_name' => 'projectsuo_list',
            'assign_data' => [
                'title' => '项目信息公司表',
                'dialog_url' => 'projectEdit',
                'data_url' => '',
            ],
        ],
        //projectList(公司)
//        'projectlist' => [
//            'action_name' => 'showEasyUiParameterList',
//            'model_name' => "base/Project",
//            'validate_name' => '',
//            'template_name' => 'project_list',
//            'parameter_name'=>'company_id',
//            'map'=>['company_id' => '','feedback_stage'=>'1'],
//            'assign_data' => [
//                'title' => '项目信息总表',
//                'dialog_url' => '',
//                'data_url' => '',
//            ],
//        ],
        //projectdepartmentList
//        'prodeparlist' => [
//            'action_name' => 'showEasyUiParameterList',
//            'model_name' => "base/Project",
//            'validate_name' => '',
//            'template_name' => 'prodepar_list',
//            'parameter_name'=>'department_id',
//            'map'=>['department_id' => '','feedback_stage'=>'1'],
//            'assign_data' => [
//                'title' => '项目信息部门表',
//                'dialog_url' => 'projectEdit',
//                'data_url' => '',
//            ],
//        ],
        //projectuserList
//        'prouserlist' => [
//            'action_name' => 'showEasyUiParameterList',
//            'model_name' => "base/Project",
//            'validate_name' => '',
//            'template_name' => 'project/prouser_list',
//            'parameter_name'=>'uuid',
//            'map'=>['uuid' => '','feedback_stage'=>'1'],
//            'assign_data' => [
//                'title' => '项目个人表',
//                'dialog_url' => 'projectEdit',
//                'data_url' => '',
//            ],
//        ],
        'projectedit' => [
            'action_name' => 'showEasyUiEdit',
            'model_name' => "base/Project",
            'validate_name' => 'base/Project.edit',
            'template_name' => 'project_edit',

        ],
        //trackStatus_list
        'trackstatuslist' => [
            'action_name' => 'showEasyUiList',
            'model_name' => "base/PersonnelTrackStatus",
            'validate_name' => '',
            'template_name' => 'trackstatus_list',
            'assign_data' => [

                'title' => '跟进状态列表',

                'dialog_url' => 'trackstatusedit',

                'data_url' => '',

            ],
        ],
        'trackstatusedit' => [

            'action_name' => 'showEasyUiEdit',

            'model_name' => "base/PersonnelTrackStatus",

            'validate_name' => 'base/PersonnelTrackStatus.edit',

            'template_name' => 'trackstatus_edit',

        ],

        //projectloglist(项目日志)
        'projectloglist' => [
            'action_name' => 'showEasyUiListLog',
            'model_name' => "base/ProjectTraceLog",
            'validate_name' => '',
            'template_name' => 'projectlog_list',
            'assign_data' => [
                'title' => '项目日志表',
                'dialog_url' => 'projectlogedit',
                'data_url' => '',
            ],
        ],
        //projectloglist(项目提醒)
        'projecttimelist' => [
            'action_name' => 'showEasyUiListLog',
            'model_name' => "base/ProjectRemindTime",
            'validate_name' => '',
            'template_name' => 'projecttime_list',
            'assign_data' => [
                'title' => '项目提醒时间表',
                'dialog_url' => '',
                'data_url' => '',
            ],
        ],
        'setuserrolelist' => [
            'action_name' => 'showEasyUiList',
            'model_name' => "base/PersonnelUser",
            'validate_name' => '',
            'template_name' => 'setuserrole_list',
            'map'=>["status"=>"0"],
            'assign_data' => [
                'title' => '权限分配',
                'dialog_url' => 'setuserroledit',
                'data_url' => '',
            ],
        ],
        'setuserroledit' => [
            'action_name' => 'showEasyUiEdit',
            'model_name' => "base/PersonnelUser",
            'validate_name' => '',
            'template_name' => 'setuserrole_edit',
        ],
        //leave_list  请假列表
        'leavelist' => [
            'action_name' => 'showEasyUiParameterList',
            'model_name' => "base/OfficeLeave",
            'validate_name' => '',
            'template_name' => 'leave_list',
            'parameter_name'=>'company_id',
            'map'=>["company_id"=>''],
            'assign_data' => [
                'title' => '假条列表',
                'dialog_url' => 'leaveedit',
                'data_url' => '',
            ],
        ],
        'leaveedit' => [
            'action_name' => 'showEasyUiEdit',
            'model_name' => "base/OfficeLeave",
            'validate_name' => 'base/OfficeLeave.edit',
            'template_name' => 'leave_edit',
        ],
        //leave_not_list  审核假条列表
        'leavenotlist' => [
            'action_name' => 'showEasyUiParameterList',
            'model_name' => "base/OfficeLeave",
            'validate_name' => '',
            'template_name' => 'leave_not_list',
            'parameter_name'=>'company_id',
            'map'=>["dep_manager"=>"0","company_id"=>''],
            'assign_data' => [
                'title' => '审核假条列表',
                'dialog_url' => 'leavenotedit',
                'data_url' => '',
            ],
        ],

        'leavenotedit' => [
            'action_name' => 'showEasyUiEdit',
            'model_name' => "base/OfficeLeave",
            'validate_name' => 'base/OfficeLeave.notedit',
            'template_name' => 'leave_not_edit',
        ],

        //wemenulist  微信菜单
        'wemenulist' => [
            'action_name' => 'showEasyUiList',
            'model_name' => "base/WeMenu",
            'validate_name' => '',
            'template_name' => 'we_menu_list',
            'assign_data' => [
                'title' => '微信菜单',
                'dialog_url' => 'wemenuedit',
                'data_url' => '',
            ],
        ],
        'wemenuedit' => [
            'action_name' => 'showEasyUiEdit',
            'model_name' => "base/WeMenu",
            'validate_name' => 'base/WeMenu.edit',
            'template_name' => 'we_menu_edit',
        ],
        //考勤记录
        'officesignlist' => [
            'action_name' => 'showEasyUiList',
            'model_name' => "base/OfficeSign",
            'validate_name' => '',
            'template_name' => 'office_sign_list',
            'assign_data' => [
                'title' => '考勤记录',
                'dialog_url' => '',
                'data_url' => '',
            ],
        ],
        //个人工作计划
        'workuserlist' => [
            'action_name' => 'showEasyUiParameterList',
            'model_name' => "base/OfficePlan",
            'validate_name' => '',
            'template_name' => 'workuser_list',
            'parameter_name'=>'uuid',
            'map'=>['uuid' => ''],
            'assign_data' => [
                'title' => '个人工作计划表',
                'dialog_url' => 'workuserEdit',
                'data_url' => '',
            ],
        ],
        'workuseredit' => [
            'action_name' => 'showEasyUiEdit',
            'model_name' => "base/OfficePlan",
            'validate_name' => 'base/OfficePlan.edit',
            'template_name' => '',
        ],
        //计划审核
        'workexaminelist'=> [
            'action_name' => 'showEasyUiList',
            'model_name' => "base/OfficePlan",
            'validate_name' => '',
            'template_name' => 'workexamine_list',
            'map'=>['dep_manager' => '0','company_id'=>''],
            'assign_data' => [
                'title' => '计划审核表',
                'dialog_url' => '',
                'data_url' => '',
            ],
        ],
//        'workexamineedit' => [
//            'action_name' => 'showEasyUiEdit',
//            'model_name' => "base/OfficePlan",
//            'validate_name' => 'base/OfficePlan.notedit',
//            'template_name' => 'leave_not_edit',
//        ],

    ];

    public static $project=[
        'contactsedit' => [
            'action_name' => 'showEasyUiEdit',
            'model_name' => "base/ProjectContacts",
            'validate_name' => 'base/ProjectContacts.edit',
            'template_name' => 'contacts_edit',
        ],
        'tracelogedit' => [
            'action_name' => 'showEasyUiEdit',
            'model_name' => "base/ProjectTraceLog",
            'validate_name' => 'base/ProjectTraceLog.edit',
            'template_name' => 'tracelog_edit',
        ],
        'structureedit' => [
            'action_name' => 'showEasyUiEdit',
            'model_name' => "base/ProjectStructure",
            'validate_name' => 'base/ProjectStructure.edit',
            'template_name' => 'structure_edit',
        ],
        'buildingedit' => [
            'action_name' => 'showEasyUiEdit',
            'model_name' => "base/ProjectBuilding",
            'validate_name' => 'base/ProjectBuilding.edit',
            'template_name' => 'building_edit',
        ],
    ];

    public static $Material=[
        'showsupplierlist' => [
            'action_name' => 'showEasyUiParameterList',
            'model_name' => "base/MaterialSupplier",
            'validate_name' => '',
            'template_name' => 'supplierList',
            'parameter_name'=>'company_id',
            'map'=>["company_id"=>''],
            'assign_data' => [
                'title' => '供应商列表',
                'dialog_url' => 'showsupplieredit',
                'data_url' => '',
            ],

        ],
        'showsupplieredit' => [
            'action_name' => 'showEasyUiEdit',
            'model_name' => "base/MaterialSupplier",
            'validate_name' => 'base/MaterialSupplier.add',
            'template_name' => 'supplier_edit',
        ],
        //material_list
//        'materiallist' => [
//            'action_name' => 'showEasyUiList',
//            'model_name' => "base/Material",
//            'validate_name' => '',
//            'template_name' => 'material_list',
//            'assign_data' => [
//                'title' => '材料列表',
//                'dialog_url' => 'materialedit',
//                'data_url' => '',
//            ],
//        ],
//        'materialedit' => [
//            'action_name' => 'showEasyUiEdit',
//            'model_name' => "base/Material",
//            'validate_name' => 'base/Material.edit',
//            'template_name' => 'material_edit',
//        ],
        'showbasestylelist' => [
            'action_name' => 'showEasyUiParameterList',
            'model_name' => "base/MaterialBaseStyle",
            'validate_name' => '',
            'template_name' => 'materialBaseStyle_list',
            'parameter_name'=>'company_id',
            'map'=>["company_id"=>''],
            'assign_data' => [
                'title' => '定额类型列表',
                'dialog_url' => 'showbasestyleedit',
                'data_url' => '',
            ],

        ],
        'showbasestyleedit' => [
            'action_name' => 'showEasyUiEditByCom',
            'model_name' => "base/MaterialBaseStyle",
            'validate_name' => 'base/Material.basestyle',
            'template_name' => 'materialBaseStyle_edit',
        ],
        'showtemplatestylelist' => [
            'action_name' => 'showEasyUiParameterList',
            'model_name' => "base/MaterialTemplateStyle",
            'validate_name' => '',
            'template_name' => 'materialTemplateStyle_list',
            'parameter_name'=>'company_id',
            'map'=>["company_id"=>''],
            'assign_data' => [
                'title' => '预算模板类型列表',
                'dialog_url' => 'showtemplatestyleedit',
                'data_url' => '',
            ],

        ],
        'showtemplatestyleedit' => [
            'action_name' => 'showEasyUiEditByCom',
            'model_name' => "base/MaterialTemplateStyle",
            'validate_name' => 'base/Material.templatestyle',
            'template_name' => 'materialTemplateStyle_edit',
        ],
        'showdatastylelist' => [   //家装定额列表
            'action_name' => 'showEasyUiParameterList',
            'model_name' => "base/MaterialDataStyle",
            'validate_name' => '',
            'template_name' => 'material_data_style_list',
            'parameter_name'=>'company_id',
            'map'=>["company_id"=>''],
            'assign_data' => [
                'title' => '数据类别列表',
                'dialog_url' => 'showdatastyleedit',
                'data_url' => '',
            ],
        ],
        'showdatastyleclotheslist' => [ //工装定额列表
            'action_name' => 'showEasyUiParameterList',
            'model_name' => "base/MaterialDataStyle",
            'validate_name' => '',
            'template_name' => 'material_data_style_clothes_list',
            'parameter_name'=>'company_id',
            'map'=>["company_id"=>''],
            'assign_data' => [
                'title' => '数据类别列表',
                'dialog_url' => 'showdatastyleedit',
                'data_url' => '',
            ],

        ],
//        'showdatastylepackagelist' => [ //套餐定额列表
//            'action_name' => 'showEasyUiParameterList',
//            'model_name' => "base/material/MaterialPackageCategory",
//            'validate_name' => '',
//            'template_name' => 'material_data_style_package_list',
//            'parameter_name'=>'company_id',
//            'map'=>["company_id"=>''],
//            'assign_data' => [
//                'title' => '类别列表',
//                'dialog_url' => 'showpackagestyleedit',
//                'data_url' => '',
//            ],
//
//        ],
//        'showpackagestyleedit' => [
//            'action_name' => 'showEasyUiEditByCom',
//            'model_name' => "base/material/MaterialPackageCategory",
//            'validate_name' => 'base/Material.packagecategorystyle',
//            'template_name' => 'material_package_style_edit',
//        ],
        'showdatastyleedit' => [
            'action_name' => 'showEasyUiEditByCom',
            'model_name' => "base/MaterialDataStyle",
            'validate_name' => 'base/Material.datastyle',
            'template_name' => 'material_data_style_edit',
        ],

        'showdatatypepackagelist' => [ //套餐定额列表
            'action_name' => 'showEasyUiParameterList',
            'model_name' => "base/material/MaterialPackageType",
            'validate_name' => '',
            'template_name' => 'material_access/material_package_type',
            'parameter_name'=>'company_id',
            'map'=>["company_id"=>''],
            'assign_data' => [
                'title' => '套餐种类',
                'dialog_url' => 'showpackagetypeedit',
                'data_url' => '',
            ],

        ],
        'showpackagetypeedit' => [
            'action_name' => 'showEasyUiEditByCom',
            'model_name' => "base/material/MaterialPackageType",
            'validate_name' => 'base/Material.packagetype',
            'template_name' => 'material_access/material_package_type_edit',
        ],
    ];
}