<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/4/17
 * Time: 15:51
 */

namespace app\base\model;


use app\erp\config\FieldValue;
use think\Db;
use think\Exception;
use think\Loader;

class DesignBudget extends BaseDesign
{

    protected $table = "mk_budget_sheet";
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
    public function saveBudgetData($data){
        try{
            $this->startTrans();
            $this->data($data)->isUpdate(false)->save();
            $guid = $this->guid;
            if (empty($guid)) throw new Exception("空间添加失败");
            $list=Loader::model('base/MaterialTemplateBudgetAccess')->getList(['budget_id'=>$data['oldTemp_guid']]);
            if(!empty($list)){
                foreach ($list as $value){
                    $dataItem=$this->setCacheMaterialData()['listEdit'];
                    if(!isset($dataItem[$value['material_id']])){
                        throw new Exception("");
                    }
                    $obj=$dataItem[$value['material_id']];
                    $obj['material_id']=$obj['guid'];
                    $obj['budget_sheet_guid']=$guid;
                    $obj['project_id']=$data['project_guid'];
                    unset($obj['id']);
                    unset($obj['guid']);
                    $this->hasMany('DesignBudgetListAccess', 'budget_sheet_guid', 'guid')->save($obj);
                }
            }
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }
    public function delSpaceData($data){
        if(empty($data)) return self::showReturnCodeWithOutData(1003,'没有接收到需要删除的数据');
        try{
            $this->startTrans();
            $this->data(['status'=>-1])->isUpdate(true,['guid'=>["in",array_values($data)]])->save();
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();

            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }

    public function delMaterialData($data){
        if(empty($data)) return self::showReturnCodeWithOutData(1003,'没有接收到需要删除的数据');
        $arr_space=[];
        $arr_material=[];
        try{
            $this->startTrans();
            foreach ($data as $value){
                if($value['type']=='1'){
                    $arr_space[]=$value['id'];
                }else if($value['type']==2){
                    $arr_material[]=$value['id'];
                }
            }
            $this->data(['status'=>-1])->isUpdate(true,['guid'=>["in",array_values($arr_space)]])->save();
            Loader::model('base/DesignBudgetListAccess')->delSpaceData($arr_material);
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();

            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }

    //获取预算数据用来生成预算书
    public function getDataList($guid){
        $list=$this->getList(['project_guid'=>$guid]);
        $arr=[];
        $brr=[];
        $i=1;
        $direct_fee=0; //直接费
        $t=1;
        if (!empty($list)){
            foreach ($list as $value){
                $arr_f=[];
                $data=Loader::model('base/DesignBudgetListAccess')->getList(['budget_sheet_guid'=>$value['guid']]);
                $arr_f['title']=$i.'：'.$value['new_name'];
                $subtotal=0; //小计
                if (!empty($data)){
                    $arr_b=[];
                    foreach ($data as $item){
                        $arr_one=[];
                        $price_arr=$this->countPriceForArr($item);
                        $arr_one['id']=$i*10000+$t;
                        $t++;
                        $arr_one['name']=$item['name'];
                        $field=new FieldValue();
                        $arr_one['unit']=$field->unit_name[$item['unit']];
                        if($price_arr['code']=="1001"){
                            $arr_one['price_unit']=$price_arr['data']['price_unit'];
                            $arr_one['price_auxiliary']=$price_arr['data']['price_auxiliary'];
                            $arr_one['price_artificial']=$price_arr['data']['price_artificial'];
                            $arr_one['price_loss']=$price_arr['data']['price_loss'];
                            $arr_one['price_mechanical']=$price_arr['data']['price_mechanical'];
                            $arr_one['price']=$price_arr['data']['price_sum'];
                            $arr_one['price_sum']=$price_arr['data']['price_sum']*$item['number'];
                            $subtotal+=$arr_one['price_sum']; //金额累加
                        }
                        $arr_one['number']=empty($item['number']) ? 0 : $item['number'];
                        $arr_one['desc']=$item['desc'];
                        $arr_b[]=$arr_one;
                    }
                }
                $arr_f['children']=$arr_b;
                $arr_f['subtotal']=$subtotal;
                $arr_f['desc']=$value['budget_desc'];
                $i++;
                $arr[]=$arr_f;
                $direct_fee+=$subtotal;
            }
        }
        return ['top'=>$arr,'foot'=>$direct_fee];
    }


}