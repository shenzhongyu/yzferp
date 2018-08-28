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
use app\base\model\finance\BankTransactionRecord;
use app\base\model\finance\CollectPlanAccess;
use app\base\model\Material;
use app\base\model\Project;
use app\base\model\project\ProjectContract;
use think\Db;
use think\Exception;
use think\Loader;

class EnginProjectBuildTypeRemind extends Base
{

    protected $table = "mk_engin_project_build_type_remind";
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
    public function addBuildRemind($data,$guid){
        if (empty($data)){
            return self::showReturnCodeWithOutData(1003);
        }
        if (empty($guid)){
            return self::showReturnCodeWithOutData(1003);
        }
        $data['build_type_id']=$guid;
        try{
            $this->startTrans();
            $this->data($data)->isUpdate(false)->save();

            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }
    public function delRemind($data){
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