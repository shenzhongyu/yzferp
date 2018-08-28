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
use think\Exception;
use think\Loader;

class BudgetDefaultRate extends Base
{

    protected $table = "mk_budget_default_rate";
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
            $map['status'] = 1 ;
        }
        if (empty($field)){
            $field = '';
        }
        return $this->where($map)->field($field)->select();
    }
    public function saveData($data,$pro_id,$com_id){
//        if (!isset($data['rate'])) return self::showReturnCodeWithOutData(1003);
        if (!isset($data['budget_type'])) return self::showReturnCodeWithOutData(1003,'未选择预算类型');
        if (empty($com_id)) return self::showReturnCodeWithOutData(1003);
        if (empty($pro_id)) return self::showReturnCodeWithOutData(1003);
        $model=new MaterialRateItem();
        $rate=$model->where(['budget_type'=>$data['budget_type'],'status'=>1,'company_id'=>$com_id])->select();
        if (empty($rate)) return self::showReturnCodeWithOutData(1003,'该类型下没有固定费率，请重新选择或为该类型添加固定费率');
        $rate_list=[];
        foreach ($rate as $value){
            $rate_list[]=$value['guid'];
        }
        if (isset($data['rate'])){
            if (!empty($data['rate'])){
                $project_rate=array_unique(array_merge($rate_list,$data['rate']));
            }else{
                $project_rate=$rate_list;
            }
        }else{
            $project_rate=$rate_list;
        }
        $model=new MaterialRateItem();
        try {
            $this->startTrans();
            $arr=[];
            foreach ($project_rate as $value){
                $list=$model->getInfoByGuId($value);
                if (!empty($list)){
                    $arr[]=[
                        'company_id'=>$com_id,
                        'project_id'=>$pro_id,
                        'rate_default_name'=>$list['rate_name'],
                        'rate_default_value'=>$list['rate_value'],
                        'rate_default_type'=>'*',
                        'desc'=>$list['rate_desc'],
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
        $re=$this->checkProjectDesign($data['project_id']);
        if(!$re){
            return self::showReturnCodeWithOutData(1003,'该项目已申请打印预算，无法修改');
        }
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

        $req=$this->where(['guid'=>$data['guid'][0],'status'=>1])->find();
        if (empty($req)) return self::showReturnCodeWithOutData(1003);

        if (!$this->checkProjectDesign($req['project_id'])){
            return self::showReturnCodeWithOutData(1020,'该项目已申请预算打印，无法修改');
        }

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

    public function checkProjectDesign($pro_id){  //检测项目预算是否提交审核
        $obj=Db::table('mk_budget_print_request')->where(['project_id'=>$pro_id,'status'=>1])->find();
        $revise=Db::table('mk_budget_revise')->where(['project_id'=>$pro_id,'status'=>1])->find();
        if (empty($obj)){
            return true;
        }else{
            //申请预算修改有没有通过
            if(empty($revise) || $obj['examine_status']!="-1"){
                return false;
            }else if($revise['examine_status']!="1"){
                return false;
            }else{
                return true;
            }

        }

    }
}