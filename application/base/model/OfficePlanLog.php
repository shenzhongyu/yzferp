<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/4/25
 * Time: 10:21
 */

namespace app\base\model;


use think\Session;

class OfficePlanLog extends Base
{
    protected $table = "mk_office_plan_log";

    protected $autoWriteTimestamp = false;


    public function getList($map = [],$field=false){
        if (empty($map)){
            $map = ['status'=>1];
        }
        if (empty($field)){
            $field = '';
        }
        return $this->where($map)->field($field)->select();

    }

    public function addOfficePlanLog($data){
        $log=[];
        $log["log_content"]=$data["title"];
        $log["log_time"]=time();
        $log["log_desc"]=$data["content"];
        if (!isset($data['uuid'])){
            $log["uuid"]=Session::get('uuid', 'Global');
        }else{
            $log["uuid"]=$data["uuid"];
        }
        $log["plan_id"]=$data["guid"];
        $this->save($log);
    }




}