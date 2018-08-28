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
use think\Loader;
use think\Route;
use think\Url;

class PersonnelNode extends Base
{

    protected $table = "mk_personnel_node";
    protected $insert = ['status'=>1,'guid'];
    protected $autoWriteTimestamp = true;

    protected function setGuidAttr($value, $data)
    {
        do {
            $mk_guid= $this->create_uuid();
        } while ($this->where('guid',$mk_guid)->count()==1);
        return $mk_guid ;
    }

    public function getNodeInfo($module,$control,$action){

        return $this->where([
            'module_name'=>$module,
            'control_name'=>$control,
            'action_name'=>$action,
        ])->find();
    }

    public function getNodeListToTreeJson($where=[],$is_mobile=0){
        $where['status']=1;
        $where['is_mobile']= empty($is_mobile)? 0 : 1 ;
        $list = Db::table('mk_personnel_node')->field('guid as id,pid,CONCAT(node_name,"  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(",IFNULL(node_desc,""),")")as text')->where($where)->select();
        return Loader::controller('base/Tree')->toTree($list,'id','pid','children') ;
    }

    public function getInputNodeListToTreeJson($where=[],$is_mobile=0){
        $where['status']=1;
        $where['is_mobile']= empty($is_mobile)? 0 : 1 ;
        $list = Db::table('mk_personnel_node')->field('guid as id,pid,CONCAT(node_name,"(",IFNULL(node_desc,""),")")as text')->where($where)->select();
        return Loader::controller('base/Tree')->toTree($list,'id','pid','children') ;
    }


    public function getUserMenuJson($role_id,$is_mobile=0){

        $is_mobile= empty($is_mobile)? 0 : 1 ;
        $arr_node =Db::table('mk_personnel_role_node_access')->where('role_id',$role_id)->column('node_id');
        $where=["guid"=>["in",$arr_node]];
        $list = Db::table('mk_personnel_node')->field("guid as menu_id,pid,node_name as menu_name,icon ,CASE action_name  when '#' then '' else   CONCAT(module_name ,'/',control_name , '\/' , action_name)  END as url")
            ->where(['status'=>1,'is_menu'=>1,"is_mobile"=>$is_mobile])->where($where)->select();

        $data=$list;
        foreach($list as $item=>$value){
            if(!empty($list[$item]['url'])){
                $data[$item]['url']=Url::build($value['url']);
            }
        }
        return  Loader::controller('base/Tree')->toTree($data,'menu_id','pid','menus');
    }

}