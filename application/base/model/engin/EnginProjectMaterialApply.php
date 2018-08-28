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
use app\base\model\budget\BudgetBookNumber;
use app\base\model\engin\ProjectDetailedListNumber;
use app\base\model\finance\BankTransactionRecord;
use app\base\model\finance\CollectPlanAccess;
use app\base\model\Material;
use app\base\model\Project;
use app\base\model\project\ProjectContract;
use think\Db;
use think\Exception;
use think\Loader;

class EnginProjectMaterialApply extends Base
{

    protected $table = "mk_engin_project_material_apply_audit";
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
        if (!isset($data['project_id'])){
            return self::showReturnCodeWithOutData(1003);
        }
        if (!isset($data['book_number'])){
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
    public function editCont($data,$guid){
        if (empty($guid)){
            return self::showReturnCodeWithOutData(1003);
        }
        if (empty($data)){
            return self::showReturnCodeWithOutData(1003);
        }
        $model_book=new BudgetBookNumber();
        $model=new EnginProjectMaterial();
        $model_copy=new EnginProjectMaterialCopy();
        $model_number=new ProjectDetailedListNumber();
        try{
            $this->startTrans();
            $obj=$this->where(['guid'=>$guid,'examine_status'=>0])->find();
            $re=$this->data($data)->isUpdate(true,['guid'=>$guid,'status'=>1])->save();
            if ($re!=1){
                $this->throwException("审核失败，该数据已变动");
            }
            if($data['examine_status']=="1"){
                $price=$model_book->where(['project_id'=>$obj['project_id'],'book_number'=>$obj['book_number'],'status'=>1])->find();
                if (empty($price)){
                    $this->throwException("该项目的合同已经无效，该审核不能进行");
                }

                $arr=[
                    'book_number'=>$price['book_number'],
                    'project_price'=>$price['project_price'],
                    'total_price'=>$price['total_price'],
                    'project_id'=>$price['project_id'],
                ];

                $model_number->data($arr)->isUpdate(false)->save();
                $id=$model_number->id;
                $number=$id+1000000;
                $model_number->data(['number'=>$number])->isUpdate(true,['id'=>$id])->save();

                $list=$model->getList(['project_id'=>$obj['project_id'],'book_number'=>$obj['book_number']]);
                if (!empty($list)){
                    foreach ($list as $value){
                        $save_data  =  is_object($value) ? $value->toArray() : $value;
                        unset($save_data['guid']);
                        unset($save_data['id']);
                        unset($save_data['create_time']);
                        unset($save_data['update_time']);
                        $save_data['list_number']=$number;
                        $model_copy->data($save_data)->isUpdate(false)->save();
                    }
                }else{
                    $this->throwException("该项目还没有选材,无法审核");
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