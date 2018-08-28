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
use app\base\model\BaseHash;
use think\Db;
use think\Exception;
use think\Loader;
use think\Log;

class Bank extends BaseHash
{

    protected $table = "mk_finance_bank";
    protected $insert = ['status'=>1,'guid'];
    protected $autoWriteTimestamp =true;
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
            $map['status']=1;
        }
        if (empty($field)){
            $field = '';
        }
        return  $this->where($map)->field($field)->select();
    }

    public function addData($data){
        if (empty($data)){
            return self::showReturnCodeWithOutData(1003);
        }
        $arr=[
            'name'=>$data['name'],
            'desc'=>isset($data['desc']) ? $data['desc'] :'',
            'uuid_name'=>$data['uuid_name'],
            'uuid'=>$data['uuid'],
            'payment_id'=>$data['payment_id'],
        ];

        try{
            $this->startTrans();
            if (isset($data['guid'])){
                $log=[
                    'desc'=>$data['desc'],
                    'name'=>$data['name'],
                    'bank_uuid'=>$data['uuid'],
                    'style'=>$data['payment_id'],
                    'balance_price'=>$data['balance_price'],
                    'guid'=>$data['guid'],
                ];
                $this->data($arr)->isUpdate(true,['guid'=>$data['guid']])->save();
                Loader::model('base/finance/BankLog')->addProjectLog($log);
            }else{
                $data["balance_price"]=0;
                $this->allowField(true)->data($data)->isUpdate(false)->save();
                $bank_id=$this->guid;
                $record_model =new BankTransactionRecord();
                if (!$record_model->createNewBankRecord($bank_id)){
                    $this->throwException("创建流水失败");
                };
            }
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }

    public function editPrice($data,$guid){
        if (empty($guid)){
            return self::showReturnCodeWithOutData(1003);
        }
        if (empty($data)){
            return self::showReturnCodeWithOutData(1003);
        }
        $list=$this->getInfoByGuId($guid);
        $save_data = [
            "project_id" => '',
            "finance_mode" => 3,
            "book_number" => '',
            "bank_id" => $guid,
            "desc" => $data['desc'],
            "type" =>1,
            'price'=>$data['balance_price'],
        ];
        $model=new BankTransactionRecord();
        try{
            $this->startTrans();

            $code=$model->createBankTransactionRecord($save_data);
            if ($code["code"]!="1001"){
                $this->throwException($code['msg']);
            }
            $log=[
                'desc'=>$data['desc'],
                'name'=>$list['name'],
                'bank_uuid'=>$list['uuid'],
                'style'=>$list['payment_id'],
                'balance_price'=>$list['balance_price'],
                'guid'=>$list['guid'],
            ];
            unset($data['desc']);
            Loader::model('base/finance/BankLog')->addProjectLog($log);
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            Log::error($e->getMessage());
         //   dump($e);
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }











//    public function delData($guid){
//        if (empty($guid)){
//            return self::showReturnCodeWithOutData(1003);
//        }
//        try{
//            $this->startTrans();
//            $this->data(['status'=>-1])->isUpdate(true,['guid'=>$guid])->save();
//            $this->commit();
//            return self::showReturnCodeWithOutData(1001);
//        }catch (Exception $e){
//            $this->rollback();
//            return self::showReturnCodeWithOutData(1008,$e->getMessage());
//        }
//    }
    public function delData($new_guid,$old_guid){
        if (empty($new_guid)){
            return self::showReturnCodeWithOutData(1003);
        }
        if (empty($old_guid)){
            return self::showReturnCodeWithOutData(1003);
        }
        $obj=$this->where(["guid"=>$new_guid,"status"=>1])->find();
        if (empty($obj)) return self::showReturnCodeWithOutData(1003,"转入的账号不存在");
        $old_obj=$this->where(["guid"=>$old_guid,"status"=>1])->find();
        if (empty($old_obj)) return self::showReturnCodeWithOutData(1003,"要删除的账号不存在");

        try{
            $this->startTrans();
            $this->data(['status'=>-1])->isUpdate(true,['guid'=>$old_guid])->save();

            //记录资金账户流水
            $save_data = [
                "project_id" =>"",
                "finance_mode" =>1,
                "book_number" =>"",
                "bank_id" =>$new_guid ,
                "desc" =>"【".$old_obj["name"]."】账号下的余额转入，转入金额为：".$old_obj["balance_price"],
                "type" =>6, //删除其他账号的金额转入
                'price'=>$old_obj["balance_price"],
            ];
            $model=new BankTransactionRecord();
            $re=$model->createBankTransactionRecord($save_data);
            if ($re['code']!="1001"){
                throw new Exception($re['msg']);
            }
            $this->data(["balance_price"=>$obj["balance_price"]+$old_obj["balance_price"]])->isUpdate(true,["guid"=>$new_guid])->save();
            //更改删除账号的流水记录,
            $model->save(["bank_id"=>$new_guid,'change_id'=>$old_guid],["bank_id"=>$old_guid]);


            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }

}