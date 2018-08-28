<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/4/25
 * Time: 10:21
 */

namespace app\base\model;

use think\Db;


class WeMenuButton extends Base
{
    protected $table = "mk_we_menu_button";
    protected $insert = ['status'=>1,'guid'];
    protected $autoWriteTimestamp = false;

    protected function setGuidAttr($value, $data)
    {
        do {
            $mk_guid= $this->create_uuid();
        } while ($this->where('guid',$mk_guid)->count()==1);
        return $mk_guid ;
    }

    public function getNodeListToTreeJson($where=[]){

        $where['status']=1;
        $list = Db::table('mk_we_menu_button')->field('guid as id,pid,name as text')->where($where)->select();
        return  controller('base/Tree')->toTree($list,'id','pid','children');
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