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

class BuildSupervisionTem extends BaseHash
{

    protected $table = "mk_build_supervision_tem";
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
    public function saveBuild($data,$category_id){
        if (empty($data)) return self::showReturnCodeWithOutData(1003);
        if (empty($category_id)) return self::showReturnCodeWithOutData(1003);
        if (!isset($data['user_id'])) return self::showReturnCodeWithOutData(1003);
        if (!is_array($data['user_id'])) return self::showReturnCodeWithOutData(1003);
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
            $this->data(['status'=>2])->isUpdate(true,['project_id'=>$data['project_id'],'book_number'=>$data['book_number'],'category_id'=>$data['category_id']])->save();
            foreach ($data['user_id'] as $value){
                $arr=[
                    'company_id'=>$data['company_id'],
                    'project_id'=>$data['project_id'],
                    'category_id'=>$data['category_id'],
                    'book_number'=>$data['book_number'],
                    'uuid'=>$data['uuid'],
                    'user_id'=>$value,
                    'type'=>1,  //发包经理
                ];
                $this->data($arr)->isUpdate(false)->save();
                $model->data(['status'=>2])->isUpdate(true,['project_id'=>$data['project_id'],'book_number'=>$data['book_number'],'category_id'=>$data['category_id']])->save();
                $re=$model->data($brr)->isUpdate(false)->save();
                if ($re!=1){
                    $this->throwException("分配施工队失败，请联系管理员");
                }
            }
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }
    public function saveBuildData($data){
        if (empty($data)) return self::showReturnCodeWithOutData(1003);
        if (!isset($data['user_id'])) return self::showReturnCodeWithOutData(1003);
        if (!isset($data['category_id'])) return self::showReturnCodeWithOutData(1003);
        if (!is_array($data['category_id'])) return self::showReturnCodeWithOutData(1003);
        try{
            $this->startTrans();
            $this->data(['status'=>2])->isUpdate(true,['project_id'=>$data['project_id'],'book_number'=>$data['book_number'],'category_id'=>['in',$data['category_id']]])->save();
            foreach ($data['category_id'] as $value){
                $arr=[
                    'company_id'=>$data['company_id'],
                    'project_id'=>$data['project_id'],
                    'category_id'=>$value,
                    'book_number'=>$data['book_number'],
                    'uuid'=>$data['uuid'],
                    'user_id'=>$data['user_id'],
                    'type'=>1,  //发包经理
                ];
                $this->data($arr)->isUpdate(false)->save();
            }
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }
    public function saveBuildAdd($data,$category_id){
        if (empty($data)) return self::showReturnCodeWithOutData(1003);
        if (empty($category_id)) return self::showReturnCodeWithOutData(1003);
        if (!isset($data['user_id'])) return self::showReturnCodeWithOutData(1003);
        if (!is_array($data['user_id'])) return self::showReturnCodeWithOutData(1003);
        $obj=$this->where(['category_id'=>$category_id,'user_id'=>$this->uuid,'status'=>1])->find();
        if (empty($obj)){
            return self::showReturnCodeWithOutData(1003,'数据有变动，请先刷新数据');
        }
        try {
            $this->startTrans();
            $this->data(['status' => 2])->isUpdate(true, ['project_id' => $data['project_id'], 'book_number' => $data['book_number'], 'category_id' => $data['category_id'],'type'=>2])->save();
            foreach ($data['user_id'] as $value) {
                $arr = [
                    'company_id' => $data['company_id'],
                    'project_id' => $data['project_id'],
                    'category_id' => $data['category_id'],
                    'book_number' => $data['book_number'],
                    'uuid' => $data['uuid'],
                    'user_id' => $value,
                    'type'=>2,  //普通的施工人员
                ];
                $this->data($arr)->isUpdate(false)->save();
            }
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        } catch (Exception $e) {
            $this->rollback();
            return self::showReturnCodeWithOutData(1008, $e->getMessage());
        }
    }

    public function delBuildUser($data){
        if (empty($data)) return self::showReturnCode(1003);
        if (empty($data['id'])) return self::showReturnCode(1003);
        try {
            $this->startTrans();
            $this->data(['status' =>-1])->isUpdate(true, ['guid' => $data['id']])->save();
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        } catch (Exception $e) {
            $this->rollback();
            return self::showReturnCodeWithOutData(1008, $e->getMessage());
        }
    }
}