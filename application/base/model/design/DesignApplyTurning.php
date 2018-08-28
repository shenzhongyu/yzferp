<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/4/17
 * Time: 15:51
 */

namespace app\base\model\design;


use app\base\model\Base;
use app\base\model\BaseHash;
use app\base\model\Project;
use think\Db;
use think\Exception;
use think\Loader;

class DesignApplyTurning extends BaseHash
{

    protected $table = "mk_design_apply_turning";
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
            $map['status'] = 1 ;
        }
        if (empty($field)){
            $field = '';
        }
        return $this->where($map)->field($field)->select();
    }
    public function saveCheck($data){
        if (empty($data)) return self::showReturnCodeWithOutData(1003);
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
    public function editCheck($data,$guid){
        if (empty($data)) return self::showReturnCodeWithOutData(1003);
        if (!isset($data['examine_status'])) return self::showReturnCodeWithOutData(1003);
        if (!in_array($data['examine_status'],[-1,1])) return self::showReturnCodeWithOutData(1003);
        $obj=$this->where(['guid'=>$guid])->find();
        $model=new Project();
        try{
            $this->startTrans();
            $re=$this->data($data)->isUpdate(true,['guid'=>$guid,'examine_status'=>0])->save();
            if ($re!=1){
                $this->throwException("该审核信息已发生变动，请刷新数据");
            }else{
                if ($data['examine_status']=="1"){
                    $model->data(['feedback_stage'=>3])->isUpdate(true,['guid'=>$obj['project_id']])->save();
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