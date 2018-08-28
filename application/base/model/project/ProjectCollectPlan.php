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
use app\base\model\finance\BankTransactionRecord;
use app\base\model\finance\CollectPlanAccess;
use app\base\model\Project;
use think\Db;
use think\Exception;
use think\Loader;

class ProjectCollectPlan extends Base
{

    protected $table = "mk_project_collect_plan";
    protected $insert = ['status'=>1,'guid'];
    protected $autoWriteTimestamp = false;
    protected function setGuidAttr($value, $data)
    {
        do {
            $mk_guid= $this->create_uuid();
        } while ($this->where('guid',$mk_guid)->count()==1);
        return $mk_guid ;
    }

    public function getList($map = [],$field=false){
        if (empty($map)){
            $map =['status'=>1];
        }else{
            $map['status']=1;
        }
        if (empty($field)){
            $field = '';
        }
        return  $this->where($map)->field($field)->select();
    }
    public function saveData($data){
        if (empty($data)){
            return self::showReturnCodeWithOutData(1003);
        }
        $model=new CollectPlanAccess();
        $list=$model->getList(['collect_plan_id']);
        if (!empty($list)){
            foreach ($list as $value){

            }
        }
        try{
            $this->startTrans();
            $this->data($data)->isUpdate(false)->save();
            $id=$this->id;
            $this->data(['number'=>date("Ymd",time()).'-'.$id])->isUpdate(true,['id'=>$id])->save();
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }
    public function delDate($guid){
        if (empty($guid)){
            return self::showReturnCodeWithOutData(1003);
        }
        try{
            $this->startTrans();
            $this->data(['status'=>-1])->isUpdate(true,['guid'=>$guid])->save();
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }
    public function editCollect($data,$guid){
        if (empty($guid)){
            return self::showReturnCodeWithOutData(1003);
        }
        if (empty($data)){
            return self::showReturnCodeWithOutData(1003);
        }
        //修改清楚所有审核信息
        $data['price']=$data['collect_price']; //剩余金额
        $data['examine_status']=0;
        $data['examine_uuid']='';
        $data['examine_uuid_name']='';
        $data['collect_status']=0;
        $data['examine_desc']='';
        $data['collect_desc']='';
        $data['collected_uuid']='';
        $data['collected_uuid_name']='';
        try{
            $this->startTrans();
            $this->data($data)->isUpdate(true,['guid'=>$guid])->save();
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }

}