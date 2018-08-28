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
use app\base\model\BudgetDefaultProject;
use think\Db;
use think\Exception;
use think\Loader;

class BudgetCopySpace extends Base
{

    protected $table = "mk_budget_space_template_copy";
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
            $map = ['status'=>1];
        }else{
            $map['status'] = 1 ;
        }
        if (empty($field)){
            $field = '';
        }
        return $this->where($map)->field($field)->select();
    }
    public function saveSpace($data){
        if (empty($data)) return self::showReturnCodeWithOutData(1003);
        if (!isset($data['id'])) return self::showReturnCodeWithOutData(1003);
        $model=new BudgetDefaultProject();
        $obj=$model->getInfoByGuId($data['id']);
        if (empty($obj)) return self::showReturnCode(1003);
        $obj=is_object($obj)?$obj->toArray():$obj;
        unset($obj['id']);
        unset($obj['guid']);
        unset($obj['project_id']);
        unset($obj['user_name']);
        unset($obj['user_tel']);
        $obj['space_type']=empty($data['type'])?'1':$data['type'];
        $list=$model->getList(['pid'=>$data['id']]);
        try{
            $this->startTrans();
            $this->data($obj)->isUpdate(false)->save();
            $guid=$this->guid;
            if (!empty($list)){
                foreach ($list as $value){
                    $value=is_object($value)?$value->toArray():$value;
                    unset($value['id']);
                    unset($value['guid']);
                    unset($value['project_id']);
                    unset($value['user_name']);
                    unset($value['user_tel']);
                    $value['pid']=$guid;
                    $value['space_type']=empty($data['type'])?'1':$data['type'];
                    $this->data($value)->isUpdate(false)->save();
                }
            }

            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }
    public function delSpace($data){
        if (empty($data)) return self::showReturnCodeWithOutData(1003);
        try{
            $this->startTrans();
            $this->data(['status'=>-1])->isUpdate(true,['guid'=>$data["id"]])->save();
            $this->data(['status'=>-1])->isUpdate(true,['pid'=>$data["id"]])->save();
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }
    public function getSpaceData($data,$name,$mobile,$uuid){
        if (empty($data)) return self::showReturnCodeWithOutData(1003);
        if (!isset($data['id'])) return self::showReturnCodeWithOutData(1003);
        if (!isset($data['pro_id'])) return self::showReturnCodeWithOutData(1003);
        $obj=$this->where(['guid'=>$data['id'],'status'=>1])->find();
        if (empty($obj)) return self::showReturnCode(1003,'空间不存在');
        $list=$this->getList(['pid'=>$data['id']]);
        $obj=is_object($obj) ? $obj->toArray():$obj;
        $obj['project_id']=$data['pro_id'];
        $obj['uuid']=$uuid;
        $obj['user_name']=$name;
        $obj['user_tel']=$mobile;
        unset($obj['guid']);
        unset($obj['id']);
        unset($obj['space_type']);
        $model=new BudgetDefaultProject();
        $model->save($obj);
        $guid=$model->guid;
        halt($list);
        try{
            $this->startTrans();
            if (!empty($list)){
                foreach ($list as $value){
                    $value=is_object($value) ? $value->toArray():$value;
                    $value['project_id']=$data['pro_id'];
                    $value['uuid']=$uuid;
                    $value['user_name']=$name;
                    $value['user_tel']=$mobile;
                    $value['pid']=$guid;
                    unset($value['guid']);
                    unset($value['id']);
                    unset($value['space_type']);
                    $model->save($value);
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