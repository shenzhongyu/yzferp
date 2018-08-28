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
use think\Db;
use think\Exception;
use think\Loader;

class BudgetTem extends Base
{

    protected $table = "mk_budget_space_style";
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
    public function saveSpace($data,$pro_id){
        if (empty($data)) return self::showReturnCodeWithOutData(1003);
        try{
            $this->startTrans();
            $this->data($data)->isUpdate(false)->save();
            $guid=$this->guid;
            $model=new SpaceTemplateUser();
            $req=$model->saveTem($guid,$pro_id);
            if ($req['code']!="1001"){
                throw new Exception($req['msg']);
            }
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }
    public function delTem($data){
        if (empty($data)) return self::showReturnCodeWithOutData(1003);
        try{
            $this->startTrans();
            $this->data(['status'=>-1])->isUpdate(true,['guid'=>$data["id"]])->save();
            $model =new SpaceTemplateUser();
            $model->data(['status'=>-1])->isUpdate(true,['space_id'=>$data["id"]])->save();
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }
}