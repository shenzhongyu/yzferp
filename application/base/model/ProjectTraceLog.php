<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/22
 * Time: 14:27
 */

namespace app\base\model;


use think\Session;

class ProjectTraceLog extends Base
{
    protected $table = "mk_project_trace_log";
    protected $insert = ['guid','status'=>1];
    protected $autoWriteTimestamp = true;

    protected function setGuidAttr($value, $data)
    {
        do {
            $mk_guid= $this->create_uuid();
        } while ($this->where('guid',$mk_guid)->count()==1);
        return $mk_guid ;
    }


//    protected function getProjectContactsAttr($value, $data)
//    {
//        return ($this->belongsTo('ProjectContacts')->where(['project_guid'=>$data['guid']])->find());
//
//
//    }




    public function getList($map = [],$field=false){
        if (empty($map)){
            $map = [];
        }
        if(!isset($map['status'])){
            $map['status'] = 1 ;
        }
        if (empty($field)){
            $field = '';
        }
        return $this->where($map)->field($field)->select();
    }

    public function addProjectLog($data){
        $log=[];
        $log["log_content"]=$data["log_content"];
        if (!isset($data['uuid'])){
            $log["uuid"]=Session::get('uuid', 'Global');
        }else{
            $log["uuid"]=$data["uuid"];
        }
        $log["project_guid"]=$data["guid"];
        $log["jobs_id"]=$data["jobs_id"];
        $log["department_id"]=$data["department_id"];
        $log["log_type"]=2;
        $this->save($log);
    }



}