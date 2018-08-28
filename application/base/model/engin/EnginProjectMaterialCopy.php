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

class EnginProjectMaterialCopy extends Base
{

    protected $table = "mk_engin_project_material_copy";
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
        if (!isset($data['pro_id'])){
            return self::showReturnCodeWithOutData(1003);
        }
        if (!isset($data['number'])){
            return self::showReturnCodeWithOutData(1003);
        }
        if (!isset($data['material_id'])){
            return self::showReturnCodeWithOutData(1003);
        }
        dump($data);
        $model_material=new EnginProjectMaterial();
        if (!is_array($data['material_id']))  return self::showReturnCodeWithOutData(1003);
        try{
            $this->startTrans();


            
//            foreach ($data['material_id'] as $value){
//                $m_obj=$model_material->where(['guid'=>$value,'status'=>1])->find();
//                if (empty($m_obj)){
//                    $this->throwException('选取的主材不存在，请刷新主材数据再选取');
//                }
//                if (is_object($m_obj)){
//                    $m_obj=$m_obj->toArray();
//                }
//                unset($m_obj['guid']);
//                unset($m_obj['create_time']);
//                unset($m_obj['update_time']);
//                unset($m_obj['status']);
//                unset($m_obj['id']);
//                $m_obj['apply_id']=
//                $this->data($m_obj)->isUpdate(false)->save();
//            }
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }
    public function replaceData($data){
        if (empty($data)){
            return self::showReturnCodeWithOutData(1003);
        }
        if (!isset($data['guid'])){
            return self::showReturnCodeWithOutData(1003);
        }
        if (!isset($data['material_id'])){
            return self::showReturnCodeWithOutData(1003);
        }
        $model_material=new Material();
        try{
            $this->startTrans();
            $m_obj=$model_material->where(['guid'=>$data['material_id'],'status'=>1])->find();
            if (empty($m_obj)){
                $this->throwException('选取的主材不存在，请刷新主材数据再选取');
            }
            if (is_object($m_obj)){
                $m_obj=$m_obj->toArray();
            }
            $m_obj['old_id']=$m_obj['guid'];
            unset($m_obj['guid']);
            unset($m_obj['create_time']);
            unset($m_obj['update_time']);
            unset($m_obj['status']);
            unset($m_obj['id']);
            $this->data($m_obj)->isUpdate(true,['guid'=>$data['guid']])->save();

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

}