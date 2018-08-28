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
use app\base\model\build\BuildSupervision;
use app\base\model\finance\BankTransactionRecord;
use app\base\model\finance\CollectPlanAccess;
use app\base\model\Material;
use app\base\model\Project;
use app\base\model\project\ProjectContract;
use think\Db;
use think\Exception;
use think\Loader;

class EnginProjectCompletionAudit extends BaseHash
{
    protected $table = "mk_engin_project_completion_audit";
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
    public function auditProjectApply($data,$guid){
        if (empty($guid)){
            return self::showReturnCodeWithOutData(1003);
        }
        if (empty($data)){
            return self::showReturnCodeWithOutData(1003);
        }
        if (!isset($data['examine_status'])) return self::showReturnCodeWithOutData(1003);
        if (!in_array($data['examine_status'],['-1','0','1']))  return self::showReturnCodeWithOutData(1003);
        $obj=$this->where(["guid"=>$guid])->value("project_id");
        if(empty($obj)){
            return self::showReturnCodeWithOutData(1003);
        }
        $model=new Project();
        $user_pro=new BuildSupervision();
        try{
            $this->startTrans();
            $re=$this->data($data)->isUpdate(true,['guid'=>$guid,'status'=>1])->save();
            if ($re!=1){
                $this->throwException("该审核信息已发生变动，请刷新数据");
            }


            if($data['examine_status'==1]){
                $model->save(["engin_status"=>2],["guid"=>$obj]);
                //竣工申请通过后 改变监理表里面的状态
                $user_pro->save(["type"=>2],['project_id'=>$obj]);
            }
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }
    public function addProjectCompletionAuidt($data,$guid){
        if (empty($data)){
            return self::showReturnCodeWithOutData(1003);
        }
        if (empty($guid)){
            return self::showReturnCodeWithOutData(1003);
        }
        $data['apply_desc']=$data['desc'];
        unset($data['desc']);
        $data['project_id']=$guid;
        $model=new Project();
        $pro=$model->getInfoByGuId($guid);
        //$pro=Project::quickGet($guid);
        $data['project_name']=$pro['project_name'];
        $count=$this->where(['project_id'=>$guid,'examine_status'=>0])->count();
        if ($count!=0){
            return self::showReturnCode(1003,[],'该项目已提交完工申请');
        }
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

}