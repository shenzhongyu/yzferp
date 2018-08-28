<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/5/16
 * Time: 16:51
 */

namespace app\erp\config;


class ListUrl
{
    public $company_json=[
        'model_name'=>'base/PersonnelCompany',
        'field_id'=>'guid',
        'field_text'=>'company_name',
    ];

    public $department_json=[
        'model_name'=>'base/PersonnelDepartment',
        'field_id'=>'guid',
        'field_text'=>'department_name',
    ];

    public $departmenttype_json=[
        'model_name'=>'base/PersonnelDepartment',
        'field_id'=>'guid',
        'field_text'=>'department_type',
    ];

    public $jobs_json=[
        'model_name'=>'base/PersonnelJobs',
        'field_id'=>'guid',
        'field_text'=>'jobs_name',
    ];
    public $role_json=[
        'model_name'=>'base/PersonnelRole',
        'field_id'=>'guid',
        'field_text'=>'role_name',
    ];
    public $pid_json=[
        'model_name'=>'base/PersonnelNode',
        'field_id'=>'guid',
        'field_text'=>'node_name',
    ];
    public $appid_json=[
        'model_name'=>'base/We',
        'field_id'=>'id',
        'field_text'=>'title',
    ];
    public $user_name_json=[
        'model_name'=>'base/PersonnelUser',
        'field_id'=>'uuid',
        'field_text'=>'name',
    ];
    public $menu_json=[
        'model_name'=>'base/WeMenu',
        'field_id'=>'guid',
        'field_text'=>'title',
    ];
    public $button_json=[
        'model_name'=>'base/WeMenuButton',
        'field_id'=>'guid',
        'field_text'=>'name',
    ];
    public $category_json=[
        'model_name'=>'base/MaterialCategory',
        'field_id'=>'guid',
        'field_text'=>'category_name',
    ];
    public $supplier_json=[
        'model_name'=>'base/MaterialSupplier',
        'field_id'=>'guid',
        'field_text'=>'supplier_name',
    ];
    public $data_name=[
        'model_name'=>'base/MaterialDataStyle',
        'field_id'=>'guid',
        'field_text'=>'categories_name',
    ];
    public $base_name=[
        'model_name'=>'base/MaterialBaseStyle',
        'field_id'=>'guid',
        'field_text'=>'base_name',
    ];
    public $template_id=[
        'model_name'=>'base/MaterialTemplateStyle',
        'field_id'=>'guid',
        'field_text'=>'template_name',
    ];
    public $paymentStyle=[
        'model_name'=>'base/finance/PaymentStyle',
        'field_id'=>'guid',
        'field_text'=>'name',
    ];
    public $paymentUser=[
        'model_name'=>'base/PersonnelUser',
        'field_id'=>'uuid',
        'field_text'=>'name',
    ];

}