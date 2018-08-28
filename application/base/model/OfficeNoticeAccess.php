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
use think\Exception;

class OfficeNoticeAccess extends Base
{

    protected $table = "mk_office_notice_access";
    protected $autoWriteTimestamp = true;


    public function getList($map = [],$field=false){
        if (empty($map)){
            
        }else{
        }
        if (empty($field)){
            $field = '';
        }
        return $this->where($map)->field($field)->select();

    }





}