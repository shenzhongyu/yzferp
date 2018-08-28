<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/4/26
 * Time: 15:20
 */

namespace  app\base\validate;


use think\Validate;

class ProjectBuildPlan extends Validate
{

    protected $rule = [
        ['book_number','require|number', '合同编号不能为空'],
        ['build_time','require', '施工开始日期不能为空'],
        ['build_days','require|number', '施工总天数不能为空'],

        ['day_name','require', '施工内容不能为空'],
        ['day_build_id','require', '施工内容不能为空'],
        ['day_user','require', '施工人员'],
        ['day_build_time','require', '施工时间不能为空'],
        ['day_time_id','require', '施工时间不能为空'],

    ];
    protected $scene = [
        'add'=>[
            'book_number','build_time','build_days'
        ],
        'addBuild'=>[
            'build_id','build_time','build_days'
        ],
        'addDay'=>[
            'day_name','day_build_id','day_user','day_build_time','day_time_id'
        ],

    ];



}