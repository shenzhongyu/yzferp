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
use app\base\model\Project;
use app\base\model\project\ProjectContract;
use app\base\model\ProjectContacts;
use think\Db;
use think\Exception;
use think\Loader;

class PrintRequest extends Base
{

    protected $table = "mk_budget_print_request";
    protected $insert = ['status'=>1,'guid'];
    protected $autoWriteTimestamp = true;

   //创建项目预算书
    public function buildBudgetBook($data,$id,$project_id){
        try{
            $this->startTrans();
            //修改审核记录
            if(!$this->data($data)->isUpdate(true,['guid'=>$id])->save()){
                throw new Exception("审核信息失败");
            }
            if($project_id!=$this->where(['guid'=>$id])->value("project_id")){
                throw new Exception("项目ID获取出错!");
            }
            //审核结果 1审核通过
            $m=isset($data['examine_status']) ? $data['examine_status'] : 0 ;
            if($m==1){
                $model_book_number= new BudgetBookNumber();
                $model_book_number->where(["project_id"=>$project_id,"status"=>1])->update(["status"=>2]);
                $model_book_number->save(["project_id"=>$project_id]);
                $book_number_id = $model_book_number->id;

                if(!is_numeric($book_number_id)){
                    throw new Exception("获取预算书ID失败");
                }
                //预算书编号
                $book_number = $book_number_id+100000;
                $model_book_number->save(["book_number"=>$book_number],["id"=>$book_number_id]);

                $list_default_book = Db::table("mk_budget_default_book")->where(["project_id"=>$project_id,"status"=>1])->select();
                if(count($list_default_book)!=1){
                    throw new Exception("项目书数量不是唯一");
                }
                $list_default_book=$list_default_book[0];
                $company_id  =$list_default_book["company_id"];
                unset($list_default_book["id"]);
                $list_default_book["book_number"]=$book_number;
                if(!isset(DefaultBookCopy::create($list_default_book)["id"])){
                    throw new Exception("出错了");
                }
                //插入装修项目信息
                $list_default_project = Db::table("mk_budget_default_project")->where(["project_id"=>$project_id,"status"=>1])->select();
                $append=["book_number"=>$book_number];
                $unset=["id"];
                //重整数组 追加 book_number 删除 id
               // $list_default_project=$this->changeArrayColumnData($list_default_project,$append,$unset);
                //修改 增加统计总金额

                $project_total_price=0;    //项目总价

                $re_array=$list_default_project;
                foreach($list_default_project as $item=>$value){
                    if (empty($user_uuid)){ //把项目预算的所属人获取
                        $user_uuid=$value['uuid'];
                    }
                    //追加值
                    if ($append){
                        foreach($append as $item_append=>$value_append){
                            //   echo "append [$item_append]为$value_append";
                            $re_array[$item][$item_append]=$value_append;
                        }
                    }
                    //剔除值
                    if ($unset){
                        foreach($unset as $v){
                            //echo "del {$item} $v";
                            unset($re_array[$item][$v]);
                        }
                    }
                    if(is_numeric($value["number"])&&is_numeric($value["price"])){
                        $project_total_price += $value["number"]*$value["price"];
                    }
                }
                $list_default_project = $re_array;

                //项目价格
                $project_price =$project_total_price;
                $insert_count= count($list_default_project);
                $model_project =new DefaultProjectCopy();
                //超过100条 分批插入
                if ($insert_count>0 && $insert_count<100){
                    $model_project->insertAll($list_default_project);
                }elseif($insert_count>100){
                    $insert_batch= ceil($insert_count/100);
                    for( $i=0 ;$i<$insert_batch;$i++ ){
                        $model_project->insertAll(array_slice($list_default_project, $i*100,100));
                    }
                }
                //插入装修费率信息
                $list_default_rate = Db::table("mk_budget_default_rate")->where(["project_id"=>$project_id,"status"=>1])->select();
                $append=["book_number"=>$book_number];
                $unset=["id"];
                //重整数组 追加 book_number 删除 id
               // $list_default_rate=$this->changeArrayColumnData($list_default_rate,$append,$unset);

                $re_array=$list_default_rate;
                foreach($list_default_rate as $item=>$value){
                    //追加值
                    if ($append){
                        foreach($append as $item_append=>$value_append){
                            //   echo "append [$item_append]为$value_append";
                            $re_array[$item][$item_append]=$value_append;
                        }
                    }
                    //剔除值
                    if ($unset){
                        foreach($unset as $v){
                            //echo "del {$item} $v";
                            unset($re_array[$item][$v]);
                        }
                    }
                    if(in_array($value["rate_default_type"],["*","+","/","-"])&&is_numeric($value["rate_default_value"])){
                        $re_array[$item]["project_rate_price"] = eval("return $project_price{$value['rate_default_type']}{$value['rate_default_value']}; ");
                        $project_total_price += $re_array[$item]["project_rate_price"];
                    }
                }
                $list_default_rate = $re_array;

                $model_rate =new DefaultRateCopy();
                $model_rate->insertAll($list_default_rate) ;

                //项目报价书更新项目报价及总价格
                if($model_book_number->save(["project_price"=> $project_price ,"total_price"=>$project_total_price],["id"=>$book_number_id]) < 0 ){
                    throw new Exception ("项目报价书更新项目报价及总价格失败");
                }
                ;

                //创建合同书
                //排除同状态的项目书
                $model_project_contract = new ProjectContract();
                $model_project_contract->where(["project_id"=>$project_id,"book_number"=>$book_number ,"status"=>1])->update(["status"=>2]);
                $pro_name=Loader::model("base/Project")->where(['guid'=>$project_id])->value('project_name');
                //查询已付的定金金额
                $price_list=Loader::model('base/project/ProjectDeposit')->getList(['project_id'=>$project_id]);
                $sum_price=0;
                if (!empty($price_list)){
                    foreach ($price_list as $value){
                        $sum_price+=$value['collected_price'];
                    }
                }
                $project_contract_data =[
                    "company_id"=>$company_id,
                    "project_id"=>$project_id,
                    "project_name"=>$pro_name,
                    "book_number"=>$book_number ,
                    "project_amount"=>floatval($project_price) ,
                    "contract_amount"=>floatval($project_total_price) ,
                    'price'=>$sum_price, //查询是否已付定金
                    'uuid'=>$user_uuid,
                    "status"=>1
                ];
                //项目只出现一个待签的
                Db::table("mk_project_contract")->where(['project_id'=>$project_id])->update(['status'=>2]);
          //      $model_project_contract->save(['status'=>2],['project_id'=>$project_id]);
                if($model_project_contract->save($project_contract_data)!=1){
                    throw new Exception("新增项目书失败");
                }
                ;
            }
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }


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
    public function getListNoStatus($map = [],$field=false){
        if (empty($map)){
            $map = ['status'=>1];
        }
        if (empty($field)){
            $field = '';
        }
        return $this->where($map)->field($field)->select();
    }
    public function getCont($where){
        $where['status']=1;
        return $this->where($where)->count();
    }
    public function savaData($data,$pro_id,$uuid,$request_uuid){
        $model=new Project();
        $pro_name=$model->getInfoByGuId($pro_id);
//        $pro_name=Project::quickGet($pro_id);
        $arr=[
            'project_id'=>$pro_id,
            'uuid'=>$uuid,
            'uuid_name'=>$request_uuid,
            'request_desc'=>$data['desc'],
            'examine_status'=>0,
            'company_id'=>$data['company_id'],
            'project_name'=>empty($pro_name)? '' :$pro_name['project_name']
        ];
        $obj=$this->where(['project_id'=>$pro_id,'examine_status'=>0,'status'=>1])->find();
        if(!empty($obj)){
            return self::showReturnCodeWithOutData(1011,'已申请预算打印，请勿重复申请');
        }
        try{
            $this->startTrans();
            $this->data(['status'=>-1])->isUpdate(true,['project_id'=>$pro_id])->save();
            $this->data($arr)->isUpdate(false)->save();
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }





    public function editSaveData($data,$id){
        try{
            $this->startTrans();
            $this->data($data)->isUpdate(true,['guid'=>$id])->save();
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }
    public function delData($pro_id){
        try{
            $this->startTrans();
            $this->data(['status'=>-1])->isUpdate(true,['project_id'=>$pro_id])->save();
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }
}