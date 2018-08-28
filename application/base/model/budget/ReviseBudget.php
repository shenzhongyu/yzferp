<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/4/17
 * Time: 15:51
 */

namespace app\base\model\budget;


use app\base\model\Base;
use think\Db;
use think\Exception;
use think\Loader;

class ReviseBudget extends Base
{

    protected $table = "mk_budget_revise";
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
        }else{
            $map['status'] = 1 ;
        }
        if (empty($field)){
            $field = '';
        }
        return $this->where($map)->field($field)->select();
    }
    public function getListNoStatus($map = [],$field=false){
        if (empty($map)){
            $map = ['status'=>1];
        }
        if (empty($field)){
            $field = '';
        }
        return $this->where($map)->field($field)->select();
    }
    public function getFind($map = [],$field=false){
        if (empty($map)){
            $map = ['status'=>1];
        }else{
            $map['status'] = 1 ;
        }
        if (empty($field)){
            $field = '';
        }
        return $this->where($map)->field($field)->find();
    }
    public function savaData($data,$pro_id,$uuid,$request_uuid){
        $pro_name=$this->setCacheProjectData()['project'];
        $arr=[
            'project_id'=>$pro_id,
            'uuid'=>$uuid,
            'uuid_name'=>$request_uuid,
            'request_desc'=>$data['desc'],
            'examine_status'=>0,
            'company_id'=>$data['company_id'],
            'project_name'=>isset($pro_name[$pro_id])? $pro_name[$pro_id]['project_name'] :""
        ];
        //申请预算修改就取消预算打印申请
        $model=new PrintRequest();
        $model->data(['status'=>-1])->isUpdate(true,['project_id'=>$pro_id,'examine_status'=>0])->save();

        try{
            $this->startTrans();

            $this->data(['status'=>-1])->isUpdate(true,['project_id'=>$pro_id])->save();
            $this->data($arr)->isUpdate(false)->save();
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }
    public function editSaveData($data,$id){
        try{
            $this->startTrans();
            $this->data($data)->isUpdate(true,['guid'=>$id])->save();
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }

}