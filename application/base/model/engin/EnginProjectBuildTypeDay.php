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
use app\base\model\BaseHash;
use app\base\model\finance\BankTransactionRecord;
use app\base\model\finance\CollectPlanAccess;
use app\base\model\Material;
use app\base\model\Project;
use app\base\model\project\ProjectContract;
use think\Db;
use think\Exception;
use think\Loader;

class EnginProjectBuildTypeDay extends BaseHash
{

    protected $table = "mk_engin_project_build_type_day";
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
            $map =['status'=>1];
        }else{
            $map['status']=1;
        }
        if (empty($field)){
            $field = '';
        }
        return  $this->where($map)->field($field)->select();
    }


}