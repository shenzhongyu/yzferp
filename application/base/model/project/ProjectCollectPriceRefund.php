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
use app\base\model\BaseHash;
use app\base\model\build\BuildSupervisionTem;
use app\base\model\finance\BankTransactionRecord;
use app\base\model\finance\CollectPlanAccess;
use app\base\model\finance\CollectStyle;
use app\base\model\Project;
use think\Db;
use think\Exception;
use think\exception\ErrorException;
use think\Loader;

class ProjectCollectPriceRefund extends BaseHash
{

    protected $table = "mk_project_collect_price_refund";
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
            $map =['status'=>1];
        }else{
            $map['status']=1;
        }
        if (empty($field)){
            $field = '';
        }
        return  $this->where($map)->field($field)->select();
    }

    public function payCollectPrice($data,$guid){
        if (empty($guid)){
            return self::showReturnCodeWithOutData(1003);
        }
        if (empty($data)){
            return self::showReturnCodeWithOutData(1003);
        }
        $obj=$this->where(['guid'=>$guid])->find();
        if (empty($obj)){
            return self::showReturnCodeWithOutData(1003,'该款项不存在，请刷新');
        }
        $price=$obj['collect_price'];
        if ($data['collect_price']>$obj['price']){
            return self::showReturnCodeWithOutData(1003,'退款资金不合法');
        }
        $data['collect_status']=1;
        $map=['guid'=>$guid,'status'=>1];
        try{
            $this->startTrans();
            if($obj['price']>$data['collect_price']){  //说明付款低于计划付款
                $obj=is_object($obj) ? $obj->toArray() : $obj;
                unset($obj['id']);
                unset($obj['guid']);
                unset($obj['create_time']);
                unset($obj['update_time']);
                $obj['collect_price']=$data['collect_price'];
                $obj['collect_name']=$obj['collect_name'].'('.$obj['number'].')';
                $obj['collect_status']=1;
                $arr=array_merge($obj,$data);
                $this->data($arr)->isUpdate(false)->save();
                $id=$this->id;
                $this->data(['number'=>date("Ymd",time()).'-'.$id])->isUpdate(true,['id'=>$id])->save();
                $re=$this->data(['price'=>$price-$data['collect_price']])->isUpdate(true,$map)->save();
            }else{
                $obj=is_object($obj) ? $obj->toArray() : $obj;
                unset($obj['id']);
                unset($obj['guid']);
                unset($obj['create_time']);
                unset($obj['update_time']);
                $obj['collect_price']=$data['collect_price'];
                $obj['collect_name']=$obj['collect_name'].'('.$obj['number'].')';
                $obj['collect_status']=1;
                $arr=array_merge($obj,$data);
                $this->data($arr)->isUpdate(false)->save();
                $id=$this->id;
                $this->data(['number'=>date("Ymd",time()).'-'.$id])->isUpdate(true,['id'=>$id])->save();
                $re=$this->data(['price'=>$price-$data['collect_price'],'status'=>2])->isUpdate(true,$map)->save();
            }
            if ($re!=1){
                $this->throwException("数据信息已更改，请重新刷新数据在操作");
            }

            //改变项目合同的实际支出
            $map=[
                'project_id'=>$obj['project_id'],
                'book_number'=>$obj['book_number']
            ];
            $pro_price=Loader::model('base/project/ProjectContract')->where($map)->value('pay_price');
            $pro_price+=$data['collect_price'];
            Loader::model('base/project/ProjectContract')->save(['pay_price'=>$pro_price],$map);
            $obj["project_name"] = Project::get(['guid'=>$obj["project_id"]])->getData("project_name");
            //记录资金账户流水
            $save_data = [
                "project_id" =>$obj['project_id'],
                "finance_mode" =>2,
                "book_number" => $obj["book_number"],
                "bank_id" => $data['bank_id'],
                "desc" =>$obj['project_name'].'项目的'.$obj['collect_type_name'].'的'.$obj['collect_name'].'退款',
                "type" =>4, //退款
                'price'=>$data['collect_price'],
            ];

            $model=new BankTransactionRecord();
            $re=$model->createBankTransactionRecord($save_data);
            if ($re['code']!="1001"){
                throw new Exception($re['msg']);
            }
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            // dump($e);
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }

    public function addPaymentPro($data,$guid){
        if (empty($data)) return self::showReturnCode(1003);
        $book_number=Loader::model('base/budget/BudgetBookNumber')->where(['project_id'=>$guid,'status'=>1])->value('book_number');
        $data['book_number']=$book_number;
        $model=new CollectStyle();
        $collect_name=$model->getInfoByGuId($data['collect_type']);
        //$collect_name=CollectStyle::quickGet($data['collect_type']);
        $data['collect_type_name']=$collect_name['collect_name'];
        $data['project_id']=$guid;
        $data['price']=$data['collect_price'];
        $data["collect_date"]=strtotime($data["collect_date"]);
        try{
            $this->startTrans();
            $this->data($data)->isUpdate(false)->save();
            $id=$this->id;
            $this->data(['number'=>date("Ymd",time()).'-'.$id])->isUpdate(true,['id'=>$id])->save();
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch  (Exception $e){
            // dump($e);
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }

//    public function addPaymentProPrice($data,$guid){
//        if (empty($data)) return self::showReturnCode(1003);
//        $collect_name=CollectStyle::quickGet($data['collect_type']);
//        $data['collect_type_name']=$collect_name['collect_name'];
//        $data['price']=$data['collect_price'];
//        $obj=Loader::model("base/build/BuildProjectPercentage")->where(['status'=>1,'project_id'=>$data['project_id'],'book_number'=>$data['book_number']])->find();
//        $data['old_guid']=empty($obj)?"":$obj['guid'];
//        $data['type']=1; //工程领款申请
//        $re=$this->where(['old_guid'=>empty($obj)?"":$obj['guid'],'type'=>1])->find();
//        if (!empty($re)){
//            return self::showReturnCode(1003,[],'已申请领款');
//        }
//        $cate=empty($obj)?"":$obj['category_id'];
//        //判断该工程属不属于他
//        $model=new BuildSupervisionTem();
//        $obj=$model->where(['project_id'=>$data['project_id'],'book_number'=>$data['book_number'], 'category_id'=>$cate,'user_id'=>$this->uuid,'status'=>1,'type'=>1])->find();
//        if (empty($obj)){
//            return self::showReturnCode(1003,[],'数据变动，请刷新数据');
//        }
//        try{
//            $this->startTrans();
//            $this->data($data)->isUpdate(false)->save();
//            $id=$this->id;
//            $this->data(['number'=>date("Ymd",time()).'-'.$id])->isUpdate(true,['id'=>$id])->save();
//            $this->commit();
//            return self::showReturnCodeWithOutData(1001);
//        }catch  (Exception $e){
//            // dump($e);
//            $this->rollback();
//            return self::showReturnCodeWithOutData(1008,$e->getMessage());
//        }
//    }
    public function delDate($guid){
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
    public function editCollect($data,$guid){
        if (empty($guid)){
            return self::showReturnCodeWithOutData(1003);
        }
        if (empty($data)){
            return self::showReturnCodeWithOutData(1003);
        }
        //修改清楚所有审核信息
        $data['price']=$data['collect_price']; //剩余金额
        $data['examine_status']=0;
        $data['examine_uuid']='';
        $data['examine_uuid_name']='';
        $data['collect_status']=0;
        $data['examine_desc']='';
        $data['collect_desc']='';
        $data['collected_uuid']='';
        $data['collected_uuid_name']='';
        $obj=$this->where(['guid'=>$guid])->find();
        if ($obj['collect_price']!=$obj['price']){
            return self::showReturnCodeWithOutData(1003,'该款项已收取部分款，无法进行修改');
        }

        try{
            $this->startTrans();
            $re=$this->data($data)->isUpdate(true,['guid'=>$guid,'status'=>1])->save();
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }
    public function editExamine($data,$guid){
        if (empty($data)){
            return self::showReturnCodeWithOutData(1003);
        }
        if (empty($guid)){
            return self::showReturnCodeWithOutData(1003);
        }
        try{
            $this->startTrans();
            $this->data($data)->isUpdate(true,['guid'=>$guid])->save();
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }
}