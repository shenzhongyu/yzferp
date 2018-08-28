<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/4/25
 * Time: 10:21
 */

namespace app\base\model;


use think\Exception;
use think\Session;

class OfficePlan extends Base
{
    protected $table = "mk_office_plan";
    protected $insert = ['status'=>1,'guid'];
    protected $autoWriteTimestamp = true;

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
        }
        if (empty($field)){
            $field = '';
        }
        return $this->where($map)->field($field)->select();

    }

    public function saveInfoByGuidWithSaveLog($data)
    {
        try{
            $this->startTrans();
            $re =  $this->isUpdate(true)->allowField(true)->save($data,["guid" => $data["guid"]]);
            if ($re!=1 ) throw new \Exception("修改状态失败");
            $log=[];
            $log["log_content"]='工作进度更改';
            $log["log_time"]=time();
            $log["log_desc"]=$data["work_schedule"];
            $log["uuid"]=Session::get('uuid', 'Global');
            $log["plan_id"]=$data["guid"];
            $this->table('mk_office_plan_log')->insert($log);
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            //dump($e);
            $this->rollback();
            return self::showReturnCodeWithOutData(1020,$e->getMessage());
        }


    }


}