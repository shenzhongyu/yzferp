<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/4/17
 * Time: 15:51
 */

namespace app\base\model;


use think\Db;

class MaterialTemplateBudgetAccess extends Base
{

    protected $table = "mk_material_template_budget_access";
    protected $insert = [];
    protected $autoWriteTimestamp = false;

    protected function setGuidAttr($value, $data)
    {
        do {
            $mk_guid= $this->create_uuid();
        } while ($this->where('guid',$mk_guid)->count()==1);
        return $mk_guid ;
    }

    public function getList($map = [],$field=false){
        if (empty($field)){
            $field = '';
        }
        return $this->where($map)->field($field)->select();
    }
    public function getCont($map = []){
        return $this->where($map)->count();
    }

    public function savaBudgetData($data){
        if (empty($data)) return self::showJsonReturnCode("1003");
        if (empty($data["budget_id"])) return self::showReturnCodeWithOutData(1003);
        if(empty($data["material_id"])) return self::showReturnCodeWithOutData(1003);
        try{
            if(is_array($data["material_id"])){
                $this->startTrans();
                foreach ($data["material_id"] as $value){
                    //先删除
                    $this->where(['material_id'=>$value])->delete();
                    $this->insert(['budget_id'=>$data["budget_id"],'material_id'=>$value]);
                }
                $this->commit();
            }
        }catch (\Exception $e){
            $this->rollback();
            return ['code'=>'1008','msg'=>$e->getMessage()];
        }
        return self::showReturnCode(1001);
    }
    public function delBudgetData($data){
        if (empty($data)) return self::showReturnCodeWithOutData("1003");
        if (empty($data["budget_id"])) return self::showReturnCodeWithOutData(1003);
        if(empty($data["material_id"])) return self::showReturnCodeWithOutData(1003);
        if(empty($data["type"])) return self::showReturnCodeWithOutData(1003);
        $re=$this->where(['budget_id'=>$data["budget_id"],'material_id'=>$data["material_id"]])->delete();
        if($re=="1"){
            return self::showReturnCode(1001);
        }else{
            return self::showReturnCode(1020);
        }
    }

}