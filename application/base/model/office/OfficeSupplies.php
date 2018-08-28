<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/4/25
 * Time: 10:21
 */

namespace app\base\model\office;


use app\base\model\BaseHash;
use think\Exception;
use think\Session;

class OfficeSupplies extends BaseHash
{
    protected $table = "mk_office_supplies";
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
        }
        if (empty($field)){
            $field = '';
        }
        return $this->where($map)->field($field)->select();

    }

    public function saveData($data){
        if (empty($data)) return self::showReturnCode(1003);

        $re=$this->save($data);
        if ($re!=1){
            return self::showReturnCode(1003,'录入失败');
        }
        return self::showReturnCode(1001);
    }
    public function delCar($data){
        if (empty($data)) return self::showReturnCode(1003);
        $this->data(["status"=>-1])->isUpdate(true,["guid"=>$data])->save();
        return self::showReturnCode(1001);
    }

    public function editCar($data,$guid){
        if (empty($data)) return self::showReturnCode(1003);
        $re=$this->save($data,["guid"=>$guid]);
        if ($re!=1){
            return self::showReturnCode(1003,'修改失败');
        }
        return self::showReturnCode(1001);
    }

}