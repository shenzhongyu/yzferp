<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/4/25
 * Time: 10:21
 */

namespace app\base\model;


class OfficeLeave extends Base
{
    protected $table = "mk_office_leave";
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

    public function delData($data){
        $this->save(["status"=>-1],["guid"=>$data]);
        return self::showReturnCodeWithOutData(1001);
    }




}