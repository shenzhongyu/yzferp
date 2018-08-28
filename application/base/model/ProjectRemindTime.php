<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/22
 * Time: 14:27
 */

namespace app\base\model;


use think\Db;

class ProjectRemindTime extends Base
{
    protected $table = "mk_project_remind_time";
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
        }else{
            $map['status'] = 1 ;
        }
        if (empty($field)){
            $field = '';
        }
        return $this->where($map)->field($field)->select();
    }

    public function setNameAttr($data)
    {
        $value=strtotime($data);
        return $value;
    }

    public function showProjectRemind($limit="1",$rows="20"){
            $list=Db::table("mk_project_remind_time")
                ->where('uuid',$this->uuid)
                ->where('status','1')
                ->whereTime('remind_time', 'd')
                ->field('remind_time,remind_content,project_guid,guid')
                ->limit($rows)
                ->page($limit)
                ->select();
            $list=$this->getTimeHours($list,'remind_time',false);
        $tatol=Db::table("mk_project_remind_time")
            ->where('uuid',$this->uuid)
            ->where('status','1')
            ->whereTime('remind_time', 'd')
            ->field('remind_time,remind_content,project_guid,guid')
            ->count();
        $model=new Project();
            if (!empty($list)){
                foreach ($list as $key=>$value){
                    $pro=$model->getInfoByGuId($value['project_guid']);
//                    $pro=Project::quickGet($value['project_guid']);
                    $list[$key]['project_name']=$pro?$pro['project_name']:'';
                }
            }
        return ['total'=>$tatol,'rows'=>$list];
    }
    public function showProjectRemindAll($limit="1",$rows="20"){
        $list=Db::table("mk_project_remind_time")
            ->where('uuid',$this->uuid)
            ->where('status','2')
            ->field('remind_time,remind_content,project_guid')
            ->limit($rows)
            ->page($limit)
            ->field('project_guid,remind_time,remind_content')
            ->select();
        $total=Db::table("mk_project_remind_time")
            ->where('uuid',$this->uuid)
            ->where('status','2')
            ->field('remind_time,remind_content,project_guid')
            ->field('project_guid,remind_time,remind_content')
            ->count();
        $list=$this->getTimeHours($list,'remind_time',false);
        $model=new Project();
        if (!empty($list)){
            foreach ($list as $key=>$value){
                $pro=$model->getInfoByGuId($value['project_guid']);
//                $pro=Project::quickGet($value['project_guid']);
                $list[$key]['project_name']=$pro?$pro['project_name']:'';
            }
        }

        return ['total'=>$total,'rows'=>$list];
    }

    public function editStatus($data){
        $re=$this->data(['status'=>2])->isUpdate(true,['guid'=>$data])->save();
        if ($re==1){
            return self::showReturnCodeWithOutData(1001);
        }else{
            return self::showReturnCodeWithOutData(1003);
        }
    }

}