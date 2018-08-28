<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/4/25
 * Time: 10:21
 */

namespace app\base\model;


class WeFans extends Base
{
    protected $table = "mk_we_fans";
    protected $insert = ['status'=>1,'id'];
    protected $autoWriteTimestamp = true;

    protected function setGuidAttr($value, $data)
    {
        do {
            $mk_guid= $this->create_uuid();
        } while ($this->where('id',$mk_guid)->count()==1);
        return $mk_guid ;
    }



    public function infoToArray($openid,$field=true){
        if (is_numeric($openid)){
            $map['id']=$openid;
        }else{
            $map['openid']=$openid;
        }
        $fans = $this->field($field)->where($map)->find();
        return  $fans;
    }

    public function infoByOpenId($openid,$field=true){
        if (is_numeric($openid)){
            $map['id']=$openid;
        }else{
            $map['openid']=$openid;
        }
        $info = $this->field($field)->where($map)->find();
        return  $info;
    }





    public function getList($map = [],$field=false){
        if (empty($map)){
            $map = ['status'=>1];
        }
        if (empty($field)){
            $field = 'id as id,fans_nickname as text';
        }
        return $this->where($map)->field($field)->select();

    }





}