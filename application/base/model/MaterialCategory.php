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

class MaterialCategory extends BaseHash
{

    protected $table = "mk_material_category";
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
            $map = ['status'=>1];
        }else{
            $map['status'] = 1 ;
        }
        if (empty($field)){
            $field = '';
        }
        return $this->where($map)->field($field)->select();

    }
    public function getNodeListToTreeJson($where=[]){
        $where['status']=1;
        $list = Db::table('mk_material_category')->field('guid as id,pid,category_name as text')->where($where)->select();
        return  controller('base/Tree')->toTree($list,'id','pid','children');
    }
    public function getToTreeJson($where=[]){
        $where['status']=1;
        $list = Db::table('mk_material_category')->field('guid as id,pid,category_name as name')->where($where)->select();
        return  controller('base/Tree')->toTree($list,'id','pid','children');
    }
}