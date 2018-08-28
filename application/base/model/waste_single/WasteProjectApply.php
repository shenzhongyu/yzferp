<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/22
 * Time: 14:27
 */

namespace app\base\model\waste_single;


use app\base\model\BaseHash;
use app\base\model\PersonnelUser;
use app\base\model\Project;
use app\erp\config\FieldValue;
use think\Exception;
use think\Loader;

class WasteProjectApply extends BaseHash
{
    protected $table = "mk_waste_project_apply";
    protected $insert = ['guid','status'=>1];
    protected $autoWriteTimestamp = true;
    protected $hashKey='guid';

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

    public function addApply($data,$guid){
        $obj=$this->where(['project_id'=>$guid,'examine_status'=>0])->find();
        if (empty($obj)){
            $data['project_id']=$guid;
            $data['examine_status']=0;
            $re=$this->data($data)->isUpdate(false)->save();
            if ($re==1){
                return self::showReturnCodeWithOutData(1001);
            }else{
                return self::showReturnCodeWithOutData(1003,'申请失败,请联系管理员');
            }

        }else{
            return self::showReturnCodeWithOutData(1003,'该项目已提交跟踪申请');
        }
    }

    public function editApply($data,$guid){
        if (empty($data)) return self::showReturnCode(1003);
        if (empty($guid)) return self::showReturnCode(1003);
        $arr=[
            "examine_status"=>$data['examine_status'],
            "examine_desc"=>$data["examine_desc"],
            'examine_uuid'=>$this->uuid,
        ];
        if (!in_array($data['examine_status'],['-1','0','1'])){
            return self::showReturnCode(1003);
        };
        $model=new Project();
        $obj=$this->where(['guid'=>$guid])->find();
        if (empty($obj)) return self::showReturnCode(1003);
        $model_user=new PersonnelUser();
        try{
            $this->startTrans();
            $re=$this->data($arr)->isUpdate(true,['guid'=>$guid,'examine_status'=>0])->save();
            if ($re!=1){
                $this->throwException("审核失败，该审核信息已变动");
            }
            if($data['examine_status']=="1"){
                $user=$model_user->getInfoByGuId($obj['uuid']);
//                $user=PersonnelUser::quickGet($obj['uuid']);
                if ($user['department_type']==array_search("市场部",FieldValue::getFieldValue("department_type"))?:6){  //市场部
                    $model->data(['uuid'=>$obj['uuid'],'status'=>1,'department_id'=>$user['department_id'],'project_type'=>0])->isUpdate(true,['guid'=>$obj['project_id']])->save();
                }else if($user['department_type']==array_search("客服部",FieldValue::getFieldValue("department_type"))?:8){  //客服部
                    $model->data(['uuid'=>$obj['uuid'],'status'=>1,'department_id'=>$user['department_id'],'project_type'=>1])->isUpdate(true,['guid'=>$obj['project_id']])->save();
                }else{
                    $model->data(['uuid'=>$obj['uuid'],'status'=>1,'department_id'=>$user['department_id']])->isUpdate(true,['guid'=>$obj['project_id']])->save();
                }

            }
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }
}