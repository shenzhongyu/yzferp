<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/4/17
 * Time: 15:51
 */

namespace app\base\model\build;


use app\base\model\Base;
use app\base\model\BaseHash;
use app\base\model\budget\DefaultProjectCopy;
use app\base\model\budget\DefaultRateCopy;
use app\base\model\MaterialDataStyle;
use app\base\model\Project;
use think\Db;
use think\Exception;
use think\Loader;

class BuildProjectCategoryPhoto extends BaseHash
{

    protected $table = "mk_build_project_category_photo";
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
    public function saveBuildPhoto($data){
        if (empty($data)) return self::showReturnCodeWithOutData(1003);
        //判断该工程属不属于他
        $model=new BuildSupervisionTem();
        $obj=$model->where(['project_id'=>$data['project_id'],'book_number'=>$data['book_number'], 'category_id'=>$data['category_id'],'user_id'=>$this->uuid,'status'=>1,'type'=>1])->find();
        if (empty($obj)){
            return self::showReturnCode(1003,[],'数据变动，请刷新数据');
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
    public function deletePhoto($data){
        if (empty($data)) return self::showReturnCodeWithOutData(1003);
        if (!isset($data['id'])) return self::showReturnCodeWithOutData(1003);
        if (empty($data['id'])) return self::showReturnCodeWithOutData(1003);
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