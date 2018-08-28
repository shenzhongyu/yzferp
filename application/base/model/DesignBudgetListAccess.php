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

class DesignBudgetListAccess extends BaseDesign
{

    protected $table = "mk_budget_sheet_list_access";
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
    public function getCont($map = []){
        return $this->where($map)->count();
    }
    public function delSpaceData($data){
        if(empty($data)) return self::showReturnCodeWithOutData(1003,'没有接收到需要删除的主材数据');
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

//    public function saveSpaceMaterial($data){
//        if (empty($data) || empty($data['budget_sheet_guid'] || empty($data['material_id'])))  return self::showReturnCodeWithOutData(1003,'没有接收到需要添加的主材数据');
//        $arr=$data['material_id'];
//        try{
//            if(is_array($arr)){
//                $this->startTrans();
//                if($data['type']==2){
//                    $list=$this->setCacheMaterialData()['material'];
//                    foreach ($arr as $value){
//                        $material_data=$list[$value];
//                        $save_data['budget_sheet_guid']=$data['budget_sheet_guid'];
//                        $save_data['material_id']=$value;
//                        $save_data['old_price']=$material_data['material_price'];
//                        $save_data['material_name']=$material_data['material_name'];
//                        $save_data['spec']=$material_data['specifications'];
//                        $save_data['desc']=$material_data['material_desc'];
//                        $save_data['units']=$material_data['unit_name'];
//                        $save_data['floor_price']=$material_data['floor_price'];
//                        $save_data['project_id']=$data['project_id'];
//                        $this->data($save_data)->isUpdate(false)->save();
//                    }
//                    $this->commit();
//                }else if($data['type']==1){
//                    $list=$this->setCacheMaterialData()['dataItem'];
//                    foreach ($arr as $value){
//                        $material_data=$list[$value];
//                        $save_data['budget_sheet_guid']=$data['budget_sheet_guid'];
//                        $save_data['material_id']=$value;
//                        $save_data['old_price']=$material_data['unit_price_sum'];
//                        $save_data['material_name']=$material_data['dataitem_name'];
//                        $save_data['spec']=$material_data['item_specifications'];
//                        $save_data['desc']=$material_data['item_desc'];
//                        $save_data['units']=$material_data['item_unit_name'];
//                        $save_data['floor_price']=$material_data['base_price'];
//                        $save_data['project_id']=$data['project_id'];
//                        $this->data($save_data)->isUpdate(false)->save();
//                    }
//                    $this->commit();
//                }else{
//                    return self::showReturnCodeWithOutData(1003,'数据传输错误，请联系管理员');
//                }
//
//            }
//            return self::showReturnCodeWithOutData(1001);
//        }catch (Exception $e){
//            $this->rollback();
//            return self::showReturnCodeWithOutData(1008,$e->getMessage());
//        }
//    }
    public function saveSpaceMaterial($data){
        if (empty($data) || empty($data['budget_sheet_guid'] || empty($data['material_id'])))  return self::showReturnCodeWithOutData(1003,'没有接收到需要添加的主材数据');
        $arr=$data['material_id'];
        try{
            if(is_array($arr)){
                $this->startTrans();
                foreach ($arr as $value){
                    $cont=$this->setCacheMaterialData()['budgetListAccess'];
                    $flag=false;
                    foreach ($cont as $item){
                        if ($item['budget_sheet_guid']==$data['budget_sheet_guid'] && $item['material_id']==$value && $item['project_id']==$data['project_id'] && $item['status']==1 ){
                            $flag=true;
                            break;
                        }
                    }

                    if($flag==true){
                        continue;
                    }
                    $dataItem=$this->setCacheMaterialData()['listEdit'];
                    $obj=$dataItem[$value];
                    $obj['material_id']=$obj['guid'];
                    $obj['budget_sheet_guid']=$data['budget_sheet_guid'];
                    $obj['project_id']=$data['project_id'];
                    unset($obj['id']);
                    unset($obj['guid']);
                   // dump($obj->toArray());
                    $obj =is_object($obj)?$obj->toArray():$obj;
                    $this->data($obj)->isUpdate(false)->save();
                }
                $this->commit();


            }
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }

    public function editData($data){
        try{
            if($data['type']==1){
                $this->data(['number'=>$data['number']])->isUpdate(true,['guid'=>$data['guid']])->save();
            }else{
                $this->data(['change_price'=>$data['change_price']])->isUpdate(true,['guid'=>$data['guid']])->save();
            }
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }

}