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
use app\erp\config\FieldValue;
use think\Db;
use think\Exception;
use think\Loader;

class ProjectContractAudit extends Base
{

    protected $table = "mk_project_contract_audit";
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
        try{
            $this->startTrans();
            $this->data(['status'=>-1])->isUpdate(true,['project_id'=>$data['project_id'],'book_number'=>$data['book_number']])->save();
            $this->data($data)->isUpdate(false)->save();
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }
    public function delProPhoto($guid){
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
    public function editSaveData($data,$number,$guid){
        if (empty($data)){
            return self::showReturnCodeWithOutData(1003);
        }
        if (empty($guid)){
            return self::showReturnCodeWithOutData(1003);
        }
        if (empty($number)){
            return self::showReturnCodeWithOutData(1003);
        }
        $department_type=array_search("设计部",FieldValue::getFieldValue("department_type"))?:3;
        try{
            $this->startTrans();
            $re=$this->data($data)->isUpdate(true,['project_id'=>$guid,'book_number'=>$number,'status'=>1])->save();
            if($re!=1){
                throw new Exception("签订合同审核出现错误");
            }
            if($data['examine_status']!=1){
                $this->data(['status'=>-1])->isUpdate(true,['project_id'=>$guid,'book_number'=>$number])->save();
            }

            //签订合同审核成功后记录一次
            if($data["examine_status"]=="1"){
                $model=new ProjectSuccessTime();
                $uuid=Db::table("mk_project_related_person")->where(["project_id"=>$guid,'department_type'=>$department_type])->value("uuid");
                $model->save(['project_id'=>$guid,'type'=>3,'uuid'=>$uuid]);
            }
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }

}