<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/4/17
 * Time: 15:51
 */

namespace app\base\model\engin;


use app\base\model\Base;
use app\base\model\BaseHash;
use app\base\model\finance\BankTransactionRecord;
use app\base\model\finance\CollectPlanAccess;
use app\base\model\Material;
use app\base\model\Project;
use app\base\model\project\ProjectContract;
use think\Db;
use think\Exception;
use think\Loader;

class EnginProjectBuildType extends BaseHash
{
    protected $table = "mk_engin_project_build_type";
    protected $insert = ['status'=>1,'guid'];
    protected $autoWriteTimestamp = true;
    protected $hashKey ="guid";
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
    public function editNumber($data,$guid){
        if (empty($guid)){
            return self::showReturnCodeWithOutData(1003);
        }
        if (empty($data)){
            return self::showReturnCodeWithOutData(1003);
        }
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
    public function addBuildType($data){
        if (empty($data)){
            return self::showReturnCodeWithOutData(1003);
        }
        $model=new EnginProjectBuildTypeDay();
        $data['type']=0;
        try{
            $this->startTrans();
            $this->data($data)->isUpdate(false)->save();
            $guid=$this->guid;
            for ($i=1;$i<=$data['build_days'];$i++){
                $arr=[
                    'day_name'=>'第'.$i.'天',
                    'day_time'=>$data['build_time']+($i-1)*60*60*24,
                    'build_type_id'=>$guid,

                ];
                $model->data($arr)->isUpdate(false)->save();
            }
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }
    public function delBuildPlan($data){
        if (empty($data)) return self::showReturnCode(1003);
        if (!isset($data['id'])) return self::showReturnCode(1003);
        try{
            $this->startTrans();

            $this->data(['status'=>-1])->isUpdate(true,['guid'=>$data['id']])->save();
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }
}