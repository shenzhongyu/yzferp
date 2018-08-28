<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/4/17
 * Time: 15:51
 */

namespace app\base\model\engin;


use app\base\model\Base;
use think\Db;
use think\Exception;
use think\Loader;

class MaterialAdressAccess extends Base
{

    protected $table = "mk_engin_material_address_access";
    protected $insert = ['status'=>1,];
    protected $autoWriteTimestamp = false;

    public function getList($map = [],$field=false){
        if (empty($map)){
            $map=['status'=>1];
        }else{
            $map['status']=1;
        }
        return $this->where($map)->field($field)->select();
    }


}