<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/5/5
 * Time: 17:20
 */

namespace app\install\controller;

use app\base\controller\Curl;
use app\base\model\PersonnelCompany;
use app\base\model\PersonnelDepartment;
use app\base\model\PersonnelJobs;
use app\base\model\PersonnelRole;
use app\base\model\PersonnelUser;
use app\erp\config\FieldValue;
use app\erp\controller\BaseEasyUI;
use think\Config;
use think\Db;
use think\Exception;
use think\Loader;
use think\Log;
use think\Session;
use app\base\model\ModelCode;
class test extends BaseEasyUI
{
    //插入更新节点数据
    public function insetNodeTableDate(){
        try{
            $install_db = Db::connect($this->db_config);
            $sql_json=Config::get("sql_node_data");
            $install_db->table('mk_personnel_node')->insertAll($sql_json);
            $this->setSession('isInsetNodeData');
            return true;
        }catch (Exception $e){
            Log::error($e);
            return false;
        }
    }
}