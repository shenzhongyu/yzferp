<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/4/17
 * Time: 15:51
 */

namespace app\base\model\project;


use app\base\model\Base;
use app\base\model\BaseHash;
use app\base\model\Project;
use think\Db;
use think\Exception;
use think\Loader;

class ProjectSuccessTime extends BaseHash   //记录各个部门的项目转入下一阶段的时间
{

    protected $table = "mk_project_success_time";
    protected $insert = ['status'=>1,'guid'];
    protected $autoWriteTimestamp = true;
    protected $hashKey="guid";
    protected function setGuidAttr($value, $data)
    {
        do {
            $mk_guid= $this->create_uuid();
        } while ($this->where('guid',$mk_guid)->count()==1);
        return $mk_guid ;
    }

    public function getList($map = [],$field=false){
        if (empty($map)){
            $map = ['status'=>1];
        }else{
            $map['status']=1;
        }
        if (empty($field)){
            $field = '';
        }
        return  $this->where($map)->field($field)->select();
    }

    public function saveTime($project_id,$type){
        $arr=[
            'project_id'=>$project_id,
            'type'=>$type?$type:"1",
            'uuid'=>$this->uuid,
        ];
        return $this->data($arr)->isUpdate(false)->save();
    }
}