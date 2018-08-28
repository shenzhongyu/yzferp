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
use app\base\model\BaseHash;
use think\Db;
use think\Exception;
use think\Loader;

class DefaultRateCopy extends BaseHash
{

    protected $table = "mk_budget_default_rate_copy";
    protected $insert = ['status'=>1];
    protected $autoWriteTimestamp = false;
    protected $hashKey="guid";


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
    public function saveData($data,$pro_id,$com_id){
        if (!isset($data['rate'])) return self::showReturnCodeWithOutData(1003);
        if (empty($com_id)) return self::showReturnCodeWithOutData(1003);
        if (empty($pro_id)) return self::showReturnCodeWithOutData(1003);
        try {
            $this->startTrans();
            $arr=[];
            foreach ($data['rate'] as $value){
                $list=Loader::model("base/MaterialRateItem")->getList(['guid'=>$value]);
                if (!empty($list)){
                    $arr[]=[
                        'company_id'=>$com_id,
                        'project_id'=>$pro_id,
                        'rate_default_name'=>$list[0]['rate_name'],
                        'rate_default_value'=>$list[0]['rate_value'],
                        'rate_default_type'=>'*',
                        'desc'=>$list[0]['rate_desc'],
                    ];
                }
            }
            $this->saveAll($arr);
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }
    public function addRate($data){
        try {
            $this->startTrans();
            if (isset($data["guid"])){
                $this->data($data)->isUpdate(true,['guid'=>$data['guid']])->save();
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
    public function delRateData($data){
        if (empty($data) || !isset($data['guid']) ) return self::showReturnCodeWithOutData(1003);
        try {
            $this->startTrans();
            $this->data(['status'=>-1])->isUpdate(true,['guid'=>["in",array_values($data['guid'])]])->save();
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }
}