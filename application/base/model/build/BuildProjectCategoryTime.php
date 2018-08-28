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

class BuildProjectCategoryTime extends BaseHash
{

    protected $table = "mk_build_project_category_time";
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
    public function saveBuildTime($data)
    {
        if (empty($data)) return self::showReturnCodeWithOutData(1003);
        try {
            $this->startTrans();
            $this->data(['status' => 2])->isUpdate(true, ['project_id' => $data['project_id'], 'book_number' => $data['book_number']])->save();
            $this->data($data)->isUpdate(false)->save();
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        } catch (Exception $e) {
            $this->rollback();
            return self::showReturnCodeWithOutData(1008, $e->getMessage());
        }
    }
    public function saveTime($data,$category_id){
        if (empty($data)) return self::showReturnCodeWithOutData(1003);
        if (empty($category_id)) return self::showReturnCodeWithOutData(1003);
        $brr=[
            'company_id'=>$data['company_id'],
            'project_id'=>$data['project_id'],
            'category_id'=>$data['category_id'],
            'book_number'=>$data['book_number'],
            'uuid'=>$data['uuid'],
            'build_time'=>strtotime($data['build_time']),
            'desc'=>$data['desc'],
            'build_days'=>$data['build_days']
        ];
        $model=new BuildProjectCategoryTime();
        try{
            $this->startTrans();
            $model->data(['status'=>2])->isUpdate(true,['project_id'=>$data['project_id'],'book_number'=>$data['book_number'],'category_id'=>$data['category_id']])->save();
            $re=$model->data($brr)->isUpdate(false)->save();
            if ($re!=1){
                $this->throwException("设置施工时间失败，请联系管理员");
            }

            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }
}