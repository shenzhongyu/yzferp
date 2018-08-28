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
use app\base\model\build\BuildProjectCategoryTime;
use app\base\model\build\BuildProjectTime;
use app\base\model\build\BuildSupervisionTem;
use app\base\model\finance\BankTransactionRecord;
use app\base\model\finance\CollectPlanAccess;
use app\base\model\Material;
use app\base\model\MaterialDataStyle;
use app\base\model\Project;
use app\base\model\project\ProjectContract;
use think\Db;
use think\Exception;
use think\Loader;

class EnginProjectBuildTypeAudit extends BaseHash
{
    protected $table = "mk_engin_project_build_type_audit";
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
    public function auditBuildApply($data,$guid){
        if (empty($guid)){
            return self::showReturnCodeWithOutData(1003);
        }
        if (empty($data)){
            return self::showReturnCodeWithOutData(1003);
        }
        if (!isset($data['examine_status'])) return self::showReturnCodeWithOutData(1003);
        if (!in_array($data['examine_status'],['-1','0','1']))  return self::showReturnCodeWithOutData(1003);
//        $obj=self::quickGet($guid);
        $obj=$this->getInfoByGuId($guid);
        $model=new EnginProjectBuildType();
        try{
            $this->startTrans();
            $re=$this->data($data)->isUpdate(true,['guid'=>$guid,'status'=>1])->save();
            if ($re!=1){
                $this->throwException("该审核信息已发生变动，请刷新数据");
            }
            if ($data['examine_status']=="-1"){
                $this->data(['status'=>2])->isUpdate(true,['guid'=>$guid])->save();
                //审核不通过该施工阶段还原
                $model->data(['type'=>0])->isUpdate(true,['guid'=>$obj['build_id']])->save();
            }
            if ($data['examine_status']=="1"){
                //审核通过该施工阶段变成完成状态
                $model->data(['type'=>2])->isUpdate(true,['guid'=>$obj['build_id']])->save();
            }
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }
    public function addBuildTypeAuidt($data,$guid){
        if (empty($data)){
            return self::showReturnCodeWithOutData(1003);
        }
        if (empty($guid)){
            return self::showReturnCodeWithOutData(1003);
        }
        $type_model=new EnginProjectBuildType();
        $obj=$type_model->getInfoByGuId($guid);
//        $obj=EnginProjectBuildType::quickGet($guid);
        $data['apply_desc']=$data['desc'];
        unset($data['desc']);
        $data['project_id']=$obj['project_id'];

        $pro_model=new Project();
        $pro=$pro_model->getInfoByGuId($obj['project_id']);
//        $pro=Project::quickGet($obj['project_id']);

        $data['project_name']=$pro['project_name'];
        $data['build_name']=$obj['build_name'];
        $data['build_id']=$guid;
        $model=new EnginProjectBuildType();
        $count=$this->where(['build_id'=>$guid,'examine_status'=>0])->count();
        if ($count!=0){
            return self::showReturnCode(1003,[],'该阶段已提交完工申请');
        }
        try{
            $this->startTrans();
            $this->data($data)->isUpdate(false)->save();
            //提交完工申请，该施工阶段变成审核中状态
            $model->data(['type'=>1])->isUpdate(true,['guid'=>$guid])->save();
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }
    public function addBuildCategoryAudit($data){
        if (empty($data)){
            return self::showReturnCodeWithOutData(1003);
        }
        $model_p=new MaterialDataStyle();
        $obj=$model_p->getInfoByGuId($data['category_id']);
//        $obj=MaterialDataStyle::quickGet($data['category_id']);
        $data['apply_desc']=$data['desc'];
        unset($data['desc']);

        $pro_model=new Project();
        $pro=$pro_model->getInfoByGuId($data['project_id']);
//        $pro=Project::quickGet($data['project_id']);
        $data['project_name']=empty($pro)?"":$pro['project_name'];
        $data['build_name']=empty($obj)?"":$obj['categories_name'];
        $data['build_id']=$data['category_id'];
        $count=$this->where(['build_id'=>$data['category_id'],'examine_status'=>0])->count();
        if ($count!=0){
            return self::showReturnCode(1003,[],'该阶段已提交完工申请');
        }
        $model=new BuildProjectCategoryTime();
        $category_id=$data['category_id'];
        unset($data['category_id']);

        try{
            $this->startTrans();
            $this->data($data)->isUpdate(false)->save();
            //提交完工申请，该施工阶段变成审核中状态
            $model->data(['type'=>1])->isUpdate(true,['project_id'=>$data['project_id'],'book_number'=>$data['book_number'],'category_id'=>$category_id])->save();
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }
    public function auditBuildCategoryApply($data,$guid){
        if (empty($guid)) {
            return self::showReturnCodeWithOutData(1003);
        }
        if (empty($data)) {
            return self::showReturnCodeWithOutData(1003);
        }
        if (!isset($data['examine_status'])) return self::showReturnCodeWithOutData(1003);
        if (!in_array($data['examine_status'], ['-1', '0', '1'])) return self::showReturnCodeWithOutData(1003);
//        $obj = self::quickGet($guid);
        $obj = $this->getInfoByGuId($guid);
        $model = new BuildProjectCategoryTime();
        try {
            $this->startTrans();
            $re = $this->data($data)->isUpdate(true, ['guid' => $guid, 'status' => 1])->save();
            if ($re != 1) {
                $this->throwException("该审核信息已发生变动，请刷新数据");
            }
            if ($data['examine_status'] == "-1") {
                $this->data(['status' => 2])->isUpdate(true, ['guid' => $guid])->save();
                //审核不通过该施工阶段还原
                $model->data(['type'=>0])->isUpdate(true,['project_id'=>$obj['project_id'],'book_number'=>$obj['book_number'],'category_id'=>$obj['build_id']])->save();
            }
            if ($data['examine_status'] == "1") {
                //审核通过该施工阶段变成完成状态
                $model->data(['type'=>1])->isUpdate(true,['project_id'=>$obj['project_id'],'book_number'=>$obj['book_number'],'category_id'=>$obj['build_id']])->save();
            }
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        } catch (Exception $e) {
            $this->rollback();
            return self::showReturnCodeWithOutData(1008, $e->getMessage());
        }
    }
}