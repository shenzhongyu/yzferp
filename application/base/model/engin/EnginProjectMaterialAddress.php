<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/4/17
 * Time: 15:51
 */

namespace app\base\model\engin;


use app\base\model\Base;
use app\base\model\project\ProjectCollectPricePay;
use think\Db;
use think\Exception;
use think\Loader;

class EnginProjectMaterialAddress extends Base
{

    protected $table = "mk_engin_project_material_address";
    protected $insert = ['status'=>1,'guid'];
    protected $autoWriteTimestamp = true;

    protected function setGuidAttr($value, $data)
    {
        do {
            $mk_guid= $this->create_uuid();
        } while ($this->where('guid',$mk_guid)->count()==1);
        return $mk_guid ;
    }

    public function getList($map = [],$field=false,$build_sql=false){
        if (empty($map)){
            $map=['status'=>1];
        }elseif(!isset($map['status'])){
            $map['status']=1;
        }
        if (empty($field)){
            $field='*';
        }
       return $build_sql ? $this->where($map)->field($field)->buildSql() : $this->where($map)->field($field) ->select();
    }
    public function saveData($data,$com){
        if (empty($data)) return self::showReturnCode(1003);
        if (empty($data['material_arr'])) return self::showReturnCode(1003);
        if (!is_array($data['material_arr'])) return self::showReturnCode(1003);
        $arr=$data['material_arr'];
        $uer=$this->setCacheSystem()['user'];
        $user_name=$uer[$data['consignee']]['name'];
        $data['consignee_name']=$user_name;
        $data['collect_date']=strtotime($data['collect_date']);
        $data['company_id']=$com;
        unset($data['material_arr']);
        $model=new EnginProjectMaterialCopy();
        $model_access=new MaterialAdressAccess();
        $sum_price=0; //单据的总金额
        foreach ($arr as $item=>$value){
            $price=$model->where(['guid'=>$value['material_id']])->value('floor_price');
            $arr[$item]['price']=$price*$value['number']; //计算购买材料的价格
            $sum_price+=$price*$value['number'];
        }
        $data['price']=$sum_price;
        $data['collect_type_name']=Loader::model('base/finance/CollectStyle')->getInfoByGuid($data['collect_type'])['collect_name'];
        try{
            $this->startTrans();
            $this->data($data)->isUpdate(false)->save();
            $guid=$this->guid;
            $id=$this->id;
            $this->data(['dj_number'=>date("Ymd",time()).'--'.$id])->isUpdate(true,['guid'=>$guid   ])->save();
            foreach ($arr as $value){
                $value['receipt_id']=$guid;
                $no_number=$model->where(['guid'=>$value['material_id']])->value('nobuy_number');
                $model->data(['nobuy_number'=>$value['number']+$no_number])->isUpdate(true,['guid'=>$value['material_id']])->save();
                $model_access->data($value)->isUpdate(false)->save();
            }
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }

    public function editBuyMaterial($data,$guid){
        if (empty($data)) return self::showReturnCode(1003);
        if (empty($guid)) return self::showReturnCode(1003);
        $list=Loader::model('base/engin/MaterialAdressAccess')->getList(['receipt_id'=>$guid]);
        $model=new EnginProjectMaterialCopy();
        try{
            $this->startTrans();
            $re=$this->data($data)->isUpdate(true,['guid'=>$guid,'examine_status'=>0])->save();
            if ($re!=1){
                $this->throwException("该审核信息已发生变动，请刷新数据");
            }
            if($data['examine_status']==-1){
                $this->data(['status'=>-1])->isUpdate(true,['guid'=>$guid])->save();
                if (!empty($list)){
                    //审核不通过减去当前申请的购买数量
                    foreach ($list as $value){
                        $no_number=$model->where(['guid'=>$value['material_id']])->value('nobuy_number');
                        $model->data(['nobuy_number'=>$no_number-$value['number']])->isUpdate(true,['guid'=>$value['material_id']])->save();
                    }
                }
            }else if ($data['examine_status']==1){
                $obj=$this->getInfoByGuId($guid);
                $this->data(['status'=>2])->isUpdate(true,['guid'=>$guid])->save();
                //审核通过添加一个付款计划
                $arr=[
                    'company_id'=>$obj['company_id'],
                    'old_guid'=>$obj['guid'],
                    'book_number'=>$obj['number'],
                    'collect_uuid'=>$obj['apply_uuid'], //款项申请人
                    'collect_uuid_name'=>$obj['apply_name'],
                    'project_id'=>$obj['project_id'],
                    'collect_type'=>$obj['collect_type'], //付款类型
                    'collect_type_name'=>$obj['collect_type_name'],
                    'number'=>$obj['dj_number'],
                    'collect_price'=>$obj['price'], //付款金额
                    'price'=>$obj['price'],
                    'collect_name'=>$obj['collect_name'],
                    'invoice_value'=>$obj['invoice_value'],
                    'invoice_price'=>$obj['invoice_price'],
                    'desc'=>$obj['desc'],
                    'examine_status'=>$obj['examine_status'],
                    'examine_uuid'=>$obj['examine_uuid'],
                    'examine_uuid_name'=>$obj['examine_uuid_name'],
                    'examine_desc'=>$obj['examine_desc'],
                ];
                Loader::model('base/project/ProjectCollectPricePay')->data($arr)->isUpdate(false)->save();
            }else{
                $this->throwException("审核出现错误，请联系管理员");
            }
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }
    //采购验收
    public function editAcceMaterial($data,$guid){
        if (empty($data)) return self::showReturnCode(1003);
        if (empty($guid)) return self::showReturnCode(1003);
        $data['accepatance_status']=1;
        $model=new ProjectCollectPricePay();
        $list=Loader::model('base/engin/MaterialAdressAccess')->getList(['receipt_id'=>$guid]);
        $model_copy=new EnginProjectMaterialCopy();
        try{
            $this->startTrans();
            $re=$this->data($data)->isUpdate(true,['guid'=>$guid,'accepatance_status'=>0])->save();
            if ($re!=1){
                $this->throwException("该验收信息已发生变动，请刷新数据");
            }
            $model->data($data)->isUpdate(true,['old_guid'=>$guid,'accepatance_status'=>0])->save();
            if (!empty($list)){
                //验收后减去当前申请的购买数量,添加已经购买数量
                foreach ($list as $value){
                    $obj=$model_copy->where(['guid'=>$value['material_id']])->find();
                    if (empty($obj)) {
                        $this->throwException("err! empty !");
                    }
                    $model_copy->data(['nobuy_number'=>$obj['nobuy_number']-$value['number'],'buy_number'=>$obj['buy_number']+$value['number']])->isUpdate(true,['guid'=>$value['material_id']])->save();
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