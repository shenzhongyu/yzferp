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
use app\base\model\budget\DefaultProjectCopy;
use app\base\model\finance\BankTransactionRecord;
use app\base\model\finance\CollectPlanAccess;
use app\base\model\Material;
use app\base\model\Project;
use app\base\model\project\ProjectContract;
use think\Db;
use think\Exception;
use think\exception\ErrorException;
use think\Loader;

class EnginProjectBuildTypeDayAccess extends Base
{

    protected $table = "mk_engin_project_build_type_day_access";
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
            $map =['status'=>1];
        }else{
            $map['status']=1;
        }
        if (empty($field)){
            $field = '';
        }
        return  $this->where($map)->field($field)->select();
    }
    public function addDayAccess($data){
        if (empty($data)) return self::showReturnCode(1003);
        $data['day_time_id']=explode(',',$data['day_time_id']);
        if (!is_array($data['day_time_id']))  return self::showReturnCode(1003);
        $model=new DefaultProjectCopy();
        $obj=$model->getInfoByGuId($data['day_build_id']);
//        $obj=DefaultProjectCopy::quickGet($data['day_build_id']);
        try{
            $this->startTrans();
            foreach ($data['day_time_id'] as $value){
                $arr=[
                    'day_id'=>$value,
                    'data_id'=>$data['day_build_id'],
                    'data_name'=>$data['day_name'],
                    'desc'=>$data['desc'],
                    'data_desc'=>$obj['desc'],
                    'data_user_num'=>$data['day_user'],
                    'uuid'=>$data['uuid'],
                    'uuid_name'=>$data['uuid_name'],
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

    public function delDataCon($data){
        if (empty($data)) return self::showReturnCode(1003);
        try{
            $this->startTrans();

            $this->data(['status'=>-1])->isUpdate(true,['guid'=>$data])->save();

            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }

}