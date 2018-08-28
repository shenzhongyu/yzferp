<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/4/17
 * Time: 15:51
 */

namespace app\base\model\finance;


use app\base\model\Base;
use think\Db;
use think\Exception;
use think\Loader;

class CollectPlan extends Base
{

    protected $table = "mk_finance_collect_plan";
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
            $map['status']=1;
        }
        if (empty($field)){
            $field = '';
        }
        return  $this->where($map)->field($field)->select();
    }

    public function addStyle($data){
        if (empty($data)){
            return self::showReturnCodeWithOutData(1003);
        }
        $arr=[
            'collect_plan_name'=>$data['collect_plan_name'],
            'desc'=>isset($data['desc']) ? $data['desc'] :'',
        ];
        try{
            $this->startTrans();
            if (isset($data['guid'])){
                $this->data($arr)->isUpdate(true,['guid'=>$data['guid']])->save();
            }else{
                $this->data($data)->isUpdate(false)->save();
            }
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }
    public function delStyle($guid){
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

}