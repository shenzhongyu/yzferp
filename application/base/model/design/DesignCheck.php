<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/4/17
 * Time: 15:51
 */

namespace app\base\model\design;


use app\base\model\Base;
use think\Db;
use think\Exception;
use think\Loader;

class DesignCheck extends Base
{

    protected $table = "mk_design_sheet_check";
    protected $insert = ['status'=>1,'guid'];
    protected $autoWriteTimestamp = true;

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
    public function saveCheck($data){
        if (empty($data)) return self::showReturnCodeWithOutData(1003);
        $arr=[
            'company_id'=>$data['company_id'],
            'project_id'=>$data['pro_id'],
            'project_name'=>$data['project_name'],
            'sheet_id'=>$data['guid'],
            'apply_uuid'=>$data['uuid'],
            'apply_name'=>$data['name'],
            'apply_desc'=>$data['desc'],
        ];
        try{
            $this->startTrans();
            $this->data(['status'=>-1])->isUpdate(true,['sheet_id'=>$data['guid']])->save();
            $this->data($arr)->isUpdate(false)->save();
            Db::table("mk_design_sheet")->where(['guid'=>$data['guid']])->update(["completion_date"=>time()]);
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }
    public function editCheck($data,$guid){
        if (empty($data)) return self::showReturnCodeWithOutData(1003);
        $arr=[
            'check_uuid'=>$data['uuid'],
            'check_name'=>$data['name'],
            'check_desc'=>$data['examine_desc'],
            'check_status'=>$data['examine_status'],
            'status'=>-1,
        ];
        try{
            $this->startTrans();
            if($data['examine_status']=="1"){
                Db::table("mk_design_sheet")->where(['guid'=>$guid])->update(["status"=>2]);
            }
            $this->data($arr)->isUpdate(true,['sheet_id'=>$guid])->save();
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
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }
}