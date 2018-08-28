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

class PersonnelLogRecord extends Base
{
    protected $table = 'mk_personnel_log_record';
    protected $insert = ['status'=>1,];
    protected $autoWriteTimestamp = true;
    public function infoByMsgId($key, $field = true){
        if (!$key) return false;
        $map['MsgId'] = $key;
        return $this->field($field)->where($map)->find();
    }


    protected function setGuidAttr($value, $data)
    {
        do {
            $mk_guid= $this->create_uuid();
        } while ($this->where('id',$mk_guid)->count()==1);
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




}