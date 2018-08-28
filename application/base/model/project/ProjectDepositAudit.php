<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/4/17
 * Time: 15:51
 */

namespace app\base\model\project;


use app\base\model\Base;
use app\base\model\finance\BankTransactionRecord;
use app\base\model\ProjectTraceLog;
use think\Db;
use think\Exception;
use think\Loader;
use think\Log;

class ProjectDepositAudit extends Base
{

    protected $table = "mk_project_deposit_price_audit";
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
            $map = ['status'=>1];
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
    public function delProPhoto($guid){
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
    public function editDateDeposit($data,$guid){
        if (empty($guid)){
            return self::showReturnCodeWithOutData(1003);
        }
        if (empty($data)){
            return self::showReturnCodeWithOutData(1003);
        }
        $model=new BankTransactionRecord();
        $model_pro=new ProjectDeposit();
        try{
            $this->startTrans();
            $re=$this->data($data)->isUpdate(true,['guid'=>$guid,'status'=>1])->save();
            if ($re!=1){
                throw new Exception('该信息出现错误');
            }
            $obj=$this->where(['guid'=>$guid])->find();
            if(empty($obj)){
                throw new Exception('数据出现异常');
            }
            Loader::model('base/Project')->save(['payment_phase'=>1],['guid'=>$obj['project_id']]);  //设置项目为定金项目
            $obj=is_object($obj) ? $obj->toArray() :$obj;
            $obj['create_time']=strtotime($obj['create_time']);
            $obj['update_time']=strtotime($obj['update_time']);
            $model_pro->save($obj);
            $save_data = [
                "project_id" => $obj['project_id'],
                "finance_mode" =>1,
                "book_number" => '',
                "bank_id" => $obj['bank_id'],
                "desc" =>$obj['project_name'].'项目的定金收入',
                "type" =>1,
                'price'=>$obj['collected_price'],
            ];
            //判断该项目是否存在合同
            $list=Loader::model('base/project/ProjectContract')->getList(['project_id'=>$obj['project_id']]);
            if (!empty($list)){
                foreach ($list as $value){
                    Loader::model('base/project/ProjectContract')->save(['price'=>$value['price']+$obj['collected_price']],['project_id'=>$obj['project_id'],'guid'=>$value['guid']]);
                }
            }



            $re=$model->createBankTransactionRecord($save_data);
            if ($re['code']!="1001"){
                throw new Exception($re['msg']);
            }
            $this->data(['status'=>2])->isUpdate(true,['guid'=>$guid])->save();
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            Log::error($e->getMessage());
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }

}