<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/4/25
 * Time: 10:21
 */

namespace app\base\model\work;


use app\base\model\BaseHash;

class WorkTarget extends  BaseHash
{
    protected $table = "mk_work_target";  //任务指标
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
        }
        if (empty($field)){
            $field = '';
        }
        return $this->where($map)->field($field)->select();

    }
    public function saveWorkTarget($data){
        if (empty($data)) return self::showReturnCodeWithOutData(1003);
        $arr=$data;
        unset($arr['work_number']);
        $obj=$this->where($arr)->find();
        if(empty($obj)){
            $re=$this->data($data)->isUpdate(false)->save();
        }else{
            $re=$this->data($data)->isUpdate(true,['guid'=>$obj['guid']])->save();
        }
        if ($re==1){
            return self::showReturnCodeWithOutData(1001);
        }else{
            return self::showReturnCodeWithOutData(1003);
        }
    }

}