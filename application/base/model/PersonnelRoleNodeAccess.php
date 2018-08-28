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

class PersonnelRoleNodeAccess extends Base
{

    protected $table = "mk_personnel_role_node_access";
    protected $autoWriteTimestamp = false;



    public function saveRoleNodeAccess($rid="",$data="",$is_mobile=0){

        if (empty($rid)||empty($data)) return self::showReturnCodeWithOutData(1003);
        $arr_node = explode(",",$data);
        if (!is_array($arr_node)) return self::showReturnCodeWithOutData(1003);
        $count = $this->table('mk_personnel_node')->where(["guid"=>["in",$data]])->count();
        //判断post的node是否存在 并且数量相等
        if ($count!=count($arr_node)) return self::showReturnCodeWithOutData(1003);
        $is_mobile = empty($is_mobile) ? 0 : 1 ;
        try{
            $this->startTrans();
            //先删除node
            $this->where(['role_id'=>$rid,'is_mobile'=>$is_mobile])->delete();
            foreach($arr_node as $value){
                $this->insert(["role_id"=>$rid,"node_id"=>$value,'is_mobile'=>$is_mobile]);
            }
            $this->commit();
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1007,$e->getMessage());
        }
        return self::showReturnCode(1001);
    }

    public function getRoleMenuList($role_id,$is_mobile=0){
        $is_mobile = empty($is_mobile) ? 0 : 1 ;
        return $this->where(['role_id'=>$role_id,'is_mobile'=>$is_mobile])->column('node_id');

    }






}