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
use think\Log;
use think\Request;

class BankTransactionRecord extends Base
{

    protected $table = "mk_finance_bank_transaction_record";
    protected $insert = ['status'=>1,'guid',"company_id","uuid"];
    protected $autoWriteTimestamp =true;
    protected $readonly = ['status'=>1,'guid',"company_id","project_id","book_number","bank_id"];


    public function createBankTransactionRecord($data){
        try{
            $this->noLoginThrowErr();
            if(!is_numeric($data["price"])){
                $this->throwException("资金非数字");
            }
            (int)$price = $data["price"];
            $finance_mode = $data["finance_mode"];
            $project_id = $data["project_id"];
            $book_number = $data["book_number"];
            $bank_id = $data["bank_id"];
            $type = $data["type"];
            $desc = $data["desc"];
            $save_data = [
                "project_id" => $project_id,
                "finance_mode" => $finance_mode,
                "book_number" => $book_number,
                "bank_id" => $bank_id,
                "desc" => $desc,
                "type" => (int)$type,
            ];

            $model_bank = new Bank();
            //对比余额
            if (!$model_bank->where(["guid"=>$bank_id])->find()){
                $this->throwException("资金帐号不存在 请联系管理员");
            }
            $balance_price=$this->where(["bank_id"=>$bank_id])->order(['id'=>"desc"])->value("balance_price");
            if ($balance_price!= $model_bank->where(["guid"=>$bank_id])->value("balance_price")){
                $this->throwException("资金余额和流水表不符 请联系管理员");
            }
            switch ((int)$finance_mode) {
                case 1://收入
                    $save_data["income_price"] = $price;         //收入
                    $balance_price_new = $balance_price + $price;
                    //  $finance_mode
                    break;
                case 2:  //支出  允许负数
                    $balance_price_new = $balance_price - $price;
                    $save_data["expenditure_price"] = $price;    //支出
                    break;
                case 3:  //变更账号余额
                    if($balance_price>$price){
                        $save_data["expenditure_price"] = $balance_price-$price;    //支出
                    }elseif($balance_price<$price){
                        $save_data["income_price"] =   $price-$balance_price;
                    }else{
                        $this->throwException("资金无变化");
                    }
                    $balance_price_new =$price;
                    break;
                default:
                    $this->throwException("资金方式错误");
                    $balance_price_new=$balance_price;
            }

            $save_data["balance_price"]=$balance_price_new;


            $this->startTrans();

//           dump( $balance_price);

            $this->data($save_data)->isUpdate(false)->save();

            $model_bank->save(["balance_price"=>$balance_price_new],["guid"=>$bank_id]);

            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            //   dump($e);
            Log::error($e->getMessage());
            return self::showReturnCodeWithOutData(1008,$e->getMessage());

        }
    }
    public function createNewBankRecord($bank_id){

        try{
            $this->noLoginThrowErr();
            $save_data = [
                "project_id" => "",
                "finance_mode" => 0, //创建资金
                "book_number" => 0,
                "bank_id" => $bank_id,
                "desc" => "创建新的资金帐户",
                "type" => 0,
                "expenditure_price"=>0,
                "income_price"=>0,
                "balance_price"=>0,
            ];
            $this->data($save_data)->isUpdate(false)->save();
            return true;
        }catch (Exception $e){
        //   dump($e);
            Log::error($e->getMessage());
            return false;
        }
    }

    public function bank(){
        return $this->hasOne("Bank","bank_id","guid");
    }
    protected function setGuidAttr($value, $data)
    {
        do {
            $mk_guid= $this->create_uuid();
        } while ($this->where('guid',$mk_guid)->count()==1);
        return $mk_guid ;
    }


    protected function setCompanyIdAttr()
    {
        return $this->member_info["company_id"];
    }

    protected function setUuidAttr(){
        return $this->uuid;
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
        try{
            $this->startTrans();
            $this->data($data)->isUpdate(false)->save();
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            Log::error($e->getMessage());
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }
    public function editData($guid){
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
            Log::error($e->getMessage());
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }

}