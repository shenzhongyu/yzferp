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

class MaterialDataStyle extends BaseHash
{

    protected $table = "mk_material_data_categories_style";
    protected $insert = ['status'=>1,'guid'];
    protected $autoWriteTimestamp = false;
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
            $map['status'] = 1 ;
        }
        if (empty($field)){
            $field = '';
        }
        return $this->where($map)->field($field)->select();
    }
    public function delDataStyle($id){
        if (empty($id)) return self::showReturnCodeWithOutData(1003);
        try{
            $this->startTrans();
            $this->data(['status'=>"-1"])->isUpdate(true,['guid'=>$id])->save();
            MaterialListEdit::update(['status'=>-1],['category_id'=>$id]);
            //下面的这句就报错
           //$this->table('mk_material_list_edit')->isUpdate(true,['category_id'=>$id])->data(['category_id'=>''])->save();
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){

            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }


    }

}