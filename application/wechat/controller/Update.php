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


class Update extends Auth
{
    public function update(){
        if (!$this->uuid){
            return self::showReturnCodeWithOutData(1004);
        }
        return $this->fetch("update/update");
    }

}