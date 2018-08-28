<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/4/17
 * Time: 15:51
 */

namespace app\base\model\office;


use app\base\model\Base;
use app\base\model\BaseHash;
use app\base\model\Project;
use think\Db;
use think\Exception;
use think\Loader;

class OfficeVehicleApply extends BaseHash
{

    protected $table = "mk_office_vehicle_apply";
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
    public function saveApplyData($data){
        if (empty($data)){
            return self::showReturnCodeWithOutData(1003);
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
    public function editProjectAudit($data,$guid){
        if (empty($data)) return self::showReturnCode(1003);
        if (empty($guid)) return self::showReturnCode(1003);
        if (!in_array($data['examine_status'],['-1','0','1'])){
            return self::showReturnCode(1003);
        };
        $model=new OfficeVehicle();
        $obj=$this->where(['guid'=>$guid])->find();
        if (empty($obj))return  self::showReturnCode(1003);
        $car=$model->getInfoByGuId($obj['vehicle_id']);
        if(empty($car)){
            return  self::showReturnCode(1003);
        }
//        if($car["use_status"]!="0" && $data['examine_status']=="1"){
//            return  self::showReturnCode(1003,[],'该车不处于可用状态，无法同意申请使用该车');
//        }
        try{
            $this->startTrans();
            $re=$this->data($data)->isUpdate(true,['guid'=>$guid,'examine_status'=>'0'])->save();
            if ($re!=1){
                $this->throwException("审核失败，该审核信息已变动");
            }
            if($data['examine_status']=="-1"){
                $this->data(['status'=>-1])->isUpdate(true,['guid'=>$guid])->save();
            }
            if($data['examine_status']=="1"){
                $model->data(['use_status'=>1])->isUpdate(true,['guid'=>$obj['vehicle_id']])->save();
            }
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }

}