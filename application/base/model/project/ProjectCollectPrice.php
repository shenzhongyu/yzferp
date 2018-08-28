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
use app\base\model\finance\CollectPlanAccess;
use app\base\model\Project;
use think\Db;
use think\Exception;
use think\Loader;

class ProjectCollectPrice extends Base
{

    protected $table = "mk_project_collect_price";
    protected $insert = ['status'=>1,'guid'];
    protected $autoWriteTimestamp = true;
    protected function setGuidAttr($value, $data)
    {
        do {
            $mk_guid= $this->create_uuid();
        } while ($this->where('guid',$mk_guid)->count()==1);
        return $mk_guid ;
    }
    public function savePlanData($data){
        if (empty($data)){
            return self::showReturnCodeWithOutData(1003);
        }
        $model=new CollectPlanAccess();
        $list=$model->getList(['collect_plan_id'=>$data['collect_plan_id']]);
        $userAll=$this->setCacheSystem();
        $data['cashier_uuid_name']=$userAll['user'][$data['cashier_uuid']]['name'];
        $data['collect_type_name']=Loader::model('base/finance/CollectStyle')->where(['guid'=>$data['collect_type']])->value('collect_name');
        $obj_list=Loader::model("base/project/ProjectDeposit")->getList(['project_id'=>$data['project_id']]);
        $actual_price=0.00;
        if (!empty($obj_list)) {
            foreach ($obj_list as $value) {
                $actual_price+= $value['collected_price'];
            }
        }

        unset($data['collect_plan_id']);
        $sum_price=$data['collect_price'];
        try{
            $this->startTrans();
            if (!empty($list)){
                foreach ($list as $key=>$value){
                    $arr=[];
                    $date=0;
                    $arr=$data;
                    $date=strtotime($data['collect_date'])+$value['collect_days']*60*60*24;
                    $arr['collect_date']=date('Y-m-d',$date);
                    $data['collect_date']=date('Y-m-d',$date);
                    if($key==0){
                        $arr['collect_name']=$value['name'].'(已付定金)';
                        $arr['collect_price']=$value['collect_value']*$sum_price-$actual_price;
                        $arr['price']=$value['collect_value']*$sum_price-$actual_price;

                    }else{
                        $arr['collect_name']=$value['name'];
                        $arr['collect_price']=$value['collect_value']*$sum_price;
                        $arr['price']=$value['collect_value']*$sum_price;

                    }


                    $this->data($arr)->isUpdate(false)->save();
                    $id=$this->id;
                    $this->data(['number'=>date("Ymd",time()).'-'.$id])->isUpdate(true,['id'=>$id])->save();
                }
            }else{
                $this->throwException("该计划下没有内容,无法添加收款");
            }
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
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
    public function saveData($data,$pro_id){
        if (empty($data)){
            return self::showReturnCodeWithOutData(1003);
        }
        if (empty($pro_id)){
            return self::showReturnCodeWithOutData(1003);
        }
        $userAll=$this->setCacheSystem();
        $data['cashier_uuid_name']=$userAll['user'][$data['cashier_uuid']]['name'];
        $data['collect_type_name']=Loader::model('base/finance/CollectStyle')->where(['guid'=>$data['collect_type']])->value('collect_name');
        $data['price']=$data['collect_price']; //剩余金额
        try{
            $this->startTrans();
            $this->data($data)->isUpdate(false)->save();
            $id=$this->id;
            $this->data(['number'=>date("Ymd",time()).'-'.$id])->isUpdate(true,['id'=>$id])->save();
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }
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

    //（有变动(2017-11-24 改 ) 原版在下面 ）
    public function addCollectPrice($data,$guid){
        if (empty($guid)){
            return self::showReturnCodeWithOutData(1003);
        }
        if (empty($data)){
            return self::showReturnCodeWithOutData(1003);
        }
        $obj=$this->where(['guid'=>$guid])->find();
//        halt($obj);
        if (empty($obj)){
            return self::showReturnCodeWithOutData(1003,'该款项不存在，请刷新');
        }
        $price=$obj['collect_price'];
        if ($data['collect_price']>$obj['price']){
            return self::showReturnCodeWithOutData(1003,'收款资金不合法');
        }
        $data['collect_status']=1;
        $map=['guid'=>$guid];

        try{
            $this->startTrans();

            $obj=is_object($obj) ? $obj->toArray() : $obj;
            unset($obj['id']);
            unset($obj['guid']);
            unset($obj['create_time']);
            unset($obj['update_time']);
            $obj['collect_price']=$data['collect_price'];
            $obj['collect_name']=$obj['collect_name'];
            $obj['collect_status']=1;
            $arr=array_merge($obj,$data);
            $this->data($arr)->isUpdate(false)->save();
            $re=$this->data(['price'=>$price-$data['collect_price'],'status'=>2])->isUpdate(true,$map)->save();
            if ($re!=1){
                $this->throwException("数据信息已更改，请重新刷新数据在操作");
            }

            //改变项目合同合同的实际收入
            $map=[
                'project_id'=>$obj['project_id'],
                'book_number'=>$obj['book_number']
            ];
            $pro_price=Loader::model('base/project/ProjectContract')->where($map)->value('price');
            $pro_price+=$data['collect_price'];
            Loader::model('base/project/ProjectContract')->save(['price'=>$pro_price],$map);
            $obj["project_name"] = Project::get(['guid'=>$obj["project_id"]])->getData("project_name");
            //记录资金账户流水
            $save_data = [
                "project_id" =>$obj['project_id'],
                "finance_mode" =>1,
                "book_number" => $obj["book_number"],
                "bank_id" => $data['bank_id'],
                "desc" =>$obj['project_name'].'项目的'.$obj['collect_name'].'收入',
                "type" =>2, //工程款
                'price'=>$data['collect_price'],
            ];
            $model=new BankTransactionRecord();
            $re=$model->createBankTransactionRecord($save_data);
            if ($re['code']!="1001"){
                throw new Exception($re['msg']);
            }

            //判断该项目的收入了首付款
//            $pro_obj=Loader::model('base/project/ProjectContract')->where($map)->find();
//            $pro_obj=Loader::model('base/Project')->where(['guid'=>$obj['project_id']])->find();

//            if ($pro_obj['feedback_stage']!=3 && $obj['collect_name']=="首付款"){
                $project=new Project();
                $project->isUpdate(true,['guid'=>$obj['project_id']])->save(['feedback_stage'=>3]);
//                Loader::model('base/Project')->data(['feedback_stage'=>3])->isUpdate(true,['guid'=>$obj['project_id']])->save();
//            }

            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
           // dump($e);
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }
//
//    public function addCollectPrice($data,$guid){
//        if (empty($guid)){
//            return self::showReturnCodeWithOutData(1003);
//        }
//        if (empty($data)){
//            return self::showReturnCodeWithOutData(1003);
//        }
//        $obj=$this->where(['guid'=>$guid])->find();
////        halt($obj);
//        if (empty($obj)){
//            return self::showReturnCodeWithOutData(1003,'该款项不存在，请刷新');
//        }
//        $price=$obj['collect_price'];
//        if ($data['collect_price']>$obj['price']){
//            return self::showReturnCodeWithOutData(1003,'收款资金不合法');
//        }
//        $data['collect_status']=1;
//        $map=['guid'=>$guid];
//
//        try{
//            $this->startTrans();
//            if($obj['price']>$data['collect_price']){  //说明收款低于计划付款
//                $obj=is_object($obj) ? $obj->toArray() : $obj;
//                unset($obj['id']);
//                unset($obj['guid']);
//                unset($obj['create_time']);
//                unset($obj['update_time']);
//                $obj['collect_price']=$data['collect_price'];
//                $obj['collect_name']=$obj['collect_name'].'('.$obj['number'].')';
//                $obj['collect_status']=1;
//                $arr=array_merge($obj,$data);
//                $this->data($arr)->isUpdate(false)->save();
//                $id=$this->id;
//                $this->data(['number'=>date("Ymd",time()).'-'.$id])->isUpdate(true,['id'=>$id])->save();
//                $re=$this->data(['price'=>$price-$data['collect_price']])->isUpdate(true,$map)->save();
//            }else{
//                $obj=is_object($obj) ? $obj->toArray() : $obj;
//                unset($obj['id']);
//                unset($obj['guid']);
//                unset($obj['create_time']);
//                unset($obj['update_time']);
//                $obj['collect_price']=$data['collect_price'];
//                $obj['collect_name']=$obj['collect_name'].'('.$obj['number'].')';
//                $obj['collect_status']=1;
//                $arr=array_merge($obj,$data);
//                $this->data($arr)->isUpdate(false)->save();
//                $id=$this->id;
//                $this->data(['number'=>date("Ymd",time()).'-'.$id])->isUpdate(true,['id'=>$id])->save();
//                $re=$this->data(['price'=>$price-$data['collect_price'],'status'=>2])->isUpdate(true,$map)->save();
//            }
//            if ($re!=1){
//                $this->throwException("数据信息已更改，请重新刷新数据在操作");
//            }
//
//            //改变项目合同合同的实际收入
//            $map=[
//                'project_id'=>$obj['project_id'],
//                'book_number'=>$obj['book_number']
//            ];
//            $pro_price=Loader::model('base/project/ProjectContract')->where($map)->value('price');
//            $pro_price+=$data['collect_price'];
//            Loader::model('base/project/ProjectContract')->save(['price'=>$pro_price],$map);
//            $obj["project_name"] = Project::get(['guid'=>$obj["project_id"]])->getData("project_name");
//            //记录资金账户流水
//            $save_data = [
//                "project_id" =>$obj['project_id'],
//                "finance_mode" =>1,
//                "book_number" => $obj["book_number"],
//                "bank_id" => $data['bank_id'],
//                "desc" =>$obj['project_name'].'项目的'.$obj['collect_type_name'].'的'.$obj['collect_name'].'收入',
//                "type" =>2, //工程款
//                'price'=>$data['collect_price'],
//            ];
//            $model=new BankTransactionRecord();
//            $re=$model->createBankTransactionRecord($save_data);
//            if ($re['code']!="1001"){
//                throw new Exception($re['msg']);
//            }
//
//            //判断该项目的收入了首付款
////            $pro_obj=Loader::model('base/project/ProjectContract')->where($map)->find();
////            $pro_obj=Loader::model('base/Project')->where(['guid'=>$obj['project_id']])->find();
//
////            if ($pro_obj['feedback_stage']!=3 && $obj['collect_name']=="首付款"){
//            $project=new Project();
//            $project->isUpdate(true,['guid'=>$obj['project_id']])->save(['feedback_stage'=>3]);
////                Loader::model('base/Project')->data(['feedback_stage'=>3])->isUpdate(true,['guid'=>$obj['project_id']])->save();
////            }
//
//
//
//
//
//
//
//            $this->commit();
//            return self::showReturnCodeWithOutData(1001);
//        }catch (Exception $e){
//            // dump($e);
//            $this->rollback();
//            return self::showReturnCodeWithOutData(1008,$e->getMessage());
//        }
//    }
    public function editExamine($data,$guid){
        if (empty($data)){
            return self::showReturnCodeWithOutData(1003);
        }
        if (empty($guid)){
            return self::showReturnCodeWithOutData(1003);
        }
        $userAll=$this->setCacheSystem();
        $data['cashier_uuid_name']=$userAll['user'][$data['cashier_uuid']]['name'];
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
    public function back($data,$guid){
        if (empty($guid)){
            return self::showReturnCodeWithOutData(1003);
        }
        if (empty($data)){
            return self::showReturnCodeWithOutData(1003);
        }
        unset($data['id']);
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


//    public function editSaveData($data,$number,$guid){
//        if (empty($data)){
//            return self::showReturnCodeWithOutData(1003);
//        }
//        if (empty($guid)){
//            return self::showReturnCodeWithOutData(1003);
//        }
//        if (empty($number)){
//            return self::showReturnCodeWithOutData(1003);
//        }
//        try{
//            $this->startTrans();
//            $re=$this->data($data)->isUpdate(true,['project_id'=>$guid,'book_number'=>$number,'status'=>1])->save();
//            if($re!=1){
//                throw new Exception("签订合同审核出现错误");
//            }
//            if($data['examine_status']!=1){
//                $this->data(['status'=>-1])->isUpdate(true,['project_id'=>$guid,'book_number'=>$number])->save();
//            }
//            $this->commit();
//            return self::showReturnCodeWithOutData(1001);
//        }catch (Exception $e){
//            $this->rollback();
//            return self::showReturnCodeWithOutData(1008,$e->getMessage());
//        }
//    }


      //收款信息
        public function showCollectRemind($limit="1",$rows="20"){
            $list=Db::table("mk_project_collect_price")
                ->where('cashier_uuid',$this->uuid)
                ->where('status','1')
                ->where('collect_status','0')
                ->whereTime('collect_date', 'd')
                ->field('project_id,collect_date,collect_price,guid')
                ->limit($rows)
                ->page($limit)
                ->select();
            $list=$this->getTime($list,'collect_date',false);
            $model=new Project();
            if (!empty($list)){
                foreach ($list as $key=>$value){
                    $pro=$model->getInfoByGuId($value['project_id']);
                    //$pro=Project::quickGet($value['project_id']);
                    $list[$key]['project_name']=$pro?$pro['project_name']:'';
                }
            }
            return ['total'=>count($list),'rows'=>$list];
        }

}