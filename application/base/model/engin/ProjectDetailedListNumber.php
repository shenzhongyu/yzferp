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

class ProjectDetailedListNumber extends Base
{

    protected $table = "mk_engin_project_detailed_list_number";
    protected $insert = ['status'=>1,];
    protected $autoWriteTimestamp = true;


    public function buildBudgetBook($project_id){
        if(empty($project_id)) return false;

    }
    public function getList($map = [],$field=false){
        if (empty($map)){
            $map=['status'=>1];
        }else{
            $map['status']=1;
        }
        $this->where($map)->field($field)->find();
    }


}