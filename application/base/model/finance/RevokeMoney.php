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
use app\base\model\Project;
use app\base\model\project\ProjectCollectPrice;
use think\Db;
use think\Exception;
use think\Loader;

class RevokeMoney extends BaseHash
{

    protected $table = "mk_revoke_money";
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
            $map['status']=1;
        }
        if (empty($field)){
            $field = '';
        }
        return  $this->where($map)->field($field)->select();
    }
    public function saveApplyData($data){
        if (empty($data)){
            return self::showReturnCodeWithOutData(1003);
        }
        $obj=$this->where(['collect_id'=>$data['collect_id'],'status'=>1,'examine_status'=>0])->find();
        if (!empty($obj)){
            return self::showReturnCodeWithOutData(1001,'该款项已申请撤销，无需重复申请');
        }
        $model=new ProjectCollectPrice();
        $pro=$model->getInfoByGuId($data["collect_id"]);
        if (empty($pro)) return self::showReturnCodeWithOutData(1001,"该款项不存在，请重新申请");
        $pro_name=Db::table("mk_project")->where(["guid"=>$pro["project_id"]])->value("project_name");
        $data["project_name"]=$pro_name;
        $data["examine_status"]=0;
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
    public function editApply($data,$guid){
        if (empty($data)) return self::showReturnCode(1003);
        if (empty($guid)) return self::showReturnCode(1003);
        if (!in_array($data['examine_status'],['-1','0','1'])){
            return self::showReturnCode(1003);
        };
        $obj=$this->where(['guid'=>$guid])->find();
        if (empty($obj))return  self::showReturnCode(1003);
        $pro=Db::table("mk_project_collect_price")->where(["guid"=>$obj['collect_id']])->find();
        $collect_model=new ProjectCollectPrice();
        try{
            $this->startTrans();
            $re=$this->data($data)->isUpdate(true,['guid'=>$guid,'examine_status'=>'0'])->save();
            if ($re!=1){
                $this->throwException("审核失败，该审核信息已变动");
            }
            if($data['examine_status']=="-1"){
                $this->data(['status'=>-1])->isUpdate(true,['guid'=>$guid])->save();
            }
            if($data['examine_status']=="1"){
                //修改已收款的数据状态(状态为3)
                $collect_model->save(["status"=>3],["guid"=>$obj['collect_id']]);
                //改变项目合同的实际收入
                $map=[
                    'project_id'=>$pro['project_id'],
                    'book_number'=>$pro['book_number']
                ];
                $pro_price=Loader::model('base/project/ProjectContract')->where($map)->value('price');
                $pro_price-=$pro['collect_price'];
                Loader::model('base/project/ProjectContract')->save(['price'=>$pro_price],$map);
                $pro["project_name"] =Db::table("mk_project")->where(['guid'=>$pro['project_id']])->value("project_name");
                //记录资金账户流水
                $save_data = [
                    "project_id" =>$pro['project_id'],
                    "finance_mode" =>2,
                    "book_number" => $pro["book_number"],
                    "bank_id" => $pro['bank_id'],
                    "desc" =>$pro['project_name'].'项目的'.'的'.$pro['collect_name'].'收款撤销',
                    "type" =>5, //收款撤销
                    'price'=>$pro['collect_price'],
                ];
                $model=new BankTransactionRecord();
                $re=$model->createBankTransactionRecord($save_data);
                if ($re['code']!="1001"){
                    throw new Exception($re['msg']);
                }
            }
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }

}